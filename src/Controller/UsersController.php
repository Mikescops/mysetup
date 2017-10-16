<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
use Cake\Event\Event;
use Cake\I18n\Time;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // If the visitor is not the owner (nor an admin), let's send only to the View the PUBLISHED setups (+ the count will be good with this method ;))
        $conditions = null;
        if($id != $this->request->session()->read('Auth.User.id') and !parent::isAdminBySession($this->request->session()))
        {
            $conditions = ['Setups.status' => 'PUBLISHED'];
        }

        $user = $this->Users->get($id, [
            'contain' => [
                'Setups' => [
                    'sort' => [
                        'Setups.creationDate' => 'DESC'
                    ],
                    'fields' => [
                        'id',
                        'title',
                        'user_id',
                        'status'
                    ],
                    'conditions' => $conditions,
                    'Resources' => [
                        'conditions' => [
                            'type' => 'SETUP_FEATURED_IMAGE'
                        ],
                        'fields' =>[
                            'src',
                            'setup_id'
                        ]
                    ],
                    'Likes' => [
                        'fields' => [
                            'id',
                            'setup_id'
                        ]
                    ]
                ],
                'Likes' => [
                    'fields' => [
                        'id',
                        'user_id'
                    ]
                ],
                'Comments' => [
                    'fields' => [
                        'id',
                        'user_id'
                    ]
                ]
            ]
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {

            $data = $this->request->getData();

            if(!parent::captchaValidation($data))
            {
                $this->Flash->warning(__('Google\'s CAPTCHA has detected you as a bot, sorry ! If you\'re a REAL human, please re-try :)'));
                return $this->redirect('/');
            }

            if($data['password'] === $data['password2'])
            {
                $user = $this->Users->patchEntity($user, $data);

                if(!isset($user['preferredStore']) or $user['preferredStore'] === '')
                {
                    $user['preferredStore'] = 'US';
                }

                // Here we'll assign a random id to this new user
                $user->id = $this->Users->getNewRandomID();

                // ... and generate a token to verify its mail address =)
                $user->mailVerification = $this->Users->getRandomString(32);

                // By default, an user will have as timezone the Europe/London one (GMT + 0)
                $user->timeZone = 'Europe/London';

                // By default, no user social networks
                $user->uwebsite  = null;
                $user->ufacebook = null;
                $user->utwitter  = null;
                $user->utwitch   = null;

                // By default user is not verified
                $user->verified = 0;

                if($this->Users->save($user))
                {
                    // Here we'll try to retrieve a Gravatar avatar linked to this email address
                    // If it fails, we'll fall back on the default egg head
                    if(!$this->Users->saveRemoteProfilePicture($user->id, 'https://secure.gravatar.com/avatar/' . md5(strtolower(trim($user->mail))) . '?s=100&d=404', $this->Flash))
                    {
                        $this->Users->saveDefaultProfilePicture($user, $this->Flash);
                    }

                    $email = $this->Users->getEmailObject($user->mail, 'Verify your account !');
                    $email->setTemplate('verify')
                          ->viewVars(['name' => $data['name'], 'id' => $user->id, 'token' => $user->mailVerification])
                          ->send();

                    $this->Flash->success(__('Your account has been created, check your email to verify your account'));
                    return $this->redirect('/');
                }

                else
                {
                    $this->Flash->error(__('The user could not be saved. (Is this address already taken ?)'));
                    return $this->redirect($this->referer());
                }
            }

            else
            {
                $this->Flash->error(__('These passwords do not match. Please try again.'));
                return $this->redirect($this->referer());
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id);

        if($this->request->is(['patch', 'post', 'put']))
        {
            $data = $this->request->getData();

            // Here we'll block the 'Users.mail' modification
            if(!isset($data['mail']) || $data['mail'] != $user['mail'])
            {
                $data['mail'] = $user['mail'];
            }

            if(!isset($data['secret']) || $data['secret'] === '')
            {
                $data['password'] = $user['password'];
            }

            else
            {
                if($data['secret'] !== $data['secret2'])
                {
                    $this->Flash->error(__('These passwords do not match. Please try again.'));
                    return $this->redirect($this->referer());
                }

                else
                {
                    $data['password'] = $data['secret'];
                }
            }

            if(!isset($data['verified']) or !parent::isAdminBySession($this->request->session()))
            {
                $data['verified'] = $user['verified'];
            }

            // Let's check the social inputs links !
            if(isset($data['uwebsite']) and $data['uwebsite'] != '' and !isset(parse_url($data['uwebsite'])['host']))
            {
                $data['uwebsite'] = $user['uwebsite'];
                $this->Flash->warning(__('One of your social inputs URL does not fit with its field. It has not been saved'));
            }
            if(isset($data['ufacebook']) and $data['ufacebook'] != '')
            {
                $temp = parse_url($data['ufacebook']);

                if(!isset($temp['host']) or $temp['host'] !== 'facebook.com')
                {
                    $data['ufacebook'] = $user['ufacebook'];
                    $this->Flash->warning(__('One of your social inputs URL does not fit with its field. It has not been saved'));
                }
            }
            if(isset($data['utwitter']) and $data['utwitter'] != '')
            {
                $temp = parse_url($data['utwitter']);

                if(!isset($temp['host']) or $temp['host'] !== 'twitter.com')
                {
                    $data['utwitter'] = $user['utwitter'];
                    $this->Flash->warning(__('One of your social inputs URL does not fit with its field. It has not been saved'));
                }
            }
            if(isset($data['utwitch']) and $data['utwitch'] != '')
            {
                $temp = parse_url($data['utwitch']);

                if(!isset($temp['host']) or !in_array($temp['host'], ['twitch.tv', 'go.twitch.tv']))
                {
                    $data['utwitch'] = $user['utwitch'];
                    $this->Flash->warning(__('One of your social inputs URL does not fit with its field. It has not been saved'));
                }
            }

            $user = $this->Users->patchEntity($user, $data);

            if($this->Users->save($user))
            {
                if(isset($data['picture']) and $data['picture'] !== '' and (int)$data['picture']['error'] === 0)
                {
                    $this->Users->saveProfilePicture($data['picture'], $user, $this->Flash);
                }

                // The user may have changed its preferred store (language) and / or its timezone, let's update this into the server's session
                $this->Users->prepareSessionForUser($this->request->session(), $user);

                $this->Flash->success(__('The user has been updated.'));
            }

            else
            {
                $this->Flash->error(__('The user could not be updated. Please, try again.'));
            }

            return $this->redirect($this->referer());
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);

        if($user and !parent::isAdmin($user))
        {
            if($this->Users->delete($user)) {
                $this->Flash->success(__('The user has been deleted.'));
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
        }

        else
        {
            $this->Flash->error(__('You just CANNOT delete the admin user of the website...'));
        }

        if(!parent::isAdminBySession($this->request->session()))
        {
            return $this->redirect($this->Auth->logout());
        }

        else
        {
            return $this->redirect($this->referer());
        }
    }

    public function login()
    {
        if($this->request->session()->check('Auth.User'))
        {
            $this->Flash->warning(__('You are already logged in.'));
            return $this->redirect('/');
        }

        if($this->request->is('post'))
        {
            if($user = $this->Auth->identify())
            {
                if($user['mailVerification'])
                {
                    $this->Flash->warning(__('Your account is not verified, check your emails !'));
                    return $this->redirect($this->referer());
                }

                else
                {
                    // Let's save the current date / time in the DB...
                    $user = $this->Users->get($user['id']);
                    $user->lastLogginDate = Time::now();
                    // The `modificationDate` value won't change as we've just updated the `lastLogginDate` value...
                    $user->setDirty('modificationDate', true);
                    $this->Users->save($user);

                    $this->Flash->success(__('You are successfully logged in !'));

                    $this->Users->prepareSessionForUser($this->request->session(), $user);

                    $this->Auth->setUser($user);
                    return $this->redirect($this->Auth->redirectUrl());
                }
            }

            else
            {
                $this->Flash->error(__('Username or password is incorrect'));
            }
        }
    }

    public function logout()
    {
        if($this->request->session()->check('Auth.User'))
        {
            $this->Flash->success(__('You are now logged out, see you soon !'));
            return $this->redirect($this->Auth->logout());
        }

        else
        {
            $this->Flash->warning(__('You can\'t logout because you\'re not connected.'));
            return $this->redirect($this->referer());
        }
    }

    public function resetPassword()
    {
        $data = $this->request->getData();

        if($data['mailReset'] !== '')
        {
            $user = $this->Users->find()->where(['mail' => $data['mailReset']])->first();

            if($user)
            {
                // Let's generate a new random password, and send it to the email address specified
                $temp = $this->Users->getRandomString();
                $user->password = $temp;
                if($this->Users->save($user))
                {
                    $email = $this->Users->getEmailObject($data['mailReset'], 'Your password has been reseted !');
                    $email->setTemplate('password')
                          ->viewVars(['name' => $user->name, 'password' => $temp])
                          ->send();

                    $this->Flash->success(__("An email has been sent to this email address !"));
                    return $this->redirect(['action' => 'login']);
                }

                else
                {
                    $this->Flash->error(__("Internal error, please try again."));
                    return $this->redirect(['action' => 'login']);
                }
            }

            else
            {
                $this->Flash->warning(__("This email address does not exist in our database. Are you sure you that you have an account ?"));
                return $this->redirect(['action' => 'login']);
            }
        }

        else
        {
            $this->Flash->error(__("Internal error, please try again."));
            return $this->redirect(['action' => 'login']);
        }
    }

    public function verifyAccount($id = null, $token = null)
    {
        if($this->request->is('get'))
        {
            $user = $this->Users->find()->where(['id' => $id])->first();

            if($user)
            {
                if($user['mailVerification'] == $token)
                {
                    $user['mailVerification'] = null;

                    // Let's set his first loggin date btw
                    $user['lastLogginDate'] = Time::now();

                    $this->Users->save($user);

                    // Let's add some notifications to this new user
                    $this->Users->Notifications->createNotification($user->id, __('We advise you to edit your profile (use the panel at the top)...'));
                    $this->Users->Notifications->createNotification($user->id, __('... in order to add a profile picture ! You\'d look better :P'));

                    $this->Flash->success(__('Your account is now activated, you\'re now logged in ;)'));

                    $this->Users->prepareSessionForUser($this->request->session(), $user);

                    $this->Auth->setUser($user);
                    return $this->redirect($this->Auth->redirectUrl());
                }

                else
                {
                    $this->Flash->error(__('Your token is invalid'));
                    return $this->redirect('/');
                }
            }

            else
            {
                $this->Flash->error(__('This request is invalid'));
                return $this->redirect('/');
            }
        }
    }

    public function twitch()
    {
        // Let's get our Twitch's API IDs
        $client_id     = $this->Users->getTwitchAPIID();
        $client_secret = $this->Users->getTwitchAPISecret();

        // The Twitch's API authorization we want
        $scope = 'user_read';

        // A simple test to check the URL validity (still : "F*CK Twitch's API")
        if(!$_GET or !isset($_GET['code']) or (!isset($_GET['scope']) or $_GET['scope'] !== $scope) or !isset($_GET['state']))
        {
            // This bastard only deserves to `die()`
            die();
        }

        $http = new Client();

        // Let's ask Twitch for this client's token
        $response = $http->post('https://api.twitch.tv/kraken/oauth2/token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&grant_type=authorization_code&redirect_uri=' . 'https://mysetup.co/twitch/' . '&code=' . $_GET['code'] . '&state=' . $_GET['state']);

        // Here we check if the response fit what we expect, and if we're allowed to get the user data
        if(!$response or !isset($response->json['scope'][0]) or !$response->json['scope'][0] === $scope)
        {
            $this->Flash->warning(__('We could not access Twitch\'s data'));
            return $this->redirect('/');
        }

        // The very token, super-sensitive data !
        $token = $response->json['access_token'];

        // We run a query through Twitch's API, in order to gather some information about this user
        $response = $http->get('https://api.twitch.tv/kraken/user', ['q' => 'widget'], [
            'headers' => [
                'Accept' => 'application/vnd.twitchtv.v5+json',
                'Client-ID' => $client_id,
                'Authorization' => 'OAuth ' . $token
            ]
        ]);

        if(!$response or !$response->json)
        {
            $this->Flash->warning(__('We could not access Twitch\'s data'));
            return $this->redirect('/');
        }

        // Let's try to get an user entity linked to the Twitch address email of this user
        $user = $this->Users->find()->where(['mail' => $response->json['email']])->first();

        if($user)
        {
            // This is the problem, when the user log in, Twitch gives us a brand new token for this person, but we don't need it :S
            // So, again, two cases, we save this token as a new one, and if one was present beforehand, we revoke it ;)
            if($user->twitchToken)
            {
                // Connection procedure
                $this->Flash->success(__('You are successfully logged in !'));

                // We identified this user, we don't need the additional token generated by Twitch
                $http->post('https://api.twitch.tv/kraken/oauth2/revoke?client_id=' . $client_id . '&client_secret=' . $client_secret . '&token=' . $user->twitchToken);
                $user->twitchToken = null;
            }

            else
            {
                // This is a[n] [ambiguous] toast message (it does not fit entirely with the reality)
                $this->Flash->success(__('Your account has just been linked to your Twitch one !'));
            }

            // ... let's now patch this new token, and save the entity
            $user->twitchToken = $token;
            // The `modificationDate` value won't change as we've just replaced the token
            $user->setDirty('modificationDate', true);
            if(!$this->Users->save($user))
            {
                $this->Flash->error(__('An error occurred while logging you in'));
                return $this->redirect('/');
            }
        }

        else
        {
            // The user has not been found, let's create a new account for him (if its Twitch email address has been verified of course) !
            if(!$response->json['email_verified'])
            {
                $this->Flash->warning(__('The email address of your Twitch account has not been verified. We can\'t create your account yet'));
                return $this->redirect($this->referer());
            }

            $user = $this->Users->newEntity();

            $user->id             = $this->Users->getNewRandomID();
            $user->name           = $response->json['display_name'];
            $user->mail           = $response->json['email'];
            $user->password       = $this->Users->getRandomString();
            $user->preferredStore = strtoupper((substr($_GET['state'], 0, 2)));
            $user->timeZone       = 'Europe/London';
            $user->twitchToken    = $token;
            $user->twitchUserId   = $response->json['_id'];
            $user->verified       = 0;

            // Fix a very weird behavior (un-debug-gable) if the `EN` language comes from the JS
            // As this Amazon Store does not exist, we just replace it by the `US` one
            if($user->preferredStore === 'EN')
            {
                $user->preferredStore = 'US';
            }

            if($this->Users->save($user))
            {
                // We'll use the Twitch API to retrieve its profile picture :O
                if(!$this->Users->saveRemoteProfilePicture($user->id, $response->json['logo'], $this->Flash))
                {
                    $this->Users->saveDefaultProfilePicture($user, $this->Flash);
                }

                $email = $this->Users->getEmailObject($user->mail, 'Your account has been created !');
                $email->setTemplate('welcome')
                      ->viewVars(['name' => $user->name])
                      ->send();

                $this->Flash->success(__('Your account is now activated, you\'re now logged in ;)'));

                // Let's add some notifications to this new user
                $this->Users->Notifications->createNotification($user->id, __('We advise you to edit your profile (use the panel at the top)...'));
                $this->Users->Notifications->createNotification($user->id, __('... in order to add a profile picture ! You\'d look better :P'));
            }

            else
            {
                $this->Flash->error(__('An error occurred while saving your account'));
                return $this->redirect('/');
            }
        }

        // Just before logging this user in, let's save the current date time into the DB
        $user->lastLogginDate = Time::now();
        $this->Users->save($user);

        $this->Users->prepareSessionForUser($this->request->session(), $user);

        // Let's log this user in !
        $this->Auth->setUser($user);
        return $this->redirect($this->Auth->redirectUrl());
    }

    /* __________ */

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['logout', 'add', 'resetPassword', 'view', 'verifyAccount', 'twitch']);
    }

    public function isAuthorized($user)
    {
        if(isset($user) && in_array($this->request->action, ['edit', 'delete']) && (int)$this->request->getAttribute('params')['pass'][0] === $user['id'])
        {
            return true;
        }

        return parent::isAuthorized($user);
    }
}
