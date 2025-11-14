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

class LoginHandler {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        session_start();
    }

    public function processLogin($user, $pass) {
        $sql = "SELECT userid, username, password, fname, phone, email, usertype, address, district FROM users WHERE BINARY username=:user and status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $storedPassword = $userRow['password']; // Storing the password from the database

            if ($pass === $storedPassword) { // Comparing the input password with the stored password
                $role = $userRow['usertype'];

                $_SESSION['id'] = $userRow['userid'];

                switch ($role) {
                    case 0:
                        header("location: ../Admin/SuperAdmin/superadmin_index.php");
                        break;
                    case 1:
                        header("location: ../Customers/customer_index.php");
                        break;
                    case 2:
                        header("location: ../Admin/Msme/msme_index.php");
                        break;
                    case 3:
                        header("location: ../Admin/Consultant/consultant_index.php");
                        break;
                    default:
                        header("location: ../index.php");
                        break;
                }
            } else {
                header("location: login.php?ErrorPass=Password is incorrect&users=" . $user);
            }
        } else {
            header("location: login.php?ErrorUser=Username does not exist");
        }
    }
}

// Usage:
$loginHandler = new LoginHandler($conn);
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $loginHandler->processLogin($user, $pass);
}
?>
