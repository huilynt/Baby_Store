<?php
session_start();

$MainContent = "<div>";

$MainContent .= "<div class='row'>";
$MainContent .= "<div class='col-12'>";
$MainContent .= "<span class='page-title'>Product Categories</span>";
$MainContent .= "<p>Select a category listed below:</p>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<hr />";

include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

$qry = "select * from category";
$result = $conn->query($qry);

while ($row = $conn->fetch_array($result)) {
    $MainContent .= "<div class='row'>";

    $img = "./Images/category/$row[CatImage]";
    $MainContent .= "<div class='col-2'>";
    $MainContent .= "<img src=$img />";
    $MainContent .= "</div>";

    $catname = urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    $MainContent .= "<div class='col-10'>";
    $MainContent .= "<p><a href=$catproduct>$row[CatName]</a></p>";
    $MainContent .= "$row[CatDesc]";
    $MainContent .= "</div>";

    $MainContent .= "</div>";

    $MainContent .= "<hr />";
}

$conn->close();
$MainContent .= "</div>";
include("MasterTemplate.php");
