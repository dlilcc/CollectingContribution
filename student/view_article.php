<?php
session_start();

// Include necessary files and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if user is logged in and is a coordinator
if (!is_logged_in()) {
    // Redirect to login page if not logged in or not a coordinator
    header('Location: ../login.php');
    exit;
}

// Fetch article details
if(isset($_GET['id'])) {
    $article_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if article exists
    if(!$article) {
        echo "Error: Article not found.";
        exit;
    }
} else {
    echo "Error: Article ID not provided.";
    exit;
}

// Process comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $coordinator_id = $_SESSION['user']['id']; // Coordinator ID from session
    $comment_text = $_POST['comment_text'];

    // Insert comment into database
    $stmt = $pdo->prepare("INSERT INTO comments (article_id, coordinator_id, comment_text) VALUES (?, ?, ?)");
    $stmt->execute([$article_id, $coordinator_id, $comment_text]);

    // Redirect back to the same article view page after submitting the comment
    header("Location: view_article.php?id=$article_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Article</title>
    <!-- Add your CSS styles here -->
</head>
<body>
    <h1><?php echo htmlspecialchars($article['title']); ?></h1>
    <div>
        <?php echo ($article['content']); ?>
        <?php if (!empty($article['image_url'])) : ?>
            <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="Article Image">
        <?php endif; ?>
    </div>

    <!-- Display comments -->
    <?php
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE article_id = ?");
    $stmt->execute([$article_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($comments as $comment) {
        echo "<div>";
        echo "<p>{$comment['comment_text']}</p>";
        echo "<small>Posted by Coordinator {$comment['coordinator_id']} at {$comment['created_at']}</small>";
        echo "</div>";
    }
    ?>

    <!-- Form for coordinators to leave comments -->
    <?php if (has_role("coordinator")) : ?> 
    <form method="post">
        <textarea name="comment_text" placeholder="Leave a comment..." required></textarea>
        <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
        <button type="submit" name="submit_comment">Submit Comment</button>
    </form>
    <?php endif; ?>
</body>
</html>
