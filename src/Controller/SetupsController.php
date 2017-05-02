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
        $additionalData['owner'] = $this->Setups->Users->find()->where(['id' => $setup->user_id])->first();
        foreach($setup['comments'] as $comment)
        {
            // Let's complete that array with the name of each person who postes a comment on this setup
            $additionalData[$comment->user_id] = $this->Setups->Users->find()->where(['id' => $comment->user_id])->first()['name'];
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

            // Here we'll assign automatically the owned of the setup to the entity, if in the setup it has not be filled
            if(!isset($data['author']) or $data['author'] === '')
            {
                $data['author'] = $this->Setups->Users->find()->where(['id' => $data['user_id']])->first()['name'];
            }

            // Classical patch entity operation
            $setup = $this->Setups->patchEntity($setup, $data);

            // Here we'll assign a random id to this new user
            do {
                $setup->id = mt_rand() + 1;
            } while($this->Setups->find()->where(['id' => $setup->id])->count() !== 0);

            if($this->Setups->save($setup))
            {
                /* Here we get and save the featured image */
                if(!isset($data['featuredImage'][0]) or $data['featuredImage'][0]['tmp_name'] === '' or !$this->Setups->Resources->saveResourceImage($data['featuredImage'][0], $setup, 'SETUP_FEATURED_IMAGE', $this->Flash, $data['user_id'], false, true))
                {
                    $this->Setups->delete($setup);
                    $this->Flash->warning(__("You need a featured image with this setup !"));
                    return $this->redirect($this->referer());
                }

                /* Here we save each product that has been selected by the user */
                $this->Setups->Resources->saveResourceProducts($data['resources'], $setup, $this->Flash, $data['user_id'], false);

                /* Here we save each gallery image uploaded */
                $i = 0;
                foreach($data['fileselect'] as $file)
                {
                    if($file['tmp_name'] !== '')
                    {
                        $this->Setups->Resources->saveResourceImage($file, $setup, 'SETUP_GALLERY_IMAGE', $this->Flash, $data['user_id'], false, false);
                        if(++$i === 5)
                        {
                            break;
                        }
                    }
                }

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

        $users = $this->Setups->Users->find('list', ['limit' => 200]);

        $this->set(compact('setup', 'users'));
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

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Let's set the id of the current logged in user 
            $data['user_id'] = $this->request->session()->read('Auth.User.id');

            // Here we'll assign automatically the owned of the setup to the entity, if in the setup it has not be filled
            if(!isset($data['author']) or $data['author'] === '')
            {
                $data['author'] = $this->Setups->Users->find()->where(['id' => $data['user_id']])->first()['name'];
            }

            $setup = $this->Setups->patchEntity($setup, $data);
            if ($this->Setups->save($setup)) {

                /* Here we delete all products then save each product that has been selected by the user */
                $this->Setups->Resources->deleteAll(['Resources.user_id' => $data['user_id'], 'Resources.setup_id' => $id, 'Resources.type' => 'SETUP_PRODUCT']);
                $this->Setups->Resources->saveResourceProducts($data['resources'], $setup, $this->Flash, $data['user_id'], true);

                /* Here we get and save the featured image */
                if(isset($data['featuredImage'][0]) and $data['featuredImage'][0] !== '' and (int)$data['featuredImage'][0]['error'] === 0)
                {
                    $image_to_delete = $this->Setups->Resources->find()->where(['Resources.user_id' => $data['user_id'], 'Resources.setup_id' => $id, 'Resources.type' => 'SETUP_FEATURED_IMAGE'])->first();
                    if($this->Setups->Resources->saveResourceImage($data['featuredImage'][0], $setup, 'SETUP_FEATURED_IMAGE', $this->Flash, $data['user_id'], true, true))
                    {
                        $this->Setups->Resources->delete($image_to_delete);
                    }
                }

                /* Here we'll compare the uploaded images to the new ones (in the 5 hidden inputs) */
                $galleries = $this->Setups->Resources->find('all', ['order' => ['id' => 'ASC']])->where(['setup_id' => $setup->id, 'user_id' => $data['user_id'], 'type' => 'SETUP_GALLERY_IMAGE'])->all()->toArray();
                if(isset($data['gallery0'][0]) and $data['gallery0'][0] !== '' and (int)$data['gallery0'][0]['error'] === 0)
                {
                    if(isset($galleries[0]))
                    {
                        $this->Setups->Resources->delete($galleries[0]);
                    }

                    $this->Setups->Resources->saveResourceImage($data['gallery0'][0], $setup, 'SETUP_GALLERY_IMAGE', $this->Flash, $data['user_id'], true, false);
                }
                if(isset($data['gallery1'][0]) and $data['gallery1'][0] !== '' and (int)$data['gallery1'][0]['error'] === 0)
                {
                    if(isset($galleries[1]))
                    {
                        $this->Setups->Resources->delete($galleries[1]);
                    }

                    $this->Setups->Resources->saveResourceImage($data['gallery1'][0], $setup, 'SETUP_GALLERY_IMAGE', $this->Flash, $data['user_id'], true, false);
                }
                if(isset($data['gallery2'][0]) and $data['gallery2'][0] !== '' and (int)$data['gallery2'][0]['error'] === 0)
                {
                    if(isset($galleries[2]))
                    {
                        $this->Setups->Resources->delete($galleries[2]);
                    }

                    $this->Setups->Resources->saveResourceImage($data['gallery2'][0], $setup, 'SETUP_GALLERY_IMAGE', $this->Flash, $data['user_id'], true, false);
                }
                if(isset($data['gallery3'][0]) and $data['gallery3'][0] !== '' and (int)$data['gallery3'][0]['error'] === 0)
                {
                    if(isset($galleries[3]))
                    {
                        $this->Setups->Resources->delete($galleries[3]);
                    }

                    $this->Setups->Resources->saveResourceImage($data['gallery3'][0], $setup, 'SETUP_GALLERY_IMAGE', $this->Flash, $data['user_id'], true, false);
                }
                if(isset($data['gallery4'][0]) and $data['gallery4'][0] !== '' and (int)$data['gallery4'][0]['error'] === 0)
                {
                    if(isset($galleries[4]))
                    {
                        $this->Setups->Resources->delete($galleries[4]);
                    }

                    $this->Setups->Resources->saveResourceImage($data['gallery4'][0], $setup, 'SETUP_GALLERY_IMAGE', $this->Flash, $data['user_id'], true, false);
                }

                /* Here we save the setup video URL */
                if(isset($data['video']) and $data['video'] !== '')
                {
                    // Here we get the current video link, if present
                    $video_to_delete = $this->Setups->Resources->find()->where(['setup_id' => $setup->id, 'type' => 'SETUP_VIDEO_LINK'])->first();

                    if($video_to_delete && $video_to_delete !== $data['video'])
                    {
                        $this->Setups->Resources->delete($video_to_delete);
                    }

                    $this->Setups->Resources->saveResourceVideo($data['video'], $setup, 'SETUP_VIDEO_LINK', $this->Flash, $data['user_id'], true);
                }

                $this->Flash->success(__('The setup has been saved.'));

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The setup could not be saved. Please, try again.'));
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
        
        if ($this->request->getQuery('q')) {

            $query = $this->request->getQuery('q','');
            $offset = $this->request->getQuery('p', '0');

            $query = urlencode($query);

            $this->loadModel('Resources');

            $qcond = array();

            foreach (explode("+", $query) as $key => $value) {
                array_push($qcond, ['CONVERT(Resources.title USING utf8)  COLLATE utf8_general_ci LIKE' => '%'.$value.'%']);
            }

            $qconditions = array('OR' => $qcond, 'Resources.type' => 'SETUP_PRODUCT'); 


            $test = $this->Resources->find('all', array('limit' => 8, 'offset' => $offset, 'group' => 'setup_id'))->where($qconditions);

            $ncond = array();

            foreach ($test as $key) {
                array_push($ncond, ['Resources.setup_id' => $key->setup_id]);
            }

            if(!empty($ncond)){

                $conditions = array('OR' => $ncond, 'Resources.type' => 'SETUP_FEATURED_IMAGE');            

                $setups = $this->Resources->find('all', array('contain' => array('Setups' => function ($q) {return $q->autoFields(false)->select(['title','user_id']);} )))->where($conditions);

            }

            else{
                $setups = "noresult";
            }

        }
        else{
            $setups = "noquery";
        }

        $this->set(compact('setups'));

        $this->set('_serialize', ['setups']);
    }
}
