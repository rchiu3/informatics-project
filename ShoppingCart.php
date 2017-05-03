<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript  -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</head>
<title>Shopping Cart</title>
<body>
	
	<?php
	//start session to keep track of customers
	session_start();
	$StoreID = $_SESSION['StoreID'];
	$CustomerEmail = $_SESSION['CustomerEmail'];
	$CustomerID = $_SESSION['CustomerID'];
	$OrderID = $_SESSION['OrderID'];	
	$page = 'ShoppingCart';
	//Nav Bar
	include_once('CustomerNav.php');

	?>

<
	
	<div class="container" style="margin-top:50px">


<?php
//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');

//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

?>
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
				<th>Quantity</th>
				<th></th>
				<th></th>
            </thead>

<!-- Display  Shopping Cart data -->

<?php
//If customer/guest has not been assigned an orderID (*because they havent added an item to the shopping cart*)
//Customer/guest is blocked from viewing the shopping cart until they've added an item to their cart
if (!isset($OrderID))
{
	//Send them to Product.php so they can add products
	header('Location: Product.php');
    exit;
}

else
	{
	// SQL query to list products from shopping cart
	// Displays list of items in shopping cart ordered by grocer
	$query = 'SELECT o.OrderLineID, o.ProductID, o.Quantity, p.ProductName, p.Price, p.Picture, s.StoreName FROM Product p, OrderLine o, Store s WHERE s.StoreID = p.StoreID AND p.ProductID = o.ProductID AND o.OrderID = ' . $OrderID . ' ORDER BY StoreName;';
	
	$result = queryDB($query, $db);
	
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
		echo "</td>";
		//Update quantity field
		echo '<td><form action = "EditCart.php" method = "post">';
		echo '<div class = "form-group">';
		echo '<input type = "number" min ="0" class = "form-control" name = "Quantity" value = "' . $row['Quantity'] . '"/>';
		echo '</div></td>';
		echo '<input type="hidden" name="OrderLineID" value="' . $row['OrderLineID'] . '"/>';
		echo '<td><button type = "Update" class = "btn btn-default" name = "Update">Update Quantity</button></td>';
		echo '</form>';
		//Remove Product
		echo "<td><a href='RemoveCart.php?OrderLineID=" . $row['OrderLineID'] . "'>Remove</a></td>";
		echo "</tr> \n";

    }
}
?>
        </table>
     </div>
</div>

<?php
        
        //Display total price with tax
        
        $query = 'SELECT CAST(SUM(X.Total) * 1.06 AS Decimal(10,2)) AS Final FROM (SELECT O.Quantity, P.Price, O.Quantity * P.Price AS Total FROM OrderLine O, Product P WHERE O.ProductID = P.ProductID AND O.OrderID =' . $OrderID . ') X;';
        $result = queryDB($query, $db);
        
        while($row = nextTuple($result)) {
            echo "<h3>Total With 6% Sales Tax:</h3>";
            echo "<h4>$" . $row['Final'] . "</h4>";
        }
        
        
// If the Customer is logged in display a single  button that takes you to checkout
if (isset($CustomerID))
{
	echo '<div class="row">';
		echo '<div class="col-xs-12">';
			echo '<a class="btn btn-default" href="Checkout.php">Proceed To Checkout</a>';
		echo '</div>';
	echo '</div>';
}
//If Customer is not logged in or if user is a Guest display two buttons with the option to proceed to checkout as guest or login
else
{
	echo '<div class="row">';
		echo '<div class="col-xs-12">';
			//GuestCheckout.php assigns the guest a customerID and adds it to the session
			echo '<a class="btn btn-default" href="GuestCheckout.php">Checkout as Guest</a>';
			//LoginCheckout.php Allows customer to login
			echo '<a class="btn btn-default" href="LoginCheckout.php">Login/Sign-Up and Checkout</a>';
		echo '</div>';
	echo '</div>';
	
}

?>
	</div>
</body>
</html>