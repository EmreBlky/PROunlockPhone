<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/online.php';
$DB = new DBConnection();

$client = $_SESSION['client_id'];
$service = $_POST['service'];

$details_client = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT username, type, first_name, email, balance FROM users WHERE id = '" . $client . "'"));
$pending_payments = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(credit) 'credits' FROM statement WHERE client = '" . $client . "' AND asGift = 1 AND validated = 0"));
$client_type = $details_client['type'];

$username = strtoupper(substr($details_client['username'], 0, 3));
$first_name = $details_client['first_name'];
$email = $details_client['email'];
$balance = $details_client['balance'] - $pending_payments['credits'];
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT service_name, short_name, regular_{$_SESSION['currency']}, reseller_{$_SESSION['currency']}, provider_details FROM services WHERE id = " . $service));
$bargains = mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = {$client} AND service = {$service}");
$bargain = mysqli_fetch_assoc($bargains);
if(mysqli_num_rows($bargains)) {
    if($bargain['nature'] == 'impose') {
        if($bargain['price'] < $row["{$client_type}_{$_SESSION['currency']}"]) {
            $price = $row["{$client_type}_{$_SESSION['currency']}"];
        } else {
            $price = $bargain['price'];
        }
    } else {
        if($row["{$client_type_}_{$_SESSION['currency']}"] < $bargain['price']) {
            $price = $row["{$client_type}_{$_SESSION['currency']}"];
        } else {
            $price = $bargain['price'];
        }
    }
} else {
    $price = $row["{$client_type}_{$_SESSION['currency']}"];
}

$serials = explode(PHP_EOL, $_POST['serials']);
$maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $client));
$nextTransaction = $maxid["relative_id"];
$maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM orders WHERE client = " . $client));
$nextOrder = $maxid["relative_id"];
$nbr = 0;

$warnings = [];
$results = [];
$messages = [];

require '/home/khoubeib/public_html/eMail.php';

foreach($serials as $serial) {
    if(ord(substr($serial, -1)) == 13 || ord(substr($serial, -1)) == 10) $serial = substr($serial, 0, -1);
    if(strlen($serial) < 8) {
        if(strlen($serial) > 1) {
            $warnings[] = "Check your S/N or IMEI: " . strtoupper($serial);
        }
        continue;
    }
    $serial = strtoupper($serial);

    if($price > $balance and $price > 0) {
        $warnings[] = "Insufficient credits to process " . $serial;
        if($pending_payments['credits'] > 0) {
            $warnings[] = $pending_payments['credits'] . " {$_SESSION['symbol']} sent as gift still under review.";
        }
        break;
    }

    $url = "http://sickw.com/api.php?key=L6W-74T-TCD-9CU-N9K-O3T-TVF-XOB&service={$row['provider_details']}&imei=" . $serial;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, '300');
//    $response = 'OK for ' . $serial . '<br />';

    $response = curl_exec($ch);
    $header  = curl_getinfo($ch);
    curl_close($ch);

    $subject = "Sickw problem while checking from account";
    $details = "<br /><br />User: {$details_client['username']}
<br />Service: {$row['service_name']} [{$service}]
<br />Server-side service ID: Sickw [{$row['provider_details']}]
<br />IMEI/SN: {$serial}";
    $link = "<br /><br /><a href='https://www.prounlockphone.com/admin/resetServiceON.php?id={$service}' target='_blank'>&rarr; Click here to reset the service ON &larr;</a>";
    $apologies = "<br /><br /><a href='https://www.prounlockphone.com/admin/apologies.php?client={$client}&service={$service}' target='_blank'>&rarr; Click here to send him an invitation to reprocess the order &larr;</a>";

    if($header["http_code"] != "301" && $header["http_code"] != "200") {
        $warnings[] = "Network problem processing " . $serial;
        Notify_me($subject, "Unexpected HTTP header received: <b>{$header["http_code"]}</b> (expecting '200' or '301')." . $details);
        continue;
    } elseif(strstr($response, "IMEI or SN is incorrect!") !== False) {
        $warnings[] = "Problem processing " . $serial . ".<br />Either the IMEI/SN is incorrect, or the information is missing.";
        continue;
    } elseif(strstr($response, "Error R01: Not Found!") !== False) {
        $warnings[] = "Problem processing " . $serial . ".<br />IMEI/SN not found!<br/>Kindly, do not submit again.";
        continue;
    } elseif(strstr($response, "Error S02: Service ID is Wrong!") !== False) {
        mysqli_query($DB->Link, "UPDATE services SET service_status = 0 WHERE id = " . $service);
        Notify_me($subject, "Sickw Service ID Not Recognized at the server side.<br/>The service was updated 'TURNED DOWN' automatically.<br/>Please check whether Sickw has removed this service or not.<br/>If you want to set it back active, simply click the link below to re-list it." . $details . $link);
        $warnings[] = "This service appears to be down!<br />Please refresh the page or try again later.";
        break;
    } elseif(strstr($response, "Service Down!") !== False) {
        mysqli_query($DB->Link, "UPDATE services SET service_status = 0 WHERE id = " . $service);
        Notify_me($subject, "Sickw service is set OFF 'Service Down!'.<br/>The service was updated 'TURNED DOWN' automatically.<br/>Please check whether Sickw is now back stable on it or not.<br/>If yes, simply click the link below to re-list it." . $details . $link);
        $warnings[] = "This service appears to be down!<br />Please refresh the page or try again later.";
        break;
    } elseif(strstr($response, "Low Balance!") !== False) {
        Notify_me($subject, "Sickw balance is low.<br/>Must add credits immediately." . $details . $apologies);
        $warnings[] = "Hmm shame on us!<br />Our API server appears to be temporarily down.<br/>We are working on fixing this tiny issue. It should back ON soon.<br/>Please try again later.";
        break;
    } elseif(strstr($response, "Wrong API KEY!") !== False || strstr($response, "API KEY is Wrong!") !== False) {
        Notify_me($subject, "A problem with the API key arised.<br/>Below is the entire message received.<hr/>{$response}<hr/><br/>" . $details . $apologies);
        $warnings[] = "Hmm shame on us!<br />Our API server appears to be temporarily down.<br/>We are working on fixing this tiny issue. It should back ON soon.<br/>Please try again later.";
        break;
    }

    $UID = "";
    while($UID == "") {
        $UID = generateRandomString();
        if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE UID = '{$UID}'")) > 0) $UID = "";
    }
    $refresh = "<br /><br /><a href='https://www.prounlockphone.com/admin/refresh.php?client={$client}&UID={$UID}' target='_blank'>&rarr; Click here to send him an invitation to check the status of his order &larr;</a>";
    if(strstr($response, "page after 5 minutes") !== False) {
        Notify_me($subject, "Sickw order that requires update after 5 minutes." . $details . $refresh);
        $warnings[] = "Hmm shame on us!<br />Our API server is a little slow on this service.<br/>We are still processing your request.<br/>Please check your Orders History page 5 minutes from now and you should find an update, or visit this link:<br/><a href='https://www.prounlockphone.com/track/order-status.php?ref={$UID}' target='_blank'>Order Status</a>";
        $response = "Your request requires more time to be processed.<br/>Please check the progress of your order in 5 minutes.<br/><a href='https://www.prounlockphone.com/track/order-status.php?ref={$UID}' target='_blank'>Order Status</a><br/><a href='https://www.prounlockphone.com/orders/' target='_blank'>Orders History</a>";
    }

    mysqli_query($DB->Link, "UPDATE users SET balance = balance - {$price} WHERE id = " . $client);
    $balance -= $price;
    $messages[] = $serial . " processed";
    $results[] = cleanResponse($response, $serial);
    $nbr++;
    $nextOrder = NextID($nextOrder);
    $nextTransaction = NextID($nextTransaction);
    $query = "INSERT INTO orders (UID, relative_id, admin_response_comments, status, order_date, ";
    if(is_numeric($serial) && strlen($serial) == 15) {
        $query .= "IMEI";
    } else {
        $query .= "SN";
    }
    $query .= ", service, client, price) VALUES ('{$UID}', '{$nextOrder}', '" . mysqli_real_escape_string($DB->Link, cleanResponse($response, $serial)) . "', 'Success', '" . gmdate("Y-m-d H:i:s") . "', '" . $serial . "', '" . $service . "', '" . $client . "', '" . $price . "')";
    mysqli_query($DB->Link, $query);
    mysqli_query($DB->Link, "INSERT INTO statement (relative_id, status, order_id, transaction_type, description, debit, balance_after, client) VALUES ('" . $nextTransaction . "', 1, " . mysqli_insert_id($DB->Link) . ", 'order completed', '" . $row['short_name'] . " " . $serial . "', " . $price . ", " . $balance . ", " . $client . ")");

}

if($nbr == 0) {
    $type = 0;
    $warnings[] = "There was a problem with your order.<br /> Double-check your IMEIs/SNs and ensure you have sufficient credits.";
} else {
    $type = 1;
    $messages[] = "Service cost " . ($nbr * $price) . " {$_SESSION['symbol']}";
    $messages[] = "New balance {$balance} {$_SESSION['symbol']}";
}
$response = array(
    "type" => $type,
    "msg" => $messages,
    "warnings" => $warnings,
    "data" => [
        "balance" => "BALANCE {$balance} {$_SESSION['symbol']}",
        "color" => ($balance < 0 ? "red" : $balance < 10 ? "orange" : "green")
    ],
    "results" => $results
);
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