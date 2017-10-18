<?php

require dirname(__DIR__) . '../../vendor/autoload.php';

use ApaiIO\ApaiIO;
use ApaiIO\Operations\Search;
use ApaiIO\Request\GuzzleRequest;
use ApaiIO\Configuration\GenericConfiguration;
use GuzzleHttp\Client;

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth'])
{
    $access_key = 'AKIAJHOTPBA2YCCGVZUA';
    $secret_key = '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';

    $lang = $_GET['lang'];

    switch($lang)
    {
        case 'FR':
            $associate_tag = 'mysetupco-21';
            break;

        case 'UK':
            $lang = "CO.UK";
            $associate_tag = 'mysetup01f-21';
            break;

        case 'ES':
            $associate_tag = 'mysetupco0a-21';
            $access_key = 'AKIAIBGLG5HKO65NOAVA';
            $secret_key = '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';
            break;

        case 'IT':
            $associate_tag = 'mysetup01s-21';
            $access_key = 'AKIAIBGLG5HKO65NOAVA';
            $secret_key = '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';
            break;

        case 'DE':
            $associate_tag = 'mysetup09-21';
            break;

        default:
            /* This case is for US and others */
            $lang = 'COM';
            $associate_tag = 'mysetupco0c-20';
            break;
    }

    $conf = (new GenericConfiguration())
                ->setCountry($lang)
                ->setAccessKey($access_key)
                ->setSecretKey($secret_key)
                ->setAssociateTag($associate_tag)
                ->setRequest((new GuzzleRequest((new Client()))));

    $search = (new Search())
                ->setCategory('All')
                ->setKeywords($_GET['q'])
                ->setResponsegroup(['Small', 'Images']);

    echo (new ApaiIO($conf))->runOperation($search);
}

else
{
    header('location: ../');
}
