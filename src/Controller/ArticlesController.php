<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[] paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $articles = $this->paginate($this->Articles);

        $this->set(compact('articles'));
        $this->set('_serialize', ['articles']);
    }


    /*Add markdown support*/
    public $helpers = ['Tanuck/Markdown.Markdown' => ['parser' => 'GithubMarkdown']];

    
    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('article', $article);
        $this->set('_serialize', ['article']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEntity();

        if($this->request->is('post'))
        {
            $data = $this->request->getData();

            // Before anything else, let's check and save the article's picture
            if(isset($data['picture']) and $data['picture']['tmp_name'] !== '')
            {
                $data['picture'] = $this->Articles->savePicture($data['picture'], $this->Flash);

                if(!$data['picture'])
                {
                    return $this->redirect($this->referer());
                }
            }

            else
            {
                $this->Flash->warning(__('You need a featured image with this article !'));
                return $this->redirect($this->referer());
            }

            $article = $this->Articles->patchEntity($article, $data);

            // Set the owner here
            $article['user_id'] = $this->request->session()->read('Auth.User.id');

            if($this->Articles->save($article))
            {

                $this->Flash->success(__('The article has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }

        $this->set(compact('article'));
        $this->set('_serialize', ['article']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $article = $this->Articles->get($id, [
            'contain' => []
        ]);

        if($this->request->is(['patch', 'post', 'put']))
        {
            $data = $this->request->getData();

            if(isset($data['picture']) and $data['picture']['tmp_name'] !== '')
            {
                // Here we save the path to the current picture
                $path = $article['picture'];

                // We save here the new picture
                $data['picture'] = $this->Articles->savePicture($data['picture'], $this->Flash);

                if(!$data['picture'])
                {
                    return $this->redirect($this->referer());
                }

                else
                {
                    $this->Articles->deletePicture($path);
                }
            }

            else
            {
                $data['picture'] = $article['picture'];
            }

            $article = $this->Articles->patchEntity($article, $data);

            if($this->Articles->save($article))
            {
                $this->Flash->success(__('The article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The article could not be saved. Please, try again.'));
        }

        $this->set(compact('article'));
        $this->set('_serialize', ['article']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article has been deleted.'));
        } else {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['index', 'view']);
    }

    public function isAuthorized($user)
    {
        if(isset($user) && parent::isAdmin($user))
        {
            if(in_array($this->request->action, ['edit', 'delete']))
            {
                if($this->Articles->isOwnedBy((int)$this->request->params['pass'][0], $user['id']))
                {
                    return true;
                }
            }

            else if($this->request->action === 'add')
            {
                return true;
            }

            else
            {
                return false;
            }
        }

        // Useless but left for the future
        return parent::isAuthorized($user);
    }
}
