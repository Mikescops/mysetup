<?php

$query = $_GET['q'];

//echo $query;

require dirname(__DIR__) . '../../vendor/autoload.php';
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

$client = new \GuzzleHttp\Client();
$request = new \ApaiIO\Request\GuzzleRequest($client);

$conf = new GenericConfiguration();
$conf
    ->setCountry('fr')
    ->setAccessKey('AKIAJHOTPBA2YCCGVZUA')
    ->setSecretKey('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')
    ->setAssociateTag('mysetup-21')
    ->setRequest($request);

$search = new Search();
$search->setCategory('PCHardware');
$search->setKeywords($query);
$search->setResponsegroup(array('Small', 'Images'));

$apaiIo = new ApaiIO($conf);

$response = $apaiIo->runOperation($search);

//var_dump($response);

echo $response;
