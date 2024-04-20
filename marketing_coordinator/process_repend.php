<?php
session_start();

// Check if the user is logged in and has coordinator role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'coordinator') {
    // Redirect to login page if not logged in or not a coordinator
    header('Location: login.php');
    exit;
}

// Include database configuration and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

$user_id = $_SESSION['user']['id'];

// Fetch user's faculty information from the database
$stmt = $pdo->prepare("SELECT faculty_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch pended articles from the database
$stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 1 AND faculty_name = ?");
$stmt->execute([$user['faculty_name']]);
$pendedArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$flag = false;
// Process article approval
if (isset($_POST['approve'])) {
    $article_id = $_POST['article_id'];
    $stmt = $pdo->prepare("UPDATE articles SET is_published = 1 WHERE id = ?");
    $stmt->execute([$article_id]);
    $stmt = $pdo->prepare("UPDATE articles SET is_disabled = 0 WHERE id = ?");
    $stmt->execute([$article_id]);
    $flag = true;
}

// Process article reject
if (isset($_POST['reject'])) {
    $article_id = $_POST['article_id'];
    $stmt = $pdo->prepare("UPDATE articles SET is_published = 0 WHERE id = ?");
    $stmt->execute([$article_id]);
    $stmt = $pdo->prepare("UPDATE articles SET is_disabled = 1 WHERE id = ?");
    $stmt->execute([$article_id]);
    $flag = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator Manage Articles</title>
</head>
<body>
    <h1>Marketing Coordinator - Manage Submitted Articles</h1>
        <h2>Re-Pending Article</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Content</th>
                    <th>Action</th>
                    <?php echo $flag;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendedArticles as $pendedArticle): ?>
                    <tr>
                        <td><?php echo $pendedArticle['title']; ?></td>
                        <td><?php echo $pendedArticle['user_id']; ?></td> <!-- Assuming user_id is the author's ID -->
                        <td><?php echo $pendedArticle['content']; ?></td>
                        <?php if ($flag == true) : ?>
                            <td>Pended</td>
                        <?php else : ?>
                        <td>
                        <form action="" method="post">
                            <input type="hidden" name="article_id" value="<?php echo $pendedArticle['id']; ?>">
                            <button type="submit" name="approve">Approve</button>
                            <button type="submit" name="reject">Reject</button>
                        </form>
                        </td>
                        <?php endif ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <a href="coordinator_manage_article.php">Back</a>
</body>