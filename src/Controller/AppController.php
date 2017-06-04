<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Network\Response;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Network\Http\Client;
use Cake\Routing\Router;
use Cake\I18n\Time;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Security');
        $this->loadComponent('Csrf');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'mail',
                        'password' => 'password'
                    ]
                ]
            ],
            'authorize' => [
                'Controller'
            ],
            'loginRedirect' => [
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'display',
                'home'
            ]
        ]);

        // Here let's adapt the website language !
        I18n::locale($this->request->session()->read('Config.language'));
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }

        // Test if a user is logged in, and if it's the case, give to the view the user entity linked
        if(isset($this->Auth)) {
            $this->loadModel('Users');
            $user = $this->Users->find()->where(['id' => $this->Auth->user()['id']])->first();
            if($user and $this->isAdmin($user))
            {
                $user['admin'] = true;
            }
            $this->set('authUser', $user);
        }

        // Before render the view, let's give a new entity for add Setup modal to it
        $this->loadModel('Setups');
        $newSetupEntity = $this->Setups->newEntity();

        // We'll need also the setups available status
        $status = $this->Setups->status;

        $this->set(compact('newSetupEntity', 'status'));
    }

    public function beforeFilter(Event $event)
    {
        // By default, no page is allowed. Please check special authorizations in the others controller
        $this->Auth->deny();

        // Allow GET request on public functions
        $this->Auth->allow(['getSetups', 'getLikes']);

        // Let's remove the tampering protection on the hidden `resources` field (handled by JS), and files inputs
        $this->Security->config('unlockedFields', [
            'resources',
            'featuredImage',
            'video',
            'mailReset',
            'picture',
            'gallery0',
            'gallery1',
            'gallery2',
            'gallery3',
            'gallery4',
            'g-recaptcha-response'
        ]);
    }

    public function isAuthorized($user)
    {
        // Authorizes some actions if the user is connected
        if(isset($user) && in_array($this->request->action, ['like', 'dislike', 'doesLike', 'getNotifications']))
        {
            return true;
        }

        /* DANGEROUS PART IS JUST BELOW, PLEASE TAKE THAT WITH EXTREME PRECAUTION */
        if(isset($user) && $this->isAdmin($user))
        {
            return true;
        }

        else
        {
            $this->redirect('/');
            return false;
        }
    }

    /* DANGEROUS PART */
    protected function isAdmin($user)
    {
        if($user['mail'] === 'admin@admin.admin' or $user['verified'] === 125)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    protected function isAdminBySession($session)
    {
        if($session->read('Auth.User.mail') === 'admin@admin.admin' or $session->read('Auth.User.verified') === 125)
        {
            return true;
        }

        else
        {
            return false;
        }
    }
    /* _______________*/

    /* GOOGLE'S CAPTCHA VERIFICATION */
    protected function captchaValidation($data)
    {
        // Is this user authorized by Google invisible CAPTCHA ?
        $response = (new Client())->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => '6LcLKx0UAAAAACDyDI7Jtmkm0IX9ni3cWN5amwx3',
            'response' => $data['g-recaptcha-response']
        ]);

        if(!$response or !$response->json or !$response->json['success'])
        {
            return false;
        }

        else
        {
            return true;
        }
    }
    /* _____________________________ */

    /* AJAX CALLS ? */
    public function getLikes()
    {
        if($this->request->is('ajax'))
        {
            $this->loadModel('Likes');

            return new Response([
                'status' => 200,
                'body' => json_encode($this->Likes->find()->where(['setup_id' => $this->request->query['setup_id']])->count())
            ]);
        }
    }

    public function doesLike()
    {
        if($this->request->is('ajax'))
        {
            $this->loadModel('Likes');

            return new Response([
                'status' => 200,
                'body' => json_encode($this->Likes->hasBeenLikedBy($this->request->query['setup_id'], $this->request->session()->read('Auth.User.id')))
            ]);
        }
    }

    public function like()
    {
        if($this->request->is('ajax'))
        {
            $status = 500;
            $body   = null;

            $setup_id = $this->request->query['setup_id'];
            $this->loadModel('Likes');

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
                        $this->loadModel('Setups');
                        $setup = $this->Setups->get($setup_id);
                        if($like['user_id'] !== $setup['user_id'])
                        {
                            $this->loadModel('Users');
                            $this->loadModel('Notifications');
                            $this->Notifications->createNotification($setup['user_id'], '<a href="' . Router::url(['controller' => 'Setups', 'action' => 'view', $like['setup_id']]) . '"><img src="' . Router::url('/') . 'uploads/files/pics/profile_picture_' . $like['user_id'] . '.png" alt="Liker\'s profile picture">  <span><strong>' . $this->Users->get($like['user_id'])['name'] . '</strong> '. __('liked your setup') . ' <strong>' . $setup['title'] . '</strong></span></a>');
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
                'body' => $body
            ]);
        }
    }

    public function dislike()
    {
        if($this->request->is('ajax'))
        {
            $status = 500;
            $body   = null;

            $setup_id = $this->request->query['setup_id'];
            $this->loadModel('Likes');

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
                'body' => $body
            ]);
        }
    }

    public function getNotifications()
    {
        if($this->request->is('ajax'))
        {
            $this->loadModel('Notifications');
            $results = $this->Notifications->find('all', [
                'conditions' => [
                    'user_id' => $this->request->session()->read('Auth.User.id'),
                    'new' => 1
                ],
                'order' => [
                    'dateTime' => 'DESC'
                ],
                'limit' => $this->request->getQuery('n', 4)
            ]);

            // Here we'll concatenate 'on-th-go' a "time ago with words" to the notifications content
            foreach($results as $result)
            {
                $result['content'] = str_replace('</a>', ' <span><i class="fa fa-clock-o"></i> ' . $result['dateTime']->timeAgoInWords() . '</span></a>', $result['content']);
            }

            return new Response([
                'status' => 200,
                'body' => json_encode($results)
            ]);
        }
    }
    /* ____________ */

    public function getSetups()
    {
        if($this->request->is('get'))
        {
            $nbpost = $this->request->getQuery('n', '8');
            $order = $this->request->getQuery('o', 'DESC');
            $type = $this->request->getQuery('t', 'date');
            $weeks = $this->request->getQuery('w', '9999');
            $featured = $this->request->getQuery('f', false);
            $offset = $this->request->getQuery('p', '0');

            $this->loadModel('Setups');

            $conditions = array();

            /* Featured ? */
            if ($featured == true) {
                array_push($conditions, array("featured" => true));
            }

            array_push($conditions, ['creationDate >' => date('Y-m-d', strtotime("-" . $weeks . "weeks")), 'creationDate <=' => date('Y-m-d', strtotime("+ 1 day")), 'status' => 'PUBLISHED']);

            $results = $this->Setups->find('all', [
                'conditions' => $conditions,
                'order' => [
                    'creationDate' => $order
                ],
                'limit' => $nbpost,
                'offset' => $offset,
                'contain' => [
                    'Likes' => function ($q) {
                        return $q->autoFields(false)->select(['setup_id', 'total' => $q->func()->count('Likes.user_id')])->group(['Likes.setup_id']);
                    },
                    'Comments' => function ($q) {
                        return $q->autoFields(false)->select(['setup_id', 'total' => $q->func()->count('Comments.user_id')])->group(['Comments.setup_id']);
                    },
                    'Resources' => function ($q) {
                        return $q->autoFields(false)->select(['setup_id', 'src'])->where(['type' => 'SETUP_FEATURED_IMAGE']);
                    }
                ]
            ])->toArray();

            if ($type == "like") {
                usort($results, function($a, $b) {
                    error_reporting(0);
                    if(empty($a->likes)){$a->likes[0]->total = 0;}
                    if(empty($b->likes)){$b->likes[0]->total = 0;}
                    if($a->likes[0]->total == $b->likes[0]->total) {
                        return 0;
                    } 
                    return ($a->likes[0]->total > $b->likes[0]->total) ? -1 : 1;
                }); // not working yet
            }

            return new Response([
                'status' => 200,
                'body' => json_encode($results)
            ]);
        }
    }
}
