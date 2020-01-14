<?php
session_start();

include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

$qry = "SELECT * FROM product WHERE Offered = 1";
$result = $conn->query($qry);

$MainContent = "<div class='row'><div class='col-sm-12 page-title'>Welcome!</div></div>";
$MainContent .= "<hr />";

$MainContent .= "<div class='row'>";
$MainContent .= "<div class='col-sm-12 sub-title'>Offers:</div>";
$MainContent .= "</div>";
if (mysqli_num_rows($result) > 0) {
    while ($row = $conn->fetch_array($result)) {
        $Price = number_format($row["Price"], 2);
        $OfferedPrice = number_format($row["OfferedPrice"], 2);
        $MainContent .= "<div class='row'>";

        $MainContent .= "<div class='col-sm-6'>";
        $MainContent .= "$row[ProductTitle]";
        $MainContent .= "</div>";

        $MainContent .= "<div class='col-sm-6'>";
        $MainContent .= "S$<span class='price'>$Price</span> <span class='offered-price'>$OfferedPrice</span>";
        $MainContent .= "</div>";

        $MainContent .= "</div>";
    }
}
$MainContent .= "</div>";
$conn->close();

include("MasterTemplate.php");
