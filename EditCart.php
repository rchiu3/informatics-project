<!-- This is the code from bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<?php
//This page is accessed when a User clicks on the 'Update Quantity' button on the Shopping Cart page
//This page is used to update the quantity of a certain item from the shopping cart page.
//Start session to keep track of Customer 
session_start();
$CustomerEmail = $_SESSION['CustomerEmail'];
$CustomerID = $_SESSION['CustomerID'];
$OrderID = $_SESSION['OrderID'];
$StoreID = $_SESSION['StoreID'];

//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');
//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

//If the 'Update Quantity' button was clicked
if(isset($_POST['Update']))
{
    // Obtain data from form
    $Quantity = $_POST['Quantity'];
    $OrderLineID = $_POST['OrderLineID'];
    
    //if the customer set the new quantity to 0 send them to removecart.php with OrderLineID appended to URL so that the item can be removed from the Orderline.
    if(!$Quantity)
    {
        header ('Location: RemoveCart.php?OrderLineID=' . $OrderLineID);
        exit;
    }
    //Updates quantity to user specified quantity and send the user back to the shopping cart page.
    else
    {
        
        $query = "UPDATE OrderLine SET Quantity = " . $Quantity . " WHERE OrderLineID = " . $OrderLineID . ";";
        queryDB($query,$db);
        header ('Location: ShoppingCart.php');
        exit;
    }
}

?>