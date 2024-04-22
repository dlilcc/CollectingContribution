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
function register_user($username, $password, $faculty, $email) {
    global $pdo;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'student'; // Default role for new users
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, faculty_name, email) VALUES (:username, :password, :role, :faculty_name, :email)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':faculty_name', $faculty);
    $stmt->bindParam(':email', $email);
    return $stmt->execute();
}

function validatePassword($password) {
    // Check if password is at least 8 characters long
    if (strlen($password) < 8) {
        return false;
    }

    // Check if password contains at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }

    // Check if password contains at least one digit
    if (!preg_match('/\d/', $password)) {
        return false;
    }

    // All conditions passed, password is valid
    return true;
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

// Function to generate report: Number of contributions per faculty or user
function generateContributionsReport($reportType, $selectedYear, $user_faculty) {
    global $pdo;
    global $chartValue;
    global $chartType;
    
    // Define the SQL query based on the report type
    switch ($reportType) {
        case 'contributions_per_faculty':
            // SQL query to count contributions per faculty for the selected year
            $sql = "SELECT faculty_name, COUNT(*) AS num_contributions FROM articles WHERE YEAR(submission_date) = :year GROUP BY faculty_name";
            $chartValue = 'faculty_name';
            $chartType = 'column';

            // Prepare the query
            $stmt = $pdo->prepare($sql);
            // Bind the year parameter
            $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
            // Execute the query
            $stmt->execute();
            break;

        case 'contributions_per_user':
            // SQL query to count contributions per user for the selected year
            $sql = "SELECT user_id, COUNT(*) AS num_contributions FROM articles WHERE YEAR(submission_date) = :year GROUP BY user_id";
            $chartValue = 'user_id';
            $chartType = 'column';

            // Prepare the query
            $stmt = $pdo->prepare($sql);
            // Bind the year parameter
            $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
            // Execute the query
            $stmt->execute();
            break;

        case 'contributors_per_faculty_per_year':
            $sql = "SELECT faculty_name, YEAR(submission_date) = :year, COUNT(DISTINCT user_id) AS num_contributions FROM articles  GROUP BY faculty_name";
            $chartValue = 'faculty_name';
            $chartType = 'column';

            // Prepare the query
            $stmt = $pdo->prepare($sql);
            // Bind the year parameter
            $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
            // Execute the query
            $stmt->execute();
            break;

        case 'percentage_contributions_per_faculty':
            // SQL query to calculate the percentage of contributions by each faculty for the selected year
            $sql = "SELECT faculty_name, COUNT(*) AS num_contributions, ROUND((COUNT(*) / (SELECT COUNT(*) FROM articles WHERE YEAR(submission_date) = :year)) * 100, 2) AS contribution_percentage FROM articles WHERE YEAR(submission_date) = :year GROUP BY faculty_name";
            $chartValue = 'faculty_name';
            $chartType = 'pie';

            // Prepare the query
            $stmt = $pdo->prepare($sql);
            // Bind the year parameter
            $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
            // Execute the query
            $stmt->execute();
            break;

        case 'contributions_without_comment':
            // Prepare the query
            $stmt = $pdo->prepare("SELECT user_id, COUNT(*) AS num_contributions FROM articles WHERE YEAR(submission_date) = :year AND comment = 'no' AND faculty_name = :faculty GROUP BY user_id");
            $chartValue = 'user_id';
            $chartType = 'column';

            // Bind the year parameter
            $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
            $stmt->bindValue(':faculty', $user_faculty['faculty_name'], PDO::PARAM_STR);
            // Execute the query
            $stmt->execute();
            break;

        case 'contributions_without_comment_admin':
            // Prepare the query
            $stmt = $pdo->prepare("SELECT faculty_name, COUNT(*) AS num_contributions FROM articles WHERE YEAR(submission_date) = :year AND comment = 'no' GROUP BY faculty_name");
            $chartValue = 'faculty_name';
            $chartType = 'column';

            // Bind the year parameter
            $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
            // Execute the query
            $stmt->execute();
            break;

        case 'contributions_without_comment_14_days_admin':
            $stmt = $pdo->prepare("SELECT faculty_name, COUNT(*) AS num_contributions 
            FROM articles 
            WHERE YEAR(submission_date) = :year AND comment = 'no' 
            AND submission_date <= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
            GROUP BY faculty_name");
            $chartValue = 'faculty_name';
            $chartType = 'column';

            $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
            // Execute the query
            $stmt->execute();
            break;

        case 'contributions_without_comment_14_days':
            $stmt = $pdo->prepare("SELECT user_id, COUNT(*) AS num_contributions 
            FROM articles 
            WHERE YEAR(submission_date) = :year AND comment = 'no'  AND faculty_name = :faculty
            AND submission_date <= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
            GROUP BY user_id");
            $chartValue = 'user_id';
            $chartType = 'column';

            $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
            $stmt->bindValue(':faculty', $user_faculty['faculty_name'], PDO::PARAM_STR);
            // Execute the query
            $stmt->execute();
            break;

        default:
            // Return an empty array for unknown report types
            return [];
    }
    
    
        // // Prepare the query
        // $stmt = $pdo->prepare($sql);
        // // Bind the year parameter
        // $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
        // // Execute the query
        // $stmt->execute();
        // Fetch the results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Handle database error
        
        // Return an empty array in case of error
        
    
}

function checkComment($id) {
    global $pdo;

    $currentDate = new DateTime(); // Current date
    $currentDate->modify('-14 days');
    $currentDate = $currentDate->format('Y-m-d H:i:s');
    $stmt = $pdo->prepare("SELECT comment, submission_date FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    $submissionDate = $article['submission_date']; // Submission date

    if (($currentDate > $submissionDate) && $article['comment'] == 'no') {
        return false;
    } else {
        return true;
    }
}

function sanitize_filename($filename) {
    return preg_replace('/[^A-Za-z0-9\-]/', '_', $filename);
}

function delete_directory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}
?>
