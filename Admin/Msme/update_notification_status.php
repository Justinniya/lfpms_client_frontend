<?php
session_start();
include '../../session.php'; // Ensure this contains your DB connection

header('Content-Type: application/json'); // Set response type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notification_id = $_POST['notification_id'] ?? null;
    $notification_type = $_POST['notification_type'] ?? null; // Get the type from AJAX request

    if ($notification_id) {
        try {
            if ($notification_type === 'chat_closed') {
                // Update room status to 1 (Closed)
                $stmt = $conn->prepare("UPDATE room SET status = 1 WHERE id = :id");
            } else {
                // Default case: update consultancyquestionnaire status
                $stmt = $conn->prepare("UPDATE consultancyquestionnaire SET status = 7 WHERE id = :id");
            }

            $stmt->bindParam(':id', $notification_id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(["success" => true]);
            exit();
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
            exit();
        }
    } else {
        echo json_encode(["success" => false, "error" => "Notification ID is missing"]);
        exit();
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit();
}
?>
