<?php
session_start();

// Include necessary files
require_once '../includes/config.php';
require_once '../functions.php';

// Check if user is logged in and has admin role
if (!is_logged_in() || !has_role('admin')) {
    // Redirect to login page if not logged in or not an admin
    header('Location: /CollectingContribution/login.php');
    exit;
}

// Initialize variables
$faculties = [];

// Fetch faculties from the database
$stmt = $pdo->query("SELECT * FROM Faculty ORDER BY faculty_name");
if ($stmt) {
    $faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_faculty'])) {
        // Add new faculty
        $new_faculty = $_POST['new_faculty'];
        $stmt = $pdo->prepare("INSERT INTO Faculty (faculty_name) VALUES (?)");
        $stmt->execute([$new_faculty]);
        // Redirect to avoid form resubmission
        header("Location: manage_faculty.php");
        exit;
    } elseif (isset($_POST['delete_faculty'])) {
        // Delete faculty
        $faculty_to_delete = $_POST['faculty_to_delete'];
        $stmt = $pdo->prepare("DELETE FROM Faculty WHERE faculty_name = ?");
        $stmt->execute([$faculty_to_delete]);
        // Redirect to avoid form resubmission
        header("Location: manage_faculty.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Faculties</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />
</head>
<body>
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Manage Faculties</h1>
    </div>
    <a href="../index.php" class="btn btn-outline-primary">Back</a>
    <div class="container">
        <!-- Add Faculty Form -->
        <form class="input-group mb-3" action="manage_faculty.php" method="post">
            <label class="input-group-text"  for="new_faculty">New Faculty:</label>
            <input class="input-group-text"  type="text" id="new_faculty" name="new_faculty" required>
            <button class="btn btn-outline-secondary" type="submit" name="add_faculty">Add Faculty</button>
        </form>
    
        <!-- List of Faculties -->
        <div class="d-flex justify-content-center bg-secondary mb-3">
            <h2>Existing Faculties</h2>
        </div>
        <ul class="list-group list-group-flush">
            <?php foreach ($faculties as $faculty): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo htmlspecialchars($faculty['faculty_name']); ?>
                    <form  action="manage_faculty.php" method="post" style="display:inline;">
                        <input type="hidden" name="faculty_to_delete" value="<?php echo $faculty['faculty_name']; ?>">
                        <button class="btn btn-outline-danger" type="submit" name="delete_faculty" onclick="return confirm('Are you sure you want to delete this closure date?')">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</body>
</html>
