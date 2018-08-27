<?php
define('INCLUDE_CHECK', true);
require 'DBConnection.php';
$DB = new DBConnection();
$error = "";
if(isset($_REQUEST['ErrorCode'])) {
    switch ($_REQUEST['ErrorCode']) {
        case "10":
            $error = "Invalid message";
            break;
        case "20":
            $error = "Network error";
            break;
        case "30":
            $error = "Spam detected";
            break;
        case "40":
            $error = "Invalid source number";
            break;
        case "50":
            $error = "Invalid destination number";
            break;
        case "60":
            $error = "Loop detected";
            break;
        case "70":
            $error = "Destination permanently unavailable";
            break;
        case "80":
            $error = "Destination temporarily unavailable";
            break;
        case "90":
            $error = "No route available";
            break;
        case "100":
            $error = "Prohibited by carrier";
            break;
        case "110":
            $error = "Message too long";
            break;
        case "200":
            $error = "Source number blocked by STOP from destination number";
            break;
        case "201":
            $error = "Outbound messages from US Toll Free Number blocked to Canaidan destination numbers";
            break;
        case "1000":
            $error = "Unknown error";
            break;
        default :
            $error = "";
            break;
    }
}
mysqli_query($DB->Link, "UPDATE notifications SET status = \"" . $_REQUEST['Status'] . "\", error = \"" . $error . "\" WHERE uuid = \"" . $_REQUEST['MessageUUID'] . "\"");
if($_REQUEST['Status'] == "undelivered") {
    $user = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT user, destination, cost FROM notifications WHERE uuid =\"" . $_REQUEST['MessageUUID'] . "\""));
    require_once('eMail.php');
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance, username FROM users WHERE id = " . $user['user']));
    if($user['cost'] != "0") {
        mysqli_query($DB->Link, "UPDATE users SET balance = balance + 0.1 WHERE id = " . $user['user']);
        $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $user['user']));
        if($maxid['relative_id'] == "") {
            $next_id = "0001";
        } else {
            $next_id = intval(substr($maxid['relative_id'], 3, 4)) + 1;
            if($next_id < 10) {
                $next_id = "000" . $next_id;
            } elseif($next_id < 100) {
                $next_id = "00" . $next_id;
            } elseif($next_id < 1000) {
                $next_id = "0" . $next_id;
            }
        }
        $next_id = strtoupper(substr($row['relative_id'], 0, 3)) . $next_id;
        $query = "INSERT INTO statement (relative_id, transaction_type, description, credit, balance_after, client, status)
                  VALUES (
                    \"" . $next_id . "\",
                    \"Refund SMS\",
                    \"SMS not delivered to " . $user['destination'] . "\",
                    \"0.10\",
                    \"" . $new_balance . "\",
                    " . $user['user'] . ",
                    4
                )";
        mysqli_query($DB->Link, $query);
        Notify_me('SMS Failed after being sent', 'SMS sent but later failed.<br />Sent to +' . $_REQUEST['To'] . ' to user: ' . $row['username'] . '(' . $user['user'] . ')<br /><br />Error: ' . $error);
    } else {
        Notify_me('SMS Failed upon registration after being sent', 'SMS sent but later failed.<br />Sent to +' . $_REQUEST['To'] . ' to user: ' . $row['username'] . '(' . $user['user'] . ')<br /><br />Error: ' . $error);
    }
}
?>