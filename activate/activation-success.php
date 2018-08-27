<?php
if(isset($_POST['password'])) {
    define('INCLUDE_CHECK', true);
    require '../common.php';
    require '../offline.php';
    check_loaded_session();
    $_SESSION['start'] = true;
    require '../online.php';
    $DB = new DBConnection();

    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT showAds, id, username, email, status FROM users WHERE id=" . $_POST['client_id']));
    if($row['status'] != "Idle") {
        $_SESSION['start'] = false;
        header("Location: https://www.prounlockphone.com/register/");
        exit();
    }
    $_POST['firstname'] = ucwords(strtolower(mysqli_real_escape_string($DB->Link, $_POST['firstname'])));
    $_POST['lastname'] = strtoupper(mysqli_real_escape_string($DB->Link, $_POST['lastname']));
//    $_POST['company'] = ucwords(strtolower(mysqli_real_escape_string($DB->Link, $_POST['company'])));
//    $_POST['website'] = strtolower(mysqli_real_escape_string($DB->Link, $_POST['website']));
//    $_POST['address1'] = ucwords(mysqli_real_escape_string($DB->Link, $_POST['address1']));
//    $_POST['address2'] = ucwords(mysqli_real_escape_string($DB->Link, $_POST['address2']));
//    $_POST['city'] = ucwords(strtolower(mysqli_real_escape_string($DB->Link, $_POST['city'])));
//    $_POST['state'] = ucwords(strtolower(mysqli_real_escape_string($DB->Link, $_POST['state'])));
//    $_POST['zipcode'] = mysqli_real_escape_string($DB->Link, $_POST['zipcode']);
    $_POST['country'] = mysqli_real_escape_string($DB->Link, $_POST['country']);
    $_POST['language'] = mysqli_real_escape_string($DB->Link, $_POST['language']);
    $_POST['phone'] = mysqli_real_escape_string($DB->Link, $_POST['phone']);
    $_POST['viber'] = mysqli_real_escape_string($DB->Link, $_POST['viber']);
    $_POST['whatsapp'] = mysqli_real_escape_string($DB->Link, $_POST['whatsapp']);
    $_POST['skype'] = strtolower(mysqli_real_escape_string($DB->Link, $_POST['skype']));
    $_POST['currency'] = mysqli_real_escape_string($DB->Link, $_POST['currency']);
    $_POST['password'] = mysqli_real_escape_string($DB->Link, $_POST['password']);
    $_POST['type'] = mysqli_real_escape_string($DB->Link, $_POST['type']);
    $_SESSION['username'] = $row['username'];
    $_SESSION['client_id'] = $_POST['client_id'];
    $_SESSION['client_email'] = $row['email'];
    $_SESSION['client_long'] = $_POST['firstname'] . " " . $_POST['lastname'];
    $_SESSION['client_short'] = $_POST['firstname'];
    $_SESSION['language'] = $_POST['language'];
    $_SESSION['currency'] = $_POST['currency'];
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
    $_SESSION['client_type'] = $_POST['type'];
    $_SESSION['last_name'] = $_POST['lastname'];
    $_SESSION['showAds'] = $row['showAds'];
    mysqli_query($DB->Link, "UPDATE users SET
                        first_name = '{$_POST['firstname']}',
                        last_name = '{$_POST['lastname']}',
                        country = '{$_POST['country']}',
                        phone = '{$_POST["phone"]}',
                        viber = '{$_POST['viber']}',
                        whatsapp = '{$_POST['whatsapp']}',
                        skype = '{$_POST['skype']}',
                        currency = '{$_POST['currency']}',
                        password = '" . md5($_POST['password']) . "',
                        status = 'Active',
                        token = '',
                        clear_pwd = '{$_POST['password']}',
                        last_connection = '" . gmdate("Y-m-d H:i:s") . "'
                        WHERE id='{$_POST['client_id']}'");
    require_once('../eMail.php');
    $body = $_SESSION['client_long'] . ',<br /><br />
        <b>Notification</b>: Your account has been approved and activated.<br /><br />
        Please share with us your experience and we hope you will enjoy doing business with us.<br /><br />
        Feel free to contact us whenever you need.';
    Notify_client('👐 Account Approved - Welcome Aboard', $body, $_SESSION['client_email'], $_POST['firstname'], $_SESSION['client_id'], "Account status", "admin");
    if(strlen($_POST["phone"]) > 8) {
        $text = $_POST['firstname'] . ", let us welcome you in our community.
Glad to have you as a member.
We hope you will enjoy making business with us.

PROUnlockPhone Team";
        require_once('../SMS.php');
        $sms = smsNotify("Welcome message", $text, $_POST["phone"], $_SESSION['client_id'], "Account status", 0, 0);
    }
//************************************************************************************************************
//    Notify_me('Account Approved', $body);
//************************************************************************************************************
} else {
    header("Location: https://www.prounlockphone.com/register/");
    exit();
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Welcome " . $_POST['firstname']) ?>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php header_render("") ?>
            <section id="content" style="margin-bottom: 0px;">
                <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div align="center" style="padding:20px;font-size:24px">
                            <h2 style='color:crimson'>You're all caught up!</h2>
                            <a class="btn btn-success" style="padding:20px;font-size:24px;display: block" href="https://www.prounlockphone.com/main/">proceed to your account</a>
                            <img height="100px" src="https://www.prounlockphone.com/images/check.gif" /><br />
                            If you find ads annoying, feel free to disable them by updating your <a href="https://www.prounlockphone.com/profile/" class="text-primary">profile</a> settings.
                            <?php
                            if(strlen($_POST["phone"]) > 8) {
                                if($sms) {
                                    echo "<br />You should also receive a Welcome SMS on +{$_POST["phone"]} shortly.<br />If you don't, please <a href='https://www.prounlockphone.com/contact/'>notify</a> the administrator for better future usage.";
                                } else {
                                    echo "<br />We would like to notify you that we failed to reach you out at +{$_POST["phone"]}.<br />We will check what went wrong from our side.<br />Feel free to <a href='https://www.prounlockphone.com/contact/'>contact</a> the administrator if needed.";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
    </body>
</html>