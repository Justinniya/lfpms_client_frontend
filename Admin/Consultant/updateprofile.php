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
$sql = 'SELECT profile_image, fname, Lname, Mname, email, phone, address, username, password FROM users WHERE userid = :id';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $Id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC); 
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
                          <div class="card-header">
                            <h5 class="card-title">Personal Information</h5>
                          </div>
                          <div class="card-body">
                            <form method="post" action="save_profile.php" enctype="multipart/form-data">
                              <input type="text" class="form-control" name="id" value="<?php echo $_SESSION['id']; ?>" hidden>
                              <!-- Profile Picture Upload -->
                              <div class="mb-3 text-center">
                                <label for="profile_image" class="form-label">Profile Image</label><br>
                                <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile" class="img-thumbnail mb-2" width="150" height="150">
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                              </div>

                              <div class="row">
                                <div class="mb-3 col-md-4">
                                  <label for="fullname" class="form-label">Full Name</label>
                                  <input type="text" class="form-control" id="fullname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>">
                                </div>

                                <div class="mb-3 col-md-4">
                                  <label for="lastname" class="form-label">Last Name</label>
                                  <input type="text" class="form-control" id="lastname" name="lname" value="<?php echo htmlspecialchars($user['Lname']); ?>">
                                </div>

                                <div class="mb-3 col-md-4">
                                  <label for="middlename" class="form-label">Middle Name</label>
                                  <input type="text" class="form-control" id="middlename" name="mname" value="<?php echo htmlspecialchars($user['Mname']); ?>">
                                </div>
                              </div>

                              <div class="row">
                                <div class="mb-3 col-md-6">
                                  <label for="email" class="form-label">Email</label>
                                  <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                </div>

                                <div class="mb-3 col-md-6">
                                  <label for="phone" class="form-label">Phone</label>
                                  <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                </div>
                              </div>

                              <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                              </div>

                              <div class="row">
                                <h3 class="mb-3">Account</h3>
                                <div class="mb-3 col-md-6">
                                  <label for="username" class="form-label">Username</label>
                                  <input type="text" class="form-control" id="username" name="username" readonly value="<?php echo htmlspecialchars($user['username']); ?>">
                                </div>

                                <div class="mb-3 col-md-6">
                                  <label for="password" class="form-label">Password</label>
                                  <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>">
                                </div>
                              </div>
                              <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success text-white">Update</button>
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

  <!-- Include Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>

</html>