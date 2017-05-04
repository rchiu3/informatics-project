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
    
<!-- HTML to display order information -->
<div class = "row">
    
    <!-- Menu bar on left side to display all or a specific order status -->
    <div class = "col-xs-2">
        <div class="list-group">
            <a href="GrocerOrders.php" class="list-group-item">All Orders <?php
                
                //Find the number of total orders and echo in left side menu bar
                $query = "SELECT COUNT(*) AS Number FROM Order_T WHERE StoreID = $StoreID";
                $result = queryDB($query,$db);
                while($row = nextTuple($result)) {
                    echo "<span class='badge'>" . $row['Number'] . "</span>";
                }
                ?></a>
            
            <a href="GrocerOrders.php?Status=Unpaid" class="list-group-item">Awaiting Payment <?php
                
                //Find the number of orders that are still awaiting payment and echo in left side menu bar
                $query = "SELECT COUNT(*) AS Number FROM Order_T WHERE StoreID = $StoreID AND OrderStatus = 'Awaiting Payment'";
                $result = queryDB($query,$db);
                while($row = nextTuple($result)) {
                    echo "<span class='badge'>" . $row['Number'] . "</span>";
                }
                ?></a>
            
            <a href="GrocerOrders.php?Status=Filling" class="list-group-item">Filling Order <?php
            
                //Find the number of orders being filled and echo in left side menu bar
                $query = "SELECT COUNT(*) AS Number FROM Order_T WHERE StoreID = $StoreID AND OrderStatus = 'Filling Order'";
                $result = queryDB($query,$db);
                while($row = nextTuple($result)) {
                    echo "<span class='badge'>" . $row['Number'] . "</span>";
                }
                ?></a>
            
            <a href="GrocerOrders.php?Status=Waiting" class="list-group-item">Waiting To Be Delivered <?php
                
                //Find the number of orders waiting to be delivered and echo in left side menu bar
                $query = "SELECT COUNT(*) AS Number FROM Order_T WHERE StoreID = $StoreID AND OrderStatus = 'Waiting To Be Delivered'";
                $result = queryDB($query,$db);
                while($row = nextTuple($result)) {
                    echo "<span class='badge'>" . $row['Number'] . "</span>";
                }
                ?></a>
            
            <a href="GrocerOrders.php?Status=Delivering" class="list-group-item">Out For Delivery <?php
            
                //Find the number of orders that are out for delivery and echo in left side menu bar
                $query = "SELECT COUNT(*) AS Number FROM Order_T WHERE StoreID = $StoreID AND OrderStatus = 'Out For Delivery'";
                $result = queryDB($query,$db);
                while($row = nextTuple($result)) {
                    echo "<span class='badge'>" . $row['Number'] . "</span>";
                }
                ?></a>
            
            <a href="GrocerOrders.php?Status=Delivered" class="list-group-item">Delivered <?php
            
                //Find the number of delivered orders and echo in left side menu bar
                $query = "SELECT COUNT(*) AS Number FROM Order_T WHERE StoreID = $StoreID AND OrderStatus = 'Delivered'";
                $result = queryDB($query,$db);
                while($row = nextTuple($result)) {
                    echo "<span class='badge'>" . $row['Number'] . "</span>";
                }
                ?></a>
            
            <a href="GrocerOrders.php?Status=Returned" class="list-group-item">Returned <?php
            
                //Find the number of returned orders and echo in left side menu bar
                $query = "SELECT COUNT(*) AS Number FROM Order_T WHERE StoreID = $StoreID AND OrderStatus = 'Returned'";
                $result = queryDB($query,$db);
                while($row = nextTuple($result)) {
                    echo "<span class='badge'>" . $row['Number'] . "</span>";
                }
                ?></a>
        </div>
    </div>
    
    <!-- Table to display order data -->
    <div class = "col-xs-10">
        <table class = "table table-hover">
            <thead>
                <th>Customer Name</th>
                <th>Delivery Date</th>
                <th>Paid</th>
                <th>Order Status</th>
                <th></th>
                <th></th>
            </thead>
            
<!-- Use php to display data -->
<?php

//Find status category that user wants to view
$Status = $_GET['Status'];

//Based on status selected by user, query to find information about orders with specified status from database
if ($Status=='Unpaid') {
    $query = 'SELECT O.OrderID, O.OrderName, O.DeliveryDate, O.Paid, O.OrderStatus FROM Order_T O, Customer C WHERE C.CustomerID = O.CustomerID AND O.StoreID = ' . $StoreID . ' AND O.Paid = 0 ORDER BY O.DeliveryDate;';
}
elseif ($Status=='Filling') {
    $query = 'SELECT O.OrderID, O.OrderName, O.DeliveryDate, O.Paid, O.OrderStatus FROM Order_T O, Customer C WHERE C.CustomerID = O.CustomerID AND O.StoreID = ' . $StoreID . ' AND O.OrderStatus = "Filling Order" ORDER BY O.DeliveryDate;';
}
elseif ($Status=='Waiting') {
    $query = 'SELECT O.OrderID, O.OrderName, O.DeliveryDate, O.Paid, O.OrderStatus FROM Order_T O, Customer C WHERE C.CustomerID = O.CustomerID AND O.StoreID = ' . $StoreID . ' AND O.OrderStatus = "Waiting To Be Delivered" ORDER BY O.DeliveryDate;';
}
elseif ($Status=='Delivering') {
    $query = 'SELECT O.OrderID, O.OrderName, O.DeliveryDate, O.Paid, O.OrderStatus FROM Order_T O, Customer C WHERE C.CustomerID = O.CustomerID AND O.StoreID = ' . $StoreID . ' AND O.OrderStatus = "Out For Delivery" ORDER BY O.DeliveryDate;';
}
elseif ($Status=='Delivered') {
    $query = 'SELECT O.OrderID, O.OrderName, O.DeliveryDate, O.Paid, O.OrderStatus FROM Order_T O, Customer C WHERE C.CustomerID = O.CustomerID AND O.StoreID = ' . $StoreID . ' AND O.OrderStatus = "Delivered" ORDER BY O.DeliveryDate;';
}
elseif ($Status=='Returned') {
    $query = 'SELECT O.OrderID, O.OrderName, O.DeliveryDate, O.Paid, O.OrderStatus FROM Order_T O, Customer C WHERE C.CustomerID = O.CustomerID AND O.StoreID = ' . $StoreID . ' AND O.OrderStatus = "Returned" ORDER BY O.DeliveryDate;';
}

//select all orders if user doesn't choose a specific status to view
else {
    $query = 'SELECT O.OrderID, O.OrderName, O.DeliveryDate, O.Paid, O.OrderStatus FROM Order_T O, Customer C WHERE C.CustomerID = O.CustomerID AND O.StoreID = ' . $StoreID . ' ORDER BY O.DeliveryDate;';
}

$result = queryDB($query, $db);
    
//Display data found by query
while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['OrderName'] . "</td>";
    echo "<td>" . $row['DeliveryDate'] . "</td>";
    
    //Echoes yes or no for paid instead of 0 or 1
    if ($row['Paid']) {
            $Paid = 'Yes';
        } else {
            $Paid = 'No';
        }
    echo "<td>" . $Paid . "</td>";
    $OrderStatus = $row['OrderStatus'];
    echo "<td><form action='UpdateOrder.php' method = 'post'>";
    
    //Dropdown menu to update status from general overview page
    echo '<select class="form-control" name="OrderStatus">';
        echo '<option value = "Awaiting Payment"' . (($OrderStatus == 'Awaiting Payment')?'selected="selected"':"") . '>Awaiting Payment</option>';
        echo '<option value = "Filling Order"' . (($OrderStatus == 'Filling Order')?'selected="selected"':"") . '>Filling Order</option>';
        echo '<option value = "Waiting To Be Delivered"' . (($OrderStatus == 'Waiting To Be Delivered')?'selected="selected"':"") . '>Waiting To Be Delivered</option>';
        echo '<option value = "Out For Delivery"' . (($OrderStatus == 'Out For Delivery')?'selected="selected"':"") . '>Out For Delivery</option>';
        echo '<option value = "Delivered"' . (($OrderStatus == 'Delivered')?'selected="selected"':"") . '>Delivered</option>';
        echo '<option value = "Returned"' . (($OrderStatus == 'Returned')?'selected="selected"':"") . '>Returned</option>';
    echo '</select>';
    echo "</td>";
    
    //Hidden OrderID to use if user updates order status from this menu
    echo '<input type="hidden" name="OrderID" value="' . $row['OrderID'] . '"/>';
    echo "<td><button type = 'submit' class = 'btn btn-default' name = 'Update'>Update</button></td></form>";
    echo "<td><a href='OrderDetails.php?OrderID=" . $row['OrderID'] . "'>Order Details</a></td>";
    echo "</tr> \n";
    }
?>
        </table>
    </div>
</div>
</div>

    </body>
</html>