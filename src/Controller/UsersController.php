<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
use Cake\Routing\Router;
use Cake\Mailer\Email;
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
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Setups' => ['sort' => ['Setups.id' => 'DESC']], 'Comments', 'Resources']
        ]);

        $fimage = $this->Users->Resources->find('all', ['order' => ['Resources.setup_id' => 'DESC']])->where(['user_id' => $id, 'type' => 'SETUP_FEATURED_IMAGE'])->toArray();

        $this->set(compact('user', 'fimage'));
        $this->set('_serialize', ['user']);
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

                if($this->Users->save($user))
                {
                    $this->Users->saveDefaultProfilePicture($user, $this->Flash);

                    Email::setConfigTransport('Zoho', [
                        'host' => 'smtp.zoho.eu',
                        'port' => 587,
                        'username' => 'support@mysetup.co',
                        'password' => 'Lsc\'etb1',
                        'className' => 'Smtp',
                        'tls' => true
                    ]);

                    $email = new Email('default');
                    $email
                        ->setTransport('Zoho')
                        ->setFrom(['support@mysetup.co' => 'mySetup.co | Support'])
                        ->setTo($user->mail)
                        ->setSubject("mySetup.co | Verify your account !")
                        ->setEmailFormat('html')
                        ->send("
                            Hello " . $data['name'] . " !
                            <br />
                            <br />
                            Please, in order to activate your account, click the following link : <a href=\"https://mysetup.co/verify/" . $user->id . '/' . $user->mailVerification . "\" target=\"_blank\">Activate my account</a> !
                            <br />
                            <br />
                            <br />
                            <img src=\"https://mysetup.co/img/logo_footer.svg\" alt=\"mySetup.co's Support\" style=\"height: 80px\">
                        ");

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
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
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
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        if($this->request->is(['patch', 'post', 'put']))
        {
            $data = $this->request->getData();

            if(!isset($data['name']) || $data['name'] === '')
            {
                $data['name'] = $user['name'];
            }

            if(!isset($data['mail']) || $data['mail'] === '')
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

            $user = $this->Users->patchEntity($user, $data);

            if($this->Users->save($user))
            {
                if(isset($data['picture'][0]) and $data['picture'][0] !== '' and (int)$data['picture'][0]['error'] === 0)
                {
                    $this->Users->saveProfilePicture($data['picture'][0], $user, $this->Flash);
                }

                // The user may have changed its preferred store / language, let's update this into the server's session
                $this->request->session()->write('Config.language', strtolower($user['preferredStore']). '_' . $user['preferredStore']);

                $this->Flash->success(__('The user has been updated.'));
            }

            else
            {
                $this->Flash->error(__('The user could not be updated. Please, try again.'));
            }
            
            return $this->redirect($this->referer());
        }

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
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
        if($this->request->session()->read('Auth.User.id') != null)
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
                    $this->Auth->setUser($user);

                    // Let's save the current date / time in the DB...
                    $user = $this->Users->get($user['id']);
                    $user->lastLogginDate = Time::now();
                    $this->Users->save($user);

                    $this->request->session()->write('Config.language', strtolower($user['preferredStore']). '_' . $user['preferredStore']);

                    $this->Flash->success(__('You are successfully logged in !'));
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
        if($this->request->session()->read('Auth.User.id') != null)
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
                    Email::setConfigTransport('Zoho', [
                        'host' => 'smtp.zoho.eu',
                        'port' => 587,
                        'username' => 'support@mysetup.co',
                        'password' => 'Lsc\'etb1',
                        'className' => 'Smtp',
                        'tls' => true
                    ]);

                    $email = new Email('default');
                    $email
                        ->setTransport('Zoho')
                        ->setFrom(['support@mysetup.co' => 'mySetup.co | Support'])
                        ->setTo($data['mailReset'])
                        ->setSubject("mySetup.co | You password has been reseted !")
                        ->setEmailFormat('html')
                        ->send("
                            Hello " . $user->name . " !
                            <br />
                            <br />
                            Your password has been reseted and set to: <span style=\"font-weight: bold;\">" . $temp . "</span><br />
                            <br />
                            Please <a href=\"https://mysetup.co/login\" target=\"_blank\">log you in</a> and change it as soon as possible !
                            <br />
                            <br />
                            <br />
                            <img src=\"https://mysetup.co/img/logo_footer.svg\" alt=\"mySetup.co's Support\" style=\"height: 80px\">
                        ");

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

                    // This person is new among us, let's log him in ASAP
                    $this->Auth->setUser($user);
                    $this->Flash->success(__('Your account is now activated, you\'re now logged in ;)'));

                    // Let's add some notifications to this new user
                    $this->loadModel('Notifications');
                    $this->Notifications->createNotification($user->id, __('We advise you to edit your profile (use the panel at the top)...'));
                    $this->Notifications->createNotification($user->id, __('... in order to add a profile picture ! You\'d look better :P'));

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
        $client_id = 'zym0nr99v74zljmo6z96st25rj6rzz';

        $http = new Client();

        $response = $http->post('https://api.twitch.tv/kraken/oauth2/token?client_id=' . $client_id . '&client_secret=b8mrbqfd9vsyjciyec560j44lh1muk&grant_type=authorization_code&redirect_uri=' . 'http://localhost/mysetup/twitch/' . '&code=' . $this->request->params['?']['code'] . '&state=' . $this->request->params['?']['state']);

        if($response and isset($response->json['scope'][0]) and $response->json['scope'][0] === 'user_read')
        {
            $token = $response->json['access_token'];

            // We just got a token from Twitch, two cases : This user has already created an account here OR We've to create its account :P
            if(!$this->Users->exists(['twitchToken' => $token]))
            {
                $response = $http->get('https://api.twitch.tv/kraken/user', ['q' => 'widget'], [
                    'headers' => [
                        'Accept' => 'application/vnd.twitchtv.v5+json',
                        'Client-ID' => $client_id,
                        'Authorization' => 'OAuth ' . $token
                    ]
                ]);

                if($response and $response->json)
                {
                    $user = $this->Users->newEntity();

                    $user->id             = $this->Users->getNewRandomID();
                    $user->name           = $response->json['display_name'];
                    $user->mail          = $response->json['email'];
                    $user->password       = $this->Users->getRandomString();
                    $user->preferredStore = strtoupper((substr($this->request->params['?']['state'], 0, 2)));
                    $user->lastLogginDate = Time::now();
                    $user->twitchToken    = $token;

                    if($this->Users->save($user))
                    {
                        // This new user has been created and saved, let's keep a local copy of its profile picture

                        $fp = fopen ('uploads/files/pics/profile_picture_' . $user->id . '.png', 'w+');              // open file handle

                        $ch = curl_init($response->json['logo']);
                        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
                        curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      // some large value to allow curl to run for a long time
                        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                        // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
                        curl_exec($ch);

                        curl_close($ch);                              // closing curl handle
                        fclose($fp);                                  // closing file handle
                        // ________________________________________________________________________________________

                        $this->Flash->success(__('Your account is now activated, you\'re now logged in ;)'));

                        // Let's add some notifications to this new user
                        $this->loadModel('Notifications');
                        $this->Notifications->createNotification($user->id, __('We advise you to edit your profile (use the panel at the top)...'));
                        $this->Notifications->createNotification($user->id, __('... in order to add a profile picture ! You\'d look better :P'));
                    }

                    else
                    {
                        $this->Flash->error(__('Your account could not be saved. (Is this address already taken ?)'));
                        return $this->redirect('/');
                    }
                }
            }

            else
            {
                $user = $this->Users->get(['twitchToken' => $token]);

                $this->Flash->success(__('You are successfully logged in !'));
            }

            // Let's log this user ASAP !
            $this->Auth->setUser($user);
            return $this->redirect($this->Auth->redirectUrl()); 
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['logout', 'add', 'resetPassword', 'view', 'verifyAccount', 'twitch']);
    }

    public function isAuthorized($user)
    {
        if(isset($user) && in_array($this->request->action, ['edit', 'delete']) && (int)$this->request->params['pass'][0] === $user['id'])
        {
            return true;
        }

        return parent::isAuthorized($user);
    }
}
