<?php

	include_once('config.php');
	include_once('dbutils.php');
    
    session_start();
	$StoreID = $_SESSION['StoreID'];
	$CustomerID = $_SESSION['CustomerID'];
	$OrderID = $_SESSION['OrderID'];
    
	$page = 'CustomerLogin';
	include_once('CustomerNav.php');
	
//
// Code to handle input from form
//

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

    if (!$CustomerEmail)
	{
        $errorMessage .= " Please enter an email.";
        $isComplete = false;
    } else
	{
        $CustomerEmail = makeStringSafe($db, $CustomerEmail);
    }

    if (!$CustomerPass)
	{
        $errorMessage .= " Please enter a password.";
        $isComplete = false;
    }

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
			
			$_SESSION['CustomerEmail'] = $CustomerEmail;
			$_SESSION['CustomerID'] = $CustomerID;
			header('Location: Checkout.php');
			exit;

		}
		else
		{
			// wrong password
			punt("Wrong password. <a href='LoginCheckout.php'>Try again</a>.");
		}
    } else {
		// email entered is not in the users table
		punt("This email is not in our system. <a href='LoginCheckout.php'>Try again</a>.");
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

<!--    *** NOT SURE IF THIS STYLING WORKS SAW IT ONLINE BUT WE CAN TEST THIS OUT L8R     ***
		*** THOUGHT IF IT DID WORK IT COULD BE A GOOD START TO STYLING OUR PAGE UNIFORMLY ***
   <style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center ;
    padding: 14px 16px;
    text-decoration: none;
}

a:hover:not(.active) {
    background-color: #111;
}

.active {
background-color:#4CAF50;
}
</style>
-->
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

<!-- form for inputting data -->
        <div class="row">
            <div class="col-xs-12">

<form action="LoginCheckout.php" method="post">
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

    <button type="submit" class="btn btn-default" name="submit">Login</button>
</form>

<div class="row">
	<div class="col-xs-12">
		<p>Don't have an account? <a href = "CreateAccountCheckout.php">Click here to create one.</a></p>
	</div>
</div>

            </div>
        </div>

</div>
</div>

</body>
</html>