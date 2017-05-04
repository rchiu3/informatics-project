<?php

    //Make sure user starts from blank slate by unsetting session variables and destroying the session
    session_start();
    if (isset($_SESSION['CustomerEmail'])) {
        unset($_SESSION['CustomerEmail']);
    }
    session_destroy();
    
    //Redirect user to homepage
    header("Location: GrocerHome.php");
    exit;

?>