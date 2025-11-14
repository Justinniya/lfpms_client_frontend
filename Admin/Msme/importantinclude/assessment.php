<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location: ../../index.php");
    exit();
}
include '../../session.php';

function getLoggedInUser($conn, $user_id)
{
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);

try {
    // Assuming $conn is your existing PDO connection
    $stmt = $conn->prepare("SELECT `product_id`, `productName` FROM `products` WHERE `msme_id` = :msme_id");
    $stmt->bindParam(':msme_id', $Id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all products for the current user
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$query = "
    SELECT `id`,`status`, `product_id` 
    FROM `consultancyquestionnaire` 
    WHERE user_id = :user_id AND `status` IN (1, 2, 3, 4, 44) 
    ORDER BY `SubmittionDate` DESC 
    LIMIT 1
";
$stmt = $conn->prepare($query);
$stmt->execute(['user_id' => $Id]);
$status12 = $stmt->fetch(PDO::FETCH_ASSOC);

// Access the status and product ID
$status = $status12['status'] ?? null;
$productId = $status12['product_id'] ?? null;
$consultationID = $status12['id'] ?? null;


// Check for status 0
$queryStatus0 = "SELECT `status` FROM `consultancyquestionnaire` WHERE user_id = :user_id AND status = 0";
$stmtStatus0 = $conn->prepare($queryStatus0);
$stmtStatus0->execute(['user_id' => $Id]);
$status0 = $stmtStatus0->fetch(PDO::FETCH_ASSOC);

// Form submission for deleting status 0 records
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare delete query to remove the record where status is 0
    $deleteQuery = "DELETE FROM `consultancyquestionnaire` WHERE user_id = :user_id AND status = 0";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute(['user_id' => $Id]);

    // Redirect after deletion to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>