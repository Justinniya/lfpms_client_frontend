<?php
include('connection/connection.php'); // Ensure $con is your mysqli connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $consultationID = $_POST['consultationID'];
    $room_id = $_POST['room_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        // Approve action: Update status to 44 in consultancyquestionnaire
        $status = 44;
        $query = "UPDATE `consultancyquestionnaire` 
                  SET `status` = ? 
                  WHERE `id` = ? 
                  ORDER BY `id` DESC 
                  LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $status, $consultationID);

        if ($stmt->execute()) {
            header("Location: assessment.php");
            exit();
        } else {
            echo "Error updating status: " . $stmt->error;
        }

    } elseif ($action == 'delete') {
        // Step 1: Update the Con_status in room to 0
        $updateRoomQuery = "UPDATE `room` SET `Con_status` = 0 WHERE `id` = ?";
        $updateStmt = $conn->prepare($updateRoomQuery);
        $updateStmt->bind_param('i', $room_id);

        if ($updateStmt->execute()) {
            // Step 2: Delete entry from consultation_report based on consultationID
            $deleteQuery = "DELETE FROM `consultation_report` WHERE `consultationID` = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param('i', $consultationID);

            if ($deleteStmt->execute()) {
                header("Location: assessment.php");
                exit();
            } else {
                echo "Error deleting record: " . $deleteStmt->error;
            }
        } else {
            echo "Error updating room Con_status: " . $updateStmt->error;
        }
    }
}
?>
