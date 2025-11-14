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

try {
    // Assuming $conn is your existing PDO connection
    $stmt = $conn->prepare("SELECT `product_id`, `productName` FROM `products` WHERE `msme_id` = :msme_id");
    $stmt->bindParam(':msme_id', $Id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all products for the current user
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$query = "
    SELECT `id`,`status`, `product_id` 
    FROM `consultancyquestionnaire` 
    WHERE user_id = :user_id AND `status` IN (1, 2, 3, 4, 44) 
    ORDER BY `SubmittionDate` DESC 
    LIMIT 1
";
$stmt = $conn->prepare($query);
$stmt->execute(['user_id' => $Id]);
$status12 = $stmt->fetch(PDO::FETCH_ASSOC);

// Access the status and product ID
$status = $status12['status'] ?? null;
$productId = $status12['product_id'] ?? null;
$consultationID = $status12['id'] ?? null;


// Check for status 0
$queryStatus0 = "SELECT `status` FROM `consultancyquestionnaire` WHERE user_id = :user_id AND status = 0";
$stmtStatus0 = $conn->prepare($queryStatus0);
$stmtStatus0->execute(['user_id' => $Id]);
$status0 = $stmtStatus0->fetch(PDO::FETCH_ASSOC);

// Form submission for deleting status 0 records
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare delete query to remove the record where status is 0
    $deleteQuery = "DELETE FROM `consultancyquestionnaire` WHERE user_id = :user_id AND status = 0";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute(['user_id' => $Id]);

    // Redirect after deletion to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
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
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="msme_index.php">
                        <img src="assets/img/bb.png" alt="">
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <h5>Welcome Msme, <span class="text-black fw-bold"><?= htmlspecialchars($loggedInUser['fname']); ?>!</span></h5>
                    </li>
                    <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="img-xs rounded-circle" src="../assets/images/faces/face8.jpg" alt="Profile image"> </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <img class="img-md rounded-circle" src="../assets/images/faces/face8.jpg" alt="Profile image">
                                <p class="mb-1 mt-3 fw-semibold">Allen Moreno</p>
                                <p class="fw-light text-muted mb-0">allenmoreno@gmail.com</p>
                            </div>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile <span class="badge badge-pill badge-danger">1</span></a>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Messages</a>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Activity</a>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a>
                            <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
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
                                    <div class="w3-main" id="mainContent" style="margin-left:250px;margin-top:43px;">
                                        <!-- Check if status is 1 or 2 --> <?php if ($status12): ?> <br>
                                            <div class="w3-margin card w3-white w3-padding">
                                                <h2><b>Note: You can only submit one assessment at a time.</b></h2>
                                                <?php if ($status12['status'] == 1): ?> <h3 class="mb-2 pt-4 d-flex flex-column"><b>Stage 1: Assessment</b></h3>
                                                    <div class="progress mb-3 ">
                                                        <div class="progress-bar" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">10%</div>
                                                    </div>
                                                    <p><b>Status:</b> Waiting for Approval</p>
                                                <?php elseif ($status12['status'] == 2): ?>
                                                    <h4 class="mb-2 pt-4 d-flex flex-column"><b>Stage 2: Communication</b></h4>
                                                    <div class="progress mb-3 ">
                                                        <div class="progress-bar" role="progressbar" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">40%</div>
                                                    </div>
                                                    <p><b>Status:</b> Communicating to Consultant</p>
                                                <?php elseif ($status12['status'] == 44): ?>
                                                    <h4 class="mb-2 pt-4 d-flex flex-column"><b>Stage 3: Consultant Report</b></h4>
                                                    <div class="progress mb-3 ">
                                                        <div class="progress-bar" role="progressbar" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">40%</div>
                                                    </div>
                                                    <p><b>Status:</b> Waiting for Approval of Consultation Report</p>
                                                <?php elseif ($status12['status'] == 3):
                                                                                    $showDiv = true; // Flag to control the visibility of the div

                                                                                    $query = "
                                SELECT 
                                cr.ConReport_ID, 
                                cr.consultationID, 
                                cr.ConceptDesign AS ReportConceptDesign, 
                                cr.size AS ReportSize, 
                                cr.Comment AS ReportComment, 
                                cr.DominantColor AS ReportDominantColor, 
                                cr.SelectedColor AS ReportSelectedColor, 
                                cr.draft_img AS ReportDraftImage, 
                                cr.room_id, 
                                cr.submission_datetime, 
                                cq.id AS QuestionnaireID, 
                                cq.user_id, 
                                cq.name, 
                                cq.address, 
                                cq.municipality, 
                                cq.phoneNumber, 
                                cq.labelingFormat, 
                                cq.brandName, 
                                cq.ProductName, 
                                cq.productIdentity, 
                                cq.label1, 
                                cq.label2, 
                                cq.label3, 
                                cq.label4, 
                                cq.tagline, 
                                cq.netContent, 
                                cq.ingredients, 
                                cq.expiryDate, 
                                cq.DirectProduct, 
                                cq.ConceptDesign AS QuestionnaireConceptDesign, 
                                cq.Size AS QuestionnaireSize, 
                                cq.DominantColor AS QuestionnaireDominantColor, 
                                cq.Comment AS QuestionnaireComment, 
                                cq.SelectedColor AS QuestionnaireSelectedColor, 
                                cq.SubmittionDate, 
                                cq.draft_img AS QuestionnaireDraftImage, 
                                cq.status 
                                FROM 
                                consultation_report cr
                                INNER JOIN 
                                consultancyquestionnaire cq ON cr.consultationID = cq.id
                                WHERE 
                                cr.consultationID = :id
                        ";

                                                                                    $statement = $conn->prepare($query);
                                                                                    $statement->bindParam(':id', $consultationID, PDO::PARAM_INT);
                                                                                    $statement->execute();
                                                                                    $Data = $statement->fetch(PDO::FETCH_ASSOC);

                                                                                    // Process the $Data as needed

                                                                                    if (!$Data) {
                                                                                        $showDiv = false; // Set the flag to false if no data
                                                                                    }
                                                ?>
                                                    <?php if ($showDiv): ?>
                                                        <h1 class="pt-1"></h1>
                                                        <div class="" id="container" style="background-color: white; <?= $showDiv ? '' : 'display:none;' ?>">
                                                            <div class="container m-1">
                                                                <h3 class="mb-2 pt-1 d-flex flex-column"><b>Stage 3: Consultant Report</b></h3>
                                                                <div class="progress mb-3 ">
                                                                    <div class="progress-bar" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80%</div>
                                                                </div>
                                                                <p><b>Status:</b> Final Design Output Report</p>
                                                                <hr>
                                                                <h2><b>Consultation Report</b></h2>
                                                                <!-- Profile Information Section -->
                                                                <h6><b>MSME Information</b></h6>
                                                                <input type="text" class="w3-input w3-border" name="name" id="name" placeholder="Name" value="<?php echo $Data['name']; ?>" readonly />
                                                                <br>
                                                                <h6><b>Product Information</b></h6>
                                                                <input type="text" class="w3-third w3-input w3-border" name="product" id="product" placeholder="Name of Product" value="<?php echo $Data['ProductName']; ?>" readonly />
                                                                <input type="text" class="w3-third w3-input w3-border" name="labelingformat" id="labelingformat" value="<?php echo $Data['labelingFormat']; ?>" readonly />
                                                                <input type="text" class="w3-third w3-input w3-border" name="brandName" id="brandName" value="<?php echo $Data['brandName'] ?>" readonly />
                                                                <!-- <button type="button" onclick="nextSection('businessSection')">Next</button>
                                </div> -->
                                                                <!-- Business Information Section -->
                                                                <!-- <div id="businessSection" class="section"> -->
                                                                <br>
                                                                <br>
                                                                <h6>Product Identity Name: True Name/Nature of the Food (ex. salted Peanut, Dried Mango)</h6>
                                                                <input type="text" class="w3-input w3-border" value="<?php echo $Data['ProductName'] ?>" readonly />
                                                                <br>
                                                                <hr>
                                                                <h6><b>Product Design Information</b></h6>
                                                                <h6><b>Note:</b> Write a report for the final design output</h6>
                                                                <h6 class="mb-0">Concept of Design</h6>
                                                                <textarea class="w3-input w3-border" name="ConceptDesign" rows="7" cols="70" readonly><?php echo $Data['ReportConceptDesign'] ?></textarea>
                                                                <h6 class="mb-0">Size of the Product</h6>
                                                                <div style="display: flex; align-items: center;">
                                                                    <label style="margin-right: 10px;">
                                                                        <input type="radio" name="size" value="Small" style="margin-right: 5px;"
                                                                            <?php echo ($Data['ReportSize'] == 'small') ? 'checked' : ''; ?> required> Small
                                                                    </label>
                                                                    <label style="margin-right: 10px;">
                                                                        <input type="radio" name="size" value="medium" style="margin-right: 5px;"
                                                                            <?php echo ($Data['ReportSize'] == 'medium') ? 'checked' : ''; ?> required> Medium
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="size" value="large" style="margin-right: 5px;"
                                                                            <?php echo ($Data['ReportSize'] == 'large') ? 'checked' : ''; ?> required> Large
                                                                    </label>
                                                                </div>
                                                                <br>
                                                                <h6 class="mb-0">Notes or Other Comments:</h6>
                                                                <textarea class="w3-input w3-border" name="Comment" rows="7" cols="70" readonly><?php echo $Data['ReportComment'] ?></textarea>
                                                                <br>
                                                                <h6 class="mb-0">Dominant Color used in the design:</h6>
                                                                <input class="w3-input w3-border" type="text" name="DominantColor" id="tagline" value="<?php echo $Data['ReportDominantColor'] ?>" placeholder="Dominant Color to be used" readonly />
                                                                <input type="hidden" id="pickedColors" name="SelectedColor" value="" readonly>
                                                                <h6 class="mb-0">Color used in the design:</h6>
                                                                <input type="text" id="displayColors" class="w3-input w3-border" value="<?php echo $Data['ReportSelectedColor'] ?>" readonly>
                                                                <table>
                                                                    <!-- The table rows and cells will be generated by JavaScript -->
                                                                </table>

                                                                <hr>
                                                            </div>
                                                            <div class="w3-center">
                                                                <h4><b>Final Output Design</b></h4>
                                                                <img src=".\uploaded_img\<?php echo $Data['ReportDraftImage'] ?>" width="250px" height="250px">
                                                            </div>

                                                            <br>
                                                            <div class="container">
                                                                <form method="POST" action="review_Assessment.php">
                                                                    <label for="decision">Stage Completion: Approve if the Stage is Complete</label><br>
                                                                    <input name="consultationID" type="hidden" value="<?php echo $Data['consultationID'] ?>">
                                                                    <input name="room_id" type="hidden" value="<?php echo $Data['room_id'] ?>">
                                                                    <button type="submit" name="action" class="btn btn-primary" value="approve">Approve</button>
                                                                    <button type="submit" name="action" class="btn btn-danger" value="delete">Delete</button>
                                                                </form>
                                                                <br>
                                                            </div>

                                                        <?php else: ?>
                                                            <h1 class="pt-1"></h1>
                                                            <div class="" id="container" style="background-color: white;">
                                                                <div class="container m-1">
                                                                    <h3 class="mb-2 pt-1 d-flex flex-column"><b>Stage 3: Consultant Report</b></h3>
                                                                    <div class="progress mb-3 ">
                                                                        <div class="progress-bar" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80%</div>
                                                                    </div>
                                                                    <p><b>Status:</b> Final Design Output Report</p>
                                                                    <p>Waiting for Consultant Output</p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php elseif ($status12['status'] == 4): ?>
                                                        <h3 class="mb-2 pt-4 d-flex flex-column"><b>Stage 4: Implementation</b></h3>
                                                        <div class="progress mb-3 ">
                                                            <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                                                        </div>
                                                        <p><b>Status:</b> Implementation of Design</p>
                                                        <?php
                                                                                    if ($consultationID) {
                                                                                        try {
                                                                                            // Prepare the SQL query
                                                                                            $sql = "SELECT draft_img FROM consultation_report WHERE consultationID = :consultationID";
                                                                                            $stmt = $conn->prepare($sql);

                                                                                            // Bind the consultationID parameter
                                                                                            $stmt->bindParam(':consultationID', $consultationID, PDO::PARAM_INT);

                                                                                            // Execute the query
                                                                                            $stmt->execute();

                                                                                            // Fetch the result
                                                                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                                                                            // Check if a result was found
                                                                                            if ($result) {
                                                                                                // Display the draft_img (assuming it's stored as a file path in the database)
                                                                                                echo '<hr><center><p>Design Output</p><img src="./uploaded_img/' . htmlspecialchars($result['draft_img']) . '" alt="Draft Image" width="550px" height="auto" /></center>';
                                                                                            } else {
                                                                                                echo "No draft image found for the provided consultation ID.";
                                                                                            }
                                                                                        } catch (PDOException $e) {
                                                                                            echo "Error: " . $e->getMessage();
                                                                                        }
                                                                                    } else {
                                                                                        echo "Consultation ID is not set.";
                                                                                    }
                                                        ?>
                                                        <br>
                                                        <a href="update_product.php?update=<?php echo $productId ?>" class="w3-btn w3-round w3-blue w3-small">Update Product</a>
                                                    <?php endif; ?> </p>
                                                        </div> <?php elseif ($status0): ?> <br>
                                                        <!-- Display the message for status 0 and the delete form -->
                                                        <div class="w3-margin card w3-white w3-xlarge w3-padding" id="container">
                                                            <p>Assessment Declined. Submit a new assessment.</p>
                                                            <p><b>Status:</b> Assessment Declined</p>
                                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete the assessment with status 0?');">
                                                                <button type="submit" class="w3-button w3-small w3-red">Submit new assessment</button>
                                                            </form>
                                                        </div>
                                                        <!-- If no relevant status, display default content --> <?php else: ?> <div class="">
                                                            <br>
                                                            <div class="w3-margin card w3-white" id="container">
                                                                <form id="colorForm" class="w3-container" method="POST" action="consultancyQuestionaire.php" onsubmit="return validateForm()" enctype="multipart/form-data">
                                                                    <h3 class="mb-2 pt-4 d-flex flex-column"><b>Stage 1: Assessment</b></h3>
                                                                    <div class="progress mb-3 ">
                                                                        <div class="progress-bar" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">10%</div>
                                                                    </div>
                                                                    <hr>
                                                                    <h3><b>Consultancy Questionaire</b></h3>
                                                                    <h6><b>Note: Answer only if you want to undergo to Product Development</b></h6>
                                                                    <!-- Profile Information Section -->
                                                                    <!-- <div id="profileSection" class="section active"> -->
                                                                    <h6 class=""><b>Please provide the following information.</b></h6>
                                                                    <h6>User Information</h6>
                                                                    <input type="hidden" class="w3-input w3-border" name="userid" id="userid" value="<?php echo $Id ?>" placeholder="Name" required />
                                                                    <input type="text" class="w3-half w3-input w3-border" name="name" id="name" placeholder="Name" required />
                                                                    <input type="text" class="w3-half w3-input w3-border" name="address" id="address" placeholder="Address" required />
                                                                    <br>
                                                                    <br>
                                                                    <input type="text" class="w3-half w3-input w3-border" name="municipality" id="municipality" placeholder="Municipality" required />
                                                                    <input type="text" class="w3-half w3-input w3-border" name="phoneNumber" id="phoneNumber" placeholder="Phone Number" required />
                                                                    <br>
                                                                    <br>
                                                                    <h6>Product Information</h6>
                                                                    <select class="w3-third w3-input w3-border" name="product" id="product" required>
                                                                        <option value="">Select a product</option> <?php foreach ($products as $product): ?> <option value="<?= $product['product_id']; ?>" data-name="<?= $product['productName']; ?>"> <?= $product['productName']; ?> </option> <?php endforeach; ?>
                                                                    </select>
                                                                    <input type="hidden" name="product_name" id="product_name" />
                                                                    <!-- JavaScript to set the hidden input value for product_name -->
                                                                    <script>
                                                                        document.getElementById('product').addEventListener('change', function() {
                                                                            var selectedOption = this.options[this.selectedIndex];
                                                                            document.getElementById('product_name').value = selectedOption.getAttribute('data-name');
                                                                        });
                                                                    </script>
                                                                    <input type="text" class="w3-third w3-input w3-border" name="labelingformat" id="labelingformat" placeholder="Labeling Format: (stick on label, header, etc)
" required />
                                                                    <input type="text" class="w3-third w3-input w3-border" name="brandName" id="brandName" placeholder="Brand Name: (ex. Coca-cola, Milo, Mt.Dew)
" required />
                                                                    <!-- <button type="button" onclick="nextSection('businessSection')">Next</button>
                </div> -->
                                                                    <!-- Business Information Section -->
                                                                    <!-- <div id="businessSection" class="section"> -->
                                                                    <br>
                                                                    <br>
                                                                    <h6>Product Identity Name: True Name/Nature of the Food (ex. salted Peanut, Dried Mango)</h6>
                                                                    <input type="text" class="w3-input w3-border" name="productIdentity" id="productIdentity" required />
                                                                    <h6>If 1 label with 2 or more product selection: Name of Product </h6>
                                                                    <input type="text" class="w3-half w3-input w3-border" name="label1" id="label1" placeholder="Label 1" required />
                                                                    <input type="text" class="w3-half w3-input w3-border" name="label2" id="label2" placeholder="Label 2" required />
                                                                    <br>
                                                                    <br>
                                                                    <input type="text" class="w3-half w3-input w3-border" name="label3" id="label3" placeholder="Label 3" required />
                                                                    <input type="text" class="w3-half w3-input w3-border" name="label4" id="label4" placeholder="Label 4" required />
                                                                    <br>
                                                                    <br>
                                                                    <input type="text" class="w3-half w3-input w3-border" name="tagline" id="tagline" placeholder="Tagline (optional) " required />
                                                                    <input type="text" class="w3-half w3-input w3-border" name="netContent" id="netContent" placeholder="Net. Content (kg, g, ml,etc.): " required />
                                                                    <!-- <button type="button" onclick="nextSection('verificationSection')">Next</button>
                </div> -->
                                                                    <!-- Verification Information Section -->
                                                                    <!-- <div id="verificationSection" class="section"> -->
                                                                    <br>
                                                                    <br>
                                                                    <h6 class="mb-0">Ingredients (from most to least quantity):</h6>
                                                                    <textarea class="w3-input w3-border" name="ingredients" rows="7" cols="70" required></textarea>
                                                                    <h6 class="mb-0">Direction of the Product:</h6>
                                                                    <textarea class="w3-input w3-border" name="ProductDirect" rows="7" cols="70" required></textarea>
                                                                    <h6 class="mb-0">Concept of Design</h6>
                                                                    <textarea class="w3-input w3-border" name="ConceptDesign" rows="7" cols="70" required></textarea>
                                                                    <h4 class="mb-0">Size of the Product</h4>
                                                                    <div style="display: flex; align-items: center;">
                                                                        <label style="margin-right: 10px;">
                                                                            <input type="radio" name="size" value="small" style="margin-right: 5px;" required> Small </label>
                                                                        <label style="margin-right: 10px;">
                                                                            <input type="radio" name="size" value="medium" style="margin-right: 5px;" required> Medium </label>
                                                                        <label>
                                                                            <input type="radio" name="size" value="large" style="margin-right: 5px;" required> Large </label>
                                                                    </div>
                                                                    <h6 class="mb-0">Expiry Date of the Product</h6>
                                                                    <input class="w3-half w3-input w3-border" type="date" name="expiryDate" id="" required />
                                                                    <br>
                                                                    <br>
                                                                    <h6 class="mb-0">Notes or Other Comments:</h6>
                                                                    <textarea class="w3-input w3-border" name="Comment" rows="7" cols="70" required></textarea>
                                                                    <br>
                                                                    <input class="w3-input w3-border" type="text" name="DominantColor" id="tagline" placeholder="Dominant Color to be used" required />
                                                                    <br>
                                                                    <input type="hidden" id="pickedColors" name="SelectedColor" value="" required>
                                                                    <input type="text" id="displayColors" class="w3-input w3-border" readonly required>
                                                                    <table>
                                                                        <!-- The table rows and cells will be generated by JavaScript -->
                                                                    </table>
                                                                    <br>
                                                                    <h6>Upload Draft Design (Optional):</h6>
                                                                    <input type="file" name="draft_img" class="w3-input w3-border">
                                                                    <br>
                                                                    <button type="submit" class="w3-btn w3-blue w3-round" name="submit" id="submit">Submit</button>
                                                                    <!-- </div> -->
                                                                </form>
                                                                <br>
                                                            </div>
                                                        </div> <?php endif; ?>
                                            </div>
                                            <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include 'importantinclude/footer.php'; ?>
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
        <!-- jQuery (Required for DataTables) -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

</body>

</html>