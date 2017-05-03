<?php
//start session to keep track of customer
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

//If the 'Add to Cart' button was clicked
if(isset($_POST['add']))
{
// get data from form
$Quantity = $_POST['Quantity'];
$ProductID = $_POST['ProductID'];
	// If the customer is logged in
	if(isset($_SESSION['CustomerEmail']))
	{
		//Searching for an unpaid order
		$query = "SELECT CustomerID, Paid FROM Order_T WHERE CustomerID = " . $CustomerID . " AND Paid = 0;";
		$result = queryDB($query, $db);
		//If no unpaid order
		if(nTuples($result) == 0)
			{
			//Insert statement that creates an OrderID for customer with the Session StoreID and CustomerID as well as sets paid boolean to 0
			$query = "INSERT INTO Order_T (StoreID, Paid, CustomerID) VALUES (" . $StoreID . ", 0, " . $CustomerID . ");";
			$result = queryDB($query, $db);
			//Retrieves last OrderID
			$_SESSION['OrderID'] = mysqli_insert_id($db);
			//Sets the Retirved OrderID as session variable to track customer throughout page
			$OrderID = $_SESSION['OrderID'];
			//Insert the selected product and given quantity into orderLine with your the new OrderID
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB($query, $db);
			//Send user back to product page
            header ('Location: Product.php');
            exit;
			}
		//If customer has an unpaid order
		else
		{
			//Search for the OrderID of the unpaid order
			$query = "SELECT OrderID FROM Order_T WHERE CustomerID = " . $CustomerID ." AND Paid = 0;";
			$result = queryDB($query, $db);
			while ($row = nextTuple($result))
			{
				$OrderID = $row['OrderID'];
			}
			//Set new session OrderID
			$_SESSION['OrderID'] = $OrderID;
			//Insert the selected product and given quantity into orderLine with your the new OrderID
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB($query, $db);
			//Send user back to product page
            header ('Location: Product.php');
            exit;
		}
	}
	// if not logged in
	else
	{
		//if you already have an OrderID in session 
		if(isset($_SESSION['OrderID']))
		{
			//Insert the selected product and given quantity into orderLine with your the new OrderID
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB($query, $db);
			//Send user back to product page
            header ('Location: Product.php');
            exit;
		}
		//if you don't have an OrderID in session
		else
		{
			//Insert statement that creates an OrderID for customer with the Session StoreID as well as sets paid boolean to 0
			$query = "INSERT INTO Order_T (StoreID, Paid) VALUES (" . $StoreID . ", 0);";
			$result = queryDB($query, $db);
			//Retrieves last OrderID
			$_SESSION['OrderID'] = mysqli_insert_id($db);
			//Sets the Retirved OrderID as session variable to track customer throughout page
			$OrderID = $_SESSION['OrderID'];
			//Insert the selected product and given quantity into orderLine with your the new OrderID
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB($query, $db);
			//Send user back to product page
            header ('Location: Product.php');
            exit;
		}


	}

}

?>