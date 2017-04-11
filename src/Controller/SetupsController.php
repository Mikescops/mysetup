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
            'contain' => ['Users', 'Resources', 'Comments']
        ]);

        $userNames['owner'] = $this->Setups->Users->find()->where(['id' => $setup->user_id])->first()['name'];

        foreach($setup['comments'] as $comment)
        {
            $userNames[$comment->user_id] = $this->Setups->Users->find()->where(['id' => $comment->user_id])->first()['name'];
        }

        $this->set(compact('setup', 'userNames'));
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

            // Let's get the data from the form
            $data = $this->request->getData();

            // Classical patch entity operation
            $setup = $this->Setups->patchEntity($setup, $data);

            // An array in order to stock the resources temporary
            $resources = [];

            // Let's check if the resources selected by the user are from amazon.com
            foreach(explode(',', $data['resources']) as $value)
            {
                $parsing = parse_url($value);
                if(isset($parsing['host']) && strstr($parsing['host'], "amazon"))
                {
                    $resource = $this->Setups->Resources->newEntity();
                    $resource->user_id = null;
                    $resource->type = 'SETUP_PRODUCT';
                    $resource->href = $value;

                    array_push($resources, $resource);
                }
            }

            if($this->Setups->save($setup))
            {
                // The data has been saved, now we got its 'id'. Let's fix it onto each resource previously created
                foreach($resources as $resource)
                {
                    $resource->setup_id = $setup->id;

                    // If the resource does not validate its rule, we rollback and throw an error...
                    if(!$this->Setups->Resources->save($resource))
                    {
                        $this->Setups->delete($setup);
                        $this->Flash->error(__('Internal error, we couldn\'t save your setup.'));
                        return $this->redirect(['action' => 'add']);
                    }
                }

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
        // $this->Auth->allow(['index', 'view']);
    }
}
