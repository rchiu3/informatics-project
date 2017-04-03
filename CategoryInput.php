<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <title>Category Input</title>
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

    $CategoryName = $_POST['CategoryName'];
    
    //Check to see if form is complete, if not, display an error message
    $isComplete = true;
    $errorMessage = "";
    
    if(!$CategoryName) {
        $errorMessage .= "Please enter a category name. \n";
        $isComplete = false;
    }
    
    if(!$isComplete) {
        punt($errorMessage);
    }
    
    //SQL to insert data from completed form into database as a new record
    $query = "INSERT INTO Category(CategoryName) VALUES ('$CategoryName');";
    
    //run the insert statement
    $result = queryDB($query, $db);
    
    //Inform user that insert statement was successfully executed
    echo ("Successfully entered new category: " . $CategoryName);
}

?>

<!-- Title -->
<div class = "row">
    <div class = "col-xs-12">
        <h1>Product Categories</h1>
    </div>
</div>

<!-- Link to CategoryInput.php -->
<div class = "row">
    <div class = "col-xs-12">
        <a href = "ProductInput.php">Click here to enter products</a>
    </div>
</div>

<br>

<!-- Form to enter new categories -->
<div class = "row">
    <div class = "col-xs-12">
        
<form action = "CategoryInput.php" method = "post">
    
<!-- Name -->
<div class = "form-group">
    <label for = "CategoryName">Category Name:</label>
    <input type = "text" class = "form-control" name = "CategoryName"/>
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
            </thead>
            
<!-- Use php to display data -->
<?php
    
//query to find information about cars from database
$query = 'SELECT CategoryName FROM Category ORDER BY CategoryName;';
    
$result = queryDB($query, $db);
    
while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['CategoryName'] . "</td>";
    echo "</tr> \n";
    }
?>
        </table>
    </div>
</div>
</div>

    </body>
</html>
