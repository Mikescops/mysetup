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
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Cache\Cache;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Captcha');

        $this->loadModel('Users');
        $this->loadModel('Setups');

        // Set home page's cache (12 hours, but GC is voluntary not disabled)
        Cache::setConfig('HomePageCacheConfig', [
            'className'   => 'Apcu',
            'duration'    => '+12 hours',
            'prefix'      => 'homePage_'
        ]);
        // Set a cache with a shorter time to live to handle "recent" entities
        Cache::setConfig('RecentPageCacheConfig', [
            'className'   => 'Apcu',
            'duration'    => '+30 minutes',
            'prefix'      => 'recentPage_'
        ]);
    }

    /**
     * Simple "hooks" hand-made (see `config/routes.php`).
     * This allows us to put some data into the view easily !
     */
    public function home()
    {
        // Set some variables here, and give back the control to the `display()` method (see at the end).

        // Our BD now contains too many elements, we'll store all of them in cache for some times !
        $featuredSetups = Cache::read('featuredSetups', 'HomePageCacheConfig');
        if($featuredSetups === false)
        {
            $featuredSetups = $this->Setups->getSetups(['featured' => true, 'number' => 3]);

            Cache::write('featuredSetups', $featuredSetups, 'HomePageCacheConfig');
        }

        $popularSetups = Cache::read('popularSetups', 'HomePageCacheConfig');
        if($popularSetups === false)
        {
            $popularSetups  = $this->Setups->getSetups(['number' => 6, 'type' => 'like']);

            Cache::write('popularSetups', $popularSetups, 'HomePageCacheConfig');
        }

        $brandSetups = Cache::read('brandSetups', 'HomePageCacheConfig');
        if($brandSetups === false)
        {
            $brandSetups = $this->loadModel('cloud_tags')->getSetupsByRandomTags([
                'type'        => 'PRODUCTS_BRAND',
                'number_tags' => 3
            ]);

            Cache::write('brandSetups', $brandSetups, 'HomePageCacheConfig');
        }

        $randomResources = Cache::read('randomResources', 'HomePageCacheConfig');
        if($randomResources === false)
        {
            $randomResources = $this->Setups->Resources->find('all', [
                'fields' => [
                    'title',
                    'src'
                ],
                'conditions' => [
                    'type' => 'SETUP_PRODUCT'
                ],
                'limit' => 6
            ])
            ->matching('Setups', function($q) {
                return $q->where(['Setups.status' => 'PUBLISHED']);
            })
            ->distinct(['Resources.title', 'Resources.src'])
            ->order('RAND()')
            ->toArray();

            Cache::write('randomResources', $randomResources, 'HomePageCacheConfig');
        }
        /* _____________________________________________________________ */


        $activeUsers = Cache::read('activeUsers', 'HomePageCacheConfig');
        if($activeUsers === false)
        {
            $activeUsers = $this->Users->getActiveUsers(8);

            Cache::write('activeUsers', $activeUsers, 'HomePageCacheConfig');
        }

        /* Let's load less elements on mobile devices */
        if($this->RequestHandler->isMobile())
        {
            // Only 3 popular setups !
            $popularSetups = array_slice($popularSetups, 0, 3);

            // Only 4 resources !
            $randomResources = array_slice($randomResources, 0, 4);

            // Only 4 users !
            $activeUsers = array_slice($activeUsers, 0, 4);
        }
        /* __________________________________________ */

        if($this->Auth->user() and $this->Auth->user('mainSetup_id') != 0)
        {
            $mainSetup = $this->Setups->get($this->Auth->user('mainSetup_id'), [
                'contain' => [
                    'Resources' => [
                        'fields' => [
                            'setup_id',
                            'src'
                        ],
                        'conditions' => [
                            'type' => 'SETUP_FEATURED_IMAGE'
                        ]
                    ],
                    'Users' => [
                        'fields' => [
                            'id',
                            'name',
                            'modificationDate'
                        ]
                    ]
                ]
            ]);
        }

        $this->set(compact('featuredSetups', 'popularSetups', 'brandSetups', 'activeUsers', 'randomResources', 'mainSetup'));

        $this->display('home');
    }

    public function recent()
    {
        $recentSetups = Cache::read('recentSetupsPage', 'RecentPageCacheConfig');
        if($recentSetups === false)
        {
            $recentSetups = $this->Setups->getSetups(['number' => 16]);

            Cache::write('recentSetupsPage', $recentSetups, 'RecentPageCacheConfig');
        }

        $this->set('setups', $recentSetups);

        $this->display('recent');
    }

    public function staffpicks()
    {
        $this->set('setups', $this->Setups->getSetups(['featured' => true, 'number' => 20]));

        $this->display('staffpicks');
    }

    public function bugReport()
    {
        if($this->request->is('post'))
        {
            $data = $this->request->getData();

            if(!$this->Captcha->validation($data))
            {
                $this->Flash->warning(__('Google\'s CAPTCHA has detected you as a bot, sorry ! If you\'re a REAL human, please re-try :)'));
                return $this->redirect('/');
            }

            $auth = $this->Auth->user();

            if(isset($data['bugDescription']) and $data['bugDescription'] !== '' and strlen($data['bugDescription'] <= 5000) and ($auth or (isset($data['bugMail']) and $data['bugMail'] !== '')))
            {
                $email = $this->loadModel('Users')->getEmailObject('beta@mysetup.co', '[mySetup.co] There is a bug !');
                $email->setTemplate('bug')
                      ->setViewVars(['content' => $data['bugDescription'], 'email' => ($auth ? $auth['mail'] : $data['bugMail'])])
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
        $query = trim($this->request->getQuery('q'));
        if($query and strlen($query) >= 2)
        {
            $this->loadModel('Resources');

            switch($entity)
            {
                case 'setups':
                    $results = $this->Setups->getSetups(['query' => $query]);
                    break;

                case 'users':
                    $results = $this->Users->getUsers($query);

                    // Redirect to user profile if the result is only one entity, matching its name
                    if(count($results) == 1 && strtolower($results[0]->name) === strtolower($query))
                    {
                        $this->Flash->success(__('We have found the user you are looking for !'));
                        return $this->redirect(['controller' => 'Users', 'action' => 'view', $results[0]->id]);
                    }

                    break;

                case 'resources':
                    $results = $this->Resources->getResources($query);

                    // Redirect to home search if the result is only one resource
                    if(count($results) == 1)
                    {
                        $this->Flash->success(__('We have found the component you are looking for !'));
                        return $this->redirect('/search/?q=' . $query);
                    }

                    break;

                default:
                    // See `setPatterns()` of `/search/:entity` route.
                    $users     = $this->Users->getUsers($query);
                    $setups    = $this->Setups->getSetups(['query' => $query]);
                    $resources = $this->Resources->getResources($query);

                    /*
                        Redirect to user profile if :
                        * The result is only one user entity
                        * The query matches its name
                        * The resulted setups are only his
                        * No resource can be associated with this query
                    */
                    if(count($users) == 1
                    && strtolower($users[0]->name) === strtolower($query)
                    && count($setups) == count($users[0]['setups'])
                    && count($resources) == 0)
                    {
                        $this->Flash->success(__('We have found the user you are looking for !'));
                        return $this->redirect(['controller' => 'Users', 'action' => 'view', $users[0]->id]);
                    }

                    // Handle empty results here
                    if(count($setups) == 0 && count($resources) == 0 && count($users) == 0)
                    {
                        $results = null;
                    }

                    else
                    {
                        // Well well this is X-mas, let's set a labeled multi-dimensional array with all of these results
                        $results = [
                            'users' => $users,
                            'setups' => $setups,
                            'resources' => $resources
                        ];
                    }

                    break;
            }

            if(!$results || count($results) == 0)
            {
                $results = ['error' => 'noresult'];
            }

            elseif($entity)  // Does not match here if it matched the `default` case above
            {
                // `$entity` will label the type of results present
                $results = [$entity => $results];
            }
        }

        else
        {
            $results = ['error' => 'noquery'];
        }

        // Send data to the View (please, refer to previous `$results` assignments above)
        $this->set('results', $results);

        $this->display('search');
    }

    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
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

        $this->Auth->allow(['display', 'home', 'recent', 'staffpicks', 'bugReport', 'search']);

        // Another hook to avoid error pages when an user...
        // ...types directly in an (existing) raw address
        if($this->request->getParam('controller') === 'Pages' and $this->request->getParam('action') === 'display')
        {
            switch($this->request->getAttribute('params')['pass'][0])
            {
                case 'home':
                    $this->redirect(['action' => 'home']);
                    break;

                case 'recent':
                    $this->redirect(['action' => 'recent']);
                    break;

                case 'staffpicks':
                    $this->redirect(['action' => 'staffpicks']);
                    break;

                case 'bugReport':
                    $this->redirect(['action' => 'bugReport']);
                    break;

                case 'search':
                    $this->redirect(['action' => 'search']);
                    break;

                default:
                    break;
            }
        }
    }
}
