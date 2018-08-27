<?php
if(!isset($_POST['id'])) {
    header("Location: https://www.prounlockphone.com/orders/");
    exit();
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$data = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT status, cancelRequest, cancelRevoke, checkRequest FROM orders WHERE id = {$_POST['id']}"));
if(($data['status'] == "Pending" or $data['status'] == "In process") and $data['cancelRequest'] == 0 and $data['cancelRevoke'] == 0 and $data['checkRequest'] == 0) {
    $data = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance, price, short_name, orders.status 'STATE', first_name, last_name, username, relative_id, service_name, order_date, orders.imei 'IMEI', orders.sn 'SN', client_order_comments
    FROM orders, services, users
    WHERE 
        orders.id = {$_POST['id']}
        AND users.id = client
        AND services.id = service"));

    if($data['STATE'] == "Pending") {
        $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $_SESSION['client_id']));
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
        $nextTransaction = strtoupper(substr($_SESSION['username'], 0, 3)) . $nextTransaction;
        mysqli_query($DB->Link, "UPDATE orders SET status = 'Canceled', cancelRequest = 0, checkRequest = 0, cancelRevoke = 0, checkRevoke = 0 WHERE id = " . $_POST['id']);
        mysqli_query($DB->Link, "UPDATE statement SET status = 3 WHERE status = 0 AND order_id = " . $_POST['id'] . " ORDER BY transaction_date DESC LIMIT 1");
        mysqli_query($DB->Link, $query = "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client, status)
    VALUES (
        \"" . $nextTransaction . "\",
        " . $_POST['id'] . ",
        \"order canceled\",
        \"" . $data['short_name'] . " " . ($data['IMEI'] != "" ? $data['IMEI'] : $data['SN']) . "\",
        " . $data['price'] . ",
        \"\",
        " . ($data['balance'] + $data['price']) . ",
        " . $_SESSION['client_id'] . ",
        3
    )");
        mysqli_query($DB->Link, "UPDATE users SET balance = " . ($data['balance'] + $data['price']) . " WHERE id = " . $_SESSION['client_id']);

        $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT UID, users.phone 'phone', SMSNotification, eMailNotification, users.id 'user', services.id 'ser_id', balance, price, currency, orders.IMEI 'IMEI', orders.SN 'SN', first_name, email, relative_id, orders.status 'status', short_name, admin_response_comments, client_personal_notes FROM orders, users, services WHERE services.id = orders.service AND users.id = client AND orders.id = " . $_POST['id']));
        if($row['eMailNotification'] == "1") {
            $subject = "😞 Order Canceled: " . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'];
            $body = $row['first_name'] . ",<br /><br />";
            $body .= 'Your request to cancel your order has been accepted.<br /><br />';
            $body .= "<u>Order ID:</u> <b>" . $row['relative_id'] . "</b><br />";
            $body .= $row['IMEI'] != "" ? "<u>IMEI:</u> <b>" . $row['IMEI'] . "</b><br />" : "";
            $body .= $row['SN'] != "" ? "<u>S/N:</u> <b>" . $row['SN'] . "</b><br />" : "";
            $body .= "<u>Service:</u> <b>#" . $row['ser_id'] . " " . $row['short_name'] . "</b><br /><br />
            <u>Admin's Comments:</u><br />";
            $body .= $row["admin_response_comments"] != "" ? nl2br($row["admin_response_comments"]) : "No comments!";
            $body .= "<br /><br />
            <u>Personal Notes:</u><br />";
            $body .= $row["client_personal_notes"] != "" ? nl2br($row["client_personal_notes"]) : "No notes!";
            $body .= "<br /><br /><a href='https://www.prounlockphone.com/track/order-status.php?ref={$row['UID']}'>Click this shareable link for a quick review.</a>";
            $body .= "<br /><br /><u>P.S:</u><br />";
            $body .= "Your money has been refunded <b>" . number_format($row['price'], 2) . " " . $row['currency'] . "</b>.<br />Your balance has been updated, your new balance is <b>" . number_format($row['balance'], 2) . " " . $row['currency'] . "</b>.";
            $body .= "<br /><br /><br />Thanks again for your business";
            require_once('../eMail.php');
            Notify_client($subject, $body, $row['email'], $row['first_name'], $row['user'], "Order status", "orders");
        }
        if($row['SMSNotification'] == "1" && $row['balance'] >= 0.1) {
            $subject = "Order Canceled :|";
            $text = "ProUnlockPhone
    " . $row['first_name'] . ", your order ";
            $text .= $row['IMEI'] != "" ? $row['IMEI'] : $row['SN'];
            $text .= " has been canceled in response to your request.";
            $text .= $row["admin_response_comments"] != "" ? "
    " . $row["admin_response_comments"] : "";
            if(strlen($text) > 160) $text = substr($text, 0, 157) . "...";
            require_once('../SMS.php');
            smsNotify($subject, $text, $row['phone'], $row['user'], "Order status", $_POST['id']);
        }
        $body = "Client: {$data['first_name']} {$data['last_name']} ({$data['username']})<br />"
            . "Order ID: {$data['relative_id']}<br />"
            . "IMEI: {$data['IMEI']}<br />"
            . "S/N: {$data['SN']}<br />"
            . "Service: {$data['service_name']}<br />"
            . "Order date: {$data['order_date']}<br />"
            . "<br />"
            . "Reasons:";
            if(isset($_POST['time']) and $_POST['time']) $body .= "<br />- Order passed the posted processing time";
            if(isset($_POST['stop']) and $_POST['stop']) $body .= "<br />- You need to stop at any cost, maybe due to your client or another reason";
            if(isset($_POST['wrong']) and $_POST['wrong']) $body .= "<br />- Wrong service, willing to order the appropriate one";
            if(isset($_POST['completed']) and $_POST['completed']) $body .= "<br />- The order is completed and no need to the service";
            if(isset($_POST['cheaper']) and $_POST['cheaper']) $body .= "<br />- You found cheaper service";
            if(isset($_POST['other']) and $_POST['other'] != "") $body .= "<br />- Other reason(s):<div style='margin-left:40px'>" . nl2br($_POST['other']) . "</div>";
        require_once('../eMail.php');
        Notify_me("Order auto canceled", $body);
    } elseif($data['STATE'] == "In process") {
        $body = "Client: {$data['first_name']} {$data['last_name']} ({$data['username']})<br />"
            . "Order ID: {$data['relative_id']}<br />"
            . "IMEI: {$data['IMEI']}<br />"
            . "S/N: {$data['SN']}<br />"
            . "Service: {$data['service_name']}<br />"
            . "Order date: {$data['order_date']}<br />"
            . "<br />"
            . "Reason:";
            if(isset($_POST['time']) and $_POST['time']) $body .= "<br />- Order passed the posted processing time";
            if(isset($_POST['stop']) and $_POST['stop']) $body .= "<br />- You need to stop at any cost, maybe due to your client or another reason";
            if(isset($_POST['wrong']) and $_POST['wrong']) $body .= "<br />- Wrong service, willing to order the appropriate one";
            if(isset($_POST['completed']) and $_POST['completed']) $body .= "<br />- The order is completed and no need to the service";
            if(isset($_POST['cheaper']) and $_POST['cheaper']) $body .= "<br />- You found cheaper service";
            if(isset($_POST['other']) and $_POST['other'] != "") $body .= "<br />- Other: " . nl2br($_POST['other']);
            $body .= "<br />"
            . "<a href='http://www.prounlockphone.com/update_order.php?external=yes&id={$_POST['id']}&data=Canceled' target='_blank'>Simply click here to approve</a>";
        require_once('../eMail.php');
        Notify_me("Cancel Request", $body);
        mysqli_query($DB->Link, "
    UPDATE orders SET
        cancelRequest = 1
     WHERE id = " . $_POST['id']);
    }
    header("Location: https://www.prounlockphone.com/orders/");
} else {
    header("Location: https://www.prounlockphone.com/orders/?error=nocancel");
}
?>