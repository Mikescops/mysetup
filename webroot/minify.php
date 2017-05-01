<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use MatthiasMullie\Minify;

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



// we can even add another file, they'll then be
// joined in 1 output file
// $sourcePath2 = '/path/to/second/source/css/file.css';
// $minifier->add($sourcePath2);

// or we can just add plain CSS
// $css = 'body { color: #000000; }';
// $minifier->add($css);

// save minified file to disk
$minifiedPath = dirname(__DIR__) . '/webroot/css/app.min.css';

echo $minifier->minify();

$minifier->minify($minifiedPath);




// or just output the content

?>