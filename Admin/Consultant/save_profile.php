<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

// DB Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "lfpms";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to get logged in user
function getLoggedInUser($conn, $user_id) {
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$userId = $_SESSION['id'];
$user = getLoggedInUser($conn, $userId);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $profileImagePath = null;

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmp = $_FILES['profile_image']['tmp_name'];
        $fileName = basename($_FILES['profile_image']['name']);
        $targetFilePath = $uploadDir . time() . '_' . $fileName;

        if (move_uploaded_file($fileTmp, $targetFilePath)) {
            $profileImagePath = $targetFilePath;
        }
    }

    $sql = 'UPDATE users SET fname = :fname, Lname = :lname, Mname = :mname, address = :address, email = :email, phone = :phone, username = :username, password = :password';
    if ($profileImagePath) {
        $sql .= ', profile_image = :profile_image';
    }
    $sql .= ' WHERE userid = :id';

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
    $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
    $stmt->bindParam(':mname', $mname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    if ($profileImagePath) {
        $stmt->bindParam(':profile_image', $profileImagePath, PDO::PARAM_STR);
    }

    if ($stmt->execute()) {
        echo "<script>
            alert('Profile successfully updated!');
            window.location.href = 'account.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Failed to update profile');
            window.location.href = 'updateprofile.php';
        </script>";
        exit;
    }
}
?>