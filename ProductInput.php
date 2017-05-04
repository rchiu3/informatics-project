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
        
    <title>Product Input</title>
    </head>
    <body>

<?php

//Set current page to echo class=active in navbar

$page = 'ProductInput';
include_once('GrocerNav.php');

?>

<div class = "container" style = "margin-top:50px">
        
<!-- PHP code to manage the data submitted by the form -->
<?php

//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');

//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

//Check to see if Save button at the end of the form was clicked
if(isset($_POST['submit'])) {
    
    $ProductID = $_POST['ProductID'];
    $CategoryID = $_POST['Category-CategoryID'];
    $Price = $_POST['Price'];
    $ProductName = $_POST['ProductName'];
    $Inventory = $_POST['Inventory'];
    
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
        punt ($errorMessage);
    }

    //SQL to insert data from completed form into database as a new record
    $query = 'INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ("' . $ProductName . '", ' . $Price . ', ' . $CategoryID . ', ' . $Inventory . ', ' . $StoreID . ');';
    
    //run the insert statement
    $result = queryDB($query, $db);
    
    //get ProductID of product that was just entered
    $ProductID = mysqli_insert_id($db);
    
    //check if user uploaded a picture
    if ($_FILES['Picture']['size'] > 0) {
        
        $tmpName = $_FILES['Picture']['tmp_name'];
        $fileName = $_FILES['Picture']['name'];
        
        $newFileName = $imagesDir . $ProductID . $fileName;
        
        if (move_uploaded_file($tmpName, $newFileName)) {
            $query = "UPDATE Product SET Picture = '$newFileName' WHERE ProductID = " . $ProductID . ";";
            queryDB($query,$db);
        }
        else {
            echo "Error copying image";
        }
    }
    
    // Inform user that insert statement was successfully executed
    if ($isComplete) {
    echo "<br>";
    echo "<div class='alert alert-success'>";
        echo ("<strong>Success!</strong> Entered new product: " . $ProductName);
    echo "</div>";
    }
}

?>

<!-- Title -->
<div class = "row">
    <div class = "col-xs-12">
        <h1>Product Input</h1>
    </div>
</div>

<br>

<!-- Form to enter new products -->
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
    <label for = "Inventory">Quantity in Stock:</label>
    <input type = "text" class = "form-control" name = "Inventory"/>
</div>

<!-- Picture -->
<div class = "form-group">
    <label for = "Picture">Product Picture:</label>
    <input type = "file" class = "form-control" name = "Picture"/>
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
                <th></th>
                <th></th>
                <th></th>
            </thead>
            
<!-- Use php to display data -->
<?php
    
//query to find information about products from database
$query = 'SELECT P.ProductID, P.ProductName, P.Price, P.Inventory, P.Picture, C.CategoryName FROM Product P, Category C WHERE P.CategoryID = C.CategoryID AND P.StoreID = ' . $StoreID . ';';
    
$result = queryDB($query, $db);
    
while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['CategoryName'] . "</td>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "<td>$" . $row['Price'] . "</td>";
    echo "<td>" . $row['Inventory'] . "</td>";
    // picture
    echo "<td>";
    if ($row['Picture']) {
        $imageLocation = $row['Picture'];
        $altText = $row['ProductName'];
        echo "<img src='$imageLocation' width='150' alt='$altText'>";
    }
    echo "</td>";
    echo "<td><a href='UpdateProduct.php?ProductID=" . $row['ProductID'] . "'>edit</a></td>";
    echo "<td><a href='DeleteProduct.php?ProductID=" . $row['ProductID'] . "'>delete</a></td>";
    echo "</tr> \n";
    }
?>
        </table>
    </div>
</div>
</div>

    </body>
</html>