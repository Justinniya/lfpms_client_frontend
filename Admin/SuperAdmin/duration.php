<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location: ../../index.php");
    exit();
}
include '../../session.php';

function getLoggedInUser($conn, $user_id)
{
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>LFPMS</title>
    <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/bb.png" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body class="with-welcome-text">
    <div class="container-scroller">
        <?php include 'importantinclude/topbar.php'; ?>
        <div class="container-fluid page-body-wrapper">
            <?php include 'importantinclude/sidebar.php'; ?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="home-tab">
                                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a style="margin-left:5px; margin-top: -35px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Product Development Duration</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content tab-content-basic">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                        <div class="row">
                                            <div class="col-lg-12 grid-margin stretch-card">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3>Finished Product Development</h3>
                                                        <hr>
                                                        <table id="assessment_monitoring" class="table table-border">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Product Name</th>
                                                                    <th>Brand Name</th>
                                                                    <th>Product Identity</th>
                                                                    <th>Submittion Date</th>
                                                                    <th>End Date</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                // Perform the SQL query to calculate counts and total rating value
                                                                $query = "SELECT * 
                                                                FROM consultancyquestionnaire AS Con 
                                                                INNER JOIN users ON Con.user_id = users.userid 
                                                                WHERE Con.status = 7
                                                                ORDER BY Con.SubmittionDate DESC";

                                                                // Execute the query and fetch data
                                                                $result = $conn->query($query);
                                                                if ($result && $result->rowCount() > 0) {
                                                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                                        echo "<tr>";
                                                                        echo "<td>{$row['fname']} {$row['Lname']} </td>";
                                                                        echo "<td>{$row['ProductName']}</td>";
                                                                        echo "<td>{$row['brandName']}</td>";
                                                                        echo "<td>{$row['productIdentity']}</td>";
                                                                        echo "<td>{$row['datecreated']}</td>";
                                                                        echo "<td>{$row['dateend']}</td>";
                                                                        echo '<td><a class="btn btn-primary btn-sm text-white" href="finishedassess.php?id=' . $row['id'] . '">View Assessment</a></td>';
                                                                        echo "</tr>";
                                                                    }
                                                                } else {
                                                                    echo "<tr><td colspan='6'>No feedback found</td></tr>";
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include 'importantinclude/footer.php'; ?>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('#assessment_monitoring').DataTable({
                responsive: true,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                language: {
                    search: "🔍 Search:",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: "←",
                        next: "→"
                    }
                },
                drawCallback: function() {
                    $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').css("margin-bottom", "15px");
                }
            });
        });
    </script>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../assets/vendors/progressbar.js/progressbar.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/template.js"></script>
    <script src="../assets/js/settings.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../assets/js/dashboard.js"></script>
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
    <!-- Include DataTables CSS & jQuery (Required) -->
    <!-- jQuery (Required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</body>

</html>