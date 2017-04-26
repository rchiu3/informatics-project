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
	$page = 'ShoppingCart';
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
                <th>Shopping Cart</th>
            </thead>

<!-- Display  Shopping Cart data -->

<?php
// SQL query to list products from shopping cart
//***** STILL Need to group by Category *****
// And Still unclear how we want this page to be shown.
//$OrderID 
$query = 'SELECT o.ProductID, o.Quantity, p.ProductName, p.Price, p.Picture FROM Product p, OrderLine o WHERE p.ProductID = o.ProductID AND o.OrderID = ' . $OrderID . ';';

$result = queryDB($query, $db);

while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['ProductID'] . "</td>";
    echo "<td>" . $row['Quantity'] . "</td>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>" . $row['Price'] . "</td>";
   	// picture
    echo "<td>";
    if ($row['Picture']) {
        $imageLocation = $row['Picture'];
        $altText = $row['ProductName'];
        echo "<img src='$imageLocation' width='150' alt='$altText'>";
    }
    echo "</td>";
    echo "</tr> \n";

    }
?>
        </table>
     </div>
</div>
</div>
</body>
</html>