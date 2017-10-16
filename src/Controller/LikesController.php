<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Response;
use Cake\Routing\Router;

/**
 * Likes Controller
 *
 * @property \App\Model\Table\ResourcesTable $Likes
 */
class LikesController extends AppController
{
    /* AJAX CALLS */
    public function getLikes()
    {
        if($this->request->is('ajax'))
        {
            return new Response([
                'status' => 200,
                'type' => 'json',
                'body' => json_encode($this->Likes->find()->where(['setup_id' => $this->request->getQuery('setup_id')])->count())
            ]);
        }
    }

    public function doesLike()
    {
        if($this->request->is('ajax'))
        {
            return new Response([
                'status' => 200,
                'type' => 'json',
                'body' => json_encode($this->Likes->hasBeenLikedBy($this->request->getQuery('setup_id'), $this->request->session()->read('Auth.User.id')))
            ]);
        }
    }

    public function like()
    {
        if($this->request->is('ajax'))
        {
            $status = 500;
            $body   = null;

            $setup_id = $this->request->getQuery('setup_id');
            if($this->Likes->Setups->exists(['id' => $setup_id]))
            {
                if(!$this->Likes->exists(['setup_id' => $setup_id, 'user_id' => $this->request->session()->read('Auth.User.id')]))
                {
                    $like = $this->Likes->newEntity();

                    // When an user likes a setup, we just create an entity with its id, and the setup's one
                    $like['setup_id'] = $setup_id;
                    $like['user_id']  = $this->request->session()->read('Auth.User.id');

                    if($this->Likes->save($like))
                    {
                        $status = 200;
                        $body   = 'LIKED';

                        // If it's not him, let's inform the setup owner of this new like
                        $setup = $this->Likes->Setups->get($setup_id);
                        if($like['user_id'] !== $setup['user_id'])
                        {
                            TableRegistry::get('Notifications')->createNotification($setup['user_id'], '<a href="' . Router::url(['controller' => 'Setups', 'action' => 'view', $like['setup_id']]) . '"><img src="' . Router::url('/') . 'uploads/files/pics/profile_picture_' . $like['user_id'] . '.png" alt="__ALT">  <span><strong>' . h($this->Likes->Users->get($like['user_id'])['name']) . '</strong> __LIKE <strong>' . h($setup['title']) . '</strong></span></a>');
                        }
                    }

                    else
                    {
                        $body = 'NOT_LIKED';
                    }
                }

                else
                {
                    $body = 'ALREADY_LIKED';
                }
            }

            else
            {
                $body = 'DOES_NOT_EXIST';
            }

            return new Response([
                'status' => $status,
                'type' => 'json',
                'body' => json_encode($body)
            ]);
        }
    }

    public function dislike()
    {
        if($this->request->is('ajax'))
        {
            $status = 500;
            $body   = null;

            $setup_id = $this->request->getQuery('setup_id');
            if($this->Likes->Setups->exists(['id' => $setup_id]))
            {
                $like = $this->Likes->find()->where(['setup_id' => $setup_id, 'user_id' => $this->request->session()->read('Auth.User.id')])->first();

                if($like)
                {
                    if($this->Likes->delete($like))
                    {
                        $status = 200;
                        $body   = 'DISLIKED';
                    }

                    else
                    {
                        $body = 'NOT_DISLIKED';
                    }
                }

                else
                {
                    $body = 'NOT_ALREADY_LIKED';
                }
            }

            else
            {
                $body = 'DOES_NOT_EXIST';
            }

            return new Response([
                'status' => $status,
                'type' => 'json',
                'body' => json_encode($body)
            ]);
        }
    }
    /* __________ */

    public function beforeFilter(Event $event)
    {
        // Anonymous visitors can retrieve likes number of a setup...
        $this->Auth->allow('getLikes');
    }

    public function isAuthorized($user)
    {
        // Connected user may do whatever they want about likes...
        // ... others don't !
        if(isset($user))
        {
            return true;
        }

        return parent::isAuthorized($user);
    }
}
