<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    // Redirect to login page if not logged in
    header('Location: /CollectingContribution/login.php');
    exit;
}
// $user_id = $_SESSION['user']['id'];
// $stmt = $pdo->prepare("SELECT faculty_name FROM users WHERE id = ?");
// $stmt -> execute([$user_id]);
// $user_faculty = $stmt -> fetch(PDO::FETCH_ASSOC);
// echo $user_faculty['faculty_name'];
// $day = is_article_submission_disabled();
// echo $day;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Article - University Magazine</title>
    <!-- Include CKEditor -->
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
</head>
<body>
    <h1>Write Article</h1>
    <form id="articleForm" action="submit_article.php" method="post" enctype="multipart/form-data">
        <label for="article_title">Article Title:</label><br>
        <input type="text" id="article_title" name="article_title" required><br><br>
        <label for="article_content">Article Content:</label><br>

        <!-- Include textarea for CKEditor -->
        <textarea id="article_content" name="article_content" required></textarea><br><br>

        <!-- Include file uploading -->
        <label for="document">Upload Word Document:</label>
        <input type="file" name="document"><br><br>
        
        <!-- Include file input for image upload -->
        <label for="image">Article Image:</label>
        <input type="file" id="image" name="image"><br><br>
        <form method="post" enctype="multipart/form-data">
            <!-- Other form fields -->
            <label for="agree_terms">
                <input type="checkbox" name="agree_terms" id="agree_terms" required>
                I agree to the Terms and Conditions
            </label>
            <button type="submit" name="submit">Submit Article</button>
        </form>

        <!--<button type="submit">Submit</button>-->
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

