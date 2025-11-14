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

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['total'])) {
    $total = floatval($_POST['total']); // Get the total from AJAX request
    echo number_format($total, 2); // Return formatted total
    exit; // Stop further execution
  }

  // Fetch MSME users (usertype = 2)
  function getMsmeUsers($conn)
  {
    $sql = "SELECT userid, fname, Lname FROM users WHERE usertype = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  if (isset($_POST['add_transaction'])) {
    $user_id = isset($_POST['user_id']) ? filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT) : null;

    $products = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $total_prices = $_POST['total_price'];

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $prefix = 'A';

    $count_transactions = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE DATE(transaction_date) = ?");
    $count_transactions->execute([$date]);
    $transaction_count = $count_transactions->fetchColumn() + 1;

    $transaction_id = "{$prefix}-{$date}-{$transaction_count}-{$time}";

    $total_quantity = array_sum($quantities);
    $total_transaction_price = array_sum($total_prices);

    $conn->beginTransaction();
    try {
      $insert_transaction = $conn->prepare("INSERT INTO transactions (transaction_id, msme_id, user_id, quantity, total_price, transaction_date) VALUES (?, ?, ?, ?, ?, NOW())");
      $insert_transaction->execute([$transaction_id, $_SESSION['id'], $user_id, $total_quantity, $total_transaction_price]);

      foreach ($products as $index => $product_id) {
        $quantity = filter_var($quantities[$index], FILTER_SANITIZE_NUMBER_INT);
        $product_total_price = filter_var($total_prices[$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $insert_purchase = $conn->prepare("INSERT INTO purchases (transaction_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        $insert_purchase->execute([$transaction_id, $product_id, $quantity, $product_total_price]);
      }

      $conn->commit();

      // Store success message in session
      $_SESSION['success_message'] = "Transaction added successfully! Transaction ID: " . $transaction_id;

      // Redirect
      header("Location: transaction.php");
      exit();
    } catch (Exception $e) {
      $conn->rollBack();
      $_SESSION['error_message'] = "Failed to add transaction: " . $e->getMessage(). $transaction_id;
      header("Location: transaction.php");
      exit();
    }
  }


  if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $delete_transaction = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
    $delete_transaction->execute([$delete_id]);

    header('location: transaction.php');
    exit();
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
    <style>
      /* Header Section */
      .heading {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #222;
        text-align: center;
        background: #007bff;
        color: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      /* Flex Layout for Inputs */
      .flex {
        display: flex;
        gap: 15px;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
      }

      /* Input Box */
      .inputBox {
        flex: 1;
      }

      .box {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
      }

      .btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 18px;
        text-align: center;
        font-size: 16px;
        cursor: pointer;
        border-radius: 6px;
        transition: 0.3s;
      }

      /* Buttons */
      .btn-danger {
        background-color: rgb(217, 8, 8);
        color: white;
        border: none;
        padding: 10px 18px;
        text-align: center;
        font-size: 16px;
        cursor: pointer;
        border-radius: 6px;
        transition: 0.3s;
      }

      /* Table Styles */
      .purchase-table,
      .transactions-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .purchase-table th,
      .transactions-table th {
        background: #007bff;
        color: white;
        padding: 14px;
        text-align: center;
      }

      .purchase-table td,
      .transactions-table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
      }

      .transactions-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
      }

      /* Buttons inside Table */
      .add-btn,
      .View-btn,
      .remove-btn {
        border: none;
        color: white;
        padding: 8px 14px;
        cursor: pointer;
        border-radius: 6px;
        transition: 0.3s;
      }

      .add-btn {
        background-color: #007bff;
      }

      .add-btn:hover {
        background-color: #0056b3;
      }

      .View-btn {
        background-color: #17a2b8;
      }

      .View-btn:hover {
        background-color: #138496;
      }

      .remove-btn {
        background-color: #dc3545;
      }

      .remove-btn:hover {
        background-color: #c82333;
      }

      /* Responsive Table */
      .table-container {
        overflow-x: auto;
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
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Transaction <span class="mdi mdi-arrow-right-thin"></span> Point of Sales(POS)</a>
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
                          <h2>Point of Sale (POS)</h2>
                          <hr>
                          <?php
                          if (isset($_SESSION['success_message'])) {
                            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                            unset($_SESSION['success_message']);
                          }

                          if (isset($_SESSION['error_message'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                            unset($_SESSION['error_message']);
                          }
                          ?>
                          <form action="" method="post" enctype="multipart/form-data" id="transactionForm">
                            <div class="row">
                              <div class="inputBox">
                                <div class="text-start mb-1">
                                  <span>Select User (optional)</span>
                                </div>
                                <select name="user_id" class="box">
                                  <option value="" disabled selected>Select a user</option>
                                  <?php
                                  $select_users = $conn->prepare("SELECT userid, fname, Lname FROM users where usertype=1");
                                  $select_users->execute();
                                  while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . htmlspecialchars($fetch_users['userid']) . '">' . htmlspecialchars($fetch_users['fname']) . '&nbsp;' . htmlspecialchars($fetch_users['Lname']) . '</option>';
                                  }
                                  ?>
                                </select>
                              </div>
                              <div class="inputBox">
                                <div class="text-start mb-1">
                                  <span>Purchased Product (required)</span>
                                </div>
                                <select id="product-select" class="box product-select">
                                  <option value="" disabled selected>Select a product</option>
                                  <?php
                                  $select_products = $conn->prepare("SELECT product_id, productName, productPrice FROM products WHERE msme_id = ?");
                                  $select_products->execute([$_SESSION['id']]);
                                  while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . htmlspecialchars($fetch_products['product_id']) . '" data-price="' . htmlspecialchars($fetch_products['productPrice']) . '">' . htmlspecialchars($fetch_products['productName']) . '</option>';
                                  }
                                  ?>
                                </select>
                                <div class="text-end mb-3">
                                  <button type="button" class="btn btn-primary mt-3 text-white" onclick="addProduct()">Add Product</button>
                                </div>
                              </div>
                            </div>
                            <table id="purchaseTable" class="table table-bordered table-striped">
                              <thead class="table-dark">
                                <tr>
                                  <th>Product Name</th>
                                  <th>Quantity</th>
                                  <th>Total Price</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>

                            <hr>
                            <div class="grand-total text-end">
                              <h4>
                                Total:
                              </h4>
                            </div>
                            <div class="text-end">
                              <input type="submit" value="Add Transactions" class="btn btn-primary mt-3 text-white" name="add_transaction">
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
      <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
          const preview = document.getElementById('imagePreview');
          const file = input.files[0];
          const reader = new FileReader();

          reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Product Image" class="img-fluid rounded">`;
          };

          if (file) {
            reader.readAsDataURL(file);
          } else {
            preview.innerHTML = '<span class="text-muted">No image selected</span>';
          }
        }

        document.getElementById('image_01').addEventListener('change', function() {
          previewImage(this);
        });
      </script>
      <script>
        function addProduct() {
          const productSelect = document.getElementById('product-select');
          const productId = productSelect.value;
          const productName = productSelect.options[productSelect.selectedIndex].text;
          const productPrice = productSelect.options[productSelect.selectedIndex].getAttribute('data-price');

          if (!productId) return;

          const purchaseTableBody = document.getElementById('purchaseTable').querySelector('tbody');
          const newRow = document.createElement('tr');
          newRow.classList.add('purchase-row');
          newRow.innerHTML = `
                  <td>
                      <input type="hidden" name="product_id[]" value="${productId}">
                      <span>${productName}</span>
                  </td>
                  <td>
                      <input type="number" name="quantity[]" min="1" value="1" class="form-control quantity-box" onchange="calculateTotalPrice(this, ${productPrice})">
                  </td>
                  <td>
                      <input type="text" name="total_price[]" value="${productPrice}" readonly class="form-control total-price-box">
                  </td>
                  <td class="text-center">
                      <button type="button" class="btn btn-danger text-white" onclick="removeProduct(this)">Remove</button>
                  </td>
              `;
          purchaseTableBody.appendChild(newRow);

          productSelect.selectedIndex = 0;
          updateGrandTotal(); // Update total on UI and send to PHP // Reset the product select
        }

        function calculateTotalPrice(inputElement, productPrice) {
          const quantity = inputElement.value;
          const totalPriceInput = inputElement.closest('.purchase-row').querySelector('.total-price-box');
          totalPriceInput.value = (quantity * productPrice).toFixed(2);
          updateGrandTotal();

        }

        function removeProduct(buttonElement) {
          const row = buttonElement.closest('.purchase-row');
          row.remove();
        }

        document.getElementById('transactionForm').addEventListener('submit', function(event) {
          const purchaseRows = document.querySelectorAll('.purchase-row');
          if (purchaseRows.length === 0) {
            alert('Please add at least one product.');
            event.preventDefault();
          }
        });
      
        function updateGrandTotal() {
          let grandTotal = 0;

          document.querySelectorAll('.total-price-box').forEach(input => {
            grandTotal += parseFloat(input.value) || 0;
          });

          console.log("Sending total:", grandTotal); // Debugging

          $.ajax({
            url: 'transaction.php',
            type: 'POST',
            data: {
              total: grandTotal
            },
            success: function(response) {
              console.log("Response received:", response); // Debugging
              $('.grand-total h4').html(`Total: ₱${response}`);
            },
            error: function(xhr, status, error) {
              console.error("AJAX Error:", status, error); // Debugging
            }
          });
        }
      </script>

      <script>
        document.addEventListener("DOMContentLoaded", function() {
          setTimeout(function() {
            let alerts = document.querySelectorAll(".alert");
            alerts.forEach(alert => {
              alert.style.transition = "opacity 0.5s";
              alert.style.opacity = "0";
              setTimeout(() => alert.remove(), 500); // Remove the element after fade-out
            });
          }, 1000); // 1 second delay before fading out
        });
      </script>

  </body>

  </html>