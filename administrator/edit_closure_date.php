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
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />
</head>
<body>
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Edit Closure Date</h1>
    </div>
    <a href="../administrator/manage_closure_dates.php" class="btn btn-outline-primary">Back</a>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
    <!-- Form to edit closure date -->
                <form class="border rounded p-3 shadow" action="" method="post">
                    <div class="row mb-3">
                        <label for="academicYear" class="col col-xl-4 col-form-label text-start custom-label">Academic Year:</label>
                        <div class="col col-xl-8">
                            <input class="form-control" type="text" id="academicYear" name="academicYear" value="<?php echo htmlspecialchars($closureDate['academic_year']); ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="closureDate" class="col col-xl-4 col-form-label text-start custom-label">Closure Date:</label>
                        <div class="col col-xl-8">
                            <input class="form-control" type="date" id="closureDate" name="closureDate" value="<?php echo htmlspecialchars($closureDate['closure_date']); ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="finalClosureDate" class="col col-xl-4 col-form-label text-start custom-label">Final Closure Date:</label>
                        <div class="col col-xl-8">
                            <input class="form-control" type="date" id="finalClosureDate" name="finalClosureDate" value="<?php echo htmlspecialchars($closureDate['final_closure_date']); ?>" required>
                        </div>
                    </div>
                    <div class="center">
                        <button class="btn btn-secondary" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
