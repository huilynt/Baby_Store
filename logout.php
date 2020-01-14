<?php
session_start();
unset($_SESSION["ShopperName"]);
unset($_SESSION["ShopperID"]);
session_destroy();

header("Location:index.php");
exit;
