<?php
if(!isset($_REQUEST['txn_id'])) {
    header("Location: ./");
    exit();
}

define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

require_once('eMail.php');
$treated = false;

if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM quick_orders WHERE paypal = '{$_REQUEST['txn_id']}'")) > 0) {
    $treated = true;
//if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM quick_orders WHERE paypal = '{$_REQUEST['txn_id']}'")) == 0) {
    if($_REQUEST['txn_type'] == "new_case") {
        $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT firstname, lastname, currency, email FROM quick_orders WHERE relative_id = '{$_REQUEST['relative_id']}'"));
        $text = "User: " . $row['firstname'] . " " . $row['lastname'] . " [" . $row['email'] . "]<br />";
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
        $body = '<p>' . ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ',</p><br>';
        $body .= '<p>Hmmm,</p>';
        $body .= '<p>We were notified that you started a PayPal dispute against us concerning this transaction:</p>';
        $body .= '<ul><li>Transaction ID: ' . $_REQUEST['txn_id'] . '</li>';
        $body .= '<li>Purchase date: ' . $_REQUEST['payment_date'] . '</li>';
        $body .= '<li>Purchased item: ' . $_REQUEST['item_name'] . '</li>';
        $body .= '<li>PayPal account (payer ID): ' . $_REQUEST['first_name'] . ' ' . $_REQUEST['last_name'] . ' [' . $_REQUEST['payer_email'] . ']</li>';
        $body .= '<li>Disputed amount: ' . $_REQUEST['amount'] . ' ' . $_REQUEST['mc_currency'] . '</li></ul><br>';
        $body .= '<p>We feel so sorry to hear that as such attitude from our partners has a great impact on our PayPal account standing and can affect our ability to keep it available to our community. Nevertheless, you are free to express your opinion and claim your money if you are not satisfied by our services.</p><br>';
        $body .= '<p>It is also sad to know because we usually respond promptly to all the "Money-back" requests that we receive and we process them within the first 12hrs upon reception.</p>';
        $body .= '<p>We were wondering why you did not attempt to get in touch with us prior to opening this case. A normal procedure, in respect to our terms and conditions that you agreed to at the time you created your account, is to first send a cancel request for all your orders in process (if there are any), then once canceled you proceed with a money-back request. These transactions are treated with no conditions.</p><br>';
        $body .= '<p>This situation can be easily solved. Once you close this dispute, you can immediately apply the procedure described above to get your money back in your PayPal account.</p>';
        $body .= '<p>You can disregard this message and stick to your decision and this won\'t be good for both of us. In such case, you must know that we won\'t comply with it and we will oppose to it using the agreement discussed above.</p><br>';
        $body .= '<p>We count on your understanding and your trust to quickly sort out this situation.</p>';
        $body .= '<p>Hope to hear good news from you soon.</p>';

        mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = 'New PayPal Case' WHERE relative_id = '{$_REQUEST['relative_id']}'");

        Notify_client("PayPal dispute against us", $body, $row['email'], ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Guest")), 0, "Account status", "admin", 1);

        Notify_me("From ProUnlockPhone: New PayPal Case - Quick Order", $text . "<br /><br />" . $body);
        exit();
    } elseif($_REQUEST['payment_status'] == "Canceled_Reversal") {
        $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT firstname, lastname, currency, email FROM quick_orders WHERE relative_id = '{$_REQUEST['relative_id']}'"));
        $text = "User: " . $row['firstname'] . " " . $row['lastname'] . " [" . $row['email'] . "]<br />";
        $text .= "Amount disputed: " . $_REQUEST['amount'];

        $body = '<p>' . ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ',</p><br>';
        $body .= '<p>We\'re so happy to know that you closed the PayPal dispute.</p>';
        $body .= '<p>Let us know how we can help now. If you still want to get your money back in your PayPal account, simply reply to this email.</p>';
        $body .= '<p>Keep in mind that you need first to cancel any active order to get its credits back in your account prior to sending the money-back request.</p><br>';
        $body .= '<p>We do value your trust and the confidence that you put on us and we promise to never disappoint you.</p>';
        $body .= '<p>We hope this incident will not occur again in the future as we will always be present to answer your questions and elucidate any confusion.</p><br>';
        $body .= '<p>Nice to do business with you.</p>';

        mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = 'PayPal Case Solved' WHERE relative_id = '{$_REQUEST['relative_id']}'");

        Notify_client("PayPal dispute closed", $body, $row['email'], ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Guest")), 0, "Account status", "admin", 1);

        Notify_me("From PROunlockPhone: PayPal Case Closed - Quick Order", $text . "<br /><br />" . $body);
        exit();
    } elseif($_REQUEST['txn_type'] != "web_accept") {
        $text = "Notification concerning an existing transaction but not a new_case [txn_type] and not Canceled_Reversal [payment_status]<br /><br />";
        foreach ($_REQUEST as $key => $value) {
            $text .= $key . " : " . $value . "<br />";
        }
        Notify_me("New kind of PayPal transaction for an existing transaction - Quick Order", $text);
        exit();
    }
}
//foreach ($_REQUEST as $key => $value) {
//    $text .= $key . " : " . $value . "<br />";
//}
//Notify_me("PayPal transaction's details - Quick Order", $text);

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT firstname, lastname, currency, email, smsEnabled, price FROM quick_orders WHERE relative_id = '{$_REQUEST['relative_id']}'"));
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
if($row['smsEnabled'] == 1) {
    $sms = 0.1;
} else {
    $sms = 0;
}

if(!isset($_REQUEST['payment_status'])) {
    $text = "Notification concerning a new transaction but without payment_status<br /><br />";
    foreach ($_REQUEST as $key => $value) {
        $text .= $key . " : " . $value . "<br />";
    }
    Notify_me("New kind of PayPal transaction without payment_status - Quick Order", $text);
    exit();
} else {
    if($_REQUEST['payment_status'] == "Refunded") {
        $amount = number_format((-1 * ($_REQUEST['mc_gross'] + 0.5 + $sms) * 100 / 105), 2, ".", ",");
        $payment_status = "Payment Refunded";
        $body = ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ",<br /><br />";
        $body .= "Your payment was refunded to {$_REQUEST['payer_email']}: <b>{$amount} {$currency}</b><br /><br />";
        $body .= "PayPal Transaction ID #{$_REQUEST['txn_id']}<br /><br />";
        $body .= "Thank you for trusting our services. We appreciate your business.";
        mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = '{$payment_status}' WHERE relative_id = '" . $_REQUEST['relative_id'] . "'");
    } elseif($_REQUEST['payment_status'] == "Reversed") {
        $amount = number_format((-1 * ($_REQUEST['mc_gross'] + $_REQUEST['mc_fee'] + 0.5 + $sms) * 100 / 105), 2, ".", ",");
        $payment_status = "New PayPal Case";
        $body = ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ",<br /><br />";
        $body .= "The payment status of your order <a href='https://www.prounlockphone.com/track/order-status.php?ref={$_REQUEST['relative_id']}' target='_blank'>{$_REQUEST['relative_id']}</a> is now on hold due to a new PayPal case.<br /><br />";
        $body .= "PayPal Transaction ID #{$_REQUEST['txn_id']}<br /><br />";
        $body .= "Thank you for trusting our services. We appreciate your business.";
        mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = '{$payment_status}' WHERE relative_id = '" . $_REQUEST['relative_id'] . "'");
    } elseif($_REQUEST['payment_status'] == "Pending") {
        $amount = number_format((($_REQUEST['mc_gross'] - 0.5 - $sms) * 100 / 105), 2, ".", ",");
        $payment_status = "Payment Pending Clearance";
        $body = ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ",<br /><br />";
        $body .= "We received your payment. However, we will start processing your order once this last becomes cleared.<br /><br />";
        $body .= "Amount: <b>{$amount} {$currency}</b><br />";
        $body .= "PayPal Transaction ID #{$_REQUEST['txn_id']}<br /><br />";
        $body .= "Thank you for trusting our services. We appreciate your business.";
        mysqli_query($DB->Link, "UPDATE quick_orders SET status = 'In process', alreadyStartedProcessing = 1, pintotop = 1, paypal = '{$_REQUEST['txn_id']}', payment_status = '{$payment_status}', amount = {$amount}, sender = '{$_REQUEST['payer_email']}' WHERE relative_id = '" . $_REQUEST['relative_id'] . "'");
    } elseif($_REQUEST['payment_status'] == "Completed") {
//    } elseif($_REQUEST['payment_status'] == "Completed" or $_REQUEST['payment_status'] == "Pending") {
        $amount = number_format((($_REQUEST['mc_gross'] - 0.5 - $sms) * 100 / 105), 2, ".", ",");
        $payment_status = "Payment Received";
        $body = ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ",<br /><br />";
        $body .= "We confirm reception of your payment. We will immediately start processing your order.<br /><br />";
        $body .= "Amount: <b>{$amount} {$currency}</b><br />";
        $body .= "PayPal Transaction ID #{$_REQUEST['txn_id']}<br /><br />";
        $body .= "Keep track of your order's progress by visiting this link: <a href='https://www.prounlockphone.com/track/order-status.php?ref={$_REQUEST['relative_id']}' target='_blank'>https://www.prounlockphone.com/track/order-status.php?ref={$_REQUEST['relative_id']}</a>";
        $body .= "<br /><br />Thank you for trusting our services. We appreciate your business.";
        mysqli_query($DB->Link, "UPDATE quick_orders SET status = 'In process', alreadyStartedProcessing = 1, pintotop = 1, paypal = '{$_REQUEST['txn_id']}', payment_status = '{$payment_status}', amount = {$amount}, sender = '{$_REQUEST['payer_email']}' WHERE relative_id = '" . $_REQUEST['relative_id'] . "'");
    } elseif($_REQUEST['payment_status'] == "Canceled_Reversal") {
        $amount = number_format((($_REQUEST['mc_gross'] + $_REQUEST['mc_fee'] - 0.5 - $sms) * 100 / 105), 2, ".", ",");
        $payment_status = "PayPal Case Solved";
        $body = ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ",<br /><br />";
        $body .= "Your payment has been released and no longer on hold.<br /><br />";
        $body .= "Amount: <b>{$amount} {$currency}</b><br />";
        $body .= "PayPal Transaction ID #{$_REQUEST['txn_id']}<br /><br />";
        $body .= "Thank you for trusting our services. We appreciate your business.";
        mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = '{$payment_status}' WHERE relative_id = '" . $_REQUEST['relative_id'] . "'");
    } else {
        $text = "Notification concerning a new transaction with a new kind of payment_status<br /><br />";
        foreach ($_REQUEST as $key => $value) {
            $text .= $key . " : " . $value . "<br />";
        }
        Notify_me("New kind of PayPal transaction with a new kind of payment_status - Quick Order", $text);
        exit();
    }
}

Notify_client("💸 " . $payment_status, $body, $row['email'], ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Guest")), 0, "Payment notification", "paypal", 1);

if($_REQUEST['mc_currency'] != $row['currency'] || $amount < $row['price']) {
    Notify_me("Currency/Amount Mismatch", "Client: {$row['firstname']} {$row['laststname']}<br /><br />Orders's currency: {$row['currency']}<br />Payment's currency: {$_REQUEST['mc_currency']}<br /><br />Orders's amount: {$row['price']}<br />Payment's amount: {$amount}<br />Transaction ID: {$_REQUEST['txn_id']}<br />Order ID: {$_REQUEST['relative_id']}");
} elseif($_REQUEST['payment_status'] == "Completed" and !$treated) {
//} elseif($_REQUEST['payment_status'] == "Completed" or $_REQUEST['payment_status'] == "Pending" and !$treated) {
    $rows = mysqli_query($DB->Link, "SELECT service_name, smsEnabled, sms, notes, quick_orders.IMEI 'IMEI', quick_orders.SN 'SN', services.id 'service', short_name, provider_details, currency, firstname, lastname, quick_orders.email 'email' FROM services, quick_orders WHERE quick_orders.relative_id = '" . $_REQUEST['relative_id'] . "' AND services.id = service AND delivery_time = 'Instant'");
    if(mysqli_num_rows($rows) > 0) {
        $row = mysqli_fetch_assoc($rows);
        $service = $row['service'];
        $client_currency = $row['currency'];
        switch($client_currency) {
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
        $email = $row['email'];

        $serial = ($row['IMEI'] == "" ? $row['SN'] : $row['IMEI']);
        $serial = strtoupper($serial);
        $url = "http://sickw.com/api.php?key=L6W-74T-TCD-9CU-N9K-O3T-TVF-XOB&service={$row['provider_details']}&imei=" . $serial;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, '300');
        $response = curl_exec($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);

        $subject = "Issue auto-processing paid check with API";

        $failed = false;
        $failure = "<br/>We will issue a full refund of your transaction.";
        $failure .= "<br/>This operation might take up to 12hrs.";
        $failure .= "<br/>Beyond that time, if you still can't see any reimbursement of your order, please <a href='https://www;prounlockphone.com/contact/' target='_blank'>contact us</a> immediately.";
        $failure .= "<br/>Our team is always ready to support.";
        $details = "<br /><br />Service: {$row['service_name']} [{$service}]
<br />Server-side service ID: Sickw [{$row['provider_details']}]
<br />IMEI/SN: {$serial}
<br />UID: {$_REQUEST['relative_id']}";
        $link = "<br /><br /><a href='https://www.prounlockphone.com/admin/resetServiceON.php?id={$service}' target='_blank'>&rarr; Click here to reset the service ON &larr;</a>";
        $apologies = "<br /><br /><a href='https://www.prounlockphone.com/admin/apologies.php?guest={$_REQUEST['relative_id']}&service={$service}' target='_blank'>&rarr; Click here to send him an invitation to reprocess the order &larr;</a>";
        $refresh = "<br /><br /><a href='https://www.prounlockphone.com/admin/setCompleted.php?UID={$_REQUEST['relative_id']}' target='_blank'>&rarr; Click here to send the client an invitation to check the status of his order and a completion notification &larr;</a>";

        if($header["http_code"] != "301" && $header["http_code"] != "200") {
            $failed = true;
            $response = "";
            Notify_me($subject, "Unexpected HTTP header received: <b>{$header["http_code"]}</b> (expecting '200' or '301').<br/><b style='color:crimson'>Must process the order manually and send the client a notification</b>." . $details . $refresh);
        } elseif(strstr($response, "IMEI or SN is incorrect!") !== False) {
            $failed = true;
            $response = "Problem processing " . $serial . ".<br />Either the IMEI/SN is incorrect, or the information is missing.<br/>Perhaps the given IMEI/SN is not supported (incompatible model/iOS)." . $failure;
            Notify_me($subject, "'IMEI or SN is incorrect!' received.<br/>Order already rejected.<br/><b style='color:crimson'>Must refund the transaction</b>." . $details);
        } elseif(strstr($response, "Error R01: Not Found!") !== False) {
            $failed = true;
            $response = "Problem processing " . $serial . ".<br />IMEI/SN not found!<br/>Kindly, do not submit again." . $failure;
            Notify_me($subject, "'Error R01: Not Found!' received.<br/>Order already rejected.<br/><b style='color:crimson'>Must refund the transaction</b>." . $details);
        } elseif(strstr($response, "Error S02: Service ID is Wrong!") !== False) {
            $failed = true;
            mysqli_query($DB->Link, "UPDATE services SET service_status = 0 WHERE id = " . $service);
            $response = "This service appears to be down!<br />Please try again later." . $failure;
            Notify_me($subject, "Sickw Service ID Not Recognized at the server side.<br/>Order already rejected.<br/><b style='color:crimson'>Must refund the transaction</b>.<br/>The service was updated 'TURNED DOWN' automatically.<br/>Please check whether Sickw has removed this service or not.<br/>If you want to set it back active, simply click the link below to re-list it." . $details . $link);
        } elseif(strstr($response, "Service Down!") !== False) {
            $failed = true;
            mysqli_query($DB->Link, "UPDATE services SET service_status = 0 WHERE id = " . $service);
            $response = "This service appears to be down!<br />Please try again later." . $failure;
            Notify_me($subject, "Sickw service is set OFF 'Service Down!'.<br/>Order already rejected.<br/><b style='color:crimson'>Must refund the transaction</b>.<br/>The service was updated 'TURNED DOWN' automatically.<br/>Please check whether Sickw is now back stable on it or not.<br/>If yes, simply click the link below to re-list it." . $details . $link);
        } elseif(strstr($response, "Low Balance!") !== False) {
            $failed = true;
            $response = "";
            Notify_me($subject, "Sickw balance is low.<br/>Must add credits immediately.<br/><b style='color:crimson'>Must process the order manually and send the client a notification</b>." . $details . $apologies);
        } elseif(strstr($response, "Wrong API KEY!") !== False || strstr($response, "API KEY is Wrong!") !== False) {
            $failed = true;
            $response = "";
            Notify_me($subject, "A problem with the API key arised.<br/>Below is the entire message received.<hr/>{$response}<hr/><br/><b style='color:crimson'>Must process the order manually and send the client a notification</b>.<br/>" . $details . $apologies);
        } elseif(strstr($response, "page after 5 minutes") !== False) {
            $failed = true;
            $response = "";
            Notify_me($subject, "Sickw order that requires update after 5 minutes.<br/><b style='color:crimson'>Must process the order manually and send the client a notification</b>." . $details . $refresh);
        }

        $response = nl2br(cleanResponse($response, $serial));
        $order = ": " . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . " " . $row['short_name'];
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
            'url': 'https://www.prounlockphone.com/track/order-status.php?ref={$_REQUEST['relative_id']}',
            'target': 'https://www.prounlockphone.com/track/order-status.php?ref={$_REQUEST['relative_id']}',
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
" . ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ",<br /><br />";

        if($failed) {
            if($response != "") $response = "<div style='color:white;padding:10px;background-color: lightseagreen;border:solid 1px black;border-radius: 5px'>" . $response . "</div>";
            $subject = "😤 Order Rejected";
            mysqli_query($DB->Link, "UPDATE quick_orders SET pintotop = 1, status = 'Rejected', admin_response_comments = '" . mysqli_real_escape_string($DB->Link, $response) . "' WHERE relative_id = '{$_REQUEST['relative_id']}'");
            $body .= '<b>We are sorry!</b><br />Your order has been rejected.<br />You might find additional details about the reasons in the "Admin Comments" section below.<br /><br />';
        } else {
            $subject = "💪 Order Completed";
            mysqli_query($DB->Link, "UPDATE quick_orders SET pintotop = 0, status = 'Success', admin_response_comments = '" . mysqli_real_escape_string($DB->Link, $response) . "' WHERE relative_id = '{$_REQUEST['relative_id']}'");
            $body .= '<b>CONGRATULATIONS!!!</b><br />Your order has been successfully processed.<br /><br />';

            if($row['smsEnabled'] == "1") {
                $text = ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Hi")) . ", your order " . $_REQUEST['relative_id'] . " has been successfully processed.
Check your eMailbox for additional details.

PROunlockPhone.";
                if(strlen($text) > 160) $text = substr($text, 0, 157) . "...";
                require_once('SMS.php');
                if(!smsNotify($subject, $text, $row['sms'], 0, "Order status", 0)) {
                    $response .= "\n\nSMS Error generated: There was a problem while attempting to send text to +" . substr($row['sms'], 0, 1);
                    for($i = 0; $i < strlen($row['sms']) - 5; $i++) {
                        $response .= "&bull;";
                    }
                    $response .= substr($row['sms'], -4) . " [unreachable destination]";
                }
            }
        }
        $body .= "<u>Order ID:</u> <b>" . $_REQUEST['relative_id'] . "</b><br />";
        $body .= $row['IMEI'] != "" ? "<u>IMEI:</u> <b>" . $row['IMEI'] . "</b><br />" : "";
        $body .= $row['SN'] != "" ? "<u>S/N:</u> <b>" . $row['SN'] . "</b><br />" : "";
        $body .= "<u>Service:</u> <b>#" . $row['service'] . " " . $row['short_name'] . "</b><br /><br />
                      <u>Admin Comments:</u><br />" . $response . "<br /><br />";
        $body .= $row["notes"] != "" ? "<u>Personal Notes:</u><br />" . nl2br($row["notes"]) : "";
        $body .= "<br /><br />More details can be found here: <a href='https://www.prounlockphone.com/track/order-status.php?ref={$_REQUEST['relative_id']}' target='_blank'>https://www.prounlockphone.com/track/order-status.php?ref={$_REQUEST['relative_id']}</a>";
        $body .= "<br /><br /><br />Thanks again for your business.";
        if($response != "") Notify_client($subject . $order, $body, $row['email'], ($row['firstname'] != "" ? $row['firstname'] : ($row['lastname'] != "" ? $row['lastname'] : "Guest")), 0, "Order status", "orders", 3);
    }
}
?>