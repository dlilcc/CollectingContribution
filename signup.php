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
    <link rel="stylesheet" href="css/signup.css"/>

    <title>University Magazine</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />
</head>
<body>
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h2>UNIVERSITY MAGAZINE</h2>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 border rounded shadow">
                <form method="post">
                    <?php if (!empty($error)) : ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <div class="mb-3 mt-3">
                        <label for="Username" class="form-label">Enter Username:</label>
                        <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="Password" class="form-label">Enter Password:</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="Password" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="Select" class="form-label">Select faculty:</label>
                        <select name="faculty" class="form-select">
                            <option value="">Select Faculty</option>
                            <?php foreach (get_faculties() as $faculty) : ?>
                                <option value="<?php echo htmlentities($faculty['faculty_name']); ?>"><?php echo htmlentities($faculty['faculty_name']); ?></option>
                            <?php endforeach; ?>
                        </select>            
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="email" class="form-label">Enter Email:</label>
                        <input type="text" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3 mt-3 form-group text-center">
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                    <div class="login-link mb-3 mt-3 text-center">
                        <p>Already have an account? <a href="login.php">Log in here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
