<?php
include "../../../includes/dbcon.php";

try {
    
    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $fname = $_POST['fname'];
        $Mname = $_POST['Mname'];
        $Lname = $_POST['Lname'];
        $username = $_POST['username'];
        $phone = $_POST['phone'];
        $pass = $_POST['password'];
        $address = $_POST['address'];
        
        $check_query = "SELECT username FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->execute([$username]);

        if ($check_stmt->rowCount() > 0) {
            header(
                "location: Customer.php?ErrorUser1=Username Already exists",
            );
            exit();
        }

        // SQL query to insert data
        $sql = "INSERT INTO users (email, fname, Mname, Lname, address, username, password, phone, usertype, status) VALUES (:email, :fname, :Mname, :Lname, :address, :username, :password, :phone, 1, 0)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters to the SQL query
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':Mname', $Mname);
        $stmt->bindParam(':Lname', $Lname);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $pass);  // Note: Consider hashing passwords before storing them
        $stmt->bindParam(':phone', $phone);
        

        // Execute the statement
        $stmt->execute();

        // Check if the registration is successful
        if ($stmt->rowCount() > 0) {
            echo "<script>
                    alert('Registration is successful! Pls Wait for Account Approval.');
                    window.location.href='../../login.php';
                </script>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection (optional, PDO closes connection automatically at the end of the script)
$pdo = null;
?>
