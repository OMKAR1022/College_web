<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seminar_hall_booking";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $programName = $_POST["programName"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $capacity = $_POST["capacity"];

    // Sanitize and validate user input (you should do more thorough validation)
    $programName = htmlspecialchars($programName);
    $startDate = date("Y-m-d", strtotime($startDate));
    $endDate = date("Y-m-d", strtotime($endDate));
    $capacity = intval($capacity);

    // Query the database to find available seminar halls
   $sql = "SELECT hall_name
        FROM seminar_halls
        WHERE capacity >= $capacity
        AND hall_name NOT IN (
            SELECT DISTINCT s.hall_name
            FROM seminar_halls s
            INNER JOIN bookings b ON s.id = b.hall_id
            WHERE ('$startDate' = b.start_date)
            OR ('$startDate' BETWEEN b.start_date AND b.end_date)
            OR ('$endDate' BETWEEN b.start_date AND b.end_date)
            OR (b.start_date BETWEEN '$startDate' AND '$endDate')
            OR ('$specificDate' BETWEEN b.start_date AND b.end_date)
        )
        AND ('$specificDate' NOT BETWEEN start_date AND end_date) 
        || start_date != '$startDate'";



    // Execute the query and fetch results
    $result = $conn->query($sql);

    if (!$result) {
        die("Database query failed: " . $conn->error);
    }

    // Check if any available halls were found
    if ($result->num_rows > 0) {
        echo "<h2>Available Seminar Halls:</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row["hall_name"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No available seminar halls found for the selected criteria.</p>";
    }
}

// Close the database connection
$conn->close();
?>
