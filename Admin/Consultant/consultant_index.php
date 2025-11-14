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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="shortcut icon" href="assets/img/bb.png" />
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
                      <div class="col-sm-12">

                      </div>
                    </div>
                    <div class="row">
                      <!-- Search Card -->
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
        
        <?php include 'importantinclude/footer.php';?>

      </div>
    </div>
                        
    <?php include 'importantinclude/script.php';?>

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