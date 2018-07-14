<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
 use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\I18n\Middleware\LocaleSelectorMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\Middleware\SecurityHeadersMiddleware;

use Setup\Middleware\MaintenanceMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
{

    public function bootstrap()
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            try {
                $this->addPlugin('Bake');
            } catch (MissingPluginException $e) {
                // Do not halt if the plugin is missing
            }

            $this->addPlugin('Migrations');
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin(\DebugKit\Plugin::class);
        }

        $this->addPlugin('Sitemap', ['bootstrap' => false, 'routes' => true]);
        Configure::write('Sitemap.tables', [
            'Setups', 'Users', 'Articles'
        ]);

        $this->addPlugin('Tanuck/Markdown');
        $this->addPlugin('Muffin/Throttle');
        $this->addPlugin('Setup', ['bootstrap' => true]);
    }

    /**
     * Setup the middleware your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware.
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(ErrorHandlerMiddleware::class)

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(AssetMiddleware::class)

            // Add routing middleware.
            ->add(new RoutingMiddleware($this))

            // Here we'll accept the user's locale (whatever it is)
            // Check AppController.php@initialize() and AppController.php@beforeRender()
            ->add(new LocaleSelectorMiddleware(['*']))

            // Since CakePHP 3.5, CSRF protection should be handled by a middleware
            ->add(new CsrfProtectionMiddleware([
                'secure'   => !Configure::read('debug'),
                'httpOnly' => true
            ]))

            // Set here some security headers
            ->add((new SecurityHeadersMiddleware())
                  ->setCrossDomainPolicy()
                  ->setReferrerPolicy()
                  ->setXssProtection())

            // Loads the Setup-plugin's "Maintenance mode" (see `bin/deployment.sh` script)
            ->add(MaintenanceMiddleware::class);

        return $middlewareQueue;
    }
}
