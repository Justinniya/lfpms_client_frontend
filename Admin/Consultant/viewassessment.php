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
                                            <a style="margin-left:5px; margin-top: -35px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview of Assessments</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content tab-content-basic">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                        <div class="row">
                                            <div class="col-lg-12 grid-margin stretch-card">
                                                <div class="card">                                                  
                                                    <div class="card-body">
                                                    <h4>View Assessment</h4>
                                                    <hr>
                                                        <table id="assessment_monitoring" class="table table-border">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Product Name</th>
                                                                    <th>Brand Name</th>
                                                                    <th>Product Identity</th>
                                                                    <th>Submittion Date</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                // Perform the SQL query to calculate counts and total rating value
                                                                $query = "SELECT * 
                                                                FROM consultancyquestionnaire AS Con 
                                                                INNER JOIN users ON Con.user_id = users.userid 
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
                                                                        echo "<td>{$row['SubmittionDate']}</td>";
                                                                        echo '<td><a class="btn btn-primary btn-sm text-white" href="ViewAssess.php?id=' . $row['id'] . '">View Assessment</a></td>';
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
    <?php include 'script.php'; ?>

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
</body>

</html>