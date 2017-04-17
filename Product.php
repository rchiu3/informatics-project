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
    text-align: center;
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
<title>Products</title>
<body>

<!-- Customer Navigation Bar -->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Home</a>
        </div>

  <ul class="nav navbar-nav">
  	<li><a href="CustomerHome.php">Home</a></li>
  	<li><a class="active" href="Product.php">Products</a></li>
  	<li><a href="CustomerLogin.php">Login</a></li>
  	<li><a href="ShoppingCart.php">Shopping Cart</a></li>
  	<li><a href="Checkout.php">Check Out</a></li>
  </ul>

   <ul class="nav navbar-nav navbar-right">
      <li><a href="GrocerLogin.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      <li><a href="GrocerInput.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
   </ul>
    </div>
</nav>


    <div class = "container">


<?php
//include config.php and dbutils.php
include_once('config.php');
include_once('dbutils.php');
//connect to database
$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
?>



//<!-- HTML Table -->
<div class = "row">
    <div class = "col-xs-12">
        <table class = "table table-hover">
            <thead>
                <th>Product Category</th>
            </thead>

//<!-- Display  Product data -->

<?php
// SQL query to list products from database
//***** STILL Need to group by Category *****
// And Still unclear how we want this page to be shown.
$query = 'SELECT ProductName FROM Product ORDER BY ProductName;';

$result = queryDB($query, $db);

while($row = nextTuple($result)) {
    echo "\n <tr>";
    echo "<td>" . $row['ProductName'] . "</td>";
    echo "</tr> \n";
    }
?>
        </table>
    </div>
</div>
</div>

    </body>
</html>