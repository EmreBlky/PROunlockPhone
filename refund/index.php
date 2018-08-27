<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Request Money-Back") ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <?php
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT requestMoneyBack, balance FROM users WHERE users.id = " . $_SESSION['client_id']));
    ?>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php
            header_render("refund");
            if($row['requestMoneyBack'] == '1') {
                ?>
            <section id="content" style="margin-bottom: 0px;">
                <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div align="center" style="font-size:36px;width:100%">
                            <h2 style='color:crimson'>You already have one request under review!</h2>
                            <hr /><h1>You're all set. Just relax while we process your request. It's just a matter of hours.</h1><hr />
                        </div>
                    </div>
                </div>
            </section>
                <?php
            }
            if($row['requestMoneyBack'] == '0') {
            ?>
            <section id="content" style="margin-bottom: 0px;">
                <div class="row margin30">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div align="center" style="font-size:24px;width:100%">
                            <?php
                            if($row['balance'] >= 0.01) {
                                ?>
<!--                            <button class="btn btn-success submit" style="padding:20px;font-size:24px;display: block" onclick="window.location.href = 'https://www.prounlockphone.com/refund/review/'">Confirm Money-Back request for --><?php //echo $row['balance'] . $currency ?><!--</button>-->
                                <h3 style="color:darkblue;margin: 0px">Step 1 &rarr; Indicate your PayPal ID</h3>
                                <div align="left" style="margin-left:20px">
                                    <label><input type="radio" name="paypalID" checked="true" value="<?php echo $_SESSION['client_email'] ?>" /> <?php echo $_SESSION['client_email'] ?> (your PUP account)</label>
                                    <?php
                                    $rows2 = mysqli_query($DB->Link, "SELECT DISTINCT sender FROM statement WHERE client = {$_SESSION['client_id']} AND sender <> '{$_SESSION['client_email']}' AND sender <> '' AND (status = 2 OR status = 4) ORDER BY sender");
                                    while($row2 = mysqli_fetch_array($rows2)) {
                                        echo "<br /><label><input type=\"radio\" name=\"paypalID\" value=\"{$row2['sender']}\" /> {$row2['sender']}</label>";
                                    }
                                    ?>
                                    <br /><label><input type="radio" name="paypalID" value="custom" id="customRadio" /> <input onfocus="customRadio.checked = true" id="custom" type="email" placeholder="Enter your PayPal account" style="padding: 5px;border-color: #dddddd;border-style: solid;border-width: 1px;border-radius: 5px" size="30" /></label>
                                </div>
                                <hr />
                                <h3 style="color:darkblue;margin: 0px">Step 2 &rarr; Agree with our refund policy</h3>
                                <input type="checkbox" id="agree" /> <label for="agree"><a class="text-primary" data-toggle="modal" data-target="#modal" href="https://www.prounlockphone.com/refund/refund-policy.php">Agree with the refund/reimbursement policy</a></label>
                                <hr />
                                <h3 style="color:darkblue;margin: 0px">Step 3 &rarr; Solve the Captcha</h3>
                                <br />
                                <form action="https://www.prounlockphone.com/refund/review/" method="post">
                                    <div class="g-recaptcha" data-callback="recaptchaCallback" data-expired-callback="recaptchaExpired" data-sitekey="6Lc4RREUAAAAAAE8igcAvJHtrA46STBiIVxrYcbj"></div>
                                    <hr />
                                    <h3 style="color:darkblue;margin: 0px">Step 4 &rarr; Send your request</h3>
                                    <br />
                                    <input type="hidden" name="paypal" id="paypal" />
                                    <input type="submit" id="getMoneyBack" class="btn btn-success disabled" disabled="true" style="padding:20px;font-size:24px;display: block" value="Request <?php echo number_format($row['balance'], 2, ".", ",") . " {$_SESSION['symbol']}" ?> back" />
                                </form>
                                <?php
                            } else {
                                ?>
                            <button class="btn btn-default" style="padding:20px;font-size:24px;display: block">No credits to request Money-Back: <?php echo number_format($row['balance'], 2, ".", ",") . " {$_SESSION['symbol']}" ?></button>
                                <?php
                            }
                            ?>
			            </div>
                    </div>
                </div>
            </section>
                <?php
            }
            ?>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
        <script language="JavaScript">
            var captcha = false;
            function recaptchaCallback() {
                captcha = true;
                checkReady();
            };
            function recaptchaExpired() {
                captcha = false;
                checkReady();
            };
            function checkReady() {
                if(captcha && $('#agree').prop('checked') && valid()) {
                    $('#getMoneyBack').removeAttr('disabled').removeClass('disabled');
                } else {
                    $('#getMoneyBack').attr('disabled', true).addClass('disabled');
                }
            }
            function valid() {
                var destination = $('input[name="paypalID"]:checked').val();
                if(destination == 'custom') {
                    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                    return pattern.test($('#custom').val());
                } else {
                    return true;
                }
            }
$(document).ready(function () {
    <?php
    if(isset($_GET['retry']) && $_GET['retry'] == 'yes') {
    ?>
    $.jGrowl("Please ensure solving the captcha before its expiration!<br/>Retry submitting your request.", {theme: 'growlFail'});
    <?php
    }
    ?>
    $('#agree').change(function() {
        checkReady();
    });
    $('#custom').on('change keypress keyup paste', function() {
        $('input[name="paypalID"]').trigger('change');
    });
    $('input[name="paypalID"]').change(function(){
        var valeur = $('input[name="paypalID"]:checked').val();
        if(valeur == 'custom') valeur = $('#custom').val();
        $('#paypal').val(valeur);
        checkReady();
    });
    $('#getMoneyBack').click(function(){
        var destination = $('input[name="paypalID"]:checked').val();
        if(destination == 'custom') {
            if($('#custom').val() == "") {
                $.jGrowl("Destination account cannot be empty!", {theme: 'growlFail'});
                $('#focus').select();
                return false;
            } else {
                var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                if(!pattern.test($('#custom').val())) {
                    $.jGrowl('You must indicate a valid PayPal account!', {theme: 'growlFail'});
                    $('#custom').select();
                    return false;
                } else {
                    destination = $('#custom').val();
                }
            }
        }
        $('#paypal').val(destination);
        $(this).hide().closest('div').append('<img class="loading" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    });
    $('#modal').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
    });
});
        </script>
    </body>
</html>