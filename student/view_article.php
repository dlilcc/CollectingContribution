<?php
// Include database configuration
require_once __DIR__ . '/../includes/config.php';

// Check if article ID is provided
if(isset($_GET['id'])) {
    // Sanitize the article ID
    $article_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Retrieve article details from the database
    $stmt = $pdo->prepare("SELECT title, content, image_url FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($article) {
        // Output the article title and content
        echo "<h1>{$article['title']}</h1>";
        echo "<p>{$article['content']}</p>";

        // Check if an image URL is provided
        if (!empty($article['image_url'])) {
            // Output the image
            echo "<img src='{$article['image_url']}' alt='Article Image'>";
        }
    } else {
        echo "Article not found.";
    }
} else {
    echo "Article ID not provided.";
}
?>
