<?php
session_start();
include '../../session.php'; // Assuming this file contains database connection logic
$userty = $usertype;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $messageId = $_POST['message_id'];

    try {
        // Begin transaction for consistency
        $conn->beginTransaction();

        // Update appropriate status field based on user type
        if ($userty == 2) {
            // Update M_status to 1 for all messages in the same room as the message being marked as read
            $updateSql = "UPDATE `chat` SET `M_status` = 1 WHERE `room_id` IN (
                            SELECT `room_id` FROM `chat` WHERE `id` = :message_id
                          )";
        } elseif ($userty == 3) {
            // Update Con_stat to 1 for all messages in the same room as the message being marked as read
            $updateSql = "UPDATE `chat` SET `Con_stat` = 1 WHERE `room_id` IN (
                            SELECT `room_id` FROM `chat` WHERE `id` = :message_id
                          )";                        
        } 

        elseif ($userty == 0) { // Assuming 1 is the admin user type
            // Admin
            $updateSql = "UPDATE `chat` 
                          SET `ad_status` = 1
                          WHERE `room_id` IN (
                              SELECT `room_id` FROM `chat` WHERE `id` = :message_id
                          )";
        }
        else {
            // Handle other cases or provide a default action
            http_response_code(400);
            echo "Invalid user type provided.";
            exit; // Stop further execution
        }

        $stmt = $conn->prepare($updateSql);
        $stmt->bindParam(':message_id', $messageId);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Return success response
        http_response_code(200);
        echo "Message marked as read successfully!";
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $conn->rollBack();
        http_response_code(500);
        echo "Error marking message as read: " . $e->getMessage();
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo "Method not allowed";
}
?>