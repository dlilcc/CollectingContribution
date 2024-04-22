<?php
// Include PHPWord library
require_once '../vendor/autoload.php';

// Include database configuration
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Fetch all approved articles
$stmt = $pdo->prepare("SELECT id, title, content, image_url, file_name FROM articles WHERE is_published = 1");
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create a directory to store articles
$articlesDir = __DIR__ . '/approved_articles_' . date('Y-m-d');
if (!file_exists($articlesDir)) {
    mkdir($articlesDir, 0777, true);
}

foreach ($articles as $article) {
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

    // Save Word document to article's directory
    $articleDir = $articlesDir . '/' . sanitize_filename($article['title']);
    if (!file_exists($articleDir)) {
        mkdir($articleDir, 0777, true);
    }
    $word_file = $articleDir . '/' . sanitize_filename($article['title']) . '.docx';
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($word_file);

    // Save attached file to article's directory
    if (!empty($article['file_name'])) {
        $attachedFilePath = '../uploads/' . $article['file_name'];
        if (file_exists($attachedFilePath)) {
            copy($attachedFilePath, $articleDir . '/' . $article['file_name']);
        }
    }
}

// Zip the articles directory
$zip_file = __DIR__ . '/approved_articles_' . date('Y-m-d') . '.zip';
$zip = new ZipArchive();
if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($articlesDir), RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($articlesDir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();

    // Force download the ZIP file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="approved_articles_' . date('Y-m-d') . '.zip"');
    header('Content-Length: ' . filesize($zip_file));
    readfile($zip_file);

    // Delete the articles directory and ZIP file
    delete_directory($articlesDir);
    unlink($word_file);
    unlink($zip_file);

    header('Location: ../marketing_coordinator/coordinator_manage_article.php');

    exit;
} else {
    // Handle the error (display an error message or redirect)
    echo "Error: Failed to create ZIP file.";
    exit();
}


?>
