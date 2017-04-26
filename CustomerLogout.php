<?php
    //Log user out by unsetting session variable and destroying the session
    session_start();
    if (isset($_SESSION['CustomerEmail']))
    {
        unset($_SESSION['CustomerEmail']);
    }
    session_destroy();
     
    //Redirect user to login
    header("Location: CustomerHome.php");
    exit;
?>