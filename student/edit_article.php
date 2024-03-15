<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header('Location: /CollectingContribution/login.php');
    exit;
}

// Include database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if article ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to some error page or display an error message
    exit('Invalid article ID');
}

$article_id = $_GET['id'];
$user_id = $_SESSION['user']['id']; // Assuming user ID is stored in the session

// Retrieve article data from the database
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ? AND user_id = ?");
$stmt->execute([$article_id, $user_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);


// Check if article exists and belongs to the logged-in user
if (!$article) {
    // Redirect to some error page or display an error message
    exit('Article not found or you do not have permission to edit this article');
}

// Handle form submission if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated article data from the form
    $article_title = $_POST['article_title'];
    $article_content = $_POST['article_content'];

    // Check closure date and final closure date
    $current_date = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT closure_date, final_closure_date FROM closure_dates ORDER BY closure_date DESC LIMIT 1");
    $stmt->execute();
    $closure_dates = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!is_article_update_disabled()){
        // Check if an image file was uploaded
        if ($_FILES['article_image']['error'] === UPLOAD_ERR_OK) {
            // Retrieve the temporary file path of the uploaded image
            $tmp_image_path = $_FILES['article_image']['tmp_name'];

            // Define the directory where the image will be stored
            $upload_dir = 'images/';
            // Generate a unique filename for the uploaded image
            $image_filename = uniqid() . '_' . $_FILES['article_image']['name'];
            // Construct the full path of the image file on the server
            $image_path = $upload_dir . $image_filename;

            // Move the uploaded image to the specified directory
            if (move_uploaded_file($tmp_image_path, $image_path)) {
                // Image uploaded successfully, update the database with the new image path
                $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, image_url = ? WHERE id = ?");
                $stmt->execute([$article_title, $article_content, $image_path, $article_id]);
            } else {
                // Failed to move uploaded image, handle error as needed
                // You may display an error message or redirect back to the edit form
            }
        } else {
            // No image file was uploaded, update the database without changing the image path
            $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$article_title, $article_content, $article_id]);
        }

        // Redirect to submission confirmation page or wherever you want
        header('Location: submission_confirmation.php');
        exit;
    } else {
        // Closure date exceeded or final closure date reached, disable article update
        echo "Article updates are disabled.";
        exit;
    }

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - University Magazine</title>
    <!-- Include CKEditor -->
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
</head>
<body>
    <h1>Edit Article</h1>
    <form id="articleForm" action="" method="post" enctype="multipart/form-data">
        <label for="article_title">Article Title:</label><br>
        <input type="text" id="article_title" name="article_title" value="<?php echo htmlspecialchars($article['title']); ?>" required><br><br>
        <label for="article_content">Article Content:</label><br>
        <!-- Include textarea for CKEditor -->
        <textarea id="article_content" name="article_content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        <br><br>
        <!-- Include file input for image upload -->
        <label for="article_image">Article Image:</label><br>
        <input type="file" id="article_image" name="article_image">
        <br><br>
        <button type="submit">Submit</button>
    </form>

    <!-- Initialize CKEditor -->
    <script>
        CKEDITOR.replace('article_content');

        // Validate article content before form submission
        document.getElementById('articleForm').addEventListener('submit', function(event) {
            var articleContent = CKEDITOR.instances.article_content.getData().trim();
            if (!articleContent) {
                alert('Article content is required');
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
</body>
</html>


