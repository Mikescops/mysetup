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
use Cake\Network\Http\Client;

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
        elseif($this->request->session()->check('Config.language'))
        {
            $lang = $this->request->session()->read('Config.language');
        }
        else
        {
            $lang = I18n::getLocale();
        }
        $lang = $this->loadModel('Users')->getLocaleByCountryID($lang);
        I18n::setLocale($lang);
        $this->set('lang', substr($lang, 0, strpos($lang, '-')));
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
        // We'll need this Model below...
        $this->loadModel('Setups');

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

            // Now, let's send the setups list to the view (to let the user choose a default one)*
            $setupsList = [];
            foreach($this->Setups->find('all', [
                'fields' => [
                    'id',
                    'title'
                ],
                'conditions' => [
                    'user_id' => $user['id']
                ],
                'order' => [
                    'creationDate' => 'DESC'
                ]
            ]) as $setup) {
                $setupsList += [$setup->id => $setup->title];
            }

            $this->set('setupsList', $setupsList);

            // Let's send to the view the list of timezones as well
            $this->set('timezones', $this->Users->timezones);
        }

        // Before render the view, let's give a new entity for add Setup modal to it
        $newSetupEntity = $this->Setups->newEntity();

        // We'll need also the setups available status
        $status = $this->Setups->status;

        $this->set(compact('newSetupEntity', 'status'));
    }

    public function beforeFilter(Event $event)
    {
        // By default, no page is allowed. Please check special authorizations in the others controller
        $this->Auth->deny();

        // Allow request on public functions
        $this->Auth->allow(['reportBug']);

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


    /* MISCELLANEOUS */
    public function reportBug()
    {
        if($this->request->is('post'))
        {
            $data = $this->request->getData();

            $auth = $this->Auth->user();

            if(isset($data['bugDescription']) and $data['bugDescription'] !== '' and strlen($data['bugDescription'] <= 5000) and ($auth or (isset($data['bugMail']) and $data['bugMail'] !== '')))
            {
                $this->loadModel('Users');
                $email = $this->Users->getEmailObject('beta@mysetup.co', '[mySetup.co] There is a bug !');
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
    /* _____________ */
}
