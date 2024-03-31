<?php
// Include database connection
require_once __DIR__ . '/../includes/config.php';

// Function to generate report: Number of contributions per faculty or user
function generateContributionsReport($reportType, $selectedYear) {
    global $pdo;
    
    // Define the SQL query based on the report type
    switch ($reportType) {
        case 'contributions_per_faculty':
            $sql = "SELECT faculty_name, COUNT(*) AS num_contributions FROM articles WHERE YEAR(submission_date) = :year GROUP BY faculty_name";
            break;
        case 'contributions_per_user':
            $sql = "SELECT user_id, COUNT(*) AS num_contributions FROM articles WHERE YEAR(submission_date) = :year GROUP BY user_id";
            break;
        default:
            return [];
    }
    
    try {
        // Prepare the query
        $stmt = $pdo->prepare($sql);
        // Bind the year parameter
        $stmt->bindValue(':year', $selectedYear, PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Fetch the results as an associative array
        $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $reportData;
    } catch (PDOException $e) {
        // Handle database error
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Example usage:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    // Check if the "Generate Report" form is submitted

    // Get the selected report type
    $reportType = $_POST['report_type'];

    // Get the selected year
    $selectedYear = $_POST['year'];

    // Generate the report based on the selected report type
    $reportData = generateContributionsReport($reportType, $selectedYear);

    // Display the report
    if (!empty($reportData)) {
        $reportTitle = ($reportType == 'contributions_per_faculty') ? "Number of Contributions per Faculty" : "Number of Contributions per User";
        echo "<h2>{$reportTitle}</h2>";
        echo "<table>";
        echo "<tr><th>Faculty/User</th><th>Number of Contributions</th></tr>";
        foreach ($reportData as $row) {
            // Use 'user_id' key for user report
            $key = ($reportType == 'contributions_per_faculty') ? 'faculty_name' : 'user_id';
            echo "<tr><td>{$row[$key]}</td><td>{$row['num_contributions']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No data available for the report.</p>";
    }
}
?>

<!-- Form to select report type and generate report -->
<form method="post">
    <label for="report_type">Select Report Type:</label>
    <select name="report_type" id="report_type">
        <option value="contributions_per_faculty">Number of Contributions per Faculty</option>
        <option value="contributions_per_user">Number of Contributions per User</option>
        <!-- Add more report options here if needed -->
    </select>
    <!-- Add a dropdown menu to select the year -->
    <label for="year">Select Year:</label>
    <select name="year" id="year">
        <?php
        // Get the current year
        $currentYear = date("Y");
        // Generate options for the past few years
        for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
            echo "<option value=\"$year\">$year</option>";
        }
        ?>
    </select>
    <button type="submit" name="generate_report">Generate Report</button>
</form>
