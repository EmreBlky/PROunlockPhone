<?php
if(!isset($_POST['g-recaptcha-response'])) {
    header("Location: https://www.prounlockphone.com/refund/?retry=yes");
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
    header("Location: https://www.prounlockphone.com/refund/?retry=yes");
    exit();
}

define('INCLUDE_CHECK', true);
require '../../common.php';
require '../../online.php';
$DB = new DBConnection();

$query = "SELECT requestMoneyBack, balance
FROM users
WHERE id = " . $_SESSION['client_id'];
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, $query));
if($row['requestMoneyBack'] == 0) {
    mysqli_query($DB->Link, "UPDATE users SET requestMoneyBack = 1 WHERE id = " . $_SESSION['client_id']);
    $body = "{$_SESSION['client_short']},<br/><br />"
        . "This email is to notify you that we successfully received your Money-Back Request and are happy to reimburse you.<br />"
        . "This operation is processed manually. Please allow at least 12hrs to treat it and you should receive another notification upon completion.<br /><br />"
        . "Total to refund: {$row['balance']} {$_SESSION['currency']}<br />"
        . "Destination: {$_POST['paypal']}";
    require_once('../../eMail.php');
    Notify_client("💰 Money-Back Request", $body, $_SESSION['client_email'], $_SESSION['client_short'], $_SESSION['client_id'], "Money-Back Request", "business");

    $body = "Money-back request received.<br /><br />"
        . "Client: {$_SESSION['client_long']} ({$_SESSION['username']}) :: {$_SESSION['client_email']}<br />"
        . "Total to refund: {$row['balance']} {$_SESSION['currency']}<br />"
        . "Destination: {$_POST['paypal']}"
        . "<br /><br /><a href='https://www.prounlockphone.com/admin/statement.php?client=" . $_SESSION['client_id'] . "' target='_BLANK'>Open the statement page</a>"
        . "<br /><br /><a href='https://www.prounlockphone.com/admin/grantMoneyBack.php?id=" . $_SESSION['client_id'] . "' target='_BLANK'>Grant access to request money back</a>";
    Notify_me("Money-Back Request", $body);
} else {
    header("Location: ../");
    exit;
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title("Request Under Review") ?>
</head>
<body class="stretched device-lg">
<div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
    <?php header_render("refund") ?>
    <div class="clear"></div>
    <section id="content" style="margin-bottom: 0px;">
        <div class="row no-margin">
            <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                <div align="center" style="font-size:36px;width:100%">
                    <h2 style='color:crimson'>You're all set!</h2>
                    <hr /><h1>We are reviewing your request.</h1><hr />
                    A confirmation email was sent to you.<br/>
                    Keep in mind that this operation is not automatic. Please allow at least 12hrs to process it.<br/>
                    Another email will be sent to you upon completion.<br/>
                    <i style="color:royalblue">Remember that you can save more money by sending your payments as gift when using <u>PayPal</u>.</i>
                    <br /><< Thanks again for your business >>
                </div>
            </div>
        </div>
    </section>
    <?php echo $footer ?>
</div>
<?php echo $common_foot ?>
</body>
</html>