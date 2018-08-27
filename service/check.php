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

<script src='https://www.google.com/recaptcha/api.js'></script>
<div class='modal-header'>
    <button aria-hidden='true' class='close last' data-dismiss='modal'>×</button>
    <h4 class='modal-title'><?php echo $row['service_name'] ?></h4>
</div>
<div id="order-details">
    <div class='modal-body'>
        <div>
            <p><b>Step 1:</b> Enter your iPhone details</p>
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
            ?>
            <div align="center" class="g-recaptcha" data-callback="recaptchaCallback" data-expired-callback="recaptchaExpired" data-sitekey="6Lc4RREUAAAAAAE8igcAvJHtrA46STBiIVxrYcbj"></div>
        </div>
    </div>
    <div class='modal-footer'>
        <button class='btn btn-default' data-dismiss='modal'>Cancel</button>
        <button id="goStep2" class='btn btn-info disabled' disabled="true">Process <?php echo ($row['imei'] == "1" ? "IMEI" : "S/N") ?></button>
    </div>
</div>
<div id="order-review" style="display:none">
    <div class='modal-body'>
        <div align="center" id="processing">
            <div align="center">
                <img src="https://www.prounlockphone.com/images/process.gif" />
            </div>
        </div>
        <div id="processed"></div>
    </div>
    <div class='modal-footer last'>
        <button id="goStep3" data-dismiss='modal' class='btn btn-success'>I'm done</button>
    </div>
</div>
<script>
    var captcha = false;
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
        checkReadyToProcess();
    });
    <?php
    }
    if($row['sn'] == "1") {
        ?>$('#sn').on('change keyup paste', function () {
        var sn = $('#sn').val();
        sn = sn.replace(/[^0-9a-zA-Z]/gi, '');
        $('#sn').val(sn);
        checkReadyToProcess();
    });
    <?php
    }
    ?>

    function checkReadyToProcess() {
        if(captcha<?php
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
        $('.last').hide();
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
        if(!captcha) {
            $.jGrowl('You must pass the captcha', {theme: 'growlFail'});
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
        ?>
        $('#order-details').slideUp();
        $('#order-review').slideDown();
        $.ajax({
            type: 'POST',
            url: 'https://www.prounlockphone.com/service/processInstantOrder.php',
            data: 'service=<?php
            echo $_GET['service'] . "'";
            if($row['imei'] == "1") echo " + '&serial=' + encodeURIComponent(imeis)";
            elseif($row['sn'] == "1") echo " + '&serial=' + encodeURIComponent($('#sn').html().toUpperCase())";
            ?>,
            success: function (res) {
                if(res == 'Failed' || res == '') {
                    $.jGrowl("Something is not quite working.<br />Please refresh this page and start over.<br />If the problem persists, please take a screenshot and send it to our <a mailto='support@prounlockphone.com'>Support Team</a>.", {theme: 'growlFail'});
                    $('#modal').modal('toggle');
                } else {
                    $('#processed').html(res);
                    $('#processing').hide();
                    $('#processed').show();
                    $('.last').show();
                    $('#goStep3').focus();
                }
            },
            error: function () {
                $.jGrowl("Something is not quite working.<br />Please refresh this page and start over.<br />If the problem persists, please take a screenshot and send it to our <a mailto='support@prounlockphone.com'>Support Team</a>.", {theme: 'growlFail'});
                $('#modal').modal('toggle');
            }
        });
    });
    <?php
        if($row['imei'] == "1") {
            echo "$('#imei').select();";
        } else {
            echo "$('#sn').select();";
        }
    ?>

    function recaptchaCallback() {
        captcha = true;
        checkReadyToProcess();
    };
    function recaptchaExpired() {
        captcha = false;
        checkReadyToProcess();
    };
</script>