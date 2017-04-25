<?php

    //Log user out by unsetting session variable and destroying the session
    session_start();
    if (isset($_SESSION['EmployeeEmail'])) {
        unset($_SESSION['EmployeeEmail']);
    }
    session_destroy();
    
    //Redirect user to login
    header("Location: GrocerHome.php");
    exit;

?>