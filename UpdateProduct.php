<?php

//Kick users if they are not logged in
    session_start();
    if (!isset($_SESSION['EmployeeEmail'])) {
        header('Location: GrocerLogin.php');
        exit;
    }
    
    $StoreName = $_SESSION['StoreName'];
    $StoreID = $_SESSION['StoreID'];

/*
 * This php file enables users to edit a particular product
 * It obtains the id for the product to update from an ID variable passed using the GET method (in the url)
 *
 */
    include_once('config.php');
    include_once('dbutils.php');
    
    /*
     * If the user submitted the form with updates, we process the form with this block of code
     *
     */
    if (isset($_POST['submit'])) {
        // process the update if the form was submitted
        
        // get data from form
        $ProductID = $_POST['ProductID'];
        if (!isset($ProductID)) {
            // if for some reason the ID didn't post, kick them back to ProductInput.php
            header('Location: ProductInput.php');
            exit;
        }

        // get data from form
        $ProductID = $_POST['ProductID'];
        $CategoryID = $_POST['Category-CategoryID'];
        $Price = $_POST['Price'];
        $ProductName = $_POST['ProductName'];
        $Inventory = $_POST['Inventory'];
        
        // variable to keep track if the form is complete (set to false if there are any issues with data)
        $isComplete = true;
        
        // error message we'll give user in case there are issues with data
        $errorMessage = "";
        
        
        // check each of the required variables in the table        
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
        
        // If there's an error, they'll go back to the form so they can fix it
        
        if($isComplete) {
            // if there's no error, then we need to update
            
            //
            // first update product record
            //
            // put together SQL statement to update the product
            $query = 'UPDATE Product SET CategoryID=' . $CategoryID . ', Price=' . $Price . ', ProductName="' . $ProductName . '", Inventory=' . $Inventory . ' WHERE ProductID=' . $ProductID . ';';
            
            // connect to the database
            $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
            
            // run the update
            $result = queryDB($query, $db);            
            
            // now that we are done, send user back to ProductInput.php and exit 
            header("Location: ProductInput.php?successmessage=Successfully updated product $ProductName");
            exit;
        }        
    } else {
        //
        // if the form was not submitted (first time in)
        //
    
        /*
         * Check if a GET variable was passed with the ID for the product
         *
         */
        if(!isset($_GET['ProductID'])) {
            // if the ID was not passed through the url
            
            // send them out to ProductInput.php and stop executing code in this page
            header('Location: ProductInput.php');
            exit;
        }
        
        /*
         * Now we'll check to make sure the ID passed through the GET variable matches the ID of a product in the database
         */
        
        // connect to the database
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
        
        // set up a query
        $ProductID = $_GET['ProductID'];
        $query = "SELECT * FROM Product WHERE ProductID=$ProductID;";
        
        // run the query
        $result = queryDB($query, $db);
        
        // if the id is not in the product table, then we need to send the user back to ProductInput.php
        if (nTuples($result) == 0) {
            // send them out to ProductInput.php and stop executing code in this page
            header('Location: ProductInput.php');
            exit;
        }
        
        /*
         * Now we know we got a valid product ID through the GET variable
         */
        
        // get data on product to fill out form with existing values
        $row = nextTuple($result);
        
        $ProductID = $row['ProductID'];
        $CategoryID = $row['Category-CategoryID'];
        $Price = $row['Price'];
        $ProductName = $row['ProductName'];
        $Inventory = $row['Inventory'];
        
        }
?>

<html>
    <head>
<!-- Bootstrap links -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>        
        
        <title>Update product <?php echo $ProductName; ?></title>
    </head>
    <body>
    
<?php

//Set current page to echo class=active in navbar

$page = 'ProductInput';
include_once('GrocerNav.php');

?>

    <div class = "container" style = "margin-top:50px">
       
<!-- Title -->
<div class="row">
    <div class="col-xs-12">
        <h1>Update product <?php echo $ProductName; ?></h1>        
    </div>
</div>


<!-- Showing errors if any exist -->
<div class="row">
    <div class="col-xs-12">
<?php
    if (isset($isComplete) && !$isComplete) {
        // executes only if form was previously submitted (and therefore $isComplete is set) and isComplete was set to false
        // you'll never be here if the form wasn't submitted (the first time you get in)
        
        echo '<div class="alert alert-danger" role="alert">';
        echo ($errorMessage);
        echo '</div>';
    }
?>            
    </div>
</div>



<!-- form to update product -->
<div class="row">
    <div class="col-xs-12">
        
<form action="UpdateProduct.php" method="post">

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
<div class="form-group">
    <label for="ProductName">Product Name:</label>
    <input type="text" class="form-control" name="ProductName" value="<?php if($ProductName) { echo $ProductName; } ?>"/>
</div>

<!-- Price -->
<div class="form-group">
    <label for="Price">Price:</label>
    <input type="number" step=".01" class="form-control" name="Price" value="<?php if($Price) { echo $Price; } ?>"/>
</div>


<!-- Inventory -->
<div class="form-group">
    <label for="Inventory">Inventory:</label>
    <input type="number" class="form-control" name="Inventory" value="<?php if($Inventory) { echo $Inventory; } ?>"/>
</div>

<!-- hidden id (not visible to user, but need to be part of form submission so we know which product we are updating -->
<input type="hidden" name="ProductID" value="<?php echo $ProductID; ?>"/>

<button type="submit" class="btn btn-default" name="submit">Save</button>

</form>
        
        
    </div>
</div>
        </div>
       
       
        
    </body>
</html>