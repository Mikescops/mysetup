<?php
/**
 * Basic Sitemaps Controller to display a standard Sitemap in HTML and XML.
 */
namespace Sitemap\Controller;

use Cake\Core\Configure;
use Sitemap\Controller\AppController;
use Cake\Event\Event;


/**
 * \Sitemap\Controller\SitemapsController
 */
class SitemapsController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['index']);
    }

	/**
	 * Index page for the sitemap.
	 *
	 * @return void
	 */
	public function index()
	{
		// We'll only render XML format for this website...
        $format = "xml";
        // ________________________________________________

        // Format pour le view mapping
        $formats = [
          'xml' => 'Xml',
          'json' => 'Json',
        ];

        // Erreur sur un type inconnu
        if (!isset($formats[$format])) {
            throw new NotFoundException(__('Unknown format.'));
        }

        // Définit le format de la Vue
        $this->viewBuilder()->setClassName($formats[$format]);

		$tablesToList = [];
		$data = [];

		if (Configure::check('Sitemap.tables')) {
			$tablesToList = Configure::read('Sitemap.tables');
		}

		foreach ($tablesToList as $table) {
			$data[$table] = $this->loadModel($table)->find('forSitemap');
		}

		$this->set('data', $data);
	}
}
