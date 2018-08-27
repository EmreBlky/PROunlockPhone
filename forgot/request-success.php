<?php
if(isset($_POST['identifier'])) {
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
        header("Location: https://www.prounlockphone.com/forgot/?retry=yes");
        exit();
    }
    define('INCLUDE_CHECK', true);
    require '../common.php';
    require '../offline.php';
    check_loaded_session();
    $DB = new DBConnection();

    $_POST['identifier'] = mysqli_real_escape_string($DB->Link, strtolower(trim($_POST['identifier'])));
    $rows = mysqli_query($DB->Link, "SELECT id, username, first_name, email, status FROM users WHERE username = \"" . $_POST['identifier'] . "\" or email = \"" . $_POST['identifier'] . "\"");
    if(mysqli_num_rows($rows) == 0) {
        header("Location: https://www.prounlockphone.com/forgot/?query=404");
        exit;
    }
    if(mysqli_num_rows($rows) == 1) {
        $row = mysqli_fetch_assoc($rows);
        if($row['status'] == 'Active') {
            $token = generateRandomString();
            mysqli_query($DB->Link, "UPDATE users SET reset_string = '{$token}', reset_timeout = '" . gmdate("Y-m-d H:i:s") . "' WHERE id = {$row['id']}");
            $body = $row['first_name'] . ",<br /><br />";
            $body .= "Your recently requested a password reset.<br />";
            $body .= "Simply click the link below and you will be invited to enter your new password.<br /><br />";
            $body .= "<a class='btn btn-danger' href='https://www.prounlockphone.com/reset/?token={$token}'>Reset my password</a><br /><br />";
            $body .= "This link is available for 24 hours from the moment you requested the reset.<br /><br />If you did not request to reset your password, please notify us.";
            require_once('../eMail.php');
            Notify_client("🗝️ Reset your password", $body, $row['email'], $row['first_name'], $row['id'], "Account status", "admin");
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title("Request Submitted") ?>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
</head>
<body class="stretched device-lg">
<div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
    <?php renderOutOfSessionHeader("") ?>
    <section id="content" style="margin-bottom: 0px;">
        <div class="row no-margin">
            <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                <div align="center" style="font-size:24px;width:100%;margin-bottom: 50px">
                    <h2 style='color:crimson'>You're all set!</h2>
                    <hr /><h1>Check your eMail NOW!!!</h1><hr />
                    We sent you a link to <b><?php
                        $email = explode("@", $row['email']);
                        echo substr($email[0], 0, 1);
//                        for($i = 0; $i < strlen($email[0]) - 1; $i++) {
//                            echo "&#9679;";
//                        }
                        echo "●●●●●@" . $email[1];
                        ?></b>
                    <br />Note that the link expires in 24 hours.
                </div>
            </div>
        </div>
    </section>
    <?php echo $footer ?>
</div>
<?php echo $common_foot ?>
</body>
</html>
<?php
        } elseif($row['status'] == 'Idle') {
            $token = "";
            while ($token == "") {
                $token = generateRandomString();
                if (mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM users WHERE token = '{$token}'")) > 0) $token = "";
            }

            mysqli_query($DB->Link, "UPDATE users SET token = '{$token}' WHERE id =" . $row['id']);
            $body = $row['username'] . ",<br /><br />Welcome to <b><a href='https://www.prounlockphone.com' target='_blank' style='text-decoration:none'>PRO<i style='color:gray;font-weight: normal'>unlock</i>Phone<sup>©</sup></a></b>.<br />
In order to complete your account's activation, please click the link below to verify your email address and finish the registration:<br /><br />
<a class='btn btn-primary' href='https://www.prounlockphone.com/activate/?token={$token}'>Verify My Account</a><br /><br/>
Ensure filling all required fields to guarantee your account's approval. This helps keeping our community safe from intruders :)";
            require_once('../eMail.php');
            Notify_client('✔ Verify your account', $body, $row['email'], $row['username'], mysqli_insert_id($DB->Link), "Account status", "admin");
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Request Submitted") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </head>
    <body class="stretched device-lg">
	    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php renderOutOfSessionHeader("") ?>
            <section id="content" style="margin-bottom: 0px;">
                <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div align="center" style="font-size:20px;width:100%">
                            Welcome back</h3>
                            <h2 style='color:crimson'>Your account is still not fully active</h2>
                            We resent you a link to <a class="txt-danger"><?php
                                $email = explode("@", $row['email']);
                                echo substr($email[0], 0, 1);
//                                for($i = 0; $i < strlen($email[0]) - 1; $i++) {
//                                    echo "&#9679;";
//                                }
                                echo "●●●●●@" . $email[1];
                                ?></a> in order to verify your emailbox and activate your account.<br/>
                            <img height="100px" src="https://www.prounlockphone.com/images/check.gif" /><br />
                            Check your inbox (check your spam/junk folder if you don't see our email)<br />Follow the instructions to activate your account
                            <hr/>
                            <div class="small">
                                It might take a couple of minutes for the email to reach your emailbox.
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
<?php
        }
    } else {
        header("Location: https://www.prounlockphone.com/forgot/?query=503");
        exit;
    }

} else {
    header("Location: https://www.prounlockphone.com/");
    exit;
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