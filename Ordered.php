<!-- This is the code from bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<head></head>
<body>
    <div class="container" style="margin-top:50px">
    
<?php

//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');
//start sesison to track customer
session_start();
	$StoreID = $_SESSION['StoreID'];
	$CustomerEmail = $_SESSION['CustomerEmail'];
	$CustomerID = $_SESSION['CustomerID'];
	$OrderID = $_SESSION['OrderID'];
    $page = 'Checkout';
    //navbar
	include_once('CustomerNav.php');
//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
// If 'Place Order' is clicked
if(isset($_POST['submit']))
{
	//get data from form
	$DeliveryDate = $_POST['DeliveryDate'];
	$DeliveryTime = $_POST['DeliveryTime'];
	$DeliveryAddress = $_POST['DeliveryAddress'];
	$ConfirmationEmail = $_POST['ConfirmationEmail'];
	$OrderName = $_POST['OrderName'];
    $NameOnCard = $_POST['NameOnCard'];
    $ExpDate = $_POST['ExpDate'];
    $CCNumber = $_POST['CCNumber'];
    $CSV = $_POST['CSV'];
    
	//variable to check if required fields entered
    $isComplete = true;
    //blank error message
    $errorMessage='';
    //failed to enter delivery date
    if(!$DeliveryDate)
    {
        //add to error message
        $errorMessage .= "Please enter a delivery date. \n";
        //form not complete
        $isComplete = false;
    }
    //failed to enter delivery time
    if(!$DeliveryTime)
    {
        //add to error message
        $errorMessage .= "Please enter a delivery time. \n";
        //form not complete
        $isComplete = false;
    }
    //failed to enter delivery address
    if(!$DeliveryAddress)
    {
        //add to error message
        $errorMessage .= "Please enter a delivery address. \n";
        //form not complete
        $isComplete = false;
    }
    //failed to enter confirmation email
    if(!$ConfirmationEmail)
    {
        //add to error message
        $errorMessage .= "Please enter the email you would like your order confirmation sent to. \n";
        //form not complete
        $isComplete = false;
    }
    //failed to enter name of order
    if(!$OrderName)
    {
        //add to error  message
        $errorMessage .= "Please enter the name on the Order. \n";
        //form not complete
        $isComplete = false;
    }
    //failed to enter name on card
    if (!$NameOnCard)
    {
        //add to error message 
        $errorMessage .= "Please enter the name on the credit card being used. \n";
        //form not complete
        $isComplete = false;
    }
    //failed to enter expiration date
    if(!$ExpDate)
    {
        //add to error message
        $errorMessage .= "Please enter the expiration date of the credit card being used. \n";
        //form not complete
        $isComplete = false;
    }
    //failed to enter credit card number
    if(!$CCNumber)
    {
        //add to error message
     $errorMessage .= "Please enter a credit card number. \n";
     //form not complete
        $isComplete = false;   
    }
    //failed to enter CSV
    if(!$CSV)
    {
        //add to error message
        $errorMessage .= "Please enter the CSV of the credit card being used. \n";
        //form not complete
        $isComplete = false;
    }
    //If form isn't complete (missing a required field)
    if(!$isComplete)
    {
        //display errormessgae
        punt($errorMessage);
    }
    //Update Order_T with information from Form as well as set Paid = 1
	$query = "UPDATE Order_T SET OrderStatus = 'Filling Order', DeliveryDate ='" . $DeliveryDate . "', DeliveryTime = '" . $DeliveryTime . "', DeliveryAddress = '" . $DeliveryAddress . "', Paid=1, OrderName = '" . $OrderName . "', ConfirmationEmail = '" . $ConfirmationEmail . "' WHERE OrderID =" . $OrderID . ";";
	queryDB($query,$db);
    echo "<div>Order has been Succesfully submitted and paid</div>";
    
	
}
?>
    </div>
</body>
