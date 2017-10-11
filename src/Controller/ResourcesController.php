<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Resources Controller
 *
 * @property \App\Model\Table\ResourcesTable $Resources
 */
class ResourcesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = ['contain' => ['Users', 'Setups']];

        $resources = $this->paginate($this->Resources);

        $this->set(compact('resources'));
        $this->set('_serialize', ['resources']);
    }

    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }
}
