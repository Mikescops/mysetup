<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Http\Client;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Routing\Router;

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
        if($id != $this->Auth->user('id') and !parent::isAdminBySession($this->request->session()))
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
                        'status',
                        'featured',
                        'like_count'
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
        if ($this->request->is('post')) {

            $data = $this->request->getData();

            if(!$this->Captcha->validation($data))
            {
                $this->Flash->warning(__('Google\'s CAPTCHA has detected you as a bot, sorry ! If you\'re a REAL human, please re-try :)'));
                return $this->redirect('/');
            }

            if($data['password'] === $data['password2'])
            {
                $user = $this->Users->patchEntity($this->Users->newEntity(), $data);

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

                // No setup exists yet...
                $user->mainSetup_id = 0;

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

                if($this->Auth->user('id') == $user->id)
                {
                    // The user may have changed its preferred store (language) and / or its timezone, let's update this into the server's session
                    $this->Users->prepareSessionForUser($this->request->session(), $user);

                    // The user entity has changed, let's update the session one to reflect the modifications everywhere !
                    $this->Users->synchronizeSessionWithUserEntity($this->request->session(), $user, parent::isAdmin($user));
                }

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

        // Only disconnects someone who is deleting himself (and not an admin !) :O
        if($user->id == $this->Auth->user('id'))
        {
            return $this->redirect($this->Auth->logout());
        }

        else
        {
            return $this->redirect('/');
        }
    }

    public function login()
    {
        if($this->Auth->user() !== null)
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
                    $this->Users->synchronizeSessionWithUserEntity($this->request->session(), $user, parent::isAdmin($user));

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
        if($this->Auth->user() !== null)
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
                // So as to limit race condition exploitation on the "reset password" feature, let's wait a bit before generating a new password.
                // The duration will be picked randomly between `0` and `3` seconds.
                sleep(mt_rand(0, 3));

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
                if($user->mailVerification === $token)
                {
                    $user->mailVerification = null;

                    // Let's set his first login date btw
                    $user->lastLogginDate = Time::now();

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
        if(!$this->request->getQuery('code') or (!$this->request->getQuery('scope') or $this->request->getQuery('scope') !== $scope) or !$this->request->getQuery('state'))
        {
            // Let's throw a 404 if the URL does not fit what we expect...
            throw new NotFoundException();
        }

        // We'll use this object to communicate with the Twitch API
        $http = new Client();

        // Let's ask Twitch for this client's token
        $response = $http->post('https://api.twitch.tv/kraken/oauth2/token', [
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => Router::url('/twitch/', true),
            'code'          => $this->request->getQuery('code'),
            'state'         => $this->request->getQuery('state')
        ]);

        // Here we check if the response fit what we expect, and if we're allowed to get the user data
        if(!$response or !isset($response->json['scope']) or !in_array($scope, $response->json['scope']))
        {
            $this->Flash->warning(__('We could not access Twitch\'s data'));
            return $this->redirect('/');
        }

        $token = $response->json['access_token'];

        // We run a query through Twitch's API, in order to gather some information about this user
        $response = $http->get('https://api.twitch.tv/kraken/user', [], [
            'headers' => [
                'Accept'        => 'application/vnd.twitchtv.v5+json',
                'Client-ID'     => $client_id,
                'Authorization' => 'OAuth ' . $token
            ]
        ]);

        if(!$response or !isset($response->json))
        {
            $this->Flash->warning(__('We could not access Twitch\'s data'));
            return $this->redirect('/');
        }

        // Let's try to get an user entity linked to the Twitch address email or the Twitch user_ID of this user
        $user = null;
        $user_by_email     = $this->Users->find()->where(['mail'         => $response->json['email']])->first();
        $user_by_twitch_ID = $this->Users->find()->where(['twitchUserId' => $response->json['_id']])->first();

        // Let's first check if at least ONE OF THE TWO ENTITIES is non-null
        if($user_by_email !== null || $user_by_twitch_ID !== null)
        {
            // If we got an user from its Twitch ID...
            if($user_by_twitch_ID !== null)
            {
                $user = $user_by_twitch_ID;

                // We save temporarily the "old" token to revoke it later (if everything went good)
                $oldToken = $user->twitchToken;

                // The token will be revoked below, so we replace it within the DB by the one we just received
                $user->twitchToken = $token;

                // If we got two user entities...
                if($user_by_email !== null)
                {
                    // They ARE SUPPOSED to be the same one...
                    if($user_by_email->id !== $user_by_twitch_ID->id)
                    {
                        // But, is the DB f*cked up (?)
                        // Or the user has 2 different accounts (one "regular", and another one linked to Twitch) ?
                        $this->Flash->error(__('Your (new) Twitch email address is already linked to a regular account. Please contact an administrator'));
                        return $this->redirect('/');
                    }

                    $this->Flash->success(__('You are successfully logged in !'));
                }

                // The user exists in the DB but does not have a Twitch user_ID ==> The user is linking its accounts !
                else
                {
                    // The user has changed (and verified) the new email address of its Twitch account, let's update the email stored in our DB
                    if($response->json['email_verified'])
                    {
                        $user->mail = $response->json['email'];

                        // Whatever the account status was, this email address can be considered verified...
                        $user->mailVerification = null;

                        $this->Flash->success(__('Your email address has been synchronized with the one from your Twitch account'));
                    }
                    else
                    {
                        // If the address is still not verified, draw a Flash !
                        $this->Flash->warning(__('The new email address of your Twitch account has not been verified. Thus, we haven\'t updated it here yet'));
                    }
                }

                // This is the problem, when the user logs in, Twitch gives us a brand new token for him, but we don't need it :S
                // So, we revoke the token we had, and store the new one (see above) !
                $http->post('https://api.twitch.tv/kraken/oauth2/revoke', [
                    'client_id'     => $client_id,
                    'client_secret' => $client_secret,
                    'token'         => $oldToken
                ]);
            }

            else // if($user_by_email !== null && $user_by_twitch_ID === null)
            {
                $user = $user_by_email;

                // It's possible that this user already has an UNVERIFIED account...
                if($user->mailVerification !== null)
                {
                    // ... with also an unverified email address on its Twitch account :/
                    if(!$response->json['email_verified'])
                    {
                        // If it's true, let's send him a (new) email to verify it, and redirect him with a message
                        $user->mailVerification = $this->Users->getRandomString(32);
                        $this->Users->save($user);
                        $email = $this->Users->getEmailObject($user->mail, 'Verify your account !');
                        $email->setTemplate('verify')
                              ->viewVars(['name' => $user->name, 'id' => $user->id, 'token' => $user->mailVerification])
                              ->send();
                        $this->Flash->warning(__('Your unverified existing account cannot be linked to Twitch because your email address is not verified either. Please verify it before retrying (you will receive a new email soon).'));
                        return $this->redirect('/');
                    }
                    else
                    {
                        // If the Twitch address is verified, let's do the same into our DB
                        $user->mailVerification = null;
                        $this->Flash->success(__('Your existing account has been verified during this first Twitch connection !'));
                    }
                }

                // If we could verify its email address, let's just "link" its account with Twitch by setting these data
                $user->twitchToken  = $token;
                $user->twitchUserId = $response->json['_id'];

                // This is a[n] [ambiguous] toast message (it does not fit entirely with the reality)
                $this->Flash->success(__('Your account has just been linked to your Twitch one !'));
            }

            // On this "code branch", whatever the path previously taken, the account is being updated, but not directly by the user.
            // Thus, the `modificationDate` field won't change as we've just set a token plus maybe an ID.
            $user->setDirty('modificationDate', true);
        }

        // The user HAS NOT BEEN FOUND, let's create a new account for him (if its Twitch email address has been verified of course) !
        else
        {
            if(!$response->json['email_verified'])
            {
                $this->Flash->warning(__('The email address of your Twitch account has not been verified. We can\'t create your account yet'));
                return $this->redirect($this->referer());
            }

            $user = $this->Users->newEntity([
                'id'             => $this->Users->getNewRandomID(),
                'name'           => $response->json['display_name'],
                'mail'           => $response->json['email'],
                'password'       => $this->Users->getRandomString(),
                // Fetches the language formatted in the query by the JS
                'preferredStore' => strtoupper(substr($this->request->getQuery('state'), 0, 2)),
                'timeZone'       => 'Europe/London',
                'twitchToken'    => $token,
                'twitchUserId'   => $response->json['_id'],
                'verified'       => 0,
                'mainSetup_id'   => 0
            ]);

            // As this Amazon Store does not exist, we just replace it by the `US` one
            if($user->preferredStore === 'EN')
            {
                $user->preferredStore = 'US';
            }

            // We'll use the Twitch API to retrieve its profile picture :O
            if(!$this->Users->saveRemoteProfilePicture($user->id, $response->json['logo'], $this->Flash))
            {
                $this->Users->saveDefaultProfilePicture($user, $this->Flash);
            }

            $email = $this->Users->getEmailObject($user->mail, 'Your account has been created !');
            $email->setTemplate('welcome')
                  ->viewVars(['name' => $user->name])
                  ->send();

            $this->Flash->success(__('Your account is now activated, and you have been logged in ;)'));

            // Let's add some notifications to this new user
            $this->Users->Notifications->createNotification($user->id, __('We advise you to edit your profile (use the panel at the top)...'));
            $this->Users->Notifications->createNotification($user->id, __('... in order to add a profile picture ! You\'d look better :P'));
        }

        // Just before logging this user in, let's save the current date time into the DB
        $user->lastLogginDate = Time::now();

        // Let's try to save this user !
        if(!$this->Users->save($user))
        {
            $this->Flash->error(__('An error occurred while saving your account. Please contact an administrator.'));
            return $this->redirect('/');
        }

        // Let's log this user in !
        $this->Users->prepareSessionForUser($this->request->session(), $user);
        $this->Users->synchronizeSessionWithUserEntity($this->request->session(), $user, parent::isAdmin($user));
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
