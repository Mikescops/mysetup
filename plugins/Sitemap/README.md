# CakePHP-Sitemap

The Sitemap provides a mechanism for displaying Sitemap style information (the URL, change frequency, priority and last modified date-time) for a set of Tables that CakePHP has access to.

## Requirements

* CakePHP 3.6+
* PHP 5.6+

## Installation

Please see [here](https://book.cakephp.org/3.0/en/plugins.html).

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
