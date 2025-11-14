<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit();
}

include '../../session.php'; // Ensure $conn is included

function getLoggedInUser($conn, $user_id)
{
  $sql = "SELECT * FROM users WHERE userid = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$user_id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);
$loggedInUserId = $loggedInUser['userid'];

// Fetch and rank products based on sentiment for the logged-in MSME
function getRankedProducts($conn, $msme_id)
{
  $sql = "SELECT p.product_id, p.productName, p.productImage, 
                   SUM(CASE WHEN f.sentiment = 'Satisfied' THEN 1 ELSE 0 END) AS satisfied_count,
                   SUM(CASE WHEN f.sentiment = 'Unsatisfied' THEN 1 ELSE 0 END) AS unsatisfied_count,
                   SUM(CASE WHEN f.sentiment = 'Neutral' THEN 1 ELSE 0 END) AS neutral_count
            FROM products p
            LEFT JOIN feedback f ON p.product_id = f.product_id
            WHERE p.msme_id = :msme_id
            GROUP BY p.product_id
            ORDER BY p.productName ASC";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':msme_id', $msme_id, PDO::PARAM_INT);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch ranked products automatically for the logged-in MSME user
$rankedProducts = getRankedProducts($conn, $loggedInUserId);
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
      <?php include 'importantinclude/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a style="margin-left:5px; margin-top: -35px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">
                        Overview of Product Analysis
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="tab-content tab-content-basic">

                  <div class="card">
                    <div class="card-body">
                      <h3 style="text-align:left;"><b>Diagram Analysis</b></h3>
                      <hr>
                      <p><b>Note:</b> Displaying sentiment analysis for your products.</p>
                    </div>
                  </div>

                  <?php if (!empty($rankedProducts)): ?>
                    <div class="card mt-4">
                      <div class="card-body">
                        <h3 class="my-4 text-center">Product Sentiment Analysis</h3>
                        <div class="chart-container d-flex justify-content-center align-items-center" style="height:500px;">
                          <canvas id="sentimentChart"></canvas>
                        </div>
                      </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                      const ctx = document.getElementById('sentimentChart').getContext('2d');
                      const labels = <?= json_encode(array_column($rankedProducts, 'productName')) ?>;
                      const satisfiedData = <?= json_encode(array_column($rankedProducts, 'satisfied_count')) ?>;
                      const unsatisfiedData = <?= json_encode(array_column($rankedProducts, 'unsatisfied_count')) ?>;
                      const neutralData = <?= json_encode(array_column($rankedProducts, 'neutral_count')) ?>;

                      const sentimentChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                          labels: labels,
                          datasets: [{
                              label: 'Satisfied',
                              data: satisfiedData,
                              backgroundColor: 'rgba(54, 162, 235, 0.5)',
                              borderColor: 'rgba(54, 162, 235, 1)',
                              borderWidth: 1
                            },
                            {
                              label: 'Unsatisfied',
                              data: unsatisfiedData,
                              backgroundColor: 'rgba(255, 99, 132, 0.5)',
                              borderColor: 'rgba(255, 99, 132, 1)',
                              borderWidth: 1
                            },
                            {
                              label: 'Neutral',
                              data: neutralData,
                              backgroundColor: 'rgba(255, 206, 86, 0.5)',
                              borderColor: 'rgba(255, 206, 86, 1)',
                              borderWidth: 1
                            }
                          ]
                        },
                        options: {
                          responsive: true,
                          scales: {
                            y: {
                              beginAtZero: true,
                              ticks: {
                                stepSize: 1
                              }
                            }
                          },
                          plugins: {
                            legend: {
                              position: 'top'
                            }
                          }
                        }
                      });
                    </script>
                  <?php else: ?>
                    <div class="alert alert-warning mt-4" role="alert">
                      No feedback data available for your products.
                    </div>
                  <?php endif; ?>
                  <br>

                </div>
              </div>
            </div>
          </div>
        </div>

        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Local Food Product Management System.</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2024. All rights reserved.</span>
          </div>
        </footer>
      </div>
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