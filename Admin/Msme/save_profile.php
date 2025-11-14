<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "lfpms";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<?php
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

    // Handle profile image upload if a file is uploaded
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
        }

        $fileTmp = $_FILES['profile_image']['tmp_name'];
        $fileName = basename($_FILES['profile_image']['name']);
        $targetFilePath = $uploadDir . time() . '_' . $fileName;

        if (move_uploaded_file($fileTmp, $targetFilePath)) {
            $profileImagePath = $targetFilePath;
        }
    }

    // Base SQL without image
    $sql = 'UPDATE users SET fname = :fname, Lname = :lname, Mname = :mname, address = :address, email = :email, phone = :phone, username = :username, password = :password';

    // If image was uploaded, include it in the update
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
    } else {
        echo "<script>
            alert('Failed to update profile');
            window.location.href = 'updateprofile.php';
        </script>";
    }
}
?>
