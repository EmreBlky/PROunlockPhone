<?php
define('INCLUDE_CHECK', true);
require 'common.php';
$DB = new DBConnection();

$last_inserted = date('Y-m-d H:i:s',strtotime('-1 hour', time()));

mysqli_query($DB->Link, "UPDATE orders SET status = 'In process', pintotop = 1, alreadyStartedProcessing = 1 WHERE alreadyStartedProcessing = 0 AND status = 'Pending' AND order_date < '{$last_inserted}'");
mysqli_query($DB->Link, "UPDATE quick_orders SET status = 'In process', pintotop = 1, alreadyStartedProcessing = 1 WHERE alreadyStartedProcessing = 0 AND status = 'Pending' AND payment_status <> 'Waiting Payment' AND order_date < '{$last_inserted}'");


$rows = mysqli_query($DB->Link, "SELECT quick_orders.id 'ID', firstname, lastname, relative_id, quick_orders.IMEI 'IMEI', quick_orders.SN 'SN', short_name, comment, notes, email FROM quick_orders, services WHERE services.id = service AND payment_status = 'Waiting Payment' AND order_date < '{$last_inserted}'");

while($row = mysqli_fetch_array($rows)) {
    mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = 'Unpaid', status = 'Rejected' WHERE id = {$row['ID']}");

    $body = ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ",<br /><br />Your order has been rejected since you did not complete your payment.<br /><br />";
    $body .= "<u>Order ID:</u> <b>" . $row['relative_id'] . "</b><br />";
    $body .= ($row['IMEI'] != "") ? "<u>IMEI:</u> <b>{$row['IMEI']}</b><br />" : "<u>S/N:</u> <b>{$row['SN']}</b><br />";
    $body .= "<u>Service:</u> <b>" . $row['short_name'] . "</b><br /><br />
<u>Order's Comments:</u><br />";
    $body .= $row["comment"] != "" ? nl2br($row["comment"]) : "No comments!";
    $body .= "<br /><br />
<u>Personal Notes:</u><br />";
    $body .= $row["notes"] != "" ? nl2br($row["notes"]) : "No notes!";
    $body .= "<br /><br />
For further details, visiting: <a href='https://www.prounlockphone.com/track/order-status.php?ref=" . $row['relative_id'] . "' target='_blank'>https://www.prounlockphone.com/track/order-status.php?ref=" . $row['relative_id'] . "</a><br /><br />
<br /><br />We appreciate your business!";
    require_once('eMail.php');
    Notify_client('😤 Order Rejected: ' . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'], $body, $row['email'], ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Guest")), 0, "Order status", "orders", 1);
}

?>