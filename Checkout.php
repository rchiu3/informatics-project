<?php
	include_once('config.php');
	include_once('dbutils.php');
	session_start();
	$StoreID = $_SESSION['StoreID'];
	$CustomerEmail = $_SESSION['CustomerEmail'];
	$CustomerID = $_SESSION['CustomerID'];
	$OrderID = $_SESSION['OrderID'];
	$page = 'Checkout';
	include_once('CustomerNav.php');
	?>
<<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!--    *** NOT SURE IF THIS STYLING WORKS SAW IT ONLINE BUT WE CAN TEST THIS OUT L8R     ***
		*** THOUGHT IF IT DID WORK IT COULD BE A GOOD START TO STYLING OUR PAGE UNIFORMLY ***
   <style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

a:hover:not(.active) {
    background-color: #111;
}

.active {
background-color:#4CAF50;
}
</style>
-->

</head>
<title>Check Out</title>
<body>



	<div class="container" style="margin-top:50px">
<!-- HTML Table -->
<div class = "row">
    <div class = "col-xs-12">
        <table class = "table table-hover">
            <thead>
                <th>Product Name</th>
				<th>Quantity</th>
				<th>Price</th>
				<th></th>
				<th></th>
				<th></th>
            </thead>
<?php

//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');
//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
//session_start{};
//$OrderID = $_SESSION['OrderID'];
// We have to check if they have an order to view checkout page if not display your shopping cart is empty

//If Logged in
//if(isset($_SESSION['CustomerEmail'])
//{

	$query = "SELECT OrderID FROM Order_T WHERE CustomerID = " . $CustomerID .";";
	$result = queryDB($query, $db);
	while ($row = nextTuple($result))
	{
		$OrderID = $row['OrderID'];
	}
	$_SESSION['OrderID'] = $OrderID;
	//Should this query Call to check O.OrderID=OLine.OrderID & P.PoductID = OLine.ProductID
	$query = 'SELECT P.ProductID, P.ProductName, P.Price, P.Picture, O.Quantity, O.OrderID FROM Product P, OrderLine O WHERE P.ProductID = O.ProductID AND O.OrderID = ' . $OrderID . ';';
	$result = queryDB($query, $db);
	while($row = nextTuple($result))
	{
    echo "\n <tr>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>" . $row['Quantity'] . "</td>";
    echo "<td>$" . $row['Price'] . "</td>";
   	// picture
    echo "<td>";
    if ($row['Picture'])
		{
			$imageLocation = $row['Picture'];
			$altText = $row['ProductName'];
			echo "<img src='$imageLocation' width='150' alt='$altText'>";
		}
    echo "</td>";
	echo "</tr> \n";
	}
	

//}

//If Guest


//Start A Seeion Call the OrderId with session $orderID=$_Session....
//use orderID to list important information from OrderLine
//make query to total out
//take payment information
?>


</div>
</div>
</table>
		
<?php
        
        //Display total price with tax
        
        $query = 'SELECT CAST(SUM(X.Total) * 1.06 AS Decimal(10,2)) AS Final FROM (SELECT O.Quantity, P.Price, O.Quantity * P.Price AS Total FROM OrderLine O, Product P WHERE O.ProductID = P.ProductID AND O.OrderID =' . $OrderID . ') X;';
        $result = queryDB($query, $db);
        
        while($row = nextTuple($result)) {
            echo "<h3>Total With 6% Sales Tax:</h3>";
            echo "<h4>$" . $row['Final'] . "</h4>";
        }
        
?>		
		
</body>
</html>