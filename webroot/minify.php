<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use MatthiasMullie\Minify;

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth'])
{
	$normalize = dirname(__DIR__) . '/webroot/css/normalize.css';
	$minifier = new Minify\CSS($normalize);

	$milligram = dirname(__DIR__) . '/webroot/css/milligram.min.css';
	$minifier->add($milligram);

	$fontawesome = dirname(__DIR__) . '/webroot/css/font-awesome.min.css';
	$minifier->add($fontawesome);

	$slick = dirname(__DIR__) . '/webroot/css/slick.css';
	$minifier->add($slick);

	$lity = dirname(__DIR__) . '/webroot/css/lity.min.css';
	$minifier->add($lity);

	$jssocials = dirname(__DIR__) . '/webroot/css/jssocials.css';
	$minifier->add($jssocials);

	$flat = dirname(__DIR__) . '/webroot/css/jssocials-theme-flat.css';
	$minifier->add($flat);

	$sourcePath = dirname(__DIR__) . '/webroot/css/style.css';
	$minifier->add($sourcePath);

	$minifiedPath = dirname(__DIR__) . '/webroot/css/app.min.css';

	echo $minifier->minify();

	$minifier->minify($minifiedPath);
}
