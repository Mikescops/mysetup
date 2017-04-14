<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;

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

        // List of products that we have to send to the View
        $products = $this->Setups->Resources->find()->where(['setup_id' => $id, 'type' => 'SETUP_PRODUCT'])->all();

        // Sets an array with the name of the owner as a first entry
        $userNames['owner'] = $this->Setups->Users->find()->where(['id' => $setup->user_id])->first()['name'];
        foreach($setup['comments'] as $comment)
        {
            // Let's complete that array with the name of each person who postes a comment on this setup
            $userNames[$comment->user_id] = $this->Setups->Users->find()->where(['id' => $comment->user_id])->first()['name'];
        }

        $this->set(compact('setup', 'userNames', 'products'));
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

            // Let's set the id of the current logged in user 
            $data['user_id'] = $this->request->session()->read('Auth.User.id');

            // Classical patch entity operation
            $setup = $this->Setups->patchEntity($setup, $data);

            // Sets the current date to the entity before its saving
            $setup['creationDate'] = Time::now()->i18nFormat('yyyy-MM-dd');

            // An array in order to stock the resources temporary
            $resources = [];

            // "Title_1;href_1;src_1,Title_2;href_2;src_2,...,Title_n;href_n;src_n"
            foreach(explode(',', $data['resources']) as $elements)
            {
                $elements = explode(';', $elements);
                if(count($elements) == 3)
                {
                    // Let's create a new entity to store these data !
                    $resource = $this->Setups->Resources->newEntity();

                    // Let's parse the URls provided, in order to check their authenticity
                    $parsing_2 = parse_url(urldecode($elements[1]));
                    $parsing_3 = parse_url(urldecode($elements[2]));

                    // Let's check if the resources selected by the user are from Amazon
                    if(isset($parsing_2['host']) && strstr($parsing_2['host'], "amazon") && isset($parsing_3['host']) && strstr($parsing_3['host'], "amazon"))
                    {
                        $resource->user_id = null;
                        $resource->type    = 'SETUP_PRODUCT';
                        $resource->title   = $elements[0];
                        $resource->href    = $elements[1];
                        $resource->src     = $elements[2];

                        array_push($resources, $resource);
                    }
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
        parent::beforeFilter($event);

        $this->Auth->allow(['index', 'view']);
    }

    public function isAuthorized($user)
    {
        if(isset($user))
        {
            if(in_array($this->request->action, ['edit', 'delete']))
            {
                if($this->Setups->isOwnedBy((int)$this->request->params['pass'][0], $user['id']))
                {
                    return true;
                }
            }

            else if($this->request->action === 'add')
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }
}
