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
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered via `Application::routes()` with `registerMiddleware()`
     */
    $routes->applyMiddleware('csrf');

    /* Static pages' routes... */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'home']);
    $routes->connect('/recent', ['controller' => 'Pages', 'action' => 'recent']);
    $routes->connect('/staffpicks', ['controller' => 'Pages', 'action' => 'staffpicks']);
    $routes->connect('/weekly/:year-:week', ['controller' => 'Pages', 'action' => 'weeklyPicks'])
            ->setPatterns(['year' => '\d{4}', 'week' => '\d{1,2}'])
            ->setPass(['year', 'week']);
    // if incorrect weekly path, use current week
    $routes->redirect('/weekly/*', '/weekly/'. (new \DateTime("-1 week"))->format("Y-W"));
    $routes->connect('/bugReport', ['controller' => 'Pages', 'action' => 'bugReport']);
    // ... and all the other ones
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);
    /* _______________________ */

    /* Search special page */
    $routes
        ->connect('/search/:entity', ['controller' => 'Pages', 'action' => 'search'])
        ->setPatterns(['entity' => '|(setups)|(users)|(resources)'])
        ->setPass(['entity']);
    /* ___________________ */

    /* Setups Controller's routes */
    $routes->scope('/setups', function($routes) {
        $routes
            ->connect('/:id:slug', ['controller' => 'Setups', 'action' => 'view'])
            ->setPatterns(['id' => '\d+', 'slug' => '(-.*)?'])
            ->setPass(['id']);
        $routes
            ->connect('/request/:id/:token/:response', ['controller' => 'Requests', 'action' => 'answerOwnership'])
            ->setPatterns(['id' => '\d+'])
            ->setPass(['id', 'token', 'response']);
    });
    /* __________________________ */

    /* Users Controller's routes */
    $routes->connect('/test',  ['controller' => 'Users', 'action' => 'test']);
    $routes->connect('/login',  ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
    $routes
        ->connect('/users/:id:slug', ['controller' => 'Users', 'action' => 'view'])
        ->setPatterns(['id' => '\d+', 'slug' => '(-.*)?'])
        ->setPass(['id']);

    $routes
        ->connect('/verify/:id/:token', ['controller' => 'Users', 'action' => 'verifyAccount'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id', 'token']);
    $routes->connect('/twitch/*', ['controller' => 'Users', 'action' => 'twitch']);
    /* _________________________ */

    /* Likes Controller's routes */
    $routes
        ->connect('/likes/:id:slug', ['controller' => 'Likes', 'action' => 'index'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    /* _________________________ */

    /* Our API routes, we connect the Throttle middleware */
    $routes->scope('/api', function($routes) {
        $routes->applyMiddleware('throttle');
        $routes->connect('/:action/*', ['controller' => 'Api', 'action' => 'action']);
    });
    /* ______________________________________ */

    /* Articles Controller's routes */
    $routes->scope('/blog', function($routes) {
        $routes->connect('/', ['controller' => 'Articles']);
        $routes
            ->connect('/:id:slug', ['controller' => 'Articles', 'action' => 'view'])
            ->setPatterns(['id' => '\d+', 'slug' => '(-.*)?'])
            ->setPass(['id']);
    });
    /* ____________________________ */

    /* Admin's (default) route */
    $routes->connect('/admin', ['controller' => 'Admin', 'action' => 'dashboard']);
    /* _______________________ */

    /* Sitemap routes */
    $routes->scope('/sitemap', function($routes) {
        $routes
            ->connect(':target.xml', ['controller' => 'Sitemaps', 'action' => 'dispatch'])
            ->setPatterns(['target' => '(-.*)?'])
            ->setPass(['target']);
    });
    /* _______________________ */

    // However, default routes are still available...
    $routes->fallbacks(DashedRoute::class);
});

/**
 * If you need different set of middleware or none at all, open new scope and define routes there
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
