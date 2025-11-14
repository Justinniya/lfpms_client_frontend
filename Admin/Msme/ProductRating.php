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
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
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
  <style>
    .table-container {
      overflow-x: auto;
    }

    .transactions-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 18px;
      text-align: left;
    }

    .transactions-table th,
    .transactions-table td {
      padding: 12px 15px;
      border: 1px solid #ddd;
    }

    .transactions-table th {
      background-color: #f4f4f4;
    }

    .transactions-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .delete-btn {
      color: white;
      text-decoration: none;
    }

    /* General section styles */
    .show-products {
      font-family: Arial, sans-serif;
    }

    /* Heading styles */
    .show-products .heading {
      text-align: center;
      font-size: 24px;
      margin-bottom: 20px;
    }

    /* Box container styles */
    .show-products .box-container {
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 20px;
    }

    /* Form styles */
    .show-products form {
      margin-bottom: 20px;
    }

    .show-products .form-group {
      margin-bottom: 10px;
    }

    .show-products label {
      font-weight: bold;
    }

    .show-products select {
      width: 100%;
      padding: 8px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    /* Feedback categories container */
    .show-products .feedback-categories {
      margin-top: 20px;
    }

    /* Feedback category styles */
    .show-products .feedback-category {
      margin-bottom: 20px;
      padding: 10px;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .show-products .category-title {
      font-size: 18px;
      margin-bottom: 10px;
      text-align: center;
    }

    /* Table styles */
    .show-products .feedback-table {
      width: 100%;
      margin-top: 10px;
      border-collapse: collapse;
    }

    .show-products .feedback-table th,
    .show-products .feedback-table td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }

    .show-products .feedback-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    .show-products .feedback-table td {
      font-size: 14px;
    }

    .show-products .feedback-table td[colspan="2"] {
      text-align: center;
      padding: 10px;
      color: #999;
      font-style: italic;
    }
  </style>
</head>

<body class="with-welcome-text">
  <div class="container-scroller">

  <?php include 'importantinclude/topbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial:partials/_sidebar.html -->
      <?php include 'importantinclude/sidebar.php'; ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="home-tab">
              <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true"> Product Ratings</a>
                  </li>
                </ul>
              </div>
              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-sm-12">

                    </div>
                  </div>
            <div class="col-sm-12">
              <section class="show-products">
                <div class="card">
                  <div class="card-body">
                  <h3>Product Ratings</h3>
                  <hr>
                    <?php
                    // Fetch the product list for the filter
                    $productQuery = "SELECT product_id, productName FROM products WHERE msme_id = $Id";
                    $productResult = $conn->query($productQuery);
                    $products = $productResult->fetchAll(PDO::FETCH_ASSOC);

                    // Check if there are any products
                    if (empty($products)) {
                      echo "<p>No products found.</p>";
                    } else {
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

                      <div class="feedback-categories">
                        <div class="row">
                          <?php
                          $categories = ['Quality', 'Labeling', 'Packaging', 'Logo', 'Taste'];
                          $hasFeedback = false;

                          foreach ($categories as $category) {
                            $data = fetchFeedbackData($conn, $selectedProductId, $category);
                            if ($data) {
                              $hasFeedback = true;
                          ?>
                              <div class="col-md-6 col-lg-4">
                                <div class="feedback-category">
                                  <h5 class="category-title">Category: <?= $category ?></h5>
                                  <table class="feedback-table">
                                    <tbody>
                                      <tr>
                                        <th>Product Name</th>
                                        <td><?= $data['productName'] ?></td>
                                      </tr>
                                      <tr>
                                        <th>Total Feedback</th>
                                        <td><?= $data['total_feedback'] ?></td>
                                      </tr>
                                      <tr>
                                        <th>Total Rating Value</th>
                                        <td><?= $data['total_rating_value'] ?></td>
                                      </tr>
                                      <tr>
                                        <th>Satisfied Count</th>
                                        <td><?= $data['satisfied_count'] ?></td>
                                      </tr>
                                      <tr>
                                        <th>Neutral Count</th>
                                        <td><?= $data['neutral_count'] ?></td>
                                      </tr>
                                      <tr>
                                        <th>Unsatisfied Count</th>
                                        <td><?= $data['unsatisfied_count'] ?></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                          <?php
                            }
                          }

                          if (!$hasFeedback) {
                            echo "<p>No feedback records found for the selected product.</p>";
                          }
                          ?>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
        <?php include 'importantinclude/footer.php'; ?>
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
  <!-- jQuery (Required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- DataTables JS -->
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