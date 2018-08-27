<?php

if(!isset($_POST['imei'])) die("Can't run this file directly");

set_time_limit(300);
$url = "https://www.att.com/apis/deviceunlock/csrfguard/JavaScriptServlet";

$headers = array(
    "Accept: application/javascript, */*;q=0.8",
    "Referer: https://www.att.com/deviceunlock/?",
    "Accept-Language: fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7,ar;q=0.6,pl;q=0.5,fr-CA;q=0.4",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36",
    "Accept-Encoding: gzip, deflate",
    "Connection: keep-alive",
    "Cookie: UUID=961ff735-9144-a101-ea05-cab898d98d72; RT=\"sl=3&ss=1520084172761&tt=9555&obo=0&sh=1520084185687%3D3%3A0%3A9555%2C1520084184127%3D2%3A0%3A8993%2C1520084181195%3D1%3A0%3A8431&dm=att.com&si=a0120197-a411-48b3-bde1-c10db9cf1fb0&bcn=%2F%2F364bf6cc.akstat.io%2F\"; _4c_mc_=de4e4ed67d0bc07a40fc2780cb90c42b; AMCV_55633F7A534535110A490D44%40AdobeOrg=2096510701%7CMCIDTS%7C17594%7CMCMID%7C77428242508833975343156106848530618559%7CMCAAMLH-1520688981%7C6%7CMCAAMB-1520688982%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCCIDH%7C-1685556446%7CMCOPTOUT-1520091381s%7CNONE%7CMCAID%7CNONE%7CMCSYNCSOP%7C411-17601%7CvVersion%7C2.0.0; TLTSID=D0D375094B2F06AF28C8FEFB3F45C08D; aam_uuid=77163966877714518363147613930665889437"
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, '300');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_ENCODING , "");

$response = curl_exec($ch);
$header = curl_getinfo($ch);
curl_close($ch);

if($header["http_code"] != 200) exit;

$pos = strpos($response, 'injectTokens("OWASP-CSRFTOKEN",');
$token = substr($response, $pos);
$token = explode('"', $token);


$url = "https://www.att.com/apis/deviceunlock/OCEUnlockOrder/orderFlow";
$data = '{"orderFlowRequestDO":{"attCustomer":false,"military":false,"currentFlow":"IMEI_VERIFICATION_FLOW","ctn":"","imei":"' . $_POST['imei'] . '","reCaptcha":{},"browserId":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36"}}';

$headers = array(
    "Accept: application/json, text/plain, */*",
    "Origin: https://www.att.com",
    "X-Requested-With: OWASP CSRFGuard Project",
    "OWASP-CSRFTOKEN: " . $token[3],
//    "OWASP-CSRFTOKEN: 63646517-a2ba-4dfe-bed0-e263c445dda7",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36",
    "Content-Type: application/json;charset=UTF-8",
    "Referer: https://www.att.com/deviceunlock/",
    "Accept-Encoding: gzip, deflate, br",
    "Accept-Language: fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7,ar;q=0.6,pl;q=0.5,fr-CA;q=0.4",
    "Cookie: TLTSID=E8DEFF94DB76C1855802C760BE44E54C; UUID=b7b6f57d-9252-a172-7308-18ae6ca89316; sto-25115-sgONEMAP_7010=PMKOAEICGCBL; AMCVS_55633F7A534535110A490D44%40AdobeOrg=1; _4c_mc_=0c7d9cb3e0363eaebc03dcf6adb38fa4; s_cc=true; AMCV_55633F7A534535110A490D44%40AdobeOrg=2096510701%7CMCIDTS%7C17593%7CMCMID%7C67559272825966017554127024124920832452%7CMCAAMLH-1520592029%7C6%7CMCAAMB-1520592038%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCCIDH%7C572885034%7CMCOPTOUT-1519994429s%7CNONE%7CMCAID%7CNONE%7CMCSYNCSOP%7C411-17600%7CvVersion%7C2.0.0; aam_uuid=67474613285386510654117432275534002150; ECOM_GTM=owffld; JSESSIONID=6c4cvgfolms91vz20p9nyp4sa",
    "Content-Length: " . strlen($data),
    "Connection: keep-alive"
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, '300');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_ENCODING , "");

$response = curl_exec($ch);
$header = curl_getinfo($ch);

if($header["http_code"] == 200) {
    $pos = strpos($response, '{');
    $head = substr($response, 0, $pos);
    $body = substr($response, $pos);
    $response = json_decode($body);
    if (isset($response->orderFlowResponseDO->make)) {
        echo $response->orderFlowResponseDO->make . ' ' . $response->orderFlowResponseDO->model;
    } else {
//        echo $response->orderFlowResponseDO->validationErrors->errorList->errorDescription;
    }
}
curl_close($ch);
?>