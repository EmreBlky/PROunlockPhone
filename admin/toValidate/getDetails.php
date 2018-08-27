<?php
if(!isset($_GET['trx'])) {
    header("Location: https://www.prounlockphone.com/main/");
    exit();
}

define('INCLUDE_CHECK', true);
require '../../common.php';
require '../../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../../login");
    exit();
}

function crashIt($title, $text) {
    echo "<div class='modal-header'>
	<button aria-hidden='true' class='close' data-dismiss='modal' type='button'>×</button>
	<h4 class='modal-title'>{$title}</h4>
    </div>
    <div class='modal-body'>
	<p class='info-row'>{$text}</p>
    </div>
    <div class='modal-footer'>
	<button class='btn btn-default' data-dismiss='modal' type='button'>Close</button>
    </div>";
    exit();
}

$_GET['trx'] = strtoupper($_GET['trx']);
$ch = curl_init();
$url = "https://api-3t.paypal.com/nvp";
$data = array(
    "USER"          => "paypal_api1.prounlockphone.com",
    "PWD"           => "7ZKPEXS7C7FBUBNN",
    "SIGNATURE"     => "AO-497oLaisZNzeBV54Olr6gsvg8ALypoln3mFbqrk25t58s0VijlJND",
    "METHOD"        => "GetTransactionDetails",
    "TRANSACTIONID" => $_GET['trx'],
    "VERSION"       => "74.0"
);

$query = http_build_query($data, '?', '&');

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

$body = curl_exec($ch);
$header = curl_getinfo($ch);
curl_close($ch);

if(empty($body)) crashIt('Error contacting PayPal API', 'Empty body returned by the API query.');

parse_str($body,$body);
$body = json_decode(json_encode($body));

if($header["http_code"] != 200) crashIt('Bad Header Returned', 'http_code != 200');
elseif($body->ACK != "Success") crashIt('Invalid Transaction', 'Not existent transaction ID.<br/>Check your spelling.');
else {
    $issue = "";
    if($body->CURRENCYCODE != $_GET['currency']) {
        $issue = "<a class='text-danger'>Currency mismatch: PayPal Records [{$body->CURRENCYCODE}] ↭ [{$_GET['currency']}] User's Currency";
    }
    if($body->TRANSACTIONTYPE != 'sendmoney') {
        $issue = "<a class='text-danger'>Transaction does not refer to money sent (very likely received, refund, etc.).";
    }
    if($body->AMT != $_GET['amount']) {
        $issue = "<a class='text-danger'>Transaction's amount is different from the entered amount: PayPal Records [{$body->AMT}] ↭ [{$_GET['amount']}] User's Data";
    }
    if($body->PAYMENTSTATUS != 'Completed' || $body->PAYMENTTYPE != 'instant') {
        $issue = "<a class='text-danger'>Payment is still not completed (pending).";
    }
    if($body->TRANSACTIONID != $_GET['trx']) {
        $ch = curl_init();
        $url = "https://api-3t.paypal.com/nvp";
        $data = array(
            "USER"          => "paypal_api1.prounlockphone.com",
            "PWD"           => "7ZKPEXS7C7FBUBNN",
            "SIGNATURE"     => "AO-497oLaisZNzeBV54Olr6gsvg8ALypoln3mFbqrk25t58s0VijlJND",
            "METHOD"        => "GetTransactionDetails",
            "TRANSACTIONID" => $body->TRANSACTIONID,
            "VERSION"       => "74.0"
        );

        $query = http_build_query($data, '?', '&');

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

        $body = curl_exec($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        parse_str($body,$body);
        $body = json_decode(json_encode($body));
    }
    if(isset($body->FEEAMT) and $body->FEEAMT != 0 and $body->FEEAMT != '') {
        $issue = "<a class='text-danger'>There are fees deducted by PayPal. Therefore this transaction was not sent as gift.";
    }

    echo "<div class='modal-header'>
        <button aria-hidden='true' class='close toclose' data-dismiss='modal' type='button'>×</button>
        <h4 class='modal-title'>PayPal Transaction ID [{$_GET['trx']}]</h4>
    </div>
    <div class='modal-body'>
        <p class='info-row'><b>Transaction Date</b>: {$body->ORDERTIME}</p>
        <hr>
        <p class='info-row'><b>Sender</b>: {$body->FIRSTNAME} {$body->LASTNAME}</p>
        <p class='info-row'><b>Sender ID</b>: {$body->EMAIL}</p>
        <p class='info-row'><b>Sender Status</b>: " . ucfirst($body->PAYERSTATUS) . "</p>
        ";
    if(isset($body->BUSINESS)) {
        echo "<p class='info-row'><b>Sender Business</b>: {$body->BUSINESS}</p>
        ";
    }
    echo "<p class='info-row'><b>Sender Country</b>: {$body->COUNTRYCODE}</p>
        <hr>
        <p class='info-row'><b>Amount</b>: {$body->AMT} {$body->CURRENCYCODE}</p>
        <p class='info-row'><b>Transaction Type</b>: " . ucfirst($body->TRANSACTIONTYPE) . "</p>
        <p class='info-row'><b>Transaction Status</b>: {$body->PAYMENTSTATUS}</p>
        <p class='info-row'><b>Payment Type</b>: " . ucfirst($body->PAYMENTTYPE) . "</p>       
        ";
    if(isset($body->FEEAMT)) {
        echo "<p class='info-row'><b>Fee Amount</b>: {$body->FEEAMT}</p>
        ";
    }
    if($issue) {
        echo "<hr>
        <p class='info-row'>{$issue}</p>";
    }
    echo "
    </div>
    <div class='modal-footer'>
	    <form style='margin:0px' method='POST' action='https://www.prounlockphone.com/admin/toValidate/validate.php'>
	    <button class='btn btn-default toclose' data-dismiss='modal'>Close</button>
	    <input type='hidden' value='{$_GET['trx']}' name='trx' />
	    ";
    if($_GET['valid'] == '0') {
        echo "<input class='btn btn-success toclose' type='submit' value='Validate' />";
    } else {
        echo "<input class='btn btn-danger toclose' type='submit' value='Disapprove' />";
    }
    echo "</form>
    </div>
    <script>
    $(function() {
        $('form').submit(function() {
            $('.toclose').hide();
            $(this).closest('div').append('<img class=\"loading\" style=\"width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);\" src=\"https://www.prounlockphone.com/images/loading.gif\">');
        });
    });
</script>";
}

?>