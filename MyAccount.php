
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
<!-- This is the code from bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<title>
    My Account
</title>    
</head>
<body>
<div class="container" style="margin-top:50px">
    <div clsas="row">
        <div class='col-xs-12'>
            <table class='table table-hover'>
                <thead>
                    <th>Name on Order</th>
                    <th>Delivery Date</th>
                    <th>Order Status</th>
                    <th>Paid</th>
                    <th></th>
                </thead>

<?php
//Query to display all orders from current customer
$query = "SELECT OrderID, OrderName, DeliveryDate, OrderStatus, Paid FROM Order_T WHERE CustomerID =" . $CustomerID .";";
$result = queryDB($query, $db);
//display all orders made by customer
while ($row = nextTuple($result))
{
    echo "\n <tr>";
    echo "<td>" . $row['OrderName'] . " </td>";
    echo "<td>" . $row['DeliveryDate'] . " </td>";
    echo "<td>" . $row['OrderStatus'] . " </td>";
    //if Paid = 1(True) display yes
    if ($row['Paid']) {
        $Paid = 'Yes';
    }
    //else display no
    else {
        $Paid = 'No';
    }
    echo "<td>" . $Paid . "</td>";
    echo "<td><a href='Details.php?OrderID=" . $row['OrderID'] . "'>Order Details</a></td>";
}
?>
        </div>
    </div>
</div>
</body>