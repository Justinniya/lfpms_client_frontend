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

// Fetch and rank products based on sentiment
function getRankedProducts($conn, $msme_id, $sentiment)
{
  $sql = "SELECT p.product_id, p.productName, p.productPrice, p.productImage, 
                 COUNT(f.sentiment) AS sentiment_count
          FROM products p
          LEFT JOIN feedback f ON p.product_id = f.product_id
          WHERE p.msme_id = :msme_id AND f.sentiment = :sentiment
          GROUP BY p.product_id
          ORDER BY sentiment_count DESC";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':msme_id', $msme_id, PDO::PARAM_INT);
  $stmt->bindParam(':sentiment', $sentiment, PDO::PARAM_STR);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// HTML form for selecting MSME and sentiment
$msmeUsers = getMsmeUsers($conn);
$sentiments = ['Satisfied', 'Unsatisfied', 'Neutral'];

$selectedMsme = null;
$selectedSentiment = null;
$rankedProducts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selectedMsme = $_POST['msme'];
  $selectedSentiment = $_POST['sentiment'];

  // Fetch the ranked products based on selection
  $rankedProducts = getRankedProducts($conn, $selectedMsme, $selectedSentiment);
}


$sql1 = "SELECT COUNT(*) AS total_cons FROM users WHERE usertype = 3"; // Corrected query
$stmt1 = $conn->prepare($sql1);
$stmt1->execute();
$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
$conCount = $result1['total_cons']; // Store the count in a variable

$sql2 = "SELECT COUNT(*) AS total_msme FROM users WHERE usertype = 2"; // Corrected query
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
$msmeCount = $result2['total_msme']; // Store the count in a variable

$sql3 = "SELECT COUNT(*) AS total_product FROM products"; // Corrected query
$stmt3 = $conn->prepare($sql3);
$stmt3->execute();
$result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
$prodCount = $result3['total_product']; // Store the count in a variable

$sql4 = "SELECT COUNT(*) AS ongoing FROM consultancyquestionnaire  WHERE status BETWEEN 1 AND 4 OR status = 6"; // Corrected query
$stmt4 = $conn->prepare($sql4);
$stmt4->execute();
$result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
$ongCount = $result4['ongoing']; // Store the count in a variable

$sql5 = "SELECT COUNT(*) AS finished FROM consultancyquestionnaire  WHERE status = 7"; // Corrected query
$stmt5 = $conn->prepare($sql5);
$stmt5->execute();
$result5 = $stmt5->fetch(PDO::FETCH_ASSOC);
$finCount = $result5['finished']; // Store the count in a variable


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

      <div class="main-panel" style="margin-top: -20px;">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a style="margin-left:5px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview of Product Analysis</a>
                    </li>
                  </ul>
                </div>

                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                      <div class="col-sm-12">

                      </div>
                    </div>
                    <div class="row">
                      <div class="row">
                        <div class="col-md-3 mb-3">
                          <a href="msme.php" style="text-decoration: none;">
                            <div class="card">
                              <div class="card-body">
                                <div class="row">
                                  <div class="text-center mb-3">
                                    <div>
                                      <span><i style="font-size: 50px;" class="mdi mdi-account-outline"></i></span>
                                      <h3 class="card-title card-title-dash">MSME Count</h3>
                                    </div>
                                  </div>
                                  <div class="text-center">
                                    <h3><?php echo $msmeCount; ?></h3>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-md-3 mb-3">
                          <a href="consultant.php" style="text-decoration: none;">
                            <div class="card">
                              <div class="card-body">
                                <div class="row">
                                  <div class="text-center mb-3">
                                    <div>
                                      <span><i style="font-size: 50px;" class="mdi mdi-account-tie"></i></span>
                                      <h3 class="card-title card-title-dash">Consultant Count</h3>
                                    </div>
                                  </div>
                                  <div class="text-center">
                                    <h3><?php echo $conCount; ?></h3>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-md-3 mb-3">
                          <a href="#" style="text-decoration: none;">
                            <div class="card">
                              <div class="card-body">
                                <div class="row">
                                  <div class="text-center mb-3">
                                    <div>
                                      <span><i style="font-size: 50px;" class="mdi mdi-package-variant"></i></span>
                                      <h3 class="card-title card-title-dash">Total Product</h3>
                                    </div>
                                  </div>
                                  <div class="text-center">
                                    <h3><?php echo $prodCount; ?></h3>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-md-3 mb-3">
                          <a href="duration.php" style="text-decoration: none;">
                            <div class="card">
                              <div class="card-body">
                                <div class="row">
                                  <div class="text-center mb-3">
                                    <div>
                                      <span><i style="font-size: 50px;" class="mdi mdi-basket-check"></i></span>
                                      <h3 class="card-title card-title-dash">Developed Product</h3>
                                    </div>
                                  </div>
                                  <div class="text-center">
                                    <h3><?php echo $finCount; ?></h3>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </a>
                        </div>

                        <div class="col-md-4 mb-3">
                          
                        </div>
                        
                        <div class="col-md-4 mb-3">
                        <a href="productdevelopment.php" style="text-decoration: none;">
                          <div class="card">
                            <div class="card-body">
                              <div class="row mb-0">
                                <div class="text-center mb-3">
                                  <span><i style="font-size: 50px;" class="mdi mdi-timer-sand"></i></span>
                                  <h3 class="card-title card-title-dash">Ongoing Product Development</h3>
                                </div>
                                <div class="text-center">
                                    <h3><?php echo $ongCount; ?></h3>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </a>
                        </div>

                        <div class="col-md-4 mb-3">
                          
                        </div>


                        <div class="col-md-12 mb-4">
                          <div class="card">
                            <div class="card-body">
                              <form method="post" class="mb-4">
                                <div class="d-flex mb-2 justify-content-center">
                                  <h2>Sentiment Analysis & Feedback Tools</h2>
                                </div>
                                <div class="d-flex mb-4 justify-content-center gap-2">
                                  <a href="Sentientanalysis.php" class="btn btn-primary w-100 text-white">Sentiment Analysis</a>
                                  <a href="SentientDiagram.php" class="btn btn-primary w-100 text-white">View Diagram</a>
                                  <a href="msmeFeedBack.php" class="btn btn-primary w-100 text-white">Monitor MSME Product Feedback</a>
                                </div>

                                <!-- MSME Selection -->
                                <div class="form-group mt-4">
                                  <p><b>Note:</b> Select the MSME user to view their product ranking based on the sentiment result</p>
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

                                <!-- Sentiment Selection -->
                                <div class="form-group">
                                  <label for="sentiment">Select Sentiment:</label>
                                  <select style="color:black;" name="sentiment" id="sentiment" class="form-control" required onchange="this.form.submit()">
                                    <option value="">-- Select Sentiment --</option>
                                    <?php foreach ($sentiments as $sent): ?>
                                      <option value="<?= $sent ?>" <?= ($sent == $selectedSentiment) ? 'selected' : '' ?>>
                                        <?= $sent ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <!-- Results Card -->
                        <?php if (!empty($rankedProducts)): ?>
                          <div class="col-md-12">
                            <div class="card">
                              <div class="card-body">
                                <h4 class="mb-4 fw-bold text-primary">
                                  Ranking for <?= htmlspecialchars($selectedSentiment) ?> Sentiment
                                </h4>

                                <div class="table-responsive">
                                  <table id="dataTable" class="table custom-table">
                                    <thead>
                                      <tr>
                                        <th class="text-center">Ranking</th>
                                        <th>Product Name</th>
                                        <th class="text-center">Product Image</th>
                                        <th class="text-center">Total Review</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $ranking = 1;
                                      $previousCount = null;
                                      $displayRank = 1;

                                      foreach ($rankedProducts as $index => $product):
                                        if ($previousCount !== null && $product['sentiment_count'] !== $previousCount) {
                                          $displayRank = $index + 1;
                                        }
                                      ?>
                                        <tr>
                                          <td class="text-center"><?= $displayRank ?></td>
                                          <td class="fw-semibold"><?= htmlspecialchars($product['productName']) ?></td>
                                          <td class="text-center">
                                            <img src="./../uploaded_img/<?= htmlspecialchars($product['productImage']) ?>"
                                              alt="Product Image"
                                              class="product-img">
                                          </td>
                                          <td class="text-center"><?= htmlspecialchars($product['sentiment_count']) ?></td>
                                        </tr>
                                      <?php
                                        $previousCount = $product['sentiment_count'];
                                      endforeach;
                                      ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php elseif ($selectedMsme && $selectedSentiment): ?>
                          <div class="col-md-12">
                            <div class="card">
                              <div class="card-body text-center">
                                <div class="alert alert-warning" role="alert">
                                  No results found for this selection.
                                </div>
                              </div>
                            </div>
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
        <!-- page-body-wrapper ends -->
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