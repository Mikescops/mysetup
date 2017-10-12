<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Text;
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
            'limit' => 10,
            'order' => [
                'Articles.dateTime'=> 'desc'
            ]
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
            'contain' => [
                'Users' => [
                    'fields' => [
                        'id',
                        'name'
                    ]
                ]
            ]
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
                    return $this->redirect(['action' => 'add']);
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

                return $this->redirect('/blog/' . $article->id . '-' . Text::slug($article->title));
            }

            else
            {
                $this->Articles->deletePicture($article['picture']);
                $this->Flash->error(__('The article could not be saved. Please, try again.'));

                return $this->redirect(['action' => 'add']);
            }

        }

        $categories = $this->Articles->categories;

        $this->set(compact('article', 'categories'));
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
        $article = $this->Articles->get($id);

        if($this->request->is(['patch', 'post', 'put']))
        {
            $data = $this->request->getData();

            $pictureToDelete = null;

            if(isset($data['picture']) and $data['picture']['tmp_name'] !== '')
            {
                // Here we save the path to the current picture
                $pictureToDelete = $article['picture'];

                // We save here the path to the new picture, returned by our dear function
                $data['picture'] = $this->Articles->savePicture($data['picture'], $this->Flash);

                if(!$data['picture'])
                {
                    return $this->redirect(['action' => 'add']);
                }
            }

            else
            {
                $data['picture'] = $article['picture'];
            }

            $article = $this->Articles->patchEntity($article, $data);

            if($this->Articles->save($article))
            {
                // If this path is non-null, we've to delete the old picture !
                if($pictureToDelete)
                {
                    $this->Articles->deletePicture($pictureToDelete);
                }

                $this->Flash->success(__('The article has been saved.'));

                return $this->redirect('/blog/' . $article->id . '-' . Text::slug($article->title));
            }

            else
            {
                // If the user uploaded a new image (different path), we've to delete it now !
                if($pictureToDelete != $data['picture'])
                {
                    $this->Articles->deletePicture($data['picture']);
                }

                $this->Flash->error(__('The article could not be saved. Please, try again.'));

                return $this->redirect($this->referer());
            }
        }

        $categories = $this->Articles->categories;

        $this->set(compact('article', 'categories'));
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

        if($this->Articles->delete($article))
        {
            $this->Flash->success(__('The article has been deleted.'));
            return $this->redirect(['action' => 'index']);
        }

        else
        {
            $this->Flash->error(__('The article could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'view', $id]);
        }
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
            // Only the owner can delete his articles
            if($this->request->action === 'delete')
            {
                if($this->Articles->isOwnedBy((int)$this->request->params['pass'][0], $user['id']))
                {
                    return true;
                }

                else
                {
                    return false;
                }
            }

            // Each admin can add a new article, or edit an existing one
            else if(in_array($this->request->action, ['add', 'edit']))
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
