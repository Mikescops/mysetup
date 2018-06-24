<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;

/**
 * Requests Controller
 *
 * @property \App\Model\Table\RequestsTable $Requests
 *
 * @method \App\Model\Entity\Request[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RequestsController extends AppController
{
    public function requestOwnership($id = null)
    {
        if($this->request->is('post') and $id != null)
        {
            $user = $this->Auth->user();
            $setup = $this->Requests->Setups->get($id, [
                'contain' => [
                    'Users' => [
                        'fields' => [
                            'id',
                            'mail'
                        ]
                    ]
                ]
            ]);

            if($setup->user_id != $user->id and !$this->Requests->exists(['user_id' => $user->id, 'setup_id' => $setup->id]))
            {
                $request = $this->Requests->newEntity([
                    'token'      => Security::randomString(),
                    'user_id'    => $user->id,
                    'setup_id'   => $setup->id
                ]);

                if($this->Requests->save($request))
                {
                    $email = $this->Requests->Users->getEmailObject($setup->user->mail, $user->name . ' has claimed your setup !');
                    $email->setTemplate('ownership')
                          ->setViewVars(['setup_id' => $setup->id, 'setup_title' => $setup->title, 'owner_name' => $setup->user->name, 'requester_id' => $user->id, 'requester_name' => $user->name, 'requester_mail' => $user->mail, 'token' => $request->token])
                          ->send();

                    $this->Flash->success(__('Your request has just been sent. Let\'s wait for the owner\'s approval for now.'));
                }

                else
                {
                    $this->Flash->error(__('An error occurred while saving your request.'));
                }
            }

            else
            {
                $this->Flash->warning(__('No, no. This is impossible.'));
            }

            return $this->redirect(['controller' => 'Setups', 'action' => 'view', $id]);
        }
    }

    public function answerOwnership($id = null, $token = null, $response = null)
    {
        if($this->request->is('get'))
        {
            $request = $this->Requests->find()->where(['setup_id' => $id, 'token' => $token])->first();

            // If this request exists...
            if($request)
            {
                // ... and the response is YES...
                if($response)
                {
                    // ... let's change the ownership of this setup !

                    /* First, we fetch the concerned entities */
                    // The very setup
                    $setup = $this->Requests->Setups->get($request->setup_id);
                    // Its actual owner
                    $old_owner = $this->Requests->Users->get($setup->user_id);
                    // The user who will gain ownership about it
                    $new_owner = $this->Requests->Users->get($request->user_id);

                    // Let's change the setup attributes to reflect this change...
                    $setup->author  = $new_owner->name;
                    $setup->user_id = $new_owner->id;

                    // ... and save everything (the request is deleted only if the changes have been saved !).
                    if(!$this->Requests->Setups->save($setup) || !$this->Requests->delete($request))
                    {
                        $this->Flash->error(__('An error occurred while processing your answer.'));
                    }

                    else
                    {
                        $this->Flash->success(__('Your voice has been heard !'));

                        // The setup has been updated, let's now change the resources user IDs and move the images to the new owner's directory
                        $this->Requests->Setups->Resources->changeSetupsResourcesOwner($setup->id, $old_owner->id, $setup->user_id, $this->Flash);

                        // Argh, a case is missing : The new owner didn't have any setup.
                        // This new one will become its default one ;)
                        if($new_owner->mainSetup_id == 0)
                        {
                            $new_owner->mainSetup_id = $setup->id;

                            $new_owner->setDirty('modificationDate', true);
                            $this->Requests->Users->save($new_owner);

                            // If the new owner is the current user (another case is not very likely, but who knows ?)
                            if($this->Auth->user('id') == $new_owner->id)
                            {
                                $this->Requests->Users->synchronizeSessionWithUserEntity($this->request->getSession(), $new_owner, parent::isAdmin($new_owner));
                            }
                        }

                        // If the same setup was the main one of the previous owner, let's affect him one other (or none)
                        if($old_owner->mainSetup_id == $setup->id)
                        {
                            $newMainSetup = $this->Requests->Setups->find('all', [
                                'fields' => [
                                    'id'
                                ],
                                'conditions' => [
                                    'user_id' => $old_owner->id
                                ],
                                'order' => [
                                    'creationDate' => 'DESC'
                                ],
                                'limit' => 1
                            ])->first();
                            $old_owner->mainSetup_id = ($newMainSetup ? $newMainSetup->id : 0);

                            $old_owner->setDirty('modificationDate', true);
                            $this->Requests->Users->save($old_owner);
                        }
                    }
                }

                else
                {
                    // else if, let's just delete the request in our DB
                    if($this->Requests->delete($request))
                    {
                        $this->Flash->success(__('Your voice has been heard !'));
                    }

                    else
                    {
                        $this->Flash->error(__('An error occurred while processing your answer.'));
                    }
                }
            }

            else
            {
                $this->Flash->error(__('This request is invalid.'));
            }

            return $this->redirect('/');
        }
    }

    public function requestReport($id = null)
    {
        if($this->request->is('post') and $id != null)
        {
            $user = $this->Requests->Users->get($this->Auth->user('id'));
            $setup = $this->Requests->Setups->get($id);

            if($setup->user_id != $user->id)
            {
                $email = $this->Requests->Users->getEmailObject('report@mysetup.co', 'A setup has been flagged !');
                $email->setTemplate('report')
                      ->setViewVars(['setup_id' => $setup->id, 'flagger_id' => $user->id, 'flagger_name' => $user->name, 'flagger_mail' => $user->mail])
                      ->send();

                $this->Flash->success(__('Your request has just been sent, we may contact you in the future.'));
            }

            else
            {
                $this->Flash->warning(__('No, no. This is impossible.'));
            }

            return $this->redirect(['controller' => 'Setups', 'action' => 'view', $id]);
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow('answerOwnership');
    }

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            if(in_array($this->request->getParam('action'), ['requestOwnership', 'requestReport']))
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
