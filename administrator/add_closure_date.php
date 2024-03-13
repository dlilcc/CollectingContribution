<?php
session_start();

// Include database configuration and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if user is logged in and has admin role
if (!is_logged_in() || !has_role('admin')) {
    // Redirect to login page if not logged in or not an admin
    header('Location: /CollectingContribution/login.php');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $academicYear = $_POST['academicYear'];
    $closureDate = $_POST['closureDate'];

    // Insert closure date into database
    $stmt = $pdo->prepare("INSERT INTO closure_dates (academic_year, closure_date) VALUES (:academicYear, :closureDate)");
    $stmt->bindParam(':academicYear', $academicYear);
    $stmt->bindParam(':closureDate', $closureDate);
    $stmt->execute();

    // Redirect to manage closure dates page
    header('Location: manage_closure_dates.php');
    exit;
} else {
    // Redirect to manage closure dates page if form is not submitted
    header('Location: manage_closure_dates.php');
    exit;
}
?>
