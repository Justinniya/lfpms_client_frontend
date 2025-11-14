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
    <link rel="stylesheet" type="text/css" href="../onts/font-awesome-4.7.0/css/font-awesome.min.css">
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
            <form class="login100-form validate-form" method="POST" action="regmsme.php" onsubmit="return validateForm()" enctype="multipart/form-data">
                <span class="login100-form-title p-b-49" style="margin-top:-80px;">
                    <img src="dti.png" height="250">
                </span>
                <span class="login100-form-title p-b-49">
                    <h3 style="margin-top:-80px;">LFPMS Sign Up</h3>
                    <p class="mt-2">Create an account for MSMEs</p>
                </span>
                <hr style="margin-top:-35px; color:black; height:2px; background-color:black; border:none;">

                <!-- Personal Information -->
                <h4 class="mt-4">Personal Information</h4>
                <div class="row mt-4">
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

                <!-- Business Information -->
                <h4 class="mt-4">Business Information</h4>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Name of Business</span>
                            <input class="input100" type="text" name="nameofbusiness" placeholder="Enter the name of your business" required>
                        </div>
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Business Address</span>
                            <input class="input100" type="text" name="businessaddress" placeholder="Enter your business address" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Type of Business</span>
                            <input class="input100" type="text" name="typeofbusiness" placeholder="Enter your business type" required>
                        </div>
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Business Registration Number</span>
                            <input class="input100" type="text" name="bnr" placeholder="Enter your business registration number" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Tax Identification Number</span>
                            <input class="input100" type="text" name="tin" placeholder="Enter your Tax ID number" required>
                        </div>
                    </div>
                </div>

                <!-- Verification Documents -->
                <h4 class="mt-4">Verification Documents</h4>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Business License</span>
                            <input class="input100" type="file" name="bl" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Tax Documents</span>
                            <input class="input100" type="file" name="td" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="wrap-input100 validate-input m-b-23">
                            <span class="label-input100">Business Permit</span>
                            <input class="input100" type="file" name="bp" required>
                        </div>
                    </div>
                </div>

                <!-- Show Password -->
                <div class="form-check mb-3">
                    <input type="checkbox" style="margin-left:10px;" class="form-check-input" id="showPasswordCheckbox" onclick="togglePasswordVisibility()">
                    <label class="form-check-label" style="margin-left:10px;" for="showPasswordCheckbox">Show Password</label>
                </div>

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
                
                <!-- Optional: Already have an account -->
                <div class="text-center mt-2">
                    <p>Already have an account? <a href="../../login.php" style="text-decoration:underline; color:blue;">Login.</a></p>
                </div> 
            </form>

            <script>
                // Enable submit button when checkbox is checked
                document.getElementById('termsCheckbox').addEventListener('change', function() {
                    var submitButton = document.getElementById('submit');
                    submitButton.disabled = !this.checked;
                });

                // Validate passwords match
                function validateForm() {
                    var password = document.getElementById('password').value;
                    var passwordCheck = document.getElementById('passwordCheck').value;
                    if (password !== passwordCheck) {
                        alert("Passwords do not match!");
                        return false;
                    }
                    return true;
                }
            </script>
        </div>
    </div>
</div>

<!--===============================================================================================-->
<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/daterangepicker/moment.min.js"></script>
<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="../vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="../js/main.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
