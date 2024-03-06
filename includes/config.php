<?php
// Database configuration
$db_host = 'localhost'; // Change this to your MySQL host
$db_username = 'root'; // Change this to your MySQL username
$db_password = ''; // Change this to your MySQL password
$db_name = 'university_magazine'; // Change this to your MySQL database name

// Create database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
