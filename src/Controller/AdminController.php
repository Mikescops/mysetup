<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Admin Controller
 *
 * This controller DOES NOT reflect any data model.
 * It's only the place where we handle 'Controllers.index' monitoring pages...
 */
class AdminController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Setups');
        $this->loadModel('Users');
        $this->loadModel('Comments');
        $this->loadModel('Resources');
        $this->loadModel('Requests');
    }

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
        $stats['count']['users']     = $this->Users->find()->count();
        $stats['count']['setups']    = $this->Setups->find()->count();
        $stats['count']['comments']  = $this->Comments->find()->count();
        $stats['count']['resources'] = $this->Resources->find()->count();

        // Some more information  !
        // We assume that this page can't be accesses if there is not any user
        $stats['users']['certified'] = round($this->Users->find()->where(['mailVerification IS' => null])->count() / $stats['count']['users'] * 100, 2);
        $stats['users']['twitch']    = round($this->Users->find()->where(['twitchToken IS NOT' => null])->count() / $stats['count']['users'] * 100, 2);

        // Don't log users with same dates of creation and last login equal (redundancy)
        $stats['users']['recentConnected'] = $this->Users->find('all', [
            'conditions' => [
                'creationDate !=' => 'lastLogginDate'
            ],
            'limit' => 5,
            'order' => [
                'lastLogginDate' => 'DESC'
            ]
        ])->toArray();
        $stats['users']['recentCreated'] = $this->Users->find('all', [
            'limit' => 5,
            'order' => [
                'creationDate' => 'DESC'
            ]
        ])->toArray();

        $stats['comments']['recentCreated'] = $this->Comments->find('all', [
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
        ])->toArray();
        $stats['requests']['onGoing'] = $this->Requests->find('all', [
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
                'dateTime' => 'DESC'
            ]
        ])->toArray();

        $this->set('stats', $stats);
    }

    public function setups()
    {
        $setups = $this->paginate($this->Setups, [
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
        $users = $this->paginate($this->Users, [
            'order' => [
                'creationDate' => 'DESC'
            ]
        ]);

        $this->set('users', $users);
    }

    public function comments()
    {
        $comments = $this->paginate($this->Comments, [
            'contain' => [
                'Users',
                'Setups'
            ],
            'order' => [
                'dateTime' => 'DESC'
            ]
        ]);

        $this->set('comments', $comments);
    }

    public function resources()
    {
        $resources = $this->paginate($this->Resources, [
            'contain' => [
                'Users',
                'Setups'
            ],
            'order' => [
                'id' => 'DESC'
            ]
        ]);

        $this->set('resources', $resources);
    }

    public function sendNotification()
    {
        // Let's just build an array as ['user_id' => 'user_name'] for each user...
        $usersList = [];
        foreach($this->Users->find()->select(['id', 'name']) as $user)
        {
            $usersList += [$user->id => $user->name];
        }

        if($this->request->is('post'))
        {
            $data = $this->request->getData();
            if(isset($data['user_id']) and (isset($data['message']) and $data['message'] !== ''))
            {
                $this->loadModel('Notifications');

                // Are we sending this to everyone ?
                if($data['user_id'] === 'global')
                {
                    $i = 0;
                    $nbUsers = count($usersList);

                    foreach(array_keys($usersList) as $user_id)
                    {
                        if(!$this->Notifications->createNotification($user_id, $data['message']))
                        {
                            $i++;
                        }
                    }

                    if($i == 0)
                    {
                        $this->Flash->success($nbUsers . ' ' . __n('notification have been sent !', 'notifications have been sent !', $nbUsers));
                    }

                    elseif($i == $nbUsers)
                    {
                        $this->Flash->error(__('No notification could be sent...'));
                    }

                    else
                    {
                        $this->Flash->warning($i . ' / ' . $nbUsers . ' ' . __n('notification couldn\'t be sent...', 'notifications couldn\'t be sent...', $nbUsers));
                    }
                }

                // Or only one user ?
                else
                {
                    if($this->Notifications->createNotification($data['user_id'], $data['message']))
                    {
                        $this->Flash->success(__('The notification has just been sent !'));
                    }

                    else
                    {
                        $this->Flash->error(__('The notification couldn\'t be sent.'));
                    }
                }
            }

            else
            {
                $this->Flash->warning(__('One information is missing to send this notification.'));
            }
        }

        $usersList = ['global' => __('Everyone')] + $usersList;
        $this->set('usersList', $usersList);
    }
}
