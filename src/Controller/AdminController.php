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
        ])->toArray();

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
        ])->toArray();

        $this->set('stats', $stats);
    }

    public function setups()
    {
        $setups = $this->paginate($this->loadModel('Setups'), [
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
        $users = $this->paginate($this->loadModel('Users'), [
            'order' => [
                'creationDate' => 'DESC'
            ]
        ]);

        $this->set('users', $users);
    }

    public function comments()
    {
        $comments = $this->paginate($this->loadModel('Comments'), [
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
        $resources = $this->paginate($this->loadModel('Resources'), [
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
        foreach($this->loadModel('Users')->find('all')->select(['id', 'name']) as $user)
        {
            $usersList += [$user->id => $user->name];
        }

        if($this->request->is('post'))
        {
            $data = $this->request->getData();
            if(isset($data['user_id']) and (isset($data['message']) and $data['message'] !== ''))
            {
                $this->loadModel('Notifications');

                // Are sending this to everyone ?
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

                    if($i === 0)
                    {
                        $this->Flash->success($nbUsers . ' ' . __n('notification have been sent !', 'notifications have been sent !', $nbUsers));
                    }

                    elseif($i === $nbUsers)
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
