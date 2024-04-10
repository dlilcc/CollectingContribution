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

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
    <h1 class="display-1 text-center">Write Article</h1>
    <form id="articleForm" action="submit_article.php" method="post" enctype="multipart/form-data" >
        <div class="mb-3 row" >
                <label class=" col-form-label" for="article_title">Article Title:</label><br>
            <div class="col-sm-8">
                <input class="form-control" type="text" id="article_title" name="article_title" required><br><br>
            </div>
        </div>
        <label for="article_content">Article Content:</label><br>

        <!-- Include textarea for CKEditor -->
        <div class="row">
            <div class="mb-3 mt-3 col-md-10 offset-md-1">
                <textarea id="article_content" name="article_content" class="form-control" required></textarea><br><br>
            </div>
        </div>
        
        <form action="">
            
        <!-- Include file uploading -->
            <div class="row">
                <div class="">
                    <label for="document" lass="form-label custom-label">Upload Word Document:</label>
                </div>
                <div class="">
                    <input type="file" name="document" class="form-control"><br><br>                
                </div>
            </div>

        <!-- Include file input for image upload -->
            <div class="row">
                <div class="">
                    <label for="image" class="form-label custom-label">Article Image:</label>
                </div>
                <div class="">
                    <input type="file" id="image" name="image" class="form-control"><br><br>           
                </div>
            </div> 

        <!-- Other form fields -->
            <form method="post" enctype="multipart/form-data">
                <div class="form-checkbox">
                    <label for="agree_terms">
                        <label class="form-check-label" for="flexCheckIndeterminate">
                            I agree to the Terms and Conditions
                        </label>
                        <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms" required>
                    </label>
                    <!--<button type="submit">Submit</button>-->
                    <button class="btn btn-secondary" type="submit" name="submit">Submit Article</button>
                </div>
            </form>            
        </form>
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
        <a href="../index.php" class="btn btn-primary">Back</a>
</body>
</html>

