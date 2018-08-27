<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

echo mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE imei = '" . $_GET['imei'] . "' AND service = '" . $_GET['service'] . "'"));
?>