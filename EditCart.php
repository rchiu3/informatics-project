<?php

session_start();
$CustomerEmail = $_SESSION['CustomerEmail'];
$CustomerID = $_SESSION['CustomerID'];
$OrderID = $_SESSION['OrderID'];
$StoreID = $_SESSION['StoreID'];

//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');
//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

if(isset($_POST['Update'])) {
    
    $Quantity = $_POST['Quantity'];
    $OrderLineID = $_POST['OrderLineID'];

    if(!$Quantity) {
        header ('Location: RemoveCart.php?OrderLineID=' . $OrderLineID);
    }
    else {
    $query = "UPDATE OrderLine SET Quantity = " . $Quantity . " WHERE OrderLineID = " . $OrderLineID . ";";
    queryDB($query,$db);
    
    header ('Location: ShoppingCart.php');
    exit;
    }
}

?>