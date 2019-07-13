<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

class SitemapsController extends AppController
{
    /* /!\ Each method present in this very file will be authorized /!\ */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow();
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('sitemapindex');
        $this->RequestHandler->respondAs('xml');
    }

    public function static()
    {
        $this->viewBuilder()->setLayout('sitemap');
        $this->RequestHandler->respondAs('xml');
    }

    public function setups()
    {
        $this->viewBuilder()->setLayout('sitemap');
        $this->RequestHandler->respondAs('xml');

        $this->loadModel('Setups');
        $setups = $this->Setups->find()->select(['id', 'title', 'modifiedDate'])->where(['status' => 'PUBLISHED'])->all();

        $this->set('records', $setups);
    }

    public function articles()
    {
        $this->viewBuilder()->setLayout('sitemap');
        $this->RequestHandler->respondAs('xml');

        $this->loadModel('Articles');
        $articles = $this->Articles->find()->select(['id', 'title', 'dateTime'])->all();

        $this->set('records', $articles);
    }

    public function users()
    {
        $this->viewBuilder()->setLayout('sitemap');
        $this->RequestHandler->respondAs('xml');

        $this->loadModel('Users');
        $users = $this->Users->find()->select(['id', 'name', 'mainSetup_id'])->where(['mainSetup_id >' => 'O'])->all();

        $this->set('records', $users);
    }
}
