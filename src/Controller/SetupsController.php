<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Response;

/**
 * Setups Controller
 *
 * @property \App\Model\Table\SetupsTable $Setups
 */
class SetupsController extends AppController
{

    /**
     * View method
     *
     * @param string|null $id Setup id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // This should be below, but we wanna throw a 404 on the production if the user tries to have access to a non-existing setup...
        $setup = $this->Setups->get($id, [
            'contain' => [
                'Users' => [
                    'fields' => [
                        'id',
                        'name',
                        'verified',
                        'modificationDate'
                    ]
                ],
                'Comments' => [
                    'Users'
                ]
            ]
        ]);

        // The 'view' action will be authorized, unless the setup is not PUBLISHED and the visitor is not its owner, nor an administrator...
        $session = $this->request->session();
        if(!$this->Setups->isPublic($id) and (!$session->check('Auth.User') or !$this->Setups->isOwnedBy($id, $session->read('Auth.User.id'))) and !parent::isAdminBySession($session))
        {
            $this->Flash->error(__('You are not authorized to access that location.'));
            return $this->redirect('/');
        }
        // _________________________________________________________________________________________________________________________________

        // Here we'll get each resource linked to this setup, and set them up into the existing entity
        $setup['resources'] = [
            'products' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_PRODUCT'])->all()->toArray(),
            'featured_image' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_FEATURED_IMAGE'])->first()['src'],
            'gallery_images' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_GALLERY_IMAGE'])->all()->toArray(),
            'video_link' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_VIDEO_LINK'])->first()['src']
        ];
        // ___________________________________________________________________________________________

        // A new entity if the current visitor wanna post a comment
        $newComment = $this->Setups->Comments->newEntity();
        // ________________________________________________________

        $this->set(compact('setup', 'newComment'));
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $setup = $this->Setups->newEntity();

        if($this->request->is('post'))
        {
            // Let's get the data from the form
            $data = $this->request->getData();

            // Let's set the id of the current logged in user
            $data['user_id'] = $this->request->session()->read('Auth.User.id');

            // Here we fetch the user entity, 'cause we'll need it later
            $user = $this->Setups->Users->get($data['user_id']);

            // Here we'll assign automatically the owner of the setup to the entity
            if(!isset($data['author']) or $data['author'] === '')
            {
                $data['author'] = $user->name;
            }

            // See the `edit` method for the explanations of the below statements
            if(!isset($data['status']) or ($data['status'] !== 'PUBLISHED' and $data['status'] !== 'DRAFT' and !parent::isAdminBySession($this->request->session())))
            {
                $data['status'] = 'PUBLISHED';
            }

            // On Setups.add, `featured` is impossible
            $data['featured'] = false;

            // Classical patch entity operation
            $setup = $this->Setups->patchEntity($setup, $data);

            // Here we'll assign a random id to this new setup
            do {
                $setup->id = mt_rand() + 1;
            } while($this->Setups->find()->where(['id' => $setup->id])->count() !== 0);

            if($this->Setups->save($setup))
            {
                /* Here we get and save the featured image */
                if(!isset($data['featuredImage']) or $data['featuredImage']['tmp_name'] === '' or !$this->Setups->Resources->saveResourceImage($data['featuredImage'], $setup, 'SETUP_FEATURED_IMAGE', $this->Flash, $data['user_id'], false, true))
                {
                    $this->Setups->delete($setup);
                    $this->Flash->warning(__('You need a featured image with this setup !'));
                    return $this->redirect($this->referer());
                }

                /* Let's save the gallery images with the adapted function */
                $this->Setups->Resources->saveGalleryImages($setup, $data, $this->Flash);

                /* Here we save each product that has been selected by the user */
                $this->Setups->Resources->saveResourceProducts($data['resources'], $setup, $this->Flash, $data['user_id'], false, parent::isAdminBySession($this->request->session()));

                /* Here we save the setup video URL */
                if(isset($data['video']) and $data['video'] !== '')
                {
                    $this->Setups->Resources->saveResourceVideo($data['video'], $setup, 'SETUP_VIDEO_LINK', $this->Flash, $data['user_id'], false);
                }

                // User's main Setup feature : If this user does not have currently any, let's assign this new one
                if(!$user->mainSetup_id)
                {
                    $user->mainSetup_id = $setup->id;

                    $user->setDirty('modificationDate', true);
                    $this->Setups->Users->save($user);
                }
                // _______________________________________________________________________________________________

                $this->Flash->success(__('The setup has been saved.'));
                return $this->redirect(['action' => 'view', $setup->id]);
            }

            else
            {
                $this->Flash->error(__('The setup could not be saved. Please, try again.'));
                return $this->redirect($this->referer());
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Setup id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setup = $this->Setups->get($id);

        if($this->request->is(['patch', 'post', 'put']))
        {
            // Let's fetch the form's data
            $data = $this->request->getData();

            // Here we'll assign automatically the owned of the setup to the entity, if in the setup it has not be filled
            if(!isset($data['author']) or $data['author'] === '')
            {
                $data['author'] = $this->Setups->Users->get($setup->user_id)['name'];
            }

            // A regular user should have the right to submit its setups with PUBLISHED and DRAFT status values
            if(!isset($data['status']) or ($data['status'] !== 'PUBLISHED' and $data['status'] !== 'DRAFT' and !parent::isAdminBySession($this->request->session())))
            {
                $data['status'] = 'PUBLISHED';
            }

            if(!isset($data['featured']) or !parent::isAdminBySession($this->request->session()))
            {
                $data['featured'] = $setup['featured'];
            }

            $setup = $this->Setups->patchEntity($setup, $data);

            if($this->Setups->save($setup))
            {
                /* Here we delete all products then save each product that has been selected by the user */
                $this->Setups->Resources->deleteAll(['Resources.user_id' => $setup->user_id, 'Resources.setup_id' => $id, 'Resources.type' => 'SETUP_PRODUCT']);
                $this->Setups->Resources->saveResourceProducts($data['resources'], $setup, $this->Flash, $setup->user_id, true, parent::isAdminBySession($this->request->session()));

                /* Here we get and save the featured image */
                if(isset($data['featuredImage']) and $data['featuredImage'] !== '' and (int)$data['featuredImage']['error'] === 0)
                {
                    $image_to_delete = $this->Setups->Resources->find()->where(['Resources.user_id' => $setup->user_id, 'Resources.setup_id' => $id, 'Resources.type' => 'SETUP_FEATURED_IMAGE'])->first();
                    if($this->Setups->Resources->saveResourceImage($data['featuredImage'], $setup, 'SETUP_FEATURED_IMAGE', $this->Flash, $setup->user_id, true, true))
                    {
                        $this->Setups->Resources->delete($image_to_delete);
                    }
                }

                $this->Setups->Resources->saveGalleryImages($setup, $data, $this->Flash);

                /* Here we save the setup video URL */
                if(isset($data['video']) and $data['video'] !== '')
                {
                    // Here we get the current video link, if present
                    $video_to_delete = $this->Setups->Resources->find()->where(['setup_id' => $setup->id, 'type' => 'SETUP_VIDEO_LINK'])->first();

                    if($video_to_delete && $video_to_delete !== $data['video'])
                    {
                        $this->Setups->Resources->delete($video_to_delete);
                    }

                    $this->Setups->Resources->saveResourceVideo($data['video'], $setup, 'SETUP_VIDEO_LINK', $this->Flash, $setup->user_id, true);
                }

                $this->Flash->success(__('The setup has been updated.'));
            }

            else
            {
                $this->Flash->error(__('The setup could not be saved. Please, try again.'));
            }

            return $this->redirect($this->referer());
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Setup id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setup = $this->Setups->get($id);
        if ($this->Setups->delete($setup)) {
            $this->Flash->success(__('The setup has been deleted.'));
        } else {
            $this->Flash->error(__('The setup could not be deleted. Please, try again.'));
        }

        if(strpos($this->referer(), $id))
        {
            return $this->redirect('/');
        }

        else
        {
            return $this->redirect($this->referer());
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['search', 'view', 'answerOwnership', 'getSetups']);
    }

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            if(in_array($this->request->action, ['edit', 'delete']))
            {
                if($this->Setups->isOwnedBy((int)$this->request->getAttribute('params')['pass'][0], $user['id']))
                {
                    return true;
                }
            }

            else if(in_array($this->request->action, ['add', 'requestOwnership', 'requestReport']))
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

    public function getSetups()
    {
        if($this->request->is('ajax') or $this->request->is('get'))
        {
            $results = $this->Setups->getSetups([
                'query' => $this->request->getQuery('q'),
                'featured' => $this->request->getQuery('f'),
                'order' => $this->request->getQuery('o'),
                'number' => $this->request->getQuery('n'),
                'offset' => $this->request->getQuery('p'),
                'type' => $this->request->getQuery('t'),
                'weeks' => $this->request->getQuery('w')
            ]);

            return new Response([
                'status' => 200,
                'type' => 'json',
                'body' => json_encode($results)
            ]);
        }
    }

    public function search()
    {
        if($this->request->getQuery('q'))
        {
            $results = $this->Setups->getSetups([
                'query' => $this->request->getQuery('q'),
                'number' => 9999
            ]);

            if(count($results) == 0)
            {
                $results = 'noresult';
            }
        }

        else
        {
            $results = 'noquery';
        }

        $this->set('results', $results);
    }

    public function requestOwnership($id = null)
    {
        if($this->request->is('post') and $id != null)
        {
            $user = $this->Setups->Users->get($this->request->session()->read('Auth.User.id'));
            $setup = $this->Setups->get($id, [
                'contain' => [
                    'Users' => [
                        'fields' => [
                            'id',
                            'mail'
                        ]
                    ]
                ]
            ]);

            if($setup->user_id != $user->id and !$this->Setups->Requests->exists(['user_id' => $user->id, 'setup_id' => $setup->id]))
            {
                $request = $this->Setups->Requests->newEntity();
                $request->token    = $this->Setups->Users->getRandomString();
                $request->user_id  = $user->id;
                $request->setup_id = $setup->id;

                if($this->Setups->Requests->save($request))
                {
                    $email = $this->Setups->Users->getEmailObject($setup->user->mail, $user->name . ' has claimed your setup !');
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

            return $this->redirect(['action' => 'view', $id]);
        }
    }

    public function answerOwnership($id = null, $token = null, $response = null)
    {
        if($this->request->is('get'))
        {
            $request = $this->Setups->Requests->find()->where(['setup_id' => $id, 'token' => $token])->first();

            // If this request exists...
            if($request)
            {
                // ... and the response is YES...
                if($response)
                {
                    // ... let's change the ownership on this setup
                    $setup = $this->Setups->get($request->setup_id);
                    $setup->author  = $this->Setups->Users->get($request->user_id)['name'];
                    $setup->user_id = $request->user_id;

                    if(!$this->Setups->save($setup))
                    {
                        $this->Flash->error(__('An error occurred while processing your answer.'));
                    }

                    else
                    {
                        $this->Flash->success(__('Your voice has been heard !'));

                        if(!$this->Setups->Requests->delete($request))
                        {
                            $this->Flash->warning(__('Your request couldn\'t be deleted as well.'));
                        }
                    }
                }

                else
                {
                    // else if, let's just delete the request in our DB
                    if($this->Setups->Requests->delete($request))
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
            $user = $this->Setups->Users->get($this->request->session()->read('Auth.User.id'));
            $setup = $this->Setups->get($id);

            if($setup['user_id'] != $user['id'])
            {
                $email = $this->Setups->Users->getEmailObject('report@mysetup.co', 'A setup has been flagged !');
                $email->setTemplate('report')
                      ->viewVars(['setup_id' => $setup->id, 'flagger_id' => $user->id, 'flagger_name' => $user->name, 'flagger_mail' => $user->mail])
                      ->send();

                $this->Flash->success(__('Your request has just been sent, we may contact you in the future.'));
            }

            else
            {
                $this->Flash->warning(__('No, no. This is impossible.'));
            }

            return $this->redirect(['action' => 'view', $id]);
        }
    }
}
