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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />
</head>
<body>
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Marketing Coordinator - Manage Submitted Articles</h1>
    </div>
    <div class="container">
        <div class="row justify-content-around bg-secondary mb-3 align-items-center">  
            <div class="col ">
                <a href="./coordinator_manage_article.php" class="btn btn-outline-primary align-items-center">Back</a>  
            </div>
            <div class="col text-center">
                <h2>Re-Pending Article</h2>
            </div>
            <div class="col text-end">
            </div>    
        </div>
        <table class="table table-bordered table-sm col table-hover">
            <thead class="thead-dark">
                <tr class="text-center row">
                    <th class="col">Title</th>
                    <th class="col">Author</th>
                    <th class="col">Content</th>
                    <th class="col">Action</th>
                    <?php echo $flag;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendedArticles as $pendedArticle): ?>
                    <tr class="row">
                        <td class="col"><?php echo $pendedArticle['title']; ?></td>
                        <td class="col"><?php echo $pendedArticle['user_id']; ?></td> <!-- Assuming user_id is the author's ID -->
                        <td class="col"><?php echo $pendedArticle['content']; ?></td>
                        <?php if ($flag == true) : ?>
                            <td class="col">Pended</td>
                        <?php else : ?>
                        <td class="col">
                        <form class="text-end" action="" method="post">
                            <input type="hidden" name="article_id" value="<?php echo $pendedArticle['id']; ?>">
                            <button class="btn btn-secondary" type="submit" name="approve">Approve</button>
                            <button class="btn btn-secondary" type="submit" name="reject">Reject</button>
                        </form>
                        </td>
                        <?php endif ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>