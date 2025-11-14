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

// Fetch MSME users (usertype = 2)
function getMsmeUsers($conn)
{
  $sql = "SELECT profile_image, userid, fname, Lname FROM users WHERE usertype = 2";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$sql = 'SELECT profile_image, fname, Lname, Mname, email, phone, address, username, password FROM users WHERE userid = :id';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $Id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <!-- partial:partials/_navbar.html -->
    <?php include 'importantinclude/topbar.php'; ?>
    <!-- partial -->
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
                    <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Account Settings</a>
                  </li>
                </ul>
              </div>

              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-sm-12">

                    </div>
                  </div>
                  <!-- Search Card -->
                  <div class="col-md-12 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex mb-2 justify-content-center">
                          <h2>Personal Information</h2>
                        </div>
                        <hr>

                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                              <img src="<?php echo !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : 'https://bootdey.com/img/Content/avatar/avatar7.png'; ?>"
                                alt="Admin"
                                class="rounded-circle"
                                width="150">

                              <div class="mt-3">
                                <h4>Welcome, <?php echo $user['fname']; ?> <?php echo $user['Mname']; ?> <?php echo $user['Lname']; ?>!</h4>
                                <a href="updateprofile.php" class="btn btn-primary text-white mt-2"> Update Profile</a>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="card-body">
                          <form method="post" action="save_profile.php">
                            <input type="text" class="form-control" name="id" value="<?php echo $_SESSION['id']; ?>" hidden>

                            <div class="row">
                              <div class="mb-3 col-md-4">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fname']); ?>" readonly>
                              </div>
                              <div class="mb-3 col-md-4">
                                <label for="middlename" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middlename" name="middlename" value="<?php echo htmlspecialchars($user['Mname']); ?>" readonly>
                              </div>
                              <div class="mb-3 col-md-4">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['Lname']); ?>" readonly>
                              </div>
                            </div>

                            <div class="row">
                              <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                              </div>


                              <div class="mb-3 col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                              </div>
                            </div>

                            <div class="mb-3">
                              <label for="address" class="form-label">Address</label>
                              <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" readonly>
                            </div>

                            <div class="row">
                              <h3 class="mb-3">Account Information</h3>
                              <div class="mb-3 col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                              </div>

                              <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" readonly>
                              </div>
                            </div>

                          </form>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php include 'importantinclude/footer.php'; ?>
            </div>





            <!-- partial -->
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