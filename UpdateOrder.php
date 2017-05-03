<?php

//This file updates an order's status and send user back to Grocer Order overview
// include database information
include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

// php to update order status

    if (isset($_POST['Update'])) {
        
        //get new order status and order id of order that needs to be updated
        $OrderStatus = $_POST['OrderStatus'];
        $OrderID = $_POST['OrderID'];
        
        //generate SQL query to update order then send user back to Order Details
        $query = "UPDATE Order_T SET OrderStatus = '" . $OrderStatus . "' WHERE OrderID = " . $OrderID . ";";
        queryDB($query,$db);
        header ('Location: GrocerOrders.php');
        exit;
    }

?>