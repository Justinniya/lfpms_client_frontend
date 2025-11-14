<?php
session_start();
include '../../session.php'; // Ensure database connection is included

try {
    // Prepare the query
    $stmt = $conn->prepare("
        SELECT 
            u.userid AS consultant_id,
            u.fname AS consultant_name,
            cq.id AS questionnaire_id,
            cq.name AS product_name,
            cq.SubmittionDate AS submission_date,
            cr.consultationID,
            r.id,
            cm.user_id AS chat_user_id
        FROM consultancyquestionnaire cq
        INNER JOIN consultation_report cr ON cq.id = cr.consultationID
        INNER JOIN room r ON cr.room_id = r.id
        INNER JOIN chatmember cm ON r.id = cm.room_ID
        INNER JOIN users u ON cm.user_id = u.userid
        WHERE u.usertype = 3  -- Only consultants
        AND u.userid = 81
        AND cq.status = 7      -- Only successfully developed products
        ORDER BY cq.SubmittionDate DESC;
    ");

    // Execute query
    $stmt->execute();

    // Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo "<h2>Consultants for Successfully Developed Products</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Consultant ID</th>
                    <th>Consultant Name</th>
                    <th>Questionnaire ID</th>
                    <th>Product Name</th>
                    <th>Submission Date</th>
                    <th>Consultation ID</th>
                    <th>Room ID</th>
                    <th>Chat User ID</th>
                </tr>";
        foreach ($results as $row) {
            echo "<tr>
                    <td>{$row['consultant_id']}</td>
                    <td>{$row['consultant_name']}</td>
                    <td>{$row['questionnaire_id']}</td>
                    <td>{$row['product_name']}</td>
                    <td>{$row['submission_date']}</td>
                    <td>{$row['consultationID']}</td>
                    <td>{$row['id']}</td>
                    <td>{$row['chat_user_id']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No successfully developed products found.</p>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
