<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Response;
use Cake\Network\Exception\NotFoundException;

/**
 * API Controller
 *
 * This controller DOES NOT reflect any data model.
 * It's only the place where we handle our API queries...
 */
class APIController extends AppController
{
    /* /!\ Each method present in this very file will be authorized /!\ */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // Dangerous. Fortunately, it's applied only locally...
        $this->Auth->allow();
    }

    /* A simple method to handle the new Twitch extension (EBS) */
    public function twitchSetup()
    {
        if($this->request->is('get') and $this->request->getQuery('twitchId'))
        {
            $Users = TableRegistry::get('Users');
            $user = $Users->find('all', [
                'conditions' => [
                    'twitchUserId' => $this->request->getQuery('twitchId')
                ]
            ])->first();

            if($user)
            {
                $setup = $Users->find('all', [
                    'fields' => [
                        'id',
                        'name',
                        'mainSetup_id',
                        'twitchUserId'
                    ],
                    'conditions' => [
                        'Users.twitchUserId' => $this->request->getQuery('twitchId')
                    ],
                    'contain' => [
                        'Setups' => [
                            'conditions' => [
                                'Setups.id' => $user->mainSetup_id
                            ],
                            'Resources' => [
                                'fields' => [
                                    'user_id',
                                    'setup_id',
                                    'src',
                                    'href'
                                ],
                                'conditions' => [
                                    'OR' => [
                                        [
                                            'type' => 'SETUP_FEATURED_IMAGE'
                                        ],
                                        [
                                            'type' => 'SETUP_PRODUCT'
                                        ]
                                    ]
                                ],
                                'sort' => ['type' => 'ASC'],
                            ]
                        ]
                    ]
                ])->first();

                return new Response([
                    'status' => 200,
                    'type' => 'json',
                    'body' => json_encode($setup)
                ]);
            }

            else
            {
                $this->Flash->error(__('You are not authorized to access that location.'));
                // Just throw a 404-like exception here to make the `iframe` voluntary crash
                throw new NotFoundException();
            }
        }
    }

    /* Our embed JS API logic */
    public function embed($id = null)
    {
        // This should be below, but we wanna throw a 404 on the production if the user tries to have access to a non-existing setup...
        $Setups = TableRegistry::get('Setups');
        $setup = $Setups->get($id, [
            'contain' => [
                'Users' => [
                    'fields' => [
                        'id',
                        'name',
                        'verified'
                    ]
                ]
            ]
        ]);

        // The 'view' action will be authorized, unless the setup is not PUBLISHED and the visitor is not its owner, nor an administrator...
        $session = $this->request->session();
        if(!$Setups->isPublic($id) and (!$session->read('Auth.User.id') or !$Setups->isOwnedBy($id, $session->read('Auth.User.id'))) and !parent::isAdminBySession($session))
        {
            $this->Flash->error(__('You are not authorized to access that location.'));
            // Just throw a 404-like exception here to make the `iframe` voluntary crash
            throw new NotFoundException();
        }
        // _________________________________________________________________________________________________________________________________

        // Here we'll get each resource linked to this setup, and set them up into the existing entity
        $setup['resources'] = [
            'products' => $Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_PRODUCT'])->limit(4)->toArray(),
            'featured_image' => $Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_FEATURED_IMAGE'])->first()['src']
        ];
        // ___________________________________________________________________________________________

        $this->set('setup', $setup);
    }
}
