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
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Write Article</h1>
    </div>
    <div class="container">
        <div class=" justify-content-around bg-secondary mb-3">  
            <a href="../index.php" class="btn btn-outline-primary">Back</a>             
        </div>
    </div>
    <div class="container">

        <form id="articleForm" action="submit_article.php" method="post" enctype="multipart/form-data" class="border rounded p-3 shadow">
            <div class="row mb-3">
                <label class="col col-xl-4 col-form-label text-start custom-label" for="article_title">Article Title:</label>
                <div class="col col-xl-8">
                    <input class="col form-control" type="text" id="article_title" name="article_title" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="document" class="col col-xl-4 col-form-label text-start custom-label">Upload Word Document:</label>
                <div class="col col-xl-8">
                    <input type="file" name="document" class="form-control">
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="image" class="col col-xl-4 col-form-label text-start custom-label">Article Image:</label>
                <div class="col col-xl-8">
                    <input type="file" id="image" name="image" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col col-form-label" for="article_title">Article Title:</label>
                <div class="col col-xl-8">
                    <textarea id="article_content" name="article_content" class="form-control" required></textarea>
                </div>
            </div>
            
            <form action=""> 
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
    </div>

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

