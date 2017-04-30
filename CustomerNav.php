<!-- Customer Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <h4 class="navbar-text"><?php if (!$CustomerID) {echo 'Guest';} else {echo $CustomerEmail;} ?></h4>
        </div>
        
        <ul class="nav navbar-nav">
            <li <?php if ($page === 'CustomerHome') {echo 'class="active"';} ?>><a href="CustomerHome.php">Home</a></li>
            <li <?php if ($page === 'Product') {echo 'class="active"';} ?>><a href="Product.php">Products</a></li>
            <li <?php if ($page === 'ShoppingCart') {echo 'class="active"';} ?>><a href="ShoppingCart.php">ShoppingCart</a></li>
            <li <?php if ($page === 'Checkout') {echo 'class="active"';} ?>><a href="Checkout.php">Check Out</a></li>
            
        </ul>
        
        <form class ="navbar-form navbar-left" action = "Search.php" method ="post">
            <div class"form-group">
                <input type="text" class="form-control" placeholder="Search Products" name="ProductSearch">
                <button type="submit" class = "btn btn-default" name="Search">Search</button>  
            </div>
        </form>
        
        <ul class="nav navbar-nav navbar-right">
            <?php
            
            if (!isset($_SESSION['CustomerEmail'])) {
                echo "<li " . (($page == 'CustomerLogin')?'class="active"':"") . "><a href='CustomerLogin.php'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>";
                echo "<li " . (($page == 'CustomerInput')?'class="active"':"") . "><a href='CustomerInput.php'><span class='glyphicon glyphicon-user'></span> Sign Up</a></li>";
            }
            
            else {
                echo "<li><a href='CustomerLogout.php'><span class='glyphicon glyphicon-log-out'></span> Logout</a></li>";
            }
            
            ?>
        </ul>
    </div>
</nav>