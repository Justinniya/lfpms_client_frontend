<?php

session_start();
session_unset();
//End Session
session_destroy();
header("location: ../index.php?logout=Log-out Successfully");
?>