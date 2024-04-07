<?php
session_start();

// Include necessary files
require_once 'functions.php';


// Check if the user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// Fetch the user's ID from the session
$user_id = $_SESSION['user']['id'];

// Fetch the user's faculty from the database
$stmt = $pdo->prepare("SELECT faculty_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user's faculty is fetched successfully
if (!$user || empty($user['faculty_name'])) {
    // Handle error (redirect, display message, etc.)
    exit('Error: Unable to fetch user\'s faculty');
}

$user_faculty = $user['faculty_name'];

// Fetch articles from the database based on the user's faculty
$stmt = $pdo->prepare("SELECT * FROM articles WHERE faculty_name = ? AND is_published = 1 ");
$stmt->execute([$user_faculty]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Feed - University Magazine</title>
    <style>
        .article {
            margin-bottom: 20px;
        }
        .article img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>News Feed - <?php echo htmlspecialchars($user_faculty); ?></h1>

    <div>
        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                <?php if (!empty($article['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="Article Image">
                <?php endif; ?>
                <p><a href="student/view_article.php?id=<?php echo $article['id']; ?>">Read more</a></p>
                <?php if (has_role('manager')) : ?>
                <li><a href="marketing_manager/manager.php?id=<?php echo $article['id']; ?>">Manager control</a></li>
                <?php endif; ?>

                <?php
                if (is_article_update_disabled() && has_role('manager')) {
                    echo '<a href="marketing_manager/manager.php?id=' . $article['id'] . '&action=download_zip">Download ZIP</a>';
                }
                ?>
                
            </div>
            <hr>
        <?php endforeach; ?>
    </div>
    <a href="index.php" class="back">Back</a>
</body>
</html>


