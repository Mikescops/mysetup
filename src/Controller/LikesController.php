<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Response;

/**
 * Likes Controller
 *
 * @property \App\Model\Table\ResourcesTable $Likes
 */
class LikesController extends AppController
{
    /* /!\ Careful /!\
     * This is not a 'regular' `index()` method :
     * This one will list the likes of the current user.
     * It's only available 'privately'.
     */
    public function index()
    {
        if($this->request->is('get'))
        {
            // Let's just fetch the setups that the current user has liked !
            // They will be available into a `like` entity although...
            $likes = $this->Likes->find('all', [
                'conditions' => [
                    'Likes.user_id' => $this->request->session()->read('Auth.User.id')
                ],
                'contain' => [
                    'Setups' => [
                        'fields' => [
                            'id',
                            'user_id',
                            'title',
                            'creationDate',
                            'status'
                        ],
                        'Users' => [
                            'fields' => [
                                'id',
                                'modificationDate'
                            ]
                        ],
                        'Resources' => [
                            'conditions' => [
                                'type' => 'SETUP_FEATURED_IMAGE'
                            ],
                            'fields' => [
                                'id',
                                'src',
                                'setup_id'
                            ]
                        ],
                        'Likes' => function ($q) {
                            return $q->enableAutoFields(false)->select(['setup_id', 'total' => $q->func()->count('Likes.user_id')])->group(['Likes.setup_id']);
                        }
                    ]
                ],
                'order' => [
                    'Likes.id' => 'DESC'
                ]
            ])->all()->toArray();

            $this->set('likes', $likes);
        }
    }


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
                        if($like['user_id'] !== $setup->user_id)
                        {
                            $this->loadModel('Notifications');
                            $this->Notifications->createNotificationLink($this->Likes->Users->get($like['user_id']), $setup, $this->Notifications->types['like']);
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
