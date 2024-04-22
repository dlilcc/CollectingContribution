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

// Fetch the user's role and faculty from the database
$stmt = $pdo->prepare("SELECT role, faculty_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user's role and faculty are fetched successfully
if (!$user || empty($user['role'])) {
    // Handle error (redirect, display message, etc.)
    exit('Error: Unable to fetch user\'s role');
}

$user_role = $user['role'];
$user_faculty = $user['faculty_name'];

// Fetch all faculties
$faculties = get_faculties();

// Initialize variable
$selected_faculty = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_faculty = $_POST['faculty'];
}

// Retrieve articles based on user's role and selected faculty
if ($user_role === 'manager') {
    if (!empty($selected_faculty)) {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE faculty_name = ? AND is_published = 1");
        $stmt->execute([$selected_faculty]);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT * FROM articles WHERE is_published = 1");
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE faculty_name = ? AND is_published = 1");
    $stmt->execute([$user_faculty]);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Feed - University Magazine</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />

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
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>View Articles</h1>
    </div>

    <div class="container">
        <div class="row justify-content-around bg-secondary mb-3 align-items-center">  
            <div class="col ">
                <a href="index.php" class="btn btn-primary align-items-center">Back</a>  
            </div>
            
            <div class="col text-center">  
            </div>

            <div class="col">

            </div>    
        </div>
    </div>
    <div class="container">
        <!-- Faculty selection form for Marketing Manager -->
        <?php if ($user_role === 'manager') : ?>
            <form method="post">
                <label for="faculty">Select Faculty:</label>
                <select name="faculty" id="faculty">
                    <option value="">All Faculties</option>
                    <?php foreach ($faculties as $faculty) : ?>
                        <option value="<?php echo htmlspecialchars($faculty['faculty_name']); ?>" <?php echo ($selected_faculty === $faculty['faculty_name']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($faculty['faculty_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filter</button>
            </form>
        <?php endif; ?>
    
        <div>
            <?php foreach ($articles as $article): ?>
                <div class="article border rounded p-3 shadow">
                    <div class="row mb-3">
                        <div class="col col-xl-4">
                            <h2><?php echo htmlspecialchars($article['title']); ?></h2>

                            <p><a href="student/view_article.php?id=<?php echo $article['id']; ?>">Read more</a></p>

                            <?php
                            if (is_article_update_disabled() && has_role('manager')) {
                                echo '<a href="marketing_manager/manager.php?id=' . $article['id'] . '&action=download_zip">Download ZIP</a>';
                            }
                            if (has_role('coordinator')){
                                echo '<a href="student/download_article.php?id=' . $article['id'] . '">Download as Word</a>';
                            }
                            ?>

                        </div>
                        <div class="col col-xl-8 text-center">                            
                            <?php if (!empty($article['image_url'])): ?>
                                <img class="text-center" src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="Article Image">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
