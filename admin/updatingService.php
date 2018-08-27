<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin" or !isset($_POST['id'])) {
    header("Location: ../login");
    exit();
}

try {
    if(isset($_POST['service_name']) and $_POST['service_name'] == "") {
        echo "FAILURE<br/>Service name cannot be empty!";
        exit();
    }
    if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM services WHERE service_name = '" . mysqli_real_escape_string($DB->Link, $_POST['service_name']) . "' AND id <> " . $_POST['id'])) > 0) {
        echo "FAILURE<br/>Duplicated service name!";
        exit();
    }
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT service_name FROM services WHERE id = " . $_POST['id']));
    if($row['service_name'] != mysqli_real_escape_string($DB->Link, $_POST['service_name'])) {
        $old_name = str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $row['service_name'])));
        $new_name = str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", mysqli_real_escape_string($DB->Link, $_POST['service_name']))));
        if(file_exists("../service/" . $old_name)) {
            rename("../service/" . $old_name, "../service/" . $new_name);
        } else {
            if (!file_exists("../service/" . $new_name)) {
                mkdir("../service/" . $new_name);
                copy("../service/service-index.php", "../service/{$new_name}/index.php");
            }
        }
    }

    $request = '
UPDATE services SET
    service_status = "' . (isset($_POST['service_status']) ? $_POST['service_status'] : "") . '",
    service_name = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['service_name']) ? $_POST['service_name'] : "")) . '",
    short_name = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['short_name']) ? $_POST['short_name'] : "")) . '",
    service_group = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['service_group']) ? $_POST['service_group'] : "")) . '",
    delivery_time = "' . (isset($_POST['delivery_time']) ? $_POST['delivery_time'] : "") . '",
    description = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['description']) ? $_POST['description'] : "")) . '",
    details = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['details']) ? $_POST['details'] : "")) . '",
    imei = "' . ((isset($_POST['imei']) and $_POST['imei'] == "on") ? "1" : "0") . '",
    sn = "' . ((isset($_POST['sn']) and $_POST['sn'] == "on") ? "1" : "0") . '",
    bulk = "' . ((isset($_POST['bulk']) and $_POST['bulk'] == "on") ? "1" : "0") . '",
    phone = "' . ((isset($_POST['phone']) and $_POST['phone'] == "on") ? "1" : "0") . '",
    account = "' . ((isset($_POST['account']) and $_POST['account'] == "on") ? "1" : "0") . '",
    udid = "' . ((isset($_POST['udid']) and $_POST['udid'] == "on") ? "1" : "0") . '",
    status_mode = "' . ((isset($_POST['status_mode']) and $_POST['status_mode'] == "on") ? "1" : "0") . '",
    photo = "' . ((isset($_POST['photo']) and $_POST['photo'] == "on") ? "1" : "0") . '",
    itools = "' . ((isset($_POST['itools']) and $_POST['itools'] == "on") ? "1" : "0") . '",
    backupData = "' . ((isset($_POST['backupData']) and $_POST['backupData'] == "on") ? "1" : "0") . '",
    videoLink = "' . ((isset($_POST['videoLink']) and $_POST['videoLink'] == "on") ? "1" : "0") . '",
    fileLink = "' . ((isset($_POST['fileLink']) and $_POST['fileLink'] == "on") ? "1" : "0") . '",
    regular_TND = "' . (isset($_POST['regular_TND']) ? $_POST['regular_TND'] : "") . '",
    regular_USD = "' . (isset($_POST['regular_USD']) ? $_POST['regular_USD'] : "") . '",
    regular_EUR = "' . (isset($_POST['regular_EUR']) ? $_POST['regular_EUR'] : "") . '",
    regular_GBP = "' . (isset($_POST['regular_GBP']) ? $_POST['regular_GBP'] : "") . '",
    reseller_TND = "' . (isset($_POST['reseller_TND']) ? $_POST['reseller_TND'] : "") . '",
    reseller_USD = "' . (isset($_POST['reseller_USD']) ? $_POST['reseller_USD'] : "") . '",
    reseller_EUR = "' . (isset($_POST['reseller_EUR']) ? $_POST['reseller_EUR'] : "") . '",
    reseller_GBP = "' . (isset($_POST['reseller_GBP']) ? $_POST['reseller_GBP'] : "") . '",
    provider = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['provider']) ? $_POST['provider'] : "")) . '",
    provider_details = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['provider_details']) ? $_POST['provider_details'] : "")) . '",
    country = "' . (isset($_POST['country']) ? $_POST['country'] : "") . '",
    manufacturer = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['manufacturer']) ? $_POST['manufacturer'] : "")) . '",
    carrier = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['carrier']) ? $_POST['carrier'] : "")) . '",
    models = "' . mysqli_real_escape_string($DB->Link, (isset($_POST['models']) ? $_POST['models'] : "")) . '",
    clean = "' . ((isset($_POST['clean']) and $_POST['clean'] == "on") ? "1" : "0") . '",
    barred = "' . ((isset($_POST['barred']) and $_POST['barred'] == "on") ? "1" : "0") . '",
    blacklisted = "' . ((isset($_POST['blacklisted']) and $_POST['blacklisted'] == "on") ? "1" : "0") . '",
    originalPrice = "' . (isset($_POST['originalPrice']) ? $_POST['originalPrice'] : "") . '",
    success_rate = ' . (isset($_POST['success_rate']) ? $_POST['success_rate'] : "") . '
WHERE id = "' . $_POST['id'] . '"';
    mysqli_query($DB->Link, $request);
    $request = 'DELETE FROM price_client_service WHERE service = ' . $_POST['id'] . ' AND price <= ' . $_POST['reseller_USD'] . ' AND client IN (SELECT id FROM users WHERE currency = "USD")';
    mysqli_query($DB->Link, $request);
    $request = 'DELETE FROM price_client_service WHERE service = ' . $_POST['id'] . ' AND price <= ' . $_POST['reseller_EUR'] . ' AND client IN (SELECT id FROM users WHERE currency = "EUR")';
    mysqli_query($DB->Link, $request);
    $request = 'DELETE FROM price_client_service WHERE service = ' . $_POST['id'] . ' AND price <= ' . $_POST['reseller_GBP'] . ' AND client IN (SELECT id FROM users WHERE currency = "GBP")';
    mysqli_query($DB->Link, $request);
    $request = 'DELETE FROM price_client_service WHERE service = ' . $_POST['id'] . ' AND price <= ' . $_POST['reseller_TND'] . ' AND client IN (SELECT id FROM users WHERE currency = "TND")';
    mysqli_query($DB->Link, $request);
} catch (Exception $e) {
    echo "FAILURE" . $e->getMessage(); 
}
?>