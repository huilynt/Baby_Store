<?php
session_start();

// Check if user logged in 
if (!isset($_SESSION["ShopperID"])) {
	// redirect to login page if the session variable shopperid is not set

	header("Location: login.php");
	exit;
}

include("mysql.php");
$conn = new Mysql_Driver();

if (isset($_POST['actionA'])) {
	// TO DO 1
	// Write code to implement: if a user clicks on "Add to Cart" button, insert/update the 
	// database and also the session variable for counting number of items in shopping cart.
	$conn->connect();

	// Check if a shopping cart exist, if not create a new shopping cart
	if (!isset($_SESSION["Cart"])) {
		// Create a shopping cart for the shopper
		$qry = "INSERT INTO shopcart(ShopperID) VALUES($_SESSION[ShopperID])";
		$conn->query($qry);
		$qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
		$result = $conn->query($qry);
		$row = $conn->fetch_array($result);
		$_SESSION["Cart"] = $row["ShopCartID"];
	}
	// If the ProductID exists in the shopping cart, 
	// update the quantity, else add the item to the Shopping Cart.

	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	$qry = "SELECT * from shopcartitem WHERE ShopCartID=$_SESSION[Cart] AND ProductID=$pid";
	$result = $conn->query($qry);
	$addNewItem = 0;
	if ($conn->num_rows($result) > 0) {
		$qry = "UPDATE shopcartitem SET Quantity = Quantity + $quantity WHERE ShopCartID=$_SESSION[Cart] AND ProductID=$pid";
		$conn->query($qry);
	} else {
		$qry = "SELECT * FROM product WHERE ProductID=$pid";
		$result = $conn->query($qry);
		if ($conn->num_rows($result) > 0) {
			$row = $conn->fetch_array($result);
			$productname = $row["ProductTitle"];
			$price = $row["Price"];
			$qry = "INSERT INTO shopcartitem(ShopCartID, ProductID, Price, Name, Quantity) VALUES ($_SESSION[Cart], $pid, $price, '$productname', $quantity)";
			$conn->query($qry);
			$addNewItem = 1;
		}
	}
	$conn->close();

	// Update session variable used for counting number of items in the shopping cart.
	if (isset($_SESSION["NumCartItem"])) {
		$_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] + $addNewItem;
	} else {
		$_SESSION["NumCartItem"] = 1;
	}

	// Redirect shopper to shopping cart page
	header("Location: shoppingcart.php");
	exit;
}

if (isset($_POST['actionU'])) {
	// TO DO 2
	// Write code to implement: if a user clicks on "Update" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	$cartid = $_SESSION["Cart"];
	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	$conn->connect();
	$qry = "UPDATE shopcartitem SET Quantity = $quantity
   WHERE ProductID = $pid and ShopCartID = $cartid";
	$conn->query($qry);
	$conn->close();
	header("Location: shoppingcart.php");
	exit;
}

if (isset($_POST['actionR'])) {
	// TO DO 3
	// Write code to implement: if a user clicks on "Remove" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	$cartid = $_SESSION["Cart"];
	$pid = $_POST["product_id"];
	$conn->connect();
	$qry = "DELETE FROM shopcartitem
   WHERE ProductID = $pid and ShopCartID = $cartid";
	$conn->query($qry);
	$conn->close();
	$_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] - 1;
	header("Location: shoppingcart.php");
	exit;
}
