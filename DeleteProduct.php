<?php

//Kick users if they are not logged in
    session_start();
    if (!isset($_SESSION['EmployeeEmail'])) {
        header('Location: GrocerLogin.php');
        exit;
    }
    
    $StoreName = $_SESSION['StoreName'];
    
?>


<?php
/*
 * This php file prompts users on whether they want to delete a product
 * It obtains the id for the product to delete from an ID variable passed using the GET method (in the url)
 *
 */
    include_once('config.php');
    include_once('dbutils.php');
    
    /*
     * If the user just made a decision on a deletion by using the form below, we process that below
     *
     */
    if (isset($_POST['submit'])) {
        // process the deletion (if selected) if the form below was submitted        
        
        // get data from form
        $ProductID = $_POST['ProductID'];
        $delete = $_POST['delete'];
        
        if ($delete == 'yes') {
            // if the user said yes to delete, we need to delete the product with ProductID = $ProductID
            
            // connect to the database
            $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
            
            // query to delete product
            $query = "DELETE FROM Product WHERE ProductID = $ProductID;";
            
            // run the delete statement to remove product from product table
            queryDB($query, $db);
        }
        
        // send user back to ProductInput.php and exit 
        header('Location: ProductInput.php');
        exit;
    }
    
    

    /*
     * Check if a GET variable was passed with the ID for the product
     *
     */
    if(!isset($_GET['ProductID'])) {
        // if the id was not passed through the url
        
        // send them out to ProductInput.php and stop executing code in this page
        header('Location: ProductInput.php');
        exit;
    }
    
    /*
     * Now we'll check to make sure the id passed through the GET variable matches the ID of a product in the database
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
    
    // get data from the Product table to ask a better question when confirming deletion
    $row = nextTuple($result);
    
    $ProductName = $row['ProductName'];    
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
        
        <title>Delete <?php echo $ProductName; ?>?</title>
    </head>
    <body>
    
<?php

//Set current page to echo class=active in navbar

$page = 'ProductInput';
include_once('GrocerNav.php');

?>

    <div class = "container" style = "margin-top:50px">
        
<!-- Visible title -->
<div class="row">
    <div class="col-xs-12">
        <h1>Do you want to delete <?php echo $ProductName; ?>?</h1>
    </div>
</div>

<!-- form to ask users to confim deletion -->
<div class="row">
    <div class="col-xs-12">
<form action="DeleteProduct.php" method="post">
    
    <!-- Radio buttons for yes or no selection -->
    <div class="radio">
        <label>
            <input type="radio" name="delete" value="yes" checked>
            Yes
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="delete" value="no">
            No
        </label>
    </div>
    
    <!-- Hidden product ID to use when form is submitted --> 
    <input type="hidden" name="ProductID" value="<?php echo $ProductID; ?>"/>
    
    <button type="submit" class="btn btn-default" name="submit">Submit</button>
</form>

    </div>
</div>
        </div>
        
    </body>
</html>