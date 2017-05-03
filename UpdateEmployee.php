<?php

//Kick users if they are not logged in
    session_start();
    if (!isset($_SESSION['EmployeeEmail'])) {
        header('Location: GrocerLogin.php');
        exit;
    }
    
    $StoreName = $_SESSION['StoreName'];
	$StoreID = $_SESSION['StoreID'];
	$Admin = $_SESSION['Admin'];
	
	//Kick users if they aren't an admin of the store
	if (!$Admin) {
		header('Location: GrocerHome.php');
		exit;
	}


/*
 * This php file enables users to edit a specific employee
 * It obtains the id for the product to update from an ID variable passed using the GET method (in the url)
 *
 */
    include_once('config.php');
    include_once('dbutils.php');
    
    /*
     * If the user submitted the form with updates, we process the form with this block of code
     *
     */
    if (isset($_POST['submit'])) {
        // process the update if the form was submitted
        
        // get data from form
        $EmployeeID = $_POST['EmployeeID'];
        if (!isset($EmployeeID)) {
            // if for some reason the ID didn't post, kick them back to EmployeeInput.php
            header('Location: EmployeeInput.php');
            exit;
        }

        // get data from form
        $EmployeeID = $_POST['EmployeeID'];
        $EmployeeName = $_POST['EmployeeName'];
        $EmployeeAdmin = $_POST['EmployeeAdmin'];
        $EmployeeEmail = $_POST['EmployeeEmail'];
		$EmployeePass = $_POST['EmployeePass'];
		$EmployeePass2 = $_POST['EmployeePass2'];
        
        // variable to keep track if the form is complete (set to false if there are any issues with data)
        $isComplete = true;
        
        // error message we'll give user in case there are issues with data
        $errorMessage = "";
        
        
        // check each of the required variables in the table        
        if(!$EmployeeName) {
            $errorMessage .= "Please select a name for the employee. \n";
            $isComplete = false;
        }
        if(!$EmployeeAdmin) {
            $errorMessage .= "Please indicate if employee is an admin. \n";
            $isComplete = false;
        }
        if(!$EmployeeEmail) {
            $errorMessage .= "Please enter an email for the employee. \n";
            $isComplete = false;
        }
		if ($EmployeePass != $EmployeePass2) {
			$errorMessage .= " Your passwords do not match.";
			$isComplete = false;
		}
        
        // If there's an error, they'll go back to the form so they can fix it
        
        if($isComplete) {
            // if there's no error, then we can update the employee record
        
            // first generate hashed pass
				if ($EmployeePass) {
					$hashedpass = crypt($EmployeePass, getSalt());
			
					// put together SQL statement to update the employee
					$query = "UPDATE Employee SET EmployeeName='$EmployeeName', EmployeeAdmin=$EmployeeAdmin, EmployeeEmail='$EmployeeEmail', EmployeePass='$hashedpass' WHERE EmployeeID=$EmployeeID;";
				}
				else {
					$query = "UPDATE Employee SET EmployeeName='$EmployeeName', EmployeeAdmin=$EmployeeAdmin, EmployeeEmail='$EmployeeEmail' WHERE EmployeeID=$EmployeeID;";
				}
			
            // connect to the database
            $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
            
            // run the update
            $result = queryDB($query, $db);            
            
            // now that we are done, send user back to EmployeeInput.php and exit 
            header("Location: EmployeeInput.php?successmessage=Successfully updated employee $EmployeeName");
            exit;
        }        
    } else {
        //
        // if the form was not submitted (first time in)
        //
    
        /*
         * Check if a GET variable was passed with the ID for the employee
         *
         */
        if(!isset($_GET['EmployeeID'])) {
            // if the ID was not passed through the url
            
            // send them out to EmployeeInput.php and stop executing code in this page
            header('Location: EmployeeInput.php');
            exit;
        }
        
        /*
         * Now we'll check to make sure the ID passed through the GET variable matches the ID of an employee in the database
         */
        
        // connect to the database
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
        
        // set up a query
        $EmployeeID = $_GET['EmployeeID'];
        $query = "SELECT * FROM Employee WHERE EmployeeID=$EmployeeID;";
        
        // run the query
        $result = queryDB($query, $db);
        
        // if the id is not in the employee table, then we need to send the user back to EmployeeInput.php
        if (nTuples($result) == 0) {
            // send them out to EmployeeInput.php and stop executing code in this page
            header('Location: EmployeeInput.php');
            exit;
        }
        
        /*
         * Now we know we got a valid employee ID through the GET variable
         */
        
        // get data on employee to fill out form with existing values
        $row = nextTuple($result);
        
        $EmployeeID = $row['EmployeeID'];
        $EmployeeName = $row['EmployeeName'];
        $EmployeeAdmin = $row['EmployeeAdmin'];
        $EmployeeEmail = $row['EmployeeEmail'];

        }
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
        
        <title>Update employee <?php echo $EmployeeName; ?></title>
    </head>
    <body>
    
<?php

//Set current page to echo class=active in navbar

$page = 'EmployeeInput';
include_once('GrocerNav.php');

?>

    <div class = "container" style = "margin-top:50px">
       
<!-- Title -->
<div class="row">
    <div class="col-xs-12">
        <h1>Update employee <?php echo $EmployeeName; ?></h1>        
    </div>
</div>


<!-- Showing errors, if any -->
<div class="row">
    <div class="col-xs-12">
<?php
    if (isset($isComplete) && !$isComplete) {
        // executes only if form was previously submitted (and therefore $isComplete is set) and isComplete was set to false
        // you'll never be here if the form wasn't submitted (the first time you get in)
        
        echo '<div class="alert alert-danger" role="alert">';
        echo ($errorMessage);
        echo '</div>';
    }
?>            
    </div>
</div>



<!-- form to update Employee -->
<div class="row">
    <div class="col-xs-12">
        
<form action="UpdateEmployee.php" method="post">

<!-- Employee Name -->
<div class="form-group">
    <label for="EmployeeName">Employee Name:</label>
    <input type="text" class="form-control" name="EmployeeName" value="<?php if($EmployeeName) { echo $EmployeeName; } ?>"/>
</div>

<!-- Employee Email -->
<div class="form-group">
    <label for="EmployeeEmail">Employee Email:</label>
    <input type="text" class="form-control" name="EmployeeEmail" value="<?php if($EmployeeEmail) { echo $EmployeeEmail; } ?>"/>
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
	
<!-- Employee Admin -->
<div class="form-group">
    <label for="EmployeeAdmin">EmployeeAdmin:</label>
    <label class="radio-inline">
        <input type="radio" name="EmployeeAdmin" value="1" <?php if($EmployeeAdmin || !isset($EmployeeAdmin)) { echo 'checked'; } ?>> Yes
    </label>    
    <label class="radio-inline">
        <input type="radio" name="EmployeeAdmin" value="0" <?php if(!$EmployeeAdmin && isset($EmployeeAdmin)) { echo 'checked'; } ?>> No
    </label>    
</div>

<!-- hidden ID (not visible to user, but need to be part of form submission so we know which product we are updating -->
<input type="hidden" name="EmployeeID" value="<?php echo $EmployeeID; ?>"/>

<button type="submit" class="btn btn-default" name="submit">Save</button>

</form>
        
        
    </div>
</div>
        </div>
       
       
        
    </body>
</html>