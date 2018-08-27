<?php
if(!isset($_POST['password'])) {
    header('location: https://www.prounlockphone.com/change/');
    exit();
}

define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$_POST['old'] = mysqli_real_escape_string($DB->Link, $_POST['old']);
$_POST['password'] = mysqli_real_escape_string($DB->Link, $_POST['password']);
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, $query = "SELECT clear_pwd FROM users WHERE id = " . $_SESSION['client_id']));
if($_POST['old'] != $row['clear_pwd']) {
    header('location: https://www.prounlockphone.com/change/?error=wrong');
    exit;
} elseif($_POST['old'] == $_POST['password']) {
    header('location: https://www.prounlockphone.com/change/?error=same');
    exit();
} else {
    mysqli_query($DB->Link, "UPDATE users SET
                    password = '" . md5($_POST['password']) . "',
                    clear_pwd = '" . $_POST['password'] . "'
                    WHERE id='{$_SESSION['client_id']}'");
    $body = $_SESSION['client_short'] . ',<br /><br />
    Your password has been successfully updated.<br /><br />
    If you did not initiate this operation, please notify us as promptly.';
    require_once('../eMail.php');
    Notify_client('🗝️ Password Reset', $body, $_SESSION['client_email'], $_SESSION['client_short'], $_SESSION['client_id'], "Account status", "admin");
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title("Password Reset Success") ?>
</head>
<body class="stretched device-lg">
<div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
    <?php header_render("change") ?>
    <div class="clear"></div>
    <section id="content" style="margin-bottom: 0px;">
        <div class="row no-margin">
            <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                <div align="center" style="padding:20px;font-size:24px">
                    <h2 style='color:crimson'>Your password has been updated!</h2>
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