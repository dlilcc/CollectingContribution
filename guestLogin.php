<?php
session_start();

// Include necessary files
require_once 'functions.php';

// Check if the user is already logged in
if (is_logged_in()) {
    // Redirect to the index page if already logged in
    header('Location: index.php');
    exit;
}

// Set default guest credentials
$guest_username = 'guest';
$guest_password = '123456';

// Log in the guest user
$_SESSION['user'] = array(
    'username' => $guest_username,
    'role' => 'guest' // You can set the role of the guest user as needed
);

// Redirect to the index page after successful login
header('Location: index.php');
exit;
?>
