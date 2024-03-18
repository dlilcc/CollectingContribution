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

// Fetch submitted articles from the database
$stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 0 AND is_published = 0 AND faculty_name = ?");
$stmt->execute([$user['faculty_name']]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process article approval
if (isset($_POST['approve'])) {
    $article_id = $_POST['article_id'];
    $stmt = $pdo->prepare("UPDATE articles SET is_published = 1 WHERE id = ?");
    $stmt->execute([$article_id]);
}

// Process article reject
if (isset($_POST['reject'])) {
    $article_id = $_POST['article_id'];
    $stmt = $pdo->prepare("UPDATE articles SET is_disabled = 1 WHERE id = ?");
    $stmt->execute([$article_id]); 
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
    <h1>Marketing Coordinator - Manage Articles</h1>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Content</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?php echo $article['title']; ?></td>
                    <td><?php echo $article['user_id']; ?></td> <!-- Assuming user_id is the author's ID -->
                    <td><?php echo $article['content']; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                            <button type="submit" name="approve">Approve</button>
                            <button type="submit" name="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../index.php" class="logout">Back</a>   
</body>
</html>
