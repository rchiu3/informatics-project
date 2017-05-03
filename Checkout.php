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
				<th>Store</th>
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

	//Get OrderID
	$query = "SELECT OrderID FROM Order_T WHERE CustomerID = " . $CustomerID .";";
	$result = queryDB($query, $db);
	while ($row = nextTuple($result))
	{
		$OrderID = $row['OrderID'];
	}
	$_SESSION['OrderID'] = $OrderID;
	//Query to get Order displayed
	$query = 'SELECT o.OrderLineID, o.ProductID, o.Quantity, p.ProductName, p.Price, p.Picture, s.StoreName FROM Product p, OrderLine o, Store s WHERE s.StoreID = p.StoreID AND p.ProductID = o.ProductID AND o.OrderID = ' . $OrderID . ' ORDER BY StoreName;';
	$result = queryDB($query, $db);
	//Display Each Product in the OrderLine
	while($row = nextTuple($result))
	{
    echo "\n <tr>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>" . $row['Quantity'] . "</td>";
    echo "<td>$" . $row['Price'] . "</td>";
	echo "<td>" . $row['StoreName'] . "</td>";
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

<br>

<!-- Form for customer to input information -->
        <div class="row">
            <div class="col-xs-12">
<h3>Billing Information</h3>
<form action="Ordered.php" method="post">
<!-- Not keeping Payment Information only Adding Billing Info to Order_T 
<!-- Name on Card -->
	<div class="form-group">
		<label for="NameOnCard">Name On Credit Card:</label>
		<input type="name" class="form-control" name="NameOnCard"/>
	</div>

<!-- Credit Card Number -->
    <div class="form-group">
        <label for="ExpDate">Expiration Date (mm/yy):</label>
        <input type="text" class="form-control" name="ExpDate"/>
    </div>
	
<!-- Credit Card Number -->
    <div class="form-group">
        <label for="CCNumber">Credit Card Number:</label>
        <input type="text" class="form-control" name="CCNumber"/>
    </div>

<!-- CSV -->
    <div class="form-group">
        <label for="CSV">CSV:</label>
        <input type="text" class="form-control" name="CSV"/>
    </div>

 <!-- Change type to text if cannot view on IE 11 or FireFox-->
<br>
<h3>Shipping Information</h3>
<!-- Name on Order -->
	<div class="form-group">
		<label for="OrderName">Name:</label>
		<input type="text" class="form-control" name="OrderName"/>
	</div>
<!-- Email -->
    <div class="form-group">
        <label for="ConfirmationEmail">Please provide the email you would like the order confirmation sent to:</label>
        <input type="email" class="form-control" name="ConfirmationEmail"/>
    </div>
	
<!-- Delivery Date -->	
    <div class="form-group">
        <label for="DeliveryDate">Delivery Date:</label>
        <input type="date" class="form-control" placeholder = "Unable to do same day deliveries, please allow atleast one day for filling and processing." name="DeliveryDate"/>
    </div>
		
<!-- Delivery Time -->	
    <div class="form-group">
        <label for="DeliveryTime">Preferred Time of Delivery <i>*Delivery hours are from 9am - 5pm*</i></label>
        <input type="time" value="09:00" step="900" class="form-control"  name="DeliveryTime"/>
    </div>

<!-- Delivery Address -->
    <div class="form-group">
        <label for="DeliveryAddress">Delivery Address:</label>
        <input type="address" class="form-control" name="DeliveryAddress"/>
    </div>
<!-- Button to Place Order -->
    <button type="submit" class="btn btn-default" name="submit">Place Order</button>
</form>		
		
</body>
</html>