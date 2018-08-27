<?php
if(!isset($_GET['service'])) {
    header("Location: https://www.prounlockphone.com/services/");
    exit();
}
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
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
                    <input id='sn' class='form-control order-put' type='text' maxlength='13' style='text-transform: uppercase' placeholder='SN' />
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <?php
}
if($row['backupData'] == "1") {
            ?><div class='form-group'>
                <label>Backup Link</label>
                <div class='input-group'>
                    <input id='backupLink' class='form-control order-put' type='url' maxlength='512' placeholder='Where you uploaded your backup' />
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <div class='form-group'>
                <label>Backup Password</label>
                <div class='input-group'>
                    <input id='backupPwd' class='form-control order-put' type='text' maxlength='32' placeholder='Code set by iTunes' />
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <?php
}
if($row['videoLink'] == "1") {
            ?><div class='form-group'>
                <label>Video Proof Link</label>
                <div class='input-group'>
                    <input id='videoLink' class='form-control order-put' type='url' maxlength='512' placeholder='YouTube link to the video you uploaded' />
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <?php
}
if($row['fileLink'] == "1") {
            ?><div class='form-group'>
                <label>File Link</label>
                <div class='input-group'>
                    <input id='fileLink' class='form-control order-put' type='url' maxlength='512'  placeholder='Where you uploaded your file'/>
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <?php
}
if($row['udid'] == "1") {
            ?><div class='form-group'>
                <label>Unique Device ID - UDID</label>
                <div class='input-group'>
                    <input id='udid' class='form-control order-put' type='text' maxlength='40' style='text-transform: lowercase' placeholder='40 hex charatcters (0-9a-f)' />
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <?php
}
if($row['account'] == "1") {
            ?><div class='form-group'>
                <label>Apple ID</label>
                <div class='input-group'>
                    <input id='account' class='form-control order-put' type='email' maxlength='40' style='text-transform: lowercase' placeholder='eMail address' />
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <?php
}
if($row['phone'] == "1") {
            ?><div class='form-group'>
                <label>Phone</label>
                <div class='input-group'>
                    <input id='phone' class='form-control order-put' type='tel' maxlength='20' placeholder="Owner's phone number" />
                    <span class='input-group-addon'>required</span>
                </div>
            </div>
            <?php
}
            ?><div class='form-group'>
                <label>Notes for us</label>
                <textarea id='comment' style='height: 60px;' class='form-control' placeholder='Any comment to our attention regarding your order'></textarea>
            </div>
            <div class='form-group'>
                <label>Personal notes</label>
                <textarea id='notes' style='height: 60px;' class='form-control' placeholder='Leave comments to help yourself better organize your orders'></textarea>
            </div>
            <div class='form-group'>
                <div style='float:right'><input id='sms' type='checkbox' /> Notify me by SMS (0.10 $/SMS)</div>
                <input id='email' type='checkbox' checked='true' disabled="true" /> Notify me by eMail
            </div>
        </div>
    </div>
    <div class='modal-footer'>
        <button class='btn btn-default' data-dismiss='modal'>Cancel</button>
        <button id="goStep2" class='btn btn-info disabled' disabled="true">Enter communication details</button>
    </div>
</div>
<div id="client-details" style="display:none">
    <div class='modal-body'>
        <div>
            <p><b>Step 2:</b> Enter your contact information</p>
            <hr />
            <form>
                <div class='form-group'>
                    <label>First Name</label>
                    <div class='input-group'>
                        <input name="firstname" id='client-firstname' class='form-control client-put' type="text" placeholder='First Name' style="text-transform: capitalize" />
                        <span class='input-group-addon'>optional</span>
                    </div>
                </div>
                <div class='form-group'>
                    <label>Last Name</label>
                    <div class='input-group'>
                        <input name="lastname" id='client-lastname' class='form-control client-put' type="text" placeholder='Last Name' style="text-transform: capitalize" />
                        <span class='input-group-addon'>optional</span>
                    </div>
                </div>
                <div class='form-group'>
                    <label>eMail Address</label> (results will be sent here)
                    <div class='input-group'>
                        <input name="email" id='client-email' class='form-control client-put' type='email' maxlength='80' style='text-transform: lowercase' placeholder='eMail of contact where we can reach you out' />
                        <span class='input-group-addon' style="background-color: crimson;color:white">required</span>
                    </div>
                </div>
                <div class='form-group' id="client-phone-div" style="display:none">
                    <label>Phone</label>
                    <div class='input-group'>
                        <input name="phone" id='client-phone' class='form-control client-put' type='tel' maxlength='20' placeholder="International format (e.g. +12104544850)" />
                        <span class='input-group-addon'>required</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class='modal-footer'>
        <button class='btn btn-default' data-dismiss='modal'>Cancel</button>
        <button class='btn btn-default' onclick="$('#client-details').slideUp();$('#order-details').slideDown();"><< Back</button>
        <button id="goStep3" class='btn btn-info disabled' disabled="true">Order Review</button>
    </div>
</div>
<div id="order-review" style="display:none">
    <div class='modal-body'>
        <p><b>Step 3:</b> Order Summary</p>
        <hr />
        <label>Service Name</label> <b class="text-danger"><?php echo $row['service_name'] ?></b>
        <br />
        <label>Service Cost</label> <?php echo number_format($row["regular_{$_SESSION['currency']}"], 2, ".", ",") ?> <label style="margin-left:20px">Fees</label> 5% = <?php echo number_format($row["regular_{$_SESSION['currency']}"] / 100 * 5, 2, ".", ",") ?> <label style="margin-left:20px">Handling</label> 0.50<a id="smsFee"><label style="margin-left:20px"> SMS</label> 0.10</a> <label style="margin-left:20px">Total</label> <b class="bg-primary" id="total"></b>
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
        if($row['backupData'] == "1") {
            ?><br />
        <label>Backup URL</label> <a class="text-muted" id="backupLink-u"></a>
        <br />
        <label>iTunes Password</label> <a class="text-muted" id="backupPwd-u"></a>
        <?php
        }
        if($row['videoLink'] == "1") {
            ?><br />
        <label>Video Link</label> <a class="text-muted" id="videoLink-u"></a>
        <?php
        }
        if($row['fileLink'] == "1") {
            ?><br />
        <label>File Link</label> <a class="text-muted" id="fileLink-u"></a>
        <?php
        }
        if($row['udid'] == "1") {
            ?><br />
        <label>UDID</label> <a class="text-muted text-lowercase" id="udid-u"></a>
        <?php
        }
        if($row['account'] == "1") {
            ?><br />
        <label>Apple ID</label> <a class="text-muted text-lowercase" id="account-u"></a>
        <?php
        }
        if($row['phone'] == "1") {
            ?><br />
        <label>Phone</label> <a class="text-muted" id="phone-u"></a>
        <?php
        }
        ?><br />
        <div id='comment-div'>
            <label>Notes for us</label> <a class="text-muted" id='comment-u'></a>
        </div>
        <div id='notes-div'>
            <label>Personal notes</label> <a class="text-muted" id='notes-u'></a>
        </div>
        <hr />
        <label>Client</label> <a class="text-primary" id="client-u"></a>
        <br />
        <label>eMail Address</label> <a class="text-primary text-lowercase" id="client-email-u"></a>
        <div id='client-phone-rev'>
            <label>Phone</label> <a class="text-primary" id="client-phone-u"></a>
        </div>
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
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on2" value="Phone Number" /></td>
                    <td><input type="hidden" name="os2" id="os2" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on3" value="Service Name" /></td>
                    <td><input type="hidden" name="os3" value="<?php echo $row['service_name'] ?>" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on4" value="Currency" /></td>
                    <td><input type="hidden" name="os4" value="<?php echo $_SESSION['currency'] ?>" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on5" value="IMEI/SN" /></td>
                    <td><input type="hidden" name="os5" id="os5" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on6" value="Processing Time" /></td>
                    <td><input type="hidden" name="os6" value="<?php echo $row['delivery_time'] ?>" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on7" value="Service Cost" /></td>
                    <td><input type="hidden" name="os7" value="<?php echo $row["regular_{$_SESSION['currency']}"] ?>" /></td>
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
        <button class='btn btn-default last' onclick="$('#order-review').slideUp();$('#client-details').slideDown();"><< Back</button>
        <button id="goStep4" class='btn btn-info last'>Pay with PayPal</button>
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
        checkReady();
    });
    <?php
    }
    if($row['sn'] == "1") {
        ?>$('#sn').on('change keyup paste', function () {
        var sn = $('#sn').val();
        sn = sn.replace(/[^0-9a-zA-Z]/gi, '');
        $('#sn').val(sn);
        checkReady();
    });
    <?php
    }
    if($row['udid'] == "1") {
        ?>$('#udid').on('change keyup paste', function () {
        $('#udid').val($('#udid').val().replace(/[^0-9a-fA-F]/gi, ''));
        checkReady();
    });
    <?php
    }
    if($row['backupData'] == "1") {
    ?>
    $('#backupLink').on('change keyup paste', function () {
        checkReady();
    });
    $('#backupPwd').on('change keyup paste', function () {
        checkReady();
    });
    <?php
    }
    if($row['videoLink'] == "1") {
    ?>$('#videoLink').on('change keyup paste', function () {
        checkReady();
    });
    <?php
    }
    if($row['fileLink'] == "1") {
    ?>$('#fileLink').on('change keyup paste', function () {
        checkReady();
    });
    <?php
    }
    if($row['account'] == "1") {
    ?>$('#account').on('change keyup paste', function () {
        checkReady();
    });
    <?php
    }
    if($row['phone'] == "1") {
    ?>$('#phone').on('change keyup paste', function () {
        checkReady();
    });
    <?php
    }
    ?>$('#goStep2').on('click', function (ev) {
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
    if($row['backupData'] == "1") {
            ?>if($('#backupLink').val() == '') {
            $.jGrowl('You must indicate the location where you uploaded your Backup', {theme: 'growlFail'});
            $('#backupLink').select();
            return false;
        } else if($('#backupPwd').val().length < 4) {
            $.jGrowl('You must indicate the password that you set to your Backup', {theme: 'growlFail'});
            $('#backupPwd').select();
            return false;
        }
        <?php
    }
    if($row['videoLink'] == "1") {
            ?>if($('#videoLink').val() == '') {
            $.jGrowl('You must indicate the YouTube link to your video in case we need it for verification', {theme: 'growlFail'});
            $('#videoLink').select();
            return false;
        }
        <?php
    }
    if($row['fileLink'] == "1") {
            ?>if($('#fileLink').val() == '') {
            $.jGrowl('You must indicate the link to your file', {theme: 'growlFail'});
            $('#fileLink').select();
            return false;
        }
        <?php
    }
    if($row['account'] == "1") {
            ?>var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if(!pattern.test($('#account').val())) {
            $.jGrowl('You must indicate the Apple ID', {theme: 'growlFail'});
            $('#account').select();
            return false;
        }
        <?php
    }
    if($row['udid'] == "1") {
            ?>if($('#udid').val().length < 40) {
            $.jGrowl('You must indicate the UDID', {theme: 'growlFail'});
            $('#udid').select();
            return false;
        }
        <?php
    }
    if($row['phone'] == "1") {
            ?>if($('#phone').val() == '') {
            $.jGrowl('You must indicate the Phone Number', {theme: 'growlFail'});
            $('#phone').select();
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
    if($row['backupData'] == "1") {
        ?>$('#backupLink-u').html($('#backupLink').val());
        $('#backupPwd-u').html($('#backupPwd').val());
        <?php
    }
    if($row['videoLink'] == "1") {
        ?>$('#videoLink-u').html($('#videoLink').val());
        <?php
    }
    if($row['fileLink'] == "1") {
        ?>$('#fileLink-u').html($('#fileLink').val());
        <?php
    }
    if($row['account'] == "1") {
        ?>$('#account-u').html($('#account').val().toLowerCase());
        <?php
    }
    if($row['udid'] == "1") {
        ?>$('#udid-u').html($('#udid').val());
        <?php
    }
    if($row['phone'] == "1") {
        ?>$('#phone-u').html($('#phone').val());
        <?php
    }
        ?>
        if($('#comment').val() == '') {
            $('#comment-div').hide();
        } else {
            $('#comment-u').html($('#comment').val().replace(/\r\n|\r|\n/g,"<br />"));
            $('#comment-div').show();
        }
        if($('#notes').val() == '') {
            $('#notes-div').hide();
        } else {
            $('#notes-u').html($('#notes').val().replace(/\r\n|\r|\n/g,"<br />"));
            $('#notes-div').show();
        }
        <?php
            if($row['imei'] == "1") {
                ?>$('#os5').val(imeis);
        $('#item_name').val($('#item_name').val() + imeis);
        <?php
            } elseif($row['sn'] == "1") {
                ?>$('#os5').val($('#sn').val().toUpperCase());
        $('#item_name').val($('#item_name').val() + $('#sn').val().toUpperCase());
        <?php
        }
        ?>
        $('#order-details').slideUp();
        $('#client-details').slideDown();
        if($('#sms').prop('checked')) $('#client-phone-div').show();
        $('#client-firstname').select();
    });
    function checkReady() {
        <?php
            if($row['account'] == "1") {
                ?>var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        <?php
            }
        ?>if(true<?php
            if($row['imei'] == "1") {
            ?> && $('#imei').val().length == 14<?php
            }
            if($row['sn'] == "1") {
            ?> && $('#sn').val().length >= 8<?php
            }
            if($row['udid'] == "1") {
            ?> && $('#udid').val().length == 40<?php
            }
            if($row['backupData'] == "1") {
            ?> && $('#backupLink').val() != '' && $('#backupPwd').val().length > 3<?php
            }
            if($row['videoLink'] == "1") {
            ?> && $('#videoLink').val() != ''<?php
            }
            if($row['fileLink'] == "1") {
            ?> && $('#fileLink').val() != ''<?php
            }
            if($row['account'] == "1") {
            ?> && pattern.test($('#account').val())<?php
            }
            if($row['phone'] == "1") {
            ?> && $('#phone').val() != ''<?php
            }
            ?>) {
            $('#goStep2').removeClass('disabled').prop('disabled', false);
        } else {
            $('#goStep2').addClass('disabled').prop('disabled', true);
        }
    }
    <?php
        if($row['imei'] == "1") {
            echo "$('#imei').select();";
        } else {
            echo "$('#sn').select();";
        }
    ?>

    $('.client-put').bind('keypress', function (event) {
        if (event.keyCode === 13) {
            $('#goStep3').trigger('click');
        }
    });
    // $('#client-firstname, #client-lastname, #client-email, #client-phone').on('change keyup paste', function () {
    $('#client-email, #client-phone').on('change keyup paste', function () {
        checkReadyToPay();
    });
    $('#goStep3').on('click', function (ev) {
        ev.preventDefault();
        $('#client-firstname').val($('#client-firstname').val().trim());
        $('#client-lastname').val($('#client-lastname').val().trim());
        // if($('#client-firstname').val().length < 3) {
        //     $.jGrowl('You must enter your first name', {theme: 'growlFail'});
        //     $('#client-firstname').select();
        //     return false;
        // }
        // if($('#client-lastname').val().length < 3) {
        //     $.jGrowl('You must enter your last name', {theme: 'growlFail'});
        //     $('#client-lastname').select();
        //     return false;
        // }
        if($('#sms').prop('checked') && $('#client-phone').val().length < 8) {
            $.jGrowl('You must enter a valid phone number to receive notifications regarding your order.', {theme: 'growlFail'});
            $('#client-phone').select();
            return false;
        }
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        if(!pattern.test($('#client-email').val())) {
            $.jGrowl('You must enter a valid email address where we can send you notifications regarding your order.', {theme: 'growlFail'});
            $('#client-email').select();
            return false;
        } else {
            $('#goStep3').addClass('disabled').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: 'https://www.prounlockphone.com/service/validator.php',
                data: 'email=' + $('#client-email').val(),
                success: function (resp) {
                    var responseData = JSON.parse(resp);
                    if(responseData.message != '') $.jGrowl(responseData.message, {theme: 'growlFail'});
                    if(responseData.valid == "OK") {
                        $('#goStep3').removeClass('disabled').prop('disabled', false);
                        var name = $('#client-firstname').val().charAt(0).toUpperCase() + $('#client-firstname').val().substr(1).toLowerCase() + " " + $('#client-lastname').val().charAt(0).toUpperCase() + $('#client-lastname').val().substr(1).toLowerCase();
                        name = name.trim();
                        if(name == "") name = "Guest";
                        $('#client-u').html(name);
                        $('#client-email-u').html($('#client-email').val().toLowerCase());
                        if($('#sms').prop('checked')) {
                            var FeeSMS = 0.1;
                            $('#client-phone-u').html($('#client-phone').val());
                            $('#client-phone-rev').show();
                            $('#smsFee').show();
                        } else {
                            var FeeSMS = 0.0;
                            $('#client-phone-rev').hide();
                            $('#smsFee').hide();
                        }
                        $('#os0').val(name);
                        $('#os1').val($('#client-email').val().toLowerCase());
                        $('#os2').val($('#client-phone').val());

                        $('#total').html('&nbsp;' + (parseFloat(<?php echo number_format(($row["regular_{$_SESSION['currency']}"] / 100 * 105) + 0.5, 2, ".", ",") ?>) + parseFloat(FeeSMS)).toFixed(2) + ' <?php echo $_SESSION['symbol'] ?>&nbsp;');
                        $('#handling').val((parseFloat(<?php echo number_format(($row["regular_{$_SESSION['currency']}"] / 100 * 5) + 0.5, 2, ".", ",") ?>) + parseFloat(FeeSMS)).toFixed(2));

                        $('#payer-email').val($('#client-email').val().toLowerCase());
                        $('#first_name').val($('#client-firstname').val());
                        $('#last_name').val($('#client-lastname').val());
                        $('#client-details').slideUp();
                        $('#order-review').slideDown();
                        $('#goStep4').focus();
                    } else {
                        $.jGrowl('We were unable to validate the authenticity of the given email address.', {theme: 'growlFail'});
                        $.jGrowl('Please ensure entering a valid and existing email address.', {theme: 'growlFail'});
                        $.jGrowl('Fake email addresses are rejected and you might find your IP address banned.', {theme: 'growlFail'});
                        $('#client-email').select();
                        return false;
                    }
                },
                error: function () {
                    console.log('Error retrieving data');
                }
            });
        }
    });
    function checkReadyToPay() {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        // if($('#client-firstname').val().length > 2 && $('#client-lastname').val().length > 2 && pattern.test($('#client-email').val()) && ($('#client-phone').val().length > 7 || !$('#sms').prop('checked'))) {
        if(pattern.test($('#client-email').val()) && ($('#client-phone').val().length > 7 || !$('#sms').prop('checked'))) {
            $('#goStep3').removeClass('disabled').prop('disabled', false);
        } else {
            $('#goStep3').addClass('disabled').prop('disabled', true);
        }
    }
    $('#goStep4').on('click', function (ev) {
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
                if($row['udid'] == "1") echo " + '&udid=' + encodeURIComponent($('#udid').val())";
                if($row['account'] == "1") echo " + '&account=' + encodeURIComponent($('#account').val())";
                if($row['phone'] == "1") echo " + '&phone=' + encodeURIComponent($('#phone').val())";
                if($row['backupData'] == "1") echo " + '&backupLink=' + encodeURIComponent($('#backupLink').val()) + '&backupPwd=' + encodeURIComponent($('#backupPwd').val())";
                if($row['videoLink'] == "1") echo " + '&videoLink=' + encodeURIComponent($('#videoLink').val())";
                if($row['fileLink'] == "1") echo " + '&fileLink=' + encodeURIComponent($('#fileLink').val())";
                ?> + '&comment=' + encodeURIComponent($('#comment').val()) + '&notes=' + encodeURIComponent($('#notes').val()) + '&smsEnabled=' + $('#sms').prop('checked') + '&sms=' + encodeURIComponent($('#client-phone').val()) + '&email=' + encodeURIComponent($('#client-email').val()) + '&firstname=' + encodeURIComponent($('#client-firstname').val()) + '&lastname=' + encodeURIComponent($('#client-lastname').val()),
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