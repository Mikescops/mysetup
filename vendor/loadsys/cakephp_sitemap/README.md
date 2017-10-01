# CakePHP-Sitemap

The Sitemap provides a mechanism for displaying Sitemap style information (the URL, change frequency, priority and last modified date-time) for a set of Tables that CakePHP has access to.

## Requirements

* CakePHP 3.0.0+
* PHP 5.6+

## Installation

1. Add the official package as a required one :
	```bash
	$ composer require loadsys/cakephp_sitemap
	```

2. Tweak the `package.lock` to point to <https://labs.pixelswap.fr/HorlogeSkynet/CakePHP_Sitemap> instead of the official package repository, and run :
	```bash
	$ composer install
	```

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

## Contributing

### Code of Conduct

This project has adopted the Contributor Covenant as its [code of conduct](CODE_OF_CONDUCT.md). All contributors are expected to adhere to this code. [Translations are available](http://contributor-covenant.org/).

### Reporting Issues

Please use [Gogs Issues](https://labs.pixelswap.fr/HorlogeSkynet/CakePHP_Sitemap/issues) for listing any known defects or issues.

### Development

When developing this plugin, please fork and issue a PR for any new development.

Set up a working copy :
```shell
$ git clone git@github.com:YOUR_USERNAME/CakePHP-Sitemap.git
$ cd CakePHP-Sitemap/
$ composer install
$ vendor/bin/phpcs --config-set installed_paths vendor/loadsys/loadsys_codesniffer,vendor/cakephp/cakephp-codesniffer
```

Make your changes :
```shell
$ git checkout -b your-topic-branch
# (Make your changes. Write some tests.)
$ vendor/bin/phpunit
$ vendor/bin/phpcs -p --extensions=php --standard=Loadsys ./src ./tests
```

Then commit and push your changes to your fork, and open a pull request.

## License

[MIT](https://labs.pixelswap.fr/HorlogeSkynet/CakePHP_Sitemap/src/master/LICENSE.md)

## Copyright

[Loadsys Web Strategies](http://www.loadsys.com) 2016
[MikeScops](https://github.com/MikeScops) 2017
[HorlogeSkynet](https://github.com/HorlogeSkynet) 2017
