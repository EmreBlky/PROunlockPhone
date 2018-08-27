<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
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
        <?php echo common_head_with_title("Register") ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php renderOutOfSessionHeader("register") ?>
            <section id="content" style="margin-bottom: 0px;">
            <div class="row no-margin">
                        <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                            <div class="boxed-grey" id="register">
                                <form action="https://www.prounlockphone.com/register/registration-success.php" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group col-md-6">
                                                <label for="username">Username</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-user"></span>
                                                    </span>
                                                    <input id='username' type="text" class="form-control" name="username" placeholder="Enter username (min. 6 characters)" autocorrect="off" autocapitalize="none" style="text-transform: lowercase">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="email">eMail</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-envelope"></span>
                                                    </span>
                                                    <input id='email' type="email" class="form-control" name="email" placeholder="Enter email address" style="text-transform: lowercase">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <a href="https://www.prounlockphone.com/login/" class="btn btn-default pull-left">Already have account?</a>
                                                <button type="submit" class="btn btn-success pull-right submit" style="display: block;">Register</button>
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
                                            <br />
                                            <div class="col-md-12" align="center">
                                                <div class="g-recaptcha" data-sitekey="6Lc4RREUAAAAAAE8igcAvJHtrA46STBiIVxrYcbj"></div>
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
            $(document).ready(function () {
                $("#username").on('change keyup', function() {
                    $(this).val($(this).val().replace(/[/\\\ "'éèàçüê^\,!?~|°¨;:+_#&%*£€$@()\[\]{}]/gi, ''));
                });
<?php
    if(isset($_GET['retry']) && $_GET['retry'] == 'yes') {
?>
                $.jGrowl("You did not solve the captcha!", {theme: 'growlFail'});
<?php
    }
?>
            });
        </script>
    </body>
</html>