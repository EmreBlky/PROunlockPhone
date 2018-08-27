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
    "service_name" => $row['service_name'],
    "description" => $row['description'],
    //"models" => $row['models'],
    "clean" => $row['clean'],
    "barred" => $row['barred'],
    "blacklisted" => $row['blacklisted'],
    "delivery_time" => $row['delivery_time'],
    "price" => "<del style='color:darkred'>\${$row['regular_USD']}</del> \${$row['reseller_USD']}",
    "details" => $row['details'],
    "imei" => $row['imei'],
    "sn" => $row['sn'],
    "account" => $row['account'],
    "udid" => $row['udid'],
    "photo" => $row['photo'],
    "phone" => $row['phone'],
    "status_mode" => $row['status_mode'],
    "bulk" => $row['bulk']
);
echo json_encode($json);
?>