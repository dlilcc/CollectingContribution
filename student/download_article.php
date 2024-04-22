<?php
// Include PHPWord library
require_once '../vendor/autoload.php';

// Include database configuration
require_once __DIR__ . '/../includes/config.php';

// Check if article ID is provided
if(isset($_GET['id'])) {
    // Sanitize the article ID
    $article_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Retrieve the article content from the database based on the article ID
    $stmt = $pdo->prepare("SELECT title, content, image_url, file_name FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if article exists
    if($article) {

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

            // Add the associated file to the ZIP archive
            $article_file_path = '../uploads/' . $article['file_name']; // Assuming uploads folder for the files
            if (file_exists($article_file_path)) {
                $zip->addFile($article_file_path, $article['file_name']);
            }

            $zip->close();

            // Force download the ZIP file
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="selected_contribution_' . $article_id . '.zip"');
            header('Content-Length: ' . filesize($zip_file));
            readfile($zip_file);
    
            // Delete temporary files and directory
            unlink($word_file);
            unlink($zip_file);

            exit;
        } else {
            // Article not found, handle the error (display an error message or redirect)
            echo "Error: Article not found.";
            exit();
        }
    } else {
        // Article ID not provided, handle the error (display an error message or redirect)
        echo "Error: Article ID not provided.";
        exit();
    }
}
?>
