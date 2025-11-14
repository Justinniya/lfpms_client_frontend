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

// Default filter condition (show all evaluations)
$filterCondition = "";

// Check if filter is applied
if (isset($_GET['filter'])) {
  $filter = $_GET['filter'];
  if ($filter === 'need_assessment') {
    // Filter for evaluations needing assessment (total score < 30)
    $filterCondition = "WHERE `total_score` < 30";
  } elseif ($filter === 'no_need_assessment') {
    // Filter for evaluations not needing assessment (total score >= 30)
    $filterCondition = "WHERE `total_score` >= 30";
  }
}

try {
  // Perform the SQL query to fetch evaluations with applied filter
  $query = "SELECT `id`, `Cname`, `MSME`, `product_id`, `Pname`, `ExamDate`, `total_score`
              FROM `evaluation`
              $filterCondition"; // Apply the filter condition

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
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">Evaluation Sheet</a>
                      </li>
                    </ul>
                  </div>

                  <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                      <div class="row">
                        <div class="col-md-12 mb-4">
                          <div class="card">
                            <div class="card-body">
                            <h5 class="card-title">View Evaluation</h5>
                            <hr>
                              <div class="btnAdd text-end">
                                <a href="ViewEvaluationDiagram.php" class="btn btn-primary text-white btn-sm">View Evaluation Diagram</a>
                                <a href="add_Evaluation.php" class="btn btn-success text-white btn-sm">Add Evaluation</a>
                              </div>
                              <!-- Selection Filter -->
                              <form method="GET" action="">
                                <div class="form-group mt-3">
                                  <label for="filterSelect">Filter by:</label>
                                  <select id="filterSelect" name="filter" class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Select Filter --</option>
                                    <option value="need_assessment" <?= isset($_GET['filter']) && $_GET['filter'] === 'need_assessment' ? 'selected' : '' ?>>Need Assessment</option>
                                    <option value="no_need_assessment" <?= isset($_GET['filter']) && $_GET['filter'] === 'no_need_assessment' ? 'selected' : '' ?>>No Need Assessment</option>
                                  </select>
                                </div>
                              </form>

                              <table id="feedback_monitoring" class="table table-border">
                                <thead>
                                  <tr>
                                    <th>Company Name</th>
                                    <th>MSME Name</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Submission Date</th>
                                    <th>Rating Score</th>
                                    <th>Action</th>
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
                                        <td><?= $row['Cname'] ?></td>
                                        <td><?= $row['MSME'] ?></td>
                                        <td><?= $row['product_id'] ?></td>
                                        <td><?= $row['Pname'] ?></td>
                                        <td><?= $row['ExamDate'] ?></td>
                                        <td>
                                          <?php if ($row['total_score'] < 30): ?>
                                            <span style="color: red;">Need Assessment:</span> <?= $row['total_score'] ?>
                                          <?php else: ?>
                                            <?= $row['total_score'] ?>
                                          <?php endif; ?>
                                        </td>
                                        <td><a class="btn btn-primary text-white btn-sm" href="ViewAnswerdEvaluation.php?id=<?= $row['id'] ?>">View Evaluation</a></td>
                                      </tr>
                                    <?php
                                    }
                                  } else {
                                    // Display a message if no feedback found
                                    ?>
                                    <tr>
                                      <td colspan="7">No feedback found</td>
                                    </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
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