<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$executionStartTime = microtime(true);

header('Content-Type: application/json; charset=UTF-8');

$host = 'localhost'; // Update with your database host
$db = 'weatherapp';   // Update with your database name
$user = 'root';      // Update with your database username
$pass = 'admin';          // Update with your database password

$city = $_POST['city'];
$country = $_POST['country'];
$temperature = $_POST['temperature'];
$humidity = $_POST['humidity'];
$condition = $_POST['condition'];
$icon = $_POST['icon'];

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    $output['status']['code'] = "500";
    $output['status']['name'] = "failure";
    $output['status']['description'] = "database unavailable";
    $output['data'] = [];
    echo json_encode($output);
    exit;
}

$query = "INSERT INTO weather (city, country, temperature, humidity, condition_text, icon) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("ssddss", $city, $country, $temperature, $humidity, $condition, $icon);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $output['status']['code'] = "200";
    $output['status']['name'] = "ok";
    $output['status']['description'] = "success";
} else {
    $output['status']['code'] = "500";
    $output['status']['name'] = "failure";
    $output['status']['description'] = "insert failure";
}

$stmt->close();
$mysqli->close();

$output['status']['returnedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
echo json_encode($output);

?>
