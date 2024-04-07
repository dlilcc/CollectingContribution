<?php
session_start();

// Include necessary files
require_once 'functions.php';

// Check if the user is already logged in, redirect to index.php
// if (is_logged_in()) {
//     header('Location: index.php');
//     exit;
// }

// Initialize variables
$username = '';
$error = '';

// Fetch faculties from the database
$faculties = get_faculties();

// Process registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $selected_faculty = $_POST['faculty'];
    $email = $_POST['email'];

     // Validate form data
     if (empty($username) || empty($password) || empty($confirm_password) || empty($selected_faculty) || empty($email)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!validatePassword($password)) {
        $error = 'Password must be at least 8 characters long and contain at least one uppercase letter and one digit.';
    } else {
        // Check if username already exists
        if (username_exists($username)) {
            $error = 'Username already exists. Please choose a different username.';
        } else {
            // Register new user
            if (register_user($username, $password, $selected_faculty, $email)) {
                // Redirect to login page after successful registration
                echo 
                "<script>
                    alert('You are register successfully');
                    window.location.href = 'login.php';
                </script>";
                exit;
            } else {
                $error = 'Error registering user. Please try again later.';
            }
        }
    }
}
?>

<link rel="stylesheet" href="signup.css" />

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Magazine</title>
    <style>
        /* Add your CSS styles here */
        form {
            margin: auto;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="password"],
        select,
        button {
            display: block;
            margin-bottom: 10px;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
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
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>UNIVERSITY MAGAZINE</h2>
    <form method="post">
        <?php if (!empty($error)) : ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <select name="faculty">
            <option value="">Select Faculty</option>
            <?php foreach (get_faculties() as $faculty) : ?>
                <option value="<?php echo htmlentities($faculty['faculty_name']); ?>"><?php echo htmlentities($faculty['faculty_name']); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="email" placeholder="Email" required>
        <button type="submit">Sign Up</button>
    </form>
    <div class="login-link">
        <p>Already have an account? <a href="login.php">Log in here</a></p>
    </div>
</body>
</html>
