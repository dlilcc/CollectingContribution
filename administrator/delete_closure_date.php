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

// Check if closure date ID is provided
if (!isset($_GET['id'])) {
    // Redirect to manage closure dates page if closure date ID is not provided
    header('Location: manage_closure_dates.php');
    exit;
}

if (isset($_GET['id'])){
    // Sanitize the closure date ID
    $closureDateId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if form is submitted and confirmation flag is set
    if (isset($_GET['confirmed']) && $_GET['confirmed'] === 'true') {
        // Delete closure date from the database
        $stmt = $pdo->prepare("DELETE FROM closure_dates WHERE id = :closureDateId");
        $stmt->bindParam(':closureDateId', $closureDateId);
        $stmt->execute();

        // Check if Closure date was deleted successfully
        if($stmt->rowCount() > 0) {
            // Article deleted successfully, redirect to manage_article.php or any other page
            header("Location: manage_closure_dates.php");
            exit();
        } else {
            // Closure date not found or deletion failed, handle the error (e.g., display an error message)
            echo "<script>alert('Error: Closure date not found or deletion failed.');</script>";
        }
    }
}
?>

