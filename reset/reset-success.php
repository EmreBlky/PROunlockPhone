<?php
if(!isset($_POST['password'])) {
    header("Location: https://www.prounlockphone.com/forgot/");
    exit();
}

define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
check_loaded_session();
$DB = new DBConnection();

$_POST['password'] = mysqli_real_escape_string($DB->Link, $_POST['password']);
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT showAds, id, username, email, first_name, last_name, language, balance, currency, type FROM users WHERE reset_string = '{$_POST['token']}' AND id=" . $_POST['client_id']));

if(!$row['id']) {
    header("Location: https://www.prounlockphone.com/forgot/");
    exit();
}

$_SESSION['start'] = true;
require '../online.php';
$_SESSION['username'] = $row['username'];
$_SESSION['client_id'] = $row['id'];
$_SESSION['client_email'] = $row['email'];
$_SESSION['client_short'] = $row['first_name'];
$_SESSION['client_long'] = $row['first_name'] . " " . $row['last_name'];
$_SESSION['language'] = $row['language'];
$_SESSION['balance'] = $row['balance'];
$_SESSION['currency'] = $row['currency'];
switch($_SESSION['currency']) {
    case "USD":
        $_SESSION['symbol'] = "$";
        break;
    case "EUR":
        $_SESSION['symbol'] = "&euro;";
        break;
    case "GBP":
        $_SESSION['symbol'] = "&pound;";
        break;
    case "TND":
        $_SESSION['symbol'] = "DT";
        break;
}
$_SESSION['client_type'] = $row['type'];
$_SESSION['showAds'] = $row['showAds'];
mysqli_query($DB->Link, "UPDATE users SET
                    password = '" . md5($_POST['password']) . "',
                    clear_pwd = '" . $_POST['password'] . "',
                    reset_string = '',
                    reset_timeout = NULL,
                    last_connection = '" . gmdate("Y-m-d H:i:s") . "'
                    WHERE id='{$_POST['client_id']}'");
require_once('../eMail.php');
$body = $_SESSION['client_short'] . ',<br /><br />
    Your password has been successfully updated.<br /><br />
    If you did not initiate this operation, please notify us as promptly.';
Notify_client('🗝️ Password Reset', $body, $_SESSION['client_email'], $_SESSION['client_short'], $_SESSION['client_id'], "Account status", "admin");

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Password Reset Success") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </head>
    <body class="stretched device-lg">
	<div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
        <?php header_render("login") ?>
            <section id="content" style="margin-bottom: 0px;">
		<div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div align="center" style="padding:20px;font-size:24px">
                            <h2 style='color:crimson'>You're all set :)</h2>
                            <a class="btn btn-success" style="padding:20px;font-size:24px;display: block" href="https://www.prounlockphone.com/main/">proceed to your account</a>
                            <img height="100px" src="https://www.prounlockphone.com/images/check.gif" /><br />
                            You should also receive a confirmation eMail shortly.
                        </div>
                    </div>
                </div>
            </section>
        <?php echo $footer ?>
	</div>
    <?php echo $common_foot ?>
    </body>
</html>