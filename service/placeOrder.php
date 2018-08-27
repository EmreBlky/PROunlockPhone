<?php
if(!isset($_POST['service'])) {
    header("Location: https://www.prounlockphone.com/services/");
    exit();
}
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

$_POST['service'] = mysqli_real_escape_string($DB->Link, $_POST['service']);
$_POST['IMEI'] = isset($_POST['IMEI']) ? mysqli_real_escape_string($DB->Link, $_POST['IMEI']) : "";
$_POST['SN'] = isset($_POST['SN']) ? mysqli_real_escape_string($DB->Link, strtoupper($_POST['SN'])) : "";

if($_POST['SN'] != "" && !preg_match('([0-9A-F]{8, 13})', $_POST['SN'])) {
    echo "The Serial Number does not obey to the text format policy.<br/>Please double check again the value you entered.";
    exit();
} elseif($_POST['IMEI'] != "" && !preg_match('([0-9]{15})', $_POST['IMEI'])) {
    echo "The IMEI does not obey to the text format policy.<br/>Please double check again the value you entered.";
    exit();
}

echo "###OK###";

$serial = $_POST['IMEI'] != "" ? $_POST['IMEI'] : $_POST['SN'];

$start = stripos($serial, "<");
while($start !== false) {
    $serial = substr($serial, 0, $start) . substr($serial, stripos(substr($serial, $start), ">") + $start + 1);
    $start = stripos($serial, "<");
}

$_POST['udid'] = isset($_POST['udid']) ? mysqli_real_escape_string($DB->Link, strtolower($_POST['udid'])) : "";
$_POST['account'] = isset($_POST['account']) ? mysqli_real_escape_string($DB->Link, strtolower($_POST['account'])) : "";
$_POST['phone'] = isset($_POST['phone']) ? mysqli_real_escape_string($DB->Link, $_POST['phone']) : "";
$_POST['backupLink'] = isset($_POST['backupLink']) ? mysqli_real_escape_string($DB->Link, $_POST['backupLink']) : "";
$_POST['backupPwd'] = isset($_POST['backupPwd']) ? mysqli_real_escape_string($DB->Link, $_POST['backupPwd']) : "";
$_POST['videoLink'] = isset($_POST['videoLink']) ? mysqli_real_escape_string($DB->Link, $_POST['videoLink']) : "";
$_POST['fileLink'] = isset($_POST['fileLink']) ? mysqli_real_escape_string($DB->Link, $_POST['fileLink']) : "";
$_POST['comment'] = isset($_POST['comment']) ? mysqli_real_escape_string($DB->Link, $_POST['comment']) : "";
$_POST['notes'] = isset($_POST['notes']) ? mysqli_real_escape_string($DB->Link, $_POST['notes']) : "";
$_POST['smsEnabled'] = isset($_POST['smsEnabled']) ? mysqli_real_escape_string($DB->Link, $_POST['smsEnabled']) : "";
$_POST['sms'] = isset($_POST['sms']) ? mysqli_real_escape_string($DB->Link, trim($_POST['sms'])) : "";
$_POST['email'] = isset($_POST['email']) ? mysqli_real_escape_string($DB->Link, strtolower(trim($_POST['email']))) : "";
$_POST['firstname'] = isset($_POST['firstname']) ? mysqli_real_escape_string($DB->Link, ucwords(strtolower(trim($_POST['firstname'])))) : "";
$_POST['lastname'] = isset($_POST['lastname']) ? mysqli_real_escape_string($DB->Link, ucwords(strtolower(trim($_POST['lastname'])))) : "";

$_POST['sms'] = str_replace(' ', '', $_POST['sms']);
if(substr($_POST['sms'], 0, 1) == "+") $_POST['sms'] = substr($_POST['sms'], 1);
if(substr($_POST['sms'], 0, 2) == "00") $_POST['sms'] = substr($_POST['sms'], 2);

$randomID = "";
while($randomID == "") {
    $randomID = generateRandomString();
    if (mysqli_num_rows(mysqli_query($DB->Link, "SELECT relative_id FROM quick_orders WHERE relative_id = '{$randomID}'")) > 0) $randomID = "";
}
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT regular_{$_SESSION['currency']}, short_name FROM services WHERE id = " . $_POST['service']));

mysqli_query($DB->Link, "INSERT INTO quick_orders (
    relative_id,
    service,
    currency, 
    IMEI, 
    SN, 
    udid, 
    account, 
    phone, 
    backupLink, 
    backupPwd, 
    videoLink, 
    fileLink, 
    comment, 
    notes, 
    smsEnabled, 
    sms, 
    email, 
    firstname, 
    lastname,
    price,
    order_date
) VALUES (
    '{$randomID}',
    {$_POST['service']},
    '{$_SESSION['currency']}',
    '{$serial}',
    '{$serial}',
    '{$_POST['udid']}',
    '{$_POST['account']}',
    '{$_POST['phone']}',
    '{$_POST['backupLink']}',
    '{$_POST['backupPwd']}',
    '{$_POST['videoLink']}',
    '{$_POST['fileLink']}',
    '{$_POST['comment']}',
    '{$_POST['notes']}',
    " . ($_POST['smsEnabled'] == "true" ? "1" : "0") . ",
    '{$_POST['sms']}',
    '{$_POST['email']}',
    '{$_POST['firstname']}',
    '{$_POST['lastname']}',
    {$row["regular_{$_SESSION['currency']}"]},
    '" . gmdate("Y-m-d H:i:s") . "')");

$body = ($_POST['firstname'] != "" ? $_POST['firstname'] : ($_POST['lastname'] != "" ? $_POST['lastname'] : "Hi")) . ",<br /><br />Your order has been successfully placed and we will start processing it soon.<br /><br />";
$body .= "<u>Order ID:</u> <b>" . $randomID . "</b><br />";
$body .= "<u>" . ($_POST['IMEI'] != "" ? "IMEI" : "S/N") . ":</u> <b>{$serial}</b><br />";
$body .= "<u>Service:</u> <b>" . $row['short_name'] . "</b><br /><br />
<u>Order's Comments:</u><br />";
$body .= $_POST['comment'] != "" ? nl2br($_POST['comment']) : "No comments!";
$body .= "<br /><br />
<u>Personal Notes:</u><br />";
$body .= $_POST['notes'] != "" ? nl2br($_POST['notes']) : "No notes!";
$body .= "<br /><br />
You can track your order's progress by visiting this link: <a href='https://www.prounlockphone.com/track/order-status.php?ref=" . $randomID . "' target='_blank'>https://www.prounlockphone.com/track/order-status.php?ref=" . $randomID . "</a><br /><br />
<u>P.S:</u><br />
This service costs <b>" . number_format($row["regular_{$_SESSION['currency']}"], 2) . " {$_SESSION['symbol']}</b>
<br /><br /><br />Thanks for shopping!";
require_once('../eMail.php');
Notify_client("🔥 Order Placed: {$serial} {$row['short_name']}", $body, $_POST['email'], ($_POST['firstname'] != "" ? $_POST['firstname'] : ($_POST['lastname'] != "" ? $_POST['lastname'] : "Guest")), 0, "Order deposit", "orders", 1);

echo $randomID;
function generateRandomString($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>