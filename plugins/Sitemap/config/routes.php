<?php

use Cake\Routing\Router;

if(!Router::routeExists(['_name' => 'sitemap'])) {
	Router::plugin(
		'Sitemap',
		['path' => '/sitemap'],
		function ($routes) {
			$routes->connect('/*', [
				'controller' => 'Sitemaps',
				'action' => 'index'
			], ['_name' => 'sitemap']);
		}
	);
}
