<?php
if(!isset($_POST['trx'])) {
    header("Location: https://www.prounlockphone.com/main/");
    exit();
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$_POST['trx'] = trim(strtoupper($_POST['trx']));
$ch = curl_init();
$url = "https://api-3t.paypal.com/nvp";
$data = array(
    "USER"          => "paypal_api1.prounlockphone.com",
    "PWD"           => "7ZKPEXS7C7FBUBNN",
    "SIGNATURE"     => "AO-497oLaisZNzeBV54Olr6gsvg8ALypoln3mFbqrk25t58s0VijlJND",
    "METHOD"        => "GetTransactionDetails",
    "TRANSACTIONID" => $_POST['trx'],
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
if(empty($body)) {
    require_once('../eMail.php');
    Notify_me('PayPal API issue', 'User: ' . $_SESSION['client_long'] . ' [' . $_SESSION['username'] . ' - ' . $_SESSION['client_id'] . ']
<br/>Transaction ID: ' . $_POST['trx'] . '
<br/>Bug line: 38');
    echo 'KO';
    exit();
}
parse_str($body,$body);
$body = json_decode(json_encode($body));

if($header["http_code"] != 200) {
    Notify_me('PayPal API issue', 'User: ' . $_SESSION['client_long'] . ' [' . $_SESSION['username'] . ' - ' . $_SESSION['client_id'] . ']
<br/>Transaction ID: ' . $_POST['trx'] . '
<br/>Bug line: 47');
    echo 'KO';
    exit();
} elseif($body->ACK != "Success") {
    echo 'INVALID';
    exit();
} else {
    if($body->CURRENCYCODE != $_SESSION['currency']) {
        echo 'CURMISMATCH';
        exit();
    }
    if($body->TRANSACTIONTYPE != 'sendmoney') {
        echo 'NOTPAYMENT';
        exit();
    }
    if($body->AMT != $_POST['amount']) {
        echo 'MISAMOUNT';
        exit();
    }
    if($body->PAYMENTSTATUS != 'Completed' || $body->PAYMENTTYPE != 'instant') {
        echo 'PENDING';
        exit();
    }
    if($body->TRANSACTIONID != $_POST['trx']) {
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
        echo 'NOTGIFT';
        exit();
    }
    if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM statement WHERE paypal = '" . $body->TRANSACTIONID . "'")) > 0) {
        echo 'DUPLICATE';
        exit();
    } elseif(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM quick_orders WHERE paypal = '" . $body->TRANSACTIONID . "'")) > 0) {
        echo 'DUPLICATE';
        exit();
    }






    require_once('../eMail.php');

    $amount = number_format($body->AMT, 2, ".", ",");
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance FROM users WHERE users.id = " . $_SESSION['client_id']));
    $balance =  number_format(($row['balance'] + $amount), 2, ".", ",");

    mysqli_query($DB->Link, "UPDATE users SET balance = \"" . $balance . "\" WHERE id = " . $_SESSION['client_id']);
    $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $_SESSION['client_id']));
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
    $next_id = strtoupper(substr($_SESSION['username'], 0, 3)) . $next_id;

    $query = "INSERT INTO statement (relative_id, transaction_type, description, credit, balance_after, client, status, paypal, sender, asgift)
          VALUES (
            \"" . $next_id . "\",
            \"Funds deposit\",
            \"PayPal payment as gift received\",
            \"" . $amount . "\",
            \"" . $balance . "\",
            " . $_SESSION['client_id'] . ",
            2,
            \"{$body->TRANSACTIONID}\",
            \"{$body->EMAIL}\",
            1
        )";

    mysqli_query($DB->Link, $query);

    $emailText = $_SESSION['client_short'] . ",<br /><br />";
    $emailText .= "Your account has been credited: <b>{$amount} {$_SESSION['symbol']}</b><br /><br />";
    $emailText .= "Your new balance is: <b>{$balance} {$_SESSION['symbol']}</b><br />";
    $emailText .= "Sender: {$body->EMAIL}</b><br />";
    $emailText .= "PayPal Transaction ID #{$body->TRANSACTIONID}<br /><br />";
    $emailText .= "Thank you for trusting our services. We appreciate your business.";

    Notify_client('💸 Funds added', $emailText, $_SESSION['client_email'], $_SESSION['client_short'], $_SESSION['client_id'], "Balance update", "payment");
    echo 'OK';
    exit();













    echo "EMAIL: " . $body->EMAIL;
    echo "<hr />";
    echo "FEEAMT after: " . $body->FEEAMT;
//    echo "RECEIVERBUSINESS: " . $body->RECEIVERBUSINESS;
//    echo "<hr />";
//    echo "RECEIVEREMAIL: " . $body->RECEIVEREMAIL;
//    echo "<hr />";
//    echo "RECEIVERID: " . $body->RECEIVERID;
//    echo "<hr />";
//    echo "<hr />";
//    echo "PAYERID: " . $body->PAYERID;
//    echo "<hr />";
//    echo "PAYERSTATUS: " . $body->PAYERSTATUS;
//    echo "<hr />";
//    echo "COUNTRYCODE: " . $body->COUNTRYCODE;
//    echo "<hr />";
//    echo "BUSINESS: " . $body->BUSINESS;
//    echo "<hr />";
//    echo "SHIPTONAME: " . $body->SHIPTONAME;
//    echo "<hr />";
//    echo "SHIPTOSTREET: " . $body->SHIPTOSTREET;
//    echo "<hr />";
//    echo "SHIPTOSTREET2: " . $body->SHIPTOSTREET2;
//    echo "<hr />";
//    echo "SHIPTOCITY: " . $body->SHIPTOCITY;
//    echo "<hr />";
//    echo "SHIPTOSTATE: " . $body->SHIPTOSTATE;
//    echo "<hr />";
//    echo "SHIPTOCOUNTRYCODE: " . $body->SHIPTOCOUNTRYCODE;
//    echo "<hr />";
//    echo "SHIPTOCOUNTRYNAME: " . $body->SHIPTOCOUNTRYNAME;
//    echo "<hr />";
//    echo "ADDRESSOWNER: " . $body->ADDRESSOWNER;
//    echo "<hr />";
//    echo "ADDRESSSTATUS: " . $body->ADDRESSSTATUS;
//    echo "<hr />";
//    echo "SHIPTOPHONENUM: " . $body->SHIPTOPHONENUM;
//    echo "<hr />";
//    echo "TIMESTAMP: " . $body->TIMESTAMP;
//    echo "<hr />";
//    echo "CORRELATIONID: " . $body->CORRELATIONID;
//    echo "<hr />";
//    echo "ACK: " . $body->ACK;
//    echo "<hr />";
//    echo "VERSION: " . $body->VERSION;
//    echo "<hr />";
//    echo "BUILD: " . $body->BUILD;
//    echo "<hr />";
//    echo "FIRSTNAME: " . $body->FIRSTNAME;
//    echo "<hr />";
//    echo "LASTNAME: " . $body->LASTNAME;
//    echo "<hr />";
//    echo "TRANSACTIONID: " . $body->TRANSACTIONID;
//    echo "<hr />";
//    echo "ORDERTIME: " . $body->ORDERTIME;
//    echo "<hr />";
//    echo "REASONCODE: " . $body->REASONCODE;
//    echo "<hr />";
//    echo "PROTECTIONELIGIBILITY: " . $body->PROTECTIONELIGIBILITY;
//    echo "<hr />";
//    echo "PROTECTIONELIGIBILITYTYPE: " . $body->PROTECTIONELIGIBILITYTYPE;
//    echo "<hr />";
//    echo "L_CURRENCYCODE0: " . $body->L_CURRENCYCODE0;
}

exit();

$ch = curl_init();
$clientID = "ASZr-HhUm5cRJ5wG7i-ASCnZiESM6v1ehnkZ7FTKWjr9F-sLg_DKM-cALMenGf0eCeuB1IX1ImH9rHN9";
$secret = "EIitAlcEx9kz04ChTHOjNXYVFyjrF5SjGUoJ5EoJxUJPb733rfMH0O5TL5LJ_CsKnvaByvFQhD0RW-s-";
$url = "https://api.paypal.com/v1/oauth2/token";

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $clientID . ":" . $secret);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

$body = curl_exec($ch);
$header = curl_getinfo($ch);
curl_close($ch);
if(empty($body)) die("Error: No response.");
$body = json_decode($body);

if($header["http_code"] != 200) die("Error: {$body->error_description}");
else {
//    echo "Token: " . $body->access_token;
//    echo "<hr />";
//    echo "Scope: " . $body->scope;
//    echo "<hr />";
//    echo "Nonce: " . $body->nonce;
//    echo "<hr />";
//    echo "Token Type: " . $body->token_type;
//    echo "<hr />";
//    echo "App ID: " . $body->app_id;
//    echo "<hr />";
//    echo "Expires in: " . $body->expires_in;
    $token = $body->access_token;
}

//$curl = curl_init("https://api.paypal.com/v1/payments/payment/042066995V5760459");
//curl_setopt($curl, CURLOPT_POST, false);
//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($curl, CURLOPT_HEADER, false);
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//    'Authorization: Bearer ' . $token,
//    'Accept: application/json',
//    'Content-Type: application/json'
//));
//$response = curl_exec($curl);
//$header = curl_getinfo($ch);
//curl_close($ch);
//print_r($header);
//echo "<hr />";
//print_r($response);
//if(empty($response)) die("Error: No response.");
//exit;




//
//class Paypal {
//    protected $_errors = array();
//    protected $_credentials = array(
//        'USER' => 'seller_1297608781_biz_api1.lionite.com',
//        'PWD' => '1297608792',
//        'SIGNATURE' => 'A3g66.FS3NAf4mkHn3BDQdpo6JD.ACcPc4wMrInvUEqO3Uapovity47p',
//    );
//    protected $_endPoint = 'https://api-3t.paypal.com/nvp';
//    protected $_version = '74.0';
//    public function request($method, $params = array()) {
//        $this -> _errors = array();
//        if( empty($method) ) { //Check if API method is not empty
//            $this -> _errors = array('API method is missing');
//            return false;
//        }
//
//        //Our request parameters
//        $requestParams = array(
//                'METHOD' => $method,
//                'VERSION' => $this -> _version
//            ) + $this -> _credentials;
//
//        //Building our NVP string
//        $request = http_build_query($requestParams + $params);
//
//        //cURL settings
//        $curlOptions = array (
//            CURLOPT_URL => $this -> _endPoint,
//            CURLOPT_VERBOSE => 1,
//            CURLOPT_SSL_VERIFYPEER => true,
//            CURLOPT_SSL_VERIFYHOST => 2,
//            CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', //CA cert file
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_POST => 1,
//            CURLOPT_POSTFIELDS => $request
//        );
//
//        $ch = curl_init();
//        curl_setopt_array($ch,$curlOptions);
//
//        //Sending our request - $response will hold the API response
//        $response = curl_exec($ch);
//
//        //Checking for cURL errors
//        if (curl_errno($ch)) {
//            $this -> _errors = curl_error($ch);
//            curl_close($ch);
//            return false;
//            //Handle errors
//        } else  {
//            curl_close($ch);
//            $responseArray = array();
//            parse_str($response,$responseArray); // Break the NVP string to an array
//            return $responseArray;
//        }
//    }
//}
//
//
//
////Our request parameters
//$requestParams = array(
//    'RETURNURL' => 'http://www.yourdomain.com/payment/success',
//    'CANCELURL' => 'http://www.yourdomain.com/payment/cancelled'
//);
//
//$orderParams = array(
//    'PAYMENTREQUEST_0_AMT' => '500',
//    'PAYMENTREQUEST_0_SHIPPINGAMT' => '4',
//    'PAYMENTREQUEST_0_CURRENCYCODE' => 'GBP',
//    'PAYMENTREQUEST_0_ITEMAMT' => '496'
//);
//
//$item = array(
//    'L_PAYMENTREQUEST_0_NAME0' => 'iPhone',
//    'L_PAYMENTREQUEST_0_DESC0' => 'White iPhone, 16GB',
//    'L_PAYMENTREQUEST_0_AMT0' => '496',
//    'L_PAYMENTREQUEST_0_QTY0' => '1'
//);
//
//$paypal = new Paypal();
//$response = $paypal -> request('SetExpressCheckout',$requestParams + $orderParams + $item);





?>