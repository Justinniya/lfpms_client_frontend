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


if (isset($_GET['update'])) {
  $userId = $_GET['update'];

  try {
    $query = "SELECT * FROM products WHERE product_id=:ID";

    $statement = $conn->prepare($query);
    $statement->bindParam(':ID', $userId, PDO::PARAM_INT);
    $statement->execute();
    $ProductData = $statement->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    // Handle any database connection or query errors here
    echo "Error: " . $e->getMessage();
  }
}

if (isset($_POST['update_product'])) {

  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
  $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

  // Check if a new image has been uploaded
  if ($_FILES['image_01']['size'] > 0) {
    $image_01 = filter_var($_FILES['image_01']['name'], FILTER_SANITIZE_STRING);
    $image_size_01 = $_FILES['image_01']['size'];
    $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
    $image_folder_01 = '../uploaded_img/' . $image_01;
  } else {
    // If no new image uploaded, retain the existing image
    $image_01 = $ProductData['productImage'];
  }

  // Update the product information
  try {
    $update_product = $conn->prepare("UPDATE products SET productName=?, productPrice=?, productImage=?, productDetails=? WHERE product_id=?");
    $update_product->execute([$name, $price, $image_01, $details, $userId]);

    if ($update_product) {
      // Log the changes
      $changes = array();
      if ($ProductData['productName'] != $name) {
        $changes[] = array('column_name' => 'productName', 'old_value' => $ProductData['productName'], 'new_value' => $name);
      }
      if ($ProductData['productPrice'] != $price) {
        $changes[] = array('column_name' => 'productPrice', 'old_value' => $ProductData['productPrice'], 'new_value' => $price);
      }
      if ($ProductData['productImage'] != $image_01) {
        $changes[] = array('column_name' => 'productImage', 'old_value' => $ProductData['productImage'], 'new_value' => $image_01);
      }
      if ($ProductData['productDetails'] != $details) {
        $changes[] = array('column_name' => 'productDetails', 'old_value' => $ProductData['productDetails'], 'new_value' => $details);
      }

      foreach ($changes as $change) {
        $column_name = $change['column_name'];
        $old_value = $change['old_value'];
        $new_value = $change['new_value'];

        $sql_log_change = "INSERT INTO `product_updates` (`product_id`, `column_name`, `old_value`, `new_value`) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_log_change);
        $stmt->execute([$userId, $column_name, $old_value, $new_value]);
      }

      // If a new image is uploaded, move it to the destination folder
      if ($_FILES['image_01']['size'] > 0) {
        if ($image_size_01 > 2000000) {
          $message[] = 'Image size is too large!';
        } else {
          move_uploaded_file($image_tmp_name_01, $image_folder_01);
        }
      }
      $message[] = 'Product updated successfully!';
      echo '<script>alert("Product updated successfully!");</script>';
      echo '<script>window.location.href = "viewproduct.php";</script>';
    } else {
      $message[] = 'Failed to update product!';
    }
  } catch (PDOException $e) {
    // Handle any database connection or query errors here
    echo "Error: " . $e->getMessage();
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
            <!-- Search Card -->
            <div class="col-md-12 mb-4">
              <div class="card">
                <div class="card-body">
                  <div class="container">
                    <form action="" method="post" enctype="multipart/form-data">
                      <!-- Image Upload -->
                      <div class="form-group text-center">
                        <label for="file-upload" class="custom-file-upload">
                          <img id="image-preview" src="../uploaded_img/<?php echo $ProductData['productImage'] ?>" alt="Preview Image"
                            class="img-thumbnail" style="min-width: 250px; height:250px; cursor: pointer;">
                          <div class="mt-2 text-primary">Click to Update Picture</div>
                        </label>
                        <input id="file-upload" type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp"
                          style="display: none;" onchange="previewImage(this)">
                      </div>
                      <!-- Product Details -->
                      <div class="form-row">
                        <div class="row">
                        <div class="form-group col-md-6">
                          <label for="productName">Product Name</label>
                          <input type="text" id="productName" class="form-control" required maxlength="100"
                            value="<?php echo htmlspecialchars($ProductData['productName']) ?>"
                            placeholder="Enter product name" name="name">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="productPrice">Product Price</label>
                          <input type="number" id="productPrice" min="0" class="form-control" required max="9999999999"
                            value="<?php echo htmlspecialchars($ProductData['productPrice']) ?>"
                            placeholder="Enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
                        </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="productDetails">Product Details</label>
                        <textarea id="productDetails" name="details" class="form-control" rows="4" required maxlength="300"
                          placeholder="Enter product details"><?php echo htmlspecialchars($ProductData['productDetails']); ?></textarea>
                      </div>
                      <!-- Submit Button -->
                      <div class="text-left mt-2">
                        <input type="submit" value="Update Product" class="btn btn-primary btn-lg" name="update_product">
                      </div>
                    </form>


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

    // Image preview function
    function previewImage(input) {
      const preview = document.getElementById('image-preview');
      const file = input.files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        preview.src = e.target.result;
      };

      if (file) {
        reader.readAsDataURL(file);
      } else {
        preview.src = '../uploaded_img/<?php echo $ProductData['productImage'] ?>';
      }
    }

    document.getElementById('file-upload').addEventListener('change', function() {
      previewImage(this);
    });
  </script>

</body>

</html>