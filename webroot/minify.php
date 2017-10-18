<?php

require dirname(__DIR__) . '/vendor/autoload.php';

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth']['User']['admin'])
{
    $base = dirname(__DIR__) . '/webroot/css/';

    $minifier = (new \MatthiasMullie\Minify\CSS())
                    ->add($base . 'normalize.css')
                    ->add($base . 'milligram.min.css')
                    ->add($base . 'font-awesome.min.css')
                    ->add($base . 'slick.css')
                    ->add($base . 'lity.min.css')
                    ->add($base . 'jssocials.css')
                    ->add($base . 'jssocials-theme-flat.css')
                    ->add($base . 'style.css');

    echo $minifier->minify($base . 'app.min.css');
}

else
{
    echo "You don't have the rights to access this service.";
}
