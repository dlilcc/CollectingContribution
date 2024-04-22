    <?php
    session_start();

    // Include database configuration and functions
    require_once __DIR__ . '/../includes/config.php';
    require_once __DIR__ . '/../functions.php';

    // Check if user is logged in and has admin role
    if (!is_logged_in() || !has_role('admin')) {
        // Redirect to login page if not logged in or not an admin
        header('Location: /CollectingContribution/login.php');
        exit;
    }

    // Initialize variables
    $closureDates = [];

    // Fetch closure dates from the database
    $stmt = $pdo->query("SELECT * FROM closure_dates ORDER BY academic_year DESC");
    if ($stmt) {
        $closureDates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Closure Dates</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
        integrity="sha512-V8h7XWvMdGYJQGch1r9ctb6IK8G0AK4gJVd1CCLldAYXHX2RyM+qsy7HmqbI5HqK8Ll4H8enYXd9T1z7lAHxvA==" 
        crossorigin="anonymous" />

        <<style>
        .custom-table {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .custom-table td {
        text-align: center;
        vertical-align: middle;
        }
        .thead-dark{        
        text-align: center;
        vertical-align: middle;
        }
        .My-Padding{
            padding-top:2%
        }
        .center{
            text-align:center;
            padding-top:0.5%;
        }
        .custom-label {
            display: inline-block;
            width: auto;
        }
    </style>


        
    </head>
    <body>
        <div class="d-flex justify-content-around bg-secondary mb-3">
            <h1>Manage Closure Dates</h1>
        </div>

        <div class="container">
            <div class=" justify-content-around bg-secondary mb-3">  
                <a href="../index.php" class="btn btn-primary p-2 bg-primary">Back</a>             
            </div>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-md-5"> 
                    <!--  -->
                    <form class="border rounded p-3 shadow" action="add_closure_date.php" method="post">
                        <div class="row mb-3">
                            <label for="academicYear" class="col col-xl-4 col-form-label text-start custom-label">Academic Year:</label>
                            <div class="col col-xl-8">
                                <input class="form-control" type="text" id="academicYear" name="academicYear" placeholder="Year" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="closureDate" class="col col-xl-4 col-form-label text-start custom-label">Closure Date:</label>
                            <div class="col col-xl-8">
                                <input class="form-control" type="date" id="closureDate" name="closureDate" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="finalClosureDate" class="col col-xl-4 col-form-label text-start custom-label">Final Closure Date:</label>
                            <div class="col col-xl-8">
                                <input class="form-control" type="date" id="finalClosureDate" name="finalClosureDate" required>
                            </div>
                        </div>
                        <div class="center">
                            <button class="btn btn-secondary" type="submit">Add Closure Date</button>
                        </div>
                    </form>
                </div>
                <!--  -->
                <div class="col-md-7">
                    <table class="table custom-table table-bordered table-shadow border rounded" >
                        <thead class="thead-dark">
                            <tr>
                                <th>Academic Year</th>
                                <th>Closure Date</th>
                                <th>Final Closure Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($closureDates as $closureDate): ?>
                                <tr>
                                    <td><?php echo $closureDate['academic_year']; ?></td>
                                    <td><?php echo $closureDate['closure_date']; ?></td>
                                    <td><?php echo $closureDate['final_closure_date']; ?></td>
                                    <td>
                                        <a class="btn btn-outline-warning" href="edit_closure_date.php?id=<?php echo $closureDate['id']; ?>">Edit</a>
                                        <a class="btn btn-outline-danger" href="delete_closure_date.php?id=<?php echo $closureDate['id']; ?>&confirmed=true" 
                                            onclick="return confirm('Are you sure you want to delete this closure date?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Link Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>
