<div class='modal-header'>
    <button aria-hidden='true' class='close last' data-dismiss='modal' type='button' onclick="cancelFun()">×</button>
    <h4 class='modal-title'>Transaction details</h4>
</div>
<div class='modal-body'>
    <div id="infoDiv">
        <p>Click the link below to visit PayPal.com and complete the transaction (window opens in a new tab).<br/>
        <a href="https://www.paypal.com/myaccount/transfer/send" target="_blank">https://www.paypal.com/myaccount/transfer/send</a></p>
        <br/>
        <p>Ensure your account is capable of sending money as gift. If not, kindly use the other option in the "Payment Method" list and select "(pay for goods or services)".</p>
        <br/>
        <p>Send your payment to <a class="bg-primary">&nbsp;paypal@prounlockphone.com&nbsp;</a>, use the same currency of your account and ensure entering the same amount you specified here.</p>
        <br/>
        <p>Once done with PayPal, come back to this tab and press "I'm done with PayPal" button and enter the details of your transaction.</p>
        <br/>
        <p>At the end of this operation, your account will be automatically credited, nevertheless the transaction will remain under review untill its validation by the administrator. By the meantime you can fully use your account.</p>
    </div>
    <div id="details" style="display: none">
        <p>Please enter the details of the PayPal transaction in order to have your credits added automatically.</p>
        <hr />
        <div class="col-md-6">
            <div class="form-group">
                <label>Transaction ID</label>
                <div class="input-group">
                    <input type="text" maxlength="17" id="trxID" class="form-control" placeholder="PayPal Transaction ID">
                </div>
                <span id="trxAlert" style="display: none;color:crimson"> Enter a valid transaction ID</span>
            </div>
        </div>
        <br/>
        <p style="clear:both">P.S: Payments sent as gift can only be refunded to the issuing account.</p>
    </div>
</div>
<div class='modal-footer'>
    <button class='btn btn-default last' data-dismiss='modal' onclick="cancelFun()">Cancel</button>
    <button class='btn btn-primary last' onclick="confirmFun()" id="confirm" style="display: none">Confirm</button>
    <button class='btn btn-primary' onclick="paypalDone()" id="paypalDone">I'm done with PayPal</button>
</div>

<script>
    $('#trxID').bind('keypress', function(ev) {
        if(ev.keyCode === 13) $("#confirm").trigger('click');
    });
    function confirmFun() {
        $('#trxAlert').slideUp();
        if($('#trxID').val().length < 17) {
            $('#trxAlert').slideDown();
            $('#trxID').select();
            return false;
        }
        $(".last").hide()
        $("#confirm").closest('div').append('<img class="loading center loader" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
        $.ajax({
            type: 'POST',
            url: 'https://www.prounlockphone.com/payment/paypal-api.php',
            data: 'trx=' + $('#trxID').val() + '&amount=' + $('#topay').html(),
            success: function (response) {
                if(response == 'KO') {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl("There was a problem processing your request.<br />Please try again", {theme: 'growlFail'});
                    $.jGrowl("If the problem persists, contact the <a class='txt-warning' href='https://www.prounlockphone.com/contactus/' target='_blank'>Support Team</a>.", {theme: 'growlFail'});
                } else if(response == 'INVALID') {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl('Your transaction ID couldn\'t be verified.<br/>Please check then try again.', {theme: 'growlFail'});
                } else if(response == 'CURMISMATCH') {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl('The currency of your transaction is different from your account\'s currency.', {theme: 'growlFail'});
                    $.jGrowl('We cannot update your balance at the moment.', {theme: 'growlFail'});
                    $.jGrowl('You will be contacted by one of our associate to discuss this issue.', {theme: 'growlFail'});
                    $.jGrowl('It is very likely that this transaction will be refunded.', {theme: 'growlFail'});
                    $.jGrowl('You can immediately resend another payment by adjusting the currency while we refund this transaction.', {theme: 'growlFail'});
                } else if(response == 'PENDING') {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl('Your transaction was successfully received.<br/>However, your balance was not updated.<br/>The reason is that your payment is still pending.', {theme: 'growlSuccess'});
                    $.jGrowl('This happens when you send the payment from your bank account or similar sources.', {theme: 'growlSuccess'});
                    $.jGrowl('Your balance will be update as soon as your payment becomes cleared.', {theme: 'growlSuccess'});
                } else if(response == 'NOTPAYMENT') {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl('The transaction you tried to register is not about a payment.', {theme: 'growlFail'});
                    $.jGrowl('Go back to your PayPal account and refresh the activity page then look for your recent payment.', {theme: 'growlFail'});
                } else if(response == 'MISAMOUNT') {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl('The transaction you tried to register is genuine.<br/>However, the amount received is different from what you declared.', {theme: 'growlFail'});
                    $.jGrowl('We cannot update your balance at the moment.', {theme: 'growlFail'});
                    $.jGrowl('You will be contacted by one of our associate to discuss this issue.', {theme: 'growlFail'});
                    $.jGrowl('It is very likely that we will check the transaction and add the correct amount.', {theme: 'growlFail'});
                    $.jGrowl('Nevertheless, you can go ahead and place your orders regardless of your balance.', {theme: 'growlFail'});
                } else if(response == 'NOTGIFT') {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl('The transaction you tried to register was not sent as gift.', {theme: 'growlFail'});
                    $.jGrowl('We cannot update your balance at the moment.', {theme: 'growlFail'});
                    $.jGrowl('You will be contacted by one of our associate to discuss this issue.', {theme: 'growlFail'});
                    $.jGrowl('It is very likely that we will check the transaction and add the correct amount after cutting the fees.', {theme: 'growlFail'});
                    $.jGrowl('Nevertheless, you can go ahead and place your orders regardless of your balance.', {theme: 'growlFail'});
                } else if(response == 'DUPLICATE') {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl('The transaction you tried to register was previously registered in our records.', {theme: 'growlFail'});
                    $.jGrowl('Your balance was not affected.', {theme: 'growlFail'});
                    $.jGrowl('Your transaction ID couldn\'t be verified.<br/>Please check then try again.', {theme: 'growlFail'});
                } else  if(response == 'OK'){
                    $.jGrowl('Your payment was successfully received!', {theme: 'growlSuccess'});
                    $.jGrowl('Thank you for your confidence.', {theme: 'growlSuccess'});
                    setTimeout(function(){
                        window.location.href = "https://www.prounlockphone.com/payment/success.php";
                    }, 3000);
                } else {
                    $(".last").show();
                    $('.loader').hide();
                    $.jGrowl('Shame on us!<br/>Something went wrong. Please try again or contact the administrator if the problem persists.', {theme: 'growlFail'});
                }
            },
            error: function() {
                $(".last").show();
                $('.loader').hide();
                $.jGrowl("Connection lost.<br />Check your Internet connection then try again.", {theme: 'growlFail'});
            }
        });
    }
</script>