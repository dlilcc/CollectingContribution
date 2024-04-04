<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <!-- Include Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <?php
    // Include necessary files
    require_once __DIR__ . '/../includes/config.php';
    require_once __DIR__ . '/../functions.php';

    

    // Initialize $reportData variable
    $reportData = [];

    // Process form submission to generate report
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
        // Get the selected report type
        $reportType = $_POST['report_type'];
        // Get the selected year
        $selectedYear = $_POST['year'];
        // Generate the report based on the selected report type
        $reportData = generateContributionsReport($reportType, $selectedYear);
    }
    ?>

    <!-- Display report data and chart if $reportData is not empty -->
    <?php if (!empty($reportData)) : ?>
        <h2>Report</h2>
        <div id="chart_div"></div>
        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Faculty/User');
                data.addColumn('number', 'Number of Contributions');
                <?php foreach ($reportData as $row) : ?>
                    data.addRow(['<?php echo $row[$chartValue]; ?>', <?php echo $row['num_contributions']; ?>]);
                <?php endforeach; ?>

                var options = {
                    title: 'Report Chart',
                    width: '100%',
                    height: 400
                };

                
                <?php if ($chartType == 'pie') : ?>
                    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                <?php endif; ?>
                <?php if ($chartType == 'column') : ?>
                    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                <?php endif; ?>
            }
        </script>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <p>No data available for the selected report.</p>
    <?php endif; ?>

    <!-- Form to select report type and generate report -->
    <form method="post">
        <label for="report_type">Select Report Type:</label>
        <select name="report_type" id="report_type">
            <option value="contributions_per_faculty">Number of Contributions per Faculty</option>
            <option value="contributions_per_user">Number of Contributions per User</option>
            <option value="percentage_contributions_per_faculty">Percentage of Contributions</option>
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
</body>
</html>
