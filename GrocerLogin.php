<?php
    include_once('config.php');
    include_once('dbutils.php');
    
?>

<html>
    <head>

<title>Login</title>

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
            <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
            <li class="active"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>
    </div>
</nav>

    <div class = "container" style = "margin-top:50px">

<!-- Visible title -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Login</h1>
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
	
    if (!$isComplete) {
        punt($errorMessage);
    }
    
    // get the hashed password from the user with the email that got entered
    $query = "SELECT EmployeeEmail, EmployeePass, StoreName FROM Employee E, Store S WHERE S.StoreID = E.StoreID AND EmployeeEmail='" . $EmployeeEmail . "';";
    $result = queryDB($query, $db);
    if (nTuples($result) > 0) {
        // there is an account that corresponds to the email that the user entered
		// get the hashed password for that account
		$row = nextTuple($result);
		$hashedpass = $row['EmployeePass'];
		$StoreName = $row['StoreName'];
		
		// compare entered password to the password on the database
		if ($hashedpass == crypt($EmployeePass, $hashedpass)) {
			// password was entered correctly
			
			// start a session
			if (session_start()) {
				$_SESSION['EmployeeEmail'] = $EmployeeEmail;
				$_SESSION['StoreName'] = $StoreName;
				header('Location: ProductInput.php');
				exit;
			} else {
				// if we can't start a session
				punt("Unable to start session when logging in.");
			}
		} else {
			// wrong password
			punt("Wrong password. <a href='GrocerLogin.php'>Try again</a>.");
		}
    } else {
		// email entered is not in the users table
		punt("This email is not in our system. <a href='GrocerLogin.php'>Try again</a>.");
	}
}
?>
            </div>
        </div>

<!-- form for inputting data -->
        <div class="row">
            <div class="col-xs-12">
                
<form action="GrocerLogin.php" method="post">
<!-- email -->
    <div class="form-group">
        <label for="EmployeeEmail">Email</label>
        <input type="email" class="form-control" name="EmployeeEmail"/>
    </div>

<!-- password -->
    <div class="form-group">
        <label for="EmployeePass">Password</label>
        <input type="password" class="form-control" name="EmployeePass"/>
    </div>

    <button type="submit" class="btn btn-default" name="submit">Login</button>
</form>

<div class="row">
	<div class="col-xs-12">
		<p>Don't have an account? <a href = "GrocerInput.php">Click here to create one.</a></p>
	</div>
</div>
                
            </div>
        </div>
            
</div>
</div>

        
    </body>
    
</html>