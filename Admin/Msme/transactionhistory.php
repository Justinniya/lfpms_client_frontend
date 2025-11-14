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

if (isset($_POST['add_transaction'])) {
  $user_id = isset($_POST['user_id']) ? filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT) : null;

  $products = $_POST['product_id'];
  $quantities = $_POST['quantity'];
  $total_prices = $_POST['total_price'];

  // Generate the transaction ID
  $date = date('Y-m-d');
  $prefix = 'A';

  // Count the number of transactions for today
  $count_transactions = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE DATE(transaction_date) = ?");
  $count_transactions->execute([$date]);
  $transaction_count = $count_transactions->fetchColumn() + 1; // Increment for the new transaction

  $transaction_id = "{$prefix}-{$date}-{$transaction_count}";

  // Calculate total quantity and total price
  $total_quantity = array_sum($quantities);
  $total_transaction_price = array_sum($total_prices);

  // Insert into transactions table
  $conn->beginTransaction();
  try {
    $insert_transaction = $conn->prepare("INSERT INTO transactions (transaction_id, msme_id, user_id, quantity, total_price, transaction_date) VALUES (?, ?, ?, ?, ?, NOW())");
    $insert_transaction->execute([$transaction_id, $_SESSION['id'], $user_id, $total_quantity, $total_transaction_price]);

    // Insert into purchases table
    foreach ($products as $index => $product_id) {
      $quantity = filter_var($quantities[$index], FILTER_SANITIZE_NUMBER_INT);
      $product_total_price = filter_var($total_prices[$index], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

      $insert_purchase = $conn->prepare("INSERT INTO purchases (transaction_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
      $insert_purchase->execute([$transaction_id, $product_id, $quantity, $product_total_price]);
    }

    $conn->commit();
    echo '<script>alert("Transaction added successfully! Transaction ID: ' . $transaction_id . '");</script>';
    echo '<script>window.location.href = "Transaction.php";</script>';
  } catch (Exception $e) {
    $conn->rollBack();
    echo '<script>alert("Failed to add transaction: ' . $e->getMessage() . '");</script>';
  }
}

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];

  $delete_transaction = $conn->prepare("DELETE FROM transactions WHERE transaction_id = ?");
  $delete_transaction->execute([$delete_id]);

  header('location: Transaction.php');
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
    /* Import Google Font */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

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

    <div class="container-fluid page-body-wrapper">
     
      <?php include 'importantinclude/sidebar.php'; ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
         
          <div class="row">
            <div class="home-tab">
              <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Transaction <span class="mdi mdi-arrow-right-thin"></span> Sales History</a>
                  </li>
                </ul>
              </div>
              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-sm-12">

                    </div>
                  </div>
            <!-- Search Card -->
            <div class="col-md-12 mb-4">
              <div class="card">
                <div class="card-body">
                    <div class="table-container">
                      <h2>Sales History</h2>
                      <hr>
                      <?php
                      $select_transactions = $conn->prepare("SELECT * FROM transactions WHERE msme_id = ? ORDER BY transaction_date DESC");
                      $select_transactions->execute([$_SESSION['id']]);

                      if ($select_transactions->rowCount() > 0) {
                      ?>
                        <table id="dataTable" class="table custom-table">
                          <thead>
                            <tr>
                              <th>Transaction ID</th>
                              <th>Full Name</th>
                              <th>Item Quantity</th>
                              <th>Total Amount</th>
                              <th>Transaction Date</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            while ($fetch_transactions = $select_transactions->fetch(PDO::FETCH_ASSOC)) {
                              $user_id = $fetch_transactions['user_id'];
                              $select_user = $conn->prepare("SELECT fname, Lname, email FROM users WHERE userid = ?");
                              $select_user->execute([$user_id]);
                              $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
                            ?>
                              <tr>
                                <td><?= htmlspecialchars($fetch_transactions['transaction_id']); ?></td>
                                <td><?= $fetch_user ? htmlspecialchars($fetch_user['fname'] . ' ' . $fetch_user['Lname']) : "None"; ?></td>
                                <td><?= htmlspecialchars($fetch_transactions['quantity']); ?></td>
                                <td>₱<?= htmlspecialchars($fetch_transactions['total_price']); ?>/-</td>
                                <td><?= htmlspecialchars($fetch_transactions['transaction_date']); ?></td>
                                <td>
                                  <a href="transactiondetails.php?Id=<?= htmlspecialchars($fetch_transactions['transaction_id']); ?>" target="blank" class="btn btn-primary text-white">View Details</a>
                                </td>
                              </tr>
                            <?php
                            }
                            ?>
                          </tbody>
                        </table>
                      <?php
                      } else {
                        echo '<p class="empty">No transactions added yet!</p>';
                      }
                      ?>
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
        order: [[4, 'desc']],
        responsive: true,
        autoWidth: false,
        lengthMenu: [
          [5, 15, 25, -1],
          [5, 15, 25, "All"]
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
                    <button type="button" class="btn btn-danger" onclick="removeProduct(this)">Remove</button>
                </td>
            `;
      purchaseTableBody.appendChild(newRow);

      productSelect.selectedIndex = 0; // Reset the product select
    }

    function calculateTotalPrice(inputElement, productPrice) {
      const quantity = inputElement.value;
      const totalPriceInput = inputElement.closest('.purchase-row').querySelector('.total-price-box');
      totalPriceInput.value = (quantity * productPrice).toFixed(2);
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
  </script>

</body>

</html>