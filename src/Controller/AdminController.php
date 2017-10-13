<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Admin Controller
 *
 * This controller DOES NOT reflect any data model.
 * It's only the place where we handle 'Controllers.index' monitoring pages...
 */
class AdminController extends AppController
{

    // Let's explicit the fact that we delegate this decision to the `AppController`
    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }


    public function setups()
    {
        $setups = $this->paginate(TableRegistry::get('Setups'), [
            'order' => [
                'creationDate' => 'DESC'
            ],
            'contain' => [
                'Users'
            ]
        ]);

        $this->set(compact('setups'));
    }

    public function users()
    {
        $users = $this->paginate(TableRegistry::get('Users'), [
            'order' => [
                'creationDate' => 'DESC'
            ]
        ]);

        $this->set(compact('users'));
    }

    public function comments()
    {
        $comments = $this->paginate(TableRegistry::get('Comments'), [
            'contain' => [
                'Users',
                'Setups'
            ]
        ]);

        $this->set(compact('comments'));
    }

    public function resources()
    {
        $resources = $this->paginate(TableRegistry::get('Resources'), [
            'contain' => [
                'Users',
                'Setups'
            ]
        ]);

        $this->set(compact('resources'));
    }
}
