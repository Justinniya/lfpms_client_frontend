<?php
session_start();
include '../../session.php'; // Assuming this file contains database connection logic

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'])) {
    $messageId = $_POST['message_id'];

    try {
        // Update MessageReadStatus or your message table to mark message as read
        $stmt = $conn->prepare("UPDATE MessageReadStatus SET read_at = NOW() WHERE message_id = :message_id AND user_id = :user_id");
        $stmt->bindParam(':message_id', $messageId);
        $stmt->bindParam(':user_id', $_SESSION['id']); // Assuming user_id is stored in session
        $stmt->execute();

        // You can optionally handle success response or logging here
        echo "Message marked as read successfully!";
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle invalid request method or missing parameters
    echo "Invalid request.";
}
?>
