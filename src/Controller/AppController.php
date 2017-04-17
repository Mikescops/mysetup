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
                'controller' => 'Setups',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Setups',
                'action' => 'index'
            ]
        ]);
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
        //Test if a user is log
        if(isset($this->Auth)){
            $this->set('authUser', $this->Auth->user());
        }

        // Before render the view, let's give a new entity for add Setup modal to it
        $this->loadModel('Setups');
        $newSetupEntity = $this->Setups->newEntity();
        $this->set('newSetupEntity');

    }

    public function beforeFilter(Event $event)
    {
        // By default, no page is allowed. Please check special authorizations in the others controller
        $this->Auth->deny();

        // Allow GET request on public functions
        $this->Auth->allow(['getsetups', 'getLikes']);

        // Let's remove the tampering protection on the hidden `resources` field (handled by JS), and files inputs
        $this->Security->config('unlockedFields', [
            'resources',
            'featuredImage',
            'fileselect',
            'video',
            'mailReset'
        ]);
    }

    public function isAuthorized($user)
    {
        /* DANGEROUS PART IS JUST BELOW, PLEASE TAKE THAT WITH EXTREME PRECAUTION */
        if(isset($user) && $user['mail'] === 'admin@admin.admin')
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    /* AJAX CALLS ? */
    public function getLikes()
    {
        if($this->request->is('get'))
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
        if($this->request->is('get'))
        {
            $this->loadModel('Likes');

            return new Response([
                'status' => 200,
                'body' => json_encode($this->Likes->find()->where(['setup_id' => $this->request->query['setup_id'], 'user_id' => $this->request->session()->read('Auth.User.id')])->count())
            ]);
        }
    }

    public function like()
    {
        if($this->request->is('get'))
        {
            $status = 500;
            $body   = null;

            $setup_id = $this->request->query['setup_id'];
            $this->loadModel('Likes');

            if($this->Likes->Setups->exists(['id' => $setup_id]))
            {
                if(!$this->Likes->find()->where(['setup_id' => $setup_id, 'user_id' => $this->request->session()->read('Auth.User.id')])->first())
                {
                    $like = $this->Likes->newEntity();

                    // When an user likes a setup, we just create an entity with its id, and the setup's one
                    $like['setup_id'] = $setup_id;
                    $like['user_id']  = $this->request->session()->read('Auth.User.id');

                    if($this->Likes->save($like))
                    {
                        $status = 200;
                        $body   = 'LIKED';
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
        if($this->request->is('get'))
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

    public function getSetups()
    {
        if($this->request->is('get'))
        {
            $nbpost = $this->request->getQuery('n', '8');
            $order = $this->request->getQuery('o', 'DESC');
            $type = $this->request->getQuery('t', 'date');
            $weeks = $this->request->getQuery('w', '9999');
            $featured = $this->request->getQuery('f', false);

            $week_start = date('Y-m-d', strtotime("-" . $weeks . "weeks"));
            $week_end = date('Y-m-d', strtotime("+ 1 day"));

            $this->loadModel('Setups');

            $conditions = array();

            /* Featured ? */
            if ($featured == true) {
                array_push($conditions, array("featured" => true));
            }

            if ($type == "date"){
                array_push($conditions, array("creationDate >" => $week_start, "creationDate <=" => $week_end)); 
                $results = $this->Setups->find('all', array('conditions' => $conditions, 'order' => ['creationDate' => $order],'limit' => $nbpost));
            }
            elseif ($type == "comment") {
                # get by comments
            }
            elseif ($type == "like") {
                # get by likes
            }

            /* Let's include extra data in our results */

            $results = $results->toArray();

            $this->loadModel('Resources');
            $this->loadModel('Likes');

            foreach ($results as $setup){
                $id = $setup->id;

                $fimage = $this->Resources->find('all', ['conditions' => ['setup_id' => $id, 'type' => 'SETUP_FEATURED_IMAGE'],'limit' => 1]);

                $likes = $this->Likes->find()->where(['setup_id' => $id])->count();

                $fimage = $fimage->toArray();

                error_reporting(0);

                $setup->src = $fimage[0]['src'];
                $setup->likes = $likes;
            }


            return new Response([
                'status' => 200,
                'body' => json_encode($results)
            ]);
        }
    }
}
