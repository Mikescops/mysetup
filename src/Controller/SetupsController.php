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
        $session = $this->request->getSession();
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

            //var_dump(json_decode($data['featuredImage'][0])->output);
            //die();

            // Before anything else, let's check whether or not this new setup has a featured image and at least one resource !
            if((!isset($data['featuredImage']) or json_decode($data['featuredImage'][0])->output->name === '') or (!isset($data['resources']) or $data['resources'] === ''))
            {
                $this->Flash->error(__('It looks like you missed something...'));
                return $this->redirect($this->referer());
            }

            // Here we fetch the user entity, 'cause we'll need it later
            $user = $this->Setups->Users->get($this->Auth->user('id'));

            // Let's set the setup owner !
            $data['user_id'] = $user->id;

            // Here we'll assign automatically the owner name of the setup
            if(!isset($data['author']) or $data['author'] === '')
            {
                $data['author'] = $user->name;
            }


            // We'll only allow `PUBLISHED` and `DRAFT` status on Setups.add
            if(!isset($data['status']) or !in_array($data['status'], ['PUBLISHED', 'DRAFT']))
            {
                // If this dude tried to play us, he'll receive a little gift :
                $data['status'] = 'REJECTED';
            }

            // Regular entity patching operation
            $setup = $this->Setups->patchEntity($this->Setups->newEntity(), $data);

            // Here we'll assign a random id to this new setup
            do {
                $setup->id = mt_rand() + 1;
            } while($this->Setups->find()->where(['id' => $setup->id])->count() !== 0);

            // Set some default values for Setups.add
            $setup->featured    = false;
            $setup->main_colors = '';

            // So, before registering Resources entities, we need to save the setup in the DB, for foreign-dependency reasons
            if($this->Setups->save($setup))
            {
                /* Here we get and save the featured image */
                if(!$this->Setups->Resources->saveResourceImage((array) json_decode($data['featuredImage'][0])->output, $setup, 'SETUP_FEATURED_IMAGE', $this->Flash))
                {
                    $this->Setups->delete($setup);
                    $this->Flash->warning(__('Your featured image could not be saved, and it is needed for your setup...'));
                    return $this->redirect($this->referer());
                }

                // The featured image has been created, let's extract and save its main colors
                $setup->main_colors = $this->Setups->Resources->extractMostUsedColorsFromImage(
                    $this->Setups->Resources->find()->where(['setup_id' => $setup->id, 'type' => 'SETUP_FEATURED_IMAGE'])->first()['src']
                );
                $this->Setups->save($setup);

                /* Let's save the gallery images with the adapted function */
                $this->Setups->Resources->saveGalleryImages($setup, $data, $this->Flash);

                /* Here we save each product that has been selected by the user */
                $this->Setups->Resources->saveResourceProducts($data['resources'], $setup, $this->Flash, parent::isAdminBySession($this->request->getSession()));

                /* Here we save the setup video URL (if it exists) */
                if(isset($data['video']) and $data['video'] !== '')
                {
                    // We ignore the return of this method, 'cause its failing is not relevant here
                    $this->Setups->Resources->saveResourceVideo($data['video'], $setup, 'SETUP_VIDEO_LINK', $this->Flash);
                }

                // User's main Setup feature : If this user does not have currently any, let's assign this new one
                if($user->mainSetup_id == 0)
                {
                    $user->mainSetup_id = $setup->id;
                    $user->setDirty('modificationDate', true);
                    $this->Setups->Users->save($user);
                    $this->Setups->Users->synchronizeSessionWithUserEntity($this->request->getSession(), $user, parent::isAdmin($user));
                }
                // _______________________________________________________________________________________________

                $this->Flash->success(__('The setup has been saved.'));
                return $this->redirect(['action' => 'view', $setup->id]);
            }

            $this->Flash->error(__('The setup could not be saved. Please, try again.'));
            return $this->redirect($this->referer());
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Setup id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setup = $this->Setups->get($id);

        if($this->request->is(['patch', 'post', 'put']))
        {
            // Let's fetch the form's data
            $data = $this->request->getData();

            // Here we'll assign automatically the owner name if the user has removed the old one
            if(!isset($data['author']) or $data['author'] === '')
            {
                $data['author'] = $this->Setups->Users->get($setup->user_id)['name'];
            }

            // A regular user should have the right to submit its setups with PUBLISHED and DRAFT status values
            if(!isset($data['status']) or
               (!in_array($data['status'], ['PUBLISHED', 'DRAFT']) && !parent::isAdminBySession($this->request->getSession())))
            {
                $data['status'] = $setup['status'];
            }

            // Only administrators can change the featured aspect of a setup
            if(!isset($data['featured']) or !parent::isAdminBySession($this->request->getSession()))
            {
                $data['featured'] = $setup['featured'];
            }

            $setup = $this->Setups->patchEntity($setup, $data);

            if($this->Setups->save($setup))
            {
                /* Here we delete all products then save again each product that has been selected by the user */
                $this->Setups->Resources->deleteAll(['Resources.user_id' => $setup->user_id, 'Resources.setup_id' => $id, 'Resources.type' => 'SETUP_PRODUCT']);
                $this->Setups->Resources->saveResourceProducts($data['resources'], $setup, $this->Flash, parent::isAdminBySession($this->request->getSession()));

                /* Here we get and save the featured image */
                if(!empty($data['featuredImage'][0]))
                {
                    // We fetch the CURRENT featured image, so as to delete it afterwards
                    $image_to_delete = $this->Setups->Resources->find()->where([
                        'Resources.user_id'  => $setup->user_id,
                        'Resources.setup_id' => $id,
                        'Resources.type'     => 'SETUP_FEATURED_IMAGE'
                    ])->first();

                    // We try to save the new image chosen by the user !
                    if($this->Setups->Resources->saveResourceImage((array) json_decode($data['featuredImage'][0])->output, $setup, 'SETUP_FEATURED_IMAGE', $this->Flash))
                    {
                        // If it's OK, we just delete the old one, and re-compute the main colors !
                        $this->Setups->Resources->delete($image_to_delete);

                        $setup->main_colors = $this->Setups->Resources->extractMostUsedColorsFromImage(
                            $this->Setups->Resources->find()->where([
                                'setup_id' => $setup->id,
                                'type'     => 'SETUP_FEATURED_IMAGE'
                            ])->first()['src']
                        );
                        $this->Setups->save($setup);
                    }
                }

                // Replace the gallery images (handle in the Resources model)
                $this->Setups->Resources->saveGalleryImages($setup, $data, $this->Flash);

                /* Here we handle video link modification */
                // First, we retrieve the current, if any
                $video_to_delete = $this->Setups->Resources->find()->where([
                    'setup_id' => $setup->id,
                    'type'     => 'SETUP_VIDEO_LINK'
                ])->first();
                // If the user has specified a new one...
                if(isset($data['video']) and $data['video'] !== '')
                {
                    // ... that is equal to the old one
                    if($video_to_delete && $video_to_delete->src === $data['video'])
                    {
                        // We won't delete it !
                        $video_to_delete = null;
                    }
                    else
                    {
                        // ... if not, we save this new link (the old one will be delete just below)
                        if(!$this->Setups->Resources->saveResourceVideo($data['video'], $setup, 'SETUP_VIDEO_LINK', $this->Flash))
                        {
                            // If we are here, the NEW link could not be saved...
                            // Let's be kind and don't delete the old one ;)
                            $video_to_delete = null;
                        }
                    }
                }
                // We delete the old one (if we still have to) !
                if($video_to_delete !== null)
                {
                    $this->Setups->Resources->delete($video_to_delete);
                }
                /* ______________________________________ */

                $this->Flash->success(__('The setup has been updated.'));
            }

            else
            {
                $this->Flash->error(__('The setup could not be saved. Please, try again.'));
            }

            return $this->redirect(['action' => 'view', $setup->id]);
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
            // Force user session updating (`mainSetup_id` may have changed)
            if($this->Auth->user('id') == $setup->user_id)
            {
                $this->Setups->Users->synchronizeSessionWithUserEntity($this->request->getSession(), null, parent::isAdmin($this->Auth->user()));
            }

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

        return $this->redirect($this->referer());
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['search', 'view']);
    }

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            if(in_array($this->request->getParam('action'), ['edit', 'delete']))
            {
                if($this->Setups->isOwnedBy((int)$this->request->getAttribute('params')['pass'][0], $user['id']))
                {
                    return true;
                }
            }

            elseif($this->request->getParam('action') === 'add')
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
