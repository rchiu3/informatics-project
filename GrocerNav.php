<!-- Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            
            <!-- Displays Store name if user is logged in, otherwise displays 'Store Overview' -->
            <h4 class="navbar-text"><?php if (!$StoreName) {echo 'Store Overview';} else {echo $StoreName;} ?></h4>
        </div>
        
        <!-- if $page is where the user is, the tab will appear active -->
        <ul class="nav navbar-nav">
            <li <?php if ($page === 'GrocerHome') {echo 'class="active"';} ?>><a href="GrocerHome.php">Home</a></li>
            <li <?php if ($page === 'ProductInput') {echo 'class="active"';} ?>><a href="ProductInput.php">Product Input</a></li>
            <li <?php if ($page === 'CategoryInput') {echo 'class="active"';} ?>><a href="CategoryInput.php">Category Input</a></li>
            <li <?php if ($page === 'Orders') {echo 'class="active"';} ?>><a href="GrocerOrders.php">Orders</a></li>
            <li <?php if ($page === 'EmployeeInput') {echo 'class="active"';} ?>><a href="EmployeeInput.php">Employees</a></li>
        </ul>
        
        <!-- Right side navbar for Login/Sign-up/Logout -->
        <ul class="nav navbar-nav navbar-right">
            <?php
            
            //If user isn't logged in, give them options to log in or create an account -->
            if (!isset($_SESSION['EmployeeEmail'])) {
                echo "<li " . (($page == 'GrocerLogin')?'class="active"':"") . "><a href='GrocerLogin.php'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>";
                echo "<li " . (($page == 'GrocerInput')?'class="active"':"") . "><a href='GrocerInput.php'><span class='glyphicon glyphicon-user'></span> Sign Up</a></li>";
            }
            
            //Display a link to logout if user is logged in
            else {
                echo "<li><a href='GrocerLogout.php'><span class='glyphicon glyphicon-log-out'></span> Logout</a></li>";
            }
            
            ?>
        </ul>
    </div>
</nav>