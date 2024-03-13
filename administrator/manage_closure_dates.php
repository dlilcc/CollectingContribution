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
    <style>
        /* Add your CSS styles here */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="date"] {
            width: 200px;
        }
    </style>
</head>
<body>
    <h1>Manage Closure Dates</h1>

    <!-- Form to add new closure date -->
    <form action="add_closure_date.php" method="post">
        <label for="academicYear">Academic Year:</label>
        <input type="text" id="academicYear" name="academicYear" required>
        <label for="closureDate">Closure Date:</label>
        <input type="date" id="closureDate" name="closureDate" required>
        <button type="submit">Add Closure Date</button>
    </form>

    <!-- Table to display existing closure dates -->
    <table>
        <thead>
            <tr>
                <th>Academic Year</th>
                <th>Closure Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($closureDates as $closureDate): ?>
                <tr>
                    <td><?php echo $closureDate['academic_year']; ?></td>
                    <td><?php echo $closureDate['closure_date']; ?></td>
                    <td>
                        <a href="edit_closure_date.php?id=<?php echo $closureDate['id']; ?>">Edit</a>
                        <a href="delete_closure_date.php?id=<?php echo $closureDate['id']; ?>&confirmed=true" onclick="return confirm('Are you sure you want to delete this closure date?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
