<?php include 'importantinclude/assessment.php'; ?>
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

        <?php include 'importantinclude/topbar.php'; ?>

        <div class="container-fluid page-body-wrapper">

            <?php include 'importantinclude/sidebar.php'; ?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="home-tab">
                            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Assessment</a>
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
                                                <div id="mainContent">

                                                    <?php if ($status12): ?>
                                                        <div class="container">
                                                            <h4><b>Note: You can only submit one assessment at a time.</b></h4>
                                                            <hr>
                                                            <?php if ($status12['status'] == 1): ?>
                                                                <h3 class="mb-2 pt-4"><b>Stage 1: Assessment</b></h3>

                                                                <p><b>Status:</b> Waiting for Approval</p>
                                                            <?php elseif ($status12['status'] == 2): ?>
                                                                <h4 class="mb-2 pt-4"><b>Stage 2: Communication</b></h4>

                                                                <p><b>Status:</b> Communicating to Consultant</p>
                                                            <?php elseif ($status12['status'] == 44): ?>
                                                                <h4 class="mb-2 pt-4"><b>Stage 3: Consultant Report</b></h4>

                                                                <p><b>Status:</b> Waiting for the Approval of Consultation Report from the Admin(DTI) to Proceed to the next stage.</p>
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

                                                                    <?php include 'steps/ifstep3.php'; ?>

                                                                <?php else: ?>

                                                                    <?php include 'steps/elsestep3.php'; ?>

                                                                <?php endif; ?>

                                                            <?php elseif ($status12['status'] == 4): ?>

                                                                <?php include 'steps/step4.php'; ?>

                                                            <?php endif; ?>
                                                        </div>
                                                    <?php elseif ($status0): ?>
                                                        <!-- Display the message for status 0 and the delete form -->
                                                        <?php include 'steps/displaymessstatus0.php'; ?>
                                                        <!-- If no relevant status, display default content -->
                                                    <?php else: ?>
                                                        <?php include 'steps/defaultstatus.php'; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php include 'importantinclude/footer.php'; ?>
                            </div>
                        </div>
                    </div>
                    <?php include 'importantinclude/script.php'; ?>
                </div>
</body>

</html>