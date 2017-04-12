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
        $resources = $this->paginate($this->Resources);

        $this->set(compact('resources'));
        $this->set('_serialize', ['resources']);
    }
}
