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

// Initialize variables
$closureDates = [];

// Fetch closure dates from the database
$stmt = $pdo->query("SELECT * FROM closure_dates ORDER BY academic_year DESC");
if ($stmt) {
    $closureDates = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Closure Dates</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />
</head>
<body>
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Manage Closure Dates</h1>
    </div>
    <a href="../index.php" class="btn btn-primary">Back</a>

    <!-- Form to add new closure date -->
    <form action="add_closure_date.php" method="post">
        <label for="academicYear">Academic Year:</label>
        <input type="text" id="academicYear" name="academicYear" required>
        <label for="closureDate">Closure Date:</label>
        <input type="date" id="closureDate" name="closureDate" required>
        <label for="finalClosureDate">Final Closure Date:</label>
        <input type="date" id="finalClosureDate" name="finalClosureDate" required>
        <button type="submit">Add Closure Date</button>
    </form>
    <!-- Table to display existing closure dates -->
    <div class="container">
        <table class="table-bordered">
            <thead>
                <tr>
                    <th>Academic Year</th>
                    <th>Closure Date</th>
                    <th>Final Closure Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($closureDates as $closureDate): ?>
                    <tr>
                        <td><?php echo $closureDate['academic_year']; ?></td>
                        <td><?php echo $closureDate['closure_date']; ?></td>
                        <td><?php echo $closureDate['final_closure_date']; ?></td>
                        <td>
                            <a class="btn btn-outline-warning" href="edit_closure_date.php?id=<?php echo $closureDate['id']; ?>">Edit</a>
                            <a class="btn btn-outline-danger" href="delete_closure_date.php?id=<?php echo $closureDate['id']; ?>&confirmed=true" onclick="return confirm('Are you sure you want to delete this closure date?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
