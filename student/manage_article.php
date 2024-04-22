<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

// Include database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';


// Fetch articles for the logged-in user
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT id, title, is_disabled, is_published FROM articles WHERE user_id = ?");
$stmt->execute([$user_id]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles</title>
</head>
<body>
    <h1>Manage Articles</h1>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <?php if($article['is_disabled'] == 1 && $article['is_published'] == 0) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td>
                            <a href="view_article.php?id=<?php echo $article['id']; ?>">View</a>
                            <a href="delete_article.php?id=<?php echo $article['id']; ?>">Delete</a>
                            <a href="download_article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Download as Word</a>
                            <t >Rejected</t>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php if($article['is_published'] == 1 && $article['is_disabled'] == 0) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td>
                            <a href="view_article.php?id=<?php echo $article['id']; ?>">View</a>
                            <a href="edit_article.php?id=<?php echo $article['id']; ?>">Edit</a>
                            <a href="delete_article.php?id=<?php echo $article['id']; ?>">Delete</a>
                            <a href="download_article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Download as Word</a>
                            <t>Approve</t>

                        </td>
                    </tr>
                <?php endif; ?>

                <?php if($article['is_published'] == 0 && $article['is_disabled'] == 0) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td>
                            <a href="view_article.php?id=<?php echo $article['id']; ?>">View</a>
                            <a href="edit_article.php?id=<?php echo $article['id']; ?>">Edit</a>
                            <a href="delete_article.php?id=<?php echo $article['id']; ?>">Delete</a>
                            <a href="download_article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Download as Word</a>
                            <t>Waiting</t>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../index.php" class="back">Back</a>
</body>
</html> -->

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles</title>
</head>
<body>
    <h1>Manage Articles</h1>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                    <td>
                        <a href="view_article.php?id=<?php echo $article['id']; ?>">View</a>
                        <a href="edit_article.php?id=<?php echo $article['id']; ?>">Edit</a>
                        <a href="delete_article.php?id=<?php echo $article['id']; ?>">Delete</a>
                        <?php if ($article['is_published'] == 1 && $article['is_disabled'] == 0): ?>
                            <a href="download_article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Download as Word</a>
                        <?php endif; ?>
                        <?php if ($article['is_disabled'] == 1 && $article['is_published'] == 0): ?>
                            <t>Rejected</t>
                        <?php endif; ?>
                        <?php if ($article['is_published'] == 0 && $article['is_disabled'] == 0): ?>
                            <t>Waiting</t>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../index.php" class="back">Back</a>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles</title>

    <link rel="stylesheet" href="../css/manage_article.css">
    <link rel="stylesheet" href="../css.manage_user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />
    <<style>
        .custom-table {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .custom-table thead {
        text-align: center;
        vertical-align: middle;
        }
        .thead-dark{        
        text-align: center;
        vertical-align: middle;
        }
        .custom-label {
            display: inline-block;
            width: auto;
        }
    </style>


</head>
<body>
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Manage Articles</h1>
    </div>
    
    <div class="container">
        <div class=" justify-content-around bg-secondary mb-3">  
            <a href="../index.php" class="btn btn-outline-primary">Back</a>             
        </div>
    </div>

    <div class="container">
        <div class="col-md-12">

            <table class="table custom-table table-bordered table-shadow border rounded">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($article['title']); ?></td>
                            <td>
                                
                                <a class="table-manager-link" href="view_article.php?id=<?php echo $article['id']; ?>">
                                    <button class="btn btn-warning btn-manager-view">
                                        View
                                    </button>
                                </a>
                                <a class="table-manager-link " href="edit_article.php?id=<?php echo $article['id']; ?>">
                                    <button class="btn btn-secondary btn-manager-edit">Edit</button>
                                </a>
                                <a class="table-manager-link " href="delete_article.php?id=<?php echo $article['id']; ?>">
                                    <button class="btn btn-danger btn-manager-delete">Delete</button>
                                </a>
                                <?php if ($article['is_published'] == 1 && $article['is_disabled'] == 0): ?>
                                    <a class="table-manager-link" href="download_article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Download as Word</a>
                                <?php endif; ?>
                                <?php if ($article['is_disabled'] == 1 && $article['is_published'] == 0): ?>
                                    <t>Rejected</t>
                                <?php endif; ?>
                                <?php if ($article['is_published'] == 0 && $article['is_disabled'] == 0): ?>
                                    <t>Waiting</t>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
