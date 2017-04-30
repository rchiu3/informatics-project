<html>
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
<title>Shopping Cart</title>
<body>
	
	<?php
	session_start();
	$StoreID = $_SESSION['StoreID'];
	$CustomerEmail = $_SESSION['CustomerEmail'];
	$CustomerID = $_SESSION['CustomerID'];
	$OrderID = $_SESSION['OrderID'];	
	$page = 'ShoppingCart';
	include_once('CustomerNav.php');

	?>

<
	
	<div class="container" style="margin-top:50px">


<?php
//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');
/*
session_start();
$StoreID = $_SESSION['StoreID'];
$CustomerEmail = $_SESSION['CustomerEmail'];
$CustomerID = $_SESSION['CustomerID'];
$OrderID = $_SESSION['OrderID'];
*/
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
				<th></th>
				<th></th>
				<th></th>
            </thead>

<!-- Display  Shopping Cart data -->

<?php
// SQL query to list products from shopping cart
//***** STILL Need to group by Category *****
// And Still unclear how we want this page to be shown.
//$OrderID 
$query = 'SELECT o.OrderLineID, o.ProductID, o.Quantity, p.ProductName, p.Price, p.Picture FROM Product p, OrderLine o WHERE p.ProductID = o.ProductID AND o.OrderID = ' . $OrderID . ';';

$result = queryDB($query, $db);

while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>" . $row['Quantity'] . "</td>";
    echo "<td>$" . $row['Price'] . "</td>";
   	// picture
    echo "<td>";
    if ($row['Picture']) {
        $imageLocation = $row['Picture'];
        $altText = $row['ProductName'];
        echo "<img src='$imageLocation' width='150' alt='$altText'>";
    }
    echo "</td>";
	echo "</td>";
	echo '<td><form action = "EditCart.php" method = "post">';
    echo '<div class = "form-group">';
	echo '<input type = "number" min ="0" class = "form-control" name = "Quantity" value = "' . $row['Quantity'] . '"/>';
    echo '</div></td>';
	echo '<input type="hidden" name="OrderLineID" value="' . $row['OrderLineID'] . '"/>';
    echo '<td><button type = "Update" class = "btn btn-default" name = "Update">Update Quantity</button></td>';
	echo '</form>';
	echo "<td><a href='RemoveCart.php?OrderLineID=" . $row['OrderLineID'] . "'>Remove</a></td>";
    echo "</tr> \n";

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
        
        

if (isset($CustomerID))
{
	echo '<div class="row">';
		echo '<div class="col-xs-12">';
			echo '<a class="btn btn-default" href="Checkout.php">Proceed To Checkout</a>';
		echo '</div>';
	echo '</div>';
}
else
{
	echo '<div class="row">';
		echo '<div class="col-xs-12">';
			echo '<a class="btn btn-default" href="Checkout.php">Checkout as Guest</a>';
			echo '<a class="btn btn-default" href="LoginCheckout.php">Login/Sign-Up and Checkout</a>';
		echo '</div>';
	echo '</div>';
	
}

?>
	</div>
</body>
</html>