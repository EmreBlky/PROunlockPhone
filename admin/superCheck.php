<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

if(!isset($_POST['client'])) {
    $client = 58;
} elseif($_POST['client'] == "") {
    $client = 58;
} else {
    $client = $_POST['client'];
}
$details_client = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT username, type, currency, balance FROM users WHERE id = '" . $client . "'"));

$client_type = $details_client['type'];
$client_currency = $details_client['currency'];
$username = strtoupper(substr($details_client['username'], 0, 3));
$balance = $details_client['balance'];
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT short_name, regular_" . $client_currency . ", reseller_" . $client_currency . ", provider_details FROM services WHERE id = '" . $_POST['service'] . "'"));
$bargains = mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$client}' AND service = '{$_POST['service']}'");
$bargain = mysqli_fetch_assoc($bargains);
if(mysqli_num_rows($bargains) > 0) {
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
$imeis = explode("%0D%0A", urlencode($_POST['bulk']));
set_time_limit(300);
$responses = "";
$curl = 0;

while($curl < count($imeis)) {
    if($imeis[$curl] == "\r" || $imeis[$curl] == "") {
        $curl++;
        continue;
    }
    if(substr($imeis[$curl], -1) == "\r" || substr($imeis[$curl], -1) == "\n" || substr($imeis[$curl], -1) == "\r\n") $imeis[$curl] = substr($imeis[$curl], 0, -1);
    $imeis[$curl] = strtoupper($imeis[$curl]);
    
    $url = "http://sickw.com/api.php?key=L6W-74T-TCD-9CU-N9K-O3T-TVF-XOB&service={$row['provider_details']}&imei=" . $imeis[$curl];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, '300');

    $response = curl_exec($ch);
    $header  = curl_getinfo($ch);
    curl_close($ch);

    if($header["http_code"] != "301" && $header["http_code"] != "200") break;
    if(strstr($response, "Low Balance!") !== False || strstr($response, "Wrong API KEY!") !== False || strstr($response, "API KEY is Wrong!") !== False || strstr($response, "Service Down!") !== False) break;
    if($responses != "") {
        $responses .= "<hr />";
    }
    if($response == '<font color="red"><b>IMEI or SN is incorrect!</b></font>' or $response == '<span style="COLOR:RED">Error R01: Not Found!</SPAN>') {
//    if($response == '<font color="red"><b>IMEI or SN is incorrect!</b></font>') {
        $responses .= "<pre>IMEI/SN: " . $imeis[$curl] . (substr($imeis[$curl], -1) != "\r"? "<br />" : "") . $response . "</pre>";
    } else {
        $responses .= $response;
        mysqli_query($DB->Link, "UPDATE users SET balance = balance - {$price} WHERE id = " . $client);
        if($curl == 0) {
            $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM statement WHERE client = " . $client));
            $nextTransaction = NextID($maxid["relative_id"]);
            $maxid = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT MAX(relative_id) 'relative_id' FROM orders WHERE client = " . $client));
            $nextOrder = NextID($maxid["relative_id"]);
        } else {
            $nextOrder = NextID($nextOrder);
            $nextTransaction = NextID($nextTransaction);
        }
        $balance = $balance - $price;
        if(is_numeric($imeis[$curl]) && strlen($imeis[$curl]) == 15) {
            mysqli_query($DB->Link, "INSERT INTO orders (relative_id, admin_response_comments, status, order_date, IMEI, service, client, price) VALUES ('" . $nextOrder . "', '" . mysqli_real_escape_string($DB->Link, $response) . "', 'Success', '" . date("Y-m-d H:i:s") . "', '" . $imeis[$curl] . "', '" . $_POST['service'] . "', '" . $client . "', '" . $price . "')");
        } else {
            mysqli_query($DB->Link, "INSERT INTO orders (relative_id, admin_response_comments, status, order_date, SN, service, client, price) VALUES ('" . $nextOrder . "', '" . mysqli_real_escape_string($DB->Link, $response) . "', 'Success', '" . date("Y-m-d H:i:s") . "', '" . $imeis[$curl] . "', '" . $_POST['service'] . "', '" . $client . "', '" . $price . "')");
        }
        mysqli_query($DB->Link, "INSERT INTO statement (relative_id, status, order_id, transaction_type, description, credit, debit, balance_after, client)
                            VALUES (
                                \"" . $nextTransaction . "\",
                                1,
                                " . mysqli_insert_id($DB->Link) . ",
                                \"order completed\",
                                \"" . $row['short_name'] . " " . $imeis[$curl] . "\",
                                \"\",
                                \"" . $price . "\",
                                \"" . $balance . "\",
                                " . $client . "
                            )");
    }
    $curl++;
}
echo $responses != "" ? $responses : $response;

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
?>