<?php

$StoreID = $_GET['StoreID'];
session_start();
$_SESSION['StoreID'] = $StoreID;
header ('Location: Product.php');
exit;

?>