<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$executionStartTime = microtime(true);

header('Content-Type: application/json; charset=UTF-8');

$host = 'localhost'; // Update with your database host
$db = 'weatherapp';   // Update with your database name
$user = 'root';      // Update with your database username
$pass = 'admin';          // Update with your database password

// Create MySQLi connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    $output['status']['code'] = "500";
    $output['status']['name'] = "error";
    $output['status']['description'] = "Database connection error";
    echo json_encode($output);
    exit;
}

// Query to select all data from the 'weather' table
$query = "SELECT * FROM weather ORDER BY created_at DESC";
$result = $mysqli->query($query);

$output = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output[] = $row;
    }
}

// Close connection
$mysqli->close();

// Prepare the response
$response['status']['code'] = "200";
$response['status']['name'] = "ok";
$response['status']['description'] = "success";
$response['status']['returnedIn'] = intval((microtime(true) - $executionStartTime) * 1000) . " ms";
$response['data'] = $output;

// Send JSON response
echo json_encode($response);

?>
