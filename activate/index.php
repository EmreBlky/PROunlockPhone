<?php
if(!isset($_GET['token']) || $_GET['token'] == "") {
    header("Location: https://www.prounlockphone.com/register/");
    exit();
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
check_loaded_session();
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT verified, id, username, first_name, last_name, language, balance, currency, status, email, type, country, city, state, post_code FROM users WHERE token = '{$_GET['token']}'"));
if(!$row['id']) {
    header("Location: https://www.prounlockphone.com/register/");
    exit();
}

if($row['status'] != "Idle") {
    mysqli_query($DB->Link, "UPDATE users SET token = '' WHERE id = " . $row['id']);
    if($row['status'] == "Active") header("Location: https://www.prounlockphone.com/login/");
    else {
        $_SESSION['client_long'] = $row['first_name'] . " " . $row['last_name'];
        if ($row['status'] == "Pending") header("Location: https://www.prounlockphone.com/processing_profile.php");
        else header("Location: https://www.prounlockphone.com/suspended_account.php");
    }
    exit();
} else mysqli_query($DB->Link, "UPDATE users SET verified = 1 WHERE token = '{$_GET['token']}'");

?><!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title("Account Activation") ?>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
</head>
<body class="stretched device-lg">
	<div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
        <?php renderOutOfSessionHeader("register") ?>
        <section id="content" style="margin-bottom: 0px;">
		    <div class="row no-margin">
                <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                    <?php if($row['verified'] == '0') {
                    ?>
                        <div class="alert alert-success alert-dismissible fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Yey, your email addres is now verified!</strong> Please complete the registration.
                        </div>
                    <?php
                    }
                    ?>
                    <div class="boxed-grey" id="register">
                        <form style="margin:0px" action="https://www.prounlockphone.com/activate/activation-success.php" method="post">
                            <input type='hidden' name='client_id' value='<?php echo $row['id'] ?>' />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col-md-6">
                                        <label for="firstname">First Name<b style='color:crimson'>*</b></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            <input style="text-transform: capitalize" id='firstname' type="text" class="form-control names" name="firstname" placeholder="Enter first name">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lastname">Last Name<b style='color:crimson'>*</b></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                            <input style="text-transform: capitalize" id="lastname" type="text" class="form-control names" name="lastname" placeholder="Enter last name">
                                        </div>
                                    </div>
<!--                                    <div class="form-group col-md-6">-->
<!--                                        <label for="company">Company</label>-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-education"></span>-->
<!--                                            </span>-->
<!--                                            <input id='company' type="text" class="form-control" name="company" placeholder="Enter company name">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-6">-->
<!--                                        <label for="website">Website</label>-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-link"></span>-->
<!--                                            </span>-->
<!--                                            <input id="website" type="text" class="form-control" name="website" placeholder="Enter url link">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-6">-->
<!--                                        <label for="address1">Address<b style='color:crimson'>*</b></label>-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-tent"></span>-->
<!--                                            </span>-->
<!--                                            <input id='address1' type="text" class="form-control" name="address1" placeholder="Enter address">-->
<!--                                        </div>-->
<!--                                        <label for="address2">Address</label>-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-home"></span>-->
<!--                                            </span>-->
<!--                                            <textarea id='address2' style='height:98px' type="text" class="form-control" name="address2" placeholder="Enter address"  rows="4"></textarea>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="form-group col-md-6">-->
<!--                                        <label for="city">City<b style='color:crimson'>*</b></label>-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-home"></span>-->
<!--                                            </span>-->
<!--                                            <input id="city" type="text" class="form-control" name="city" placeholder="Enter city" value='--><?php //echo $row['city'] ?><!--'>-->
<!--                                        </div>-->
<!--                                        <label for="state">State</label>-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-home"></span>-->
<!--                                            </span>-->
<!--                                            <input id="state" type="text" class="form-control" name="state" placeholder="Enter state" value='--><?php //echo $row['state'] ?><!--'>-->
<!--                                        </div>-->
<!--                                        <label for="zipcode">Zipcode<b style='color:crimson'>*</b></label>-->
<!--                                        <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-map-marker"></span>-->
<!--                                            </span>-->
<!--                                            <input id="zipcode" type="text" class="form-control" name="zipcode" placeholder="Enter zipcode" value='--><?php //echo $row['post_code'] ?><!--'>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="form-group col-md-6">
                                        <label for="country">Country<b style='color:crimson'>*</b></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-globe"></span>
                                            </span>
                                            <select id="country" class="form-control" name="country" onchange="$('#phone_code').val($('#country').val())">
<?php
    $reqs = mysqli_query($DB->Link, "SELECT country_code, english_name, phone_code FROM countries ORDER BY english_name");
    while($req = mysqli_fetch_array($reqs)) {
        echo "<option value='{$req['country_code']}'>{$req['english_name']} (+{$req['phone_code']})</option>\n";
    }
?>
                                            </select>
                                        </div>
                                    </div>
                                    <input type='hidden' name='language' value='EN' />
                                    <input type='hidden' name='type' value='regular' />
                                    <div class="form-group col-md-6">
                                        <label for="language">Currency <i class="text-muted small" style="font-weight: normal">change requires approval</i><b style='color:crimson'>*</b></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-usd"></span>
                                            </span>
                                            <select type="text" class="form-control" name="currency" id="currency">
                                                <option value="USD">USD - US Dollar ($)</option>
                                                <option value="EUR">EUR - Euro (&euro;)</option>
                                                <option value="GBP">GBP - British Pound (&pound;)</option>
                                                <option value="TND">TND - Tunisian Dinar (DT)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone">Phone <i class="text-muted small" style="font-weight: normal">optional</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-phone-alt"></span>
                                            </span>
                                            <input id='phone' type="tel" class="form-control nbr" name="phone" placeholder="without leading (0)">
                                            <select id="phone_code" class="form-control" name="phone_code" style="display:none">
<?php
    $reqs = mysqli_query($DB->Link, "SELECT country_code, phone_code FROM countries ORDER BY english_name");
    while($req = mysqli_fetch_array($reqs)) {
        echo "<option value='{$req['country_code']}'>{$req['phone_code']}</option>\n";
    }
?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="whatsapp">Whatsapp <i class="text-muted small" style="font-weight: normal">optional</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-phone"></span>
                                            </span>
                                            <input id="whatsapp" type="tel" class="form-control nbr" name="whatsapp" placeholder="Enter whatsapp number">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="viber">Viber <i class="text-muted small" style="font-weight: normal">optional</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-phone"></span>
                                            </span>
                                            <input id='viber' type="tel" class="form-control nbr" name="viber" placeholder="Enter viber number">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="skype">Skype <i class="text-muted small" style="font-weight: normal">optional</i></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-link"></span>
                                            </span>
                                            <input id="skype" type="text" class="form-control" name="skype" placeholder="Enter skype nickname">
                                        </div>
                                    </div>
                                    <hr style="clear: both;border-color:lightgray" />
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
                                    <label style="font-weight: normal;color:black"><input type="checkbox" checked="true" /> Agree with the our terms and conditions</label><br/>
                                    <label style="font-weight: normal;color:black"><input type="checkbox" checked="true" /> Agree with the refund/reimbursement policy</label> [<a class="text-primary" data-toggle="modal" data-target="#modal" href="https://www.prounlockphone.com/refund/refund-policy.php">read details...</a>]
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success pull-right submit">Activate</button>
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
    $('#phone_code').val('<?php echo $row['country'] ?>');
    $(".names").on('change keyup paste', function() {
        $(this).val($(this).val().replace(/[/\\\ "^\,!?~|°¨;:+_#&%*£€$@()\[\]{}]/gi, ''));
    });
    $(".nbr").on('change keyup paste', function() {
        $(this).val($(this).val().replace(/[^0-9\-+ ()]/gi, ''));
    });
    $('#firstname').focus();
    $('input[type=checkbox]').change(function(){
        var checked = true;
        $('input[type=checkbox]').each(function() {
            checked = checked && $(this).prop('checked');
        });
        $('.submit').prop('disabled', !checked);
    });
    $('#modal').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
    });
});
    </script>
</body>
</html>