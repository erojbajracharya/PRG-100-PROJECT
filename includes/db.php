<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_ead_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // If DB doesn't exist, redirect to init.php
    if ($conn->connect_errno == 1049) { // Unknown database
        header("Location: /PRG 100 PROJECT/init.php");
        exit();
    }
    die("Connection failed: " . $conn->connect_error);
}
?>
