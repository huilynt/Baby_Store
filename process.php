<?php
session_start();
include("mypaypal.php");
include("mysql.php");
$MainContent = "";
$conn = new Mysql_Driver();

if ($_POST) {
	foreach ($_SESSION['Items'] as $key => $item) {
		$qry = "SELECT Quantity FROM product WHERE ProductID=$item[productId]";
		$result = $conn->query($qry);

		if ($conn->num_rows($result) > 0) {
			echo ($result);
		}
	}

	$paypal_data = '';
	foreach ($_SESSION['Items'] as $key => $item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($item["quantity"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($item["price"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($item["productId"]);
		echo ($paypal_data);
	}

	$_SESSION["Tax"] = round($_SESSION["SubTotal"] * 0.07, 2);

	$_SESSION["ShipCharge"] = 2.00;

	$padata = '&CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
		'&PAYMENTACTION=Sale' .
		'&ALLOWNOTE=1' .
		'&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
		'&PAYMENTREQUEST_0_AMT=' . urlencode($_SESSION["SubTotal"] +
			$_SESSION["Tax"] +
			$_SESSION["ShipCharge"]) .
		'&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($_SESSION["SubTotal"]) .
		'&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($_SESSION["ShipCharge"]) .
		'&PAYMENTREQUEST_0_TAXAMT=' . urlencode($_SESSION["Tax"]) .
		'&BRANDNAME=' . urlencode("Mamaya e-BookStore") .
		$paypal_data .
		'&RETURNURL=' . urlencode($PayPalReturnURL) .
		'&CANCELURL=' . urlencode($PayPalCancelURL);

	//We need to execute the "SetExpressCheckOut" method to obtain paypal token
	$httpParsedResponseAr = PPHttpPost(
		'SetExpressCheckout',
		$padata,
		$PayPalApiUsername,
		$PayPalApiPassword,
		$PayPalApiSignature,
		$PayPalMode
	);

	if (
		"SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) ||
		"SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])
	) {
		if ($PayPalMode == 'sandbox')
			$paypalmode = '.sandbox';
		else
			$paypalmode = '';

		$paypalurl = 'https://www' . $paypalmode .
			'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' .
			$httpParsedResponseAr["TOKEN"] . '';
		header('Location: ' . $paypalurl);
	} else {
		$MainContent .= "<div style='color:red'><b>SetExpressCheckout failed : </b>" .
			urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . "</div>";
		$MainContent .= "<pre>";
		$MainContent .= print_r($httpParsedResponseAr);
		$MainContent .= "</pre>";
	}
}

if (isset($_GET["token"]) && isset($_GET["PayerID"])) {
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	$paypal_data = '';
	foreach ($_SESSION['Items'] as $key => $item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($item["quantity"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($item["price"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($item["productId"]);
	}

	$padata = '&TOKEN=' . urlencode($token) .
		'&PAYERID=' . urlencode($playerid) .
		'&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") .
		$paypal_data .
		'&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($_SESSION["SubTotal"]) .
		'&PAYMENTREQUEST_0_TAXAMT=' . urlencode($_SESSION["Tax"]) .
		'&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($_SESSION["ShipCharge"]) .
		'&PAYMENTREQUEST_0_AMT=' . urlencode($_SESSION["SubTotal"] +
			$_SESSION["Tax"] +
			$_SESSION["ShipCharge"]) .
		'&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode);


	$httpParsedResponseAr = PPHttpPost(
		'DoExpressCheckoutPayment',
		$padata,
		$PayPalApiUsername,
		$PayPalApiPassword,
		$PayPalApiSignature,
		$PayPalMode
	);

	if (
		"SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) ||
		"SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])
	) {

		$conn->connect();

		$qry = "SELECT * from shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
		$result = $conn->query($qry);
		if ($conn->num_rows($result) > 0) {
			while ($row = $conn->fetch_array($result)) {
				$qry = "UPDATE product SET Quantity = Quantity-$row[Quantity] WHERE ProductID=$row[ProductID]";
				$conn->query($qry);
			}
		}

		$total = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
		$qry = "UPDATE shopcart SET Quantity=$_SESSION[NumCartItem], OrderPlaced=1, SubTotal=$_SESSION[SubTotal], ShipCharge=$_SESSION[ShipCharge], Tax=$_SESSION[Tax], Total=$total WHERE ShopCartID=$_SESSION[Cart]";
		$conn->query($qry);

		$transactionID = urlencode(
			$httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]
		);
		$nvpStr = "&TRANSACTIONID=" . $transactionID;
		$httpParsedResponseAr = PPHttpPost(
			'GetTransactionDetails',
			$nvpStr,
			$PayPalApiUsername,
			$PayPalApiPassword,
			$PayPalApiSignature,
			$PayPalMode
		);

		if (
			"SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) ||
			"SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])
		) {
			$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));

			$ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
			if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
				$ShipAddress .= ' ' . urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
			if (isset($httpParsedResponseAr["SHIPTOCITY"]))
				$ShipAddress .= ' ' . urldecode($httpParsedResponseAr["SHIPTOCITY"]);
			if (isset($httpParsedResponseAr["SHIPTOSTATE"]))
				$ShipAddress .= ' ' . urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
			$ShipAddress .= ' ' . urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]) .
				' ' . urldecode($httpParsedResponseAr["SHIPTOZIP"]);

			$ShipCountry = urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
			$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);

			$qry = "INSERT INTO orderdata (ShipName, ShipAddress, ShipCountry, ShipEmail, ShopCartID) VALUES ('$ShipName', '$ShipAddress', '$ShipCountry', '$ShipEmail', $_SESSION[Cart])";
			$conn->query($qry);

			$qry = "SELECT LAST_INSERT_ID() AS OrderID";
			$result = $conn->query($qry);
			$row = $conn->fetch_array($result);
			$_SESSION["OrderID"] = $row["OrderID"];


			$conn->close();

			$_SESSION["NumCartItem"] = 0;
			unset($_SESSION["Cart"]);
			header("Location: orderConfirmed.php");
			exit;
		} else {
			$MainContent .= "<div style='color:red'><b>GetTransactionDetails failed :  </b>" .
				urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '</div>';
			$MainContent .= "<pre>";
			$MainContent .= print_r($httpParsedResponseAr);
			$MainContent .= "</pre>";

			$conn->close();
		}
	} else {
		$MainContent .= "<div style='color:red'><b>DoExpressCheckoutPayment failed :  </b>" .
			urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '</div>';
		$MainContent .= "<pre>";
		$MainContent .= print_r($httpParsedResponseAr);
		$MainContent .= "</pre>";
	}
}

include("MasterTemplate.php");
