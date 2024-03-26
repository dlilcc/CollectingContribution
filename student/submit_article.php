<?php
session_start();

require_once 'send_mail.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header('Location: /CollectingContribution/login.php');
    exit;
}

// Include database connection
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if the Terms and Conditions checkbox is checked
    // If the checkbox is checked, proceed with article submission
    if (!isset($_POST['agree_terms'])) {
        // Display an error message and redirect back to the write article page
        $_SESSION['error'] = 'You must agree to the Terms and Conditions before submitting.';
        header('Location: write_article.php');
        exit;
    }

    // Retrieve article data from the form
    $article_title = $_POST['article_title'];
    $article_content = $_POST['article_content'];
    $user_id = $_SESSION['user']['id']; // Assuming user ID is stored in the session

    // Fetch user's faculty information from the database
    $stmt = $pdo->prepare("SELECT faculty_name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch user's email information from the database
    $stmt = $pdo->prepare("SELECT email FROM users WHERE role = 'coordinator'");
    $stmt->execute();
    $user_email = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check closure date and final closure date
    $current_date = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT closure_date, final_closure_date FROM closure_dates ORDER BY closure_date DESC LIMIT 1");
    $stmt->execute();
    $closure_dates = $stmt->fetch(PDO::FETCH_ASSOC);

    $email = "vnguyenduylinh@gmail.com";
    $title = "new submittion";
    $message = "you have new submittion";

    if (!is_article_submission_disabled()) {
        // File upload handling for image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'images/';
            $image_name = uniqid('image_') . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
            $image_url = $upload_dir . $image_name;
        } else {
            $image_url = ''; // Set default image URL if no image uploaded
        }

        // Insert article into database with submission date and image URL
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, image_url, user_id, submission_date, faculty_name, closure_date, final_closure_date) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?)");
        $stmt->execute([$article_title, $article_content, $image_url, $user_id, $user['faculty_name'], $closure_dates['closure_date'], $closure_dates['final_closure_date']]);
        
        // Sending notification for the Coordinator 
        sendMail($email, $title, $message);

        // Redirect to submission confirmation page
        header('Location: submission_confirmation.php');
        exit;

    } else {
        // Closure date exceeded or final closure date reached, disable article submission
        echo "New article submissions are disabled.";
        exit;
    }  
} else {
    // Redirect to write article page if form is not submitted
    header('Location: write_article.php');
    exit;
}
?>

