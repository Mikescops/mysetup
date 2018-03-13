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
                        'modificationDate',
                        'mainSetup_id'
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
            'products' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_PRODUCT'])->toArray(),
            'featured_image' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_FEATURED_IMAGE'])->first()['src'],
            'gallery_images' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_GALLERY_IMAGE'])->toArray(),
            'video_link' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_VIDEO_LINK'])->first()['src']
        ];
        // ___________________________________________________________________________________________

        $this->set('setup', $setup);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if($this->request->is('post'))
        {
            // Let's get the data from the form
            $data = $this->request->getData();

            // Let's set the id of the current logged in user
            $data['user_id'] = $this->Auth->user('id');

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

            // Regular entity patching operation
            $setup = $this->Setups->patchEntity($this->Setups->newEntity(), $data);

            // Here we'll assign a random id to this new setup
            do {
                $setup->id = mt_rand() + 1;
            } while($this->Setups->find()->where(['id' => $setup->id])->count() !== 0);

            // Fix for previous versions of MySQL
            $setup->main_colors = '';

            if($this->Setups->save($setup))
            {
                /* Here we get and save the featured image */
                if(!isset($data['featuredImage']) or $data['featuredImage']['tmp_name'] === '' or !$this->Setups->Resources->saveResourceImage($data['featuredImage'], $setup, 'SETUP_FEATURED_IMAGE', $this->Flash, $data['user_id'], false, true))
                {
                    $this->Setups->delete($setup);
                    $this->Flash->warning(__('You need a featured image with this setup !'));
                    return $this->redirect($this->referer());
                }

                // The featured image has been created, let's extract its main colors
                $setup->main_colors = $this->Setups->Resources->extractMostUsedColorsFromImage(
                    $this->Setups->Resources->find()->where(['setup_id' => $setup->id, 'type' => 'SETUP_FEATURED_IMAGE'])->first()['src']
                );
                $this->Setups->save($setup);

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

                    $this->Setups->Users->synchronizeSessionWithUserEntity($this->request->session(), $user);
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

                        // The featured image has changed, let's re-compute the main used colors !
                        $setup->main_colors = $this->Setups->Resources->extractMostUsedColorsFromImage(
                            $this->Setups->Resources->find()->where(['setup_id' => $setup->id, 'type' => 'SETUP_FEATURED_IMAGE'])->first()['src']
                        );
                        $this->Setups->save($setup);
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
        if($this->Setups->delete($setup))
        {
            // Force user session updating (`mainSetup_id` has changed)
            $this->Setups->Users->synchronizeSessionWithUserEntity($this->request->session());

            $this->Flash->success(__('The setup has been deleted.'));
        }
        else
        {
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

        $this->Auth->allow(['search', 'view', 'getSetups']);
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

            else if($this->request->action === 'add')
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
}
