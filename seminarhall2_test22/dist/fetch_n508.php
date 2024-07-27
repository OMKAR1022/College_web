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

// Fetch event data from the database
// Fetch event data from the database, including event_id
$sql = "SELECT event_id, event_name, event_start_date, event_end_date, color FROM rk_auditoriumm WHERE seminar_hall_id = 2";
$result = $conn->query($sql);

$eventsData = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event = array(
            "id" => $row["event_id"], // Include event_id
            "title" => $row["event_name"],
            "start" => $row["event_start_date"],
            "end" => $row["event_end_date"],
            "color" => $row["color"], // You can customize the color here
        );
        array_push($eventsData, $event);
    }
}

// Close the database connection
$conn->close();

// Return event data as JSON
header('Content-Type: application/json');
echo json_encode($eventsData);
?>
