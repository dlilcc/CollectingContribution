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
    $stmt = $pdo->prepare("SELECT title, content FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if article exists
    if($article) {


        // Create PHPWord object
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Add a section
        $section = $phpWord->addSection();

        // Add title
        $section->addTitle(htmlspecialchars($article['title']), 1);

        // Add content
        $section->addText(htmlspecialchars($article['content']));

        // Save the document
        $tempFilePath = tempnam(sys_get_temp_dir(), 'article_');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFilePath);

        // Send the generated Word document to the browser for download
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=article_$article_id.docx");
        readfile($tempFilePath);



        // Delete temporary file
        unlink($tempFilePath);

        exit();
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
?>
