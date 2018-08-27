<?php
if(!isset($_POST['name'])) {
    header("Location: https://www.prounlockphone.com/contactus/");
    exit();
}
if(!isset($_POST['g-recaptcha-response'])) {
    header("Location: https://www.prounlockphone.com/contactus/?retry=yes");
    exit();
}
$url = "https://www.google.com/recaptcha/api/siteverify?";
$fields = array(
    'secret' => '6Lc4RREUAAAAAEva9nl5eNzl6agr18bl1bv0bzZu',
    'response' => $_POST['g-recaptcha-response'],
    'remoteip' => $_SERVER['REMOTE_ADDR']
);
$fields_string = '';
foreach($fields as $key=>$value) {
    $fields_string .= $key.'='.$value.'&';
}
rtrim($fields_string, '&');

$url = $url . $fields_string;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, '300');
curl_setopt($ch, CURLOPT_HEADER, false);
$response = curl_exec($ch);
curl_close($ch);
$response = json_decode($response);
if(!$response->success) {
    header("Location: https://www.prounlockphone.com/contactus/?retry=yes");
    exit();
}

define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$_POST['name'] = mysqli_real_escape_string($DB->Link, $_POST['name']);
$_POST['guest_email'] = mysqli_real_escape_string($DB->Link, $_POST['guest_email']);
$_POST['subject'] = mysqli_real_escape_string($DB->Link, $_POST['subject']);
//$_POST['message'] = mysqli_real_escape_string($DB->Link, $_POST['message']);
$details = json_decode(file_get_contents("http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}"));
if($details->status != "success") $details = "";
else $details = " - {$details->city} / {$details->regionName} / {$details->country} [$details->countryCode]";

require_once('../eMail.php');
Notify_me('Message from ' . $_POST['name'] . ' :: ' . $_POST['subject'], "Message from " . $_POST['name'] . ":<br /><br />
IP address: " . $_SERVER['REMOTE_ADDR'] . "{$details}<br />
User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "<br /><br /><hr />
Name: " . $_POST['name'] . "<br />
eMail address: " . $_POST['guest_email'] . "<br />
Subject: <b>" . $_POST['subject'] . "</b><br /><br />
<u>Message</u>:<br />" . nl2br($_POST['message']), $_POST['guest_email'], $_POST['name']);
$body = $_POST['name'].',<br /><br />Thank you for contacting our support team.<br />
        One of our associate should get in touch with you shortly.<br />
        We highly value your business and rely on your feedback to improve our work.<br /><br />
        <hr /><div align="center">COPY OF YOUR MESSAGE</div><hr />
        <pre style="font-size:16px"><u>Name</u>: ' . $_POST['name'] . '
<u>eMail address</u>: ' . $_POST['guest_email'] . '
<u>Subject</u>: ' . $_POST['subject'] . '
<u>message</u>:

' . nl2br($_POST['message']) . '</pre><hr /><div align="center">END OF YOUR MESSAGE</div><hr />';
Notify_client('📧 Thank you for contacting our support', $body, $_POST['guest_email'], $_POST['name'], $_SESSION['client_id'], "Query status", "support");

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("eMail Received") ?>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php header_render("contactus") ?>
            <div class="clear"></div>
            <section id="content" style="margin-bottom: 0px;">
		        <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                            <div align="center" style="font-size:36px;width:100%">
                                <h2 style='color:crimson'>You're all caught up!</h2>
                                <hr /><h1>We can hear you...</h1><hr />
                                <div class="small">
                                A copy of your message was sent to your email box<br />(check your spam if necessary)<br />We confirm reception of your query and the administrator should get back to you shortly.<br />Thank you for contacting us.
                                </div>
                                <br/>
                            </div>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
    </body>
</html>