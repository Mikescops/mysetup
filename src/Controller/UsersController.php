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

        $this->set('user', $user);
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

                if($this->Users->save($user))
                {
                    $this->Users->saveDefaultProfilePicture($user, $this->Flash);

                    $this->Flash->success(__('The user has been saved.'));

                    // Let's check if the person that has just created this user is connected (admin one ?), or not
                    if($this->request->session()->read('Auth.User.id') == null)
                    {
                        // This person is new among us, let's log him in ASAP
                        $this->Auth->setUser($user);
                        $this->Flash->success(__('We are so kind, you\'re now logged in ;-)'));
                        return $this->redirect('/'); 
                    }

                    else
                    {
                        // This is an admin, "Hey you !". Where will we set you ?
                        return $this->redirect(['action' => 'add']);
                    }
                }

                $this->Flash->error(__('The user could not be saved. Please, try again.'));
                return $this->redirect(['action' => 'add']);
            }

            else
            {
                $this->Flash->error(__('These passwords do not match. Please try again.'));
                return $this->redirect('/');
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

            if(!isset($data['password']) || $data['password'] === '')
            {
                $data['password'] = $user['password'];
            }

            else
            {
                if($data['password'] !== $data['password2'])
                {
                    $this->Flash->error(__('These passwords do not match. Please try again.'));
                    return $this->redirect('/');
                }
            }

            if(!isset($data['verified']) || $data['verified'] === '' || $this->request->session()->read('Auth.User.mail') !== 'admin@admin.admin')
            {
                $data['verified'] = false;
            }

            $user = $this->Users->patchEntity($user, $data);

            if($this->Users->save($user))
            {
                if(isset($data['picture'][0]) and $data['picture'][0] !== '')
                {
                    $this->Users->saveProfilePicture($data['picture'][0], $user, $this->Flash);
                }

                $this->Flash->success(__('The user has been updated.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The user could not be updated. Please, try again.'));
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
        
        if($user and $user['mail'] !== 'admin@admin.admin')
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

        return $this->redirect('/');
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
            $user = $this->Auth->identify();

            if($user)
            {
                $this->Auth->setUser($user);
                $this->Flash->success(__('You are successfully logged in !'));
                return $this->redirect($this->Auth->redirectUrl());
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
            return $this->redirect('/');
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
                $user->password = substr(md5(rand()), 0, 16);
                if($this->Users->save($user))
                {
                    // $email = new Email('default');
                    // $email->setFrom(['support@mysetup.co' => 'MySetup.co'])
                    //     ->setTo($data['mailReset'])
                    //     ->setSubject("You password has been reseted !")
                    //     ->send("Your password has been reseted and set to: " . $user->password . "<br />Please log in and change it as soon as possible !");

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

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['logout', 'add', 'resetPassword', 'view']);
    }

    public function isAuthorized($user)
    {
        if(isset($user) && in_array($this->request->action, ['edit', 'delete', 'view']) && (int)$this->request->params['pass'][0] === $user['id'])
        {
            return true;
        }

        return parent::isAuthorized($user);
    }
}
