<?php
if(!isset($_GET['token'])) {
    header("Location: https://www.prounlockphone.com/forgot/");
    exit();
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
check_loaded_session();
$DB = new DBConnection();

$last_requested = date('Y-m-d H:i:s',strtotime('-24 hours', time()));
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT id FROM users WHERE status = 'Active' AND reset_string = '{$_GET['token']}' AND reset_timeout > '{$last_requested}'"));
if(!$row['id']) {
    header("Location: https://www.prounlockphone.com/forgot/?error=expired");
    exit();
} else {
    session_name('Client_Session');
    session_start();
    $_SESSION['start'] = false;
    session_destroy();
    session_unset();
    $_SESSION = array();
}
?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Reset Password") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </head>
    <body class="stretched device-lg">
	<div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
        <?php renderOutOfSessionHeader("login") ?>
            <section id="content" style="margin-bottom: 0px;">
		<div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div class="boxed-grey" id="register">
                            <div class="form-group">
                                <p>Welcome back.<br/>Please enter your new password to finish the reset process.</p>
                            </div>
                            <form action="https://www.prounlockphone.com/reset/reset-success.php" method="post">
                                <input type='hidden' name='client_id' value='<?php echo $row['id'] ?>' />
                                <input type='hidden' name='token' value='<?php echo $_GET['token'] ?>' />
                                <div class="row">
                                    <div class="col-md-12">                                      
                                        <div class="form-group col-md-6">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                </span>
                                                <input type="password" id="password" class="form-control" name="password" placeholder="Enter password">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="confirm">Password Confirmation</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                </span>
                                                <input id="confirm" type="password" class="form-control" name="confirm" placeholder="Confirm password">
                                            </div>
                                        </div>
                                        <input type="hidden" value="<?php echo $_GET['token'] ?>" name="token" />
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-success pull-right submit">Set a new password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
		</div>
            </section>
        <?php echo $footer ?>
	</div>
    <?php echo $common_foot ?>
        <script>
$(function() {
    $('#country').val('<?php echo $row['country'] ?>');
    $('#password').focus();
});
        </script>
    </body>
</html>