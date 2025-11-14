<?php
session_start();
include '../../session.php'; // Assuming this file contains database connection logic

try {
    // Prepare SQL statement to fetch the latest unread message for each room for the current user
    $sql = "SELECT c1.`id` AS message_id, c1.`room_id`, c1.`user_id`, c1.`message`, c1.`file_path`, c1.`created_at`,
                   r.`id` AS room_id, r.`name`, r.`created_at` AS room_created_at, r.`status`
            FROM `chat` c1
            INNER JOIN (
                SELECT `room_id`, MAX(`created_at`) AS latest_message_time
                FROM `chat`
                GROUP BY `room_id`
            ) c2 ON c1.`room_id` = c2.`room_id` AND c1.`created_at` = c2.`latest_message_time`
            INNER JOIN `chatmember` cm ON c1.`room_id` = cm.`room_id`
            INNER JOIN `room` r ON c1.`room_id` = r.`id`
            WHERE cm.`user_id` = :user_id AND r.`status` = 0
            ORDER BY c1.`created_at` DESC"; // SQL query to fetch latest unread message for each room for current user

    // Prepare and execute the statement using PDO
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $Id); // Assuming user_id is stored in session

    $stmt->execute();
    
    // Fetch all rows as associative array
    $latestMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare SQL statement to count total unread messages for current user's chats
    $sqlCount = "SELECT COUNT(*) AS total_messages
                 FROM `chat` c
                 INNER JOIN `chatmember` cm ON c.`room_id` = cm.`room_id`
                 INNER JOIN `room` r ON c.`room_id` = r.`id`
                 WHERE cm.`user_id` = :user_id AND r.`status` = 0 AND c.`ad_status` = 0 AND c.`user_id` != :user_id";

    // Prepare and execute the count statement
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bindParam(':user_id', $Id); // Assuming user_id is stored in session
    $stmtCount->execute();

    // Fetch the total unread message count
    $totalMessages = $stmtCount->fetch(PDO::FETCH_ASSOC)['total_messages'];

    // Return latest unread messages and total unread count as JSON
    echo json_encode([
        'count' => $totalMessages,
        'messages' => $latestMessages
    ]);
} catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
}
?>