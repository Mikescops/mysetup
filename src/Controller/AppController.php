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
use Cake\Event\Event;
use Cake\I18n\I18n;

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

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Security');
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
                'action' => 'home'
            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'home'
            ]
        ]);

        /* Here let's adapt the website language !
         *
         * Order of treatment :
         *   1. `?lang=` GET parameter (hello the robots ;))
         *   2. Session configuration (written through user preferences)
         *   3. Browser locale sent through `Accept-Language` header
         *
         */
        $lang = null;
        if($this->request->getQuery('lang'))
        {
            $lang = $this->request->getQuery('lang');
        }
        elseif($this->request->getSession()->check('Config.language'))
        {
            $lang = $this->request->getSession()->read('Config.language');
        }
        else
        {
            $lang = I18n::getLocale();
        }
        $lang = $this->loadModel('Users')->getLocaleByCountryID($lang);
        I18n::setLocale($lang);
        $this->set('lang', explode('_', $lang)[0]);
        /* __________________________________________________________ */
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        $user = $this->Auth->user();

        // Test if a user is logged in, and if it's the case, fetch some more data
        if($user !== null)
        {
            // We'll need this Model below...
            $this->loadModel('Setups');

            // Now, let's send the setups list to the view (to let the user choose a default one);
            $this->set('setupsList', $this->Setups->find('list', [
                'keyField'   => 'id',
                'valueField' => 'title',
                'conditions' => [
                    'user_id' => $user->id
                ],
                'order'      => [
                    'creationDate' => 'DESC'
                ]
            ])->toArray());

            // Let's send to the view the list of timezones as well
            $this->set('timezones', $this->Setups->Users->timezones);

            // Before render the view, let's give a new entity for add Setup modal to it
            $this->set('newSetupEntity', $this->Setups->newEntity());

            // We'll need also the setups available status
            if(!$this->isAdmin($user))
            {
                // ... but if the user is not an admin, let's hide from him the `REJECTED` status.
                unset($this->Setups->status['REJECTED']);
            }
            $this->set('status', $this->Setups->status);
        }

        // Just give the session user entity to the front...
        // This object is supposed to be synchronize with the "real" one.
        $this->set('authUser', $this->request->getSession()->read('Auth.User'));
    }

    public function beforeFilter(Event $event)
    {
        // By default, no page is allowed. Please check special authorizations in the other controllers
        $this->Auth->deny();

        // Let's remove the tampering protection on the hidden `resources` field (handled by JS), and files inputs
        $this->Security->setConfig('unlockedFields', [
            'resources',
            'featuredImage',
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
}
