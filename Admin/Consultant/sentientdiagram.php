<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit();
}

include '../../session.php'; // Ensure $conn is included here

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
  $sql = "SELECT userid, fname, Lname FROM users WHERE usertype = 2";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch and rank products based on sentiment
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

// Fetch MSME users
$msmeUsers = getMsmeUsers($conn);

$selectedMsme = null;
$rankedProducts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['msme'])) {
  $selectedMsme = intval($_POST['msme']); // Ensure integer value

  // Fetch ranked products for the selected MSME
  $rankedProducts = getRankedProducts($conn, $selectedMsme);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>LFPMS</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <link rel="shortcut icon" href="assets/img/bb.png" />

  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Custom Table Styling */
    .custom-table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Table Header */
    .custom-table thead {
      background-color: #007bff;
      color: white;
      font-weight: bold;
    }

    .custom-table thead th {
      padding: 12px;
      text-align: center;
    }

    /* Table Rows */
    .custom-table tbody tr {
      transition: all 0.3s ease-in-out;
    }

    .custom-table tbody tr:hover {
      background: rgba(0, 123, 255, 0.1);
    }

    /* Table Cells */
    .custom-table td {
      padding: 12px;
      text-align: center;
      vertical-align: middle;
    }

    /* Product Image Styling */
    .product-img {
      max-width: 250px;
      height: auto;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Responsive Table */
    @media (max-width: 768px) {
      .product-img {
        max-width: 50px;
      }

      .custom-table td {
        font-size: 14px;
      }
    }

    /* Space out the DataTable components */
    .dataTables_wrapper {
      padding: 15px 0;
    }

    /* Space between Show Entries and Search */
    .dataTables_length {
      margin-bottom: 15px;
      margin-left: 10px;
    }


    .dataTables_filter {
      margin-bottom: 15px;
      margin-left: 350px;
    }

    /* Add spacing below the table */
    .dataTables_info {
      margin-top: 15px;
    }

    /* Add spacing above pagination */
    .dataTables_paginate {
      margin-top: 15px;
    }

    /* Fix horizontal scrollbar issue */
    .dataTables_wrapper .row {
      margin-bottom: 10px;
    }
  </style>
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
                      <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview of Product Analysis</a>
                    </li>
                  </ul>
                </div>
                <div class="tab-content tab-content-basic">

                  <div class="card">
                    <div class="card-body">
                      <h3 style="text-align:left;"><b>Diagram Analysis</b></h3>
                      <hr>
                      <p><b>Note:</b> Select the MSME user to view their product ranking based on the sentiment result in diagram format</p>
                      <form method="post" class="mb-4">
                        <div class="form-group">
                          <label for="msme">Select MSME:</label>
                          <select style="color:black;" name="msme" id="msme" class="form-control" required onchange="this.form.submit()">
                            <option value="">-- Select MSME --</option>
                            <?php foreach ($msmeUsers as $msme): ?>
                              <option value="<?= $msme['userid'] ?>" <?= ($msme['userid'] == $selectedMsme) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($msme['fname'] . ' ' . $msme['Lname']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </form>
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
                  <?php elseif ($selectedMsme): ?>
                    <div class="alert alert-warning mt-4" role="alert">
                      No results found for this selection.
                    </div>
                  <?php endif; ?>
                  <br>


                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php include 'importantinclude/footer.php'; ?>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
  <!-- Include DataTables CSS & jQuery (Required) -->
  <!-- jQuery (Required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

</body>

</html>