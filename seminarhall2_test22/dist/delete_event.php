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

// Check if the request is for deleting an event
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get JSON data from the request body
    $data = json_decode(file_get_contents("php://input"));

    $eventID = $data->event_id;

    // Delete the event from the database
    $sql = "DELETE FROM rk_auditoriumm WHERE event_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare statement error: " . $conn->error);
    }

    $stmt->bind_param("i", $eventID);

    if ($stmt->execute()) {
        $response = array("success" => true, "message" => "Event deleted successfully.");
    } else {
        $response = array("success" => false, "error" => "Execution error: " . $stmt->error);
    }

    $stmt->close();
} else {
    $response = array("success" => false, "error" => "Invalid request method.");
}

// Close the database connection
$conn->close();

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
