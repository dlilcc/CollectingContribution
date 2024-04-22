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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />
</head>
<body>
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Marketing Coordinator Dashboard</h1>
    </div>
    <div class="text-right">
        <a href="../index.php" class="btn btn-outline-primary">Back</a>   
    </div>
    <div class="container">
        
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 border rounded shadow">
                <form method="post">
                    <div class="login-link mb-3 mt-3 text-center">
                        <a type="button" class="btn btn-dark" href="coordinator_manage_article.php">Manage Articles</a>
                    </div>
                </form>
            </div>
            <div class="col-md-4 border rounded shadow">
                <form method="post">
                    <div class="login-link mb-3 mt-3 text-center">
                        <a type="button" class="btn btn-dark" href="statistical_analysis.php">Statistical Analysis</a>
                    </div>
                </form>
            </div>
            <div class="col-md-4 border rounded shadow">
                <form method="post">
                    <div class="login-link mb-3 mt-3 text-center">
                        <a type="button" class="btn btn-dark" href="../news_feed.php">News Feed</a>                      
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
