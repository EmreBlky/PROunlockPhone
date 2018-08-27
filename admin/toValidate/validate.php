<?php
if(!isset($_POST['trx'])) {
    header("Location: https://www.prounlockphone.com/main/");
    exit();
}

define('INCLUDE_CHECK', true);
require '../../common.php';
require '../../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../../login");
    exit();
}

$data = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT validated FROM statement WHERE paypal = '{$_POST['trx']}'"));
mysqli_query($DB->Link, "UPDATE statement SET validated = " . ($data['validated'] == 0 ? 1 : 0) . " WHERE paypal = '{$_POST['trx']}'");
header("Location: ./");
?>