<?php
include 'includes/dbcon.php';
$ssid = $_SESSION['id'];

try {

    $sql = "SELECT userid, username, password, fname, Lname, Mname, phone, email, usertype, address, district
            FROM users
            WHERE userid = :ssid";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ssid', $ssid, PDO::PARAM_INT);
    $stmt->execute();

    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_info) {
        $Id = $user_info['userid'];
        $User = $user_info['username'];
        $Pass = $user_info['password'];
        $Name = $user_info['fname'];
        $LName = $user_info['Lname'];
        $MName = $user_info['Mname'];
        $Cont = $user_info['phone'];
        $Mail = $user_info['email'];
        $address = $user_info['address'];
        $district = $user_info['district'];  
        $usertype = $user_info['usertype']; 
    } else {
        // Handle the case where no user information is found
        // You can set default values or redirect as needed.
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
