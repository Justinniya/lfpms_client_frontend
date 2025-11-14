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

// Fetch MSME users (usertype = 2)
function getMsmeUsers($conn)
{
  $sql = "SELECT userid, fname, Lname FROM users WHERE usertype = 2";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch the product list for the filter
$productQuery = "SELECT product_id, productName FROM products";
$productResult = $conn->query($productQuery);
$products = $productResult->fetchAll(PDO::FETCH_ASSOC);

// Get selected product ID from the filter
$selectedProductId = isset($_GET['product_id']) ? $_GET['product_id'] : $products[0]['product_id'];

function fetchFeedbackData($conn, $productId, $category)
{
  $query = "SELECT p.productName,
                     SUM(f.rating_value) AS total_rating_value,
                     SUM(CASE WHEN f.rating_value = 3 THEN 1 ELSE 0 END) AS satisfied_count,
                     SUM(CASE WHEN f.rating_value = 2 THEN 1 ELSE 0 END) AS neutral_count,
                     SUM(CASE WHEN f.rating_value = 1 THEN 1 ELSE 0 END) AS unsatisfied_count,
                     COUNT(*) AS total_feedback
              FROM feedback AS f
              INNER JOIN products AS p ON f.product_id = p.product_id
              WHERE f.product_id = :product_id AND f.category = :category
              GROUP BY f.product_id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
  $stmt->bindParam(':category', $category, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
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
  <link rel="shortcut icon" href="assets/img/bb.png" />
  <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
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
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php include 'importantinclude/sidebar.php'; ?>
      <!-- partial -->
      <div class="main-panel" style="margin-top: -20px;">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a style="margin-left:5px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview of Product Feedback</a>
                    </li>
                  </ul>
                </div>
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                      <!-- Sentiment Analysis Search Card -->
                      <div class="col-md-12 mb-4">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="card-title">Monitor Product Feedbacks</h5>
                          </div>
                          <div class="card-body">
                            <div class="btnAdd d-flex justify-content-end">
                              <a href="ViewFeedbackDiagram.php" class="btn btn-success btn-sm text-white">View Feedback Diagram</a>
                              <button class="btn btn-primary btn-sm text-white" onclick="printTable()">Print Table</button>
                            </div>
                            <form method="GET" action="">
                              <div class="form-group">
                                <label for="productSelect">Select Product:</label>
                                <select style="color:black;" id="productSelect" name="product_id" class="form-control" onchange="this.form.submit()">
                                  <?php foreach ($products as $product): ?>
                                    <option value="<?= $product['product_id'] ?>" <?= $selectedProductId == $product['product_id'] ? 'selected' : '' ?>>
                                      <?= $product['productName'] ?>
                                    </option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                            </form>

                            <?php if ($selectedProductId): ?>
                              <?php
                              $categories = ['Quality', 'Labeling', 'Packaging', 'Logo', 'Taste'];
                              foreach ($categories as $category):
                                $data = fetchFeedbackData($conn, $selectedProductId, $category);
                              ?>
                                <h5 class="mt-4">Category: <?= $category ?></h5>
                                <table class="table table-border">
                                  <thead>
                                    <tr>
                                      <th>Product Name</th>
                                      <th>Total Feedback</th>
                                      <th>Total Rating Value</th>
                                      <th>Satisfied Count</th>
                                      <th>Neutral Count</th>
                                      <th>Unsatisfied Count</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php if ($data): ?>
                                      <tr>
                                        <td><?= $data['productName'] ?></td>
                                        <td><?= $data['total_feedback'] ?></td>
                                        <td><?= $data['total_rating_value'] ?></td>
                                        <td><?= $data['satisfied_count'] ?></td>
                                        <td><?= $data['neutral_count'] ?></td>
                                        <td><?= $data['unsatisfied_count'] ?></td>
                                      </tr>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="6">No feedback found for this category.</td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                </table>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <p>Please select a product to view feedback details.</p>
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
  <script src="../assets/vendors/chart.js/chart.umd.js"></script>
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
  <script>
    function printTable() {
      var content = '';
      var tables = document.querySelectorAll('.table-border');
      tables.forEach(function(table) {
        var category = table.previousElementSibling.textContent.trim();
        content += `<h2>${category}</h2>` + table.outerHTML + '<br>';
      });
      var newWin = window.open('', 'Print-Window');
      newWin.document.open();
      newWin.document.write(`
          <html>
            <head>
              <title>Print</title> 
              <link rel="stylesheet" type="text/css" href="./home/css/bootstrap5.0.1.min.css">
              <style>
                body {
                  font-family: Arial, sans-serif;
                  margin: 20px;
                }
                h2 {
                  text-align: center;
                  margin-bottom: 20px;
                  color: #007bff;
                }
                .table-border {
                  width: 100%;
                  border-collapse: collapse;
                  margin-bottom: 20px;
                }
                .table-border th, .table-border td {
                  border: 1px solid #ddd;
                  padding: 8px;
                  text-align: center;
                }
                .table-border th {
                  background-color: #f2f2f2;
                  color: black;
                }
              </style>
            </head>
            <body onload="window.print()">
              ${content}
            </body>
          </html>
        `);
      newWin.document.close();
      setTimeout(function() {
        newWin.close();
      }, 1000); // Increased timeout to 1000ms to ensure the print dialog appears
    }
  </script>

  <script>
    // Function to clear the search input field
    function clearSearch() {
      document.getElementById('search').value = ''; // Clear input field
    }
  </script>

  <!-- Initialize DataTable -->
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