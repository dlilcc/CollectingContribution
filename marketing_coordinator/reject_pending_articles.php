<?php
// Include necessary files and perform database connection
require_once __DIR__ . '/../includes/config.php';

// Perform a database query to select articles pending approval for more than 14 days
$stmt = $pdo->prepare("SELECT id FROM articles WHERE submission_date < DATE_SUB(NOW(), INTERVAL 14 DAY) AND is_published = 0");
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Automatically reject articles
foreach ($articles as $article) {
    $articleId = $article['id'];
    
    // Update the is_approved column to indicate that the article has been rejected
    $stmt = $pdo->prepare("UPDATE articles SET is_disabled = 1 WHERE id = ?");
    $stmt->execute([$articleId]);
}

// Optionally, send notifications to users informing them that their articles have been rejected
?>
