<?php
include('connection/connection.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['decision'])) {
    // Get the decision value from the button click (2 for Approve, 3 for Decline)
    $status = $_POST['decision'];
    $Id = $_POST['user_id'];

    // Start transaction to ensure both queries execute together
    $conn->begin_transaction();

    try {
        // Query 1: Update consultancyquestionnaire (set status and dateend)
        $query1 = "UPDATE `consultancyquestionnaire` 
                   SET `status` = ?, `dateend` = NOW() 
                   WHERE `user_id` = ? 
                   ORDER BY `id` DESC 
                   LIMIT 1";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param('ii', $status, $Id);
        $stmt1->execute();

        // Query 2: Update product_updates (set is_read = 1)
        $query2 = "UPDATE `product_updates` 
                   SET `is_read` = 1 
                   WHERE `product_id` IN (
                       SELECT `product_id` FROM `products` WHERE `msme_id` = ?
                   ) 
                   AND `is_read` = 0";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param('i', $Id);
        $stmt2->execute();

        // Commit transaction if both queries succeed
        $conn->commit();

        // Redirect after successful update
        header("Location: ProductDevelopment.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction if any query fails
        $conn->rollback();
        echo "Error updating records: " . $e->getMessage();
    }
}
?>
