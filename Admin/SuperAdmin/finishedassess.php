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
  $id = $_GET['id'];

  $query = "Select * from consultancyquestionnaire WHERE id = :id";
  $statement = $conn->prepare($query);
  $statement->bindParam(':id', $id, PDO::PARAM_INT);
  $statement->execute();
  $Data = $statement->fetch(PDO::FETCH_ASSOC);
}

$selectedSize = $Data['Size'];

$selectedSize = isset($Data['Size']) ? $Data['Size'] : '';
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
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">Product Development Duration <span class="mdi mdi-arrow-right-thin"> Assessed Product </span></a>
                    </li>
                  </ul>
                </div>
                <div class="col-lg-12 grid-margin stretch-card mt-3">
                  <div class="card">
                    <div class="card-body">
                      <form id="colorForm" method="POST" action="consultancyQuestionaire.php" onsubmit="return validateForm()" enctype="multipart/form-data">
                        <h3 class="mb-2 pt-4 d-flex flex-column"><b>Assessed Data</b></h3>
                       
                        <hr>
                        <div class="form-row">
                          <input type="hidden" name="userid" id="userid" value="<?php echo $Id ?>" required />
                        </div>

                        <h3 class="mt-4 mb-4">MSME's Personal Information.</h3>

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($Data['name']) ? $Data['name'] : ''; ?>" required readonly />
                          </div>
                          <div class="form-group col-md-6">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" name="address" id="address" value="<?php echo isset($Data['address']) ? $Data['address'] : ''; ?>" required readonly />
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label for="municipality">Municipality</label>
                            <input type="text" class="form-control" name="municipality" id="municipality" value="<?php echo isset($Data['municipality']) ? $Data['municipality'] : ''; ?>" required readonly />
                          </div>
                          <div class="form-group col-md-6">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" value="<?php echo isset($Data['phoneNumber']) ? $Data['phoneNumber'] : ''; ?>" required readonly />
                          </div>
                        </div>

                        <h3 class="mt-4 mb-4">MSME's Product Information.</h3>

                        <div class="row">
                          <div class="form-group col-md-4">
                            <label for="product">Name of Product</label>
                            <input type="text" class="form-control" name="product" id="product" value="<?php echo isset($Data['ProductName']) ? $Data['ProductName'] : ''; ?>" required readonly />
                          </div>
                          <div class="form-group col-md-4">
                            <label for="labelingformat">Labeling Format (stick on label, header, etc)</label>
                            <input type="text" class="form-control" name="labelingformat" id="labelingformat" value="<?php echo isset($Data['labelingFormat']) ? $Data['labelingFormat'] : ''; ?>" required readonly />
                          </div>
                          <div class="form-group col-md-4">
                            <label for="brandName">Brand Name (ex. Coca-cola, Milo, Mt.Dew)</label>
                            <input type="text" class="form-control" name="brandName" id="brandName" value="<?php echo isset($Data['brandName']) ? $Data['brandName'] : ''; ?>" required readonly />
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="productIdentity">Product Identity Name: True Name/Nature of the Food (ex. salted Peanut, Dried Mango)</label>
                          <input type="text" class="form-control" name="productIdentity" id="productIdentity" value="<?php echo isset($Data['productIdentity']) ? $Data['productIdentity'] : ''; ?>" required readonly />
                        </div>

                        <div class="row">
                          <h6 class="mt-4 mb-4">Name of Product's (If 1 label with 2 or more product selection).</h6>
                          <div class="form-group col-md-6">
                            <label for="label1">Product 1</label>
                            <input type="text" class="form-control" name="label1" id="label1" value="<?php echo isset($Data['label1']) ? $Data['label1'] : ''; ?>" required readonly />
                          </div>
                          <div class="form-group col-md-6">
                            <label for="label2">Product 2</label>
                            <input type="text" class="form-control" name="label2" id="label2" value="<?php echo isset($Data['label2']) ? $Data['label2'] : ''; ?>" required readonly />
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label for="label3">Product 3</label>
                            <input type="text" class="form-control" name="label3" id="label3" value="<?php echo isset($Data['label3']) ? $Data['label3'] : ''; ?>" required readonly />
                          </div>
                          <div class="form-group col-md-6">
                            <label for="label4">Product 4</label>
                            <input type="text" class="form-control" name="label4" id="label4" value="<?php echo isset($Data['label4']) ? $Data['label4'] : ''; ?>" required readonly />
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-3"></div>
                          <div class="form-group col-md-3">
                            <label for="tagline">Tagline (optional)</label>
                            <input type="text" class="form-control" name="tagline" id="tagline" value="<?php echo isset($Data['tagline']) ? $Data['tagline'] : ''; ?>" required readonly />
                          </div>
                          <div class="form-group col-md-3">
                            <label for="netContent">Net Content (kg, g, ml, etc.)</label>
                            <input type="text" class="form-control" name="netContent" id="netContent" value="<?php echo isset($Data['netContent']) ? $Data['netContent'] : ''; ?>" required readonly />
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="ingredients">Ingredients (from most to least quantity)</label>
                          <textarea name="ingredients" class="form-control" rows="3" disabled><?php echo isset($Data['ingredients']) ? $Data['ingredients'] : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                          <label for="expiryDate">Expiry Date of the Product</label>
                          <input type="date" class="form-control" name="expiryDate" id="expiryDate" value="<?php echo isset($Data['expiryDate']) ? $Data['expiryDate'] : ''; ?>" required readonly />
                        </div>

                        <div class="row">
                          <div class="form-group col-md-6">
                            <label for="ProductDirect">Direction of the Product</label>
                            <textarea name="ProductDirect" class="form-control" rows="3" disabled><?php echo isset($Data['DirectProduct']) ? $Data['DirectProduct'] : ''; ?></textarea>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="ConceptDesign">Concept of Design</label>
                            <textarea name="ConceptDesign" class="form-control" rows="3" disabled><?php echo isset($Data['ConceptDesign']) ? $Data['ConceptDesign'] : ''; ?></textarea>
                          </div>
                        </div>

                        <h4>Size of the Product</h4>
                        <div class="form-group" style="margin-left: 30px;">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="size" value="small" id="sizeSmall" <?php if ($selectedSize == 'small') echo 'checked'; ?> disabled>
                            <label class="form-check-label" for="sizeSmall">Small</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="size" value="medium" id="sizeMedium" <?php if ($selectedSize == 'medium') echo 'checked'; ?> disabled>
                            <label class="form-check-label" for="sizeMedium">Medium</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="size" value="large" id="sizeLarge" <?php if ($selectedSize == 'large') echo 'checked'; ?> disabled>
                            <label class="form-check-label" for="sizeLarge">Large</label>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="DominantColor">Dominant Color to be used</label>
                          <input type="text" class="form-control" name="DominantColor" id="DominantColor" value="<?php echo isset($Data['DominantColor']) ? $Data['DominantColor'] : ''; ?>" placeholder="Dominant Color to be used" required readonly />
                        </div>
                        <div class="form-group">
                          <label for="Comment">Notes or Other Comments</label>
                          <textarea name="Comment" class="form-control" rows="3" disabled><?php echo isset($Data['Comment']) ? $Data['Comment'] : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                          <input type="hidden" id="pickedColors" name="SelectedColor">
                          <label for="displayColors">Selected Colors</label>
                          <input type="text" class="form-control" id="displayColors" value="<?php echo isset($Data['SelectedColor']) ? $Data['SelectedColor'] : ''; ?>" readonly>
                        </div>

                        <div class="form-group">
                          <table id="colorTable">

                          </table>
                        </div>

                        <div class="form-group img-container">
                          <label style="font-size: 30px; font-weight: bold; margin-bottom:25px;" for="uploadedImage">Uploaded Draft Design</label>
                          <?php if (!empty($Data['draft_img'])) { ?>
                            <div>
                              <img src="../uploaded_img/<?php echo $Data['draft_img']; ?>" alt="Draft Image" class="img-fluid">
                            </div>
                          <?php } else { ?>
                            <p>No image uploaded</p>
                          <?php } ?>
                        </div>
                      </form>
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