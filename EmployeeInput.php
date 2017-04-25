<!-- This file allows users to make accounts -->

<?php
    include_once('config.php');
    include_once('dbutils.php');

//Kick users if they are not logged in
    session_start();
    if (!isset($_SESSION['EmployeeEmail'])) {
        header('Location: GrocerLogin.php');
        exit;
    }
    
    $StoreName = $_SESSION['StoreName'];
	$StoreID = $_SESSION['StoreID'];
	$Admin = $_SESSION['Admin'];
	
	if (!$Admin) {
		header('Location: GrocerHome.php');
		exit;
	}

?>

<html>
    <head>

<title>Add Employee</title>

<!-- This is the code from bootstrap -->        
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
    </head>
    
    <body>
		
<?php

//Set current page to echo class=active in navbar

$page = 'EmployeeInput';
include_once('GrocerNav.php');

?>
	
	<div class="container" style="margin-top:50px">

<!-- Visible title -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Add Employees</h1>
            </div>
        </div>
        
<!-- Processing form input -->
        <div class="row">
            <div class="col-xs-12">
<?php
//
// Code to handle input from form
//

if (isset($_POST['submit'])) {
    // only run if the form was submitted
    
    // get data from form
	$EmployeeEmail = $_POST['EmployeeEmail'];
    $EmployeeUser = $_POST['EmployeeUser'];
	$EmployeePass = $_POST['EmployeePass'];
	$EmployeePass2 = $_POST['EmployeePass2'];
    $EmployeeAdmin = $_POST['EmployeeAdmin'];
    $EmployeeName = $_POST['EmployeeName'];
    
   // connect to the database
    $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);    
    
    // check for required fields
    $isComplete = true;
    $errorMessage = "";
    
	if (!$EmployeeEmail) {
        $errorMessage .= " Please enter an email for the employee.";
        $isComplete = false;
    } else {
        $EmployeeEmail = makeStringSafe($db, $EmployeeEmail);
    }
	
    if (!$EmployeeUser) {
        $errorMessage .= " Please enter a username for the employee.";
        $isComplete = false;
    } else {
        $EmployeeUser = makeStringSafe($db, $EmployeeUser);
    }

    if (!$EmployeePass) {
        $errorMessage .= " Please enter a password.";
        $isComplete = false;
    }
	
	if (!$EmployeePass2) {
        $errorMessage .= " Please enter a password again.";
        $isComplete = false;
    }
	
	if ($EmployeePass != $EmployeePass2) {
		$errorMessage .= " Your passwords do not match.";
		$isComplete = false;
	}
    
    if (!$EmployeeName) {
        $errorMessage .= " Please enter your name.";
        $isComplete = false;
    }   
	
    if ($isComplete) {
    
		// Check for existing user with same email
		$query = "SELECT EmployeeUser FROM Employee WHERE EmployeeUser = '" . $EmployeeUser . "';";
		$result = queryDB($query, $db);
		if (nTuples($result) == 0) {
			
			// generate the hashed version of the password
			$hashedpass = crypt($EmployeePass, getSalt());
			
			// SQL to insert record
			$insert = "INSERT INTO Employee(EmployeeName, EmployeeEmail, EmployeeUser, EmployeePass, EmployeeAdmin, StoreID) VALUES ('" . $EmployeeName . "','" . $EmployeeEmail . "', '" . $EmployeeUser . "', '" . $hashedpass . "', $EmployeeAdmin, $StoreID);";
		
			// run the insert statement
			$result = queryDB($insert, $db);
			
			// we have successfully inserted the record
			echo ("Successfully entered " . $EmployeeName . " into the database.");
		} else {
			$isComplete = false;
			$errorMessage = "Sorry. We already have a user account under the username: " . $EmployeeUser;
		}
	}
}
?>
            </div>
        </div>
		
		
<!-- Showing errors -->
<div class="row">
    <div class="col-xs-12">
<?php
    if (isset($isComplete) && !$isComplete) {
        echo '<div class="alert alert-danger" role="alert">';
        echo ($errorMessage);
        echo '</div>';
    }
?>            
    </div>
</div>

<!-- Form for user to input information -->
        <div class="row">
            <div class="col-xs-12">
                
<form action="EmployeeInput.php" method="post">
	
<!-- name -->
	<div class="form-group">
		<label for="EmployeeName">Name</label>
		<input type="name" class="form-control" name="EmployeeName"/>
	</div>

<!-- email -->
    <div class="form-group">
        <label for="EmployeeEmail">Email</label>
        <input type="email" class="form-control" name="EmployeeEmail"/>
    </div>

<!-- username -->
    <div class="form-group">
        <label for="EmployeeUser">Username</label>
        <input type="name" class="form-control" name="EmployeeUser"/>
    </div>

<!-- password1 -->
    <div class="form-group">
        <label for="EmployeePass">Password</label>
        <input type="password" class="form-control" name="EmployeePass"/>
    </div>

<!-- password2 -->
    <div class="form-group">
        <label for="EmployeePass2">Enter password again</label>
        <input type="password" class="form-control" name="EmployeePass2"/>
    </div>
	
<!-- admin -->
	<div class="form-group">
		<label for="EmployeeAdmin">Is this user an admin?:</label>
		<label class="radio-inline">
        <input type="radio" name="EmployeeAdmin" value="1" <?php if($EmployeeAdmin || !isset($EmployeeAdmin)) { echo 'checked'; } ?>> Yes
    </label>    
    <label class="radio-inline">
        <input type="radio" name="EmployeeAdmin" value="0" <?php if(!$EmployeeAdmin && isset($EmployeeAdmin)) { echo 'checked'; } ?>> No
    </label>    
	</div>
    
    <button type="submit" class="btn btn-default" name="submit">Add Employee</button>
</form>
                
            </div>
        </div>
      
<!-- HTML Table to display data -->
<div class = "row">
    <div class = "col-xs-12">
        <table class = "table table-hover">
            <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Admin?</th>
                <th></th>
                <th></th>
            </thead>
            
<!-- Use php to display data -->
<?php
    
//query to find information about employees from database
$query = 'SELECT EmployeeID, EmployeeName, EmployeeEmail, EmployeeUser, EmployeeAdmin FROM Employee WHERE StoreID = ' . $StoreID . ';';

$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
    
$result = queryDB($query, $db);
    
while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['EmployeeName'] . "</td>";
    echo "<td>" . $row['EmployeeEmail'] . "</td>";
    echo "<td>" . $row['EmployeeUser'] . "</td>";
	if ($row['EmployeeAdmin']) {
            $EmployeeAdmin = 'Yes';
        } else {
            $EmployeeAdmin = 'No';
        }
    echo "<td>" . $EmployeeAdmin . "</td>";
    echo "<td><a href='UpdateEmployee.php?EmployeeID=" . $row['EmployeeID'] . "'>edit</a></td>";
    echo "<td><a href='DeleteEmployee.php?EmployeeID=" . $row['EmployeeID'] . "'>delete</a></td>";
    echo "</tr> \n";
    }
?>
        </table>
    </div>
</div>
</div>
        
    </body>
    
</html>s