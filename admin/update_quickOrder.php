<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin" && !isset($_GET['external'])) {
    header("Location: ../login");
    exit();
}

$actualStatus = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT lastname, payment_status, service, alreadyStartedProcessing, status, short_name, price, quick_orders.IMEI 'IMEI', quick_orders.SN 'SN', firstname, relative_id, admin_response_comments, notes, currency, email, smsEnabled, sms FROM quick_orders, services WHERE services.id = service AND quick_orders.id = " . $_GET['id']));

mysqli_query($DB->Link, "UPDATE quick_orders SET status = \"" . $_GET['data'] . "\" WHERE id = " . $_GET['id']);

if($_GET['data'] == "Canceled") {
    mysqli_query($DB->Link, "UPDATE quick_orders SET cancelRequest = 0, checkRequest = 0, cancelRevoke = 0, checkRevoke = 0 WHERE id = " . $_GET['id']);
} elseif($_GET['data'] == "Rejected") {
    mysqli_query($DB->Link, "UPDATE quick_orders SET cancelRequest = 0, checkRequest = 0, cancelRevoke = 0, checkRevoke = 0 WHERE id = " . $_GET['id']);
} elseif($_GET['data'] == "In process") {
    mysqli_query($DB->Link, "UPDATE quick_orders SET alreadyStartedProcessing = 1, cancelRevoke = cancelRequest, cancelRequest = 0, checkRequest = 0, checkRevoke = 0  WHERE id = " . $_GET['id']);
} elseif($_GET['data'] == "Pending") {
    mysqli_query($DB->Link, "UPDATE quick_orders SET cancelRequest = 0, cancelRevoke = 0, checkRequest = 0, checkRevoke = 0 WHERE id = " . $_GET['id']);
} elseif($_GET['data'] == "Success") {
    mysqli_query($DB->Link, "UPDATE quick_orders SET checkRevoke = 1 WHERE checkRequest = 1 AND id = " . $_GET['id']);
    mysqli_query($DB->Link, "UPDATE quick_orders SET cancelRevoke = 1 WHERE cancelRequest = 1 AND id = " . $_GET['id']);
    mysqli_query($DB->Link, "UPDATE quick_orders SET cancelRequest = 0, checkRequest = 0 WHERE id = " . $_GET['id']);
}
if(($_GET['data'] != "In process" or $actualStatus['alreadyStartedProcessing'] == '1') and $_GET['data'] != $actualStatus['status']) {
    $body = "<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'></meta>
    <script type='application/ld+json'>
    {
        '@context': 'http://schema.org',
        '@type': 'EmailMessage',
        'action': {
            '@type': 'ViewAction',
            'url': 'https://www.prounlockphone.com/track/order-status.php?ref={$actualStatus['relative_id']}',
            'target': 'https://www.prounlockphone.com/track/order-status.php?ref={$actualStatus['relative_id']}',
            'name': 'View details'
        },
        'description': 'View details',
        'publisher': {
            '@type': 'Organization',
            'name': 'PROunlockPhone',
            'url': 'https://www.prounlockphone.com',
            'url/facebook': 'https://www.m.me/prounlockphone'
        }
    }
</script>
</head>
<body>
" . ($actualStatus['firstname'] != "" ? $actualStatus['firstname'] : ($actualStatus['lastname'] != "" ? $actualStatus['lastname'] : "Hi")) . ",<br /><br />";
    $order = ($actualStatus['IMEI'] != "" ? $actualStatus['IMEI'] : $actualStatus['SN']) . " " . $actualStatus['short_name'];
    switch($_GET['data']) {
        case 'Success':
            $bcc = 2;
            $subject = "💪 Order Completed: " . $order;
            $body .= '<b>CONGRATULATIONS!!!</b><br />Your order has been successfully processed.<br /><br />';
            break;
        case 'Pending':
            $subject = "🤔 Order set back to idle: " . $order;
            $body .= '<b>Hmmm! Something is going wrong!</b><br />Your order has been reset in pending status.<br />You might find more information in the "Admin\'s Comments" below.<br />If you don\'t know about the issue, please contact the administrator for additional details.<br /><br />';
            break;
        case 'Canceled':
            $subject = "😞 Order Canceled: " . $order;
            $body .= 'Your request to cancel your order has been accepted.<br /><br />';
            break;
        case 'Rejected':
            $subject = "😤 Order Rejected: " . $order;
            $body .= '<b>We are sorry!</b><br />Your order has been rejected.<br />You might find additional details about the reasons in the "Admin\'s Comments" below.<br /><br />';
            break;
        case 'In process':
            $subject = "😎 Order in process: " . $order;
            $body .= 'We wanted to notify you that we just started processing your order.<br />Stay tuned for further updates.<br /><br />';
            break;
        default:
            $bcc = 0;
            break;
    }
    $body .= "<u>Order ID:</u> <b>" . $actualStatus['relative_id'] . "</b><br />";
    $body .= $actualStatus['IMEI'] != "" ? "<u>IMEI:</u> <b>" . $actualStatus['IMEI'] . "</b><br />" : "";
    $body .= $actualStatus['SN'] != "" ? "<u>S/N:</u> <b>" . $actualStatus['SN'] . "</b><br />" : "";
    $body .= "<u>Service:</u> <b>#" . $actualStatus['service'] . " " . $actualStatus['short_name'] . "</b>";
    if($actualStatus['smsEnabled'] == "1" && $_GET['data'] == "Success") {
        $subject = "💪 Order Completed: " . ($actualStatus['IMEI'] != "" ? $actualStatus['IMEI'] : $actualStatus['SN']) . " " . $actualStatus['short_name'];
        $text = ($actualStatus['firstname'] != "" ? $actualStatus['firstname'] : ($actualStatus['lastname'] != "" ? $actualStatus['lastname'] : "Hi")) . ", your order " . $actualStatus['relative_id'] . " has been successfully processed.
Check your eMailbox for additional details.

PROunlockPhone.";
        if(strlen($text) > 160) $text = substr($text, 0, 157) . "...";
        require_once('../SMS.php');
        if(!smsNotify($subject, $text, $actualStatus['sms'], 0, "Order status", $_GET['id'])) {
            $actualStatus["admin_response_comments"] = $actualStatus["admin_response_comments"] . ($actualStatus["admin_response_comments"] != "" ? "\n" : "");
            $actualStatus["admin_response_comments"] .= "SMS Error generated: There was a problem while attempting to send text to +" . substr($actualStatus['sms'], 0, 1);
            for($i = 0; $i < strlen($actualStatus['sms']) - 5; $i++) {
                $actualStatus["admin_response_comments"] .= "&bull;";
            }
            $actualStatus["admin_response_comments"] .= substr($actualStatus['sms'], -4) . " [unreachable destination]";
            mysqli_query($DB->Link, "UPDATE quick_orders SET admin_response_comments = '" . mysqli_real_escape_string($DB->Link, $actualStatus["admin_response_comments"]) . "' WHERE id = " . $_GET['id']);
        }
    }
    if($actualStatus["admin_response_comments"] != "") {
        $body .= "<br /><br /><u>Admin Comments:</u><br />" . nl2br($actualStatus["admin_response_comments"]);
    }
    if($actualStatus["notes"] != "") {
        $body .= "<br /><br /><u>Personal Notes:</u><br />" . nl2br($actualStatus["notes"]);
    }
    if(($_GET['data'] == "Canceled" or $_GET['data'] == "Rejected") and ($actualStatus['status'] == "Pending" or $actualStatus['status'] == "Success" or $actualStatus['status'] == "In process") and ($actualStatus['payment_status'] == 'Payment Pending Clearance' or $actualStatus['payment_status'] == 'Payment Received' or $actualStatus['payment_status'] == 'PayPal Case Solved')) {
        $body .= "<br /><br /><u>P.S:</u><br />Your money has been refunded <b>" . number_format($actualStatus['price'], 2) . " " . $actualStatus['currency'] . "</b>.";
    }
    $body .= "<br /><br />Thanks again for your business.";
    require_once('../eMail.php');
    Notify_client($subject, $body, $actualStatus['email'], ($actualStatus['firstname'] != "" ? $actualStatus['firstname'] : ($actualStatus['lastname'] != "" ? $actualStatus['lastname'] : "Guest")), 0, "Order status", "orders", 2);
}

if(isset($_GET['external'])) {
    echo "<script language='javascript'>window.close()</script>";
}
?>