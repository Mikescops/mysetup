<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Cache\Cache;
use Cake\Network\Response;
use Cake\Http\Exception\NotFoundException;


class SitemapsController extends AppController
{
    /* /!\ Each method present in this very file will be authorized /!\ */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow();
    }

    /* Hooks the View rendering postlude to cache the generated content */
    public function afterFilter(Event $event)
    {
        // This is THE trick of the caching method :
        // When we return a `Response` object directly containing our XML, no `View` are rendered.
        // In such a case, the below condition is not met.
        if($this->View)
        {
            Cache::write(
                $this->viewBuilder()->getTemplate(),
                $this->response->body(),
                'SitemapsCacheConfig');
        }
    }

    public function dispatch($target)
    {
        $viewBuilder = $this->viewBuilder();

        if($target)
        {
            $target = ltrim($target, '-');
            $viewBuilder->setLayout('sitemap');
        }
        else
        {
            // Matches `sitemap.xml`.
            $target = 'index';
            $viewBuilder->setLayout('sitemapindex');
        }

        // Returns any Sitemap already cached.
        $content = Cache::read($target, 'SitemapsCacheConfig');
        if($content !== false)
        {
            $response = new Response([
                'type' => 'xml',
                'body' => $content
            ]);

            return $response;
        }

        // Retrieving from cache failed, let's render a template and save the generated content (see `@afterFilter`).
        $records = null;
        switch($target)
        {
            // "Static" ones.
            case 'index':
            case 'static':
                break;

            // "Dynamic" ones.
            case 'setups':
                $this->loadModel('Setups');
                $records = $this->Setups->find()->select(['id', 'title', 'modifiedDate'])->where(['status' => 'PUBLISHED'])->all();
                break;

            case 'blog':
                $this->loadModel('Articles');
                $records = $this->Articles->find()->select(['id', 'title', 'dateTime'])->all();
                break;

            case 'users':
                $this->loadModel('Users');
                $records = $this->Users->find()->select(['id', 'name', 'mainSetup_id'])->where(['mainSetup_id !=' => 0])->all();
                break;

            // This sitemap does not exit...
            default:
                throw new NotFoundException();
        }

        $this->set('records', $records);
        $viewBuilder->setTemplate($target);
        $this->RequestHandler->respondAs('xml');
    }
}
