<?php
// Detect the current session
session_start();
$MainContent = "<div>";

$MainContent .= "<div class='row'>";
$MainContent .= "<div class='col-12'>";
$MainContent .= "<span class='page-title'>$_GET[catName]</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<hr />";

include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

$cid = $_GET["cid"];
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity
        FROM CatProduct cp INNER JOIN product p ON cp.ProductID = p.ProductID
        WHERE cp.CategoryID=$cid";
$result = $conn->query($qry);

while ($row = $conn->fetch_array($result)) {
    $MainContent .= "<div class='row'>";

    $img = "./Images/products/$row[ProductImage]";
    $MainContent .= "<div class='col-2'>";
    $MainContent .= "<img src=$img />";
    $MainContent .= "</div>";

    $product = "productDetails.php?pid=$row[ProductID]";
    $formattedPrice = number_format($row["Price"], 2);
    $MainContent .= "<div class='col-10'>";
    $MainContent .= "<p><a href=$product>$row[ProductTitle]</a></p>";
    $MainContent .= "Price: <span'>S$$formattedPrice</span>";
    $MainContent .= "</div>";

    $MainContent .= "</div>";

    $MainContent .= "<hr />";
}

$conn->close();
$MainContent .= "</div>";
include("MasterTemplate.php");
