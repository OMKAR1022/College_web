<?php
session_start();
$host = "localhost"; // Replace with your MySQL host
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "seminar"; // Replace with your MySQL database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // In a real application, hash and salt the password
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION["success"] = "Registration successful. You can now log in.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION["error"] = "Registration failed: " . $conn->error;
    }
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate user credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION["username"] = $username;
        header("Location: home.php");
        exit();
    }else {
        $_SESSION["error"] = "Login failed. Invalid username or password.";
    }
}

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
