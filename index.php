<?php
session_start();

// Include necessary files(ads)

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

// $user_id = $_SESSION['user']['id'];

// Fetch user's faculty information from the database
$stmt = $pdo->prepare("SELECT faculty_name FROM users WHERE username = ?");
$stmt->execute([$user['username']]);
$user_faculty = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch submitted articles from the database
$stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 0 AND is_published = 0 AND faculty_name = ?");
$stmt->execute([$user_faculty['faculty_name']]);
$newArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Magazine</title>

        <!-- Bootstrap link -->
    <link rel="stylesheet" href="./css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/T_index.css">
    <link rel="stylesheet" href="./css/bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="./css/bootstrap/js/navbar-toggler.js">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    

</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
                <?php if (has_role('admin')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php" class="dropdown-item">Admin Dashboard</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('admin')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="administrator/manage_user.php" class="dropdown-item">Manage User</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('admin')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="administrator/manage_closure_dates.php" class="dropdown-item">Manage Closure Dates</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('admin')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="administrator/manage_faculty.php" class="dropdown-item">Manage Faculty</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('admin')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="administrator/report.php" class="dropdown-item">Manage Report</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('coordinator')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="marketing_coordinator/coordinator_dashboard.php" class="dropdown-item">Coordinator Dashboard</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('coordinator')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="administrator/report.php" class="dropdown-item">Report</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('manager')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="marketing_manager/manager.php" class="dropdown-item">Manager Dashboard</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('manager')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="news_feed.php" class="dropdown-item">News Feed</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('manager')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="administrator/report.php" class="dropdown-item">Report</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('student')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php" class="dropdown-item">Student Dashboard</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('student')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="student/write_article.php" class="dropdown-item">Write your article</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('student')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="student/manage_article.php" class="dropdown-item">Manage your article</a>
                    </li>
                <?php endif; ?>
                <?php if (has_role('student')) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="news_feed.php" class="dropdown-item">News Feed</a>
                    </li>
                <?php endif; ?>
        </ul>
        <form class="d-flex">
            <button class="btn btn-primary" type="button"><a class="nav-link " href="logout.php">Logout</a></button>
        </form>
        </div>
    </div>
</nav>
<marquee class="greeting" behavior="" direction="">Welcome To University Magazine, <?php echo $user['username']; ?>!</marquee>
    
    </nav>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    
    <div class="header">

        
        <!-- Bell icon and notification count -->
    </div>
    <nav class="navbar col-md-6">
        <div class="navbar-notification col-md-6">
            <?php if (has_role('coordinator')) : ?>
                <div class="notification-container">
    
                    <!-- Notification Bell -->
                    <div class="notification-bell" id="notificationBell">
                        <i class="fas fa-bell"></i>
                        <span class="badge" id="notificationCount"><?php echo count($newArticles); ?></span>
                    </div>
                    <!-- End Notification Bell -->
                
                    <!-- Display new article submissions -->
                    <div class="notification-box" id="notificationBox">
                        <?php if (empty($newArticles)): ?>
                            <p>No new article submissions</p>
                        <?php else: ?>
                            <ul>
                                <?php foreach ($newArticles as $article): ?>
                                    <li>
                                        <a href="student/view_article.php?id=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a>
                                        <!-- Display other details of the article as needed -->
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <!-- End Display new article submissions -->
                </div>
            <?php endif; ?>
        </div>
    </nav>



    <script>
        // JavaScript to toggle the visibility of new article submissions when clicking on the bell icon
        document.addEventListener('DOMContentLoaded', function() {
            const bell = document.getElementById('notificationBell');
            const box = document.getElementById('notificationBox');

            bell.addEventListener('click', function() {
                box.classList.toggle('open');
            });
        });        
    </script>
</body>
</html>
