<?php
session_start();

// Include necessary files
require_once 'functions.php';

// Check if the user is already logged in, redirect to index.php
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

// Initialize variables
$username = '';
$password = '';
$error = '';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve username and password from form submission
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate username and password
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Attempt to authenticate user
        $user = validate_user($username, $password);
        if ($user) {
            // Store user data in session
            $_SESSION['user'] = $user;

            // Redirect to index.php
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        } 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University Magazine</title>
    <style>
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
        .signup-link {
            text-align: center;
            margin-top: 10px;
        }
        .guest-login {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Login - University Magazine</h2>
    <form method="post">
        <?php if (!empty($error)) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlentities($username); ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <div class="signup-link">
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>
    <div class="guest-login">
        <p>Continue as a guest? <a href="guestLogin.php">Guest Login</a></p>
    </div>
</body>
</html>


