<?php

//Kick users if they are not logged in
    session_start();
    if (!isset($_SESSION['EmployeeEmail'])) {
        header('Location: GrocerLogin.php');
        exit;
    }
    
    $StoreName = $_SESSION['StoreName'];
    
?>

<!-- This is a more detailed page for each order including all the order information -->

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
    
<!-- HTML to display customer information -->
<div class='row'>
    <div class='col-xs-12'>
        <h3>Customer Details</h3>
    </div>
</div>

<div class='row'>
    
    <!-- php to retrieve customer information from database -->
    <?php
        
    $OrderID = $_GET['OrderID'];
    $query = 'SELECT O.OrderName, O.ConfirmationEmail, O.DeliveryDate, O.DeliveryAddress, O.DeliveryTime FROM Customer C, Order_T O WHERE OrderID = ' . $OrderID . ' AND C.CustomerID = O.CustomerID;';
    $result = queryDB($query, $db);
    
    // echo customer information from $row
        
    while($row = nextTuple($result)) {
        echo "<div class='col-xs-3'>";
        echo '<b>Name:</b>';
        echo '<p>' . $row['OrderName'] . '</p>';
        echo "</div>";
        
        echo "<div class='col-xs-3'>";
        echo '<b>Email:</b>';
        echo '<p>' . $row['ConfirmationEmail'] . '</p>';
        echo "</div>";
            
        echo "<div class='col-xs-3'>";
        echo '<b>Delivery Address:</b>';
        echo '<p>' . $row['DeliveryAddress'] . '</p>';
        echo "</div>";
        
        echo "<div class='col-xs-3'>";
        echo '<b>Delivery Date:</b>';
        echo '<p>' . $row['DeliveryDate'] . '</p>';
        echo '<b>Delivery Time:</b>';
        echo '<p>' . $row['DeliveryTime'] . '</p>';
        echo "</div>";
    }
        
        ?>
</div>
    
<!-- HTML Table to display data -->
<div class='row'>
    <div class='col-xs-12'>
        <h3>Order Details</h3>
    </div>
</div>

<div class = "row">
    <div class = "col-xs-12">
        <table class = "table table-hover">
            <thead>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price/Unit</th>
                <th></th>
            </thead>
            
<!-- Use php to display data -->
<?php
    
//query to find information about products in the order from database
$query = 'SELECT P.ProductName, O.Quantity, P.Price FROM Product P, OrderLine O WHERE P.ProductID = O.ProductID AND O.OrderID = ' . $OrderID . ';';
    
$result = queryDB($query, $db);
    
while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>" . $row['Quantity'] . "</td>";
    echo "<td>$" . $row['Price'] . "</td>";
    echo "</tr> \n";
    }
?>
        </table>
    </div>
</div>

<div class='row'>
    <div class='col-xs-12'>
        
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
</div>

<br>

<!--Dropdown menu to update workflow options -->

<form action = "UpdateOrder.php" method = "post">
    
    <div>
        
        <?php
        
        //get order status from database to make it the default value
        $query = "SELECT OrderStatus FROM Order_T WHERE OrderID = $OrderID;";
        $result = queryDB($query,$db);
        while($row = nextTuple($result)) {
            $Status = $row['OrderStatus'];
        }
        
        //$Status is used to have the right option selected in the dropdown menu that corresponds to the orders status
        echo '<label for="OrderStatus">Order Status:</label>';
        echo '<select class="form-control" name="OrderStatus">';
            echo '<option value = "Awaiting Payment"' . (($Status == 'Awaiting Payment')?'selected="selected"':"") . '>Awaiting Payment</option>';
            echo '<option value = "Filling Order"' . (($Status == 'Filling Order')?'selected="selected"':"") . '>Filling Order</option>';
            echo '<option value = "Waiting To Be Delivered"' . (($Status == 'Waiting To Be Delivered')?'selected="selected"':"") . '>Waiting To Be Delivered</option>';
            echo '<option value = "Out For Delivery"' . (($Status == 'Out For Delivery')?'selected="selected"':"") . '>Out For Delivery</option>';
            echo '<option value = "Delivered"' . (($Status == 'Delivered')?'selected="selected"':"") . '>Delivered</option>';
            echo '<option value = "Returned"' . (($Status == 'Returned')?'selected="selected"':"") . '>Returned</option>';
        echo '</select>';
        
        ?>
        
        <!-- hidden id (not visible to user, but need to be part of form submission so we know which order we are updating -->
        <input type="hidden" name="OrderID" value="<?php echo $OrderID; ?>"/>
        
        <!-- Button to update order -->
        <button type = "Update" class = "btn btn-default" name = "Update">Update Order</button>
        
    </div>
    
</form>


</div>

    </body>
</html>