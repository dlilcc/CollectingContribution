<?php
session_start();

// Include necessary files
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';

// Check if user is logged in and has admin role
if (!is_logged_in() || !has_role('admin')) {
    // Redirect to login page if not logged in or not an admin
    header('Location: /CollectingContribution/login.php');
    exit;
}

// Initialize variables
$users = [];

// Fetch users from the database
$stmt = $pdo->query("SELECT id, username, role, faculty_name FROM users");
if ($stmt) {
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the password update form is submitted
    if (isset($_POST['update_password'])) {
        $user_id = $_POST['user_id'];
        $new_password = $_POST['new_password'];
        
        // Update the password in the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $user_id]);
        // Redirect back to the same page after updating password
        header('Location: manage_user.php');
        exit;
    }
    
    // Check if the role/faculty update form is submitted
    if (isset($_POST['update_role_faculty'])) {
        $user_id = $_POST['user_id'];
        $new_role = $_POST['new_role'];
        $new_faculty = $_POST['new_faculty'];
        
        // Update the role and faculty in the database
        $stmt = $pdo->prepare("UPDATE users SET role = ?, faculty_name = ? WHERE id = ?");
        $stmt->execute([$new_role, $new_faculty, $user_id]);
        // Redirect back to the same page after updating role/faculty
        header('Location: manage_user.php');
        exit;
    }
}
?>

<link rel="stylesheet" href="manage_user.css" />

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        input[type="text"],
        input[type="password"],
        select,
        button {
            display: block;
            margin-bottom: 10px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Manage Users</h1>
    <a href="../signup.php" class="create">Create account</a>      

    <!-- Table to display existing users -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Faculty</th>
                <th>Password</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td><?php echo $user['faculty_name']; ?></td>
                    <td>
                        <!-- Form to update password -->
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="password" name="new_password" placeholder="New Password" required>
                            <button type="submit" name="update_password">Update</button>
                        </form>
                    </td>
                    <td>
                        <!-- Form to update role and faculty -->
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <select name="new_role" required>
                                <option value=""></option>
                                <option value="admin">Admin</option>
                                <option value="coordinator">Coordinator</option>
                                <option value="student">Student</option>
                                <option value="student">Manager</option>
                            </select>
                            <select name="new_faculty" required>
                                <!-- Fetch faculties from database and populate options -->
                                <?php
                                    $faculties = get_faculties();
                                    foreach ($faculties as $faculty) {
                                        echo "<option value='{$faculty['faculty_name']}'>{$faculty['faculty_name']}</option>";
                                    }
                                ?>
                            </select>
                            <button type="submit" name="update_role_faculty">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../index.php" class="back">Back</a>                                
</body>
</html>
