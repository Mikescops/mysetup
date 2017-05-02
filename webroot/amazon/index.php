<?php

require dirname(__DIR__) . '../../vendor/autoload.php';

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth'])
{
	$lang = $_GET['lang'];
	if ($lang == 'FR') {
		$associate_tag = 'mysetupco-21';
	}
	elseif ($lang == 'ES'){
		$associate_tag = 'mysetup0b-21';
	}
	elseif ($lang == 'IT'){
		$associate_tag = 'mysetup02e-21';
	}
	elseif ($lang == 'DE'){
		$associate_tag = 'mysetup09-21';
	}
	else{ /* This case is for UK and others */
		$lang = "UK";
		$associate_tag = 'mysetup01f-21';
	}

	$conf = new GenericConfiguration();
	$conf
	    ->setCountry($lang)
	    ->setAccessKey('AKIAJHOTPBA2YCCGVZUA')
	    ->setSecretKey('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')
	    ->setAssociateTag($associate_tag)
	    ->setRequest((new \ApaiIO\Request\GuzzleRequest((new \GuzzleHttp\Client()))));

	$search = new Search();
	$search->setCategory('All');
	$search->setKeywords($_GET['q']);
	$search->setResponsegroup(array('Small', 'Images'));

	echo (new ApaiIO($conf))->runOperation($search);
}

else
{
	header('location: ../../');
}
