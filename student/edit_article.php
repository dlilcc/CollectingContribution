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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />

</head>
<body>

    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Edit Article</h1>
    </div>

    <div class="container">
        <div class="row justify-content-around bg-secondary mb-3 align-items-center">  
            <div class="col ">
                <a href="../student/manage_article.php" class="btn btn-outline-primary align-items-center">Back</a>  
            </div>
            <div class="col text-center">
                <h1><?php echo htmlspecialchars($article['title']); ?></h1>       
            </div>
            <div class="col">

            </div>    
        </div>
    </div>
    
    <Div class="container">
        <form id="articleForm" action="" method="post" enctype="multipart/form-data" class="border rounded p-3 shadow">
            <div class="row mb-2">
                <label class="col col-xl-2 col-form-label text-start custom-label" for="article_title">Article Title:</label>
                <div class="col col-xl-10">
                    <input class="col form-control" type="text" id="article_title" name="article_title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col col-xl-2 col-form-label text-start custom-label" for="article_content">Article Content:</label>
                <div class="col col-xl-10">
                    <input type="file" name="document" class="form-control">
                </div>
            </div>
            
            <!-- Include file input for image upload -->
            <div class="row mb-3">
                <label class="col col-xl-2 col-form-label text-start custom-label" for="article_image">Article Image:</label>
                <div class="col col-xl-10">
                    <input class="col form-control" type="file" id="article_image" name="article_image">
                </div>
            </div>

            <!-- Include textarea for CKEditor -->
            <div class="row bm-3">
                <div class="col col-xl-12">
                    <textarea id="article_content" name="article_content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                </div>
            </div>

            <form method="post" enctype="multipart/form-data">
                <div class="form-checkbox">
                    <label for="agree_terms">
                        <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms" required>
                        <label class="form-check-label" for="flexCheckIndeterminate">
                            I agree to the Terms and Conditions
                        </label>
                    </label>
                    <!--<button type="submit">Submit</button>-->
                    <button class="btn btn-secondary" type="submit" name="submit">Submit</button>
                </div>
            </form>
        </form>
    </Div>

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
    <a href="../index.php" class="back">Back</a>
</body>
</html>


