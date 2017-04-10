<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Setups Controller
 *
 * @property \App\Model\Table\SetupsTable $Setups
 */
class SetupsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $setups = $this->paginate($this->Setups);

        $this->set(compact('setups'));
        $this->set('_serialize', ['setups']);
    }

    /**
     * View method
     *
     * @param string|null $id Setup id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setup = $this->Setups->get($id, [
            'contain' => ['Users', 'Resources']
        ]);

        $this->set('setup', $setup);
        $this->set('_serialize', ['setup']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $setup = $this->Setups->newEntity();
        if ($this->request->is('post')) {
            $setup = $this->Setups->patchEntity($setup, $this->request->getData());
            if ($this->Setups->save($setup)) {
                $this->Flash->success(__('The setup has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setup could not be saved. Please, try again.'));
        }
        $users = $this->Setups->Users->find('list', ['limit' => 200]);
        $this->set(compact('setup', 'users'));
        $this->set('_serialize', ['setup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Setup id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setup = $this->Setups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setup = $this->Setups->patchEntity($setup, $this->request->getData());
            if ($this->Setups->save($setup)) {
                $this->Flash->success(__('The setup has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The setup could not be saved. Please, try again.'));
        }
        $users = $this->Setups->Users->find('list', ['limit' => 200]);
        $this->set(compact('setup', 'users'));
        $this->set('_serialize', ['setup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Setup id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setup = $this->Setups->get($id);
        if ($this->Setups->delete($setup)) {
            $this->Flash->success(__('The setup has been deleted.'));
        } else {
            $this->Flash->error(__('The setup could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['index', 'view']);
    }
}
