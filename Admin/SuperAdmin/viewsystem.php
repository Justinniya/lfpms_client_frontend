<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit();
}
include '../../session.php';

// Fetch logged-in user details
function getLoggedInUser($conn, $user_id)
{
  $sql = "SELECT * FROM users WHERE userid = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$user_id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);


try {
  // Perform the SQL query to fetch feedback data
  $query = "SELECT `feedbackID`, 
                   COALESCE(`name`, 'Anonymous') AS name, 
                   `comment`, 
                   `feedback_date` 
            FROM `system_feedback`";

  // Execute the query and fetch data
  $result = $conn->query($query);

  // Start the HTML structure
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
    <style>
      .custom-table th,
      .custom-table td {
        text-align: left;
        vertical-align: middle;
      }

      .custom-table .btn-group {
        display: flex;
        gap: 5px;
      }

      .custom-table .btn-sm {
        padding: 5px 10px;
      }
    </style>
  </head>

  <body class="with-welcome-text">
    <div class="container-scroller">
      <?php include 'importantinclude/topbar.php'; ?>
      <div class="container-fluid page-body-wrapper">
        <?php include 'importantinclude/sidebar.php'; ?>

        <div class="main-panel" style="margin-top: -20px;">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-sm-12">
                <div class="home-tab">
                  <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">System Feedback</a>
                      </li>
                    </ul>
                  </div>

                  <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                      <div class="row">
                        <div class="col-md-12 mb-4">
                          <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">View Survey Feedback</h5>
                              <hr>
                              <table id="feedback_monitoring" class="table table-border">
                                <thead>
                                  <tr>
                                    <th>Feedback ID</th>
                                    <th>Name</th>
                                    <th>Comment</th>
                                    <th>Feedback Date</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  // Check if there are rows returned
                                  if ($result && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                      // Output each row as table rows
                                  ?>
                                      <tr>
                                        <td><?= $row['feedbackID'] ?></td>
                                        <td><?= $row['name'] ?></td>
                                        <td><?= $row['comment'] ?></td>
                                        <td><?= $row['feedback_date'] ?></td>
                                      </tr>
                                    <?php
                                    }
                                  } else {
                                    // Display a message if no feedback found
                                    ?>
                                    <tr>
                                      <td colspan="5">No feedback found</td>
                                    </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        <?php
                      } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                      }
                        ?>

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

    <script>
      $(document).ready(function() {
        $('#feedback_monitoring').DataTable();
      });
    </script>

  </body>

  </html>