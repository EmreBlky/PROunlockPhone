<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin" && !isset($_GET['external'])) {
    header("Location: ../login");
    exit();
}

mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = \"" . $_GET['data'] . "\" WHERE id = " . $_GET['id']);
?>