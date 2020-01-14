<?php
// $content1 = "Welcome Guest<br />";
$content1 = "";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='register.php'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";

if (isset($_SESSION["ShopperName"])) {
    // $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
                <a class='nav-link' href='changePassword.php'>Change Password</a></li>
                <li class='nav-item'>
                <a class='nav-link' href='logout.php'>Logout</a></li>";

    if (isset($_SESSION["NumCartItem"])) {
        $content1 .= "($_SESSION[NumCartItem])";
    } else {
        $content1 .= "0";
    }
}
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a href="index.php" class="navbar-brand">ECAD</a>
    <button class="navbar-toggler" type="button" data-toggle='collapse' data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="index.php" class="nav-link">Home</a>
            </li>

            <li class="nav-item">
                <a href="category.php" class="nav-link">Product Categories</a>
            </li>

            <li class="nav-item">
                <a href="search.php" class="nav-link">Product Search</a>
            </li>

            <li class="nav-item">
                <a href="shoppingCart.php" class="nav-link">Shopping Cart <?php echo $content1; ?></a>
            </li>
        </ul>

        <ul class="navbar-nav">
            <?php echo $content2; ?>
        </ul>
    </div>
</nav>