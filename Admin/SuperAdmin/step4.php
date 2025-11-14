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

if (isset($_GET['id'])) {
  $MSME_id = $_GET['id'];
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
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">Assessment Stage <span class="mdi mdi-arrow-right-thin"> Stage 1 <span class="mdi mdi-arrow-right-thin"> Stage 2 <span class="mdi mdi-arrow-right-thin"> Stage 3 <span class="mdi mdi-arrow-right-thin"> Stage 4</a>
                    </li>
                  </ul>
                </div>

                <?php
                include('connection/connection.php');
                $MSME_id = $_GET['id']; // Assuming MSME_id is passed via GET request

                $sql_notifications = "SELECT 
                        products.productName, 
                        product_updates.column_name, 
                        product_updates.old_value, 
                        product_updates.new_value, 
                        product_updates.updated_at, 
                        CONCAT(users.fname, ' ', users.Lname) AS updater 
                      FROM 
                        product_updates 
                      JOIN 
                        products ON product_updates.product_id = products.product_id 
                      JOIN 
                        users ON products.msme_id = users.userid 
                      WHERE 
                        product_updates.is_read = 0 
                        AND products.msme_id = ? 
                      ORDER BY 
                        product_updates.updated_at DESC 
                      LIMIT 1"; // Fetch only the most recent unread update

                // Prepare and execute the query
                $stmt = $conn->prepare($sql_notifications);
                $stmt->bind_param("i", $MSME_id); // Bind MSME_id as an integer
                $stmt->execute();
                $result_notifications = $stmt->get_result();

                // Check if any unread implementation exists
                $hasUnreadUpdates = ($result_notifications->num_rows > 0);
                ?>

                <div class="col-lg-12 grid-margin stretch-card mt-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="container mt-4">
                        <div class="card">
                          <div class="card-body">
                            <h3 class="mb-2 pt-4 d-flex flex-column"><b>Stage 4: Implementation</b></h3>
                        
                            <p><b>Status:</b> Implementation of Design</p>
                            <hr>
                            <div class="">
                              <div class="card">
                                <div class="card-header">
                                  <h5 class="card-title">Product Implementation</h5>
                                </div>
                                <div class="card-body">
                                  <?php if ($hasUnreadUpdates) { ?>
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr>
                                          <th>Product</th>
                                          <th>Old Data</th>
                                          <th>New Data</th>
                                          <th>Implementation Date</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php while ($row_notification = $result_notifications->fetch_assoc()) { ?>
                                          <tr>
                                            <td><?php echo $row_notification['productName']; ?></td>
                                            <?php if ($row_notification['column_name'] === 'productImage') { ?>
                                              <td><img src="../../pages/uploaded_img/<?php echo $row_notification['old_value'] ?: 'no-image.png'; ?>" width="250px" height="250px" alt="Old Image"></td>
                                              <td><img src="../../pages/uploaded_img/<?php echo $row_notification['new_value'] ?: 'no-image.png'; ?>" width="250px" height="250px" alt="New Image"></td>
                                            <?php } else { ?>
                                              <td><?php echo $row_notification['old_value']; ?></td>
                                              <td><?php echo $row_notification['new_value']; ?></td>
                                            <?php } ?>
                                            <td><?php echo $row_notification['updated_at']; ?></td>
                                          </tr>
                                        <?php } ?>
                                      </tbody>
                                    </table>
                                  <?php } else { ?>
                                    <p>No Implementation Found</p>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>

                            <div class="container text-center">
                              <form method="POST" action="review_Assessment.php">
                                <label for="decision">Stage Completion: Mark as Finished if the Stage is Complete</label><br>
                                <input name="user_id" type="hidden" value="<?php echo $MSME_id; ?>">
                                <button type="submit" name="decision" class="btn btn-primary text-white mt-2" value="6" <?php echo !$hasUnreadUpdates ? 'disabled' : ''; ?>>Approve</button>
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
      $('#feedback_monitoring').DataTable({
        responsive: true,
        autoWidth: false
      });
    });
  </script>

</body>

</html>