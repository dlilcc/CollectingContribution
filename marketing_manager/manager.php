<?php
session_start();

// Check if user is logged in and is the University Marketing Manager
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'manager') {
    // Redirect to login page if not logged in or not the University Marketing Manager
    header('Location: /CollectingContribution/login.php');
    exit;
}

// Include necessary files and configurations
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Include PHPWord library
require_once '../vendor/autoload.php';

// Function to sanitize article ID
function sanitizeArticleID($id) {
    return filter_var($id, FILTER_SANITIZE_NUMBER_INT);
}

// Function to fetch article details by ID
function fetchArticleDetails($pdo, $article_id) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch article details
if(isset($_GET['id'])) {
    $article_id = sanitizeArticleID($_GET['id']);
    $article = fetchArticleDetails($pdo, $article_id);

    // Check if article exists
    if(!$article) {
        $_SESSION['error'] = 'Article not found.';
        header('Location: university_marketing_manager.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Article ID not provided.';
    header('Location: university_marketing_manager.php');
    exit;
}

// Check if form is submitted to download selected contribution as ZIP
if ($_GET['action'] === 'download_zip') {

    // Convert article content to Word document
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $section = $phpWord->addSection();
    $section->addTitle(htmlspecialchars($article['title']), 1);
    $section->addText(htmlspecialchars($article['content']));

    // Check if an image URL is provided
    if (!empty($article['image_url'])) {
        // Add the image to the document
        $imagePath = '../student/images/' . basename($article['image_url']);
        $section->addImage($imagePath, ['width' => 200, 'height' => 200]);
    }

    // Save Word document to temporary directory
    $temp_dir = sys_get_temp_dir();
    $word_file = tempnam($temp_dir, 'article_') . '.docx';
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($word_file);

    // Create a ZIP archive
    $zip_file = __DIR__ . '/selected_contribution_' . $article_id . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        // Add the Word document to the ZIP archive
        $zip->addFile($word_file, basename($word_file));
        $zip->close();

        // Force download the ZIP file
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="selected_contribution_' . $article_id . '.zip"');
        header('Content-Length: ' . filesize($zip_file));
        readfile($zip_file);

        // Delete temporary files and directory
        unlink($word_file);
        exit;
    } else {
        $_SESSION['error'] = 'Failed to create ZIP archive.';
        header('Location: university_marketing_manager.php');
        exit;
    }
}
?>