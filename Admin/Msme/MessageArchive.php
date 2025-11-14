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

<?php
$Id = $loggedInUser['userid']; // Current logged-in user's ID

$stmt = $conn->prepare("
                      SELECT r.id, r.name, r.created_at, 
                            GROUP_CONCAT(u.username SEPARATOR ', ') AS members 
                      FROM room r
                      INNER JOIN chatmember rm ON r.id = rm.room_id
                      INNER JOIN users u ON rm.user_id = u.userid
                      WHERE r.status = 1 
                        AND rm.user_id = :current_user
                      GROUP BY r.id
                      ORDER BY r.id ASC
                  ");

$stmt->bindParam(':current_user', $Id, PDO::PARAM_INT);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="../assets/js/select.dataTables.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="shortcut icon" href="assets/img/bb.png" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

</head>

<body class="with-welcome-text">
  <div class="container-scroller">

  <?php include 'importantinclude/topbar.php'; ?>
  
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php include 'importantinclude/sidebar.php'; ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="home-tab">
              <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true"> Inbox <span class="mdi mdi-arrow-right-thin"></span> Archived Messages</a>
                  </li>
                </ul>
              </div>
              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-sm-12">

                    </div>
                  </div>
            <div class="col-md-12 mb-4">
              <div class="card">
                <div class="card-body">
                <h3 class="mb-4">Archived Messages</h3>
                <hr>
                  <div class="btn-go text-end">
                    <a class="btn btn-primary mb-3 text-white " href="message.php">Go Back</a>
                  </div>
                  <table id="dataTable" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Room Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($rooms as $room): ?>
                        <tr>
                          <td><?= htmlspecialchars($room['name']) ?></td>
                          <td><?= htmlspecialchars($room['created_at']) ?></td>
                          <td class="text-center">
                            <a style="" href="chatArchive.php?room_id=<?= htmlspecialchars($room['id']) ?>" class="btn btn-primary text-white">Open Room</a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include 'importantinclude/footer.php'; ?>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
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
  <!-- jQuery (Required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#dataTable').DataTable({
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