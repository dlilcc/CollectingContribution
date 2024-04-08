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
            $error = 'Invalid username or password. Please try again!';
        } 
    }
}
?>
<link rel="stylesheet" href="login.css" />

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="css/login.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <title>LOGIN - UNIVERSITY MAGAZINE </title>    

</head>
<body>
    <div class= "container">
            <div class="row justify-content-around">
                <form class="container__from-login bg-light col-md-5 text-center" method="post" >
                    <h2 class="text-center container-text">Form login</h2>
                    <?php if (!empty($error)) : ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <input class="form-control container-form-input " type="text" name="username" placeholder="Username" value="<?php echo htmlentities($username); ?>" required>
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                    <button class="btn btn-primary" type="submit">Login</button>
                    <div class="form__link">
                        <div class="signup-link">
                            <p>Don't have an account? <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="signup.php">Sign up here</a></p>
                        </div>
                        <div class="guest-login">
                            <p>Continue as a guest? <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="guestLogin.php">Guest Login</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</body>
</html>


