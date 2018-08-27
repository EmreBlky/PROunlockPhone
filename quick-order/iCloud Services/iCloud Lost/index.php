<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("iCloud Lost") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
        <style>
            .test ul {
                list-style-position: inside;
                margin-left: 50px;
            }
        </style>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php renderOutOfSessionHeader("icloud") ?>
            <div class="account">
                <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div style="font-size:24px;width:100%" align="center">
                            <hr />
                            <div class="steps">
                                <h3 class="nomargin"><a style="color:crimson"><u>Step 3</u>: Read, understand and make the right choice</a></h3>
                                <small>
                                    Several scenarios but very low success rate.
                                    <br/>
                                    Every decision you take can make you waste your money.
                                    <br/>
                                    Take the time to read before making any step.</small>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>
                <div class="container" style="margin-bottom: 50px">
                    <a class="text-primary" href="https://www.prounlockphone.com/quick-order/">Quick Order</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/">iCloud Services</a> <b>></b> iCloud Lost
                </div>
                <div class="test col-lg-8 no-padding" style="font-size: 125%;display: block; margin: 0 auto; float: none;">
                    <p>In case the iCloud is set to LOST MODE, unfortunately you will have access to limited options:</p>
                    <ul type="disc">
                        <li>Option 1:</li>
                        <ul type="circle">
                            <li>Step 1: Buy full info</li>
                            <ul type="square">
                                <li>Cost: $10 → $25.5 non-refundable (by IMEI or a few networks supported)</li>
                                <li>Processing time: up to 7 days</li>
                                <li>Success rate: <span style="color:red">18%</span>. If not found then <span style="background-color: rgb(0, 255, 0);"> 100% </span> reimbursed. In case it's replied (found records), there is a risk it doesn't match the partial Apple ID showing on your screen. In this case you get <span style="background-color: rgb(0, 255, 0);"> 100% </span> refund also.</li>
                            </ul>
                            <li>Step 2: Unlock iCloud</li>
                            <ul type="square">
                                <li>Cost: $35 refundable</li>
                                <li>Processing time: up to 11 days</li>
                                <li>Success rate: up to 80% for fresh orders (never attempted with this technique)</li>
                            </ul>
                        </ul>
                    </ul>
                    <p><u color="#FF0000"><b>Important:</b></u><br/>Note that if you pass step 1 and fail in step 2 then the payment of the first step cannot be refunded (order fully processed by providing the necessary information). These two steps are in fact two separate and independent services.</p>
                    <ul type="disc">
                        <li>Option 2:</li>
                        <ul type="circle">
                            <li>Order iCloud unlock premium:</li>
                            <ul type="square">
                                <li>Cost: From $180 to $280 depends on the model and the original country/seller/network (all refundable)</li>
                                <li>Processing time: up to 25 days</li>
                                <li>Success rate: 65%</li>
                                <li>Limited countries/sellers/networks supported</li>
                            </ul>
                        </ul>
                    </ul>
                    <p>Please read the disclaimer in the payment section to understand the rules and conditions before posting your payment and submitting your order (<a href="#" class="text-primary" data-toggle="modal" data-target="#modal" data-webx="https://www.prounlockphone.com/payment/refund-policy.php">Read our reimbursement policy</a>).</p>
                    <br />
                    <br />
                    <p>If you need additional information, contact us with Whatsapp at +12104544850.</p>
                    <div style="text-align: center">
                        <a style='margin:30px;width:350px' class='btn btn-default' href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Lost/Option 1/'>
                            <label style='font-size:150%;font-weight: normal'>Buy Full Info +<br />Order iCloud Unlock<hr style="margin: 0px" /><b>Opt for Option 1</b></label>
                        </a>
                        <a style='margin:30px;width:350px' class='btn btn-default' href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Lost/Option 2/'>
                            <label style='font-size:150%;font-weight: normal'>Immediately Order<br />iCloud Unlock<hr style="margin: 0px" /><b>Opt for Option 2</b></label>
                        </a>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
    </body>
</html>