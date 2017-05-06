<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;
use Cake\Event\Event;

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
            'contain' => ['Setups', 'Comments', 'Resources']
        ]);

        $fimage = $this->Users->Resources->find('all')->where(['user_id' => $id, 'type' => 'SETUP_FEATURED_IMAGE'])->toArray();

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

            if($data['password'] === $data['password2'])
            {
                $user = $this->Users->patchEntity($user, $data);

                if(!isset($user['preferredStore']) or $user['preferredStore'] === '')
                {
                    $user['preferredStore'] = 'US';
                }

                // Here we'll assign a random id to this new user
                do {
                    $user->id = mt_rand() + 1;
                } while($this->Users->find()->where(['id' => $user->id])->count() !== 0);

                $user->mailVerification = substr(md5(mt_rand()), 0, 32);

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
                            Hello !
                            <br />
                            <br />
                            Please, in order to activate your account, click the following link : <a href=\"https://mysetup.co/verify/" . $user->id . '/' . $user->mailVerification . "\" target=\"_blank\">Activate my account</a> !
                            <br />
                            <br />
                            <br />
                            <img src=\"https://mysetup.co/img/logo_footer.svg\" alt=\"mySetup.co's Support\" style=\"height: 80px\">
                        ");

                    $this->Flash->success(__('Your account has been created, check your email to verify your account'));

                    // Let's check if the person that has just created this user is connected (admin one ?), or not
                    if($this->request->session()->read('Auth.User.id') == null)
                    {
                        return $this->redirect('/');
                    }

                    else
                    {
                        // This is an admin, "Hey you !". Where will we set you ?
                        return $this->redirect(['action' => 'add']);
                    }
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
                $temp = substr(md5(mt_rand()), 0, 16);
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
                            Hello " . ($user->name !== '' ? $user->name . ' ' : '') . "!
                            <br />
                            <br />
                            Your password has been reseted and set to: <span style=\"font-weight: bold;\">" . $temp . "</span>
                            <br />
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

                    $this->Users->save($user);

                    // This person is new among us, let's log him in ASAP
                    $this->Auth->setUser($user);
                    $this->Flash->success(__('Your account is now activated, you\'re now logged in ;)'));
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

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['logout', 'add', 'resetPassword', 'view', 'verifyAccount']);
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
