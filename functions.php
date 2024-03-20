<?php
// Include database configuration
include('includes/config.php');

// Function to validate user credentials
function validate_user($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    } else {
        return false;
    }
}

// Function to fetch user details from the database
function get_user($username) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to retrieve list of faculties from the database
function get_faculties() {
    global $pdo;

    // Prepare and execute SQL query to fetch faculties
    $stmt = $pdo->query("SELECT faculty_name FROM faculty");
    if ($stmt) {
        // Fetch all rows as associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Handle error if query fails
        return [];
    }
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
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['count'] > 0;
}


// Function to register a new user
function register_user($username, $password, $faculty) {
    global $pdo;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'student'; // Default role for new users
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, faculty_name) VALUES (:username, :password, :role, :faculty_name)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':faculty_name', $faculty);
    return $stmt->execute();
}

// Function to check if new article submissions are disabled
function is_article_submission_disabled() {
    global $pdo;

    $current_date = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT closure_date FROM closure_dates ORDER BY closure_date DESC LIMIT 1");
    $stmt->execute();
    $closure_date = $stmt->fetchColumn();

    return ($closure_date && $current_date > $closure_date);
}

// Function to check if article updates are disabled
function is_article_update_disabled() {
    global $pdo;

    $current_date = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT final_closure_date FROM closure_dates ORDER BY closure_date DESC LIMIT 1");
    $stmt->execute();
    $final_closure_date = $stmt->fetchColumn();

    return ($final_closure_date && $current_date > $final_closure_date);
}
?>
