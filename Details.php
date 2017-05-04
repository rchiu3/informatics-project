
<?php
//start session to track user
session_start();
$StoreID = $_SESSION['StoreID'];
$CustomerEmail = $_SESSION['CustomerEmail'];
$CustomerID = $_SESSION['CustomerID'];
$OrderID = $_SESSION['OrderID'];
//active tab on navbar
$page = 'MyAccount';
//navbar
include_once('CustomerNav.php');
//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');

//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

?>
<head>
!-- This is the code from bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<title> Order Details </title>
</head>
<body>
    
    <div class="container" style="margin-top:50px">
       <div class = "row">
            <div class = "col-xs-12">
               <table class = "table table-hover">
                   <thead>
                       <th>Product Name</th>
                       <th>Quantity</th>
                       <th>Price</th>
                       <th>Store</th>
                       <th></th>
                   </thead>
    <?php
    //Query to get information for Order Display
	$OrderID = $_GET['OrderID'];
	
    $query = 'SELECT o.OrderLineID, o.ProductID, o.Quantity, p.ProductName, p.Price, p.Picture, s.StoreName FROM Product p, OrderLine o, Store s WHERE s.StoreID = p.StoreID AND p.ProductID = o.ProductID AND o.OrderID = ' . $OrderID . ' ORDER BY StoreName;';
	
	$result = queryDB($query, $db);
	//Display Order Information
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
    ?>
    </div>
    
</body>