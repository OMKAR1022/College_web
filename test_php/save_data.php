<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "Mysql@123";
$dbname = "user_data";

// Create a new mysqli connection with the socket path
$conn = new mysqli($servername, $username, $password, $dbname, null, "/tmp/mysql.sock");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO users (name, birthdate) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare statement error: " . $conn->error);
}

$stmt->bind_param("ss", $name, $birthdate);

$data = json_decode(file_get_contents("php://input"));

$name = $data->name;
$birthdate = $data->birthdate;

if ($stmt->execute()) {
    $response = array("message" => "User data saved successfully.");
} else {
    $response = array("error" => "Execution error: " . $stmt->error);
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
