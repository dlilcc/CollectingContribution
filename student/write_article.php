<?php
session_start();


// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header('Location: /CollectingContribution/login.php');
    exit;
}
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
        <textarea id="article_content" name="article_content" required></textarea>
        <br><br>
        <!-- Include file input for image upload -->
        <label for="image">Article Image:</label><br>
        <input type="file" id="image" name="image">
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
