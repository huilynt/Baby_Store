<?php
session_start();
if (!isset($_SESSION["ShopperID"])) {
	$_SESSION['flash'] = ['type' => 'error', 'message' => 'Please login to view shopping cart!'];
	header("Location: login.php");
	exit;
}

include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

if (isset($_SESSION["Cart"])) {
	$qry = "SELECT ProductID, Name, Price, Quantity, (Price*Quantity) AS Total 
			FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
	$result = $conn->query($qry);

	if ($conn->num_rows($result) > 0) {
		$MainContent = "<p class='page-title'>Shopping Cart</p>";
		$MainContent .= "<div class='table-responsive'>";
		$MainContent .= "<table class='table table-hover'>";
		$MainContent .= "<thead class='cart-header'>";
		$MainContent .= "<tr>";
		$MainContent .= "<th width='40px'>Product ID</th>";
		$MainContent .= "<th width='250px'>Name</th>";
		$MainContent .= "<th width='90px'>Price (S$)</th>";
		$MainContent .= "<th width='60px'>Quantity</th>";
		$MainContent .= "<th width='120px'>Total (S$)</th>";
		$MainContent .= "<th>&nbsp;</th>";
		$MainContent .= "<th>&nbsp;</th>";
		$MainContent .= "</tr>";
		$MainContent .= "</thead>";

		$_SESSION["Items"] = array();

		$MainContent .= "<tbody>";
		while ($row = $conn->fetch_array($result)) {
			$MainContent .= "<tr>";
			$MainContent .= "<td>$row[ProductID]</td>";
			$MainContent .= "<td>$row[Name]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			$MainContent .= "<td>$formattedPrice</td>";
			$MainContent .= "<form action='cart-functions.php' method='post'>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='number' name='quantity'value='$row[Quantity]' min='1' max='10' required />";
			$MainContent .= "</td> ";
			$formattedTotal = number_format($row["Total"], 2);
			$MainContent .= "<td>$formattedTotal</td>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='hidden' name='actionU' value='update'>";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
			$MainContent .= "<button type='submit'>Update</button>";
			$MainContent .= "</td>";
			$MainContent .= "</form>";
			$MainContent .= "<form action='cart-functions.php' method='post'>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='hidden' name='actionR' value='remove'>";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
			$MainContent .= "<button type='submit'>Remove</button>";
			$MainContent .= "</td>";
			$MainContent .= "</form> ";
			$MainContent .= "</tr> ";
		}
		$MainContent .= "</tbody>";
		$MainContent .= "</table>";
		$MainContent .= "</div>";

		$_SESSION["Items"][] = array(
			"productId" => $row["ProductID"],
			"name" => $row["Name"],
			"price" => $row["Price"],
			"quantity" => $row["Quantity"]
		);



		$qry = "SELECT SUM(Quantity) as TotalQuantity FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
		$result = $conn->query($qry);
		$row = $conn->fetch_array($result);
		$MainContent .= "<p> Total Quantity = S$" . number_format($row["TotalQuantity"], 0);

		$MainContent .= "<br />";

		$qry = "SELECT SUM(Price*Quantity) as SubTotal FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
		$result = $conn->query($qry);
		$row = $conn->fetch_array($result);
		$MainContent .= "Subtotal = S$" . number_format($row["SubTotal"], 2);
		$_SESSION["SubTotal"] = round($row["SubTotal"], 2);

		$MainContent .= "<br />";
		$normal = "S$" . number_format(5, 2);
		$express = "S$" . number_format(10, 2);
		if ($_SESSION["SubTotal"] > 200) {
			$express = "S$" . number_format(0, 2);
		}

		$MainContent .= "<form method='post'>";
		$MainContent .= "Please select a delivery mode:<br />";
		$MainContent .= "<input type='radio' name='delivery'> Normal Delivery ($normal)</input><br />";
		$MainContent .= "<input type='radio' name='delivery'> Express Delivery ($express)</input>";
		$MainContent .= "</form>";

		$MainContent .= "<br />";

		$MainContent .= "<form method='post' action='process.php'>";
		$MainContent .= "<input type='image' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		$MainContent .= "</form></p>";
	} else {
		$MainContent = "<span>Empty shopping cart!</span>";
	}
} else {
	$MainContent = "<span>Empty shopping cart!</span>";
}

$conn->close();
include("MasterTemplate.php");
