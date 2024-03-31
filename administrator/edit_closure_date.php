<?php
session_start();

// Include database configuration and functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if user is logged in and has admin role
if (!is_logged_in() || !has_role('admin')) {
    // Redirect to login page if not logged in or not an admin
    header('Location: /CollectingContribution/login.php');
    exit;
}

// Check if closure date ID is provided
if (!isset($_GET['id'])) {
    // Redirect to manage closure dates page if closure date ID is not provided
    header('Location: manage_closure_dates.php');
    exit;
}

// Sanitize the closure date ID
$closureDateId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// Check if closure date with provided ID exists
$stmt = $pdo->prepare("SELECT * FROM closure_dates WHERE id = :closureDateId");
$stmt->bindParam(':closureDateId', $closureDateId);
$stmt->execute();
$closureDate = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if closure date exists
if (!$closureDate) {
    // Redirect to manage closure dates page if closure date does not exist
    header('Location: manage_closure_dates.php');
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $academicYear = $_POST['academicYear'];
    $closureDate = $_POST['closureDate'];
    $finalClosureDate = $_POST['finalClosureDate'];

    // Update closure date in the database
    $stmt = $pdo->prepare("UPDATE closure_dates SET academic_year = :academicYear, closure_date = :closureDate, final_closure_date = :finalClosureDate WHERE id = :closureDateId");
    $stmt->bindParam(':academicYear', $academicYear);
    $stmt->bindParam(':closureDate', $closureDate);
    $stmt->bindParam(':closureDateId', $closureDateId);
    $stmt->bindParam(':finalClosureDate', $finalClosureDate);
    $stmt->execute();

    // Redirect to manage closure dates page
    header('Location: manage_closure_dates.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Closure Date</title>
    <style>
        /* Add your CSS styles here */
        form {
            margin-top: 20px;
        }
        input[type="text"], input[type="date"] {
            width: 200px;
        }
    </style>
</head>
<body>
    <h1>Edit Closure Date</h1>

    <!-- Form to edit closure date -->
    <form action="" method="post">
        <label for="academicYear">Academic Year:</label>
        <input type="text" id="academicYear" name="academicYear" value="<?php echo htmlspecialchars($closureDate['academic_year']); ?>" required>
        
        <label for="closureDate">Closure Date:</label>
        <input type="date" id="closureDate" name="closureDate" value="<?php echo htmlspecialchars($closureDate['closure_date']); ?>" required>
        
        <label for="finalClosureDate">Final Closure Date:</label>
        <input type="date" id="finalClosureDate" name="finalClosureDate" value="<?php echo htmlspecialchars($closureDate['final_closure_date']); ?>" required>
        
        <button type="submit">Save Changes</button>
    </form>
    <a href="../index.php" class="back">Back</a>
</body>
</html>
