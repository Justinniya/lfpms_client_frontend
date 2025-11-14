<?php
include "../../../includes/dbcon.php";

// Function to handle file upload
function uploadFile($file, $uploadDir) {
    $errors = [];

    if ($file['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $file["tmp_name"];
        $name = basename($file["name"]);
        $target_file = $uploadDir . $name;

        if (move_uploaded_file($tmp_name, $target_file)) {
            return $target_file; // Return the filename if upload is successful
        } else {
            $errors[] = "Failed to upload $name.";
        }
    } elseif ($file['error'] != UPLOAD_ERR_NO_FILE) {
        $errors[] = "Error uploading " . $file['error'];
    }

    return ['uploaded' => null, 'errors' => $errors];
}

function handleUploadResult($uploadResult, &$errors) {
    if (!empty($uploadResult['errors'])) {
        $errors = array_merge($errors, $uploadResult['errors']);
    }
    return $uploadResult;
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get all values from the HTML form
    $email = $_POST['email'];
    $fname = $_POST['fname']; 
    $Mname = $_POST['Mname'];
    $Lname = $_POST['Lname'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $address = $_POST['address'];

    // Check if username exists
    $check_query = "SELECT username FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->execute([$username]);

    if ($check_stmt->rowCount() > 0) {
        header("location: msme.php?ErrorUser1=Username Already exists");
        exit();
    }

    try {
        // Insert user data into the database
        $stmt = $conn->prepare("INSERT INTO users (email, fname, Mname, Lname, address, phone, username, password, usertype, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 2, 0)");
        $stmt->execute([$email, $fname, $Mname, $Lname, $address, $phone, $username, $pass]);

        // Get the ID of the newly created user
        $id = $conn->lastInsertId();

        $uploadDir = "../../../admin/uploaded_file/";
        $errors = []; // Initialize errors array

        // Handle file uploads
        $BLcenseFile = $_FILES['bl'];
        $BLcenseFilename = $BLcenseFile["name"]; // Initialize filename
        if (!empty($BLcenseFile)) {
            $uploadResult = uploadFile($BLcenseFile, $uploadDir);
            $uploadResult = handleUploadResult($uploadResult, $errors);
            if (!empty($uploadResult['uploaded'])) {
                $BLcenseFilename = $uploadResult['uploaded'];
            }
        }

        $tdFile = $_FILES['td'];
        $tdFilename = $tdFile["name"]; // Initialize filename
        if (!empty($tdFile)) {
            $uploadResult = uploadFile($tdFile, $uploadDir);
            $uploadResult = handleUploadResult($uploadResult, $errors);
            if (!empty($uploadResult['uploaded'])) {
                $tdFilename = $uploadResult['uploaded'];
            }
        }

        $bpFile = $_FILES['bp'];
        $bpFilename = $bpFile["name"]; // Initialize filename
        if (!empty($bpFile)) {
            $uploadResult = uploadFile($bpFile, $uploadDir);
            $uploadResult = handleUploadResult($uploadResult, $errors);
            if (!empty($uploadResult['uploaded'])) {
                $bpFilename = $uploadResult['uploaded'];
            }
        }

        // Save file names for business information
        $BLcense = $BLcenseFilename;
        $TDocuments = $tdFilename;
        $BPermit = $bpFilename;

        // Insert business information into the database
        $stmt = $conn->prepare("INSERT INTO business_information (userid, NBusiness, BusinessAddress, TypeBusiness, brn, BLcense, TDocuments, BPermit) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $_POST['nameofbusiness'], $_POST['businessaddress'], $_POST['typeofbusiness'], $_POST['bnr'], $BLcense, $TDocuments, $BPermit]);

        echo "<script>
                alert('Registration is successful! Please Wait for the Account Approval');
                window.location.href='../../login.php';
            </script>";
        $conn->commit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>
