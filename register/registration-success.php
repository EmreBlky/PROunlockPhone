<?php
if(isset($_POST['username']) and isset($_POST['g-recaptcha-response'])) {
    define('INCLUDE_CHECK', true);
    require '../common.php';
    require '../offline.php';
    $DB = new DBConnection();

    $url = "https://www.google.com/recaptcha/api/siteverify?";
    $fields = array(
        'secret' => '6Lc4RREUAAAAAEva9nl5eNzl6agr18bl1bv0bzZu',
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );
    $fields_string = '';
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
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
    if (!$response->success) {
        header("Location: https://www.prounlockphone.com/register/?retry=yes");
        exit();
    }

//    $pass = substr(md5($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);
    $_POST['email'] = strtolower(mysqli_real_escape_string($DB->Link, trim($_POST['email'])));
    $_POST['username'] = strtolower(mysqli_real_escape_string($DB->Link, trim($_POST['username'])));
    if (mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM users WHERE username = \"" . $_POST['username'] . "\" or email = \"" . $_POST['email'] . "\"")) > 0) {
        header("Location: https://www.prounlockphone.com/register/");
        exit();
    }

    $token = "";
    while ($token == "") {
        $token = generateRandomString();
        if (mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM users WHERE token = '{$token}'")) > 0) $token = "";
    }

    //$details = json_decode(file_get_contents("http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));
    $details = json_decode(file_get_contents("http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}"));
    require_once('../eMail.php');
    //if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT country_code FROM countries WHERE country_code = '{$details->country}'")) == 0) {
    //Notify_me('Country missing', "New user <b>{$_POST['username']}</b> attempted to register new country: <{$details->country}><br />IP: {$_SERVER['REMOTE_ADDR']}<br />Check this link: <a target='_blank' href='http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json'>http://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json</a>");
    //}
    if ($details->status != "success") {
        Notify_me('Country Error', "While attempting to retrieve the country of this IP {$_SERVER['REMOTE_ADDR']}, the remote server encountred this error below:<br />{$details->message}<br /><br />Check this link: <a target='_blank' href='http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}'>http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}</a>");
    } elseif (mysqli_num_rows(mysqli_query($DB->Link, "SELECT country_code FROM countries WHERE country_code = '{$details->countryCode}'")) == 0) {
        Notify_me('Country Missing', "New user <b>{$_POST['username']}</b> attempted to register new country: {$details->countryCode} - {$details->country}<br />IP: {$_SERVER['REMOTE_ADDR']}<br />Check this link: <a target='_blank' href='http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}'>http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}</a>");
    }

    mysqli_query($DB->Link, "INSERT INTO users(username, email, ip, city, state, country, post_code, creation_date, token)
    VALUES(
        \"{$_POST['username']}\",
        \"{$_POST['email']}\",
        \"{$_SERVER['REMOTE_ADDR']}\",
        \"{$details->city}\",
        \"{$details->regionName}\",
        \"{$details->countryCode}\",
        \"{$details->zip}\",
        \"" . date("Y-m-d H:i:s") . "\",
        \"{$token}\")");
    $body = $_POST['username'] . ",<br /><br />Welcome to <b><a href='https://www.prounlockphone.com' target='_blank' style='text-decoration:none'>PRO<i style='color:gray;font-weight: normal'>unlock</i>Phone<sup>©</sup></a></b>.<br />
In order to complete your account's activation, please click the link below to verify your email address and finish the registration:<br /><br />
<a class='btn btn-primary' href='https://www.prounlockphone.com/activate/?token={$token}'>Verify My Account</a><br /><br/>
Ensure filling all required fields to guarantee your account's approval. This helps keeping our community safe from intruders :)";
    Notify_client('✔ Verify your account', $body, $_POST['email'], $_POST['username'], mysqli_insert_id($DB->Link), "Account status", "admin");

//************************************************************************************************************
//        Notify_me('New attempt to register', $body);
//************************************************************************************************************

} else {
    header("Location: https://www.prounlockphone.com/");
    exit();
}

function generateRandomString($length = 128) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title("Registration Success") ?>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
</head>
<body class="stretched device-lg">
    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
        <?php renderOutOfSessionHeader("register") ?>
        <section id="content" style="margin-bottom: 0px;">
            <div class="row no-margin">
                <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                    <div align="center" style="font-size:20px;width:100%">
                        <h2 style='color:crimson'>You're all caught up!</h2>
                        <a class="btn btn-success submit" style="padding:20px;font-size:24px;display: block" href="https://www.prounlockphone.com/login/">Proceed to your new account</a>
                        <img height="100px" src="https://www.prounlockphone.com/images/check.gif" /><br />
                        <hr /><h1>Welcome abord</h1><hr />
                        <div class="small">
                            Check your inbox (check your spam/junk folder if you don't see our email)<br />Follow the instructions to activate your account
                            <br/>It might take a couple of minutes for the email to reach your emailbox.
                            <br />If after 5 minutes you still can't find our email, try adding <a href="mailto:support@prounlockphone.com" class="txt-primary">support@prounlockphone.com</a> to your contact list.
                            <br />Feel free to <a href="https://www.prounlockphone.com/contact/" class="txt-primary">contact us</a> if you are facing issues with the activation.
                        </div>
                    <br />
                    </div>
                </div>
            </div>
        </section>
        <?php echo $footer ?>
    </div>
    <?php echo $common_foot ?>
</body>
</html>