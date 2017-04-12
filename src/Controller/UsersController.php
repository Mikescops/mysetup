<?php
namespace App\Controller;

use App\Controller\AppController;
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

                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }

            else
            {
                $this->Flash->error(__('These passwords do not match. Please try again.'));

                return $this->redirect(['action' => 'add']);
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
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
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
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        if($this->request->session()->read('Auth.User.id') != null)
        {
            $this->Flash->warning(__('You are already logged in.'));
            return $this->redirect(['action' => 'index']);
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
        /* TO DO ! */
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
