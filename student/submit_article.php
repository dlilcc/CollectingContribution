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
    $articleTitle = $_POST['article_title'];
    $articleContent = $_POST['article_content'];
    $userId = $_SESSION['user']['id']; // Assuming user ID is stored in the session

    // Fetch user's information from the database
    $stmt = $pdo->prepare("SELECT username, email, faculty_name FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $user['username'];
    $facultyName = $user['faculty_name'];

    // Fetch coordinator's email from the database
    $stmt = $pdo->prepare("SELECT email FROM users WHERE faculty_name = ? AND role = 'coordinator'");
    $stmt->execute([$facultyName]);
    $userEmail = $stmt->fetch(PDO::FETCH_ASSOC);
    $userEmail = implode(', ', $userEmail);

    // Check closure date and final closure date
    $currentDate = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT closure_date, final_closure_date FROM closure_dates ORDER BY closure_date DESC LIMIT 1");
    $stmt->execute();
    $closureDates = $stmt->fetch(PDO::FETCH_ASSOC);

    $title = "New Submission";
    $message = "{$username} has submitted a new article";

    // Ensure article submission is not disabled
    if (!is_article_submission_disabled()) {
        // File upload handling for image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'images/';
            $imageName = uniqid('image_') . '_' . $_FILES['image']['name'];
            $imageUrl = $uploadDir . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $imageUrl);
        } else {
            $imageUrl = ''; // Set default image URL if no image uploaded
        }

        // Handle uploaded document
        $document = $_FILES['document'];
        $documentName = $document['name'];
        $documentTmpName = $document['tmp_name'];

        // Check if the file is null
        if ($documentName !== '') {
            // Validate file extension
            $fileExtension = pathinfo($documentName, PATHINFO_EXTENSION);
            $allowedExtensions = array('doc', 'docx');
            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                echo "<script>
                    alert('Only Word documents (DOC/DOCX) are allowed.');
                    window.location.href = 'write_article.php'
                </script>";
                exit;
            } else {
                // Move uploaded file to desired location
                $uploadDir = '../uploads/';
                $uploadPath = $uploadDir . $documentName;
                if (!move_uploaded_file($documentTmpName, $uploadPath)) {
                    $_SESSION['error'] = "Error: Failed to upload document.";
                    header('Location: write_article.php');
                    exit;
                }
            }
        }



        // Insert article into database with submission date, image URL, and file name
        try {
            $stmt = $pdo->prepare("INSERT INTO articles (title, content, image_url, user_id, submission_date, faculty_name, closure_date, final_closure_date, file_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$articleTitle, $articleContent, $imageUrl, $userId, $currentDate, $facultyName, $closureDates['closure_date'], $closureDates['final_closure_date'], $documentName]);
            
            // Sending notification for the Coordinator 
            sendMail($userEmail, $title, $message);
            
            // Redirect to submission confirmation page
            header('Location: submission_confirmation.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header('Location: write_article.php');
            exit;
        }
    } else {
        // Closure date exceeded or final closure date reached, disable article submission
        echo "<script>
            alert('New article submissions are disabled.');
            window.location.href = 'write_article.php'
        </script>";
    }
} else {
    // Redirect to write article page if form is not submitted
    header('Location: write_article.php');
    exit;
}
?>
