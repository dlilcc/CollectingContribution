<?php
// Include necessary files and configurations
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../functions.php';



// Process form submission to generate report
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    // Get the selected report type
    $reportType = $_POST['report_type'];
    // Get the selected year
    $selectedYear = $_POST['year'];
    // Generate the report based on the selected report type
    $reportData = generateContributionsReport($reportType, $selectedYear);

    // Display the report
    if (!empty($reportData)) {
        // Determine the report title based on the selected report type
        if ($reportType == 'percentage_contributions_per_faculty') {
            $reportTitle = "Percentage of Contributions by each Faculty for Academic Year $selectedYear";
        } elseif ($reportType == 'contributors_per_faculty') {
            $reportTitle = "Number of Contributors within each Faculty for Academic Year $selectedYear";
        } else {
            $reportTitle = ($reportType == 'contributions_per_faculty') ? "Number of Contributions per Faculty" : "Number of Contributions per User";
        }
        // Display the report title
        echo "<h2>{$reportTitle}</h2>";
        // Display the report table
        echo "<table>";
        // Display table headers based on the report type
        if ($reportType == 'percentage_contributions_per_faculty') {
            echo "<tr><th>Faculty</th><th>Number of Contributions</th><th>Contribution Percentage (%)</th></tr>";
        } elseif ($reportType == 'contributors_per_faculty') {
            echo "<tr><th>Faculty</th><th>Number of Contributors</th></tr>";
        } else {
            echo "<tr><th>Faculty/User</th><th>Number of Contributions</th></tr>";
        }
        // Loop through the report data and display each row in the table
        foreach ($reportData as $row) {
            // Determine the key for faculty/user based on the report type
            $key = ($reportType == 'contributions_per_faculty' || $reportType == 'percentage_contributions_per_faculty') ? 'faculty_name' : 'user_id';
            // Display each row of the report data
            echo "<tr><td>{$row[$key]}</td><td>{$row['num_contributions']}</td>";
            // If it's a percentage contributions report, display the percentage column
            if ($reportType == 'percentage_contributions_per_faculty') {
                echo "<td>{$row['contribution_percentage']}</td>";
            }
            // Close the table row
            echo "</tr>";
        }
        // Close the table
        echo "</table>";
    } else {
        // If no data available for the report, display a message
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
        <option value="percentage_contributions_per_faculty">Percentage of Contributions by each Faculty for any academic year</option>
        <option value="contributors_per_faculty">Number of Contributors within each Faculty for each academic year</option>
    </select>
    <!-- Add a dropdown menu to select the year -->
    <label for="year">Select Year:</label>
    <select name="year" id="year">
        <?php
        // Generate options for the past few years
        $currentYear = date("Y");
        for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
            echo "<option value=\"$year\">$year</option>";
        }
        ?>
    </select>
    <!-- Button to submit the form -->
    <button type="submit" name="generate_report">Generate Report</button>
    <!-- Link to go back to the index page -->
    <a href="../index.php" class="back">Back</a>
</form>
