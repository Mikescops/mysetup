<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

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
                $request = $this->Requests->newEntity();
                $request->token    = $this->Requests->Users->getRandomString();
                $request->user_id  = $user->id;
                $request->setup_id = $setup->id;

                if($this->Requests->save($request))
                {
                    $email = $this->Requests->Users->getEmailObject($setup->user->mail, $user->name . ' has claimed your setup !');
                    $email->setTemplate('ownership')
                          ->viewVars(['setup_id' => $setup->id, 'setup_title' => $setup->title, 'owner_name' => $setup->user->name, 'requester_id' => $user->id, 'requester_name' => $user->name, 'requester_mail' => $user->mail, 'token' => $request->token])
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
                    // ... let's change the ownership of this setup
                    $setup = $this->Requests->Setups->get($request->setup_id);
                    $old_user_id = $setup->user_id;
                    $setup->author  = $this->Requests->Users->get($request->user_id)['name'];
                    $setup->user_id = $request->user_id;

                    if(!$this->Requests->Setups->save($setup))
                    {
                        $this->Flash->error(__('An error occurred while processing your answer.'));
                    }

                    else
                    {
                        // The setup has been updated, let's now move the images to the new owner's directory
                        $this->Requests->Setups->Resources->changeSetupsImagesOwner($setup->id, $old_user_id, $setup->user_id);

                        $this->Flash->success(__('Your voice has been heard !'));

                        if(!$this->Requests->delete($request))
                        {
                            $this->Flash->warning(__('Your request couldn\'t be deleted as well.'));
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

            if($setup['user_id'] != $user['id'])
            {
                $email = $this->Requests->Users->getEmailObject('report@mysetup.co', 'A setup has been flagged !');
                $email->setTemplate('report')
                      ->viewVars(['setup_id' => $setup->id, 'flagger_id' => $user->id, 'flagger_name' => $user->name, 'flagger_mail' => $user->mail])
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
            if(in_array($this->request->action, ['requestOwnership', 'requestReport']))
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
