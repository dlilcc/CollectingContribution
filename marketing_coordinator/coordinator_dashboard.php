<?php
session_start();

require_once __DIR__ . '/../functions.php';

// Check if the user is logged in and has coordinator role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'coordinator') {
    // Redirect to login page if not logged in or not a coordinator
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator Dashboard</title>
</head>
<body>
    <h1>Marketing Coordinator Dashboard</h1>
    <ul>
        <li><a href="coordinator_manage_article.php">Manage Articles</a></li>
        <li><a href="statistical_analysis.php">Statistical Analysis</a></li>
        <li><a href="../news_feed.php">News Feed</a></li>
    </ul>
    <a href="../index.php" class="logout">Back</a>   
</body>
</html>
