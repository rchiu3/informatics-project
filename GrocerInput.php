<!-- This file allows users to make accounts -->

<?php
    include_once('config.php');
    include_once('dbutils.php');
    
?>

<html>
    <head>

<title>Create Account</title>

<!-- This is the code from bootstrap -->        
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
    </head>
    
    <body>
		
	<!-- Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Store Overview</a>
        </div>
        
        <ul class="nav navbar-nav">
            <li><a href="GrocerHome.php">Home</a></li>
            <li><a href="ProductInput.php">Product Input</a></li>
            <li><a href="CategoryInput.php">Category Input</a></li>
            <li><a href="#">Orders</a></li>
            <li><a href="#">Employees</a></li>
        </ul>
        
        <ul class="nav navbar-nav navbar-right">
			<li><a href="GrocerLogin.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            <li class="active"><a href="GrocerInput.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
        </ul>
    </div>
</nav>
	
	<div class="container" style="margin-top:50px">

<!-- Visible title -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Enter Users</h1>
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
	$EmployeePass = $_POST['EmployeePass'];
	$EmployeePass2 = $_POST['EmployeePass2'];
    $EmployeeAdmin = $_POST['EmployeeAdmin'];
    $EmployeeName = $_POST['EmployeeName'];
    $StoreID = $_POST['Store-StoreID'];
    
   // connect to the database
    $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);    
    
    // check for required fields
    $isComplete = true;
    $errorMessage = "";
    
    if (!$EmployeeEmail) {
        $errorMessage .= " Please enter an email.";
        $isComplete = false;
    } else {
        $EmployeeEmail = makeStringSafe($db, $EmployeeEmail);
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
    
    if (!$EmployeeAdmin) {
        $errorMessage .= " Please indicate whether or not this employee is an admin.";
        $isComplete = false;
    }
	    
	
    if ($isComplete) {
    
		// Check for existing user with same email
		$query = "SELECT EmployeeEmail FROM Employee WHERE EmployeeEmail = '" . $EmployeeEmail . "';";
		echo ($query);
		$result = queryDB($query, $db);
		if (nTuples($result) == 0) {
			
			// generate the hashed version of the password
			$hashedpass = crypt($EmployeePass, getSalt());
			
			// SQL to insert record
			$insert = "INSERT INTO Employee(EmployeeName, EmployeeEmail, EmployeePass, EmployeeAdmin, StoreID) VALUES ('" . $EmployeeName . "', '" . $EmployeeEmail . "', '" . $hashedpass . "', $EmployeeAdmin, $StoreID);";
		
			// run the insert statement
			$result = queryDB($insert, $db);
			
			// we have successfully inserted the record
			echo ("Successfully entered " . $EmployeeEmail . " into the database.");
		} else {
			$isComplete = false;
			$errorMessage = "Sorry. We already have a user account under email " . $EmployeeEmail;
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
                
<form action="GrocerInput.php" method="post">
	
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
	
<!-- store -->
<div class = "form-group">
    <label for="StoreID">Store Name:</label>
    <?php
    //connect to database
    if (!isset($db)) {
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
    }
    echo (generateDropdown($db,"Store", "StoreName", "StoreID", $StoreID));
    ?>
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
    
    <button type="submit" class="btn btn-default" name="submit">Create Account</button>
</form>

<div class="row">
	<div class="col-xs-12">
		<p>Already have an account? <a href = "GrocerLogin.php">Click here to login</a></p>
	</div>
</div>
                
            </div>
        </div>
      

        
    </body>
    
</html>