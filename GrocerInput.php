<!-- This file allows grocers to make accounts -->

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
		
<?php

//Set current page to echo class=active in navbar

$page = 'GrocerInput';
include_once('GrocerNav.php');

?>
	
	<div class="container" style="margin-top:50px">

<!-- Visible title -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Create Account</h1>
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
    $StoreName = $_POST['StoreName'];
    
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
		$result = queryDB($query, $db);
		if (nTuples($result) == 0) {
		
			// Check for existing store
			$query = 'SELECT StoreID FROM Store WHERE StoreName LIKE "' . $StoreName . '";';
			$result = queryDB($query, $db);
			if (nTuples($result) == 0) {
				
				// add store into store table
				$query = 'INSERT INTO Store(StoreName) VALUES ("' . $StoreName . '");';
				$result = queryDB($query, $db);
				
				// get store id for newly added store
				$query = 'SELECT StoreID FROM Store WHERE StoreName LIKE "' . $StoreName . '";';
				$result = queryDB($query, $db);
				
				while($row = nextTuple($result)) {
					$StoreID = $row['StoreID'];
				}
				
				// generate the hashed version of the password
				$hashedpass = crypt($EmployeePass, getSalt());
				
				// SQL to insert record
				$insert = "INSERT INTO Employee(EmployeeName, EmployeeEmail, EmployeePass, EmployeeAdmin, StoreID) VALUES ('" . $EmployeeName . "', '" . $EmployeeEmail . "', '" . $hashedpass . "', $EmployeeAdmin, $StoreID);";
			
				// run the insert statement
				$result = queryDB($insert, $db);
				
				// we have successfully inserted the record
				echo ("Successfully created account " . $EmployeeEmail);
			}
			else {
				$query = 'SELECT StoreID FROM Store WHERE StoreName LIKE "' . $StoreName . '";';
				$result = queryDB($query, $db);
				
				while($row = nextTuple($result)) {
					$StoreID = $row['StoreID'];
				}
				
				// generate the hashed version of the password
				$hashedpass = crypt($EmployeePass, getSalt());
				
				// SQL to insert record
				$insert = "INSERT INTO Employee(EmployeeName, EmployeeEmail, EmployeePass, EmployeeAdmin, StoreID) VALUES ('" . $EmployeeName . "', '" . $EmployeeEmail . "', '" . $hashedpass . "', $EmployeeAdmin, $StoreID);";
			
				// run the insert statement
				$result = queryDB($insert, $db);
				
				// we have successfully inserted the record
				echo ("Successfully created account " . $EmployeeUser);
			}
			
			//Upload and enter picture into SQL if user uploaded a file
			if ($_FILES['Picture']['size'] > 0) {
        
			$tmpName = $_FILES['Picture']['tmp_name'];
			$fileName = $_FILES['Picture']['name'];
        
			$newFileName = $imagesDir . $ProductID . $fileName;
        
			if (move_uploaded_file($tmpName, $newFileName)) {
				$query = "UPDATE Store SET Picture = '$newFileName' WHERE StoreID = " . $StoreID . ";";
				queryDB($query,$db);
			}
			else {
				echo "Error copying image";
        }
    }
			
		}
		else {
			$isComplete = false;
			$errorMessage = "Sorry. We already have a user registered with the email " . $EmployeeEmail;
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
    <label for="StoreName">Store Name:</label>
    <input type="name" class="form-control" name="StoreName"/>
</div>

	
<!-- admin -->
	<div class="form-group">
		<label for="EmployeeAdmin">Is this employee an admin?:</label>
		<label class="radio-inline">
        <input type="radio" name="EmployeeAdmin" value="1" <?php if($EmployeeAdmin || !isset($EmployeeAdmin)) { echo 'checked'; } ?>> Yes
    </label>    
    <label class="radio-inline">
        <input type="radio" name="EmployeeAdmin" value="0" <?php if(!$EmployeeAdmin && isset($EmployeeAdmin)) { echo 'checked'; } ?>> No
    </label>    
	</div>
	
<!-- Picture -->
<div class = "form-group">
    <label for = "Picture">Store Logo/Picture:</label>
    <input type = "file" class = "form-control" name = "Picture"/>
</div>
    
    <button type="submit" class="btn btn-default" name="submit">Create Account</button>
</form>

<!-- Link to login if user already has an account -->
<div class="row">
	<div class="col-xs-12">
		<p>Already have an account? <a href = "GrocerLogin.php">Click here to login</a></p>
	</div>
</div>
                
            </div>
        </div>
      

        
    </body>
    
</html>