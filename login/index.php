<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
check_loaded_session();
$DB = new DBConnection();

$url = "https://widget.trustpilot.com/base-data?businessUnitId=5ab0e7e37e2f2d0001ad4c35&locale=en-US";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, '300');
$response = json_decode(curl_exec($ch));
curl_close($ch);
$reviews = $response->businessUnit->numberOfReviews->total;

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Login") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </head>
    <body class="stretched device-lg">
	    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php renderOutOfSessionHeader("login") ?>
            <section id="content" style="margin-bottom: 0px;">
		        <div class="row no-margin">
                    <div class="col-lg-6" style="display: block; margin: 0 auto; float: none;">
                        <div class="boxed-grey">
                            <form action="https://www.prounlockphone.com/login/access.php" method="post">
                                <input type="hidden" name="url" value="<?php if(isset($_GET['url'])) echo $_GET['url'] ?>" />
                                <input type="hidden" name="param" value="<?php if(isset($_GET['param'])) echo $_GET['param'] ?>" />
                                <input type="hidden" name="urlx" value="<?php if(isset($_GET['urlx'])) echo $_GET['urlx'] ?>" />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="username">Username or eMail Address</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </span>
                                                <input id='username' type="text" class="form-control" name="username" placeholder="Enter username or email address" autocorrect="off" autocapitalize="none" style="text-transform: lowercase">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                </span>
                                                <input id='password' type="password" class="form-control" name="password" placeholder="Enter password">
                                            </div>
                                        </div>
                                        <a href="https://www.prounlockphone.com/forgot/" class="btn btn-default pull-left">Forgot Password</a>
                                        <button type="submit" class="btn btn-success pull-right submit" style="display: block;">Login</button>
                                    </div>
                                    <?php
                                    if($reviews > 0) {
                                        ?>
                                        <!-- TrustBox widget - Micro Review Count -->
                                        <br />
                                        <br />
                                        <div align="center">
                                            See our <b><?php echo $reviews ?></b> reviews on<a href="https://www.trustpilot.com/review/prounlockphone.com" target="_blank"><img src="https://www.prounlockphone.com/images/trustPilot.png" height="22px" /></a>
                                        </div>
                                        <br />
                                        <!-- End TrustBox widget -->
                                        <?php
                                    }
                                    ?>
                                    <div align="center"><span id="siteseal" class="center"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=4nsQi5bcVmF3fOQwfxjdCqJhZsFw9UAxBxkAm1U2soCE4Cj3sW1RSh4xMHIe"></script></span></div>
                                    <br /><a href="https://info.flagcounter.com/7sUT"><img src="https://s05.flagcounter.com/countxl/7sUT/bg_FFFFFF/txt_000000/border_CCCCCC/columns_8/maxflags_250/viewers_0/labels_0/pageviews_0/flags_0/percent_0/" alt="Flag Counter" border="0"></a>
                                </div>
                            </form>
                        </div>
                    </div>
		        </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
        <?php
    if(isset($_GET['error']) && $_GET['error'] == 'error') {
?>
        <script>
$(document).ready(function () {
    $.jGrowl("There was a problem executing your request. Please try again or contact our <a href='https://www.prounlockphone.com/support/'>support team</a> for assistance.", {theme: 'growlFail'});
});
        </script>
<?php
    }
?>
    </body>
</html>