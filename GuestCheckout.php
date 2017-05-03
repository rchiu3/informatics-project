<?php
//Start session to track Guest/Customer
    session_start();
	$StoreID = $_SESSION['StoreID'];
	$CustomerEmail = $_SESSION['CustomerEmail'];
	$OrderID = $_SESSION['OrderID'];
    // include config.php and dbutils.php
    include_once('config.php');
    include_once('dbutils.php');
    // connect to database
    $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
    //Assigns generic values for guest in the name, address, and email field so that the guest can be assigned a CustomerID
    $query = "INSERT INTO Customer(CustomerName, CustomerAddress, CustomerEmail) VALUES ('Guest', 'Guest Address', 'Guest Email');";
    //run query
    queryDB($query, $db);
    //Get the last CustomerID in Customer Table and add it to the session 
    $_SESSION['CustomerID'] = mysqli_insert_id($db);
    //Send guest to checkout page with a customerID in session
    header('Location: Checkout.php');
    exit;
?>