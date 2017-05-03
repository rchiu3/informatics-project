<?php
//Sets $StoreID to the StoreID that was passed through URL using the GET function
$StoreID = $_GET['StoreID'];
//Start session to keep track of customer
session_start();
//Add StoreID to Session so that it can be tracked and used for product display page
$_SESSION['StoreID'] = $StoreID;
//Send user to Product page  
header ('Location: Product.php');
exit;

?>