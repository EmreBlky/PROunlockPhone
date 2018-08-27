<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT icloud_clean_service, bad_supported FROM services WHERE id = {$_GET['service']}"));
require 'getiCloudCleanDetailsDeep.php';

$json = array(
    "iCloudClean" => $row['icloud_clean_service'] == 1,
    "bad_supported" => $row['bad_supported'] == 1,
    "supported" => showSupported($DB, $_GET['service'])
);
echo json_encode($json);
?>