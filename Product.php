<?php

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

//Add to cart button is called 'add'
/*
if(isset($_Post['add']))
{
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
		}
	}
	else
	{
		if(isset($_SESSION['OrderID']))
		{
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB($query, $db);
		}
		else
		{
			$query = "INSERT INTO Order_T (StoreID, Paid) VALUES (" . $StoreID . ", 0);";
			$result = queryDB($query, $db);
			$_SESSION['OrderID'] = mysqli_insert_id($db);
			$OrderID = $_SESSION['OrderID'];
			$query = "INSERT INTO OrderLine (Quantity, OrderID, ProductID)  VALUES ( " . $Quantity . ", " . $OrderID . ", " . $ProductID . ");";
			$result = queryDB ($query, $db);
		}


	}

}
*/
?>



<!-- HTML Table -->
<div class = "row">
    <div class = "col-xs-12">
        <table class = "table table-hover">
            <thead>
                <th>Product Category</th>
				
				

            </thead>
			

<!-- Display  Product data -->

<?php
// SQL query to list products from database
//***** STILL Need to group by Category *****
// And Still unclear how we want this page to be shown.

$query = "SELECT ProductID, ProductName, Price, Picture FROM Product WHERE StoreID = " . $StoreID . " ORDER BY ProductName ;";

$result = queryDB($query, $db);

while($row = nextTuple($result))
{
    echo "<tr>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>" . $row['Price'] ."</td>";
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
    echo '<label for = "Quantity">Quantity</label><input type = "number" class = "form-control" name = "Quantity"/>';
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