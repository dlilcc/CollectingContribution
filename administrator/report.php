<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <!-- Include Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
    crossorigin="anonymous" />
</head>
<body>
    <div class="d-flex justify-content-around bg-secondary mb-3">
        <h1>Manage Report</h1>
    </div>

    <a href="../index.php" class="btn btn-outline-primary">Back</a>

    <?php
    session_start();

    // Include necessary files
    require_once __DIR__ . '/../includes/config.php';
    require_once __DIR__ . '/../functions.php';

    $user_id = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT faculty_name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_faculty = $stmt->fetch(PDO::FETCH_ASSOC);


    // Initialize $reportData variable
    $reportData = [];

    // Process form submission to generate report
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
        // Get the selected report type
        $reportType = $_POST['report_type'];
        // Get the selected year
        $selectedYear = $_POST['year'];
        // Generate the report based on the selected report type
        $reportData = generateContributionsReport($reportType, $selectedYear,  $user_faculty);
    }
    ?>

    <!-- Display report data and chart if $reportData is not empty -->
    <?php if (!empty($reportData)) : ?>
        <div class="container">
            <div class="d-flex justify-content-around bg-secondary mb-3">
                <h2>Report</h2>
            </div>    
            <div id="chart_div"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Faculty/User');
                    
                    <?php if ($reportType == 'contributions_per_faculty' || $reportType == 'contributions_per_user' || $reportType == 'percentage_contributions_per_faculty') : ?>
                        data.addColumn('number', 'Number of Contributions');
                    <?php endif ?>

                    <?php if ($reportType == 'contributors_per_faculty_per_year') : ?>
                        data.addColumn('number', 'Number of Contributors');
                    <?php endif ?>

                    <?php if ($reportType == 'contributions_without_comment' || $reportType == 'contributions_without_comment_admin') : ?>
                        data.addColumn('number', 'Contribution without comment');
                    <?php endif ?>

                    <?php if ($reportType == 'contributions_without_comment_14_days_admin' || $reportType == 'contributions_without_comment_14_days') : ?>
                        data.addColumn('number', 'Contribution without comment 14 days');
                    <?php endif ?>
                    
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
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <p>No data available for the selected report.</p>
    <?php endif; ?>
    <div class="container">
        <form class="form-control" method="post">
            <!-- Form to select report type and generate report -->
            <div class="input-group mt-3 mb-3">
                <label class="input-group-text" for="report_type">Select Report Type:</label>
                <select class="form-select" name="report_type" id="report_type">
                    <option value="contributions_per_faculty">Number of Contributions per Faculty</option>
                    <option value="contributions_per_user">Number of Contributions per User</option>
                    <option value="contributors_per_faculty_per_year">Number of Contributors per Faculty</option>
                    <option value="percentage_contributions_per_faculty">Percentage of Contributions</option>
                    
                    <?php if (has_role('admin') || has_role('mananger')) : ?>
                        <option value="contributions_without_comment_admin">Contributions without Comment</option>
                    <?php else : ?>
                        <option value="contributions_without_comment">Contributions without Comment</option>
                    <?php endif ?>
                    
                    <?php if (has_role('admin') || has_role('mananger')) : ?>
                        <option value="contributions_without_comment_14_days_admin"> Contributions without a comment after 14 days</option>
                    <?php else : ?>
                        <option value="contributions_without_comment_14_days"> Contributions without a comment after 14 days</option>
                    <?php endif ?>
                </select>
            </div>
            <div class="input-group mt-3 mb-3">
                <!-- Add a dropdown menu to select the year -->
                <label class="input-group-text" for="year">Select Year:</label>
                <select class="form-select" name="year" id="year">
                    <?php
                    // Generate options for the past few years
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                        echo "<option value=\"$year\">$year</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- Button to submit the form -->
            <button class="btn btn-outline-primary" type="submit" name="generate_report">Generate Report</button>
            <!-- Link to go back to the index page -->
        </form>
    </div>
</body>
</html>
