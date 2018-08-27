<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

mysqli_query($DB->Link, "
UPDATE orders SET
    cancelRequest = 0,
    cancelRevoke = 1,
    admin_response_comments = CASE
        WHEN admin_response_comments = 'CANCEL REQUEST PENDING' THEN
            ''
        WHEN SUBSTR(admin_response_comments, 1, 23) = 'CANCEL REQUEST PENDING\n' THEN
            SUBSTR(admin_response_comments, 24)
        WHEN SUBSTR(admin_response_comments, 1, 22) = 'CANCEL REQUEST PENDING' THEN
            SUBSTR(admin_response_comments, 23)
        ELSE
            admin_response_comments
        END
 WHERE id = " . $_GET['id']);
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT UID, users.phone 'phone', SMSNotification, eMailNotification, users.id 'user', services.id 'ser_id', balance, price, currency, orders.IMEI 'IMEI', orders.SN 'SN', first_name, email, relative_id, orders.status 'status', short_name, admin_response_comments, client_personal_notes FROM orders, users, services WHERE services.id = orders.service AND users.id = client AND orders.id = \"" . $_GET['id'] . "\""));
if($row['eMailNotification'] == "1") {
    $subject = "😰 Cancel Request Revoked: " . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'];
    $body = $row['first_name'] . ",<br /><br />
Unfortunately, we couldn't cancel your order <b>{$row['relative_id']}</b>.<br /><br />
Generally, we can't accept such request when:
<ul>
<li>Our provider is already processing the order and there's no way to stop it, even if the delays were exceeded.</li>
<li>The delays are still respected</li>
<li>We already get the result of your order but might still dispatching it</li>
<li>The order is almost done, after reviewing it with the provider</li>
</ul><br /><br /><u>";
    $body .= $row['IMEI'] != "" ? "IMEI:</u> <b>" . $row['IMEI'] : "S/N:</u> <b>" . $row['SN'];
    $body .= "</b><br /><u>Service:</u> <b>#" . $row['ser_id'] . " " . $row['short_name'] . "</b><br /><br />
    <u>Admin's Comments:</u><br />";
    $body .= $row["admin_response_comments"] != "" ? nl2br($row["admin_response_comments"]) : "No comments!";
    $body .= "<br /><br />
    <u>Personal Notes:</u><br />";
    $body .= $row["client_personal_notes"] != "" ? nl2br($row["client_personal_notes"]) : "No notes!";
    $body .= "<br /><br /><a href='https://www.prounlockphone.com/track/order-status.php?ref={$row['UID']}'>Click this shareable link for a quick review.</a>";
    $body .= "<br /><br /><u>P.S:</u><br />";
    $body .= "No change in your balance, your actual balance is <b>" . number_format($row['balance'], 2) . " " . $row['currency'] . "</b>.";
    $body .= "<br /><br /><br />Thanks again for your business";
    require_once('../eMail.php');
    Notify_client($subject, $body, $row['email'], $row['first_name'], $row['user'], "Request status", "orders");
}
if($row['SMSNotification'] == "1") {
    $subject = "Cancel Request Revoked";
    $text = $row['first_name'] . ",
Your cancel request for " . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " was unfortunately declined.
Check your eMailbox for additional details.

PROunlockPhone";
    if(strlen($text) > 160) $text = substr($text, 0, 157) . "...";
    require_once('../SMS.php');
    smsNotify($subject, $text, $row['phone'], $row['user'], "Request status", $_GET['id']);
}
?>