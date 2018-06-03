# CakePHP-Sitemap

The Sitemap provides a mechanism for displaying Sitemap style information (the URL, change frequency, priority and last modified date-time) for a set of Tables that CakePHP has access to.

## Requirements

* CakePHP 3.6+
* PHP 5.6+

## Installation

1. Add the official package as a required one :
	```bash
	$ composer require loadsys/cakephp_sitemap
	```

2. You'll have to tweak _Composer_ files to fetch our sources :
	1. `package.json` :  
		```diff
		-        "loadsys/cakephp_sitemap": "^3.1",
		+        "loadsys/cakephp_sitemap": "4.*",
		```

	2. `package.lock` :  
		```diff
		        "name": "loadsys/cakephp_sitemap",
		-       "version": "OFFICIAL_VERSION",
		+       "version": "OUR_LATEST_VERSION",
		        "source": {
		             "type": "git",
		-            "url": "https://github.com/loadsys/CakePHP-Sitemap.git",
		-            "reference": "OFFICIAL_COMMIT_SHA"
		+            "url": "https://labs.pixelswap.fr/HorlogeSkynet/CakePHP_Sitemap.git",
		+            "reference": "OUR_LATEST_COMMIT_SHA"
		        },
		        "dist": {
		             "type": "zip",
		-            "url": "https://api.github.com/repos/loadsys/CakePHP-Sitemap/zipball/OFFICIAL_COMMIT_SHA",
		-            "reference": "OFFICIAL_COMMIT_SHA",
		+            "url": "https://labs.pixelswap.fr/HorlogeSkynet/CakePHP_Sitemap/archive/master.zip",
		+            "reference": "OUR_LATEST_COMMIT_SHA",
		```

	3. Run `$ composer install`

	4. You'll be able in the future to run `$ composer update` as well !

3. In your `config/bootstrap.php` file, add:
	```php
	Plugin::load('Sitemap', ['bootstrap' => false, 'routes' => true]);
	```

	OR

	```php
	bin/cake plugin load Sitemap -r
	```

## Usage

* Add list of tables to display Sitemap records via an array at `Sitemap.tables`

```php
Configure::write('Sitemap.tables', [
	'Pages',
	'Sites',
	'Camps',
]);
```

* Add the `Sitemap.Sitemap` Behavior to each table as well

```php
$this->addBehavior('Sitemap.Sitemap');
```

You can now access the sitemap at `/sitemap` !

### Configuration

* Default configuration options for the `Sitemap` Behavior is:

```php
'cacheConfigKey' => 'default',
'lastmod' => 'modified',
'changefreq' => 'daily',
'priority' => '0.9',
'conditions' => [],
'order' => [],
'fields' => [],
'implementedMethods' => [
	'getUrl' => 'returnUrlForEntity',
],
'implementedFinders' => [
	'forSitemap' => 'findSitemapRecords',
],
```

* To modify these options for instance to change the `changefreq` when listing records, updated the `addBehavior` method call for the `Table` in question like so:

```php
$this->addBehavior('Sitemap.Sitemap', ['changefreq' => 'weekly']);
```

* To customize the URL generated for each record create a method named `getUrl` in the matching `Table` class.

```php
public function getUrl(\App\Model\Entity $entity) {
	return \Cake\Routing\Router::url(
		[
			'prefix' => false,
			'plugin' => false,
			'controller' => $this->registryAlias(),
			'action' => 'display',
			$entity->display_id,
		],
		true
	);
}
```

## License

[MIT](https://labs.pixelswap.fr/HorlogeSkynet/CakePHP_Sitemap/src/master/LICENSE.md)

## Copyright

* [Loadsys Web Strategies](http://www.loadsys.com) 2016
* [MikeScops](https://github.com/MikeScops) 2017
* [HorlogeSkynet](https://github.com/HorlogeSkynet) 2017
