<?php

function cleanString($string) {
    // on supprime : majuscules ; / ? : @ & = + $ , . ! ~ * ( ) les espaces multiples et les underscore
	$string = strtolower($string);
	$string = preg_replace("/[^a-z0-9_'\s-]/", "", $string);
	$string = preg_replace("/[\s-]+/", " ", $string);
	$string = preg_replace("/[\s_]/", " ", $string);
	return $string;
}

function curlUrl($url){
	$client_id = '1361';
	$secret_key = '5dd52298d90c03dc30a67eb1664e81c707c8e64a';

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
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
	  return json_decode($response, true)['access_token'];
	}
}

function getToken($file,$url,$hours = 24,$fn = '',$fn_args = '') {
	//vars
	$current_time = time(); $expire_time = $hours * 60 * 60; $file_time = filemtime($file);
	//decisions, decisions
	if(file_exists($file) && ($current_time - $expire_time < $file_time)) {
		//echo 'returning from cached file';
		return json_decode(file_get_contents($file), true)['token'];
	}
	else {
		$content = curlUrl($url);
		if($fn) { $content = $fn($content,$fn_args); }
		$content = '{"token":"'.$content.'", "cached":"'.time().'"}';
		file_put_contents($file,$content);
		//echo 'retrieved fresh from '.$url.':: '.$content;
		return json_decode($content, true)['token'];
	}
}

function outputProductsJSON($raw){
	$json = json_decode($raw, true);
	$output = '{"products": [';
	foreach ($json['resources']['products']['items'] as $product => $value) {
		$output .= '{"title":"'. $value['name'] . '", "href":"'. $value['web_uri']. '","src":"'. $value['media']['product_images']['first'][280] .'"},';
	}

	$output = substr($output, 0, -1) . ']}';
	return $output;
}


session_name('CAKEPHP');
session_start();

if($_SESSION['Auth'])
{
	$url_token = "https://api.ledenicheur.fr/v1/auth/token";
	$cache_file = "tokencached.txt";

	$token = getToken($cache_file, $url_token, 20, null, array('file'=>$cache_file));

	if(isset($_GET['q']) && !empty($_GET['q'])) {

		header('Content-Type: application/json');

		$query = urlencode(cleanString($_GET['q']));

		$url = "https://api.ledenicheur.fr/v1/search?modes=products&query=". $query ."&suggestions=false&access_token=". $token;
		$raw = file_get_contents($url);
		file_put_contents($dir . '/' . $motRecherche . '.json', $raw);

		// echo $raw;

		// die();

		echo outputProductsJSON($raw);
	}
}
else
{
	header('location: ../');
}

?>