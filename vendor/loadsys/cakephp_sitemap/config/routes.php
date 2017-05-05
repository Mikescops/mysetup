<?php
use Cake\Routing\Router;

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
