<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <title>Product Input</title>
    </head>
    
    <body>
        <div class = "container">
        
<!-- PHP code to manage the data submitted by the form -->
<?php

//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');

//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

//Check to see if Save button at the end of the form was clicked
if(isset($_POST['submit'])) {
    
    $CategoryID = $_POST['Category-CategoryID'];
    $Price = $_POST['Price'];
    $ProductName = $_POST['ProductName'];
    $Inventory = $_POST['Inventory'];
    $StoreID = 1;
    
    //Check to see if form is complete, if not, display an error message
    $isComplete = true;
    $errorMessage = "";
    
    if(!isset($CategoryID)) {
        $errorMessage .= "Please select a category for the product. \n";
        $isComplete = false;
    }
    if(!$ProductName) {
        $errorMessage .= "Please enter a product name. \n";
        $isComplete = false;
    }
    if(!$Price) {
        $errorMessage .= "Please enter a price for the product. \n";
        $isComplete = false;
    }
    
    if(!$isComplete) {
        punt($errorMessage);
    }

    //SQL to insert data from completed form into database as a new record
    $query = "INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('$ProductName', $Price, $CategoryID, $Inventory, $StoreID);";
    
    //run the insert statement
    $result = queryDB($query, $db);
    
    //Inform user that insert statement was successfully executed
    echo ("Successfully entered new product: " . $ProductName);
}

?>

<!-- Title -->
<div class = "row">
    <div class = "col-xs-12">
        <h1>Product Input</h1>
    </div>
</div>

<!-- Link to CategoryInput.php -->
<div class = "row">
    <div class = "col-xs-12">
        <a href = "https://webdev.cs.uiowa.edu/~ruchiu/informatics-project/CategoryInput.php">Click here to enter new product categories</a>
    </div>
</div>

<br>

<!-- Form to enter new cars -->
<div class = "row">
    <div class = "col-xs-12">
        
<form action = "ProductInput.php" method = "post" enctype="multipart/form-data">
    
<!-- Category -->
<div class = "form-group">
    <label for="Category">Category:</label>
    <?php
    //connect to database
    if (!isset($db)) {
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
    }
    echo (generateDropdown($db,"Category", "CategoryName", "CategoryID", $CategoryID));
    ?>
</div>

<!-- Product Name -->
<div class = "form-group">
    <label for = "ProductName">Product Name:</label>
    <input type = "text" class = "form-control" name = "ProductName"/>
</div>

<!-- Price -->
<div class = "form-group">
    <label for = "Price">Price:</label>
    <input type = "number" step = ".01" class = "form-control" name = "Price"/>
</div>

<!-- Inventory -->
<div class = "form-group">
    <label for = "trim">Quantity in Stock:</label>
    <input type = "text" class = "form-control" name = "Inventory"/>
</div>

<button type = "submit" class = "btn btn-default" name = "submit">Save</button>
</form>
        
    </div>
</div>

<!-- HTML Table to display data -->
<div class = "row">
    <div class = "col-xs-12">
        <table class = "table table-hover">
            <thead>
                <th>Category</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity in Stock</th>
            </thead>
            
<!-- Use php to display data -->
<?php
    
//query to find information about cars from database
$query = 'SELECT P.ProductName, P.Price, P.Inventory, P.Picture, C.CategoryName FROM Product P, Category C WHERE P.CategoryID = C.CategoryID;';
    
$result = queryDB($query, $db);
    
while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['CategoryName'] . "</td>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>" . $row['Price'] . "</td>";
    echo "<td>" . $row['Inventory'] . "</td>";
    echo "</tr> \n";
    }
?>
        </table>
    </div>
</div>
</div>

    </body>
</html>