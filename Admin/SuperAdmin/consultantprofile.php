<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit();
}
include '../../session.php';

// Database connection using PDO
try {
  $conn = new PDO("mysql:host=localhost;dbname=lfpms", "root", "");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// Function to get logged-in user details
function getLoggedInUser($conn, $user_id)
{
  $sql = "SELECT * FROM users WHERE userid = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$user_id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);


// Fetch user data if form is submitted
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

  $query2 = "SELECT COUNT(*) as total 
    FROM consultancyquestionnaire cq
    INNER JOIN consultation_report cr ON cq.id = cr.consultationID
    INNER JOIN room r ON cr.room_id = r.ID
    INNER JOIN chatmember cm ON r.ID = cm.room_ID
    INNER JOIN users u ON cm.user_id = u.userid
    WHERE u.usertype = 3  -- Only consultants
    AND cq.status = 7      -- Only successfully developed products
    AND u.userid = :Id"; // Filter for a specific consultant

$stmt2 = $conn->prepare($query2);
$stmt2->bindParam(':Id', $Id, PDO::PARAM_INT); // Bind consultant ID
$stmt2->execute();

$userData2 = $stmt2->fetch(PDO::FETCH_ASSOC);
$totalCount = $userData2['total']; // Get the count result
}

try {
  // Prepare the query
  $stmt = $conn->prepare("
      SELECT 
          u.userid AS consultant_id,
          u.fname AS consultant_name,
          cq.id AS questionnaire_id,
          cq.name AS product_name,
          cq.SubmittionDate AS submission_date,
          cr.consultationID,
          r.id,
          cm.user_id AS chat_user_id
      FROM consultancyquestionnaire cq
      INNER JOIN consultation_report cr ON cq.id = cr.consultationID
      INNER JOIN room r ON cr.room_id = r.id
      INNER JOIN chatmember cm ON r.id = cm.room_ID
      INNER JOIN users u ON cm.user_id = u.userid
      WHERE u.usertype = 3  -- Only consultants
      AND u.userid = 81
      AND cq.status = 7      -- Only successfully developed products
      ORDER BY cq.SubmittionDate DESC;
  ");

  // Execute query
  $stmt->execute();

  // Fetch all results
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  </head>

  <body class="with-welcome-text">
    <div class="container-scroller">
      <?php include 'importantinclude/topbar.php'; ?>
      <div class="container-fluid page-body-wrapper">
        <?php include 'importantinclude/sidebar.php'; ?>
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a style="margin-left:5px; margin-top: -35px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Account Settings</a>
                    </li>
                  </ul>
                </div>

                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="col-md-12 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex mb-2 justify-content-center">
                            <h2>Personal Information</h2>
                          </div>
                          <hr>

                          <?php if ($userData): ?>
                            <div class="card">
                              <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                  <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                                  <div class="mt-3">
                                    <h4><?php echo htmlspecialchars($userData['fname'] . " " . $userData['Mname'] . " " . $userData['Lname']); ?>!</h4>
                                    <p class="text-secondary mb-1"><?php echo $totalCount;?> </p>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="card-body">
                              <form method="post" action="save_profile.php">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">

                                <div class="row">
                                  <div class="mb-3 col-md-4">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['fname']); ?>" readonly>
                                  </div>
                                  <div class="mb-3 col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['Mname']); ?>" readonly>
                                  </div>
                                  <div class="mb-3 col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['Lname']); ?>" readonly>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="mb-3 col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
                                  </div>
                                  <div class="mb-3 col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['phone']); ?>" readonly>
                                  </div>
                                </div>

                                <div class="mb-3">
                                  <label class="form-label">Address</label>
                                  <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['address']); ?>" readonly>
                                </div>

                                <div class="row">
                                  <h3 class="mb-3">Account Information</h3>
                                  <div class="mb-3 col-md-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['username']); ?>" readonly>
                                  </div>
                                  <div class="mb-3 col-md-6">
                                    <label class="form-label">Password</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($userData['password']); ?>" readonly>
                                  </div>
                                </div>
                              </form>
                            </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php include 'importantinclude/footer.php'; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
} ?>
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