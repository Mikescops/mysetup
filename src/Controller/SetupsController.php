<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;

/**
 * Setups Controller
 *
 * @property \App\Model\Table\SetupsTable $Setups
 */
class SetupsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $setups = $this->paginate($this->Setups);

        $this->set(compact('setups'));
        $this->set('_serialize', ['setups']);
    }

    /*Add markdown support*/
    public $helpers = ['Tanuck/Markdown.Markdown' => ['parser' => 'GithubMarkdown']];

    /**
     * View method
     *
     * @param string|null $id Setup id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setup = $this->Setups->get($id, [
            'contain' => ['Users', 'Resources', 'Comments']
        ]);

        // List of products that we have to send to the View
        $products = $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_PRODUCT'])->all();

        // Featured Image that we have to send to the View
        $fimage = $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_FEATURED_IMAGE'])->first();

        // List of images that we have to send to the View
        $gallery = $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_GALLERY_IMAGE'])->all();

        // Video link that we have to send to the View
        $video = $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_VIDEO_LINK'])->first();

        // Sets an array with the name of the owner as a first entry, and its profile validation status
        $additionalData['owner'] = $this->Setups->Users->get($setup->user_id);
        foreach($setup['comments'] as $comment)
        {
            // Let's complete that array with the name of each person who posted a comment on this setup
            $additionalData[$comment->user_id] = $this->Setups->Users->get($comment->user_id)['name'];
        }

        $newComment = $this->Setups->Comments->newEntity();

        $this->set(compact('setup', 'additionalData', 'products', 'fimage', 'gallery', 'video', 'newComment'));
        $this->set('_serialize', ['setup']);
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

            // Here we'll assign automatically the owner of the setup to the entity
            if(!isset($data['author']) or $data['author'] === '')
            {
                $data['author'] = $this->Setups->Users->get($data['user_id'])['name'];
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
                $this->Setups->Resources->saveResourceProducts($data['resources'], $setup, $this->Flash, $data['user_id'], false);

                /* Here we save the setup video URL */
                if(isset($data['video']) and $data['video'] !== '')
                {
                    $this->Setups->Resources->saveResourceVideo($data['video'], $setup, 'SETUP_VIDEO_LINK', $this->Flash, $data['user_id'], false);
                }

                $this->Flash->success(__('The setup has been saved.'));
                return $this->redirect(['action' => 'view', $setup->id]);
            }

            else
            {
                $this->Flash->error(__('The setup could not be saved. Please, try again.'));
                return $this->redirect($this->referer());
            }
        }

        $this->set(compact('setup'));
        $this->set('_serialize', ['setup']);
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
        $setup = $this->Setups->get($id, [
            'contain' => []
        ]);

        if($this->request->is(['patch', 'post', 'put']))
        {
            // Let's fetch the form's data
            $data = $this->request->getData();

            // Here we'll assign automatically the owned of the setup to the entity, if in the setup it has not be filled
            if(!isset($data['author']) or $data['author'] === '')
            {
                $data['author'] = $this->Setups->Users->find()->where(['id' => $setup->user_id])->first()['name'];
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

            // If the setup was not published, but now this will be the case, let's change its creation date
            if($setup['status'] !== 'PUBLISHED' and $data['status'] === 'PUBLISHED')
            {
                $data['creationDate'] = Time::now();
            }

            $setup = $this->Setups->patchEntity($setup, $data);

            if($this->Setups->save($setup))
            {
                /* Here we delete all products then save each product that has been selected by the user */
                $this->Setups->Resources->deleteAll(['Resources.user_id' => $setup->user_id, 'Resources.setup_id' => $id, 'Resources.type' => 'SETUP_PRODUCT']);
                $this->Setups->Resources->saveResourceProducts($data['resources'], $setup, $this->Flash, $setup->user_id, true);

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

                $this->Flash->success(__('The setup has been saved.'));
            }

            else
            {
                $this->Flash->error(__('The setup could not be saved. Please, try again.'));
            }

            return $this->redirect($this->referer());
        }

        $users = $this->Setups->Users->find('list', ['limit' => 200]);
        $this->set(compact('setup', 'users'));
        $this->set('_serialize', ['setup']);
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

        $this->Auth->allow(['view', 'search']);
    }

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            if(in_array($this->request->action, ['edit', 'delete']))
            {
                if($this->Setups->isOwnedBy((int)$this->request->params['pass'][0], $user['id']))
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

    public function search()
    {
        if($this->request->getQuery('q'))
        {
            /* Get query */
            $query  = $this->request->getQuery('q', '');
            $offset = $this->request->getQuery('p', '0');

            /* Add each word divided by + in request "like"*/
            $qcond = array();

            foreach(explode("+", urlencode($query)) as $key => $value)
            {
                array_push($qcond, ['CONVERT(Resources.title USING utf8) COLLATE utf8_general_ci LIKE' => '%'.$value.'%']);
            }

            /* Fetch corresponding setups */
            $this->loadModel('Resources');
            $test = $this->Resources->find('all', [
                'limit' => 10,
                'offset' => $offset,
                'group' => 'setup_id'
            ])->where([
                'OR' => $qcond,
                'Resources.type' => 'SETUP_PRODUCT'
            ]);

            /* Query featured image and infos for each setup found */
            $ncond = array();
            foreach($test as $key)
            {
                array_push($ncond, ['Resources.setup_id' => $key->setup_id]);
            }

            if(!empty($ncond))
            {
                // To avoid a additional loop, we fetched here only the published setups
                $setups = $this->Resources->find('all', [
                    'contain' => [
                        'Setups' => function($q) {
                            return $q->autoFields(false)
                            ->select([
                                'title',
                                'user_id',
                                'creationDate'
                            ])->where([
                                'status' => 'PUBLISHED'
                            ]);
                        }
                    ]
                ])->where([
                    'OR' => $ncond,
                    'Resources.type' => 'SETUP_FEATURED_IMAGE'
                ])->order([
                    'Setups.creationDate' => 'DESC'
                ])->all()->toArray();

                if(sizeof($setups) == 0)
                {
                    $setups = "noresult";
                }
            }

            else
            {
                $setups = "noresult";
            }
        }

        else
        {
            $setups = "noquery";
        }

        $this->set(compact('setups'));
        $this->set('_serialize', ['setups']);
    }
}
