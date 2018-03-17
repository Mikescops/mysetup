<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Response;
use Cake\Network\Exception\NotFoundException;
use Cake\Cache\Cache;

/**
 * API Controller
 *
 * This controller DOES NOT reflect any data model.
 * It's only the place where we handle our API queries...
 */
class APIController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        // We'll store Twitch promote images generated for some hours !
        Cache::config('TwitchPromoteCacheConfig', [
            'className'   => 'File',
            'duration'    => '+1 day',
            'path'        => CACHE . 'twitchPromote' . DS,
            'prefix'      => 'twitchPromote_'
        ]);
    }

    /* /!\ Each method present in this very file will be authorized /!\ */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // Dangerous. Fortunately, it's applied only locally...
        $this->Auth->allow();
    }

    /* A public endpoint to retrieve setups in a JSON format */
    public function getSetups()
    {
        if($this->request->is('ajax') or $this->request->is('get'))
        {
            $results = TableRegistry::get('Setups')->getSetups([
                'query'    => $this->request->getQuery('q'),
                'featured' => $this->request->getQuery('f'),
                'order'    => $this->request->getQuery('o'),
                'number'   => $this->request->getQuery('n', 8),
                'offset'   => $this->request->getQuery('p'),
                'type'     => $this->request->getQuery('t'),
                'weeks'    => $this->request->getQuery('w')
            ]);

            return new Response([
                'status' => 200,
                'type'   => 'json',
                'body'   => json_encode($results)
            ]);
        }
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
                        'twitchUserId',
                        'uwebsite',
                        'ufacebook',
                        'utwitter'
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

    /* Twitch-promote image generation ! */
    public function twitchPromote()
    {
        // Limits queries to GET request
        if(!$this->request->is('get'))
        {
            die();
        }

        // Verifies authenticity of the setup id specified (would throw a 404 if these entities could not be found)
        $setup = TableRegistry::get('Setups')->get($this->request->getQuery('id'), [
            'fields' => [
                'id',
                'title',
                'modifiedDate'
            ],
            'contain' => [
                'Users' => [
                    'fields' => [
                        'id',
                        'name',
                        'modificationDate'
                    ]
                ]
            ]
        ]);

        // Only logged in users will be able to generate THEIR image
        if($this->Auth->user('id') != $setup->user->id)
        {
            $this->Flash->error(__('You are not authorized to access that location.'));
            return $this->redirect('/');
        }

        // Is the image missing from the cache ? Has the setup recently changed ? Has the user recently changed ?
        $data = Cache::read($setup->id, 'TwitchPromoteCacheConfig');
        if($data === false ||
           $setup->modifiedDate != $data['timestamps']['setup_date'] ||
           $setup->user->modificationDate != $data['timestamps']['user_date'])
        {
            // Seems not, let's generate and store it directly as JPEG-formatted string !

            // At first, we load the profile picture of the setup owner
            $profile_picture = new \Imagick('uploads/files/pics/profile_picture_' . $setup->user->id . '.png');
            $profile_picture->cropThumbnailImage(81, 81);

            // We'll also need this beautiful promote banner for Twitch :)
            $image = new \Imagick('img/twitch_promote.jpg');
            $image->compositeImage($profile_picture, \Imagick::COMPOSITE_COPY, 0, 239);

            // We'll write down the setup title and the user name directly on the image !
            $text = new \ImagickDraw();
            $text->setFillColor('white');
            $text->setFont('fonts/corbel.ttf');

            // Let's make a beautiful truncation of the setup title if it's too long
            if(strlen($setup->title) > 24)
            {
                $matches = [];
                preg_match('/.{1,24}(?:\W|$)/', $setup->title, $matches);
                $setup->title = rtrim($matches[0]) . '...';
            }
            $text->setFontSize(22);
            $image->annotateImage($text, 88, 282, 0, $setup->title);

            // Same thing with the user name :S
            if(strlen($setup->user->name) > 22)
            {
                $matches = [];
                preg_match('/.{1,22}(?:\W|$)/', $setup->user->name, $matches);
                $setup->user->name = rtrim($matches[0]) . '...';
            }
            $text->setFontSize(15);
            $image->annotateImage($text, 88, 310, 0, 'Shared by ' . $setup->user->name);

            // Let's compress just a bit this image
            $image->setImageCompressionQuality(93);

            // Finally, we store the image into a JPEG-formatted raw string
            $data['image'] = $image->getImageBlob();

            // Let's store it into our cache (+ two timestamps to handle entities modification) !
            Cache::write($setup->id, [
                'image'      => $data['image'],
                'timestamps' => [
                    'setup_date' => $setup->modifiedDate,
                    'user_date'  => $setup->user->modificationDate
                ]
            ], 'TwitchPromoteCacheConfig');

            // Destroy elements used above...
            $image->clear();
            $profile_picture->clear();
        }

        // Let's return this image to the user, with a beautiful filename ;)
        $response = new Response([
            'status' => 200,
            'type'   => 'jpeg',
            'body'   => $data['image']
        ]);
        $response->header([
            'Content-Disposition' => 'inline; filename="' . str_replace('...', '', $setup->title) . '".jpeg'
        ]);
        return $response;
    }
}
