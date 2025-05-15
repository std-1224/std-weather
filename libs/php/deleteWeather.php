<?php
header('Content-Type: application/json; charset=UTF-8');

// Database connection details
$host = 'localhost'; // Update with your database host
$db = 'weatherapp';  // Update with your database name
$user = 'root';      // Update with your database username
$pass = 'admin';     // Update with your database password

// Retrieve DELETE data
parse_str(file_get_contents("php://input"), $deleteData);
$id = $deleteData['id'] ?? null;

// Validate ID
if (!$id) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing or invalid ID']);
    exit;
}

// Create MySQLi connection
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    $response['status']['code'] = "500";
    $response['status']['name'] = "error";
    $response['status']['description'] = "Database connection error";
    echo json_encode($response);
    exit;
}

// Prepare SQL statement for deleting weather entry by id
$query = "DELETE FROM weather WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

// Check if deletion was successful
if ($stmt->affected_rows > 0) {
    $response['status']['code'] = "200";
    $response['status']['name'] = "ok";
    $response['status']['description'] = "Weather entry deleted successfully";
} else {
    $response['status']['code'] = "404";
    $response['status']['name'] = "not_found";
    $response['status']['description'] = "Weather entry with ID $id not found";
}

// Close statement and connection
$stmt->close();
$mysqli->close();

// Send JSON response
echo json_encode($response);
?>
