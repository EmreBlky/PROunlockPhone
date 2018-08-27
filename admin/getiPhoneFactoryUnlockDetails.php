<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$rows = mysqli_query($DB->Link, "SELECT DISTINCT country, english_name FROM iPhoneFactoryUnlock, countries WHERE country_code = country AND service = " . $_GET['service'] . " ORDER BY english_name");
$countryList = "";
$countries = array();
while($row = mysqli_fetch_array($rows)) {
    if($countryList == "") $countryList = $row['english_name'];
    else $countryList .= "  - " . $row['english_name'];
    $countries[] = $row['country'];
}

$rows = mysqli_query($DB->Link, "SELECT DISTINCT iPhoneFactoryUnlock.carrier 'id', carriers.carrier 'carrier' FROM iPhoneFactoryUnlock, carriers WHERE iPhoneFactoryUnlock.carrier = carriers.id AND service = " . $_GET['service'] . " ORDER BY carrier");
$carrierList = "";
$carriers = array();
while($row = mysqli_fetch_array($rows)) {
    if($carrierList == "") $carrierList = $row['carrier'];
    else $carrierList .= "  - " . $row['carrier'];
    $carriers[] = $row['id'];
}

$rows = mysqli_query($DB->Link, "SELECT DISTINCT iPhoneFactoryUnlock.model 'id', models.model 'model' FROM iPhoneFactoryUnlock, models WHERE iPhoneFactoryUnlock.model = models.id AND service = " . $_GET['service'] . " ORDER BY models.id");
$modelList = "";
$models = array();
while($row = mysqli_fetch_array($rows)) {
    if($modelList == "") $modelList = $row['model'];
    else $modelList .= "  - " . $row['model'];
    $models[] = $row['id'];
}

$rows = mysqli_query($DB->Link, "SELECT DISTINCT iPhoneFactoryUnlock.status 'id', ESNstatus.status 'status' FROM iPhoneFactoryUnlock, ESNstatus WHERE iPhoneFactoryUnlock.status = ESNstatus.id AND service = " . $_GET['service'] . " ORDER BY ESNstatus.id");
$statusList = "";
$status = array();
while($row = mysqli_fetch_array($rows)) {
    if($statusList == "") $statusList = $row['status'];
    else $statusList .= "  - " . $row['status'];
    $status[] = $row['id'];
}

$json = array(
    "iPhoneFactoryUnlockService" => mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM iPhoneFactoryUnlock WHERE service = " . $_GET['service'])) > 0,
    "countryList" => $countryList,
    "countries" => $countries,
    "carrierList" => $carrierList,
    "carriers" => $carriers,
    "modelList" => $modelList,
    "models" => $models,
    "statusList" => $statusList,
    "status" => $status
);
echo json_encode($json);
?>