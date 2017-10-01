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

        /* Here let's adapt the website language ! */
        // This is the trick : If the `?lang=` is specified in the URL, this parameter overwrites the Session's one (hello the robots ;))
        if(array_key_exists('lang', $_GET))
        {
            $this->loadModel('Users');
            I18n::setLocale($this->Users->getLocaleByCountryID($_GET['lang']));
        }

        else
        {
            I18n::setLocale($this->request->session()->read('Config.language'));
        }

        // Listen carefully to the second trick : In the 'default.ctp' and 'admin.ctp' (and now `Setups/embed.ctp`, you'll find a `if(!$lang)`.
        // This line set this very variable for the view, if the lang is enforced in the URL the HTML will follow it. If not, check there what is done :P
        $this->set('lang', (isset($_GET['lang']) ? strtolower($_GET['lang']) : null));
        /* _______________________________________ */
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

            // Let's check if the the session is "synced" with the user entity...
            if($user['admin'] XOR $this->request->session()->check('Auth.User.admin'))
            {
                // ... if not, let's update the session accordingly
                $this->request->session()->write('Auth.User', $user);
            }

            $this->set('authUser', $user);

            // Let's send to the view the list of timezones as well
            $this->set('timezones', $this->Users->timezones);
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
        $this->Auth->allow(['getSetups', 'getActiveUsers', 'getLikes', 'reportBug']);

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
            'g-recaptcha-response',
            'bugDescription',
            'bugMail'
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
        if($session->read('Auth.User.mail') === 'admin@admin.admin' or $session->check('Auth.User.admin'))
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
                            $this->Notifications->createNotification($setup['user_id'], '<a href="' . Router::url(['controller' => 'Setups', 'action' => 'view', $like['setup_id']]) . '"><img src="' . Router::url('/') . 'uploads/files/pics/profile_picture_' . $like['user_id'] . '.png" alt="__ALT">  <span><strong>' . h($this->Users->get($like['user_id'])['name']) . '</strong> __LIKE <strong>' . h($setup['title']) . '</strong></span></a>');
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

            // Here we'll concatenate 'on-the-go' a "time ago with words" to the notifications content + Makes some translations
            foreach($results as $result)
            {
                $result['content'] = str_replace('</a>', ' <span><i class="fa fa-clock-o"></i> ' . $result['dateTime']->timeAgoInWords() . '</span></a>', $result['content']);

                if(strpos($result['content'], '__LIKE'))
                {
                    $result['content'] = str_replace('__ALT', __('Liker\'s profile picture'), $result['content']);
                    $result['content'] = str_replace('__LIKE', __('liked your setup'), $result['content']);
                }

                else if(strpos($result['content'], '__COMMENT'))
                {
                    $result['content'] = str_replace('__ALT', __('Commenter\'s profile picture'), $result['content']);
                    $result['content'] = str_replace('__COMMENT', __('commented your setup'), $result['content']);
                }
            }

            return new Response([
                'status' => 200,
                'body' => json_encode($results)
            ]);
        }
    }
    /* ____________ */

    public function getActiveUsers()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('Notifications');

            $results = $this->Notifications->find('all', ['limit' => $this->request->getQuery('n', '8')])
                ->select([
                    'Notifications.user_id',
                    'Users.name',
                    'Users.modificationDate'
                ])
                ->group('Notifications.user_id')
                ->contain([
                    'Users'
                ])
                ->toArray();

            return new Response([
                'status' => 200,
                'body' => json_encode($results)
            ]);
        }
    }

    public function getSetups()
    {
        if($this->request->is('get'))
        {
            $conditions = [];

            // If the query specified only the features ones
            if($this->request->getQuery('f', false))
            {
                $conditions += ['featured' => true];
            }

            $conditions += [
                'Setups.creationDate >' => date('Y-m-d', strtotime("-" . $this->request->getQuery('w', '9999') . "weeks")),
                'Setups.creationDate <=' => date('Y-m-d', strtotime("+ 1 day")),
                'status' => 'PUBLISHED'
            ];


            $term_query = $this->request->getQuery('q');
            // Some empty arrays in which we'll set the SQL conditions to match a setup... or not
                $author_cond    = [];
                $title_cond     = [];
                $resources_cond = [];

            if($term_query)
            {
                // Let's fill in these array (tough operation)
                foreach(explode("+", urlencode($term_query)) as $word)
                {
                    array_push($author_cond, ['LOWER(Setups.author) LIKE' => '%' . strtolower($word) . '%']);
                    array_push($title_cond, ['LOWER(Setups.title) LIKE' => '%' . strtolower($word) . '%']);
                    array_push($resources_cond, ['CONVERT(Resources.title USING utf8) COLLATE utf8_general_ci LIKE' => '%' . $word . '%']);
                }

            }


            $this->loadModel('Setups');
            $results = $this->Setups->find('all', [
                'conditions' => $conditions,
                'order' => [
                    'Setups.creationDate' => $this->request->getQuery('o', 'DESC')
                ],
                'limit' => $this->request->getQuery('n', '8'),
                'offset' => $this->request->getQuery('p', '0'),
                'contain' => [
                    'Likes' => function ($q) {
                        return $q->autoFields(false)->select(['setup_id', 'total' => $q->func()->count('Likes.user_id')])->group(['Likes.setup_id']);
                    },
                    'Comments' => function ($q) {
                        return $q->autoFields(false)->select(['setup_id', 'total' => $q->func()->count('Comments.user_id')])->group(['Comments.setup_id']);
                    },
                    'Resources' => function ($q) {
                        return $q->autoFields(false)->select(['setup_id', 'src'])->where(['type' => 'SETUP_FEATURED_IMAGE']);
                    },
                    'Users' => function ($q) {
                        return $q->autoFields(false)->select(['Users.id', 'Users.name', 'Users.modificationDate']);
                    }
                ]
            ])
            ->where(['OR' => $author_cond])
            ->orWhere(['OR' => $title_cond])
            ->leftJoinWith('Resources')
            ->orWhere(['OR' => $resources_cond])
            ->distinct()
            ->toArray();

            // If the query specified a ranking by number of "likes", let's sort them just before sending it
            if($this->request->getQuery('t', 'date') == "like")
            {
                usort($results, function($a, $b) {

                    if(empty($a->likes))
                    {
                        $a->likes += [0 => ['total' => 0]];
                    }

                    if(empty($b->likes))
                    {
                        $b->likes += [0 => ['total' => 0]];
                    }

                    if($a->likes[0]['total'] == $b->likes[0]['total'])
                    {
                        return 0;
                    }

                    else
                    {
                        return ($a->likes[0]['total'] < $b->likes[0]['total']) ? 1 : -1;
                    }
                });
            }

            return new Response([
                'status' => 200,
                'body' => json_encode($results)
            ]);
        }
    }

    public function reportBug()
    {
        if($this->request->is('post'))
        {
            $data = $this->request->getData();

            $auth = $this->Auth->user();

            if(isset($data['bugDescription']) and $data['bugDescription'] !== '' and strlen($data['bugDescription'] <= 5000) and ($auth or (isset($data['bugMail']) and $data['bugMail'] !== '')))
            {
                $this->loadModel('Users');
                $email = $this->Users->getEmailObject('samuel@geek-mexicain.net', '[mySetup.co] There is a bug !');
                $email->setTemplate('bug')
                      ->viewVars(['content' => $data['bugDescription'], 'email' => ($auth ? $auth['mail'] : $data['bugMail'])])
                      ->send();

                $this->Flash->success(__('Your bug has been correctly sent ! Thanks for this report :)'));
            }

            else
            {
                $this->Flash->warning(__('You didn\'t report anything (or has missed something) :('));
            }
        }

        return $this->redirect('/');
    }
}
