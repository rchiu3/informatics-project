<?php

//Kick users if they are not logged in
    session_start();
    if (!isset($_SESSION['EmployeeEmail'])) {
        header('Location: GrocerLogin.php');
        exit;
    }
    
    $StoreName = $_SESSION['StoreName'];
    $StoreID = $_SESSION['StoreID'];
    
?>

<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <title>Orders</title>
    </head>
    <body>

<?php

//Set current page to echo class=active in navbar

$page = 'Orders';
include_once('GrocerNav.php');

include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

?>

<div class = "container" style = "margin-top:50px">
    
<!-- HTML Table to display data -->
<div class = "row">
    <div class = "col-xs-12">
        <table class = "table table-hover">
            <thead>
                <th>Customer Name</th>
                <th>Delivery Date</th>
                <th>Paid</th>
                <th>Order Status</th>
                <th></th>
            </thead>
            
<!-- Use php to display data -->
<?php
    
//query to find information about orders from database
$query = 'SELECT O.OrderID, C.CustomerName, O.DeliveryDate, O.Paid, O.OrderStatus FROM Order_T O, Customer C WHERE C.CustomerID = O.CustomerID AND O.StoreID = ' . $StoreID . ';';
    
$result = queryDB($query, $db);
    
while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['CustomerName'] . "</td>";
    echo "<td>" . $row['DeliveryDate'] . "</td>";
    if ($row['Paid']) {
            $Paid = 'Yes';
        } else {
            $Paid = 'No';
        }
    echo "<td>" . $Paid . "</td>";
    echo "<td>" . $row['OrderStatus'] . "</td>";
    echo "<td><a href='OrderDetails.php?OrderID=" . $row['OrderID'] . "'>Details/Update</a></td>";
    echo "</tr> \n";
    }
?>
        </table>
    </div>
</div>
</div>

    </body>
</html>