<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$actualStatus = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT alreadyStartedProcessing, status, short_name, price, orders.IMEI 'IMEI', orders.SN 'SN', client, relative_id FROM orders, services WHERE services.id = orders.service AND orders.id = " . $_GET['id']));
$maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $actualStatus['client']));
$user = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT username, balance FROM users WHERE id = " . $actualStatus['client']));
if($maxid['relative_id'] == "") {
        $nextTransaction = "0001";
} else {
    $nextTransaction = intval(substr($maxid['relative_id'], 3, 4)) + 1;
    if($nextTransaction < 10) {
        $nextTransaction = "000" . $nextTransaction;
    } elseif($nextTransaction < 100) {
        $nextTransaction = "00" . $nextTransaction;
    } elseif($nextTransaction < 1000) {
        $nextTransaction = "0" . $nextTransaction;
    }
}
$nextTransaction = strtoupper(substr($user['username'], 0, 3)) . $nextTransaction;

mysqli_query($DB->Link, "UPDATE orders SET status = \"" . $_GET['data'] . "\" WHERE id = " . $_GET['id']);
if($_GET['data'] == "Canceled" or $_GET['data'] == "Rejected" or $_GET['data'] == "Success") {
        mysqli_query($DB->Link, "
UPDATE orders SET
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
}
$token = chr(rand(65, 90)) . rand(0, 9) . rand(0, 9) . chr(rand(65, 90));
if($_GET['data'] == "Canceled") {
    mysqli_query($DB->Link, "UPDATE orders SET cancelRequest = 0, checkRequest = 0, cancelRevoke = 0, checkRevoke = 0 WHERE id = " . $_GET['id']);
    mysqli_query($DB->Link, "UPDATE users SET refundToken = '" . $token . "' WHERE id = " . $actualStatus['client']);
    if($actualStatus['status'] == "Rejected") {
        mysqli_query($DB->Link, "UPDATE statement SET transaction_type = 'order canceled' WHERE status = 3 AND order_id = " . $_GET['id'] . " ORDER BY transaction_date DESC LIMIT 1");
    } elseif($actualStatus['status'] == "Success" or $actualStatus['status'] == "In process" or $actualStatus['status'] == "Pending") {
        mysqli_query($DB->Link, "UPDATE statement SET status = 3 WHERE (status = 0 OR status = 1) AND order_id = " . $_GET['id'] . " ORDER BY transaction_date DESC LIMIT 1");
        mysqli_query($DB->Link, $query = "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client, status)
        VALUES (
            \"" . $nextTransaction . "\",
            " . $_GET['id'] . ",
            \"order canceled\",
            \"" . $actualStatus['short_name'] . " " . ($actualStatus['IMEI'] != "" ? $actualStatus['IMEI'] : $actualStatus['SN']) . "\",
            " . $actualStatus['price'] . ",
            \"\",
            " . ($user['balance'] + $actualStatus['price']) . ",
            " . $actualStatus['client'] . ",
            3
        )");
        mysqli_query($DB->Link, "UPDATE users SET balance = " . ($user['balance'] + $actualStatus['price']) . " WHERE id = " . $actualStatus['client']);
    }
} elseif($_GET['data'] == "Rejected") {
    mysqli_query($DB->Link, "UPDATE orders SET cancelRequest = 0, checkRequest = 0, cancelRevoke = 0, checkRevoke = 0 WHERE id = " . $_GET['id']);
    mysqli_query($DB->Link, "UPDATE users SET refundToken = '" . $token . "' WHERE id = " . $actualStatus['client']);
    if($actualStatus['status'] == "Canceled") {
        mysqli_query($DB->Link, "UPDATE statement SET transaction_type = 'order rejected' WHERE status = 3 AND order_id = " . $_GET['id'] . " ORDER BY transaction_date DESC LIMIT 1");
    } elseif($actualStatus['status'] == "Success" or $actualStatus['status'] == "In process" or $actualStatus['status'] == "Pending") {
        mysqli_query($DB->Link, "UPDATE statement SET status = 3, transaction_type = 'order placed' WHERE (status = 0 OR status = 1) AND order_id = " . $_GET['id'] . " ORDER BY transaction_date DESC LIMIT 1");
        mysqli_query($DB->Link, $query = "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client, status)
        VALUES (
            \"" . $nextTransaction . "\",
            " . $_GET['id'] . ",
            \"order rejected\",
            \"" . $actualStatus['short_name'] . " " . ($actualStatus['IMEI'] != "" ? $actualStatus['IMEI'] : $actualStatus['SN']) . "\",
            \"" . $actualStatus['price'] . "\",
            \"\",
            \"" . ($user['balance'] + $actualStatus['price']) . "\",
            " . $actualStatus['client'] . ",
            3
        )");
        mysqli_query($DB->Link, "UPDATE users SET balance = " . ($user['balance'] + $actualStatus['price']) . " WHERE id = " . $actualStatus['client']);
    }
} elseif($_GET['data'] == "In process") {
    mysqli_query($DB->Link, "UPDATE orders SET alreadyStartedProcessing = 1, cancelRevoke = cancelRequest, cancelRequest = 0, checkRequest = 0, checkRevoke = 0  WHERE id = " . $_GET['id']);
    if($actualStatus['status'] == "Success") {
        mysqli_query($DB->Link, "UPDATE statement SET transaction_type = \"order in process\", status = 0 WHERE status = 1 AND order_id = " . $_GET['id'] . " ORDER BY transaction_date DESC LIMIT 1");
    } elseif($actualStatus['status'] == "Canceled" or $actualStatus['status'] == "Rejected") {
        mysqli_query($DB->Link, $query = "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client)
        VALUES (
            \"" . $nextTransaction . "\",
            " . $_GET['id'] . ",
            \"order in process\",
            \"" . $actualStatus['short_name'] . " " . ($actualStatus['IMEI'] != "" ? $actualStatus['IMEI'] : $actualStatus['SN']) . "\",
            \"\",
            \"" . $actualStatus['price'] . "\",
            \"" . ($user['balance'] - $actualStatus['price']) . "\",
            " . $actualStatus['client'] . "
        )");
        mysqli_query($DB->Link, "UPDATE users SET balance = " . ($user['balance'] - $actualStatus['price']) . " WHERE id = " . $actualStatus['client']);
    }
} elseif($_GET['data'] == "Pending") {
    mysqli_query($DB->Link, "UPDATE orders SET cancelRequest = 0, cancelRevoke = 0, checkRequest = 0, checkRevoke = 0 WHERE id = " . $_GET['id']);
    if($actualStatus['status'] == "Success") {
        mysqli_query($DB->Link, "UPDATE statement SET transaction_type = \"order placed\", status = 0 WHERE status = 1 AND order_id = " . $_GET['id'] . " ORDER BY transaction_date DESC LIMIT 1");
    } elseif($actualStatus['status'] == "Canceled" or $actualStatus['status'] == "Rejected") {
        mysqli_query($DB->Link, $query = "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client)
        VALUES (
            \"" . $nextTransaction . "\",
            " . $_GET['id'] . ",
            \"order restarted\",
            \"" . $actualStatus['short_name'] . " " . ($actualStatus['IMEI'] != "" ? $actualStatus['IMEI'] : $actualStatus['SN']) . "\",
            \"\",
            \"" . $actualStatus['price'] . "\",
            \"" . ($user['balance'] - $actualStatus['price']) . "\",
            " . $actualStatus['client'] . "
        )");
        mysqli_query($DB->Link, "UPDATE users SET balance = " . ($user['balance'] - $actualStatus['price']) . " WHERE id = " . $actualStatus['client']);
    }
} elseif($_GET['data'] == "Success") {
    mysqli_query($DB->Link, "UPDATE orders SET checkRevoke = 1 WHERE checkRequest = 1 AND id = " . $_GET['id']);
    mysqli_query($DB->Link, "UPDATE orders SET cancelRevoke = 1 WHERE cancelRequest = 1 AND id = " . $_GET['id']);
    mysqli_query($DB->Link, "UPDATE orders SET cancelRequest = 0, checkRequest = 0 WHERE id = " . $_GET['id']);
    if($actualStatus['status'] == "In process" or $actualStatus['status'] == "Pending") {
        mysqli_query($DB->Link, "UPDATE statement SET transaction_type = \"order completed\", status = 1 WHERE status = 0 AND order_id = " . $_GET['id'] . " ORDER BY transaction_date DESC LIMIT 1");
    } elseif($actualStatus['status'] == "Canceled" or $actualStatus['status'] == "Rejected") {
        mysqli_query($DB->Link, $query = "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client, status)
        VALUES (
            \"" . $nextTransaction . "\",
            " . $_GET['id'] . ",
            \"order completed\",
            \"" . $actualStatus['short_name'] . " " . ($actualStatus['IMEI'] != "" ? $actualStatus['IMEI'] : $actualStatus['SN']) . "\",
            \"\",
            \"" . $actualStatus['price'] . "\",
            \"" . ($user['balance'] - $actualStatus['price']) . "\",
            " . $actualStatus['client'] . ",
            1
        )");
        mysqli_query($DB->Link, "UPDATE users SET balance = " . ($user['balance'] - $actualStatus['price']) . " WHERE id = " . $actualStatus['client']);
    }
}
if(($_GET['data'] != "In process" or $actualStatus['alreadyStartedProcessing'] == '1') and $_GET['data'] != $actualStatus['status']) {
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT last_name, UID, users.phone 'phone', SMSNotification, eMailNotification, tracker, users.id 'user', services.id 'ser_id', balance, price, currency, orders.IMEI 'IMEI', orders.SN 'SN', first_name, email, relative_id, orders.status 'status', short_name, admin_response_comments, client_personal_notes FROM orders, users, services WHERE services.id = orders.service AND users.id = client AND orders.id = " . $_GET['id']));
    $order = ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'];
    if($actualStatus['client'] != 58) {
        if($row['eMailNotification'] == "1") {
            $bcc = 0;
            switch($_GET['data']) {
                case 'Success':
                    $schema = 'OrderDelivered';
                    $bcc = 2;
                    $subject = "💪 Order Completed: " . $order;
                    $body = '<b>CONGRATULATIONS!!!</b><br />Your order has been successfully processed.<br /><br />';
                    break;
                case 'Pending':
                    $schema = 'OrderProblem';
                    $subject = "😒 Order set back to idle: " . $order;
                    $body = '<b>Hmmm! Something is going wrong!</b><br />Your order has been reset in pending status.<br /><br />You might find more information in the "Admin\'s Comments" below.<br />If you don\'t know about the issue, please contact the administrator for additional details.<br /><br />';
                    break;
                case 'Canceled':
                    $schema = 'OrderCancelled';
                    $subject = "😞 Order Canceled: " . $order;
                    $body = 'Your request to cancel your order has been accepted.<br /><br />';
                    break;
                case 'Rejected':
                    $schema = 'OrderReturned';
                    $subject = "😤 Order Rejected: " . $order;
                    $body = '<b>We are sorry!</b><br />Your order has been rejected.<br />You might find additional details about the reasons in the "Admin\'s Comments" below.<br /><br />';
                    break;
                case 'In process':
                    $schema = 'OrderProcessing';
                    $subject = "😎 Order in process: " . $order;
                    $body = 'We wanted to notify you that we just started processing your order.<br />Stay tuned for further updates.<br /><br />';
                    break;
                default:
                    $bcc = 0;
                    break;
            }
            $body = '<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
        <script type="application/ld+json">
        { 
            "@context" : "http://schema.org", 
            "@type" : "Order",
            "acceptedOffer" : {
                "@type" : "Offer", 
                "itemOffered" : { 
                    "@type" : "Product", 
                    "name" : "' . $row['short_name'] . '",
                    "url": "https://www.prounlockphone.com/",
                    "image": "https://www.prounlockphone.com/images/pup.png"
                },
                "priceSpecification" : { 
                    "@type" : "PriceSpecification", 
                    "priceCurrency" : "' . $row['currency'] . '", 
                    "price" : "' . number_format($row['price'], 2, ".", ",") . '"
                } 
            },
            "merchant" : {
                "@type" : "Organization",
                "name" : "PROunlockPhone"
            },
            "orderNumber" : "' . $row['UID'] . '",
            "orderStatus": "http://schema.org/' . $schema . '",
            "customer" : {
                "@type" : "Person",
                "name" : "' . $row['first_name'] . ' ' . $row['last_name'] . '"
            },
            "url": "https://www.prounlockphone.com/track/order-status.php?ref=' . $row['UID'] . '",
                "potentialAction": {
                "@type": "ViewAction",
                "url": "https://www.prounlockphone.com/track/order-status.php?ref=' . $row['UID'] . '",
                "name": "View details"
            }
        }
        </script>
    </head>
    <body>
    ' . $row['first_name'] . ",<br /><br />" . $body;
            $body .= "<u>Order ID:</u> <b>" . $row['relative_id'] . "</b><br />";
            $body .= $row['IMEI'] != "" ? "<u>IMEI:</u> <b>" . $row['IMEI'] . "</b><br />" : "";
            $body .= $row['SN'] != "" ? "<u>S/N:</u> <b>" . $row['SN'] . "</b><br />" : "";
            $body .= "<u>Service:</u> <b>#" . $row['ser_id'] . " " . $row['short_name'] . "</b>";
            $body .= $row["admin_response_comments"] != "" ? "<br /><br /><u>Admin Comments:</u><br />" . nl2br($row["admin_response_comments"]) : "";
            $body .= $row["client_personal_notes"] != "" ? "<br /><br /><u>Personal Notes:</u><br />" . nl2br($row["client_personal_notes"]) : "";
            $body .= "<br /><br /><a href='https://www.prounlockphone.com/track/order-status.php?ref={$row['UID']}'>Click this shareable link for a quick review.</a>";

            if(($_GET['data'] == "Canceled" or $_GET['data'] == "Rejected") and ($actualStatus['status'] == "Pending" or $actualStatus['status'] == "Success" or $actualStatus['status'] == "In process")) {
                $body .= "<br /><br /><u>P.S:</u><br />
                Your money has been refunded <b>" . number_format($row['price'], 2) . " " . $row['currency'] . "</b>.<br />Your balance has been updated, your new balance is <b>" . number_format($row['balance'], 2) . " " . $row['currency'] . "</b>.";
                if($row['balance'] > 0) {
                    $body .= "<br /><br />You can start a refund process simply by clicking this link: send a <a href='https://www.prounlockphone.com/refund/refund-success/?token=" . $token . "'>Money-Back Request</a>";
                }
            } elseif(($actualStatus['status'] == "Canceled" or $actualStatus['status'] == "Rejected") and ($_GET['data'] == "Pending" or $_GET['data'] == "Success" or $_GET['data'] == "In process")) {
                $body .= "<br /><br /><u>P.S:</u><br />
                Your order has been charged <b>" . number_format($row['price'], 2) . " " . $row['currency'] . "</b>.<br />Your balance has been updated, your new balance is <b>" . number_format($row['balance'], 2) . " " . $row['currency'] . "</b>.";
            } else {
                $body .= "<br /><br /><u>P.S:</u><br />
                No change in your balance, your actual balance is <b>" . number_format($row['balance'], 2) . " " . $row['currency'] . "</b>.";
            }
            $body .= "<br /><br /><br />Thanks again for your business.";
            require_once('../eMail.php');
            Notify_client($subject, $body, $row['email'], $row['first_name'], $row['user'], "Order status", "orders", $bcc);
        }
        if($row['SMSNotification'] == "1" && $row['balance'] > 0.1) {
            $text = $row['first_name'] . ", your order " . $row['relative_id'];
            $order = ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'];
            switch($_GET['data']) {
                case 'Success':
                    $subject = "💪 Order Completed: " . $order;
                    $text .= " has been successfully processed.";
                    break;
                case 'Pending':
                    $subject = "😒 Order set back to idle: " . $order;
                    $text .= " has been reset to pending.";
                    break;
                case 'Canceled':
                    $subject = "😞 Order Canceled: " . $order;
                    $text .= " has been canceled in response to your request.";
                    break;
                case 'Rejected':
                    $subject = "😤 Order Rejected: " . $order;
                    $text .= " has been rejected.";
                    break;
            }
            $text .= "Check your eMailbox for additional details.

PROunlockPhone";
            if(strlen($text) > 160) $text = substr($text, 0, 157) . "...";
            require_once('../SMS.php');
            smsNotify($subject, $text, $row['phone'], $row['user'], "Order status", $_GET['id']);
        }
    }
    if($row['tracker'] != "") {
        $body = "Valuable customer,<br /><br />";
        $order = ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'];
        $bcc = 0;
        switch($_GET['data']) {
            case 'Success':
                $bcc = 2;
                $subject = "💪 Order Completed: " . $order;
                $body .= '<b>CONGRATULATIONS!!!</b><br />Your order has been succesfully processed.<br /><br />';
                break;
            case 'Pending':
                $subject = "😒 Order set back to idle: " . $order;
                $body .= '<b>Hmmm! Something is going wrong!</b><br />Your order has been reset in pending status.<br />If you don\'t know about the issue, please contact the administrator for more information.<br /><br />';
                break;
            case 'Canceled':
                $subject = "😞 Order Canceled: " . $order;
                $body .= 'Your request to cancel your order has been accepted.<br /><br />';
                break;
            case 'Rejected':
                $subject = "😤 Order Rejected: " . $order;
                $body .= '<b>We are sorry!</b><br />Your order has been rejected.<br /><br />';
                break;
            case 'In process':
                $subject = "😎 Order in process: " . $order;
                $body .= 'We wanted to notify you that we just started processing your order.<br />Stay tuned for further updates.<br /><br />';
                break;
            default:
                $bcc = 0;
                break;
        }
        $body .= "<u>Order ID:</u> <b>" . $row['relative_id'] . "</b><br />";
        $body .= $row['IMEI'] != "" ? "<u>IMEI:</u> <b>" . $row['IMEI'] . "</b><br />" : "";
        $body .= $row['SN'] != "" ? "<u>S/N:</u> <b>" . $row['SN'] . "</b><br />" : "";
        $body .= "<u>Service:</u> <b>#" . $row['ser_id'] . " " . $row['short_name'] . "</b>";
        $body .= $row["admin_response_comments"] != "" ? "<br /><br /><u>Admin Comments:</u><br />" . nl2br($row["admin_response_comments"]) : "";
        $body .= $row["client_personal_notes"] != "" ? "<br /><br /><u>Personal Notes:</u><br />" . nl2br($row["client_personal_notes"]) : "No notes!";
        $body .= "<br /><br /><a href='https://www.prounlockphone.com/track/order-status.php?ref={$row['UID']}'>Click this shareable link for a quick review.</a>";
        $body .= "<br /><br /><br />Thanks again for your business.";
        require_once('../eMail.php');
        Notify_client($subject, $body, $row['tracker'], "valuable customer", $row['user'], "Order status", "orders", $bcc);
    }
}

if(isset($_GET['external'])) {
    echo "<script language='javascript'>window.close()</script>";
}
?>