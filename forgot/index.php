<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
check_loaded_session();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Forgot Password") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body class="stretched device-lg">
	    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php renderOutOfSessionHeader("") ?>
            <section id="content" style="margin-bottom: 0px;">
		<div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div class="boxed-grey" id="register">
                            <div class="form-group">
                                <p>Please enter your email or username to get password reset link.</p>
                            </div>
                            <form action="https://www.prounlockphone.com/forgot/request-success.php" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="identifier">Identifier</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-envelope"></span>
                                                </span>
                                                <input id="identifier" type="text" class="form-control" name="identifier" placeholder="Enter email / username" style="text-transform: lowercase"/>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success pull-right submit" style="float:right">Submit</button>
                                        <div class="g-recaptcha" data-sitekey="6Lc4RREUAAAAAAE8igcAvJHtrA46STBiIVxrYcbj"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <footer id="footer" class="dark">
                <div class="margin30 container-fluid">
                    <p class="p1" style="text-align: center; ">
                        <span style="font-size: 14px;">
                            <font color="#9c9c94">All the prices are subject to change without prior notice. If you have any progressing orders, they should not be affected by any change. However, if it is the case, the order will be rejected and your credits refunded to your account and you should receive a notification explaining the reason.</font>
                        </span>
                    </p>
                    <p class="p2" style="text-align: center;">
                        <font color="#9c9c94"><br></font>
                    </p>
                    <p class="p1" style="text-align: center;">
                        <span style="font-size: 14px;">
                            <font color="#9c9c94">Feel free to contact us about any issue that you might face or about any concern. Our team is here to server and help. Our goal is to make you raise money, and eventually we will make profits.</font>
                        </span>
                    </p>
                    <p>
                        <font color="#9c9c94">
                            <span style="font-size: 14px;"></span>
                            <style type="text/css">
p.p1 {margin: 0.0px 0.0px 0.0px 0.0px; font: 12.0px Helvetica}
p.p2 {margin: 0.0px 0.0px 0.0px 0.0px; font: 12.0px Helvetica; min-height: 14.0px}
                            </style>
                            <span style="font-size: 14px;"></span>
                        </font>
                    </p>
                    <p class="p2" style="text-align: center; "><br></p>
                </div>
                <div id="copyrights">
                    <div class="container clearfix">
                        <div class="col_half">PROUnlockPhone© 2015 - 2017</div>
                        <div class="col_half col_last tright">
                            <i class="fa fa-envelope"></i> <a href='mailto:support@prounlockphone.com'>support@prounlockphone.com</a>
                            <span class="middot">   </span>
                            <i class="fa fa-phone"></i> +1 (210) 454-4850
			</div>
                    </div>
                </div>
            </footer>
	</div>
	<div id="gotoTop" class="fa fa-caret-up" style="display: none;"></div>
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1041;">
            <div class="modal-dialog">
		<div class="modal-content"></div>
            </div>
        </div>
        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1041;">
            <div class="modal-dialog" style="width: 96%;">
		<div class="modal-content"></div>
            </div>
        </div>
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1100;">
            <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" class="close" data-dismiss="modal" type="button">Ã—</button>
				<h4 class="modal-title" style="color: red;">
					Error!
				</h4>
			</div>
			<div class="modal-body container-fluid">
                            <p>Communication token expired, please refresh the page and try again!</p>
			</div>
			<div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
			</div>
		</div>
            </div>
        </div>
        <script src="https://www.prounlockphone.com/common/bootstrap.min.js"></script>
        <script src="https://www.prounlockphone.com/common/plugins.js"></script>
        <script src="https://www.prounlockphone.com/common/functions.js"></script>

        <script type="text/javascript" src="https://www.prounlockphone.com/common/jquery.jgrowl.min.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/jquery.form.min.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/script.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/switchery.js"></script>
        <div id="jGrowl" class="top-right jGrowl">
            <div class="jGrowl-notification"></div> 
        </div>
        <?php
    if(isset($_GET['retry']) && $_GET['retry'] == 'yes') {
?>
        <script>
$(document).ready(function () {
    $.jGrowl("You did not solve the captcha!", {theme: 'growlFail'});
});
        </script>
<?php
    } elseif(isset($_GET['query']) && $_GET['query'] == '404') {
?>
        <script>
$(document).ready(function () {
    $.jGrowl("Username / eMail address not registred in our records!", {theme: 'growlFail'});
});
        </script>
<?php
    } elseif(isset($_GET['query']) && $_GET['query'] == '503') {
?>
        <script>
$(document).ready(function () {
    $.jGrowl("There was a problem with your query. Try with the username/email instead!", {theme: 'growlFail'});
});
        </script>
<?php
    } elseif(isset($_GET['error']) && $_GET['error'] == 'expired') {
?>
        <script>
$(document).ready(function () {
    $.jGrowl("Your reset token has expired.<br />You can request a new one and proceed immediately with the reset process.", {theme: 'growlFail'});
});
        </script>
<?php
    }
?>
    </body>
</html>