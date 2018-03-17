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
                'title',
                'user_id'
            ],
            'contain' => [
                'Users' => [
                    'fields' => [
                        'id',
                        'name'
                    ]
                ]
            ]
        ]);

        // Only logged in users will be able to generate THEIR image
        if($this->Auth->user() === null || $this->Auth->user('id') != $setup->user_id)
        {
            $this->Flash->error(__('You are not authorized to access that location.'));
            return $this->redirect('/');
        }

        $pfile = 'uploads/files/pics/profile_picture_' . $setup->user->id . '.png';
        $profile = ImageCreateFromPNG($pfile);
        list($pwidth, $pheight) = GetImageSize($pfile);

        $image = ImageCreateFromJPEG('img/partner_banner.jpg');
        ImageAlphaBlending($image, true);
        ImageSaveAlpha($image, true);
        ImageCopyResampled($image, $profile, 0, 239, 0, 0, 81, 81, $pwidth, $pheight);

        $color = ImageColorAllocate($image, 255, 255, 255);

        // Write names.
        if(strlen($setup->title) > 20)
        {
            $setup = wordwrap(substr($setup->title, 0, 38), 20, "\n");
            ImageTTFText($image, 15, 0, 88, 260, $color, 'fonts/corbel.ttf', $setup->title);
            ImageTTFText($image, 11, 0, 88, 308, $color, 'fonts/corbel.ttf', 'Shared by ' . $setup->user->name);
        }

        else
        {
            ImageTTFText($image, 17, 0, 88, 277, $color, 'fonts/corbel.ttf', $setup->title);
            ImageTTFText($image, 11, 0, 88, 300, $color, 'fonts/corbel.ttf', 'Shared by ' . $setup->user->name);
        }

        header('Content-type: image/jpeg');
        header('Content-Disposition: inline; filename="' . $setup->title . '.jpeg"');

        // Return output.
        ImageJPEG($image, NULL, 93);
        ImageDestroy($image);
    }
}
