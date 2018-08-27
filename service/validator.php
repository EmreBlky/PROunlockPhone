<?php
if(!isset($_POST['email'])) die('You are not allowed to access this file!');

set_time_limit(300);

$ch = curl_init();

$url = "https://email-checker.p.mashape.com/verify/v1";

$data = array("email" => $_POST['email']);
$query = $url . '?' . http_build_query($data, '?', '&');

$headers = array(
    "Accept: application/json",
    "X-Mashape-Key: SxWL70RIKOmsh9br2U0snF7LXoMVp1StY8BjsnrfYWKcYdejtG"
);

curl_setopt($ch, CURLOPT_URL, $query);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, '300');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

$body = curl_exec($ch);
$header = curl_getinfo($ch);
curl_close($ch);

$message = json_decode($body);

if($header["http_code"] != 200) {
    $response = array(
        "valid" => "KO",
        "message" => (isset($message->message) ? $message->message : "Validation server could not be reached.<br/>We will consider the given email address valid.")
    );
} else {
    $response = array(
        "valid" => ((isset($message->status) and $message->status == "invalid") ? "KO" : "OK"),
        "message" => ((isset($message->status) and $message->status == "invalid" and isset($message->reason)) ? $message->reason : "")
    );
}
echo json_encode($response);
?>