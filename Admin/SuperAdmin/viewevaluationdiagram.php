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
// Fetch product list for the filter
try {
  $productQuery = "SELECT product_id, productName FROM products";
  $productResult = $conn->query($productQuery);
  $products = $productResult->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

// Fetch latest evaluation scores for MSME users
try {
  $query = "SELECT e.MSME, e.total_score, u.fname, u.lname
            FROM evaluation AS e
            INNER JOIN users AS u ON e.MSME = u.userid
            WHERE e.id IN (
                SELECT MAX(id)
                FROM evaluation
                GROUP BY MSME
            )";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $evaluationData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
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
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">View Diagram</a>
                    </li>
                  </ul>
                </div>

                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row">
                      <div class="col-md-12 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <div class="btnAdd text-end">
                              <a href="ViewEvaluation.php" class="btn btn-primary text-white btn-sm">Go Back</a>
                            </div>

                            <?php if ($evaluationData): ?>
                              <div>
                                <canvas id="scoreChart"></canvas>
                              </div>
                            <?php else: ?>
                              <p>No evaluation data found.</p>
                            <?php endif; ?>
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

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Get evaluation data from PHP and convert it to JavaScript array
    var evaluationData = <?php echo json_encode($evaluationData); ?>;

    // Prepare arrays for chart data
    var msmeNames = evaluationData.map(item => `${item.fname} ${item.lname}`);
    var scores = evaluationData.map(item => item.total_score);

    // Create chart data
    var chartData = {
      labels: msmeNames,
      datasets: [{
        label: 'Latest Evaluation Score',
        data: scores,
        backgroundColor: 'rgba(75, 192, 192, 0.5)',
        borderWidth: 1
      }]
    };

    // Get the canvas element
    var ctx = document.getElementById('scoreChart').getContext('2d');

    // Create the chart
    var scoreChart = new Chart(ctx, {
      type: 'bar',
      data: chartData,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

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