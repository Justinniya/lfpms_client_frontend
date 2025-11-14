<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Local Food Product Management System - Login Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="dti.png"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->    
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->    
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    
<div class="limiter">
    <div class="container-login100" style="background:lightblue;">
        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            <form class="login100-form validate-form" method="post" action="conn.php">
            <a  style="float:right;color:black; margin-top:-50px;" href="../index.php"><i class="fa-solid fa-arrow-left"></i> Back</a>
                <span class="login100-form-title p-b-49">
                    <img src="dti.png" height="250">
                </span>
                <span class="login100-form-title p-b-49">
                    <h3 style="margin-top:-50px;">LFPMS Login <br> or use your account.</h3>
                </span>
                <!-- Username Field -->
                <div class="wrap-input100 validate-input m-b-23" data-validate="Username is required">
                    <span class="label-input100" style="font-size:20px;">Username</span>
                    <input class="input100"
                    style="font-size:20px;" 
                           type="text" 
                           name="username" 
                           placeholder="Type your Username" 
                           value="<?php echo isset($_GET['users']) ? htmlspecialchars($_GET['users']) : ''; ?>" 
                           required>
                    <span class="focus-input100" data-symbol="&#xf206;"></span>
                    <p class="text-danger mt-2"><?php if (isset($_GET['ErrorUser'])) { echo $_GET['ErrorUser']; } ?></p>
                </div>

                <!-- Password Field -->
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <span class="label-input100" style="font-size:20px;">Password</span>
                    <input class="input100" 
                    style="font-size:20px;"
                           type="password" 
                           name="password" 
                           placeholder="Type your password" 
                           required>
                    <span class="focus-input100" data-symbol="&#xf190;"></span>
                    <p class="text-danger mt-2"><?php if (isset($_GET['ErrorPass'])) { echo $_GET['ErrorPass']; } ?></p>
                </div>

                <div class="container-login100-form-btn mt-4">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" type="submit" name="login">
                            Login
                        </button>
                    </div>
                </div>  
                
                <div class="text-center mt-4">
                   <h5>or</h5>
                </div> 

                <div class="text-center mt-4">
                    <p>Doesn't have an account? <a href="signup.php" style="text-decoration:underline; color:blue;">Register.</a></p>
                </div> 
            </form>
        </div>
    </div>
</div>

    <div id="dropDownSelect1"></div>
    
    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

</body>
</html>
