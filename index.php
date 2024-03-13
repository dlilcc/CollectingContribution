<?php
session_start();

// Include necessary files
require_once 'functions.php';

// Check if the user is logged in
if (!is_logged_in()) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit;
}

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Destroy session and redirect to login page if session has expired
    session_unset();
    session_destroy();
    header('Location: login.php?expired=true');
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Display welcome message
$user = get_user($_SESSION['user']['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Magazine</title>
    <style>
        .logout {
            float: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div>
        <h1>Welcome, <?php echo $user['username']; ?>!</h1>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div>
        <h2>Dashboard</h2>
        <ul>
            <?php if (has_role('admin')) : ?>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
            <?php endif; ?>
            <?php if (has_role('admin')) : ?>
                <li><a href="administrator/manage_user.php">Manage User</a></li>
            <?php endif; ?>
            <?php if (has_role('admin')) : ?>
                <li><a href="administrator/manage_closure_dates.php">Manage Closure Dates</a></li>
            <?php endif; ?>
            <?php if (has_role('admin')) : ?>
                <li><a href="administrator/manage_faculty.php">Manage Faculty</a></li>
            <?php endif; ?>
            <?php if (has_role('coordinator')) : ?>
                <li><a href="coordinator_dashboard.php">Coordinator Dashboard</a></li>
            <?php endif; ?>
            <?php if (has_role('student')) : ?>
                <li><a href="student_dashboard.php">Student Dashboard</a></li>
            <?php endif; ?>
            <?php if (has_role('student')) : ?>
                <li><a href="student/write_article.php">Write your article</a></li>
            <?php endif; ?>
            <?php if (has_role('student')) : ?>
                <li><a href="student/manage_article.php">Manage your article</a></li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
