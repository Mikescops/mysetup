<?php

function cleanString($string) {
    // on supprime : majuscules ; / ? : @ & = + $ , . ! ~ * ( ) les espaces multiples et les underscore
	$string = strtolower($string);
	$string = preg_replace("/[^a-z0-9_'\s-]/", "", $string);
	$string = preg_replace("/[\s-]+/", " ", $string);
	$string = preg_replace("/[\s_]/", " ", $string);
	return $string;
}

session_name('CAKEPHP');
session_start();

if($_SESSION['Auth'])
{
	$client_id = '1361';
	$secret_key = '5dd52298d90c03dc30a67eb1664e81c707c8e64a';


	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.ledenicheur.fr/v1/auth/token",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "{\"grant_type\":\"client_credentials\",\"client_id\": \"" . $client_id . "\",\"client_secret\": \"" . $secret_key . "\",\"audience\": \"https://api.ledenicheur.fr/v1/\"}",
		CURLOPT_HTTPHEADER => array(
			"content-type: application/json"
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err)
	{
		echo "cURL Error #:" . $err;
		echo "Process terminated > Token was not generated.";
		die();
	} 
	else
	{
	  //echo $response;
		header('Content-Type: application/json');
	}

	$token = json_decode($response, true)['access_token'];

	if(isset($_GET['q']) && !empty($_GET['q'])) {

		$query = urlencode(cleanString($_GET['q']));

		$url = "https://api.ledenicheur.fr/v1/search?modes=products&query=". $query ."&suggestions=false&access_token=".$token;
		$raw = file_get_contents($url);
		file_put_contents($dir . '/' . $motRecherche . '.json', $raw);
        // $json = json_decode($raw, true);

		echo $raw;
	}
}
else
{
	header('location: ../');
}

?>