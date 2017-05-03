<?php
//start session to track customer
session_start();
$StoreID = $_SESSION['StoreID'];
$CustomerEmail = $_SESSION['CustomerEmail'];
$CustomerID = $_SESSION['CustomerID'];
$OrderID = $_SESSION['OrderID'];

//send user back to customer home if they don't have a grocer selected 
if (!isset($StoreID))
{
	header ('Location: CustomerHome.php');
	exit;
}

//set page to echo active page in navbar
$page = 'Product';
//navbar
include_once('CustomerNav.php');

?>
<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<title>Products</title>
</head>

<body>



	<div class="container" style="margin-top:50px">

<!-- What happens if a guests starts making an order and then leaves how do we find their OrderID since it won't be the last one created -->


<?php

//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');
//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);


?>



<!-- HTML Table -->
<div class = "row">
	
	<div class = "col-xs-2">
		<div class = "list-group">
			
			<?php
				//side Navbar displaying Different Product Categories
				$query = "SELECT CategoryID, CategoryName FROM Category ORDER BY CategoryName;";
				$result = queryDB($query,$db);
				
				while ($row = nextTuple($result)) {
					echo "<a href='Product.php?CategoryID=" . $row['CategoryID'] . "' class = 'list-group-item'>" . $row['CategoryName'] . "</a>";
				}
			?>
			
		</div>
	</div>
	
    <div class = "col-xs-10">
        <table class = "table table-hover">
            <thead>
                <th>Product</th>
				<th>Price</th>
				<th></th>
				<th>Quantity</th>
            </thead>
			

<!-- Display  Product data -->

<?php
// SQL query to list products from database
//***** STILL Need to group by Category *****
// And Still unclear how we want this page to be shown.

//
//Checks to see if customer has searched for a product
if(isset($_GET['Search'])) {
	$Search = $_GET['Search'];
	$query = "SELECT ProductID, ProductName, Price, Picture FROM Product WHERE StoreID = " . $StoreID . " AND ProductName LIKE '%" . $Search . "%' ORDER BY ProductName ;";
}
//Checks to see if customer has selected a Product category they would like to view
elseif(isset($_GET['CategoryID']))
{
	$CategoryID = $_GET['CategoryID'];
	$query = "SELECT ProductID, ProductName, Price, Picture FROM Product WHERE StoreID = " . $StoreID . " AND CategoryID = " . $CategoryID . " ORDER BY ProductName ;";
}

//If customer has not searched for an item or selected a category, then list all items
else {
	$query = "SELECT ProductID, ProductName, Price, Picture FROM Product WHERE StoreID = " . $StoreID . " ORDER BY ProductName ;";
}

$result = queryDB($query, $db);
//Displays products
while($row = nextTuple($result))
{
    echo "<tr>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>$" . $row['Price'] ."</td>";
	// picture
    echo "<td>";
    if ($row['Picture']) {
        $imageLocation = $row['Picture'];
        $altText = $row['ProductName'];
        echo "<img src='$imageLocation' width='150' alt='$altText'>";
    }
    echo "</td>";
	echo '<td><form action = "AddToCart.php" method = "post">';
    echo '<div class = "form-group">';
	echo '<input type = "number" min ="0" class = "form-control" name = "Quantity"/>';
    echo '</div></td>';
	echo '<input type="hidden" name="ProductID" value="' . $row['ProductID'] . '"/>';
    echo '<td><button type = "add" class = "btn btn-default" name = "add">Add to Cart</button></td></tr>';
	echo '</form>';
    }
?>
        </table>
    </div>
</div>
</div>

    </body>
</html>