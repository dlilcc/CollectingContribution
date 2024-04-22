<?php
session_start();

// Include necessary files and database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if user is logged in and is a coordinator
if (!is_logged_in()) {
    // Redirect to login page if not logged in or not a coordinator
    header('Location: ../login.php');
    exit;
}

// Fetch article details
if(isset($_GET['id'])) {
    $article_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if article exists
    if(!$article) {
        echo "Error: Article not found.";
        exit;
    }
} else {
    echo "Error: Article ID not provided.";
    exit;
}

// Process comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $coordinator_id = $_SESSION['user']['id']; // Coordinator ID from session
    $comment_text = $_POST['comment_text'];

    // Insert comment into database
    $stmt = $pdo->prepare("INSERT INTO comments (article_id, coordinator_id, comment_text) VALUES (?, ?, ?)");
    $stmt->execute([$article_id, $coordinator_id, $comment_text]);

    $comment = 'yes';
    $stmt = $pdo->prepare("UPDATE articles SET comment = ? WHERE id = ?");
    $stmt->execute([$comment, $article_id]);
    

    // Redirect back to the same article view page after submitting the comment
    header("Location: view_article.php?id=$article_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Article</title>
    <!-- Add your CSS styles here -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />

</head>
<body>

    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>View Articles</h1>
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
    
    <div class="container">
        <div class="text-center h4">
            <?php echo ($article['content']); ?>
        </div>
        <div class="row">
            <div class="col-sm-6 text-center">
                <?php if (!empty($article['image_url'])) : ?>
                    <img class="col-sm-10 img-fluid" src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="Article Image">
                <?php endif; ?>
            </div>
            <div class="col-sm-6">

                <div class="my-4"> <!-- Thêm class "my-4" để tạo khoảng cách giữa nội dung và khung comment -->
                    <div class="border p-3"> <!-- Thêm class "border" và "p-3" để tạo khung và padding cho comment -->
                        <h5 class="text-center">Comment</h5>
                        <!-- Display comments -->

                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM comments WHERE article_id = ?");
                        $stmt->execute([$article_id]);
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                        foreach ($comments as $comment) {
                            echo "<p class='bg-info text-white rounded p-3'>{$comment['comment_text']}</p>";
                            echo "<div class='mb-3 text-center'>";
                            echo "<small>Posted by Coordinator {$comment['coordinator_id']} at {$comment['created_at']}</small>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>                                                              
            </div>
        </div> 

        <!-- Form for coordinators to leave comments -->
        <?php if (has_role("coordinator") && checkComment($article_id)) : ?> 
            <?php echo checkComment($article_id); ?>
        <form method="post">
            <textarea name="comment_text" placeholder="Leave a comment..." required></textarea>
            <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
            <button type="submit" name="submit_comment">Submit Comment</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
