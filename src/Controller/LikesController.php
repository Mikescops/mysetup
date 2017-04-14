<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Likes Controller
 *
 * @property \App\Model\Table\LikesTable $Likes
 */
class LikesController extends AppController
{

    public function like($id = null)
    {
        if($this->request->is('post'))
        {
            if($this->Likes->Setups->exists(['id' => $id]))
            {
                if(!$this->Likes->find()->where(['setup_id' => $id, 'user_id' => $this->request->session()->read('Auth.User.id')])->first())
                {
                    $like = $this->Likes->newEntity();

                    // When an user likes a setup, we just create an entity with its id, and the setup's one
                    $like['setup_id'] = $id;
                    $like['user_id']  = $this->request->session()->read('Auth.User.id');

                    if(!$this->Likes->save($like))
                    {
                        $this->Flash->error(__("Your like could not be saved."));
                        $this->redirect(['controller' => 'setups', 'action' => 'view', $id]);
                    }
                }
            }

            else
            {
                $this->Flash->error(__("This setup does not exist."));
                return $this->redirect('/');
            }
        }
    }

    public function dislike($id = null)
    {
        if($this->request->is('post'))
        {
            if($this->Likes->Setups->exists(['id' => $id]))
            {
                $like = $this->Likes->find()->where(['setup_id' => $id, 'user_id' => $this->request->session()->read('Auth.User.id')])->first();

                if($like && !$this->Likes->delete($like))
                {
                    $this->Flash->error(__("Your like could not be deleted."));
                    $this->redirect(['controller' => 'setups', 'action' => 'view', $id]);
                }
            }

            else
            {
                $this->Flash->error(__("This setup does not exist."));
                return $this->redirect('/');
            }
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->eventManager()->off($this->Csrf);
    }

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            if($this->request->action === 'like')
            {
                if($this->Likes->hasBeenLikedBy((int)$this->request->params['pass'][0], $user['id']))
                {
                    return true;
                }
            }

            else if($this->request->action === 'dislike')
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
