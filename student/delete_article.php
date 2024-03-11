<?php
// Include database configuration
require_once __DIR__ . '/../includes/config.php';

// Check if article ID is provided
if(isset($_GET['id'])) {
    // Sanitize the article ID
    $article_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if confirmation flag is set
    if(isset($_GET['confirmed']) && $_GET['confirmed'] === 'true') {
        // Display an alert indicating the deletion process
        echo "<script>alert('Deleting article...');</script>";

        // Prepare and execute SQL DELETE statement
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);

        // Check if article was deleted successfully
        if($stmt->rowCount() > 0) {
            // Article deleted successfully, redirect to manage_article.php or any other page
            header("Location: manage_article.php");
            exit();
        } else {
            // Article not found or deletion failed, handle the error (e.g., display an error message)
            echo "<script>alert('Error: Article not found or deletion failed.');</script>";
            // You may choose to redirect or handle the error differently here
        }
    } else {
        // Confirmation flag not set, display confirmation dialog
        echo "<script>
            var confirmDelete = confirm('Are you sure you want to delete this article?');
            if(confirmDelete) {
                window.location.href = 'delete_article.php?id=<?php echo $article_id; ?>&confirmed=true';
            } else {
                alert('Deletion canceled.');
                window.history.back(); // Go back to the previous page
            }
        </script>";
    }
} else {
    // Article ID not provided, handle the error (display an error message)
    echo "<script>alert('Error: Article ID not provided.');</script>";
    // You may choose to redirect or handle the error differently here
}
?>
