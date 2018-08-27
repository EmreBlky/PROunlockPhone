<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT * FROM services WHERE id = '" . $_GET['service'] . "'"));
$json = array(
    "service_status" => $row['service_status'],
    "service_name" => $row['service_name'],
    "short_name" => $row['short_name'],
    "service_group" => $row['service_group'],
    "delivery_time" => $row['delivery_time'],
    "description" => $row['description'],
    "details" => $row['details'],
    "imei" => $row['imei'],
    "sn" => $row['sn'],
    "bulk" => $row['bulk'],
    "phone" => $row['phone'],
    "account" => $row['account'],
    "udid" => $row['udid'],
    "status_mode" => $row['status_mode'],
    "photo" => $row['photo'],
    "itools" => $row['itools'],
    "backupData" => $row['backupData'],
    "reseller_USD" => $row['reseller_USD'],
    "regular_USD" => $row['regular_USD'],
    "reseller_EUR" => $row['reseller_EUR'],
    "regular_EUR" => $row['regular_EUR'],
    "reseller_GBP" => $row['reseller_GBP'],
    "regular_GBP" => $row['regular_GBP'],
    "reseller_TND" => $row['reseller_TND'],
    "regular_TND" => $row['regular_TND'],
    "provider" => $row['provider'],
    "provider_details" => $row['provider_details'],
    "country" => $row['country'],
    "manufacturer" => $row['manufacturer'],
    "carrier" => $row['carrier'],
    "models" => $row['models'],
    "clean" => $row['clean'],
    "barred" => $row['barred'],
    "blacklisted" => $row['blacklisted'],
    "originalPrice" => $row['originalPrice'],
    "videoLink" => $row['videoLink'],
    "fileLink" => $row['fileLink'],
    "success_rate" => $row['success_rate']
);
echo json_encode($json);
?>