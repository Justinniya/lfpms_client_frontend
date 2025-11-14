<?php

include '../../session.php';

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data
    $Cname = $_POST['Cname'];
    $MSME = $_POST['MSME'];
    $product_id = $_POST['product_id'];
    $Pname = $_POST['Pname'];
    $Stime = $_POST['Stime'];
    $Product_Line = $_POST['Product_Line'];
    $Enterprise_Category = $_POST['Enterprise_Category'];
    $Currently_Exporting = $_POST['Currently_Exporting'];
    $ExportPlan = $_POST['ExportPlan'];
    $QUALITY_Score = $_POST['score1'];
    $QUALITY_Comment = $_POST['QUALITY_Comment'];
    $DESIGN_Score = $_POST['score2'];
    $DESIGN_Comment = $_POST['DESIGN_Comment'];
    $PACKAGING_Score = $_POST['score3'];
    $packagingComments = $_POST['packagingComments'];
    $MARKETABILITY_Score = $_POST['score4'];
    $marketabilityComments = $_POST['marketabilityComments'];
    $BRAND_Score_Score = $_POST['score5'];
    $brandComments = $_POST['brandComments'];
    $PRODUCTION_Score = $_POST['score6'];
    $productionComments = $_POST['productionComments'];
    $FINANCING_Score = $_POST['score7'];
    $financingComments = $_POST['financingComments'];
    $CULTURAL_Score = $_POST['score8'];
    $culturalComments = $_POST['culturalComments'];
    $INNOVATION_Score = $_POST['score9'];
    $Innovation = $_POST['Innovation'];
    $CUSTOMER_Score = $_POST['score10'];
    $CUSTOMER = $_POST['CUSTOMER'];
    $Designer = $_POST['Designer'];
    $total_score = $_POST['total_score'];
    $intensive = $_POST['intensive'];
    $Recommend = $_POST['Recommend'];
    $Enroll = $_POST['Enroll'];
    $ExamDate = $_POST['ExamDate'];
    $Province = $_POST['Province'];
    // $TimeEnd = $_POST['TimeEnd'];
    $EName = $_POST['EName'];
    $Signature = $_FILES['Signature'];

    // Save the signature file
    $signatureFolder = 'signature/';
    $signaturePath = $signatureFolder . basename($Signature['name']);
    if (move_uploaded_file($Signature['tmp_name'], $signaturePath)) {
        // Prepare the insert statement
        $sql = "INSERT INTO Evaluation (
            Cname, MSME, product_id, Pname, Stime, Product_Line, Enterprise_Category, 
            Currently_Exporting, ExportPlan, QUALITY_Score, QUALITY_Comment, DESIGN_Score, 
            DESIGN_Comment, PACKAGING_Score, packagingComments, MARKETABILITY_Score, 
            marketabilityComments, BRAND_Score_Score, brandComments, PRODUCTION_Score, 
            productionComments, FINANCING_Score, financingComments, CULTURAL_Score, 
            culturalComments, INNOVATION_Score, Innovation, CUSTOMER_Score, CUSTOMER, 
            Designer, total_score, intensive, Recommend, Enroll, ExamDate, Province, EName, Signature
        ) VALUES (
            :Cname, :MSME, :product_id, :Pname, :Stime, :Product_Line, :Enterprise_Category, 
            :Currently_Exporting, :ExportPlan, :QUALITY_Score, :QUALITY_Comment, :DESIGN_Score, 
            :DESIGN_Comment, :PACKAGING_Score, :packagingComments, :MARKETABILITY_Score, 
            :marketabilityComments, :BRAND_Score_Score, :brandComments, :PRODUCTION_Score, 
            :productionComments, :FINANCING_Score, :financingComments, :CULTURAL_Score, 
            :culturalComments, :INNOVATION_Score, :Innovation, :CUSTOMER_Score, :CUSTOMER, 
            :Designer, :total_score, :intensive, :Recommend, :Enroll, :ExamDate, :Province, :EName, :Signature
        )";

        try {
            $stmt = $conn->prepare($sql);
            
            // Bind the values to the statement
            $stmt->bindParam(':Cname', $Cname);
            $stmt->bindParam(':MSME', $MSME);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':Pname', $Pname);
            $stmt->bindParam(':Stime', $Stime);
            $stmt->bindParam(':Product_Line', $Product_Line);
            $stmt->bindParam(':Enterprise_Category', $Enterprise_Category);
            $stmt->bindParam(':Currently_Exporting', $Currently_Exporting);
            $stmt->bindParam(':ExportPlan', $ExportPlan);
            $stmt->bindParam(':QUALITY_Score', $QUALITY_Score);
            $stmt->bindParam(':QUALITY_Comment', $QUALITY_Comment);
            $stmt->bindParam(':DESIGN_Score', $DESIGN_Score);
            $stmt->bindParam(':DESIGN_Comment', $DESIGN_Comment);
            $stmt->bindParam(':PACKAGING_Score', $PACKAGING_Score);
            $stmt->bindParam(':packagingComments', $packagingComments);
            $stmt->bindParam(':MARKETABILITY_Score', $MARKETABILITY_Score);
            $stmt->bindParam(':marketabilityComments', $marketabilityComments);
            $stmt->bindParam(':BRAND_Score_Score', $BRAND_Score_Score);
            $stmt->bindParam(':brandComments', $brandComments);
            $stmt->bindParam(':PRODUCTION_Score', $PRODUCTION_Score);
            $stmt->bindParam(':productionComments', $productionComments);
            $stmt->bindParam(':FINANCING_Score', $FINANCING_Score);
            $stmt->bindParam(':financingComments', $financingComments);
            $stmt->bindParam(':CULTURAL_Score', $CULTURAL_Score);
            $stmt->bindParam(':culturalComments', $culturalComments);
            $stmt->bindParam(':INNOVATION_Score', $INNOVATION_Score);
            $stmt->bindParam(':Innovation', $Innovation);
            $stmt->bindParam(':CUSTOMER_Score', $CUSTOMER_Score);
            $stmt->bindParam(':CUSTOMER', $CUSTOMER);
            $stmt->bindParam(':Designer', $Designer);
            $stmt->bindParam(':total_score', $total_score);
            $stmt->bindParam(':intensive', $intensive,);
            $stmt->bindParam(':Recommend', $Recommend);
            $stmt->bindParam(':Enroll', $Enroll);
            $stmt->bindParam(':ExamDate', $ExamDate);
            $stmt->bindParam(':Province', $Province);
            // $stmt->bindParam(':TimeEnd', $TimeEnd);
            $stmt->bindParam(':EName', $EName);
            $stmt->bindParam(':Signature', $signaturePath);

            // Execute the statement
            $stmt->execute();
            echo '<script>alert("User account successfully activated!");</script>
            <script>window.location.href = "ViewEvaluation.php";</script>
            ';
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Failed to upload the signature file.";
    }
}
?>
