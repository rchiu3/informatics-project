<?php

//Echo store name if user is logged in
    session_start();
    $StoreName = $_SESSION['StoreName'];
    
?>

<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <title>Store Overview</title>
    </head>

<?php

//Set current page to echo class=active in navbar

$page = 'GrocerHome';
include_once('GrocerNav.php');

?>

<body>
    <div class='container' style='margin-top:50px'>

<h1>Grocer Home</h1>
    
<!--Description of grocer website and explain admin functions -->
<div class='row'>
    <div class='col-xs-12'>
        <p>Welcome to the grocer home page of FreshShop! <?php if(!$StoreName) {echo "Please <a href='GrocerLogin.php'>login</a> or <a href='GrocerInput.php'>create an account</a>
            to access other grocer functions.";}?></p>
        <p>If you are a shopper and looking to browse our aisles of fresh food, <a href='CustomerRedirect.php'>please click here.</a></p>
        <p>As a regular employee without administrator permissions, you'll be able to view orders that are submitted by customers.
        You'll be able to view the customer information as well as updating the order status. </p>
        
        <p>If you have administrative permissions, you have the same abilities as a regular employee in addition to being able to add, edit, or
        remove existing employees from your store's records.</p>
    </div>
</div>

<h2>Grocer Tools</h2>

<!-- Link and explanation of Product Input page -->
<div class='row'>
    <div class='col-xs-12'>
        <a href='ProductInput.php'>Product Input</a>
        <p>On the product input page, you can add products to your stores inventory. Here you can also update the prices and inventory in stock.
        As customers purchase your products, the inventory will automatically subtract the quantity purchased after the order is filled. At the
        bottom of the page, you can view all of the existing products you have in your store.</p>
    </div>
</div>

<!-- Link and explanation of Category Input page -->
<div class='row'>
    <div class='col-xs-12'>
        <a href='CategoryInput.php'>Category Input</a>
        <p>On the category input page you can add categories of products if you don't see it in the product input page. Having descriptive categories
        will help your customers find items while shopping online.</p>
    </div>
</div>

<!-- Link and explanation of Orders page -->
<div class='row'>
    <div class='col-xs-12'>
        <a href='GrocerOrders.php'>Orders</a>
        <p>On the main order page, you will see all the unfinished orders at the top. At the bottom, there is a list of all fulfilled orders.
        For each order, you can see the details and update the orders status as it's filled, sent out for delivery, and delivered.</p>
    </div>
</div>

<!-- Link and explanation of Employees page -->
<div class='row'>
    <div class='col-xs-12'>
        <a href='EmployeeInput.php'>Employees</a>
        <p>This page is only accessible to administrators of your store. Here you can view all employees in your store, add new employees,
        and edit or delete existing ones.</p>
    </div>
</div>
        
    </div>
</body>
    
</html>