<?php
session_start();
$MainContent = "";

$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];

include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

$qry = "INSERT INTO Shopper (name, address, country, phone, email, password) VALUES ('$name', '$address', '$country', '$phone', '$email', '$password')";
$result = $conn->query($qry);

if ($result == true) {
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry);
    while ($row = $conn->fetch_array($result)) {
        $_SESSION["ShopperID"] = $row["ShopperID"];
    }
    $MainContent .= "Registration successful!<br/>";
    $MainContent .= "Your ShopperID is $_SESSION[ShopperID]<br/>";
    $_SESSION["ShopperName"] = $name;
} else {
    $MainContent .= "<h3>Error in inserting record</h3>";
}

$conn->close();
include("MasterTemplate.php");
