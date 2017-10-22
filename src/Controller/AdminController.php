<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Admin Controller
 *
 * This controller DOES NOT reflect any data model.
 * It's only the place where we handle 'Controllers.index' monitoring pages...
 */
class AdminController extends AppController
{

    // Let's explicit the fact that we delegate this decision to the `AppController`
    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }

    public function beforeRender(Event $event)
    {

        parent::beforeRender($event);
    }

    public function dashboard()
    {
        // Here we'll just gather some global counters about the data we have !
        $this->loadModel('Setups');
        $stats['count']['users'] = $this->Setups->Users->find()->count();
        $stats['count']['setups'] = $this->Setups->find()->count();
        $stats['count']['comments'] = $this->Setups->Comments->find()->count();
        $stats['count']['resources'] = $this->Setups->Resources->find()->count();

        // Some more information !
        $stats['users']['certified'] = ($stats['count']['users'] !== 0 ? round($this->Setups->Users->find()->where(['mailVerification IS' => null])->count() / $stats['count']['users'] * 100, 2) : 0);
        $stats['users']['twitch'] = ($stats['count']['users'] !== 0 ? round($this->Setups->Users->find()->where(['twitchToken IS NOT' => null])->count() / $stats['count']['users'] * 100, 2) : 0);

        $stats['users']['recentConnected'] = $this->Setups->Users->find()->order(['lastLogginDate' => 'DESC'])->limit(5)->toArray();
        $stats['users']['recentCreated'] = $this->Setups->Users->find()->order(['creationDate' => 'DESC'])->limit(5)->toArray();

        $stats['comments']['recentCreated'] = $this->Setups->Comments->find('all', [
            'contain' => [
                'Users' => [
                    'fields' => [
                        'id',
                        'name'
                    ]
                ],
                'Setups' => [
                    'fields' => [
                        'id',
                        'title'
                    ]
                ]
            ],
            'order' => [
                'dateTime' => 'DESC'
            ],
            'limit' => 5
        ])->all()->toArray();

        $stats['requests']['onGoing'] = $this->Setups->Requests->find('all', [
            'contain' => [
                'Users' => [
                    'fields' => [
                        'id',
                        'name',
                        'mail'
                    ]
                ],
                'Setups' => [
                    'fields' => [
                        'id',
                        'title'
                    ]
                ]
            ],
            'order' => [
                'Requests.id' => 'DESC'
            ]
        ])->all()->toArray();

        $this->set('stats', $stats);
    }

    public function setups()
    {
        $setups = $this->paginate(TableRegistry::get('Setups'), [
            'order' => [
                'creationDate' => 'DESC'
            ],
            'contain' => [
                'Users'
            ]
        ]);

        $this->set('setups', $setups);
    }

    public function users()
    {
        $users = $this->paginate(TableRegistry::get('Users'), [
            'order' => [
                'creationDate' => 'DESC'
            ]
        ]);

        $this->set('users', $users);
    }

    public function comments()
    {
        $comments = $this->paginate(TableRegistry::get('Comments'), [
            'contain' => [
                'Users',
                'Setups'
            ]
        ]);

        $this->set('comments', $comments);
    }

    public function resources()
    {
        $resources = $this->paginate(TableRegistry::get('Resources'), [
            'contain' => [
                'Users',
                'Setups'
            ]
        ]);

        $this->set('resources', $resources);
    }
}
