<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT * FROM services WHERE id = '" . $_POST['service'] . "'"));
$bargain = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$_SESSION['client_id']}' AND service = '{$_POST['service']}'"));
$sms = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT phone FROM users WHERE id = " . $_SESSION['client_id']));
if($bargain['price']) {
    if($bargain['nature'] == 'impose') {
        if($row['regular_' . $_SESSION['currency']] > $bargain['price']) {
            $price = "<del style='color:crimson'>" . $row['regular_' . $_SESSION['currency']] . "</del> " . $bargain['price'];
        } else {
            $price = $bargain['price'];
        }
    } else {
        if($_SESSION['client_type'] == "reseller") {
            if($row['reseller_' . $_SESSION['currency']] < $bargain['price']) {
                $bargain['price'] = $row['reseller_' . $_SESSION['currency']];
            }
            if($bargain['price'] < $row['regular_' . $_SESSION['currency']]) {
                $price = "<del style='color:crimson'>" . $row['regular_' . $_SESSION['currency']] . "</del> " . $bargain['price'];
            } else {
                $price = $bargain['price'];
            }
        } else {
            if($bargain['price'] < $row['regular_' . $_SESSION['currency']]) {
                $price = "<del style='color:crimson'>" . $row['regular_' . $_SESSION['currency']] . "</del> " . $bargain['price'];
            } else {
                $price = $row['regular_' . $_SESSION['currency']];
            }
        }
    }
} else {
    if($_SESSION['client_type'] == "reseller") {
        if($row['reseller_' . $_SESSION['currency']] < $row['regular_' . $_SESSION['currency']]) {
            $price = "<del style='color:crimson'>" . $row['regular_' . $_SESSION['currency']] . "</del> " . $row['reseller_' . $_SESSION['currency']];
        } else {
            $price = $row['reseller_' . $_SESSION['currency']];
        }
    } else {
        $price = $row['regular_' . $_SESSION['currency']];
    }
}
$form = "";
if($row['imei'] == "1") {
    $form .= "<div class='form-group'>
    <label>IMEI</label><span id='makenmodel' style='display:none;float:right;color:crimson'></span>
    <div class='input-group'>
        <input id='imei' class='form-control' type='text' maxlength='14' />
        <span class='input-group-addon'>
            <span id='check'>0</span>
        </span>\n";
    if($row['bulk'] == "1") {
        $form .= "        <span class='input-group-addon add-to-bulk'>
            <span><span class='glyphicon glyphicon-arrow-down'></span> Add To Bulk</span>
        </span>\n";
    }
    $form .= "    </div>
</div>\n";
    $form .= "<div class='form-group'>Label (optional): <input id='label' class='form-control' type='text' placeholder=\"Helps you better organize your orders, e.g. Marc's iPhone\" maxlength='50' /></div>\n";
    if($row['bulk'] == "1") {
        $form .= "<div class='form-group'>
    <label>Bulk IMEIs</label>
    <textarea id='imeis' style='height: 200px;' class='form-control'></textarea>
</div>\n";
    }
}
if($row['sn'] == "1") {
    $form .= "<div class='form-group'>
    <label>Serial Number</label>
    <div class='input-group'>
        <input id='sn' class='form-control' type='text' maxlength='12' style='text-transform: uppercase' />
        <span class='input-group-addon'>required</span>\n";
    if($row['bulk'] == "1") {
        $form .= "        <span class='input-group-addon add-to-bulk'>
            <span><span class='glyphicon glyphicon-arrow-down'></span> Add To Bulk</span>
        </span>\n";
    }
    $form .= "    </div>
</div>\n";
    $form .= "<div class='form-group'>Label (optional): <input id='label' class='form-control' type='text' placeholder=\"Helps you better organize your orders, e.g. Marc's iPod\" maxlength='50' /></div>\n";
    if($row['bulk'] == "1") {
        $form .= "<div class='form-group'>
    <label>Bulk SNs</label>
    <textarea id='sns' style='height: 200px;text-transform: uppercase' class='form-control'></textarea>
</div>\n";
    }
}
if($row['backupData'] == "1") {
    $form .= "<div class='form-group'>
    <label>Backup Link</label>
    <div class='input-group'>
        <input id='backupLink' class='form-control' type='url' maxlength='512' placeholder='Where you uploaded your backup' />
        <span class='input-group-addon'>required</span>
    </div>
</div>
<div class='form-group'>
    <label>Backup Password</label>
    <div class='input-group'>
        <input id='backupPwd' class='form-control' type='text' maxlength='32' placeholder='Code set by iTunes' />
        <span class='input-group-addon'>required</span>
    </div>
</div>\n";
}
if($row['videoLink'] == "1") {
    $form .= "<div class='form-group'>
    <label>Video Proof Link</label>
    <div class='input-group'>
        <input id='videoLink' class='form-control' type='url' maxlength='512' placeholder='YouTube link to the video you uploaded' />
        <span class='input-group-addon'>required</span>
    </div>
</div>\n";
}
if($row['fileLink'] == "1") {
    $form .= "<div class='form-group'>
    <label>File Link</label>
    <div class='input-group'>
        <input id='fileLink' class='form-control' type='url' maxlength='512' placeholder='Where you uploaded your file' />
        <span class='input-group-addon'>required</span>
    </div>
</div>\n";
}
if($row['udid'] == "1") {
    $form .= "<div class='form-group'>
    <label>Unique Device ID - UDID</label>
    <div class='input-group'>
        <input id='udid' class='form-control' type='text' maxlength='40' style='text-transform: lowercase' />
        <span class='input-group-addon'>required</span>
    </div>
</div>\n";
}
if($row['account'] == "1") {
    $form .= "<div class='form-group'>
    <label>Apple ID</label>
    <div class='input-group'>
        <input id='account' class='form-control' type='email' maxlength='40' style='text-transform: lowercase' />
        <span class='input-group-addon'>required</span>
    </div>
</div>\n";
}
if($row['phone'] == "1") {
    $form .= "<div class='form-group'>
    <label>Phone</label>
    <div class='input-group'>
        <input id='phone' class='form-control' type='tel' maxlength='20' />
        <span class='input-group-addon'>required</span>
    </div>
</div>\n";
}
$form .= "<div class='form-group'>
    <label>Notes for us</label>
    <textarea id='comment' style='height: 100px;' class='form-control'></textarea>
</div>
<div class='form-group'>
    <label>Personal notes</label>
    <textarea id='notes' style='height: 100px;' class='form-control'></textarea>
</div>
<div class='form-group'>
    <label>Additional notification destination (optional)</label>
    <input id='tracker' type='email' class='form-control' style='text-transform: lowercase' placeholder='Other than " . $_SESSION['client_email'] . "' />
</div>
<div class='form-group'>
    " . ($sms['phone'] != "" ? "<div style='float:right'><label><input id='sms' type='checkbox' /> Notify me by SMS (0.10 $/SMS)</label></div>" : "") . "
    <label><input id='email' type='checkbox' checked='true' /> Notify me by eMail</label>
</div>
<div class='form-group'>
    <a class='btn btn-primary order center-block'>place order</a>
</div>";
$info = "<p class='info-row'><b>" . $row['service_name'] . "</b><br />{$row['description']}</p>
<p class='info-row'><b>Price:</b> {$price} {$_SESSION['symbol']}</p>
<p class='info-row'><b>Delivery Time:</b> {$row['delivery_time']}</p>
<p class='info-row'><b>Supported models:</b> " . ($row['models'] != "" ? $row['models'] : "All if applicable") . "</p>
<p class='info-row'><b>Supported status:</b> " . ($row['clean'] == "1" ? "CLEAN" : "<del style='color:crimson'>CLEAN</del>") . " - " . ($row['barred'] == "1" ? "BARRED" : "<del style='color:crimson'>BARRED</del>") . " - " . ($row['blacklisted'] == "1" ? "BLACKLISTED" : "<del style='color:crimson'>BLACKLISTED</del>") . "</p>
<hr style='border-color:black;margin-bottom:0px' />
<p class='info-row center-text'><b>~ Description ~</b></p>
<hr style='border-color:black;margin-top:0px' />
<div class='info-row details'>" . $row['details'] . "</div>
<script>
$(document).ready(function () {
    ";
if($row['imei'] == "1") {
    $info .= "current_imei = '';
    function gen_cd(imei) {
        
        var step2 = 0;
        var step2a = 0;
        var step2b = 0;
        var step3 = 0;

        for(var i = imei.length; i < 14; i++) imei = imei + \"0\";

        for(var i = 1; i < 14; i = i + 2) {
            var step1 = (imei.charAt(i)) * 2 + \"0\";
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
        }
        
        return step3;
    }

    $('#imei').on('change keyup paste', function () {
        var imei = $(this).val();
        imei = remove_not_digits(imei);
        $(this).val(imei);
        if($(this).val().length == 14) $('#check').html(gen_cd(imei));
    });

    function remove_not_digits(imei) {
        return imei.replace(/[^0-9\\n]/gi, '');
    }\n";
    if($row['bulk'] == "1") {
        $info .= "
    $('input').bind('keypress', function (event) {
        if (event.keyCode === 13) {
            if ($('#imei').is(\":focus\")) {
                $('.add-to-bulk').trigger('click');
            } else {
                $('.order').trigger('click');
            }
        }
    });
    $('#imeis').on('keyup', function () {
        var imeis = $('#imeis').val();
        imeis = remove_not_digits(imeis);
        $('#imeis').val(imeis);
    });
    $('.add-to-bulk').on('click', function () {
        if ($('#imei').val() != '' && $('#imei').val().length == 14) {
            var imei = $('#imei').val() + $('#check').html();
        } else {
            var imei = '';
        }

        if (imei != '') {
            $('#imei').val('');
            var bulkValues = $('#imeis').val();
            if (bulkValues == '') {
                bulkValues = imei;
            } else {
                bulkValues = imei + \"\\n\" + bulkValues;
            }
            $('#imeis').val(bulkValues);
            $('#check').html('0');
        } else {
            $.jGrowl('IMEI must be 15 digits to be accepted', {theme: 'growlFail'});
            $('#imei').select('');
        }
    });\n";
    } else {
        $info .= "
    $('input').bind('keypress', function (event) {
        if (event.keyCode === 13) {
            $('.order').trigger('click');
        }
    });\n";
    }
}
if($row['sn'] == "1") {
    $info .= "$('#sn').on('change keyup', function () {
        var sn = $('#sn').val();
        sn = remove_not_chars(sn);
        $('#sn').val(sn);
    });

    function remove_not_chars(sn) {
        return sn.replace(/[^0-9a-zA-Z\\n]/gi, '');
    }\n";
    if($row['bulk'] == "1") {
        $info .= "$('input').bind('keypress', function (event) {
        if (event.keyCode === 13) {
            if ($('#sn').is(\":focus\")) {
                $('.add-to-bulk').trigger('click');
            } else {
                $('.order').trigger('click');
            }
        }
    });
    $('#sns').on('keyup', function () {
        var sns = $('#sns').val();
        sns = remove_not_chars(sns);
        $('#sns').val(sns);
    });
    $('.add-to-bulk').on('click', function () {
        if ($('#sn').val() != '' && $('#sn').val().length >= 8) {
            var sn = $('#sn').val();
        } else {
            var sn = '';
        }

        if (sn != '') {
            $('#sn').val('');
            var bulkValues = $('#sns').val();
            if (bulkValues == '') {
                bulkValues = sn.toUpperCase();
            } else {
                bulkValues = sn.toUpperCase()+ \"\\n\" + bulkValues;
            }
            $('#sns').val(bulkValues);
        }
    });\n";
    } else {
        $info .= "
    $('input').bind('keypress', function (event) {
        if (event.keyCode === 13) {
            $('.order').trigger('click');
        }
    });\n";
    }
}
if($row['udid'] == "1") {
    $info .= "
    $('#udid').on('keyup', function () {
        $('#udid').val(remove_not_hex($('#udid').val()));
    });

    function remove_not_hex(imei) {
        return imei.replace(/[^0-9a-fA-F]/gi, '');
    }\n";
}
$info .= "
    $('.order').on('click', function (ev) {
        ev.preventDefault();\n";
if($row['imei'] == "1") {
    $info .= "        if ($('#imei').val() != '' && $('#imei').val().length == 14) {
            var imeis = $('#imei').val() + $('#check').html();
        } else if ($('#imei').val() != '') {
            $.jGrowl('You IMEI is incomplete', {theme: 'growlFail'});
            $('#imei').select();
            return false;
        } else {
            var imeis = '';
        }\n";
    if($row['bulk'] == '1') {
        $info .= "        if ($('#imeis').val() != '') {
            if(imeis != '') {
                imeis = imeis + \"\\n\" + $('#imeis').val();
            } else {
                imeis = $('#imeis').val();
            }
        }\n";
    }
    $info .= " 
        if (imeis == '') {
            $.jGrowl('You must enter your IMEI', {theme: 'growlFail'});
            $('#imei').focus();
            return false;
        } else {
            var bulk = imeis.split(/\\r\\n|\\r|\\n/g);
            var valid = true;
            var imei = '';
            $.each(bulk, function(index, value) {
                if(isNaN(value) || value.length != 15 || gen_cd(value.substr(0, 14)) != value[14]) {
                    $.jGrowl('Character ' + value + ' at line ' + index + ' does not respect the format', {theme: 'growlFail'});
                    valid = false
                    return false;
                }
            });
            if(!valid) return false;
        }\n";
}
if($row['sn'] == "1") {
    if($row['bulk'] == '1') {
        $info .= "
        if($('#sn').val() == '' && $('#sns').val() == '') {
            $.jGrowl('You must enter at least one Serial Number', {theme: 'growlFail'});
            $('#sn').select();
            return false;
        }\n";
    } else {
        $info .= "
        if($('#sn').val().length < 8) {
            $.jGrowl('You must enter the Serial Number', {theme: 'growlFail'});
            $('#sn').select();
            return false;
        }\n";
    }
}
if($row['backupData'] == "1") {
    $info .= " 
        if($('#backupLink').val() == '') {
            $.jGrowl('You must indicate the location where you uploaded your Backup', {theme: 'growlFail'});
            $('#backupLink').focus();
            return false;
        } else if($('#backupPwd').val() == '') {
            $.jGrowl('You must indicate the password that you set to your Backup', {theme: 'growlFail'});
            $('#backupPwd').focus();
            return false;
        }\n";
}
if($row['videoLink'] == "1") {
    $info .= " 
        if($('#videoLink').val() == '') {
            $.jGrowl('You must indicate the YouTube link to your video in case we need it for verification', {theme: 'growlFail'});
            $('#videoLink').focus();
            return false;
        }\n";
}
if($row['fileLink'] == "1") {
    $info .= " 
        if($('#fileLink').val() == '') {
            $.jGrowl('You must indicate the link to your file', {theme: 'growlFail'});
            $('#fileLink').focus();
            return false;
        }\n";
}
if($row['account'] == "1") {
    $info .= " 
        if($('#account').val() == '') {
            $.jGrowl('You must indicate the Apple ID', {theme: 'growlFail'});
            $('#account').focus();
            return false;
        }\n";
}
if($row['udid'] == "1") {
    $info .= " 
        if($('#udid').val() == '') {
            $.jGrowl('You must indicate the UDID', {theme: 'growlFail'});
            $('#udid').focus();
            return false;
        }\n";
}
if($row['phone'] == "1") {
    $info .= " 
        if($('#phone').val() == '') {
            $.jGrowl('You must indicate the Phone Number', {theme: 'growlFail'});
            $('#phone').focus();
            return false;
        }\n";
}
$info .= "
        $('.order').hide();
        $('.order').closest('div').append('<img class=\"loading\" style=\"width: 25px; display: block; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);\" src=\"https://www.prounlockphone.com/images/loading.gif\">');
        var custom = '';        
        $.ajax({
            type: \"POST\",
            url: 'https://www.prounlockphone.com/order/placeOrder.php',
            data: 'service=' + $('#service').val()";
if($row['imei'] == "1") {
    if($row['bulk'] == "1") {
        $info .= " + '&serials=' + $('#imei').val() + $('#check').html() + '\\n' + $('#imeis').val()";
    } else {
        $info .= " + '&serials=' + $('#imei').val() + $('#check').html()";
    }
} elseif($row['sn'] == "1") {
    if($row['bulk'] == "1") {
        $info .= " + '&serials=' + $('#sn').val() + '\\n' + $('#sns').val()";
    } else {
        $info .= " + '&serials=' + $('#sn').val()";
    }
}
if($row['udid'] == "1") $info .= " + '&udid=' + $('#udid').val()";
if($row['account'] == "1") $info .= " + '&account=' + encodeURIComponent($('#account').val())";
if($row['phone'] == "1") $info .= " + '&phone=' + encodeURIComponent($('#phone').val())";
if($row['backupData'] == "1") $info .= " + '&backupLink=' + encodeURIComponent($('#backupLink').val()) + '&backupPwd=' + encodeURIComponent($('#backupPwd').val())";
if($row['videoLink'] == "1") $info .= " + '&videoLink=' + encodeURIComponent($('#videoLink').val())";
if($row['fileLink'] == "1") $info .= " + '&fileLink=' + encodeURIComponent($('#fileLink').val())";
$info .= " + '&comment=' + encodeURIComponent($('#comment').val()) + '&notes=' + encodeURIComponent($('#notes').val()) + '&sms=' + $('#sms').prop('checked') + '&email=' + $('#email').prop('checked') + '&tracker=' + encodeURIComponent($('#tracker').val()) + '&label=' + encodeURIComponent($('#label').val()),
            success: function (resp) {
                var responseData = JSON.parse(resp);
                if (responseData.type == 0) {
                    responseData.msg.forEach(function (item) {
                        $.jGrowl(item, {theme: 'growlFail'});
                    });
                } else {
                    responseData.msg.forEach(function (item) {
                        $.jGrowl(item, {theme: 'growlSuccess'});
                    });
                    $('#balance').html(responseData.data.balance);
                    $('#balance').css('color', responseData.data.color);
                }";
if($row['backupData'] == "1") {
    $info .= "
                $('#backupLink').val('');
                $('#backupPwd').val('');";
}
if($row['videoLink'] == "1") {
    $info .= "
                $('#videoLink').val('');";
}
if($row['fileLink'] == "1") {
    $info .= "
                $('#fileLink').val('');";
}
if($row['account'] == "1") {
    $info .= "
                $('#account').val('');";
}
if($row['udid'] == "1") {
    $info .= "
                $('#account').val('');";
}
if($row['phone'] == "1") {
    $info .= "
                $('#account').val('');";
}
if($row['imei'] == "1") {
    $info .= "
                $('#imei').val('');
                $('#check').html('0');";
    if ($row['bulk'] == "1") $info .= "
                $('#imeis').val('');";
} elseif($row['sn'] == "1") {
     $info .= "
                $('#sn').val('');";
     if($row['bulk'] == "1") $info .= "
                $('#sns').val('');";
}
$info .= "
                $('#label').val('');
                $('.order').show();
                $('.loading').remove();
            },
            error: showError
        });
    });
});
	</script>";
$response = array(
    "type" => 1,
    "msg" => [""],
    "data" => array(
        "form" => $form,
        "info" => $info
    )
);
echo json_encode($response);
?>