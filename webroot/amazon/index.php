<?php

require dirname(__DIR__) . '../../vendor/autoload.php';

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth'])
{
	$accesskey = 'AKIAJHOTPBA2YCCGVZUA';
	$secretkey = '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';
	$lang = $_GET['lang'];
	if ($lang == 'FR') {
		$associate_tag = 'mysetupco-21';
	}
	elseif ($lang == 'UK') {
		$lang = "CO.UK";
		$associate_tag = 'mysetup01f-21';
	}
	elseif ($lang == 'ES'){
		$associate_tag = 'mysetupco0a-21';
		$accesskey = 'AKIAIBGLG5HKO65NOAVA';
		$secretkey = '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';
	}
	elseif ($lang == 'IT'){
		$associate_tag = 'mysetup01s-21';
		$accesskey = 'AKIAIBGLG5HKO65NOAVA';
		$secretkey = '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';
	}
	elseif ($lang == 'DE'){
		$associate_tag = 'mysetup09-21';
	}
	else{ /* This case is for US and others */
		$lang = "COM";
		$associate_tag = 'mysetupco0c-20';
	}

	$conf = new GenericConfiguration();
	$conf
	    ->setCountry($lang)
	    ->setAccessKey($accesskey)
	    ->setSecretKey($secretkey)
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
	header('location: ../');
}
