<?php
session_start();

include '../../session.php';

// Get current user ID from session
$currentUserId = $_SESSION['id']; // Adjust this line if the session key is different

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomName = $_POST['room_name'];

    // Get selected MSME and Consultant members
    $msmeMember = $_POST['msme_member'];  // Selected MSME member (usertype 2)
    $consultantMember = $_POST['consultant_member'];  // Selected Consultant member (usertype 3)

    // Create an array of member IDs to insert into the ChatMember table
    $memberIds = [$msmeMember, $consultantMember];

    // Ensure current user is included in the members list
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

        // Get the admin user ID (you can adjust the condition based on your admin criteria)
        $stmt = $conn->prepare("SELECT userid FROM users WHERE usertype = 1 LIMIT 1"); // Assuming usertype 1 is admin
        $stmt->execute();
        $adminUser = $stmt->fetch(PDO::FETCH_ASSOC);
        $adminUserId = $adminUser['userid'];

        // Insert welcome message into chat
        $welcomeMessage = "Welcome! Consultant and MSME, Establishing Communication has been established to conduct Product Development.";
        $stmt = $conn->prepare("INSERT INTO chat (room_id, user_id, message, created_at) VALUES (:room_id, :user_id, :message, NOW())");
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':user_id', $currentUserId); // Admin user ID
        $stmt->bindParam(':message', $welcomeMessage);
        $stmt->execute();

        // Redirect to the chat page with the room ID
        header("Location: productdevelopment.php");
        exit();
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
