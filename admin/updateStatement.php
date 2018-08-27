<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

if($_GET['action'] == 'credit') {
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT client, order_id, credit FROM statement WHERE id = {$_GET['id']}"));
    $diff = $_GET['newValue'] - $row['credit'];
    mysqli_query($DB->Link, "UPDATE statement SET credit = {$_GET['newValue']} WHERE id = {$_GET['id']}");
    mysqli_query($DB->Link, "UPDATE statement SET balance_after = balance_after + ({$diff}) WHERE id >= {$_GET['id']}");
    mysqli_query($DB->Link, "UPDATE users SET balance = balance + ({$diff}) WHERE id = {$row['client']}");
    if($row['order_id'] != 0) mysqli_query($DB->Link, "UPDATE orders SET price = price + ({$diff}) WHERE id = {$row['client']}");
}



$link = ($_POST['backupLink'] != "" ? $_POST['backupLink'] : ($_POST['videoLink'] != "" ? $_POST['videoLink'] : ($_POST['fileLink'] != "" ? $_POST['fileLink'] : "")));
mysqli_query($DB->Link, "UPDATE orders SET backupLink = '" . mysqli_real_escape_string($DB->Link, $link) . "', backupPwd = '" . mysqli_real_escape_string($DB->Link, $_POST['backupPwd']) . "', ebayer = '" . mysqli_real_escape_string($DB->Link, $_POST['ebayer']) . "', tracker = '" . mysqli_real_escape_string($DB->Link, $_POST['tracker']) . "', service = '{$_POST['service']}', IMEI = '{$_POST['imei']}', SN = '{$_POST['serial']}', udid = '{$_POST['udid']}', phone = '" . mysqli_real_escape_string($DB->Link, $_POST['phone']) . "', clear_email = '" . mysqli_real_escape_string($DB->Link, $_POST['account']) . "', owner_name = '" . mysqli_real_escape_string($DB->Link, $_POST['owner_name']) . "' WHERE id = {$_POST['id']}");
echo "Update complete";
?>