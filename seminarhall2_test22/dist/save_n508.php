<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seminar";

// Create a new mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get JSON data from the request body
$data = json_decode(file_get_contents("php://input"));

$eventName = $data->event_name;
$startDate = $data->event_start_date;
$endDate = $data->event_end_date;

// Set a specific unique key of 1 for all events
$uniqueKey = "2";

$sql = "INSERT INTO rk_auditoriumm (seminar_hall_id, event_name, event_start_date, event_end_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare statement error: " . $conn->error);
}

$stmt->bind_param("ssss", $uniqueKey, $eventName, $startDate, $endDate);

if ($stmt->execute()) {
    $response = array("message" => "Event data saved successfully.");
} else {
    $response = array("error" => "Execution error: " . $stmt->error);
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
