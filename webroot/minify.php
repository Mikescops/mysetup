<header><title>msMinifier</title></header>
<?php

require dirname(__DIR__) . '/vendor/autoload.php';

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth']['User']['admin'])
{

    echo '<hr>RENDERED OUTPUT OF MINIFIER<hr>';

    $base = dirname(__DIR__) . '/webroot/js/';

    $minifierjs = (new \MatthiasMullie\Minify\JS())->add($base . 'app.js');

    dump($minifierjs->minify($base . 'app.min.js')); //Dump is used to prevent javascript activation in minifier

    echo '<hr>JS IS UGLYFIED AND SAVED<hr>';
}
else
{
    echo "You don't have the rights to access this service.";
}