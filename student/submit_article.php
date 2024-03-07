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



// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve article data from the form
    $article_title = $_POST['article_title'];
    $article_content = $_POST['article_content'];
    $user_id = $_SESSION['user']['id']; // Assuming user ID is stored in the session

    // File upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $image_name = uniqid('image_') . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
    } else {
        $image_name = ''; // Set default image name if no image uploaded
    }

    // Insert article into database with submission date
    $stmt = $conn->prepare("INSERT INTO articles (title, content, image_url, user_id, submission_date) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->execute([$article_title, $article_content, $image_name, $user_id]);

    // Redirect to submission confirmation page
    header('Location: submission_confirmation.php');
    exit;
} else {
    // Redirect to write article page if form is not submitted
    header('Location: write_article.php');
    exit;
}
?>
