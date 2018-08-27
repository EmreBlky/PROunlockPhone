<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$quick = "";
if(isset($_GET['quick']) && $_GET['quick'] == "yes") $quick = "quick_";
mysqli_query($DB->Link, "UPDATE " . $quick . "orders SET pintotop = {$_GET['pintotop']} WHERE id = {$_GET['id']}");
?>