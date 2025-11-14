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

// Function to search comments based on user input
function searchComments($conn, $searchTerm)
{
  $sql = "SELECT f.name AS customer_name, f.comment, f.sentiment, 
                 p.productName, p.productImage, 
                 u.fname AS msme_fname, u.Lname AS msme_lname
          FROM feedback f
          JOIN products p ON f.product_id = p.product_id
          JOIN users u ON p.msme_id = u.userid
          WHERE f.comment LIKE :searchTerm";

  $stmt = $conn->prepare($sql);
  $searchTerm = '%' . $searchTerm . '%'; // Prepare the search term for LIKE
  $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Initialize variables
$searchTerm = '';
$searchResults = [];

// Handle search input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $searchTerm = trim($_POST['search']); // Get the search term from the input
  $searchResults = searchComments($conn, $searchTerm); // Perform search
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
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
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
                      <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview of Product Analysis</a>
                    </li>
                  </ul>
                </div>
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                      <!-- Sentiment Analysis Search Card -->
                      <div class="col-md-12 mb-4">
                        <div class="card">
                          <div class="card-body">
                            <h3><b>Sentiment Analysis</b></h3>
                            <hr>
                            <h4 class="text-left mb-4">Search Comments</h4>
                            <p><b>Note:</b> Search the keyword in the comment to get the product sentiment analysis.</p>
                            <form method="post" class="text-center mb-4">
                              <label for="search" class="sr-only">Search Comments:</label>
                              <div class="input-group w-100 mx-auto">
                                <input type="text" name="search" id="search" class="form-control"
                                  value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search Comments" required>
                              </div>
                              <div class="mt-3 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary text-white w-25">Search</button>
                                <button type="button" class="btn btn-danger text-white w-25 ml-2" onclick="clearSearch()">Clear</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                      <!-- Search Results Card -->
                      <?php if (!empty($searchResults)): ?>
                        <div class="col-md-12">
                          <div class="card">
                            <div class="card-body">
                              <div class="text-end">
                                <button class="btn btn-primary text-white" onclick="printVerticalTable()">Print Table</button>
                              </div>
                              <hr>

                              <h4 class="text-center mb-2">Search Results for: <strong><?= htmlspecialchars($searchTerm) ?></strong></h4>
                              <div class="table-responsive">
                                <table id="dataTable" class="table custom-table">
                                  <thead>
                                    <tr>
                                      <th>MSME</th>
                                      <th>Product Name</th>
                                      <th>Product Image</th>
                                      <th>Customer Name</th>
                                      <th>Comment</th>
                                      <th>Sentiment</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php foreach ($searchResults as $result): ?>
                                      <tr>
                                        <td><?= htmlspecialchars($result['msme_fname'] . ' ' . $result['msme_lname']) ?></td>
                                        <td><?= htmlspecialchars($result['productName']) ?></td>
                                        <td><img src="./../uploaded_img/<?= htmlspecialchars($result['productImage']) ?>"
                                            alt="Product Image" class="img-fluid" style="max-width: 100px;"></td>
                                        <td><?= htmlspecialchars($result['customer_name']) ?></td>
                                        <td><?= htmlspecialchars($result['comment']) ?></td>
                                        <td><?= htmlspecialchars($result['sentiment']) ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                          <div class="alert alert-warning text-center" role="alert">
                            No results found for: <strong><?= htmlspecialchars($searchTerm) ?></strong>
                          </div>
                        <?php endif; ?>
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

    <script>
      function printVerticalTable() {
        var table = document.getElementById("dataTable");
        var headers = Array.from(table.querySelectorAll("thead th")).map(th => th.innerText);
        var rows = Array.from(table.querySelectorAll("tbody tr")).map(row =>
          Array.from(row.querySelectorAll("td")).map(td => td.innerHTML)
        );

        var printContent = `
      <html>
      <head>
        <title>Print Table</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
          }
          .table-container {
            width: 100%;
            border-collapse: collapse;
          }
          .table-container tr {
            border-bottom: 1px solid #ddd;
          }
          .table-container th, .table-container td {
            padding: 10px;
            text-align: left;
          }
          .table-container th {
            background-color: #f2f2f2;
            width: 30%;
          }
          img {
            width: 100px; /* Fixed width */
            height: 100px; /* Fixed height */
            object-fit: cover; /* Maintain aspect ratio and fill box */
            display: block;
            border-radius: 15px;
          }
        </style>
      </head>
      <body>
        <h2>Sentiment Analysis Report</h2>
        <p>Generated on: ${new Date().toLocaleString()}</p>
        <hr>
    `;

        rows.forEach((row, index) => {
          printContent += `<h3>Entry ${index + 1}</h3><table class="table-container">`;
          row.forEach((cell, i) => {
            printContent += `
          <tr>
            <th>${headers[i]}</th>
            <td>${cell}</td>
          </tr>
        `;
          });
          printContent += `</table><br>`;
        });

        printContent += `</body></html>`;

        var newWindow = window.open("", "_blank");
        newWindow.document.write(printContent);
        newWindow.document.close();
        newWindow.print();
      }
    </script>



</body>

</html>