<?php
if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

function smsNotify($subject, $text, $dest, $client, $order, $order_id = 0, $billit = 1) {
    set_time_limit(300);
    $DB = new DBConnection();
    require_once('plivo.php');
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT phone_code FROM countries, users WHERE country_code = country AND users.id = " . $client));
    $p = new RestAPI("MAZJEZYWVKZJQ1ZJQ5NW", "YzM2Mjk3OTgyYWNmNjQzOGUyMmI2MjVjNjA2MzQz");
    $reply = 'https://www.prounlockphone.com/SMSfeedback.php';
    if($row['phone_code'] == "1") {
        $src = "19093434570";
    } else {
        $src = 'Pro-U-Phone';
    }
    $params = array(
        'src' => $src,
        'dst' => $dest,
        'text' => $text,
        'url' => $reply,
        'method' => 'POST'
    );
    $response = $p->send_message($params);
    if(isset($response['response']['error'])) {
        mysqli_query($DB->Link, "INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert, error) VALUES (" . $client . ", \"SMS\", \"undelivered\", \"" . $dest . "\", \"" . $subject . "\", \"" . $text . "\", \"" . $order . "\", \"" . $response['response']['error'] . "\")");
        require_once('eMail.php');
        if($billit == 0) {
            Notify_me('SMS Failed upon registration', 'Attempt to send SMS to +' . $dest . ' to user ID: ' . $client . '<br /><br />Error: ' . $response['response']['error']);
        } else {
            Notify_me('SMS Failed', 'Attempt to send SMS to +' . $dest . ' to user ID: ' . $client . '<br /><br />Error: ' . $response['response']['error']);
        }
        return false;
    } else {
        if($billit == 1) {
            mysqli_query($DB->Link, "INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert, uuid) VALUES (" . $client . ", \"SMS\", \"queued\", \"+" . $dest . "\", \"" . $subject . "\", \"" . $text . "\", \"" . $order . "\", \"" . $response['response']['message_uuid'][0] . "\")");
            $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $client));
            if($maxid["relative_id"] == "") {
                $next_id = "0001";
            } else {
                $next_id = intval(substr($maxid["relative_id"], 3, 4)) + 1;
                if($next_id < 10) {
                    $next_id = "000" . $next_id;
                } elseif($next_id < 100) {
                    $next_id = "00" . $next_id;
                } elseif($next_id < 1000) {
                    $next_id = "0" . $next_id;
                }
            }
            $nextTransaction = substr($maxid["relative_id"], 0, 3) . $next_id;
            mysqli_query($DB->Link, "UPDATE users SET balance = balance - 0.1 WHERE id = '" . $client . "'");
            $details_client = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance FROM users WHERE id = '" . $client . "'"));
            $order_relative_id = "";
            if($order_id != 0) {
                $order_details = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT relative_id FROM orders WHERE id = '" . $order_id . "'"));
                $order_relative_id = "<a href='order_details.php?id=" . $order_id . "' target='_blank'>#" . $order_details['relative_id'] . "</a> ";
            }
            mysqli_query($DB->Link, "INSERT INTO statement (relative_id, order_id, transaction_type, description, credit, debit, balance_after, client, status)
                            VALUES (
                                \"" . $nextTransaction . "\",
                                0,
                                \"SMS Notification\",
                                \"" .$order_relative_id . "Subject: " . $subject . ". Sent to: +" . $dest . "\",
                                0,
                                0.1,
                                " .$details_client['balance'] . ",
                                " . $client . ",
                                5
                            )");
            return true;
        } else {
            mysqli_query($DB->Link, "INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert, uuid, cost) VALUES (" . $client . ", \"SMS\", \"queued\", \"+" . $dest . "\", \"" . $subject . "\", \"" . $text . "\", \"" . $order . "\", \"" . $response['response']['message_uuid'][0] . "\", 0)");
            return true;
        }
    }
}
?>