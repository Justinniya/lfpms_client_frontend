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

try {
  // Prepare SQL query to fetch MSME names with usertype = 2
  $sql = "SELECT userid, CONCAT(fname, ' ', lname) AS full_name FROM users WHERE usertype = 2";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Prepare SQL query to fetch products
  $sql = "SELECT msme_id, product_id, productName FROM products";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Structure the data for easier JavaScript usage
  $userProducts = [];
  foreach ($users as $user) {
    $userProducts[$user['userid']] = [
      'full_name' => $user['full_name'],
      'products' => []
    ];
  }
  foreach ($products as $product) {
    $userProducts[$product['msme_id']]['products'][] = [
      'id' => $product['product_id'],
      'name' => $product['productName']
    ];
  }
} catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $query = "SELECT `id`, `Cname`, `MSME`, `product_id`, `Pname`, `Stime`, `Product_Line`, `Enterprise_Category`, `Currently_Exporting`, `ExportPlan`, `QUALITY_Score`, `QUALITY_Comment`, `DESIGN_Score`, `DESIGN_Comment`, `PACKAGING_Score`, `packagingComments`, `MARKETABILITY_Score`, `marketabilityComments`, `BRAND_Score_Score`, `brandComments`, `PRODUCTION_Score`, `productionComments`, `FINANCING_Score`, `financingComments`, `CULTURAL_Score`, `culturalComments`, `INNOVATION_Score`, `Innovation`, `CUSTOMER_Score`, `CUSTOMER`, `Designer`, `total_score`, `intensive`, `Recommend`, `Enroll`, `ExamDate`, `Province`, `TimeEnd`, `EName`, `Signature` FROM `evaluation` WHERE id = :id";
  $statement = $conn->prepare($query);
  $statement->bindParam(':id', $id, PDO::PARAM_INT);
  $statement->execute();
  $Data = $statement->fetch(PDO::FETCH_ASSOC);
}
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

    @media print {
      body {
        zoom: 0.8;
        /* Scales the entire page down to 90% */
      }
    }

    .form-container {
      transform: scale(0.8);
      transform-origin: top left;
      width: 111.11%;
      /* Adjust to counteract scale effect */
    }

    .table-responsive {
      overflow-x: auto;
    }

    .table {
      font-size: 0.85rem;
      /* Reduce font size */
    }

    .table th,
    .table td {
      padding: 0.5rem;
      /* Reduce padding */
      vertical-align: middle;
    }

    .form-control {
      font-size: 0.85rem;
      /* Reduce input text size */
      padding: 0.3rem 0.5rem;
    }

    .form-check-input {
      transform: scale(0.9);
      /* Slightly shrink checkboxes */
    }

    img {
      max-width: 60px;
      height: auto;
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
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">Evaluation 00-005 CRITERIA</a>
                    </li>
                  </ul>
                </div>

                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row">
                      <div class="col-md-12 mb-4">
                        <div class="text-end">
                          <button class="btn btn-primary text-white" onclick="printForm()">Print Form</button>
                        </div>
                        <div class="card">
                          <div class="card-body">
                            <form id="EvaluationPrint" action="insert_evaluation.php" method="post" enctype="multipart/form-data">
                              <div class="row">
                                <div class="text-center">
                                  <img src="assets/img/DTI.png" style="height:120px; width:120px;">
                                  <h1 class=""><b>Evaluation 00-005 CRITERIA</b></h1>
                                  <h2 class=""><b>Evaluation SHEET</b></h2>
                                </div>
                                <hr>
                                <small>
                                  <p><b>TO THE EVALUATORS:</b> This <b><i>Criteria Evaluation Sheet</i></b> is intended to rate the product/s enrolled by the MSME. TPOs need to accomplish <b><i><u>ONE sheet PER PRODUCT LINE.</u></i></b> Kindly write legibly the <b><i>score and the comments</i></b> provided per criteria to help you assess whether the MSME should be enrolled to OTOP program. This accomplished form should be provided to all designers and experts assigned. Analytics should be summarized in the separate excel summary sheet.</p>
                                </small>
                              </div>
                              <div class="row">
                                <div class="col-6">
                                  <label for="Cname"><b>Company Name</b></label>
                                  <input type="text" class="form-control" id="Cname" value="<?php echo $Data['Cname'] ?>" name="Cname" placeholder="Company name" required readonly>
                                </div>
                                <div class="col-6">
                                  <label for="Cname"><b>MSME Name</b></label>
                                  <input type="text" class="form-control" id="Cname" value="<?php echo $Data['MSME'] ?>" name="Cname" placeholder="Company name" required readonly>
                                </div>
                              </div>

                              <div class="row mt-2">
                                <div class="col-3">
                                  <label for="Cname"><b>Product ID</b></label>
                                  <input type="text" class="form-control" id="Cname" value="<?php echo $Data['product_id'] ?>" name="Cname" placeholder="Company name" required readonly>
                                </div>
                                <div class="col-3">
                                  <label for="Pname"><b>Product Name</b></label>
                                  <input type="text" class="form-control" id="Pname" name="Pname" value="<?php echo $Data['Pname'] ?>" placeholder="Product name" required readonly>
                                </div>
                                <div class="col-6">
                                  <label for="Stime"><b>Time Started</b></label>
                                  <input type="time" class="form-control" value="<?php echo $Data['Stime'] ?>" id="Stime" name="Stime" readonly>
                                </div>
                              </div>

                              <div class="row mt-4">
                                <div class="col-3">
                                  <p>Product Line</p>
                                  <input type="checkbox" name="Product_Line" value="Food" class="form-check-input" <?php echo ($Data['Product_Line'] === 'Food') ? 'checked' : ''; ?> id="exampleCheck1" readonly>
                                  <label class="form-check-label" for="exampleCheck1">Food</label>
                                  <input type="checkbox" name="Product_Line" value="Non-Food" class="form-check-input" <?php echo ($Data['Product_Line'] === 'Non-Food') ? 'checked' : ''; ?> id="exampleCheck1" readonly>
                                  <label class="form-check-label" for="exampleCheck1">Non-Food</label>
                                </div>
                                <div class="col-3">
                                  <p>Enterprise Category</p>
                                  <input type="checkbox" class="form-check-input" id="exampleCheck1" <?php echo ($Data['Enterprise_Category'] === 'Micro') ? 'checked' : ''; ?> name="Enterprise_Category" value="Micro" readonly>
                                  <label class="form-check-label" for="exampleCheck1">Micro</label>
                                  <input type="checkbox" class="form-check-input" name="Enterprise_Category" <?php echo ($Data['Enterprise_Category'] === 'Medium') ? 'checked' : ''; ?> value="Medium" id="exampleCheck1" readonly>
                                  <label class="form-check-label" for="exampleCheck1">Medium</label>
                                  <input type="checkbox" class="form-check-input" name="Enterprise_Category" <?php echo ($Data['Enterprise_Category'] === 'Small') ? 'checked' : ''; ?> value="Small" id="exampleCheck1" readonly>
                                  <label class="form-check-label" for="exampleCheck1">Small</label>
                                </div>
                                <div class="col-3">
                                  <p>Currently Exporting?</p>
                                  <input type="checkbox" class="form-check-input" id="exampleCheck1" name="Currently_Exporting" <?php echo ($Data['Currently_Exporting'] === 'Yes') ? 'checked' : ''; ?> value="Yes" readonly>
                                  <label class="form-check-label" for="exampleCheck1">Yes</label>
                                  <input type="checkbox" class="form-check-input" id="exampleCheck1" name="Currently_Exporting" <?php echo ($Data['Currently_Exporting'] === 'No') ? 'checked' : ''; ?> value="No" readonly>
                                  <label class="form-check-label" for="exampleCheck1">No</label>
                                </div>
                                <div class="col-3">
                                  <p>Planning to Export?</p>
                                  <input type="checkbox" class="form-check-input" name="ExportPlan" value="Yes" <?php echo ($Data['ExportPlan'] === 'Yes') ? 'checked' : ''; ?> id="exampleCheck1" readonly>
                                  <label class="form-check-label" for="exampleCheck1">Yes</label>
                                  <input type="checkbox" class="form-check-input" id="exampleCheck1" name="ExportPlan" <?php echo ($Data['ExportPlan'] === 'No') ? 'checked' : ''; ?> value="No" readonly>
                                  <label class="form-check-label" for="exampleCheck1">No</label>
                                </div>
                              </div>

                              <hr>
                              <small>
                                <p><b>Instructions:</b> From a scale of 1 to 5 with 5 as the highest, rate the product line based on the indicated criterion. <u>You may score in fractions of 0.25 (e.g. 2.75).</u></p>
                              </small>
                              <small>
                                <p>1 - Poor&nbsp;&nbsp;&nbsp; 2 - Fair&nbsp;&nbsp;&nbsp; 3 - Average&nbsp;&nbsp;&nbsp; 4 - Good&nbsp;&nbsp;&nbsp; 5 - Excellent</p>
                              </small>
                              <div class="table-responsive">
                                <table class="table table-bordered">
                                  <thead>
                                    <tr>
                                      <th colspan="2" class="text-center">CRITERIA</th>
                                      <th class="text-center">SCORE</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <!-- Quality Section -->
                                    <tr class="section-title">
                                      <td colspan="2">1. QUALITY</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['QUALITY_Score'] ?>" name="score1" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="criteria">
                                      <td colspan="3">
                                        Stability, reliability, shelf life; Product performance; Consistent taste for food; Product features, and Controlled number of defects or quality variance
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="qualityComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="qualityComments" value="" name="QUALITY_Comment" rows="3" readonly><?php echo $Data['QUALITY_Comment'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- Design Section -->
                                    <tr class="section-title">
                                      <td colspan="2">2. DESIGN</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['DESIGN_Score'] ?>" name="score2" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="design">
                                      <td colspan="3">
                                        Design elements; Aesthetics/visuals/optics; Functionality; Overall user experience (easy-to-use, install, intuitive, etc.) and Uniqueness
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="designComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="designComments" value="" name="DESIGN_Comment" rows="3" readonly><?php echo $Data['DESIGN_Comment'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- Packaging / Labelling Section -->
                                    <tr class="section-title">
                                      <td colspan="2">3. PACKAGING / LABELLING</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['PACKAGING_Score'] ?>" name="score3" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="packaging">
                                      <td colspan="3">
                                        Compliance with mandatory labeling standards (adequate and accurate information, accurate identification, legibility) and Packaging quality (hygienic, food grade etc.)
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="packagingComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="packagingComments" value="" name="packagingComments" rows="3" readonly><?php echo $Data['packagingComments'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- Marketability / Market Access Section -->
                                    <tr class="section-title">
                                      <td colspan="2">4. MARKETABILITY / MARKET ACCESS</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['MARKETABILITY_Score'] ?>" name="score4" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="marketability">
                                      <td colspan="3">
                                        Identified and addresses a market need or niche; Aligned to business strategy; Business & Marketing Plan and Market penetration, access or convenience
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="marketabilityComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="marketabilityComments" value="" name="marketabilityComments" rows="3" readonly><?php echo $Data['marketabilityComments'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- Brand Development Section -->
                                    <tr class="section-title">
                                      <td colspan="2">5. BRAND DEVELOPMENT</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['BRAND_Score_Score'] ?>" name="score5" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="brand">
                                      <td colspan="3">
                                        Branding initiatives; Brand Equity and IPO; Well-defined Brand Identity and Market Penetration, Differentiation and Positioning
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="brandComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="brandComments" value="" name="brandComments" rows="3" readonly><?php echo $Data['brandComments'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- Production and Delivery Capacity Section -->
                                    <tr class="section-title">
                                      <td colspan="2">6. PRODUCTION AND DELIVERY CAPACITY</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['PRODUCTION_Score'] ?>" name="score6" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="production">
                                      <td colspan="3">
                                        Volume Capacity; Consistency in Supply; Ability to Deliver based on Client needs / Timelines and Logistics Capability and Network
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="productionComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="productionComments" value="" name="productionComments" rows="3" readonly><?php echo $Data['productionComments'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- Financing Capability Section -->
                                    <tr class="section-title">
                                      <td colspan="2">7. FINANCING CAPABILITY</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['FINANCING_Score'] ?>" name="score7" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="financing">
                                      <td colspan="3">
                                        Sound Financial Capability; Willingness to Invest in Product Development or in the Prescribed Intervention
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="financingComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="financingComments" value="" name="financingComments" rows="3" readonly><?php echo $Data['financingComments'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- Cultural Value Section -->
                                    <tr class="section-title">
                                      <td colspan="2">8. CULTURAL VALUE</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['CULTURAL_Score'] ?>" name="score8" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="cultural">
                                      <td colspan="3">
                                        Product has high cultural value (i.e. pride of place, etc.); Product or Process is driven by Tradition or Heritage and Product Reinforces the Locality’s Cultural Identity
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="culturalComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="culturalComments" value="" name="culturalComments" rows="3" readonly><?php echo $Data['culturalComments'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- Innovation Section -->
                                    <tr class="section-title">
                                      <td colspan="2">9. INNOVATION</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['INNOVATION_Score'] ?>" name="score9" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="Innovation">
                                      <td colspan="3">
                                        Track record of Innovation, disruption and adaptability, Product is currently a game changer or potential one; Willingness and openness to innovate and Technology-driven
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="culturalComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="Innovation" value="" name="Innovation" rows="3" readonly><?php echo $Data['Innovation'] ?></textarea>
                                      </td>
                                    </tr>

                                    <!-- CUSTOMER EXPERIENCE Section -->
                                    <tr class="section-title">
                                      <td colspan="2">10. CUSTOMER EXPERIENCE</td>
                                      <td class="score"><input type="number" class="form-control" value="<?php echo $Data['CUSTOMER_Score'] ?>" name="score10" min="1" max="5" readonly></td>
                                    </tr>
                                    <tr class="CUSTOMER">
                                      <td colspan="3">
                                        Mechanism for Customer Feedback, Mechanism for Service Recovery, Provides Warranties or Guarantees and Customer-responsive Service Design
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3">
                                        <label for="culturalComments">Comments (Strengths, Areas for Intervention, Other Notes / Remarks)</label>
                                        <textarea class="form-control" id="CUSTOMER" value="" name="CUSTOMER" rows="3" readonly><?php echo $Data['CUSTOMER'] ?></textarea>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                                         
                              <table class="table table-bordered table-sm align-middle mt-2">
                                <thead class="table-light">
                                  <tr>
                                    <th colspan="5">
                                      Indicate initial Intervention/s recommended and the assessment details:
                                      <input type="text" class="form-control d-inline w-auto" value="<?php echo $Data['Designer']; ?>" name="Designer" readonly>
                                    </th>
                                    <th colspan="2">
                                      <input type="number" class="form-control text-center" value="<?php echo $Data['total_score']; ?>" name="total_score" readonly>
                                    </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>A.</td>
                                    <td colspan="6">Identify Enterprise Development Track</td>
                                  </tr>
                                  <tr class="text-center">
                                    <td></td>
                                    <td>0</td>
                                    <td>1.1</td>
                                    <td>1.2</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                  </tr>
                                  <tr>
                                    <td>B.</td>
                                    <td colspan="6">OTOP MSME Criteria</td>
                                  </tr>
                                  <tr>
                                    <td></td>
                                    <td colspan="6">
                                      <strong>Potential:</strong> Gauge the growth and market potential.<br>
                                      <strong>Promise:</strong> Commitment and cooperation level.<br>
                                      <strong>Passion:</strong> Openness to innovation and new models.
                                    </td>
                                  </tr>
                                  <tr class="text-center">
                                    <td></td>
                                    <td>Potential</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                  </tr>
                                  <tr class="text-center">
                                    <td></td>
                                    <td>Promise</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                  </tr>
                                  <tr class="text-center">
                                    <td></td>
                                    <td>Passion</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                  </tr>
                                  <tr>
                                    <td>C.</td>
                                    <td colspan="6">Type of Product Development Needed</td>
                                  </tr>
                                  <tr>
                                    <td></td>
                                    <td colspan="3">
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="intensive" value="intensive" <?php echo ($Data['intensive'] === 'intensive') ? 'checked' : ''; ?> id="intensive" readonly>
                                        <label class="form-check-label" for="intensive">Intensive</label>
                                      </div>
                                    </td>
                                    <td colspan="3">
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="intensive" value="non-intensive" <?php echo ($Data['intensive'] === 'non-intensive') ? 'checked' : ''; ?> id="non-intensive" readonly>
                                        <label class="form-check-label" for="non-intensive">Non-Intensive</label>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>D.</td>
                                    <td colspan="6">Recommend for Go Lokal?</td>
                                  </tr>
                                  <tr class="text-center">
                                    <td></td>
                                    <td colspan="2">
                                      <input class="form-check-input" type="checkbox" name="Recommend" value="Yes" <?php echo ($Data['Recommend'] === 'Yes') ? 'checked' : ''; ?> readonly> Yes
                                    </td>
                                    <td colspan="2">
                                      <input class="form-check-input" type="checkbox" name="Recommend" value="No" <?php echo ($Data['Recommend'] === 'No') ? 'checked' : ''; ?> readonly> No
                                    </td>
                                    <td colspan="2">
                                      <input class="form-check-input" type="checkbox" name="Recommend" value="conditional" <?php echo ($Data['Recommend'] === 'conditional') ? 'checked' : ''; ?> readonly> Conditional
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>E.</td>
                                    <td colspan="6">Enroll to OTOP Next Generation?</td>
                                  </tr>
                                  <tr class="text-center">
                                    <td></td>
                                    <td colspan="3">
                                      <input class="form-check-input" type="checkbox" name="Enroll" value="yes" <?php echo ($Data['Enroll'] === 'yes') ? 'checked' : ''; ?> readonly> Yes
                                    </td>
                                    <td colspan="3">
                                      <input class="form-check-input" type="checkbox" name="Enroll" value="No" <?php echo ($Data['Enroll'] === 'No') ? 'checked' : ''; ?> readonly> No
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3">Evaluator</td>
                                    <td colspan="2">
                                      <input type="text" name="EName" value="<?php echo $Data['EName']; ?>" class="form-control form-control-sm" readonly>
                                    </td>
                                    <td colspan="2">
                                      <img src="<?php echo $Data['Signature']; ?>" class="img-fluid" style="max-width: 80px; height: 50px;">
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3">Date</td>
                                    <td colspan="4">
                                      <input type="date" name="ExamDate" value="<?php echo $Data['ExamDate']; ?>" class="form-control form-control-sm" readonly>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3">Province / Region</td>
                                    <td colspan="4">
                                      <input type="text" name="Province" value="<?php echo $Data['Province']; ?>" class="form-control form-control-sm" readonly>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                      

                        <div class="text-center">
                          <button class="btn btn-primary mt-4 text-white" type="submit">Submit</button>
                        </div>
                        </form>
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
    // JavaScript object containing user and product data
    var userProducts = <?= json_encode($userProducts) ?>;

    document.getElementById('user').addEventListener('change', function() {
      var userId = this.value;
      var productSelect = document.getElementById('product_id');
      var productNameInput = document.getElementById('Pname');

      // Clear the product select options and product name input
      productSelect.innerHTML = '<option value="">Select Product</option>';
      productNameInput.value = '';

      // Populate the product select with options based on selected user
      if (userId && userProducts[userId]) {
        var products = userProducts[userId].products;
        for (var i = 0; i < products.length; i++) {
          var option = document.createElement('option');
          option.value = products[i].id;
          option.text = products[i].id;
          productSelect.appendChild(option);
        }
      }
    });

    document.getElementById('product_id').addEventListener('change', function() {
      var productId = this.value;
      var userId = document.getElementById('user').value;
      var productNameInput = document.getElementById('Pname');

      // Clear the product name input
      productNameInput.value = '';

      // Find and set the product name based on selected product ID
      if (userId && productId && userProducts[userId]) {
        var products = userProducts[userId].products;
        for (var i = 0; i < products.length; i++) {
          if (products[i].id == productId) {
            productNameInput.value = products[i].name;
            break;
          }
        }
      }
    });
  </script>
  <script>
    // Function to calculate total score
    function calculateTotalScore() {
      let totalScore = 0;
      for (let i = 1; i <= 10; i++) {
        let scoreInput = document.getElementsByName('score' + i)[0];
        if (scoreInput) {
          let scoreValue = parseFloat(scoreInput.value) || 0;
          totalScore += scoreValue;
        }
      }
      let totalScoreInput = document.getElementsByName('total_score')[0];
      if (totalScoreInput) {
        totalScoreInput.value = totalScore;
      }
    }

    // CSS to hide the up and down arrows in number input fields
    var style = document.createElement('style');
    style.innerHTML = `
                input[type=number]::-webkit-inner-spin-button, 
                input[type=number]::-webkit-outer-spin-button { 
                    -webkit-appearance: none; 
                    margin: 0; 
                }
                input[type=number] {
                    -moz-appearance: textfield;
                }
            `;
    document.head.appendChild(style);

    // Add event listeners to all score inputs to recalculate total score on change
    for (let i = 1; i <= 10; i++) {
      let scoreInput = document.getElementsByName('score' + i)[0];
      if (scoreInput) {
        scoreInput.addEventListener('input', calculateTotalScore);
      }
    }

    function onlyOne(checkbox, groupName) {
      const checkboxes = document.querySelectorAll(`input[name="${groupName}"]`);
      checkboxes.forEach((item) => {
        if (item !== checkbox) item.checked = false;
      });
    }

    function validateForm() {
      const groups = ['Product_Line', 'Enterprise_Category', 'Currently_Exporting', 'ExportPlan', 'intensive', 'Recommend', 'Enroll'];
      let isValid = true;

      groups.forEach((group) => {
        const checkboxes = document.querySelectorAll(`input[name="${group}"]`);
        const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        if (!isChecked) {
          isValid = false;
          alert(`Please select an option for ${group.replace('_', ' ').replace(/^\w/, c => c.toUpperCase())}`);
        }
      });

      return isValid;
    }

    function printForm() {
      var printContents = document.getElementById('EvaluationPrint').innerHTML;
      var originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
    }
  </script>
  <script>
    $(document).ready(function() {
      $('#feedback_monitoring').DataTable();
    });
  </script>

</body>

</html>