<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

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

    public function beforeRender(Event $event)
    {

        parent::beforeRender($event);

        $this->loadModel('Users');
        $this->loadModel('Setups');
        $this->loadModel('Resources');
        $this->loadModel('Comments');

        $total_users = $this->Users->find()->count();
        $total_setups = $this->Setups->find()->count();
        $total_comments = $this->Comments->find()->count();
        $total_resources = $this->Resources->find()->count();

        $this->set(compact('total_users', 'total_setups', 'total_comments', 'total_resources'));
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

        $this->set('setups', $setups);
    }

    public function users()
    {
        $users = $this->paginate(TableRegistry::get('Users'), [
            'order' => [
                'creationDate' => 'DESC'
            ]
        ]);

        $this->set('users', $users);
    }

    public function comments()
    {
        $comments = $this->paginate(TableRegistry::get('Comments'), [
            'contain' => [
                'Users',
                'Setups'
            ]
        ]);

        $this->set('comments', $comments);
    }

    public function resources()
    {
        $resources = $this->paginate(TableRegistry::get('Resources'), [
            'contain' => [
                'Users',
                'Setups'
            ]
        ]);

        $this->set('resources', $resources);
    }
}
