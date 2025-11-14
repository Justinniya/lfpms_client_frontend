<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location: ../../index.php");
}
include '../../session.php';

// Assuming the PDO connection is already set in $conn
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the POST request
    $consultationID = $_POST['consultationID'];
    $conID = $_POST['conID']; // Added conID parameter
    $room_id = $_POST['room_id'];
    $ConceptDesign = $_POST['ConceptDesign'];
    $size = $_POST['size'];
    $Comment = $_POST['Comment'];
    $DominantColor = $_POST['DominantColor'];
    $SelectedColor = $_POST['SelectedColor'];

    // Handle the file upload
    if (isset($_FILES['draft_img']) && $_FILES['draft_img']['error'] == 0) {
        // Extract the file name without the path
        $draft_img = basename($_FILES['draft_img']['name']);

        // Define the target directory for the uploaded file
        $target_dir = "./../uploaded_img/";
        $target_file = $target_dir . $draft_img;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['draft_img']['tmp_name'], $target_file)) {
            // Prepare the SQL insert query with conID included
            $sql = "INSERT INTO consultation_report (consultationID, conID, ConceptDesign, size, Comment, DominantColor, SelectedColor, draft_img, room_id, submission_datetime)
                    VALUES (:consultationID, :conID, :ConceptDesign, :size, :Comment, :DominantColor, :SelectedColor, :draft_img, :room_id, NOW())";

            // Prepare the statement
            $stmt = $conn->prepare($sql);

            // Bind the parameters
            $stmt->bindParam(':consultationID', $consultationID);
            $stmt->bindParam(':conID', $conID);
            $stmt->bindParam(':ConceptDesign', $ConceptDesign);
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':Comment', $Comment);
            $stmt->bindParam(':DominantColor', $DominantColor);
            $stmt->bindParam(':SelectedColor', $SelectedColor);
            $stmt->bindParam(':draft_img', $draft_img);
            $stmt->bindParam(':room_id', $room_id);

            // Execute the insert statement
            if ($stmt->execute()) {
                echo "Record inserted successfully";

                // Update the Con_status to 1 based on room_id
                $updateSql = "UPDATE room SET Con_status = 1 WHERE id = :room_id";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bindParam(':room_id', $room_id);

                if ($updateStmt->execute()) {
                    header("Location: DevReport.php");
                } else {
                    echo "Error updating Con_status.";
                }
            } else {
                echo "Error inserting record.";
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or file upload error.";
    }
}
?>
