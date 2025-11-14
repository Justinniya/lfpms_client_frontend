<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../session.php';

// Get current user ID from session
$currentUserId = $_SESSION['id']; // Adjust this line if the session key is different

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomName = $_POST['room_name'];
    $memberId = $_POST['member'];
    
    // Ensure current user is included in the members list
    $memberIds = [$memberId];
    if (!in_array($currentUserId, $memberIds)) {
        $memberIds[] = $currentUserId;
    }

    try {
        // Create the room
        $stmt = $conn->prepare("INSERT INTO Room (name) VALUES (:name)");
        $stmt->bindParam(':name', $roomName);
        $stmt->execute();
        
        // Get the last inserted room ID
        $roomId = $conn->lastInsertId();

        // Add members to the room
        foreach ($memberIds as $memberId) {
            $stmt = $conn->prepare("INSERT INTO ChatMember (room_id, user_id) VALUES (:room_id, :user_id)");
            $stmt->bindParam(':room_id', $roomId);
            $stmt->bindParam(':user_id', $memberId);
            $stmt->execute();
        }

        header("Location: chat.php?room_id=$roomId"); // Redirect to chat page
        exit();
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
