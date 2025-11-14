<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "lfpms";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $status = $_POST['status'];

    $sql = 'UPDATE users SET fname = :fname, Lname = :lname, Mname = :mname, address = :address, email = :email, phone = :phone, username = :username, password = :password, status = :status WHERE userid = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
    $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
    $stmt->bindParam(':mname', $mname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kiezerph@gmail.com';
            $mail->Password = 'abfobtnnfcxedvav';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('kiezerph@gmail.com', 'SYSTEM');
            $mail->addAddress($email);
            
            if ($status == 1) {
                $mail->Subject = 'Account Re Activation - Local Food Product Management System';
                $mail->Body = "Dear $fname,\n\nYour account has been Re activated!\n\nBest regards,\nLocal Food Product Management System Team";
            } elseif ($status == 2) {
                $mail->Subject = 'Account Deactivated';
                $mail->Body = "Dear $fname,\n\nYour account has been deactivated.\n\nIf you believe this is a mistake, please contact support.\n\nBest regards,\nLocal Food Product Management System Team";
            }
            
            $mail->isHTML(false);
            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        
        echo "<script>
            alert('Profile successfully updated!');
            window.location.href = 'userz.php';
        </script>";
    } else {
        echo "<script>
            alert('Failed to update profile');
            window.location.href = 'updateprofile.php';
        </script>";
    }
}
?>
