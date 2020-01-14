<?php
session_start();

$email = $_POST["email"];
$pwd = $_POST["password"];

include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

$qry = "SELECT * FROM Shopper WHERE email = '$email' AND password = '$pwd'";
$result = $conn->query($qry);

if ($result == true) {
	if (mysqli_num_rows($result)) {
		while ($row = $conn->fetch_array($result)) {
			$_SESSION["ShopperID"] = $row["ShopperID"];
			$_SESSION["ShopperName"] = $row["Name"];
		}

		$qry = "SELECT * FROM shopcart WHERE ShopperID=$_SESSION[ShopperID] AND OrderPlaced=0";
		$result = $conn->query($qry);

		if ($result == true) {
			$row = $conn->fetch_array($result);
			$_SESSION["Cart"] = $row["ShopCartID"];
			echo $_SESSION["Cart"];

			$qry = "SELECT * FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
			$result = $conn->query($qry);
			if ($result == true) {
				$count = mysqli_num_rows($result);
				echo $count;
				$_SESSION["NumCartItem"] = $count;
			}
		}

		header("Location: index.php");
		exit;
	} else {
		$MainContent = "<h3>Invalid Login Credentials</h3>";
		$MainContent .= "<div><a href='login.php'>Return to login</a></div>";
	}
} else {
	$MainContent = "<h3>Connection failed</h3>";
}

$conn->close();
include("MasterTemplate.php");
