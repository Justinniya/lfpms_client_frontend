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

// Fetch product list for the filter
try {
    $productQuery = "SELECT product_id, productName FROM products WHERE msme_id = ?";
    $stmt = $conn->prepare($productQuery);
    $stmt->execute([$_SESSION['id']]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Initialize feedback data
$feedbackData = [];

try {
    $selectedProductId = isset($_GET['product_id']) ? $_GET['product_id'] : ($products ? $products[0]['product_id'] : null);

    if ($selectedProductId) {
        $query = "SELECT f.category,
                         SUM(CASE WHEN f.rating_value >= 3 THEN 1 ELSE 0 END) AS satisfied_count,
                         SUM(CASE WHEN f.rating_value = 2 THEN 1 ELSE 0 END) AS neutral_count,
                         SUM(CASE WHEN f.rating_value <= 1 THEN 1 ELSE 0 END) AS unsatisfied_count
                  FROM feedback AS f
                  INNER JOIN products AS p ON f.product_id = p.product_id
                  WHERE f.product_id = :product_id
                  AND p.msme_id = :logged_in_user_id
                  GROUP BY f.category";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':product_id', $selectedProductId, PDO::PARAM_INT);
        $stmt->bindParam(':logged_in_user_id', $_SESSION['id'], PDO::PARAM_INT);
        $stmt->execute();
        $feedbackData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Add categories with zero counts if not present in the data
        $categories = ['Quality', 'Labeling', 'Packaging', 'Logo', 'Taste'];
        foreach ($categories as $category) {
            $found = false;
            foreach ($feedbackData as $item) {
                if ($item['category'] === $category) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $feedbackData[] = [
                    'category' => $category,
                    'satisfied_count' => 0,
                    'neutral_count' => 0,
                    'unsatisfied_count' => 0
                ];
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>LFPMS</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="shortcut icon" href="assets/img/bb.png" />
    <link rel="stylesheet" href="../assets/css/style.css">

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
                        <div class="col-sm-12">
                            <div class="home-tab">
                                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a style="margin-left:5px; margin-top: -35px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview of Product Feedback <span class="mdi mdi-arrow-right-thin"></span> Product Feedback Diagram</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content tab-content-basic">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                        <div class="row">
                                            <div class="col-md-12 mb-4">
                                                <div class="card">                                               
                                                    <div class="card-body">
                                                    <h4 class="card-title">Product Feedback Diagram</h4>
                                                    <hr class="mb-4">
                                                        <div class="btnAdd d-flex justify-content-end">
                                                            <a href="productFeedBack.php" class="btn btn-success btn-sm text-white">Go Back</a>
                                                        </div>
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

                                                        <?php if ($selectedProductId && $feedbackData): ?>
                                                            <h5 class="mt-4">Feedback Summary for <?= htmlspecialchars($products[array_search($selectedProductId, array_column($products, 'product_id'))]['productName']) ?></h5>
                                                            <canvas id="feedbackChart"></canvas>
                                                        <?php elseif ($selectedProductId): ?>
                                                            <p>No feedback found for this product.</p>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>
                                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Get feedback data from PHP and convert it to JavaScript array
        var feedbackData = <?php echo json_encode($feedbackData); ?>;

        // Extract category names and counts for the chart
        var categories = feedbackData.map(item => item.category);
        var satisfiedCounts = feedbackData.map(item => Math.round(item.satisfied_count)); // Ensure whole numbers
        var neutralCounts = feedbackData.map(item => Math.round(item.neutral_count));
        var unsatisfiedCounts = feedbackData.map(item => Math.round(item.unsatisfied_count));

        // Create chart data
        var chartData = {
            labels: categories,
            datasets: [{
                label: 'Satisfied',
                data: satisfiedCounts,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 1
            }, {
                label: 'Neutral',
                data: neutralCounts,
                backgroundColor: 'rgba(255, 206, 86, 0.5)',
                borderWidth: 1
            }, {
                label: 'Unsatisfied',
                data: unsatisfiedCounts,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderWidth: 1
            }]
        };

        // Get the canvas element
        var ctx = document.getElementById('feedbackChart').getContext('2d');

        // Create the chart
        var feedbackChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0 // Force whole numbers
                        }
                    }
                }
            }
        });
    </script>

    </div>
</body>

</html>