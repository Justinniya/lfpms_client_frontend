<?php
include('connection/connection.php'); // Ensure $con is your mysqli connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $consultationID = $_POST['consultationID'];
    $room_id = $_POST['room_id'];
    $action = $_POST['action'];

    if ($action == 'delete') {
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
                // Update consultancyquestionnaire status to 3
                $query1 = "UPDATE `consultancyquestionnaire` 
                           SET `status` = 3, `dateend` = NOW() 
                           WHERE `user_id` = ? 
                           ORDER BY `id` DESC 
                           LIMIT 1";
                $stmt1 = $conn->prepare($query1);
                $stmt1->bind_param('i', $Id);
                $stmt1->execute();

                // Revert status to 3
                $revertStatusQuery = "UPDATE `consultancyquestionnaire` SET `status` = 3 WHERE `user_id` = ?";
                $revertStmt = $conn->prepare($revertStatusQuery);
                $revertStmt->bind_param('i', $Id);
                $revertStmt->execute();

                $conn->commit();
                header("Location: productdevelopment.php");
                exit();
            }
        }
    }
}
?>
