<?php
//
session_start();
$CustomerEmail = $_SESSION['CustomerEmail'];
$StoreID = $_SESSION['StoreID'];
$CustomerEmail = $_SESSION['CustomerEmail'];
$CustomerID = $_SESSION['CustomerID'];
$OrderID = $_SESSION['OrderID'];
//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');
//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
?>
<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript  -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</head>
<title>Home</title>
<body>


<?php
$page = 'CustomerHome';
include_once('CustomerNav.php');
?>

	<div class="container" style="margin-top:50px">
	
	<div class="row">
		<div class="col-xs-12">
			<h3>Welcome to FreshShop!</h3>
			<p>Getting your groceries is now as simple as a few clicks, then waiting at your door for them to arrive! You don't even need
			one of our free accounts to place an order. Please select a grocer to begin browsing their products.</p>
			
			<p>If you're a new store that wants to list your products on FreshShop or you
			already have an existing store, <a href='GrocerRedirect.php'>please click here.</a></p>
		</div>
	</div>
	
	<br>
	
	<div class = "row">
    <div class = "col-xs-4">
        <table class = "table table-hover">
            <thead>
                <th></th>
				<th></th>
            </thead>
			

<!-- Display  Product data -->

<?php
// SQL query to list products from database
//***** STILL Need to group by Category *****
// And Still unclear how we want this page to be shown.
$query = "SELECT StoreName, StoreID, Picture FROM Store";

$result = queryDB($query, $db);

while($row = nextTuple($result))
{
	echo "\n <tr>";
	echo "<td><a href='SetStore.php?StoreID=" . $row['StoreID'] . "'>" . $row['StoreName'] . "</a></td>";
	echo "<td>";
    if ($row['Picture']) {
        $imageLocation = $row['Picture'];
        $altText = $row['StoreName'];
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

<!-- not sure how we want the page stylized but this is where the rest of that would go.
	 we will also have grocery selection on this page -->