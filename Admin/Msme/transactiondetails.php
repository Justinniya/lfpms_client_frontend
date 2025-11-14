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

if (isset($_GET['Id'])) {
  $transaction_id = $_GET['Id'];

  // Retrieve transaction details
  $select_transaction = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ? AND msme_id = ?");
  $select_transaction->execute([$transaction_id, $_SESSION['id']]);
  $fetch_transaction = $select_transaction->fetch(PDO::FETCH_ASSOC);

  if ($fetch_transaction) {
    $user_id = $fetch_transaction['user_id'];
    $transaction_date = $fetch_transaction['transaction_date'];

    // Retrieve user details
    $select_user = $conn->prepare("SELECT fname, Lname, email FROM users WHERE userid = ?");
    $select_user->execute([$user_id]);
    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);


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
        .qr-code {
          text-align: center;
          margin-top: 20px;
        }

        .qr-code img {
          max-width: 150px;
          height: auto;
        }

        .print-button {
          text-align: center;
          margin-top: 20px;
        }

        .print-button button {
          display: block;
          /* Ensure button is displayed */
          margin: 0 auto;
          /* Center button */
        }

        .print-button.hidden {
          display: none;
          /* Hide button */
        }

        /* Media query to hide print button when printing */
        @media print {
          .print-button {
            display: none !important;
          }
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
                        <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Transaction <span class="mdi mdi-arrow-right-thin"></span> Sales History <span class="mdi mdi-arrow-right-thin"></span> Details</a>
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
                          <div id="container" class="card-body">
                            <h2 class="mt-3" style="margin-left: 30px;">Transaction Details</h2>
                            <hr>
                            <div style="margin-left: 30px;">
                              <h4><strong>Transaction ID:</strong> <?= htmlspecialchars($fetch_transaction['transaction_id']); ?></h4>
                              <h4><strong>Customer Name:</strong> <?= $fetch_user ? htmlspecialchars($fetch_user['fname'] . ' ' . $fetch_user['Lname']) : "None"; ?></h4>
                              <h4><strong>Email:</strong> <?= $fetch_user ? htmlspecialchars($fetch_user['email']) : "None"; ?></h4>
                              <h4><strong>Transaction Date:</strong> <?= htmlspecialchars($transaction_date); ?></h4>
                            </div>

                            <?php
                            // Retrieve and display purchased products
                            $select_purchases = $conn->prepare("SELECT * FROM purchases NATURAL JOIN products WHERE transaction_id = ?");
                            $select_purchases->execute([$transaction_id]);

                            if ($select_purchases->rowCount() > 0) {
                              $total_quantity = 0;
                              $total_amount = 0;
                            ?>
                              <div style="margin-left: 30px; margin-top: 20px;">
                                <h2>Purchased Products</h2>
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th>Product Name</th>
                                      <th>Quantity</th>
                                      <th>Total Price</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    while ($fetch_purchase = $select_purchases->fetch(PDO::FETCH_ASSOC)) {
                                      $total_quantity += $fetch_purchase['quantity'];
                                      $total_amount += $fetch_purchase['total_price'];
                                      echo "<tr>";
                                      echo "<td>" . htmlspecialchars($fetch_purchase['productName']) . "</td>";
                                      echo "<td>" . htmlspecialchars($fetch_purchase['quantity']) . "</td>";
                                      echo "<td>₱" . htmlspecialchars($fetch_purchase['total_price']) . "</td>";
                                      echo "</tr>";
                                    }
                                    ?>
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <td><strong>Total:</strong></td>
                                      <td><strong><?= $total_quantity; ?></strong></td>
                                      <td><strong>₱<?= $total_amount; ?></strong></td>
                                    </tr>
                                  </tfoot>
                                </table>
                              <?php
                            } else {
                              echo "<p class='empty'>No products purchased in this transaction.</p>";
                            }
                              ?>
                              </div>
                              <!-- QR Code for Transaction ID -->
                              <div class="qr-code">
                                <?php
                                
                                // Generate QR Code URL
                                $qrValue = urlencode($transaction_id);
                                $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={$qrValue}";
                                echo '<img src="' . $qrCodeUrl . '" alt="Transaction QR Code">';
                                ?>
                                <br>
                                <br>
                                <a href="http://localhost/lfpms-main6/pages/SystemFeedback.php" target="_blank">We'd appreciate your feedback on our system</a>
                              </div>

                              <!-- Print Button -->

                              <div class="print-button">
                                <button class="btn btn-primary text-white" onclick="printContainer()" id="printBtn">Print Transaction Details</button>
                              </div>
                          <?php
                        } else {
                          echo "<p class='empty'>Transaction not found or unauthorized access.</p>";
                        }
                      } else {
                        echo "<p class='empty'>Transaction ID not specified.</p>";
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

        <script>
          function printContainer() {
            var printContents = document.querySelector('#container').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();

            document.body.innerHTML = originalContents;
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