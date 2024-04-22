<?php
session_start();

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if the user is logged in and has coordinator or manager role
if (!has_role('coordinator') && !has_role('manager')) {
    // Redirect to login page if not logged in or not a coordinator or manager
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch all faculties
$faculties = get_faculties();

// Initialize variable
$selected_faculty = '';

// Fetch user's faculty information from the database
$stmt = $pdo->prepare("SELECT faculty_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch articles based on selected faculty
if (isset($_POST['faculty'])) {
    $selected_faculty = $_POST['faculty'];
    if ($selected_faculty == 'all') {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 0 AND is_published = 0");
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 0 AND is_published = 0 AND faculty_name = ?");
        $stmt->execute([$selected_faculty]);
    }
} else {
    // Default to current user's faculty
    $selected_faculty = $user['faculty_name'];
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 0 AND is_published = 0 AND faculty_name = ?");
    $stmt->execute([$user['faculty_name']]);
}

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch pended articles based on the selected faculty
if ($selected_faculty == 'all') {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 1 OR is_published = 1");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE is_disabled = 1 OR is_published = 1 AND faculty_name = ?");
    $stmt->execute([$selected_faculty]);
}
$pendedArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process article approval
if (isset($_POST['approve'])) {
    $article_id = $_POST['article_id'];
    $stmt = $pdo->prepare("UPDATE articles SET is_published = 1, is_disabled = 0 WHERE id = ?");
    $stmt->execute([$article_id]);
}

// Process article reject
if (isset($_POST['reject'])) {
    $article_id = $_POST['article_id'];
    $stmt = $pdo->prepare("UPDATE articles SET is_published = 0, is_disabled = 1 WHERE id = ?");
    $stmt->execute([$article_id]);
}
?>

<!-- Rest of the HTML remains unchanged -->


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
    <style>
        .custom-table {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .custom-table td {
        text-align: center;
        vertical-align: middle;
        }
        .thead-dark{        
        text-align: center;
        vertical-align: middle;
        }
        .My-Padding{
            padding-top:2%
        }
        .center{
            text-align:center;
            padding-top:0.5%;
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
        <div class="row justify-content-around bg-secondary mb-3 align-items-center">  
            <div class="col ">
                <a href="coordinator_dashboard.php" class="btn btn-outline-primary align-items-center">Back</a>  
            </div>
            <div class="col text-center">
                <h2 >Pending Article</h2> 
            </div>
            <div class="col text-end">
                <?php if (has_role('coordinator')) : ?>
                    <a class="btn btn-outline-primary align-items-center" href="process_repend.php">Re-Pending Articles</a>
                <?php endif ?>
            </div>    
        </div>
    </div>

    <!-- Faculty selection form for Marketing Manager -->
    <?php if (has_role('manager')) : ?>
        <form method="post">
            <label for="faculty">Select Faculty:</label>
            <select name="faculty" id="faculty">
                <option value="all">All Faculties</option>
                <?php foreach ($faculties as $faculty) : ?>
                    <option value="<?php echo htmlspecialchars($faculty['faculty_name']); ?>" <?php echo ($selected_faculty === $faculty['faculty_name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($faculty['faculty_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filter</button>
        </form>
    <?php endif; ?>

    <div class="container">
        <table class="table table-bordered table-sm col">
            <!-- Table headers -->
            <thead class="thead-dark">
                <tr>
                    <th >Title</th>
                    <th>Author</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr class="text-wrap">
                        <td><?php echo $article['title']; ?></td>
                        <td><?php echo $article['user_id']; ?></td> <!-- Assuming user_id is the author's ID -->
                        <td><?php echo $article['content']; ?></td>
                        <?php if (has_role('manager')) : ?>
                            <td>Pending...</td>
                        <?php else : ?>
                        <td>
                            <form class="border rounded p-3 shadow" action="" method="post">
                                <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                <button type="submit" name="approve">Approve</button>
                                <button type="submit" name="reject">Reject</button>
                            </form>
                        </td>
                        <?php endif ?>
                        <td><a href="../student/view_article.php?id=<?php echo $article['id']; ?>">View Article</a></td>
                        <td><a href="../student/download_article.php?id=<?php echo $article['id']; ?>">Download Article</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="container">
        <h2 class="text-center">Submitted Article</h2>
        <?php if (has_role('manager')) : ?>
            <a href="../marketing_manager/download_all_approval_articles.php">Download All Approval Articles</a>
        <?php endif ?>
        <table class="table custom-table table-bordered table-shadow border rounded">
            <thead class="thead-dark">
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Content</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendedArticles as $pendedArticle): ?>
                    <tr>
                        <td><?php echo $pendedArticle['title']; ?></td>
                        <td><?php echo $pendedArticle['user_id']; ?></td> <!-- Assuming user_id is the author's ID -->
                        <div class="col">
                            <td class="text-wrap"><?php echo $pendedArticle['content']; ?></td>
                        </div>
                        <?php if ($pendedArticle['is_disabled'] == 1) : ?>
                            <td>Reject</td>
                        <?php else : ?>
                            <td>Approve</td>
                        <?php endif ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>               
</body>
</html>
