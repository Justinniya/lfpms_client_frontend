<?php
session_start();
include '../../session.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (isset($_POST['room_id'])) {
    $room_id = $_POST['room_id'];
    
    $stmt = $conn->prepare("UPDATE room SET status = 1 WHERE id = ?");
    if ($stmt->execute([$room_id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update room status']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Room ID not provided']);
}
?>
