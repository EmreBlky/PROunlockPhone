<?php
if(!isset($_GET['service'])) {
    header("Location: https://www.prounlockphone.com/services/");
    exit();
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT * FROM services WHERE id = " . $_GET['service']));

?>

<div class='modal-header'>
    <button aria-hidden='true' class='close last' data-dismiss='modal'>×</button>
    <h4 class='modal-title'>Place new order</h4>
</div>
<div id="session_status">
    <div class='modal-body'>
        <a href="https://www.prounlockphone.com/login/?url=order&param=<?php echo $_GET['service'] ?>" class="btn btn-success center-block">Connect to your account</a>
        <a href="https://www.prounlockphone.com/register/" class="text-info center-block">Don't have an account? Register one now.</a>
        <hr />
        <a class="btn btn-default center-block" onclick="$('#session_status').slideUp();$('#order-details').slideDown();">Continue as guest</a>
    </div>
</div>
<div id="order-details" style="display:none">
    <div class='modal-body'>
        <div>
            <p><b>Step 1:</b> Enter your order details</p>
            <hr />
            <?php
if($row['imei'] == "1") {
            ?><div class='form-group'>
                <label>IMEI</label><span id='makenmodel' style='display:none;float:right;color:crimson'></span>
                <div class='input-group'>
                    <input id='imei' class='form-control order-put' type="text" maxlength='14' placeholder='*#06# then copy the 15 digits' />
                    <span class='input-group-addon'>
                        <span id='check'>0</span>
                    </span>
                </div>
            </div>
            <?php
}
if($row['sn'] == "1") {
            ?><div class='form-group'>
                <label>Serial Number</label>
                <div class='input-group'>
                    <input id='sn' class='form-control order-put' type='text' maxlength='12' style='text-transform: uppercase' placeholder='SN' />
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <?php
}
            ?><form>
                <div class='form-group'>
                    <label>First Name</label>
                    <div class='input-group'>
                        <input name="firstname" id='client-firstname' class='form-control order-put' type="text" placeholder='First Name' style="text-transform: capitalize" />
                        <span class='input-group-addon'>optional</span>
                    </div>
                </div>
                <div class='form-group'>
                    <label>Last Name</label>
                    <div class='input-group'>
                        <input name="lastname" id='client-lastname' class='form-control order-put' type="text" placeholder='Last Name' style="text-transform: capitalize" />
                        <span class='input-group-addon'>optional</span>
                    </div>
                </div>
                <div class='form-group'>
                    <label>eMail Address</label> (results will be sent here)
                    <div class='input-group'>
                        <input name="email" id='client-email' class='form-control order-put' type='email' maxlength='80' style='text-transform: lowercase' placeholder='eMail of contact where we can reach you out' />
                        <span class='input-group-addon' style="background-color: crimson;color:white">required</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class='modal-footer'>
        <button class='btn btn-default' data-dismiss='modal'>Cancel</button>
        <button id="goStep2" class='btn btn-info disabled' disabled="true">Order Review</button>
    </div>
</div>
<div id="order-review" style="display:none">
    <div class='modal-body'>
        <p><b>Step 2:</b> Order Summary</p>
        <hr />
        <label>Service Name</label> <b class="text-danger"><?php echo $row['service_name'] ?></b>
        <br />
        <label>Service Cost</label> <?php echo number_format($row["regular_{$_SESSION['currency']}"], 2, ".", ",") ?> <label style="margin-left:20px">Fees</label> 5% = <?php echo number_format($row["regular_{$_SESSION['currency']}"] / 100 * 5, 2, ".", ",") ?> <label style="margin-left:20px">Handling</label> 0.50<label style="margin-left:20px">Total</label> <b class="bg-primary" id="total"></b>
        <?php
        if($row['imei'] == "1") {
            ?><br />
        <label>IMEI</label> <a class="text-primary" id="imei-u"></a>
        <?php
        }
        if($row['sn'] == "1") {
            ?><br />
        <label>Serial Number</label> <a class="text-primary text-uppercase" id="sn-u"></a>
        <?php
        }
        ?><hr />
        <label>Client</label> <a class="text-primary" id="client-u"></a>
        <br />
        <label>eMail Address</label> <a class="text-primary text-lowercase" id="client-email-u"></a>
    </div>
    <div class='modal-footer'>
<!--        <form id="paypal" name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">-->
        <form id="paypal" name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <table>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on0" value="Client Name" /></td>
                    <td><input type="hidden" name="os0" id="os0" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on1" value="eMail Address" /></td>
                    <td><input type="hidden" name="os1" id="os1" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on2" value="Service Name" /></td>
                    <td><input type="hidden" name="os2" value="<?php echo $row['service_name'] ?>" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on3" value="Currency" /></td>
                    <td><input type="hidden" name="os3" value="<?php echo $_SESSION['currency'] ?>" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on4" value="IMEI/SN" /></td>
                    <td><input type="hidden" name="os4" id="os4" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on5" value="Processing Time" /></td>
                    <td><input type="hidden" name="os5" value="Instant" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on6" value="Service Cost" /></td>
                    <td><input type="hidden" name="os6" value="<?php echo $row["regular_{$_SESSION['currency']}"] ?>" /></td>
                </tr>
            </table>
            <input type="hidden" name="cmd" value="_ext-enter">
            <input type="hidden" name="redirect_cmd" value="_xclick">
            <input type="hidden" name="business" value="paypal@prounlockphone.com" />
            <input type="hidden" name="currency_code" value="<?php echo $_SESSION['currency'] ?>" />
            <input type="hidden" name="item_name" id="item_name" value="<?php echo $row['service_name'] ?> for " />
            <input type="hidden" name="no_note" value="1" />
            <input type="hidden" name="no_shipping" value="1" />
            <input type="hidden" name="handling" id="handling" />
            <input type="hidden" name="amount" value="<?php echo $row["regular_{$_SESSION['currency']}"] ?>" />
            <input type="hidden" name="email" id="payer-email" />
            <input type="hidden" name="first_name" id="first_name" />
            <input type="hidden" name="last_name" id="last_name" />
            <input type="hidden" name="item_number" value="<?php echo $_GET['service'] ?>" />
            <input type="hidden" name="quantity" value="1" />
            <input type="hidden" name="image_url" value="https://www.prounlockphone.com/images/pup82x50.png" />
            <input type="hidden" name="return" id="return_url" value="https://www.prounlockphone.com/service/placed.php?return=success&relative_id=" />
            <input type="hidden" name="cancel_return" id="cancel_return" value="https://www.prounlockphone.com/service/?id=<?php echo $_GET['service'] ?>&return=cancel&relative_id=" />
            <input type="hidden" name="notify_url" id="notify_url" value="https://www.prounlockphone.com/confirm-fast.php?relative_id=" />
        </form>
        <button class='btn btn-default last' data-dismiss='modal'>Cancel</button>
        <button class='btn btn-default last' onclick="$('#order-review').slideUp();$('#order-details').slideDown();"><< Back</button>
        <button id="goStep3" class='btn btn-info last'>Pay with PayPal</button>
    </div>
</div>
<script>
    $('.order-put').bind('keypress', function (event) {
        if (event.keyCode === 13) {
            $('#goStep2').trigger('click');
        }
    });
    <?php
    if($row['imei'] == "1") {
        ?>var current_imei = '';
    $('#imei').select();
    function gen_cd(imei) {
        var step2 = 0;
        var step2a = 0;
        var step2b = 0;
        var step3 = 0;
        for(var i = imei.length; i < 14; i++) imei = imei + "0";
        for(var i = 1; i < 14; i = i + 2) {
            var step1 = (imei.charAt(i)) * 2 + "0";
            step2a = step2a + parseInt(step1.charAt(0)) + parseInt(step1.charAt(1));
        }
        for(var i = 0; i < 14; i = i + 2) step2b = step2b + parseInt(imei.charAt(i));
        step2 = step2a + step2b;
        if (step2 % 10 == 0) step3 = 0;
        else  step3 = 10 - step2 % 10;
        if(current_imei != imei + step3) {
            current_imei = imei + step3;
            $.ajax({
                type: 'POST',
                url: 'https://www.prounlockphone.com/order/makenmodel.php',
                data: 'imei=' + current_imei,
                success: function (resp) {
                    $('#makenmodel').html(resp).slideDown('slow');
                },
                error: function() {
                    console.log('Error retrieving data');
                }
            });
        } else {
            $('#makenmodel').slideUp();
        }
        return step3;
    }
    $('#imei').on('change keyup paste', function () {
        var imei = $(this).val();
        imei = imei.replace(/[^0-9]/gi, '');
        $(this).val(imei);
        if($(this).val().length == 14) {
            $('#check').html(gen_cd(imei));
        } else {
            $('#makenmodel').slideUp();
        }
        checkReadyToPay();
    });
    <?php
    }
    if($row['sn'] == "1") {
        ?>$('#sn').on('change keyup paste', function () {
        var sn = $('#sn').val();
        sn = sn.replace(/[^0-9a-zA-Z]/gi, '');
        $('#sn').val(sn);
        checkReadyToPay();
    });
    <?php
    }
    ?>
    $('#client-email').on('change keyup paste', function () {
        checkReadyToPay();
    });

    function checkReadyToPay() {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if(pattern.test($('#client-email').val())<?php
            if($row['imei'] == "1") {
            ?> && $('#imei').val().length == 14<?php
            }
            if($row['sn'] == "1") {
            ?> && $('#sn').val().length >= 8<?php
            }
            ?>) {
            $('#goStep2').removeClass('disabled').prop('disabled', false);
        } else {
            $('#goStep2').addClass('disabled').prop('disabled', true);
        }
    }

    $('#goStep2').on('click', function (ev) {
        ev.preventDefault();
        <?php
            if($row['imei'] == "1") {
            ?>if ($('#imei').val() != '' && $('#imei').val().length == 14) {
            var imeis = $('#imei').val() + $('#check').html();
        } else if ($('#imei').val() != '') {
            $.jGrowl('You IMEI is incomplete', {theme: 'growlFail'});
            $('#imei').select();
            return false;
        } else {
            var imeis = '';
        }
        if (imeis == '') {
            $.jGrowl('You must enter your IMEI', {theme: 'growlFail'});
            $('#imei').select();
            return false;
        }
        <?php
            }
            if($row['sn'] == "1") {
            ?>if ($('#sn').val().length < 8) {
            $.jGrowl('You must enter the Serial Number', {theme: 'growlFail'});
            $('#sn').select();
            return false;
        }
        <?php
        }
        if($row['imei'] == "1") {
        ?>$('#imei-u').html(imeis);
        <?php
        }
        if($row['sn'] == "1") {
        ?>$('#sn-u').html($('#sn').val().toUpperCase());
        <?php
        }
        ?>
        <?php
        if($row['imei'] == "1") {
        ?>$('#os4').val(imeis);
        $('#item_name').val($('#item_name').val() + imeis);
        <?php
        } elseif($row['sn'] == "1") {
        ?>$('#os4').val($('#sn').val().toUpperCase());
        $('#item_name').val($('#item_name').val() + $('#sn').val().toUpperCase());
        <?php
        }
        ?>

        $('#client-firstname').val($('#client-firstname').val().trim());
        $('#client-lastname').val($('#client-lastname').val().trim());

        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if(!pattern.test($('#client-email').val())) {
            $.jGrowl('You must enter a valid email address where we can send you notifications regarding your order.', {theme: 'growlFail'});
            $('#client-email').select();
            return false;
        }

        var name = $('#client-firstname').val().charAt(0).toUpperCase() + $('#client-firstname').val().substr(1).toLowerCase() + " " + $('#client-lastname').val().charAt(0).toUpperCase() + $('#client-lastname').val().substr(1).toLowerCase();
        name = name.trim();
        if(name == "") name = "Guest";
        $('#client-u').html(name);
        $('#client-email-u').html($('#client-email').val().toLowerCase());

        $('#os0').val(name);
        $('#os1').val($('#client-email').val().toLowerCase());

        $('#total').html('&nbsp;<?php echo number_format(($row["regular_{$_SESSION['currency']}"] / 100 * 105) + 0.5, 2, ".", ",") . ' ' . $_SESSION['symbol'] ?>&nbsp;');
        $('#handling').val('<?php echo number_format(($row["regular_{$_SESSION['currency']}"] / 100 * 5) + 0.5, 2, ".", ",") ?>');

        $('#payer-email').val($('#client-email').val().toLowerCase());
        $('#first_name').val($('#client-firstname').val());
        $('#last_name').val($('#client-lastname').val());
        $('#order-details').slideUp();
        $('#order-review').slideDown();
        $('#goStep3').focus();
    });
    <?php
        if($row['imei'] == "1") {
            echo "$('#imei').select();";
        } else {
            echo "$('#sn').select();";
        }
    ?>

    $('#goStep3').on('click', function (ev) {
        ev.preventDefault();
        $('.last').hide();
        $(this).closest('div').append('<img class="loading center" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
        $.ajax({
            type: 'POST',
            url: 'https://www.prounlockphone.com/service/placeOrder.php',
            data: 'service=<?php
                echo $_GET['service'] . "'";
                if($row['imei'] == "1") echo " + '&IMEI=' + encodeURIComponent($('#imei-u').html())";
                elseif($row['sn'] == "1") echo " + '&SN=' + encodeURIComponent($('#sn-u').html())";
                ?> + '&email=' + encodeURIComponent($('#client-email').val()) + '&firstname=' + encodeURIComponent($('#client-firstname').val()) + '&lastname=' + encodeURIComponent($('#client-lastname').val()),
            success: function (res) {
                if(res.substring(0, 8) == "###OK###") {
                    res = res.substring(8);
                    $('#cancel_return').val($('#cancel_return').val() + res);
                    $('#return_url').val($('#return_url').val() + res);
                    $('#notify_url').val($('#notify_url').val() + res);
                    $('#paypal').submit();
                } else {
                    $.jGrowl(res, {theme: 'growlFail'});
                    $('.loading').hide();
                    $('.last').show();
                    return false;
                }
            },
            error: function () {
                $.jGrowl("Something is not quite working.<br />Please refresh this page and start over.<br />If the problem persists, please take a screenshot and send it to our <a mailto='support@prounlockphone.com'>Support Team</a>.", {theme: 'growlFail'});
                $('#modal').modal('toggle');
            }
        });
    });
</script>