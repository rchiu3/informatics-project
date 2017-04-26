<!-- This file allows Customers to make accounts -->


<?php
    include_once('config.php');
    include_once('dbutils.php');
	$page = 'CustomerInput';
	include_once('CustomerNav.php');
?>

<html>
    <head>
.
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


	<div class="container" style="margin-top:50px">

<!-- Visible title -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Customer Sign Up</h1>
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
    $CustomerEmail = $_POST['CustomerEmail'];
	$CustomerPass = $_POST['CustomerPass'];
	$CustomerPass2 = $_POST['CustomerPass2'];
    $CustomerAddress = $_POST['CustomerAddress'];
    $CustomerName = $_POST['CustomerName'];

   // connect to the database
    $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

    // check for required fields
    $isComplete = true;
    $errorMessage = "";

    if (!$CustomerEmail) {
        $errorMessage .= " Please enter an email.";
        $isComplete = false;
    } else {
        $CustomerEmail = makeStringSafe($db, $CustomerEmail);
    }

    if (!$CustomerPass) {
        $errorMessage .= " Please enter a password.";
        $isComplete = false;
    }

	if (!$CustomerPass2) {
        $errorMessage .= " Please enter a password again.";
        $isComplete = false;
    }

	if ($CustomerPass != $CustomerPass2) {
		$errorMessage .= " Your passwords do not match.";
		$isComplete = false;
	}

    if (!$CustomerName) {
        $errorMessage .= " Please enter your name.";
        $isComplete = false;
    }

    if (!$CustomerAddress) {
        $errorMessage .= " Please enter your address.";
        $isComplete = false;
    }


    if ($isComplete) {

		// Check for existing customer with same email
		$query = "SELECT CustomerEmail FROM Customer WHERE CustomerEmail = '" . $CustomerEmail . "';";
		$result = queryDB($query, $db);
		if (nTuples($result) == 0) {

			// generate the hashed version of the password
			$hashedpass = crypt($CustomerPass, getSalt());

			// SQL to insert record
			$insert = "INSERT INTO Customer(CustomerName, CustomerEmail, CustomerPass, CustomerAddress) VALUES ('" . $CustomerName . "', '" . $CustomerEmail . "', '" . $hashedpass . "', '" . $CustomerAddress . "');";

			// run the insert statement
			$result = queryDB($insert, $db);

			// we have successfully inserted the record
			echo ("Successfully entered " . $CustomerEmail . " into the database.");
		} else {
			$isComplete = false;
			$errorMessage = "Sorry. We already have a customer account associated with the email you provided. " . $CustomerEmail;
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

<!-- Form for customer to input information -->
        <div class="row">
            <div class="col-xs-12">

<form action="CustomerInput.php" method="post">

<!-- name -->
	<div class="form-group">
		<label for="CustomerName">Name</label>
		<input type="name" class="form-control" name="CustomerName"/>
	</div>

<!-- email -->
    <div class="form-group">
        <label for="CustomerEmail">Email</label>
        <input type="email" class="form-control" name="CustomerEmail"/>
    </div>

<!-- password1 -->
    <div class="form-group">
        <label for="CustomerPass">Password</label>
        <input type="password" class="form-control" name="CustomerPass"/>
    </div>

<!-- password2 -->
    <div class="form-group">
        <label for="CustomerPass2">Enter password again</label>
        <input type="password" class="form-control" name="CustomerPass2"/>
    </div>

<!-- address -->
    <div class="form-group">
        <label for="CustomerAddress">Address</label>
        <input type="address" class="form-control" name="CustomerAddress"/>
    </div>

    <button type="submit" class="btn btn-default" name="submit">Create Account</button>
</form>

<div class="row">
	<div class="col-xs-12">
		<p>Already have an account? <a href = "CustomerLogin.php">Click here to login</a></p>
	</div>
</div>

            </div>
        </div>



    </body>

</html>