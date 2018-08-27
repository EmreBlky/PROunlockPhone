<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$details_client = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance FROM users WHERE id = " . $_SESSION['client_id']));
if(!isset($_POST["tracker"])) $_POST["tracker"] = "";
else {
    $_POST["tracker"] = strtolower($_POST["tracker"]);
    if ($_POST["tracker"] == $_SESSION['client_email']) $_POST["tracker"] = "";
}

$balance = $details_client['balance'];

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT short_name, regular_{$_SESSION['currency']}, reseller_{$_SESSION['currency']} FROM services WHERE id = '{$_POST['service']}'"));
$bargains = mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$_SESSION['client_id']}' AND service = '{$_POST['service']}'");
$bargain = mysqli_fetch_assoc($bargains);
if(mysqli_num_rows($bargains)) {
    if($bargain['nature'] == 'impose') {
        if($bargain['price'] < $row["{$_SESSION['client_type']}_{$_SESSION['currency']}"]) {
            $price = $row["{$_SESSION['client_type']}_{$_SESSION['currency']}"];
        } else {
            $price = $bargain['price'];
        }
    } else {
        if($row["{$_SESSION['client_type']}_{$_SESSION['currency']}"] < $bargain['price']) {
            $price = $row["{$_SESSION['client_type']}_{$_SESSION['currency']}"];
        } else {
            $price = $bargain['price'];
        }
    }
} else {
    $price = $row["{$_SESSION['client_type']}_{$_SESSION['currency']}"];
}

$serials = explode(PHP_EOL, $_POST['serials']);
$maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $_SESSION['client_id']));
$nextTransaction = $maxid["relative_id"];
$maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM orders WHERE client = " . $_SESSION['client_id']));
$nextOrder = $maxid["relative_id"];
$nbr = 0;
$postscript = array();

foreach($serials as $serial) {
    if(ord(substr($serial, -1)) == 13 || ord(substr($serial, -1)) == 10) $serial = substr($serial, 0, -1);
    if(strlen($serial) < 8) continue;
    if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE (IMEI = '{$serial}' OR SN = '{$serial}') AND service = '{$_POST['service']}' AND status IN('Pending', 'In process')"))) {
        $postscript[] = "Duplicated order {$serial}.";
        continue;
    }
    $serial = strtoupper($serial);
    $nbr++;
    $nextOrder = NextID($nextOrder);
    $nextTransaction = NextID($nextTransaction);

    $UID = "";
    while ($UID == "") {
        $UID = generateRandomString();
        if (mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE UID = '{$UID}'")) > 0) $UID = "";
    }

    $query = "INSERT INTO orders (
        UID,
        relative_id,
        order_date,
        IMEI,
        SN,
        udid,
        backupLink,
        backupPwd,
        clear_email,
        phone,
        owner_name,
        client_order_comments,
        client_personal_notes,
        service,
        client,
        price,
        SMSNotification,
        eMailNotification,
        tracker,
        label) VALUES (
        '{$UID}',
        '{$nextOrder}',
        '" . gmdate("Y-m-d H:i:s") . "'";

    if(is_numeric($serial) && strlen($serial) == 15) {
        $query .= ", '" . $serial . "', ''";
    } else {
        $query .= ", '', '" . $serial . "'";
    }
    if(isset($_POST['udid'])) {
        $query .= ", '" . strtolower(mysqli_real_escape_string($DB->Link, $_POST['udid'])) . "'";
    } else {
        $query .= ", ''";
    }
    if(isset($_POST['fileLink'])) {
        $query .= ", '" . mysqli_real_escape_string($DB->Link, $_POST['fileLink']) . "', ''";
    } elseif(isset($_POST['videoLink'])) {
        $query .= ", '" . mysqli_real_escape_string($DB->Link, $_POST['videoLink']) . "', ''";
    } elseif(isset($_POST['backupLink'])) {
        $query .= ", '" . mysqli_real_escape_string($DB->Link, $_POST['backupLink']) . "', '" . mysqli_real_escape_string($DB->Link, $_POST['backupPwd']) . "'";
    } else {
        $query .= ", '', ''";
    }
    if(isset($_POST['account'])) {
        $query .= ", '" . strtolower(mysqli_real_escape_string($DB->Link, $_POST['account'])) . "'";
    } else {
        $query .= ", ''";
    }
    if(isset($_POST['phone'])) {
        $query .= ", '" . mysqli_real_escape_string($DB->Link, $_POST['phone']) . "'";
    } else {
        $query .= ", ''";
    }
    if(isset($_POST['owner_name'])) {
        $query .= ", '" . mysqli_real_escape_string($DB->Link, $_POST['owner_name']) . "'";
    } else {
        $query .= ", ''";
    }

    $balance = $balance - $price;
    $query .= ", '" . mysqli_real_escape_string($DB->Link, $_POST["comment"]) . "', '" . mysqli_real_escape_string($DB->Link, $_POST["notes"]) . "', '{$_POST['service']}',
                    '{$_SESSION['client_id']}', '{$price}', '" . ($_POST['sms'] == "true" ? "1" : "0") . "', '" . ($_POST['email'] == "true"  ? "1" : "0") . "', '" . mysqli_real_escape_string($DB->Link, $_POST['tracker']) . "', '" . mysqli_real_escape_string($DB->Link, $_POST['label']) . "')";
    mysqli_query($DB->Link, $query);

    mysqli_query($DB->Link, "INSERT INTO statement (relative_id, order_id, transaction_type, description, debit, balance_after, client)
                                    VALUES (
                                        \"" . $nextTransaction . "\",
                                        " . mysqli_insert_id($DB->Link) . ",
                                        \"order placed\",
                                        \"" . $row['short_name'] . " " . $serial . "\",
                                        \"" . $price . "\",
                                        \"" . $balance . "\",
                                        {$_SESSION['client_id']}
                                    )");
    mysqli_query($DB->Link, "UPDATE users SET balance = balance - " . $price . " WHERE id = " . $_SESSION['client_id']);
    $lastSerial = $serial;
}
if($nbr == 0) {
    $response = array(
        "type" => 0,
        "msg" => [
            "There was a problem with your order.<br /> Double-check your IMEIs/SNs."
        ]
    );
    if(count($postscript) > 0) {
        $response['msg'] = array_merge ($response['msg'], $postscript);
    }
    echo json_encode($response);
    exit;
}
if($_SESSION['client_id'] != "58" && $_POST['email'] == 'true') {
    if($nbr > 1) {
        $order = $row['short_name'];
        $body = "{$_SESSION['client_short']},<br /><br />Your orders have been successfully placed and we will start processing them soon.<br /><br />";
        $body .= "<u>Service:</u> <b>" . $row['short_name'] . "</b><br /><br />
<u>Order's Comments:</u><br />";
        $body .= $_POST["comment"] != "" ? nl2br($_POST["comment"]) : "No comments!";
        $body .= "<br /><br />
<u>Personal Notes:</u><br />";
        $body .= $_POST["notes"] != "" ? nl2br($_POST["notes"]) : "No notes!";
        $body .= "<br /><br />";
        $body .= "You successfully placed " . $nbr . " orders.<br />
Go to <a href='https://www.prounlockphone.com/orders/'>your summary</a> to check your orders.<br /><br />
<u>P.S:</u><br />
This service costs <b>" . number_format($price, 2) . " {$_SESSION['symbol']}</b><br />Your balance has been updated, your new balance is <b>" . number_format($balance, 2) . " {$_SESSION['symbol']}</b> = " . number_format($details_client['balance'], 2, ".", ",") . " - " . $nbr . " x " . number_format($price, 2, ".", ",") . "
<br /><br /><br />Thanks for shopping!";
    } elseif($nbr == 1) {
        $order = $lastSerial . " " . $row['short_name'];
        $body = "{$_SESSION['client_short']},<br /><br />Your order has been successfully placed and we will start processing it soon.<br /><br />";
        $body .= "<u>Order ID:</u> <b>" . $nextOrder . "</b><br />";
        $body .= (is_numeric($lastSerial) && strlen($lastSerial) == 15) ? "<u>IMEI:</u> <b>" . $lastSerial . "</b><br />" : "<u>S/N:</u> <b>" . $lastSerial . "</b><br />";
        $body .= "<u>Service:</u> <b>" . $row['short_name'] . "</b><br /><br />
<u>Order's Comments:</u><br />";
        $body .= $_POST["comment"] != "" ? nl2br($_POST["comment"]) : "No comments!";
        $body .= "<br /><br />
<u>Personal Notes:</u><br />";
        $body .= $_POST["notes"] != "" ? nl2br($_POST["notes"]) : "No notes!";
        $body .= "<br /><br />
<a href='https://www.prounlockphone.com/track/order-status.php?ref={$UID}'>Click this shareable link for a quick review.</a><br /><br />
<u>P.S:</u><br />
This service costs <b>" . number_format($price, 2) . " {$_SESSION['symbol']}</b><br />Your balance has been updated, your new balance is <b>" . number_format($balance, 2) . " {$_SESSION['symbol']}</b>
<br /><br /><br />Thanks for shopping!";
    }
    require_once('../eMail.php');
    Notify_client('🔥 Order Placed: ' . $order, $body, $_SESSION['client_email'], $_SESSION['client_short'], $_SESSION['client_id'], "Order deposit", "orders", 1);
}

if($nbr == 1) {
    $response = array(
        "type" => 1,
        "msg" => [
            "Order successfully placed",
            "Order ID " . $nextOrder,
            "Service cost {$price} {$_SESSION['symbol']}",
            "New balance {$balance} {$_SESSION['symbol']}"
        ],
        "data" => [
            "balance" => "BALANCE {$balance} {$_SESSION['symbol']}",
            "color" => ($balance < 0 ? "red" : $balance < 10 ? "orange" : "green")
        ]
    );
} else {
    $response = array(
        "type" => 1,
        "msg" => [
            $nbr . " Orders successfully placed",
            "Service cost " . ($nbr * $price) . " " . $_SESSION['symbol'],
            "New balance {$balance} {$_SESSION['symbol']}"
        ],
        "data" => [
            "balance" => "BALANCE {$balance} {$_SESSION['symbol']}",
            "color" => ($balance < 0 ? "red" : $balance < 10 ? "orange" : "green")
        ]
    );
}

if(count($postscript) > 0) {
    $response['msg'] = array_merge ($response['msg'], $postscript);
}

echo json_encode($response);

function NextID($CurrentID) {
    if($CurrentID == "") {
        $next_id = "0001";
    } else {
        $next_id = intval(substr($CurrentID, 3, 4)) + 1;
        if($next_id < 10) {
            $next_id = "000" . $next_id;
        } elseif($next_id < 100) {
            $next_id = "00" . $next_id;
        } elseif($next_id < 1000) {
            $next_id = "0" . $next_id;
        }
    }
    return strtoupper(substr($_SESSION['username'], 0, 3)) . $next_id;
}

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