<?php
// Include database configuration
include('includes/config.php');

// Function to validate user credentials
function validate_user($username, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    } else {
        return false;
    }
}

// Function to fetch user details from the database
function get_user($username) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to check if a user is logged in
function is_logged_in() {
    return isset($_SESSION['user']);
}

// Function to check if a user has a specific role
function has_role($role) {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] == $role;
}

// Function to check if a username already exists in the database
function username_exists($username) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'] > 0;
}


// Function to register a new user
function register_user($username, $password) {
    global $conn;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'student'; // Default role for new users
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);
    return $stmt->execute();
}
?>
