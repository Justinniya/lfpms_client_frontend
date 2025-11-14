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

$userData = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userid'])) {
  $Id = $_POST['userid'];

  // Secure query using prepared statement
  $query = "SELECT * FROM users WHERE userid = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$Id]);
  $userData = $stmt->fetch(PDO::FETCH_ASSOC);

  // Secure query using prepared statement
  $query1 = "SELECT * FROM users 
            INNER JOIN products ON users.userid = products.msme_id
            WHERE userid = ?";
  $stmt1 = $conn->prepare($query1);
  $stmt1->execute([$Id]);
  $userData1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  $query2 = "SELECT COUNT(*) as total FROM users 
  INNER JOIN products ON users.userid = products.msme_id WHERE userid = $Id";
  $stmt2 = $conn->prepare($query2);
  $stmt2->execute();
  $userData2 = $stmt2->fetch(PDO::FETCH_ASSOC);
  $totalCount = $userData2['total']; // Get the count result
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
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
                      <a style="margin-left:5px; margin-top: -35px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Account <span class="mdi mdi-arrow-right-thin"></span> Update Profile</a>
                    </li>
                  </ul>
                </div>
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                      <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Personal Information</h4>
                            <hr>
                            <form method="post" action="save_profile.php">
                              <input type="text" class="form-control" name="id" value="<?php echo $Id; ?>" hidden>
                              <div class="row">

                                <div class="mb-3 col-md-3">
                                  <label class="form-label">Status</label>
                                  <div class="d-flex align-items-center">
                                    <div class="form-check me-3">
                                      <input style="margin-left:5px;" class="form-check-input" type="radio" name="status" id="active" value="1"
                                        <?php echo ($userData['status'] == 1) ? 'checked' : ''; ?>>
                                      <label style="font-size:16px;" class="form-check-label" for="active">Active</label>
                                    </div>
                                    <div class="form-check">
                                      <input style="margin-left:5px;" class="form-check-input" type="radio" name="status" id="inactive" value="2"
                                        <?php echo ($userData['status'] == 2) ? 'checked' : ''; ?>>
                                      <label style="font-size:16px;" class="form-check-label" for="inactive">Inactive</label>
                                    </div>
                                  </div>
                                </div>

                                <div class="mb-3 col-md-3">
                                  <label for="fullname" class="form-label">Full Name</label>
                                  <input type="text" class="form-control" id="fullname" name="fname" value="<?php echo htmlspecialchars($userData['fname']); ?>">
                                </div>

                                <div class="mb-3 col-md-3">
                                  <label for="lastname" class="form-label">Last Name</label>
                                  <input type="text" class="form-control" id="lastname" name="lname" value="<?php echo htmlspecialchars($userData['Lname']); ?>">
                                </div>

                                <div class="mb-3 col-md-3">
                                  <label for="middlename" class="form-label">Middle Name</label>
                                  <input type="text" class="form-control" id="middlename" name="mname" value="<?php echo htmlspecialchars($userData['Mname']); ?>">
                                </div>
                              </div>

                              <div class="row">
                                <div class="mb-3 col-md-6">
                                  <label for="email" class="form-label">Email</label>
                                  <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
                                </div>

                                <div class="mb-3 col-md-6">
                                  <label for="phone" class="form-label">Phone</label>
                                  <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>">
                                </div>
                              </div>

                              <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($userData['address']); ?>">
                              </div>

                              <div class="row">
                                <h3 class="mb-3">Account</h3>
                                <div class="mb-3 col-md-6">
                                  <label for="username" class="form-label">Username</label>
                                  <input type="text" class="form-control" id="username" name="username" readonly value="<?php echo htmlspecialchars($userData['username']); ?>">
                                </div>

                                <div class="mb-3 col-md-6">
                                  <label for="password" class="form-label">Password</label>
                                  <input type="text" class="form-control" id="password" name="password" placeholder="" value="<?php echo htmlspecialchars($userData['password']); ?>">
                                </div>
                              </div>
                              <div class="text-end">
                                <button type="submit" class="btn btn-success btn-lg text-white">Update</button>
                              </div>
                            </form>
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
  <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="../assets/vendors/chart.js/chart.umd.js"></script>
  <script src="../assets/vendors/progressbar.js/progressbar.min.js"></script>
  <script src="../assets/js/off-canvas.js"></script>
  <script src="../assets/js/template.js"></script>
  <script src="../assets/js/settings.js"></script>
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/todolist.js"></script>
  <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="../assets/js/dashboard.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</body>

</html>