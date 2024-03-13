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
</head>
<body>
    <h1>Manage Faculties</h1>

    <!-- Add Faculty Form -->
    <form action="manage_faculty.php" method="post">
        <label for="new_faculty">New Faculty:</label>
        <input type="text" id="new_faculty" name="new_faculty" required>
        <button type="submit" name="add_faculty">Add Faculty</button>
    </form>

    <!-- List of Faculties -->
    <h2>Existing Faculties</h2>
    <ul>
        <?php foreach ($faculties as $faculty): ?>
            <li><?php echo htmlspecialchars($faculty['faculty_name']); ?>
                <form action="manage_faculty.php" method="post" style="display:inline;">
                    <input type="hidden" name="faculty_to_delete" value="<?php echo $faculty['faculty_name']; ?>">
                    <button type="submit" name="delete_faculty" onclick="return confirm('Are you sure you want to delete this closure date?')">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="../index.php" class="logout">Back</a>
</body>
</html>
