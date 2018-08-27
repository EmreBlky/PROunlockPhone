<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

if(is_uploaded_file($_FILES["photo"]["tmp_name"])) {
    $path = $_POST['imei'] != "" ? $_POST['imei'] . $_POST["last_digit"] : strtoupper($_POST['serial']);
    rename($_FILES["photo"]["tmp_name"], "images/Uploaded/" . $path . ".jpg");
    chmod("images/Uploaded/" . $path . ".jpg", 0755);
}
if($_POST['client'] != "") {
    $client = $_POST['client']; 
} else {
    $client = $_SESSION['client_id'];
}
$details_client = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT waived, username, type, currency, first_name, email, balance FROM users WHERE id = '" . $client . "'"));
$client_type = $details_client['type'];
$client_currency = $details_client['currency'];
switch($client_currency) {
    case "USD":
        $devise = "$";
        break;
    case "EUR":
        $devise = "&euro;";
        break;
    case "GBP":
        $devise = "&pound;";
        break;
    case "TND":
        $devise = "DT";
        break;
}
$username = strtoupper(substr($details_client['username'], 0, 3));
$first_name = $details_client['first_name'];
$email = $details_client['email'];
$balance = $details_client['balance'];
$waived = $details_client['waived'] == "1";
$tracker = $_POST["tracker"] == $details_client['email'] ? "" : strtolower($_POST["tracker"]);
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT short_name, regular_" . $client_currency . ", reseller_" . $client_currency . " FROM services WHERE id = '" . $_POST['service'] . "'"));
$bargains = mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$client}' AND service = '{$_POST['service']}'");
$bargain = mysqli_fetch_assoc($bargains);
if(mysqli_num_rows($bargains)) {
    if($bargain['nature'] == 'impose') {
        if($bargain['price'] < $row[$client_type . "_" . $client_currency]) {
            $price = $row[$client_type . "_" . $client_currency];
        } else {
            $price = $bargain['price'];
        }
    } else {
        if($row[$client_type . "_" . $client_currency] < $bargain['price']) {
            $price = $row[$client_type . "_" . $client_currency];
        } else {
            $price = $bargain['price'];
        }
    }
} else {
    $price = $row[$client_type . "_" . $client_currency];
}
if(isset($_POST['bulk']) && $_POST['bulk'] != "") {
    $imeis = split("/\r\n|\n|\r/", $_POST['bulk']);
    if((count($imeis) * $price <= $balance) || $waived) {
        mysqli_query($DB->Link, "UPDATE users SET balance = balance - " . (count($imeis) * $price) . " WHERE id = '" . $client . "'");
        if(isset($_SESSION['balance'])) $_SESSION['balance'] = $_SESSION['balance'] - (count($imeis) * $price);
        $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $client));
        $nextTransaction = NextID($maxid["relative_id"]);
        $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM orders WHERE client = " . $client));
        $nextOrder = NextID($maxid["relative_id"]);
        $nbr = 0;
        foreach($imeis as $imei) {
            $nbr++;
            if(ord(substr($imei, -1)) == 13) $imei = substr($imei, 0, -1);
            $balance = $balance - $price;

            $UID = "";
            while ($UID == "") {
                $UID = generateRandomString();
                if (mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE UID = '{$UID}'")) > 0) $UID = "";
            }

            if(strlen($imei) == 15) {
                mysqli_query($DB->Link, "INSERT INTO orders (UID, relative_id, order_date, IMEI, SN, udid, clear_email, phone, status_mode, itools, client_order_comments, client_personal_notes, service, client, price, SMSNotification, eMailNotification, ebayer, tracker, owner_name) VALUES ('{$UID}', '" . $nextOrder . "', '" . gmdate("Y-m-d H:i:s") . "', '" . $imei . "',
                    '" . strtoupper($_POST["serial"]) . "', '" . strtolower($_POST["udid"]) . "', '" . strtolower($_POST["account"]) . "', '" . $_POST["phone"] . "', '" . $_POST["status_mode"] . "', '" . $_POST["itools"] . "', '" . mysqli_real_escape_string($DB->Link, $_POST["client_order_comments"]) . "', '" . mysqli_real_escape_string($DB->Link, $_POST["client_personal_notes"]) . "', '" . $_POST['service'] . "',
                    '" . $client . "', '" . $price . "', '" . ($_POST['SMSNotification'] == "on" ? "1" : "0") . "', '" . ($_POST['eMailNotification'] == "on" ? "1" : "0") . "', '" . strtolower($_POST["ebayer"]) . "', '" . $tracker . "', '" . $_POST["owner_name"] . "')");
            } else {
                mysqli_query($DB->Link, "INSERT INTO orders (UID, relative_id, order_date, IMEI, SN, udid, clear_email, phone, status_mode, itools, client_order_comments, client_personal_notes, service, client, price, SMSNotification, eMailNotification, ebayer, tracker, owner_name) VALUES ('{$UID}', '" . $nextOrder . "', '" . gmdate("Y-m-d H:i:s") . "', '" . $_POST["imei"] . "',
                    '" . strtoupper($imei) . "', '" . strtolower($_POST["udid"]) . "', '" . strtolower($_POST["account"]) . "', '" . $_POST["phone"] . "', '" . $_POST["status_mode"] . "', '" . $_POST["itools"] . "', '" . mysqli_real_escape_string($DB->Link, $_POST["client_order_comments"]) . "', '" . mysqli_real_escape_string($DB->Link, $_POST["client_personal_notes"]) . "', '" . $_POST['service'] . "',
                    '" . $client . "', '" . $price . "', '" . ($_POST['SMSNotification'] == "on" ? "1" : "0") . "', '" . ($_POST['eMailNotification'] == "on" ? "1" : "0") . "', '" . strtolower($_POST["ebayer"]) . "', '" . $tracker . "', '" . $_POST["owner_name"] . "')");
            }
            
            mysqli_query($DB->Link, "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client)
                                    VALUES (
                                        \"" . $nextTransaction . "\",
                                        " . mysqli_insert_id($DB->Link) . ",
                                        \"order placed\",
                                        \"" . $row['short_name'] . " " . ($imei != "" ? $imei : strtoupper($_POST["serial"])) . "\",
                                        \"\",
                                        \"" . $price . "\",
                                        \"" . $balance . "\",
                                        " . $client . "
                                    )");
            $nextOrder = NextID($nextOrder);
            $nextTransaction = NextID($nextTransaction);
        }
        $body = $first_name . ",<br /><br />Your orders have been successfully placed and we will start processing them soon.<br /><br />";
        $body .= "<u>Service:</u> <b>" . $row['short_name'] . "</b><br /><br /><u>Order's Comments:</u><br />";
        $body .= $_POST["client_order_comments"] != "" ? nl2br($_POST["client_order_comments"]) : "No comments!";
        $body .= "<br /><br />
        <u>Personal Notes:</u><br />";
        $body .= $_POST["client_personal_notes"] != "" ? nl2br($_POST["client_personal_notes"]) : "No notes!";
        $body .= "<br /><br />";
        $body .= "You successfully placed " . count($imeis) . " orders.<br />";
        $body .= "Go to <a href='https://www.prounlockphone.com/orders/'>your summary</a> to check them.<br /><br />
        <u>P.S:</u><br />
        This service costs <b>" . number_format($price, 2) . " " . $devise . "</b><br />Your balance has been updated, your new balance is <b>" . number_format($balance, 2) . " " . $devise . "</b>
        <br /><br /><br />Thanks for shopping !";
        if($client != "58" && $_POST['eMailNotification'] == "on") {
            require_once('../eMail.php');
            Notify_client('🔥 Order Placed: ' . $row['short_name'], $body, $email, $first_name, $client, "Order deposit", "orders");
        }
        echo "BULK (" . $nbr . " orders)";
    } else {
        echo "Failure";
    }
} else {
    if(($balance >= $price) || $waived) {
        mysqli_query($DB->Link, "UPDATE users SET balance = balance - " . $price . " WHERE id = " . $client);
        if(isset($_SESSION['balance'])) $_SESSION['balance'] = $_SESSION['balance'] - $price;
        $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $client));
        $nextTransaction = NextID($maxid["relative_id"]);
        $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM orders WHERE client = " . $client));
        $nextOrder = NextID($maxid["relative_id"]);
        $balance = $balance - $price;
        $imei = $_POST["imei"] . $_POST["last_digit"];

        $UID = "";
        while ($UID == "") {
            $UID = generateRandomString();
            if (mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE UID = '{$UID}'")) > 0) $UID = "";
        }

        mysqli_query($DB->Link, "INSERT INTO orders (UID, relative_id, order_date, IMEI, SN, udid, clear_email, phone, status_mode, itools, client_order_comments, client_personal_notes, service, client, price, SMSNotification, eMailNotification, ebayer, tracker, owner_name) VALUES ('{$UID}', '" . $nextOrder . "', '" . gmdate("Y-m-d H:i:s") . "', '" . $imei . "',
                        '" . strtoupper($_POST["serial"]) . "', '" . strtolower($_POST["udid"]) . "', '" . strtolower($_POST["account"]) . "', '" . $_POST["phone"] . "', '" . $_POST["status_mode"] . "', '" . $_POST["itools"] . "', '" . mysqli_real_escape_string($DB->Link, $_POST["client_order_comments"]) . "', '" . mysqli_real_escape_string($DB->Link, $_POST["client_personal_notes"]) . "', '" . $_POST['service'] . "',
                        '" . $client . "', '" . $price . "', '" . ($_POST['SMSNotification'] == "on" ? "1" : "0") . "', '" . ($_POST['eMailNotification'] == "on" ? "1" : "0") . "', '" . strtolower($_POST["ebayer"]) . "', '" . $tracker . "', '" . $_POST["owner_name"] . "')");
        mysqli_query($DB->Link, "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client)
                        VALUES (
                            \"" . $nextTransaction . "\",
                            " . mysqli_insert_id($DB->Link) . ",
                            \"order placed\",
                            \"" . $row['short_name'] . " " . ($imei != "" ? $imei : strtoupper($_POST["serial"])) . "\",
                            \"\",
                            \"" . $price . "\",
                            \"" . $balance . "\",
                            " . $client . "
                        )");

        $body = $first_name . ",<br /><br />Your order has been successfully placed and we will start processing it soon.<br /><br />";
        $body .= "<u>Order ID:</u> <b>" . $nextOrder . "</b><br />";
        $body .= $_POST["imei"] != "" ? "<u>IMEI:</u> <b>" . $imei . "</b><br />" : "";
        $body .= $_POST["serial"] != "" ? "<u>S/N:</u> <b>" . strtoupper($_POST["serial"]) . "</b><br />" : "";
        $body .= "<u>Service:</u> <b>" . $row['short_name'] . "</b><br /><br />
        <u>Order's Comments:</u><br />";
        $body .= $_POST["client_order_comments"] != "" ? nl2br($_POST["client_order_comments"]) : "No comments!";
        $body .= "<br /><br />
        <u>Personal Notes:</u><br />";
        $body .= $_POST["client_personal_notes"] != "" ? nl2br($_POST["client_personal_notes"]) : "No notes!";
        $body .= "<br /><br />
        <a href='https://www.prounlockphone.com/track/order-status.php?ref={$UID}'>Click this shareable link for a quick review.</a><br /><br />
        <u>P.S:</u><br />
        This service costs <b>" . number_format($price, 2) . " " . $devise . "</b><br />Your balance has been updated, your new balance is <b>" . number_format($balance, 2) . " " . $devise . "</b>
        <br /><br /><br />Thanks for shopping !";
        if($client != "58" && $_POST['eMailNotification'] == "on") {
            require_once('../eMail.php');
            Notify_client('🔥 Order Placed: ' . $row['short_name'], $body, $email, $first_name, $client, "Order deposit", "orders");
        }
        echo $nextOrder;
    } else {
        echo "Failure";
    }
}
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
    global $username;
    return $username . $next_id;
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