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
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
</head>
<body>
    <h1>Write Article</h1>
    <form action="submit_article.php" method="post" enctype="multipart/form-data">
        <label for="article_title">Article Title:</label><br>
        <input type="text" id="article_title" name="article_title" required><br><br>
        <label for="editor">Article Content:</label><br>
        <textarea id="editor" name="article_content" ></textarea><br><br>
        <label for="image">Upload Image:</label><br>
        <input type="file" id="image" name="image"><br><br>
        <button type="submit">Submit</button>
    </form>

    <!-- Initialize CKEditor -->
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
        // Validate article content before form submission
        document.getElementById('articleForm').addEventListener('submit', function(event) {
            var articleContent = CKEDITOR.instances.editor.getData().trim();
            if (!articleContent) {
                alert('Article content is required');
                event.preventDefault(); // Prevent form submission
            }
        });    
    </script>
</body>
</html>
