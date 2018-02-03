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

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Simple "hooks" hand-made (see `config/routes.php`).
     * This allows us to put some data into the view easily !
     */
    public function home()
    {
        // Set some variables here, and give back the control to the `display()` method
        $Setups = TableRegistry::get('Setups');

        $featuredSetups = $Setups->getSetups(['featured' => true, 'number' => 3]);
        $popularSetups = $Setups->getSetups(['number' => 20, 'type' => 'like']);
        $recentSetups = $Setups->getSetups(['number' => 3]);
        $amdSetups = $Setups->getSetups(['query' => 'amd', 'number' => 10, 'type' => 'like']);
        $nvidiaSetups = $Setups->getSetups(['query' => 'nvidia', 'number' => 10, 'type' => 'like']);

        $recentResources = $Setups->Resources->find()->where(['type' => 'SETUP_PRODUCT'])->order('RAND()')->limit(6)->toArray();

        if($this->Auth->user())
        {
            $mainSetup = $Setups->get($this->Auth->user('mainSetup_id'), [
                'contain' => [
                    'Resources' => [
                        'fields' => [
                            'setup_id',
                            'src'
                        ],
                        'conditions' => [
                            'type' => 'SETUP_FEATURED_IMAGE'
                        ]
                    ]
                ]
            ]);
        }

        $activeUsers = TableRegistry::get('Users')->getActiveUsers(12);

        $this->set(compact('featuredSetups', 'popularSetups', 'recentSetups', 'amdSetups', 'nvidiaSetups', 'activeUsers', 'recentResources', 'mainSetup'));

        $this->display('home');
    }

    public function recent()
    {
        $this->set('setups', TableRegistry::get('Setups')->getSetups(['number' => 6]));

        $this->display('recent');
    }

    public function bugReport()
    {
        if($this->request->is('post'))
        {
            $data = $this->request->getData();

            if(!parent::captchaValidation($data))
            {
                $this->Flash->warning(__('Google\'s CAPTCHA has detected you as a bot, sorry ! If you\'re a REAL human, please re-try :)'));
                return $this->redirect('/');
            }

            $auth = $this->Auth->user();

            if(isset($data['bugDescription']) and $data['bugDescription'] !== '' and strlen($data['bugDescription'] <= 5000) and ($auth or (isset($data['bugMail']) and $data['bugMail'] !== '')))
            {
                $email = $this->loadModel('Users')->getEmailObject('beta@mysetup.co', '[mySetup.co] There is a bug !');
                $email->setTemplate('bug')
                      ->viewVars(['content' => $data['bugDescription'], 'email' => ($auth ? $auth['mail'] : $data['bugMail'])])
                      ->send();

                $this->Flash->success(__('Your bug has been correctly sent ! Thanks for this report :)'));
            }

            else
            {
                $this->Flash->warning(__('You didn\'t report anything (or have missed something) :('));
            }
        }

        $this->display('bugReport');
    }

    public function search($entity = null)
    {
        $query = $this->request->getQuery('q');
        if($query and strlen($query) >= 3)
        {
            switch($entity)
            {
                case 'setups':
                    $results = TableRegistry::get('Setups')->getSetups([
                        'query' => $query,
                        'number' => 9999
                    ], $this->Flash);
                    break;

                case 'users':
                    // TO DO
                    break;

                case 'products':
                    // TO DO
                    break;

                default:
                    $results = null;
                    break;
            }

            if(count($results) == 0)
            {
                $results = 'noresult';
            }
        }

        else
        {
            $results = 'noquery';
        }

        $this->set('results', $results);
        $this->display('search');
    }

    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display(...$path)
    {
        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['display', 'home', 'recent', 'bugReport', 'search']);

        // Another hook to avoid error pages when an user...
        // ...types directly in an (existing) raw address
        if($this->request->controller === 'Pages' and $this->request->action === 'display')
        {
            switch($this->request->getAttribute('params')['pass'][0])
            {
                case 'home':
                    $this->redirect(['action' => 'home']);
                    break;

                case 'recent':
                    $this->redirect(['action' => 'recent']);
                    break;

                case 'search':
                    $this->redirect(['action' => 'search']);
                    break;

                case 'bugReport':
                    $this->redirect(['action' => 'bugReport']);
                    break;

                default:
                    break;
            }
        }
    }
}
