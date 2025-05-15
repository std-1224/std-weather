<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$executionStartTime = microtime(true);

// Ensure the 'cityname' parameter is correctly used in the URL
$cityName = !empty($_REQUEST['cityname']) ? urlencode($_REQUEST['cityname']) : 'London';
$apiKey = '2607c0663cb54462b24170101242306';

// Correct the URL structure
$url = 'https://api.weatherapi.com/v1/current.json?key=' . $apiKey . '&q=' . $cityName;

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);

$result = curl_exec($ch);

curl_close($ch);

// Ensure the result is correctly decoded
$decode = json_decode($result, true);

$output = [];
$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
$output['data'] = $decode;

header('Content-Type: application/json; charset=UTF-8');

echo json_encode($output);

?>
