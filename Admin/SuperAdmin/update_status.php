<?php
include('connection/connection.php');
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$userid = $_POST['userid'];
$email = $_POST['email'];
$status = $_POST['status']; // The new status ('active' or 'inactive')

// Update the status of the user
$sql = "UPDATE users SET status = '$status' WHERE userid = '$userid'";
$updateQuery = mysqli_query($conn, $sql);

if ($updateQuery) {
    if ($status == '1') {
        echo '<script>alert("User account successfully activated!");</script>';
        
        // Send email notification for activation
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kiezerph@gmail.com';
            $mail->Password = 'abfobtnnfcxedvav';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('kiezerph@gmail.com', 'SYSTEM');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(false); // Plain text email
            $mail->Subject = 'Account Activation - Local Food Product Management System';
            
            $mail->Body = "Dear User,\n\n"
                . "Congratulations! Your account has been successfully activated.\n\n"
                . "You can now log in and start managing your food products on the Local Food Product Management System.\n\n"
                . "If you did not request this activation, please contact our support team immediately.\n\n"
                . "Best regards,\n"
                . "Local Food Product Management System Team";
            
            $mail->AltBody = $mail->Body; // Ensures the same message is sent as an alternative body.

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        
    } else {
        echo '<script>alert("User account successfully deactivated!");</script>';
        
        // Send email notification for deactivation
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kiezerph@gmail.com';
            $mail->Password = 'abfobtnnfcxedvav';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('kiezerph@gmail.com', 'SYSTEM');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Account Deactivation';
            $mail->Body    = 'Your account has been successfully deactivated.';
            $mail->AltBody = 'Your account has been successfully deactivated.';

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    echo '<script>window.location.href = "userz.php";</script>';
}
?>
