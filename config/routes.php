<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);


    $routes->connect('/recents', ['controller' => 'Pages', 'action' => 'display', 'recent']);
    $routes->connect('/popular', ['controller' => 'Pages', 'action' => 'display', 'popular']);

    /** Handle routes for login and logout **/
    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $routes->connect('/setups/:id', ['controller' => 'Setups', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/setups/:id-:slug', ['controller' => 'Setups', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/blog/', ['controller' => 'Articles', 'action' => 'index']);

    $routes->connect('/blog/:id', ['controller' => 'Articles', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/blog/:id-:slug', ['controller' => 'Articles', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/users/:id', ['controller' => 'Users', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->connect('/verify/:id/:token', ['controller' => 'Users', 'action' => 'verifyAccount'], ['id' => '\d+', 'pass' => ['id', 'token']]);

    $routes->connect('/notifications', ['controller' => 'Notifications', 'action' => 'index']);

    $routes->connect('/twitch/*', ['controller' => 'Users', 'action' => 'twitch']);

    /* Admin panel routes */
    $routes->connect('/admin', ['controller' => 'Setups', 'action' => 'index']);
    $routes->connect('/admin/setups', ['controller' => 'Setups', 'action' => 'index']);
    $routes->connect('/admin/users', ['controller' => 'Users', 'action' => 'index']);
    $routes->connect('/admin/comments', ['controller' => 'Comments', 'action' => 'index']);
    $routes->connect('/admin/resources', ['controller' => 'Resources', 'action' => 'index']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
