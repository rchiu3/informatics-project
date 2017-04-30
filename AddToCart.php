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

if(isset($_POST['add']))
{
$Quantity = $_POST['Quantity'];
$ProductID = $_POST['ProductID'];

	if(isset($_SESSION['CustomerEmail']))
	{
		$query = "SELECT CustomerID, Paid FROM Order_T WHERE CustomerID = " . $CustomerID . " AND Paid = 0;";
		$result = queryDB($query, $db);
		if(nTuples($result) == 0)
			{
			$query = "INSERT INTO Order_T (StoreID, Paid, CustomerID) VALUES (" . $StoreID . ", 0, " . $CustomerID . ");";
			$result = queryDB($query, $db);
			$_SESSION['OrderID'] = mysqli_insert_id($db);
			$OrderID = $_SESSION['OrderID'];
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB($query, $db);
            header ('Location: Product.php');
            exit;
			}
		else
		{
			$query = "SELECT OrderID FROM Order_T WHERE CustomerID = " . $CustomerID ." AND Paid = 0;";
			$result = queryDB($query, $db);
			while ($row = nextTuple($result))
			{
				$OrderID = $row['OrderID'];
			}
			$_SESSION['OrderID'] = $OrderID;
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB($query, $db);
            header ('Location: Product.php');
            exit;
		}
	}
	else
	{
		if(isset($_SESSION['OrderID']))
		{
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB($query, $db);
            header ('Location: Product.php');
            exit;
		}
		else
		{
			$query = "INSERT INTO Order_T (StoreID, Paid) VALUES (" . $StoreID . ", 0);";
			$result = queryDB($query, $db);
			$_SESSION['OrderID'] = mysqli_insert_id($db);
			$OrderID = $_SESSION['OrderID'];
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB ($query, $db);
            header ('Location: Product.php');
            exit;
		}


	}

}

?>