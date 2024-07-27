<?php
// Replace these with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seminar";

require 'vendor/autoload.php'; // Include PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;

// Check if a file was uploaded
if (isset($_FILES['file'])) {
    // Temporary file location
    $tempFile = $_FILES['file']['tmp_name'];

    // Load the uploaded Excel file
    $spreadsheet = IOFactory::load($tempFile);

    // Get the first worksheet (assuming data is in the first sheet)
    $worksheet = $spreadsheet->getActiveSheet();

    // Create a new mysqli connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Loop through the rows in the Excel sheet
    foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $name = $cellIterator->current()->getValue();
        $cellIterator->next();
        $email = $cellIterator->current()->getValue();
        $cellIterator->next();
        $points = $cellIterator->current()->getValue();

        // Check if the student with the same name and email already exists
        $checkQuery = "SELECT * FROM reward WHERE name = ? AND email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update the existing student's points
            $updateQuery = "UPDATE reward SET points = points + ? WHERE name = ? AND email = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("iss", $points, $name, $email);
            $stmt->execute();
        } else {
            // Insert a new student record
            $insertQuery = "INSERT INTO reward (name, email, points) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssi", $name, $email, $points);
            $stmt->execute();
        }
    }

    // Close the database connection
    $conn->close();

    echo "Data updated successfully.";
} else {
    echo "Please upload a file.";
}
?>

