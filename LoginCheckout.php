<?php
	// include config.php and dbutils.php
	include_once('config.php');
	include_once('dbutils.php');
    //start session to track customer
    session_start();
	$StoreID = $_SESSION['StoreID'];
	$CustomerID = $_SESSION['CustomerID'];
	$OrderID = $_SESSION['OrderID'];
    // Use for Navbar Active tab
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

	//error message if user didnt enter email
    if (!$CustomerEmail)
	{
        $errorMessage .= " Please enter an email.";
        $isComplete = false;
    } else
	{
        $CustomerEmail = makeStringSafe($db, $CustomerEmail);
    }
	
	//error message if user didnt enter password
    if (!$CustomerPass)
	{
        $errorMessage .= " Please enter a password.";
        $isComplete = false;
    }
	//display error message if a password and/or email were not entered
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
			//Add CustomerEmail and ID to session
			$_SESSION['CustomerEmail'] = $CustomerEmail;
			$_SESSION['CustomerID'] = $CustomerID;
			//Send customer to checkout page if they're password matched
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
<!-- Login Button -->
    <button type="submit" class="btn btn-default" name="submit">Login</button>
</form>
<!-- Create Account link -->
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