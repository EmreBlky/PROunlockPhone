<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

if($_POST['amount'] == 0) {
    header("Location: statement.php?failed=Yes&action=refund&reason=amount&client=" . $_POST['client']);
    exit();
}
if($_POST['comment'] == "" and $_POST['nature'] == "") {
    header("Location: statement.php?failed=Yes&action=refund&reason=null&client=" . $_POST['client']);
    exit();
}

$_POST['trx'] = trim(strtoupper($_POST['trx']));
$_POST['sender'] = trim(strtolower($_POST['sender']));

if($_POST['nature'] == "PayPal" and $_POST['trx'] != "") {
    $row = mysqli_query($DB->Link, "SELECT paypal FROM statement WHERE paypal = \"{$_POST['trx']}\"");
    if(mysqli_num_rows($row) > 0) {
        header("Location: statement.php?failed=Yes&action=refund&reason=duplicate&client={$_POST['client']}&trx={$_POST['trx']}");
        exit;
    }
}
if($_POST['nature'] == "PayPal" and $_POST['trx'] == "") {
    header("Location: statement.php?failed=Yes&action=refund&reason=trx&client=" . $_POST['client']);
    exit;
}

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance, username, first_name, currency, email FROM users WHERE id = " . $_POST['client']));
$balance = $row['balance'] - $_POST['amount'];
mysqli_query($DB->Link, "UPDATE users SET balance = \"" . $balance . "\" WHERE id = " . $_POST['client']);
$maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $_POST['client']));
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
$next_id = strtoupper(substr($row['username'], 0, 3)) . $next_id;
if($_POST['nature'] == "PayPal") {
    $query = "INSERT INTO statement (relative_id, transaction_type, description, debit, balance_after, client, status, paypal, sender)
          VALUES (
            \"{$next_id}\",
            \"Refund deposit\",
            \"{$_POST['nature']} reimbursement\",
            \"{$_POST['amount']}\",
            \"{$balance}\",
            {$_POST['client']},
            4,
            \"{$_POST['trx']}\",
            \"{$_POST['sender']}\"
        )";
} else {
    $query = "INSERT INTO statement (relative_id, transaction_type, description, debit, balance_after, client, status)
          VALUES (
            \"{$next_id}\",
            \"Refund credits\",
            \"{$_POST['nature']}" . (($_POST['comment'] != "" and $_POST['nature'] != "") ? " " : "") . $_POST['comment'] . "\",
            \"{$_POST['amount']}\",
            \"{$balance}\",
            {$_POST['client']},
            4
        )";
}
mysqli_query($DB->Link, $query);
switch($row['currency']) {
    case "USD":
        $currency = "$";
        break;
    case "EUR":
        $currency = "&euro;";
        break;
    case "GBP":
        $currency = "&pound;";
        break;
    case "TND":
        $currency = "DT";
        break;
}
$subject = "💸 Credits refunded";
$body = "Dear " . $row['first_name'] . ",<br /><br />";
$body .= "Your account has been debited: <b>" . number_format($_POST['amount'], 2, '.', ',') . " {$currency}</b><br /><br />";
$body .= "Your new balance is: <b>" . number_format($balance, 2, '.', ',') . " {$currency}</b><br />";
if($_POST['nature'] == "PayPal" and $_POST['sender'] != "") $body .= "Recipient: {$_POST['sender']}<br />";
if($_POST['nature'] == "PayPal" and $_POST['trx'] != "")    $body .= $_POST['nature'] . " Transaction ID #{$_POST['trx']}<br />";

$body .= '<br />Thank you for trusting our services. We appreciate your business.';
require_once('../eMail.php');
Notify_client($subject, $body, $row['email'], $row['first_name'], $_POST['client'], "Balance update", "business");
header("Location: statement.php?client=" . $_POST['client']);
?>