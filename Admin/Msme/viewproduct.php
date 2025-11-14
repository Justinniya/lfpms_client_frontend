<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit();
}
include '../../session.php';

// Fetch MSME users (usertype = 2)
function getMsmeUsers($conn)
{
  $sql = "SELECT userid, fname, Lname FROM users WHERE usertype = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLoggedInUser($conn, $user_id)
{
  $sql = "SELECT * FROM users WHERE userid = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$user_id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);

if (isset($_POST['add_product'])) {
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
  $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

  $image_01 = filter_var($_FILES['image_01']['name'], FILTER_SANITIZE_STRING);
  $image_size_01 = $_FILES['image_01']['size'];
  $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
  $image_folder_01 = '../pages/uploaded_img/' . $image_01;

  // Check if product name already exists
  $select_products = $conn->prepare("SELECT * FROM products WHERE productName = ? AND msme_id = ?");
  $select_products->execute([$name, $_SESSION['id']]);

  if ($select_products->rowCount() > 0) {
    $product_exists_message = 'Product name already exists!';
  } else {
    // Insert new product
    $insert_products = $conn->prepare("INSERT INTO products (msme_id, productName, productPrice, productImage, productDetails) VALUES (?, ?, ?, ?, ?)");
    $insert_products->execute([$_SESSION['id'], $name, $price, $image_01, $details]);

    if ($insert_products) {
      if ($image_size_01 > 2000000) {
        $message[] = 'Image size is too large!';
      } else {
        move_uploaded_file($image_tmp_name_01, $image_folder_01);
        $message[] = 'New product added!';
        echo '<script>alert("Product added successfully!");</script>';
        echo '<script>window.location.href = "products2.php";</script>';
      }
    } else {
      $message[] = 'Failed to add product!';
    }
  }
}
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
                    <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true"> Product <span class="mdi mdi-arrow-right-thin"></span> All Products</a>
                  </li>
                </ul>
              </div>

              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-sm-12">

                    </div>
                  </div>
            <div class="col-md-12 mb-4">
              <div class="card">
                <div class="card-body">
                  
                    <?php
                    $select_products = $conn->prepare("SELECT * FROM products WHERE msme_id = ?");
                    $select_products->execute([$_SESSION['id']]);

                    if ($select_products->rowCount() > 0) {
                    ?>
                      <div class="table-responsive">
                        <h2 class="mb-3">List of All Products</h2>
                        <hr>
                        <table id="dataTable" class="table table-striped table-bordered">
                          <thead class="thead-dark">
                            <tr>
                              <th scope="col">Product Image</th>
                              <th scope="col">Product Name</th>
                              <th scope="col">Price</th>
                              <th scope="col">Details</th>
                              <th scope="col">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) { ?>
                              <tr>
                                <td>
                                  <div class="text-center">
                                    <img src="../uploaded_img/<?= htmlspecialchars($fetch_products['productImage']); ?>" alt="Product Image" class="img-fluid" style="min-width: 250; max-width: 250px; height: 250;">
                                  </div>
                                </td>
                                <td><?= htmlspecialchars($fetch_products['productName']); ?></td>
                                <td>₱<?= htmlspecialchars($fetch_products['productPrice']); ?></td>
                                <td><?= htmlspecialchars($fetch_products['productDetails']); ?></td>
                                <td>
                                  <a href="update_product.php?update=<?= htmlspecialchars($fetch_products['product_id']); ?>"
                                    class="btn btn-warning btn-sm">Update</a>
                                  <!-- Uncomment if delete functionality is needed -->
                                  <!-- <a href="products2.php?delete=<?= htmlspecialchars($fetch_products['product_id']); ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Delete this product?');">Delete</a> -->
                                </td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    <?php
                    } else {
                      echo '<p class="empty text-center text-muted">No products added yet!</p>';
                    }
                    ?>
                  
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