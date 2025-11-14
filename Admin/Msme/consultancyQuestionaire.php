<?php
include "../../session.php";

date_default_timezone_set('Asia/Manila'); // Set timezone to Manila
$currentDateTime = date('Y-m-d H:i:s'); // Get current date and time

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $userid = $_POST['userid'];
    $address = $_POST['address'];
    $municipality = $_POST['municipality'];
    $phoneNumber = $_POST['phoneNumber'];
    $labelingFormat = $_POST['labelingformat'];
    $brandName = $_POST['brandName'];
    $productIdentity = $_POST['productIdentity'];
    $label1 = $_POST['label1'];
    $label2 = $_POST['label2'];
    $label3 = $_POST['label3'];
    $label4 = $_POST['label4'];
    $tagline = $_POST['tagline'];
    $netContent = $_POST['netContent'];
    $ingredients = $_POST['ingredients'];
    $expiryDate = $_POST['expiryDate'];
    $ProductName = $_POST['product_name'];
    $ProductID = $_POST['product'];
    $ProductDirect = $_POST['ProductDirect'];
    $ConceptDesign = $_POST['ConceptDesign'];
    $size = $_POST['size'];
    $DominantColor = $_POST['DominantColor'];
    $SelectedColor = $_POST['SelectedColor'];
    $Comment = $_POST['Comment'];

    // Handle file upload
    $draftImg = null;
    if (isset($_FILES['draft_img']) && $_FILES['draft_img']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = './uploaded_img/';
        $fileName = basename($_FILES['draft_img']['name']);
        $draftImg = $fileName;
        move_uploaded_file($_FILES['draft_img']['tmp_name'], $uploadDir . $fileName);
    }

    try {
        // Prepare SQL statement to insert feedback with datecreated
        $stmt = $conn->prepare("INSERT INTO ConsultancyQuestionnaire (
            name, user_id, address, municipality, phoneNumber, labelingFormat, brandName, 
            productIdentity, label1, label2, label3, label4, tagline, netContent, 
            ingredients, expiryDate, product_id, ProductName, DirectProduct, ConceptDesign, Size, 
            DominantColor, Comment, SelectedColor, draft_img, status, datecreated) 
            VALUES (:name, :userid, :address, :municipality, :phoneNumber, :labelingFormat, :brandName, 
            :productIdentity, :label1, :label2, :label3, :label4, :tagline, :netContent, :ingredients, 
            :expiryDate, :ProductID, :product, :ProductDirect, :ConceptDesign, :size, :DominantColor, 
            :Comment, :SelectedColor, :draft_img, '1', :datecreated)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':municipality', $municipality);
        $stmt->bindParam(':phoneNumber', $phoneNumber);
        $stmt->bindParam(':labelingFormat', $labelingFormat);
        $stmt->bindParam(':brandName', $brandName);
        $stmt->bindParam(':productIdentity', $productIdentity);
        $stmt->bindParam(':label1', $label1);
        $stmt->bindParam(':label2', $label2);
        $stmt->bindParam(':label3', $label3);
        $stmt->bindParam(':label4', $label4);
        $stmt->bindParam(':tagline', $tagline);
        $stmt->bindParam(':netContent', $netContent);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':expiryDate', $expiryDate);
        $stmt->bindParam(':product', $ProductName);
        $stmt->bindParam(':ProductID', $ProductID);
        $stmt->bindParam(':ProductDirect', $ProductDirect);
        $stmt->bindParam(':ConceptDesign', $ConceptDesign);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':DominantColor', $DominantColor);
        $stmt->bindParam(':Comment', $Comment);
        $stmt->bindParam(':SelectedColor', $SelectedColor);
        $stmt->bindParam(':draft_img', $draftImg);
        $stmt->bindParam(':datecreated', $currentDateTime);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>
                alert('Feedback submitted successfully!');
                window.location.href='assessment.php';
            </script>";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
