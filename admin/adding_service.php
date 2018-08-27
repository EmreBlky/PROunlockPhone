<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if(!isset($_SESSION['client_type']) or $_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

try {
    if(!isset($_POST['service_name']) or $_POST['service_name'] == "") {
        echo "FAILURE<br/>Service name cannot be empty!";
        exit();
    }
    if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM services WHERE service_name = '" . mysqli_real_escape_string($DB->Link, $_POST['service_name']) . "'")) > 0) {
        echo "FAILURE<br/>Duplicated service name!";
        exit();
    }
    $request = '
INSERT INTO services (
    service_name,
    short_name,
    service_group,
    delivery_time,
    description,
    details,
    service_status,
    imei,
    sn,
    bulk,
    phone,
    account,
    udid,
    status_mode,
    photo,
    itools,
    regular_TND,
    regular_USD,
    regular_EUR,
    regular_GBP,
    reseller_TND,
    reseller_USD,
    reseller_EUR,
    reseller_GBP,
    provider,
    provider_details,
    country,
    manufacturer,
    carrier,
    models,
    clean,
    barred,
    blacklisted,
    originalPrice,
    backupData,
    videoLink,
    fileLink
) VALUES (
    "' . $_POST['service_name'] . '",
    "' . (isset($_POST['short_name']) ? $_POST['short_name'] : "") . '",
    "' . (isset($_POST['service_group']) ? $_POST['service_group'] : "") . '",
    "' . (isset($_POST['delivery_time']) ? $_POST['delivery_time'] : "") . '",
    "' . mysqli_real_escape_string($DB->Link, (isset($_POST['description']) ? $_POST['description'] : "")) . '",
    "' . mysqli_real_escape_string($DB->Link, (isset($_POST['details']) ? $_POST['details'] : "")) . '",
    "1",
    "' . (isset($_POST['imei']) ? ($_POST['imei'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['sn']) ? ($_POST['sn'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['bulk']) ? ($_POST['bulk'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['phone']) ? ($_POST['phone'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['account']) ? ($_POST['account'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['udid']) ? ($_POST['udid'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['status_mode']) ? ($_POST['status_mode'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['photo']) ? ($_POST['photo'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['itools']) ? ($_POST['itools'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['regular_TND']) ? $_POST['regular_TND'] : "") . '",
    "' . (isset($_POST['regular_USD']) ? $_POST['regular_USD'] : "") . '",
    "' . (isset($_POST['regular_EUR']) ? $_POST['regular_EUR'] : "") . '",
    "' . (isset($_POST['regular_GBP']) ? $_POST['regular_GBP'] : "") . '",
    "' . (isset($_POST['reseller_TND']) ? $_POST['reseller_TND'] : "") . '",
    "' . (isset($_POST['reseller_USD']) ? $_POST['reseller_USD'] : "") . '",
    "' . (isset($_POST['reseller_EUR']) ? $_POST['reseller_EUR'] : "") . '",
    "' . (isset($_POST['reseller_GBP']) ? $_POST['reseller_GBP'] : "") . '",
    "' . (isset($_POST['provider']) ? $_POST['provider'] : "") . '",
    "' . (isset($_POST['provider_details']) ? $_POST['provider_details'] : "") . '",
    "' . (isset($_POST['country']) ? $_POST['country'] : "") . '",
    "' . (isset($_POST['manufacturer']) ? $_POST['manufacturer'] : "") . '",
    "' . (isset($_POST['carrier']) ? $_POST['carrier'] : "") . '",
    "' . (isset($_POST['models']) ? $_POST['models'] : "") . '",
    "' . (isset($_POST['clean']) ? ($_POST['clean'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['barred']) ? ($_POST['barred'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['blacklisted']) ? ($_POST['blacklisted'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['originalPrice']) ? $_POST['originalPrice'] : "") . '",
    "' . (isset($_POST['backup_link']) ? ($_POST['backup_link'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['video_link']) ? ($_POST['video_link'] == "on" ? "1" : "0") : "0") . '",
    "' . (isset($_POST['file_link']) ? ($_POST['file_link'] == "on" ? "1" : "0") : "0") . '"
)';
    mysqli_query($DB->Link, $request);
    if(mysqli_insert_id($DB->Link) != 0) {
        echo mysqli_insert_id($DB->Link);
        $new_name = str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", mysqli_real_escape_string($DB->Link, $_POST['service_name']))));
        if(!file_exists("../service/" . $new_name)) {
            mkdir("../service/" . $new_name);
            copy("../service/service-index.php", "../service/{$new_name}/index.php");
        }

    } else {
        echo "FAILURE Check your query :: " . $request;
    }
} catch (Exception $e) {
    echo "FAILURE " . $e->getMessage(); 
}
?>