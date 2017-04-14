<?php

require dirname(__DIR__) . '../../vendor/autoload.php';

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth'])
{
	$conf = new GenericConfiguration();
	$conf
	    ->setCountry('fr')
	    ->setAccessKey('AKIAJHOTPBA2YCCGVZUA')
	    ->setSecretKey('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')
	    ->setAssociateTag('mysetup-21')
	    ->setRequest((new \ApaiIO\Request\GuzzleRequest((new \GuzzleHttp\Client()))));

	$search = new Search();
	$search->setCategory('PCHardware');
	$search->setKeywords($_GET['q']);
	$search->setResponsegroup(array('Small', 'Images'));

	echo (new ApaiIO($conf))->runOperation($search);
}

else
{
	header('location: ../../');
}
