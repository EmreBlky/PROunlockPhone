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
$bargain = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$_SESSION['client_id']}' AND service = '{$_GET['service']}'"));
if($bargain['price']) {
    if($bargain['nature'] == 'impose') {
        if($row["regular_{$_SESSION['currency']}"] > $bargain['price']) {
            $price = "<strike style='color:crimson'>" . $row["regular_{$_SESSION['currency']}"] . "</strike> " . $bargain['price'];
        } else {
            $price = $bargain['price'];
        }
    } else {
        if($_SESSION['client_type'] == "reseller") {
            if($row["reseller{$_SESSION['currency']}"] < $bargain['price']) {
                $bargain['price'] = $row["reseller{$_SESSION['currency']}"];
            }
            if($bargain['price'] < $row["regular_{$_SESSION['currency']}"]) {
                $price = "<strike style='color:crimson'>" . $row["regular_{$_SESSION['currency']}"] . "</strike> " . $bargain['price'];
            } else {
                $price = $bargain['price'];
            }
        } else {
            if($bargain['price'] < $row["regular_{$_SESSION['currency']}"]) {
                $price = "<strike style='color:crimson'>" . $row["regular_{$_SESSION['currency']}"] . "</strike> " . $bargain['price'];
            } else {
                $price = $row["regular_{$_SESSION['currency']}"];
            }
        }
    }
} else {
    if($_SESSION['client_type'] == "reseller") {
        if($row["reseller{$_SESSION['currency']}"] < $row["regular_{$_SESSION['currency']}"]) {
            $price = "<strike style='color:crimson'>" . $row["regular_{$_SESSION['currency']}"] . "</strike> " . $row["reseller{$_SESSION['currency']}"];
        } else {
            $price = $row["reseller{$_SESSION['currency']}"];
        }
    } else {
        $price = $row["regular_{$_SESSION['currency']}"];
    }
}

$json = array(
    "service_name" => $row['service_name'],
    "description" => $row['description'],
    "models" => $row['models'],
    "clean" => $row['clean'],
    "barred" => $row['barred'],
    "blacklisted" => $row['blacklisted'],
    "delivery_time" => $row['delivery_time'],
    "price" => $price . " " . $_SESSION['symbol'],
    "details" => $row['details'],
    "imei" => $row['imei'],
    "sn" => $row['sn'],
    "account" => $row['account'],
    "udid" => $row['udid'],
    "photo" => $row['photo'],
    "phone" => $row['phone'],
    "status_mode" => $row['status_mode'],
    "bulk" => $row['bulk'],
    "itools" => $row['itools']
);
echo json_encode($json);
?>