<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
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

        $this->loadModel('Setups');

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
            // By default, we'll return a maximum of 8 results
            $n = $this->request->getQuery('n', 8);
            if($n < 0)
            {
                $n = 0;
            }
            elseif($n > 16)
            {
                $n = 16;
            }

            $results = $this->Setups->getSetups([
                'query'    => $this->request->getQuery('q'),
                'featured' => $this->request->getQuery('f'),
                'order'    => $this->request->getQuery('o'),
                'number'   => $n,
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
        $twitch_id = $this->request->getQuery('twitchId');

        if($this->request->is('get') and $twitch_id)
        {
            $this->loadModel('Users');

            $user = $this->Users->find('all', [
                'fields' => [
                    'id',
                    'name',
                    'mainSetup_id',
                    'twitchUserId'
                ],
                'conditions' => [
                    'twitchUserId' => $twitch_id
                ],
                'contain' => [
                    'Setups' => [
                        'Resources' => [
                            'fields' => [
                                'user_id',
                                'setup_id',
                                'id',
                                'title',
                                'src'
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
                            'sort' => [
                                'type' => 'ASC'
                            ]
                        ]
                    ]
                ]
            ])->first();

            // The user has been found !
            if($user !== null)
            {
                // The user got a main setup set !
                if($user->mainSetup_id != 0)
                {
                    // We iterate over the user's setups to keep only the main one.
                    foreach($user->setups as $key => $setup)
                    {
                        if($setup->id != $user->mainSetup_id)
                        {
                            unset($user->setups[$key]);
                        }
                    }

                    // Use `array_values` to reorder the keys (useful when some setups have been deleted above).
                    $user->setups = array_values($user->setups);

                    // Last check to ensure the setup is not unpublished !
                    if($user->setups[0]->status !== 'PUBLISHED')
                    {
                        // "(draft)|(rejected)_main_setup"
                        $results = ['error' => strtolower($user->setups[0]->status) . '_main_setup'];
                    }

                    else
                    {
                        $results = $user;
                    }
                }

                else
                {
                    // Does the user have a setup "set-able" as main ?
                    if(!count($user->setups))
                    {
                        $results = ['error' => 'no_setup'];
                    }

                    else
                    {
                        $results = ['error' => 'no_main_setup_set'];
                    }
                }
            }

            else
            {
                $results = ['error' => 'user_not_found'];
            }
        }

        else
        {
            $results = ['error' => 'bad_query'];
        }

        return new Response([
            'status' => 200,
            'type' => 'json',
            'body' => json_encode($results)
        ]);
    }

    /* Our embed JS API logic */
    public function embed($id = null)
    {
        // This should be below, but we wanna throw a 404 on the production if the user tries to have access to a non-existing setup...
        $setup = $this->Setups->get($id, [
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
        if(!$this->Setups->isPublic($id) and
           (!$session->read('Auth.User.id') or!$this->Setups->isOwnedBy($id, $session->read('Auth.User.id'))) and
           !parent::isAdminBySession($session))
        {
            // Just throw a 404-like exception here to make the `iframe` voluntary crash
            throw new NotFoundException();
        }
        // _________________________________________________________________________________________________________________________________

        // Here we'll get each resource linked to this setup, and set them up into the existing entity
        $setup['resources'] = [
            'products' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_PRODUCT'])->limit(4)->toArray(),
            'featured_image' => $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_FEATURED_IMAGE'])->first()['src']
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
        $setup = $this->Setups->get($this->request->getQuery('id'), [
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

        // Only logged in users will be able to generate THEIR image (or administrators)
        if($this->Auth->user('id') != $setup->user->id && !$this->Auth->user('admin'))
        {
            throw new NotFoundException();
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
        $setup->title = preg_replace(['/\W+/', '/\.\.\./', '/^(.*)_$/'], ['_', '', '$1'], $setup->title);
        return $response->withHeader(
            'Content-Disposition',
            'inline; filename="' . $setup->title . '".jpeg'
        );
    }

    /*
     * This method is here to bother Twitch and its limitation for links pointing to other domains.
     * We simply fetch a product from its ID, and redirect the client to its URL !
     */
    public function pLink()
    {
        // Allows only GET requests
        if(!$this->request->is('get'))
        {
            // Just throw a 404-like exception here...
            throw new NotFoundException();
        }

        // This would throw a 404 if the Resource ID does not exist for a setup product !
        $resource = $this->Setups->Resources->get($this->request->getQuery('id'), [
            'fields' => [
                'href'
            ],
            'conditions' => [
                'type' => 'SETUP_PRODUCT'
            ]
        ]);

        return $this->redirect(htmlspecialchars_decode(urldecode($resource->href)));
    }
}
