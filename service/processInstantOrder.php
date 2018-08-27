<?php
if(!isset($_POST['service'])) {
    header("Location: https://www.prounlockphone.com/login/");
    exit();
}
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

$sickw = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT service_name, provider_details FROM services WHERE regular_USD = 0 AND id = {$_POST['service']}"));
if(!$sickw['provider_details']) {
    echo "Failed";
    exit();
}

$url = "http://sickw.com/api.php?key=L6W-74T-TCD-9CU-N9K-O3T-TVF-XOB&service={$sickw['provider_details']}&imei=" . strtoupper($_POST['serial']);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, '300');
$response = curl_exec($ch);
$header  = curl_getinfo($ch);
curl_close($ch);

require '/home/khoubeib/public_html/eMail.php';
if($header["http_code"] != "301" && $header["http_code"] != "200") {
    Notify_me("Issue auto-processing a free check with API", "Error HTTP {$header["http_code"]} while processing a free order<br/>IMEI/SN: {$_POST['serial']}<br />Service: {$sickw['service_name']} ({$_POST['service']}) [{$sickw['provider_details']} at sickw]");
    echo "Failed";
} elseif(strstr($response, "IMEI or SN is incorrect!") !== False) {
    Notify_me("Issue auto-processing a free check with API", "Error \"{$response}\" received while processing a free order<br/>IMEI/SN: {$_POST['serial']}<br />Service: {$sickw['service_name']} ({$_POST['service']}) [{$sickw['provider_details']} at sickw]");
    echo "Failed";
} elseif(strstr($response, "Low Balance!") !== False) {
    Notify_me("Issue auto-processing a free check with API", "Error \"{$response}\" received while processing a free order<br/>IMEI/SN: {$_POST['serial']}<br />Service: {$sickw['service_name']} ({$_POST['service']}) [{$sickw['provider_details']} at sickw]");
    echo "Failed";
} elseif(strstr($response, "Wrong API KEY!") !== False || strstr($response, "API KEY is Wrong!") !== False) {
    Notify_me("Issue auto-processing a free check with API", "Error \"{$response}\" received while processing a free order<br/>IMEI/SN: {$_POST['serial']}<br />Service: {$sickw['service_name']} ({$_POST['service']}) [{$sickw['provider_details']} at sickw]");
    echo "Failed";
} elseif(strstr($response, "Service Down!") !== False) {
    mysqli_query($DB->Link, "UPDATE services SET service_status = 0 WHERE id = " . $_POST['service']);
    Notify_me("Issue auto-processing a free check with API", "Error '{$response}' received while processing a free order.<br/><br/>This service was updated 'TURNED DOWN' automatically.<br/>Please check whether Sickw is now back stable on it or not.<br/>If yes simply click the link below to re-list it.<br /><br />Service: {$sickw['service_name']} - {$_POST['service']} - [{$sickw['provider_details']} at sickw]<br />IMEI / S/N: {$_POST['serial']}<br /><br /><a href='https://www.prounlockphone.com/admin/resetServiceON.php?id={$_POST['service']}' target='_blank'>&rarr; Click here to reset the service ON &larr;</a>");
    echo "Failed";
} elseif(strstr($response, "Error S02: Service ID is Wrong!") !== False) {
    mysqli_query($DB->Link, "UPDATE services SET service_status = 0 WHERE id = " . $_POST['service']);
    Notify_me("Issue auto-processing a free check with API", "Error '{$response}' received while processing a free order.<br/><br/>This service was updated 'TURNED DOWN' automatically.<br/>Please check whether Sickw has removed this service or not.<br/>If you want to set it back active, simply click the link below to re-list it.<br /><br />Service: {$sickw['service_name']} - {$_POST['service']} - [{$sickw['provider_details']} at sickw]<br />IMEI / S/N: {$_POST['serial']}<br /><br /><a href='https://www.prounlockphone.com/admin/resetServiceON.php?id={$_POST['service']}' target='_blank'>&rarr; Click here to reset the service ON &larr;</a>");
    echo "Failed";
} else {
    echo $response;
}
?>