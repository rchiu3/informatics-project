<?php

    //Make sure user starts from blank slate by unsetting session variables and destroying the session
    session_start();
    if (isset($_SESSION['EmployeeEmail'])) {
        unset($_SESSION['EmployeeEmail']);
    }
    session_destroy();
    
    //Redirect user to homepage
    header("Location: CustomerHome.php");
    exit;

?>