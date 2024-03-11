<?php
// Database configuration
$db_host = 'localhost'; // Change this to your MySQL host
$db_username = 'root'; // Change this to your MySQL username
$db_password = ''; // Change this to your MySQL password
$db_name = 'university_magazine'; // Change this to your MySQL database name

// Create PDO database connection
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
