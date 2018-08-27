<?php
if(!isset($_POST['relative_id'])) {
    header("Location: https://www.prounlockphone.com/services/");
    exit();
}
define('INCLUDE_CHECK', true);
require '../common.php';
$DB = new DBConnection();

$_POST['relative_id'] = mysqli_real_escape_string($DB->Link, $_POST['relative_id']);

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT lastname, relative_id, quick_orders.IMEI 'IMEI', quick_orders.SN 'SN', firstname, short_name, email, payment_status, status, comment, notes FROM quick_orders, services WHERE services.id = service AND relative_id = '{$_POST['relative_id']}'"));
if($row['payment_status'] != 'Waiting Payment' || $row['status'] != 'Pending') {
    echo "Treated";
    exit();
}

mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = 'Payment Canceled', status = 'Canceled' WHERE relative_id = '{$_POST['relative_id']}'");

$body = ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ",<br /><br />Your order has been canceled since you did not complete your payment.<br /><br />";
$body .= "<u>Order ID:</u> <b>" . $row['relative_id'] . "</b><br />";
$body .= ($row['IMEI'] != "") ? "<u>IMEI:</u> <b>{$row['IMEI']}</b><br />" : "<u>S/N:</u> <b>{$row['SN']}</b><br />";
$body .= "<u>Service:</u> <b>" . $row['short_name'] . "</b><br /><br />";
$body .= $row["comment"] != "" ? "<u>Admin Comments:</u><br />" . nl2br($row["comment"]) : "";
$body .= "<br /><br />";
$body .= $row["notes"] != "" ? "<u>Personal Notes:</u><br />" . nl2br($row["notes"]) : "";
$body .= "<br /><br />
For further details, visit: <a href='https://www.prounlockphone.com/track/order-status.php?ref=" . $row['relative_id'] . "' target='_blank'>https://www.prounlockphone.com/track/order-status.php?ref=" . $row['relative_id'] . "</a><br /><br />
<br /><br />We appreciate your business!";
require_once('../eMail.php');
Notify_client('😞 Order Payment Canceled: ' . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'], $body, $row['email'], ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Guest")), 0, "Order status", "orders", 1);
echo "OK";
?>