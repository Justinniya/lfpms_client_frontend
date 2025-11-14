<?php
session_start();
include '../../session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['room_id'];
    $message = $_POST['message'];
    $userId = $_SESSION['id']; // Get the user ID from session

    // Handle file upload if exists
    $filePath = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = './../uploaded_file/'; // Ensure this directory exists and is writable
        $filePath = $uploadDir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
    }

    // Insert the message into the database
    $stmt = $conn->prepare("INSERT INTO Chat (room_id, user_id, message, file_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$roomId, $userId, $message, $filePath]);

    // Fetch the username to include in the response
    $userStmt = $conn->prepare("SELECT username FROM users WHERE userid = ?");
    $userStmt->execute([$userId]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    // Return the message with the username
    echo htmlspecialchars($user['username']) . ": " . htmlspecialchars($message); // Adjust if needed

    // If there's a file, also return the file link
    if ($filePath) {
        echo '<br><a href="' . htmlspecialchars($filePath) . '" target="_blank">' . htmlspecialchars(basename($filePath)) . '</a>';
    }
}
?>
