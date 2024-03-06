<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user'])) {
    // Destroy the session
    session_unset();
    session_destroy();
}

// Redirect to the login page after logout
header('Location: login.php');
exit;
?>
