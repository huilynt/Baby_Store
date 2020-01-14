<?php
session_start();

$MainContent = "<div>";
$MainContent .= "<form name='frmSearch' method='get' action=''>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-12'>";
$MainContent .= "<span class='page-title'>Product Search</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label for='keywords' 
                  class='col-sm-3 col-form-label'>Product Title:</label>";
$MainContent .= "<div class='col-sm-6'>";
$MainContent .= "<input class='form-control' name='keywords' id='keywords' 
                  type='search' required />";
$MainContent .= "</div>";
$MainContent .= "<div class='col-sm-3'>";
$MainContent .= "<button type='submit'>Search</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</form>";

if (isset($_GET['keywords'])) {
    $SearchText = $_GET["keywords"];

    include("mysql.php");
    $conn = new Mysql_Driver();
    $conn->connect();

    $qry = "SELECT * from product where ProductTitle LIKE '%$SearchText%' OR ProductDesc LIKE '%$SearchText%'";
    $result = $conn->query($qry);

    $MainContent .= "<div class='font-weight-bold'>Search results for $SearchText:</div>";
    $MainContent .= "<hr />";

    if (mysqli_num_rows($result) > 0) {
        while ($row = $conn->fetch_array($result)) {
            $product = "productDetails.php?pid=$row[ProductID]";

            $MainContent .= "<div><a href=$product>$row[ProductTitle]</a> <br /> $row[ProductDesc]</div>";
            $MainContent .= "<hr />";
        }
    } else {
        $MainContent .= "<div>No records found.</div>";
    }
    $conn->close();
}

$MainContent .= "</div>";
include("MasterTemplate.php");
