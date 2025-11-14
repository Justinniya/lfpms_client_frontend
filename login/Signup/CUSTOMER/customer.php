<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Local Food Product Management System - Sign Up Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="dti.png"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
    <!--===============================================================================================-->    
    <link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <!--===============================================================================================-->    
    <link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <!--===============================================================================================-->
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="w3-light-grey">

<div class="limiter">
    <div class="container-login100" style="background:lightblue;">
        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            <form class="login100-form validate-form" method="POST" action="regcustomer.php" onsubmit="return validateForm()">
                <span class="login100-form-title p-b-49" style="margin-top:-80px;">
                    <img src="dti.png" height="250">
                </span>
                <span class="login100-form-title p-b-49">
                    <h3 style="margin-top:-80px;">LFPMS Sign Up</h3>
                    <p class="mt-2">Create an account as a Customer</p>
                </span>
                <hr style="margin-top:-35px; color:black; height:2px; background-color:black; border:none;">
                <!-- 3-Column Grid Layout -->
                <div class="row mt-4">
                    <!-- First Column -->
                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">First Name</span>
                            <input class="input100" type="text" name="fname" placeholder="Enter your first name" required>
                        </div>
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Email</span>
                            <input class="input100" type="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Username</span>
                            <input class="input100" type="text" name="username" placeholder="Choose a username" required>
                            <small class="text-danger"><?php if (isset($_GET['ErrorUser1'])) { echo $_GET['ErrorUser1']; } ?></small>
                        </div>
                        
                        
                    </div>

                    <!-- Second Column -->
                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Middle Name</span>
                            <input class="input100" type="text" name="Mname" placeholder="Enter your middle name" required>
                        </div>
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Phone</span>
                            <input class="input100" type="number" name="phone" placeholder="Enter your phone number" required>
                        </div>
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Password</span>
                            <input class="input100" type="password" name="password" id="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <!-- Third Column -->
                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Last Name</span>
                            <input class="input100" type="text" name="Lname" placeholder="Enter your last name" required>
                        </div>
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Address</span>
                            <input class="input100" type="text" name="address" placeholder="Enter your address" required>
                        </div>
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Repeat Password</span>
                            <input class="input100" type="password" name="passwordCheck" id="passwordCheck" placeholder="Repeat your password" required>
                        </div>
                    </div>
                </div>
                
                <!-- Show Password -->
                <div class="form-check mb-3">
                    <input type="checkbox" style="margin-left:10px;" class="form-check-input" id="showPasswordCheckbox" onclick="togglePasswordVisibility()">
                    <label class="form-check-label" style="margin-left:10px;" for="showPasswordCheckbox">Show Password</label>
                </div>
                
                <!-- Terms and Conditions -->
                <div class="form-check mb-3">
                    <input type="checkbox" style="margin-left:10px;" class="form-check-input" id="termsCheckbox" required>
                    <label class="form-check-label" style="margin-left:10px;" for="termsCheckbox">
                        By clicking Register, I agree to the <a href="Terms.php" target="_blank">Terms and Conditions</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="container-login100-form-btn mt-4">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" type="submit" name="submit" id="submit" disabled>
                            Create Account
                        </button>
                    </div>
                </div>

                <div class="text-center mt-2">
                   <h6>or</h6>
                </div> 

                <div class="text-center mt-2">
                    <p>Already have an account? <a href="../../login.php" style="text-decoration:underline; color:blue;">Login.</a></p>
                </div> 

            </form>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        const password = document.getElementById('password').value;
        const passwordCheck = document.getElementById('passwordCheck').value;
        if (password !== passwordCheck) {
            alert('Passwords do not match.');
            return false;
        }
        return true;
    }

    function togglePasswordVisibility() {
        const password = document.getElementById('password');
        const passwordCheck = document.getElementById('passwordCheck');
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;
        passwordCheck.type = type;
    }

    document.getElementById('termsCheckbox').addEventListener('change', function() {
        document.getElementById('submit').disabled = !this.checked;
    });
</script>
</body>
</html>
