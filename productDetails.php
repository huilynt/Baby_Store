<?php
session_start();
$MainContent = "<div>";


include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

$pid = $_GET["pid"];
$qry = "SELECT * from product where ProductID=$pid";
$result = $conn->query($qry);

while ($row = $conn->fetch_array($result)) {
    $MainContent .= "<div class='row'>";
    $MainContent .= "<div class='col-sm-12'>";
    $MainContent .= "<span class='page-title'>$row[ProductTitle]</span>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";

    $MainContent .= "<div class='row'>";

    $MainContent .= "<div class='col-sm-9'>";
    $MainContent .= "<p>$row[ProductDesc]</p>";

    $qry = "SELECT s.SpecName, ps.SpecVal from productspec ps
            INNER JOIN specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID=$pid
            ORDER BY ps.priority";
    $result2 = $conn->query($qry);
    while ($row2 = $conn->fetch_array($result2)) {
        $MainContent .= $row2["SpecName"] . ": " . $row2["SpecVal"] . "<br />";
    }
    $MainContent .= "</div>";

    $img = "./Images/products/$row[ProductImage]";
    $MainContent .= "<div class='col-sm-3'>";
    $MainContent .= "<p><img src=$img /></p>";

    $formattedPrice = number_format($row["Price"], 2);
    $MainContent .= "Price: <span>S$$formattedPrice</span>";
}

$MainContent .= "<form action='cart-functions.php' method='post'>";
$MainContent .= "<input type='hidden' name='actionA' value='add' />";
$MainContent .= "<input type='hidden' name='product_id' value='$pid' />";
$MainContent .= "Quantity: <input type='number' name='quantity' value='1' min='1' max='10' required />";
$MainContent .= "<button type='submit'>Add to Cart</button>";
$MainContent .= "</form>";
$MainContent .= "</div>";
$MainContent .= "</div>";

$conn->close();
$MainContent .= "</div>";
include("MasterTemplate.php");
