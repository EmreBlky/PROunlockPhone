<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Update Password") ?>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php header_render("change"); ?>
            <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                <div class="boxed-grey" id="register">
                    <form action="https://www.prounlockphone.com/change/success.php" method="post">
                        <input type="hidden" value="<?php echo $_SESSION['username'] ?>" id="username" class="form-control" name="username">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-6 left-block">
                                    <label for="password">Current Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </span>
                                        <input type="password" id="old" class="form-control" name="old" placeholder="Old password">
                                    </div>
                                </div>
                                <hr style="clear: both" />
                                <div class="form-group col-md-6">
                                    <label for="password">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-lock"></span>
                                        </span>
                                        <input type="password" id="password" class="form-control" name="password" placeholder="New password">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="confirm">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-lock"></span>
                                        </span>
                                        <input id="confirm" type="password" class="form-control" name="confirm" placeholder="Confirm password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success pull-right submit">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php echo $footer ?>
        </div>
        <?php echo $common_foot ?>
        <script>
            $(document).ready(function () {
                $('#old').focus();
                <?php
                    if(isset($_GET['error'])) {
                        if($_GET['error'] == 'wrong') {
                            ?>
                $.jGrowl("Your old password does not match our records!<br/>Please try again or visit <a class='text-info' href='https://www.prounlockphone.com/forgot/' target='_blank'>https://www.prounlockphone.com/forgot/</a> if you do not remember your password.", {theme: 'growlFail'});

                            <?php
                        } elseif($_GET['error'] == 'same') {
                            ?>
                $.jGrowl("Your new password is the same as your old password!<br />No changes made.", {theme: 'growlFail'});
                            <?php
                        }
                    }
        ?>
        });
        </script>
    </body>
</html>