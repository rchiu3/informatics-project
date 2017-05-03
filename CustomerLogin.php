<?php

//include config.php dbutils.php
	include_once('config.php');
	include_once('dbutils.php');
	
	//used for active tab in nav bar
	$page = 'CustomerLogin';
	//navbar
	include_once('CustomerNav.php');
	
//
// Code to handle input from form
//
//if
if (isset($_POST['submit']))
{

    // only run if the form was submitted

    // get data from form
    $CustomerEmail = $_POST['CustomerEmail'];
	$CustomerPass = $_POST['CustomerPass'];


   // connect to the database
    $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

    // check for required fields
    $isComplete = true;
    $errorMessage = "";
//failed to enter email
    if (!$CustomerEmail)
	{
		//add to error message
        $errorMessage .= " Please enter an email.";
		//form not complete
        $isComplete = false;
    }
	else
	{
        $CustomerEmail = makeStringSafe($db, $CustomerEmail);
    }
	//failed to enter password
    if (!$CustomerPass)
	{
		//add to error message
        $errorMessage .= " Please enter a password.";
		//form not complete
        $isComplete = false;
    }
	//form not complete(missing required field)
    if (!$isComplete)
	{
        punt($errorMessage);
    }

    // get the hashed password from the user with the email that got entered
    $query = "SELECT CustomerEmail, CustomerID, CustomerPass FROM Customer WHERE CustomerEmail='" . $CustomerEmail . "';";
    $result = queryDB($query, $db);
    if (nTuples($result) > 0) {
        // there is an account that corresponds to the email that the user entered
		// get the hashed password for that account
		$row = nextTuple($result);
		$hashedpass = $row['CustomerPass'];
		$CustomerID = $row['CustomerID'];

		// compare entered password to the password on the database
		if ($hashedpass == crypt($CustomerPass, $hashedpass))
		{
			// password was entered correctly

			// start a session
			if (session_start())
			{
				//add to session
				$_SESSION['CustomerEmail'] = $CustomerEmail;
				$_SESSION['CustomerID'] = $CustomerID;
				//send customer to home page
				header('Location: CustomerHome.php');
				exit;
			}
			else
			{
				// if we can't start a session
				punt("Unable to start session when logging in.");
			}
		}
		else
		{
			// wrong password
			punt("Wrong password. <a href='CustomerLogin.php'>Try again</a>.");
		}
    } else {
		// email entered is not in the users table
		punt("This email is not in our system. <a href='CustomerLogin.php'>Try again</a>.");
	}
	
}
?>

<html>
    <head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</head>
<title>Log-in</title>
<body>


	<div class="container" style="margin-top:50px">


<!-- Visible title -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Login</h1>
            </div>
        </div>

<!-- Processing form input -->
        <div class="row">
            <div class="col-xs-12">


            </div>
        </div>

<!-- form for inputting data  -->
        <div class="row">
            <div class="col-xs-12">

<form action="CustomerLogin.php" method="post">
<!-- email -->
    <div class="form-group">
        <label for="CustomerEmail">Email</label>
        <input type="email" class="form-control" name="CustomerEmail"/>
    </div>

<!-- password -->
    <div class="form-group">
        <label for="CustomerPass">Password</label>
        <input type="password" class="form-control" name="CustomerPass"/>
    </div>
<!-- Button to Login -->

    <button type="submit" class="btn btn-default" name="submit">Login</button>
</form>
<!-- link to create an account-->
<div class="row">
	<div class="col-xs-12">
		<p>Don't have an account? <a href = "CustomerInput.php">Click here to create one.</a></p>
	</div>
</div>

            </div>
        </div>

</div>
</div>

</body>
</html>