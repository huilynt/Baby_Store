<?php
session_start();




$MainContent = "<div>";
$MainContent .= "<form action='checkLogin.php' method='post'>";

$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-12'>";
$MainContent .= "<span class='page-title'>Member Login</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='email'>Email Address:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' type='email' name='email' id='email' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='password'>Password:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' type='password' name='password' id='password' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<button type='submit'>Login</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";

if (isset($_SESSION['flash'])) {
    $error = $_SESSION['flash']['message'];
    $MainContent .= "<div class='form-group row'>";
    $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
    $MainContent .= "<div class='alert alert-danger'>$error</div>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
    unset($_SESSION['flash']);
}


$MainContent .= "</form>";
$MainContent .= "</div>";

include("MasterTemplate.php");
