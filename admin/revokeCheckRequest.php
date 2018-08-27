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
    checkRequest = 0,
    checkRevoke = 1,
    status = 'Success',
    admin_response_comments = CASE
        WHEN admin_response_comments = 'CHECK REQUEST PENDING' THEN
            ''
        WHEN SUBSTR(admin_response_comments, 1, 22) = 'CHECK REQUEST PENDING\n' THEN
            SUBSTR(admin_response_comments, 23)
        WHEN SUBSTR(admin_response_comments, 1, 21) = 'CHECK REQUEST PENDING' THEN
            SUBSTR(admin_response_comments, 22)
        ELSE
            admin_response_comments
        END
 WHERE id = " . $_GET['id']);
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT UID, users.phone 'phone', SMSNotification, eMailNotification, users.id 'user', services.id 'ser_id', balance, price, currency, orders.IMEI 'IMEI', orders.SN 'SN', first_name, email, relative_id, orders.status 'status', short_name, admin_response_comments, client_personal_notes FROM orders, users, services WHERE services.id = orders.service AND users.id = client AND orders.id = \"" . $_GET['id'] . "\""));
if($row['eMailNotification'] == "1") {
    $subject = "🧐 Check Request Closed: " . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'];
    $body = $row['first_name'] . ",<br /><br />
In response to your request, we reviewed your order <b>{$row['relative_id']}</b> and finally adjusted it so that now it is permanently considered completed.<br /><br />
Generally, 3 cases might have happened:
<ul>
<li>There is no way to check with the source, simple double check with the provided results from provider.</li>
<li>The results were reviewed and are now confirmed to be correct</li>
<li>The results were reviewed and regenerated and led to the actual conclusion (might be different from the previous results)</li>
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
    $subject = "Check Request Revoked";
    $text = $row['first_name'] . ",
Your cancel request for " . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " was unfortunately declined.
Check your eMailbox for additional details.

PROunlockPhone";
    $subject = "Check Request Closed";
    $text = $row['first_name'] . ", the status of your order " . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " is now final and closed.
Check your eMailbox for additional details.

PROunlockPhone";
    if(strlen($text) > 160) $text = substr($text, 0, 157) . "...";
    require_once('../SMS.php');
    smsNotify($subject, $text, $row['phone'], $row['user'], "Request status", $_GET['id']);
}
?>