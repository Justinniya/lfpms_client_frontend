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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="shortcut icon" href="assets/img/bb.png" />
</head>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php
    $current_user = $_SESSION['id'] ?? null; // Ensure user ID is set
    try {
      $stmt = $conn->prepare("
        SELECT cq.id, 
               u.userid, 
               'Your Product Development Assessment has been approved.' AS message,
               'approved' AS type,
               cr.*
        FROM consultancyquestionnaire cq
        INNER JOIN consultation_report cr ON cq.id = cr.consultationID
        INNER JOIN users u ON u.userid = cr.conID
        WHERE cr.conid = :user_id AND cq.status = 6
        ORDER BY cq.id DESC
    ");
      $stmt->bindParam(':user_id', $current_user, PDO::PARAM_INT);
      $stmt->execute();
      $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Database error: " . $e->getMessage();
      exit();
    } catch (Exception $e) {
      echo $e->getMessage();
      exit();
    }


    ?>

    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="consultant_index.php">
            <img src="assets/img/bb.png" alt="">
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <h5>Welcome Consultant, <span class="text-black fw-bold"><?= htmlspecialchars($loggedInUser['fname']); ?>!</span></h5>
          </li>
        </ul>
    </nav>
    <div class="container-fluid page-body-wrapper">
      <?php include 'importantinclude/sidebar.php'; ?>
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="home-tab">
              <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Account Page</a>
                  </li>
                </ul>
              </div>
              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <h3>Profile Information</h3><span class="text-end">
                              </div>
                              <div class="col-md-6 text-end">
                                <a href="consultant_index.php" class="btn btn-primary text-white">Home</a></span>
                              </div>
                            </div>
                            <hr>
                            <div class="d-flex flex-column align-items-center text-center">
                             <img src="<?php echo !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : 'https://bootdey.com/img/Content/avatar/avatar7.png'; ?>"
                                alt="Admin"
                                class="rounded-circle"
                                width="150">
                              <div class="mt-3">
                                <h4>Welcome, <?php echo $user['fname']; ?> <?php echo $user['Mname']; ?> <?php echo $user['Lname']; ?>!</h4>
                                <a href="updateprofile.php" class="btn btn-primary text-white"> Update Profile</a>
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