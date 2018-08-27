txn_type<?php
if(!isset($_REQUEST['txn_id'])) {
    header("Location: ./");
    exit();
}

define('INCLUDE_CHECK', true);
require 'common.php';
require 'offline.php';
$DB = new DBConnection();

require_once('eMail.php');

if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM statement WHERE paypal = \"" . $_REQUEST['txn_id'] . "\"")) > 0) {
    if($_REQUEST['txn_type'] == "new_case") {
        mysqli_query($DB->Link, "UPDATE users SET type = 'regular', status = 'Suspended' WHERE id = " . $_REQUEST['client']);
        $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance, username, first_name, last_name, currency, email FROM users WHERE id = " . $_REQUEST['client']));
        $text = "User: " . $row['first_name'] . " " . $row['last_name'] . " (" . $row['username'] . ") [" . $row['email'] . "]<br />";
        $text .= "Amount disputed: " . $_REQUEST['amount'] . " " . $_REQUEST['mc_currency'] . "<br />";
        $text .= "Protection eligibility: " . $_REQUEST['protection_eligibility'] . "<br />";
        $text .= "Case type: " . ($_REQUEST['case_type'] == "complaint" ? "Complaint" : $_REQUEST['case_type']) . "<br />";
        $text .= "Reason: " . ($_REQUEST['reason_code'] == "non_receipt" ? "Non receipt" : $_REQUEST['reason_code']) . "<br />";
        $text .= "User comment: " . $_REQUEST['buyer_additional_information'] . "<br />";
        $text .= '<ul><li>Transaction ID: ' . $_REQUEST['txn_id'] . '</li>';
        $text .= '<li>Purchase date: ' . $_REQUEST['payment_date'] . '</li>';
        $text .= '<li>Purchased item: ' . $_REQUEST['item_name'] . '</li>';
        $text .= '<li>PayPal account (payer ID): ' . $_REQUEST['first_name'] . ' ' . $_REQUEST['last_name'] . ' [' . $_REQUEST['payer_email'] . ']</li>';
        $text .= '<li>Disputed amount: ' . $_REQUEST['amount'] . ' ' . $_REQUEST['mc_currency'] . '</li></ul>';

//        https://www.prounlockphone.com/confirm.php?client=134&payment_date=07:18:37 Jul 22, 2017 PDT&txn_type=new_case&payer_email=lanceks@tpg.com.au&txn_id=7NN282288Y088913N&item_name=Credits purchase&amount=85&mc_currency=USD
        $body = '<p>' . $row['first_name'] . ',</p><br>';
        $body .= '<p>Hmmm,</p>';
        $body .= '<p>We were notified that you started a PayPal dispute against us concerning this transaction:</p>';
        $body .= '<ul><li>Transaction ID: ' . $_REQUEST['txn_id'] . '</li>';
        $body .= '<li>Purchase date: ' . $_REQUEST['payment_date'] . '</li>';
        $body .= '<li>Purchased item: ' . $_REQUEST['item_name'] . '</li>';
        $body .= '<li>PayPal account (payer ID): ' . $_REQUEST['first_name'] . ' ' . $_REQUEST['last_name'] . ' [' . $_REQUEST['payer_email'] . ']</li>';
        $body .= '<li>Disputed amount: ' . $_REQUEST['amount'] . ' ' . $_REQUEST['mc_currency'] . '</li></ul><br>';
        $body .= '<p>We feel so sorry to hear that as such attitude from our partners has a great impact on our PayPal account standing and can affect our ability to keep it available to our community. Nevertheless, you are free to express your opinion and claim your money if you are not satisfied by our services.</p><br>';
        $body .= '<p>It is also sad to know it because we usually respond promptly to all the "Money-back" requests that we receive and we process them within the first 12hrs upon reception.</p>';
        $body .= '<p>We were wondering why you did not attempt to get in touch with us prior to opening this case. A normal procedure, in respect to our terms and conditions that you agreed to at the time you created your account, is to first send a cancel request for all your orders in process (if there are any), then once canceled you proceed with a money-back request. These transactions are treated with no conditions.</p><br>';
        $body .= '<p>Back to your case, we need to inform you that your account\'s credits (equivalent to the disputed amount) were debited from your <b>PROUnlockPhone </b>account. This last is now <font color="#FF0000"><b><span style="background-color: rgb(255, 0, 0);"><font color="#FFFFFF">_SUSPENDED_</font></span></b></font> and you can\'t temporary access it.</p><br>';
        $body .= '<p>This situation can be easily solved. Once you close this disputed, your account will be reset <b><span style="background-color: rgb(0, 255, 0);"><font color="#FFFFFF">_ACTIVE_</font></span></b> and you can apply the procedure described above to get your money back in your PayPal account.</p>';
        $body .= '<p>You can disregard this message and stick to your decision and this won\'t be good for both of us. In such case, you must know that we won\'t comply with it and we will oppose to it using the agreement discussed above.</p><br>';
        $body .= '<p>We count on your understanding and your trust to quickly sort out this situation.</p>';
        $body .= '<p>Hope to hear good news from you soon.</p>';

        Notify_client("Account Suspended", $body, $row['email'], $row['first_name'], $_REQUEST['client'], "Account status", "admin");

        Notify_me("From ProUnlockPhone: New PayPal Case :(", $text . "<br /><br />" . $body);
        exit();
    } elseif($_REQUEST['payment_status'] == "Canceled_Reversal") {
        mysqli_query($DB->Link, "UPDATE users SET status = 'Active' WHERE id = " . $_REQUEST['client']);
        $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance, username, first_name, last_name, currency, email FROM users WHERE id = " . $_REQUEST['client']));
        $text = "User: " . $row['first_name'] . " " . $row['last_name'] . " (" . $row['username'] . ") [" . $row['email'] . "]<br />";
        $text .= "Amount disputed: " . $_REQUEST['amount'];

        $body = '<p>' . $row['first_name'] . ',</p><br>';
        $body .= '<p>We\'re so happy to know that you closed the PayPal dispute.</p>';
        $body .= '<p>Let us know how we can help now. If you still want to get your money back in your PayPal account, simply connect to your <b>PROUnlockPhone</b> account and send a request from there.</p>';
        $body .= '<p>Keep in mind that you need first to cancel any active order to get its credits back in your account prior to sending the money-back request.</p><br>';
        $body .= '<p>We do value your trust and the confidence that you put on us and we promise to never disappoint you.</p>';
        $body .= '<p>We hope this incident will not occur again in the future as we will always be present to answer your questions and elucidate any confusion.</p><br>';
        $body .= '<p>Your credits were added back to your account and this last is now set back <b><span style="background-color: rgb(0, 255, 0);"><font color="#FFFFFF">_ACTIVE_</font></span></b></p><br>';
        $body .= '<p>Nice to do business with you.</p>';

        Notify_client("Account Reactivated", $body, $row['email'], $row['first_name'], $_REQUEST['client'], "Account status", "admin");

        Notify_me("From ProUnlockPhone: PayPal Case Closed :)", $text . "<br /><br />" . $body);
        exit();
    } elseif($_REQUEST['txn_type'] != "web_accept") {
        $text = "Notification concerning an existing transaction but not a new_case [txn_type] and not Canceled_Reversal [payment_status]<br /><br />";
        foreach ($_REQUEST as $key => $value) {
            $text .= $key . " : " . $value . "<br />";
        }
        Notify_me("New kind of PayPal transaction", $text);
        exit();
    }
}

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance, username, first_name, currency, email FROM users WHERE id = " . $_REQUEST['client']));

if(!isset($_REQUEST['payment_status'])) {
    $text = "Notification concerning a new transaction but without payment_status<br /><br />";
    foreach ($_REQUEST as $key => $value) {
        $text .= $key . " : " . $value . "<br />";
    }
    Notify_me("New kind of PayPal transaction", $text);
    exit();
} else {
    if($_REQUEST['payment_status'] == "Refunded") {
        $source = 'Recipient';
        $nature = 'PayPal reimbursement';
        $field = "debit";
        $description = "Refund credits";
        $subject = "💸 Credits refunded";
        $code = 4;
        $amount = number_format((-1 * ($_REQUEST['mc_gross'] + 0.5) * 100 / 105), 2, ".", ",");
        $balance =  number_format(($row['balance'] - $amount), 2, ".", ",");
    } elseif($_REQUEST['payment_status'] == "Reversed") {
        $source = 'Recipient';
        $nature = 'New PayPal dispute. Credits on hold.';
        $field = "debit";
        $description = "PayPal case";
        $subject = "Credits on hold";
        $code = 4;
        $amount = number_format((-1 * ($_REQUEST['mc_gross'] + $_REQUEST['mc_fee'] + 0.5) * 100 / 105), 2, ".", ",");
        $balance =  number_format(($row['balance'] - $amount), 2, ".", ",");
    } elseif($_REQUEST['payment_status'] == "Completed" || $_REQUEST['payment_status'] == "Pending") {
        $source = 'Sender';
        $nature = 'PayPal payment received';
        if($_REQUEST['payment_status'] == "Pending") $nature .= ' (not instant)';
        $field = "credit";
        $description = "Funds deposit";
        $subject = "💸 Funds added";
        $code = 2;
        $amount = number_format((($_REQUEST['mc_gross'] - 0.5) * 100 / 105), 2, ".", ",");
        $balance =  number_format(($row['balance'] + $amount), 2, ".", ",");
    } elseif($_REQUEST['payment_status'] == "Canceled_Reversal") {
        $source = 'Sender';
        $nature = 'PayPal dispute closed and credits released';
        $field = "credit";
        $description = "Case closed";
        $subject = "💸 Credits released";
        $code = 2;
        $amount = number_format((($_REQUEST['mc_gross'] + $_REQUEST['mc_fee'] - 0.5) * 100 / 105), 2, ".", ",");
        $balance =  number_format(($row['balance'] + $amount), 2, ".", ",");
    } else {
        $text = "Notification concerning a new transaction with a new kind of payment_status<br /><br />";
        foreach ($_REQUEST as $key => $value) {
            $text .= $key . " : " . $value . "<br />";
        }
        Notify_me("New kind of PayPal transaction", $text);
        exit();
    }
}
mysqli_query($DB->Link, "UPDATE users SET requestMoneyBack = 0, balance = \"" . $balance . "\" WHERE id = " . $_REQUEST['client']);
$maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $_REQUEST['client']));
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

$query = "INSERT INTO statement (relative_id, transaction_type, description, {$field}, balance_after, client, status, paypal, sender)
          VALUES (
            \"" . $next_id . "\",
            \"{$description}\",
            \"{$nature}\",
            \"" . $amount . "\",
            \"" . $balance . "\",
            " . $_REQUEST['client'] . ",
            {$code},
            \"{$_REQUEST['txn_id']}\",
            \"{$_REQUEST['payer_email']}\"
        )";
            
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

$body = "{$row['first_name']},<br /><br />";
$body .= "Your account has been {$field}ed: <b>{$amount} {$currency}</b><br /><br />";
$body .= "Your new balance is: <b>{$balance} {$currency}</b><br />";
$body .= "{$source}: {$_REQUEST['payer_email']}</b><br />";
$body .= "PayPal Transaction ID #{$_REQUEST['txn_id']}<br /><br />";
$body .= "Thank you for trusting our services. We appreciate your business.";

Notify_client($subject, $body, $row['email'], $row['first_name'], $_REQUEST['client'], "Balance update", "payment");

if($_REQUEST['mc_currency'] != $row['currency']) {
    Notify_me("Currency Mismatch", "Client: {$row['username']} [{$_REQUEST['client']}] :: {$row['email']}<br /><br />User's currency: {$row['currency']}<br />Payment's currency: {$_REQUEST['mc_currency']}<br />Transaction ID: {$_REQUEST['txn_id']}");
}
?>