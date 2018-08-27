<?php
if(isset($_GET['session'])) {
    session_id($_GET['session']);
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Add Credits") ?>
    </head>
    <?php
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance FROM users WHERE users.id = " . $_SESSION['client_id']));
    $row['balance'] < 0 ? $color = "red" : $color = "green";
    ?>
    <body class="stretched device-lg">
	    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php header_render("payment") ?>
            <section id="content" class="account" style="margin-bottom: 0px">
                <div class="container">
                    <div class="col-md-12 margin30 notopmargin">
                        <div class="curved-widget widget-white">
                            <div class="widget-name-rev center-text">
                                Add Funds
                            </div>
                            <div class="widget-content container-fluid" id="paymentForm">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><?php echo $_SESSION['symbol'] ?></span>
                                                <input type="number" id="customAmount" class="form-control" placeholder="<?php if($row['balance'] < 0) { echo  (-1 * number_format($row['balance'], 2, ".", ","));} else { echo '0.00';} ?>"<?php if($row['balance'] < 0) { echo  'value="' . (-1 * number_format($row['balance'], 2, ".", ",")) . '"';} ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>
                                            Payment Method
                                        </label>
                                        <select id="paymentMethod" name='paymentMethod' class="form-control">
                                            <option value="">Select...</option>
                                            <option value="pay">PayPal (Pay for goods or services)</option>
                                            <option value="gift">PayPal (Send to friends or family)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 margin30">
                                        <div class="form-group">
                                            <form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                                <table>
                                                    <tr>
                                                        <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on0" value="Client" /></td>
                                                        <td><input type="hidden" readonly="true" name="os0" maxlength="200" value="<?php echo $_SESSION['client_short'] . " " . $_SESSION['last_name'] ?>" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on1" value="Username" /></td>
                                                        <td><input type="hidden" readonly="true" name="os1" maxlength="200" value="<?php echo $_SESSION['username'] ?>" id="username" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on2" value="Account" /></td>
                                                        <td><input type="hidden" readonly="true" name="os2" maxlength="200" value="<?php echo $_SESSION['client_email'] ?>" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on3" value="Top up amount" /></td>
                                                        <td><input type="hidden" readonly="true" name="os3" maxlength="200" value="0" id="credits" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right;padding-right: 10px;"><input type="hidden" name="on4" value="Currency" /></td>
                                                        <td><input type="hidden" readonly="true" name="os4" maxlength="200" value="<?php echo $_SESSION['currency'] ?>" id="curency" /></td>
                                                    </tr>
                                                </table>

                                                <input type="hidden" name="cmd" value="_ext-enter">
                                                <input type="hidden" name="redirect_cmd" value="_xclick">
                                                <input type="hidden" name="business" value="paypal@prounlockphone.com" />
                                                <input type="hidden" name="currency_code" value="<?php echo $_SESSION['currency'] ?>" />
                                                <input type="hidden" name="item_name" value="PROunlockPhone Top Up Account" />
                                                <input type="hidden" name="no_note" value="1" />
                                                <input type="hidden" name="no_shipping" value="1" />
                                                <input type="hidden" name="handling" value="0" id="handling" />
                                                <input type="hidden" name="amount" value="0" id="amount" />
                                                <input type="hidden" name="email" value="<?php echo $_SESSION['client_email'] ?>" />
                                                <input type="hidden" name="first_name" value="<?php echo $_SESSION['client_short'] ?>" />
                                                <input type="hidden" name="last_name" value="<?php echo $_SESSION['last_name'] ?>" />
                                                <input type="hidden" name="image_url" value="https://www.prounlockphone.com/images/pup82x50.png" />
                                                <input type="hidden" name="return" value="https://www.prounlockphone.com/payment/success.php?session=<?php echo session_id() ?>" />
                                                <input type="hidden" name="cancel_return" value="https://www.prounlockphone.com/payment/?return=cancel&session=<?php echo session_id() ?>" />
                                                <input id="notify_url" type="hidden" name="notify_url" value="https://www.prounlockphone.com/confirm.php?client=<?php echo $_SESSION['client_id'] ?>&amount=" />
                                                <div class="col-md-12 center">
                                                    <div class="form-group">
                                                        <a style="margin: 5px;" class="btn btn-default" data-toggle="modal" data-target="#modal" data-webx="https://www.prounlockphone.com/payment/refund-policy.php">Read our reimbursement policy</a>
                                                        <a style="margin: 5px;" class="btn btn-default" data-toggle="modal" data-target="#modal" data-webx="https://www.prounlockphone.com/payment/gift-policy.php">Send as gift instructions</a>
                                                        <a id="info" style="margin: 5px;" class="btn btn-info disabled">Enter amount and select option</a>
                                                        <a id="asGift" style="margin: 5px;" class="btn btn-primary hidden" data-toggle="modal" data-target="#modal" data-webx="https://www.prounlockphone.com/payment/confirm-gift.php" data-backdrop="static" data-keyboard="false">Add as Gift</a>
                                                        <button id="asPayment" class="btn btn-primary hidden submit">Add Credits</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <table align="center">
                                                <tr>
                                                    <td align="right" style="border-bottom: black 1px solid">Current balance:</td>
                                                    <td style="padding-left:10px;border-bottom: black 1px solid"><b><?php echo number_format($row['balance'], 2, ".", ",") ?></b></td>
                                                </tr>
                                                <tr>
                                                    <td align="right">Fees:</td>
                                                    <td style="padding-left:10px" id="fees">--.--</td>
                                                </tr>
                                                <tr>
                                                    <td align="right">Taxe 5%:</td>
                                                    <td style="padding-left:10px" id="taxe">--.--</td>
                                                </tr>
                                                <tr>
                                                    <td align="right">To pay:</td>
                                                    <td style="padding-left:10px" id="topay">--.--</td>
                                                </tr>
                                                <tr>
                                                    <td align="right" style="border-top: black 1px solid">New balance:</td>
                                                    <td style="padding-left:10px;border-top: black 1px solid" id="newbalance"><b>--.--</b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
<?php
$locked = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'locked' FROM orders WHERE client='{$_SESSION['client_id']}' AND (status = 'Pending' OR status = 'In process')"));
$locked_amount = $locked['locked'] == "" ? 0 : $locked['locked'];
if($row['balance'] + $locked_amount < 0) {
    $due =  $row['balance'] + $locked_amount;
    $color2 = "red";
} else {
    $due =  0;
    $color2 = "green";
}
?>
                    <div class="col-md-4">
			            <div class="curved-widget widget-<?php echo $color2 ?> center-text">
                            <div class="widget-amount<?php if($due != 0) echo " blink" ?>"><?php echo number_format($due, 2, ".", ",") . " " . $_SESSION['symbol'] ?></div>
                            <div class="widget-name">Unpaid Money</div>
			            </div>
                    </div>
                    <div class="col-md-4">
			            <div class="curved-widget widget-<?php echo $color ?> center-text">
                            <div class="widget-amount"><?php echo number_format($row['balance'], 2, ".", ",") . " " . $_SESSION['symbol'] ?></div>
                            <div class="widget-name">Current Balance</div>
			            </div>
                    </div>
                    <div class="col-md-4">
			            <div class="curved-widget widget-black center-text">
                            <div class="widget-amount"><?php echo number_format($locked_amount, 2, ".", ",") . " " . $_SESSION['symbol'] ?></div>
                            <div class="widget-name">Locked Amount</div>
			            </div>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
        <script language="JavaScript">
            var fees =  0.5;
            var taxe = 5;
            function setAmounts(fees, taxe) {
                if($('#customAmount').val() <= '0' || $('#customAmount').val() == '') {
                    $('#fees').html('--.--');
                    $('#taxe').html('--.--');
                    $('#topay').html('--.--');
                    $('#newbalance').html('--.--');
                    $('#asPayment').addClass('hidden');
                    $('#asGift').addClass('hidden');
                    $('#info').removeClass('hidden');
                } else if($('#paymentMethod').val() == '') {
                    $('#fees').html('--.--');
                    $('#taxe').html('--.--');
                    $('#newbalance').html((Math.ceil((parseFloat(<?php echo $row['balance'] ?>) + parseFloat($('#customAmount').val())) * 100) / 100).toFixed(2));
                    $('#asPayment').addClass('hidden');
                    $('#asGift').addClass('hidden');
                    $('#info').removeClass('hidden');
                } else {
                    if(fees == 0) {
                        $('#fees').html('--.--');
                    } else {
                        $('#fees').html((fees).toFixed(2));
                    }
                    var taxeToPay = Math.ceil($('#customAmount').val() * taxe) / 100;
                    $('#info').addClass('hidden');
                    if(taxe == 0) {
                        $('#taxe').html('--.--');
                        $('#asPayment').addClass('hidden');
                        $('#asGift').removeClass('hidden');
                    } else {
                        $('#taxe').html((taxeToPay).toFixed(2));
                        $('#asPayment').removeClass('hidden');
                        $('#asGift').addClass('hidden');
                    }
                    $('#topay').html((Math.ceil((parseFloat($('#customAmount').val()) + parseFloat(fees) + parseFloat(taxeToPay)) * 100) / 100).toFixed(2));
                    $('#newbalance').html((Math.ceil((parseFloat(<?php echo $row['balance'] ?>) + parseFloat($('#customAmount').val())) * 100) / 100).toFixed(2));
                    $('#handling').val((Math.ceil((parseFloat(fees) + parseFloat(taxeToPay)) * 100) / 100).toFixed(2));
                    $('#amount').val((Math.ceil(parseFloat($('#customAmount').val()) * 100) / 100).toFixed(2));
                    $('#credits').val((Math.ceil(parseFloat($('#customAmount').val()) * 100) / 100).toFixed(2));
                }
            }
$(document).ready(function () {
    <?php
    if(isset($_GET['return']) && $_GET['return'] == 'cancel') {
    ?>
    $.jGrowl("Your transaction was cancelled.<br />Your balance was not affected.", {theme: 'growlFail'});
    <?php
    } elseif(isset($_GET['return']) && $_GET['return'] == 'duplicated') {
    ?>
    $.jGrowl("Your transaction was not completed.<br />Your transaction ID was previously processed.", {theme: 'growlFail'});
    <?php
    }
    ?>
    $('#customAmount').on('change blur keypress keydown keyup', function() {
        setAmounts(fees, taxe);
    });
    $('#paymentMethod').change(function() {
        if($(this).val() == 'pay') {
            fees = 0.5;
            taxe = 5;
        } else if($(this).val() == 'gift') {
            fees = 0;
            taxe = 0;
        } else {
            fees = 0;
            taxe = 0;
        }
        setAmounts(fees, taxe)
    });

    $('#asPayment').click(function(){
        $('#customAmount').prop('readonly', true);
        $('#paymentMethod').prop('disabled', true);
        $('#notify_url').val($('#notify_url').val() + $('#customAmount').val());
        $(this).hide().closest('div').append('<img class="loading center" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    });
    $('#asGift').click(function(){
        $('#customAmount').prop('readonly', true);
        $('#paymentMethod').prop('disabled', true);
    });
});

function paypalDone() {
    $('#infoDiv').slideUp();
    $('#details').slideDown();
    $('#paypalDone').hide();
    $('#confirm').show();
    $('#trxID').select();
}
function cancelFun() {
    $('#customAmount').prop('readonly', false);
    $('#paymentMethod').prop('disabled', false);
}
        </script>
    </body>
</html>