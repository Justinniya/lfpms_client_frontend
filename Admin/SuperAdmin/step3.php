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
  $MSME_id = $_GET['id'];
  $id = $_GET['feedid'];

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
          cr.room_id,
          cq.status 
      FROM 
          consultation_report cr
      INNER JOIN 
          consultancyquestionnaire cq ON cr.consultationID = cq.id
      WHERE 
          cq.id = :id
  ";

  $statement = $conn->prepare($query);
  $statement->bindParam(':id', $id, PDO::PARAM_INT);
  $statement->execute();
  $Data = $statement->fetch(PDO::FETCH_ASSOC);

  // Process the $Data as needed
}
if (!$Data) {
  $showDiv = false; // Set the flag to false if no data
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
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">Assessment Stage <span class="mdi mdi-arrow-right-thin"> Stage 1 <span class="mdi mdi-arrow-right-thin"> Stage 2 <span class="mdi mdi-arrow-right-thin"> Stage 3</a>
                    </li>
                  </ul>
                </div>

                <?php if ($showDiv): ?>

                  <div class="col-lg-12 grid-margin stretch-card mt-3">
                    <div class="card">
                      <div class="card-body">
                        <div class="container mt-4">
                          <div class="card">
                            <div class="card-body">
                              <h3 class="mb-2 pt-1"><b>Stage 3: Consultant Report</b></h3>
                              <p><b>Status:</b> Final Design Output Report</p>
                              <hr>
                              <h2><b>Consultation Report</b></h2>

                              <div class="row">
                                <div class="col-md-6">
                                  <h6><b>MSME Information</b></h6>
                                  <input type="text" class="form-control mb-3" name="name" id="name" placeholder="Name" value="<?php echo $Data['name']; ?>" readonly />
                                </div>
                                <div class="col-md-6">
                                  <h6><b>Product Information</b></h6>
                                  <input type="text" class="form-control mb-3" name="product" id="product" placeholder="Name of Product" value="<?php echo $Data['ProductName']; ?>" readonly />
                                  <input type="text" class="form-control mb-3" name="labelingformat" id="labelingformat" value="<?php echo $Data['labelingFormat']; ?>" readonly />
                                  <input type="text" class="form-control mb-3" name="brandName" id="brandName" value="<?php echo $Data['brandName'] ?>" readonly />
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <h6>Product Identity Name: True Name/Nature of the Food (ex. salted Peanut, Dried Mango)</h6>
                                  <input type="text" class="form-control mb-3" value="<?php echo $Data['ProductName'] ?>" readonly />
                                </div>
                              </div>
                              <hr class="mb-4">
                              <h2><b>Product Design Information</b></h2>
                              <h6><b>Note:</b> This a report for the new final design output from the Consultant.</h6>
                              <div class="row mt-4">
                                <div class="col-md-12">
                                  <h6 class="mb-2">Concept of Design</h6>
                                  <textarea class="form-control mb-3" name="ConceptDesign" rows="7" readonly><?php echo $Data['ReportConceptDesign'] ?></textarea>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <h6 class="mb-2">Size of the Product</h6>
                                  <div class="form-check" style="margin-left:30px;">
                                    <input class="form-check-input" type="radio" name="size" value="Small" <?php echo ($Data['ReportSize'] == 'small') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" style="font-size: 15px;">Small</label>
                                  </div>
                                  <div class="form-check" style="margin-left:30px;">
                                    <input class="form-check-input" type="radio" name="size" value="medium" <?php echo ($Data['ReportSize'] == 'medium') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" style="font-size: 15px;">Medium</label>
                                  </div>
                                  <div class="form-check" style="margin-left:30px;">
                                    <input class="form-check-input" type="radio" name="size" value="large" <?php echo ($Data['ReportSize'] == 'large') ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" style="font-size: 15px;">Large</label>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <h6 class="mb-2">Notes or Other Comments:</h6>
                                  <textarea class="form-control mb-3" name="Comment" rows="7" readonly><?php echo $Data['ReportComment'] ?></textarea>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                  <h6 class="mb-2">Dominant Color used in the design:</h6>
                                  <input class="form-control mb-3" type="text" name="DominantColor" id="tagline" value="<?php echo $Data['ReportDominantColor'] ?>" readonly />
                                </div>
                                <div class="col-md-6">
                                  <h6 class="mb-2">Color used in the design:</h6>
                                  <input type="text" id="displayColors" class="form-control mb-3" value="<?php echo $Data['ReportSelectedColor'] ?>" readonly>
                                </div>
                              </div>
                              <table class="table">
                                <!-- The table rows and cells will be generated by JavaScript -->
                              </table>
                              <hr>
                              <div class="text-center mb-4">
                                <h4><b>Final Output Design</b></h4>
                                <img src="..\uploaded_img\<?php echo $Data['ReportDraftImage'] ?>" class="img-fluid" width="250px" height="250px">
                              </div>


                              <div class="container">
                                <div class="text-center">
                                  <form method="POST" action="review_Assessment.php">
                                    <label for="decision">Stage Completion: Press Proceed to Mark Stage as Completed</label><br>
                                    <p style="font-size: 17px;"><b>Note: </b>If the Proceed button is disabled it means it's waiting for approval of MSME</p>
                                    <input name="user_id" type="hidden" value="<?php echo $Data['user_id']; ?>">
                                    <button type="submit" name="decision" class="btn btn-primary text-white" value="4"
                                      <?php echo ($Data['status'] == 3) ? 'disabled' : ''; ?>>Proceed</button>
                                  </form>
                                  <form method="POST" action="reject_Assessment.php">
                                    <input name="consultationID" type="hidden" value="<?php echo $Data['consultationID'] ?>">
                                    <input name="room_id" type="hidden" value="<?php echo $Data['room_id'] ?>">
                                    <button type="submit" name="action" class="btn btn-danger text-white" value="delete">Reject</button>
                                  </form>
                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                <?php else: ?>
                  <div class="col-lg-12 grid-margin stretch-card mt-3">
                    <div class="card">
                      <div class="card-body">
                        <h3 class="mb-2 pt-1"><b>Stage 3: Consultant Report</b></h3>
                        <p><b>Status:</b> Final Design Output Report</p>
                        <p>Waiting for Consultant Output</p>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>

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