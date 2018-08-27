<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT service_name FROM services WHERE id = 453"));
?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("iCloud Clean") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
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
                                <h3 class="nomargin"><a style="color:crimson"><u>Step 3</u>: Indicate the status of your device</a></h3>
                                <small>
                                    Have you tried unlocking your device before?
                                    <br/>
                                    Was your device replaced?
                                    <br/>
                                    Apple keeps record of all iCloud unlock attempts and marks them as FRAUD. Replaced IMEIs also cannot be processed with regular services.
                                    <br/>
                                    If this is the case, it will be a waste of time to select a service dedicated to fresh orders.
                                    <br/>
                                    If you don't know the status of your device, please use <a href="https://www.prounlockphone.com/service/<?php echo str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $row['service_name']))) ?>" target="_blank"><?php echo $row['service_name'] ?></a> service first.</small>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>

		        <div class="container" style="margin-bottom: 50px">
                    <a class="text-primary" href="https://www.prounlockphone.com/quick-order/">Quick Order</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/">iCloud Services</a> <b>></b> iCloud Clean
                    <div style="text-align: center">
                        <a style='margin:30px;width:350px' class='btn btn-default' href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/Fresh/'>
                            <label style='font-size:150%'>Fresh Order<hr style="margin: 0px" />Never Attempted Before</label>
                        </a>
                        <a style='margin:30px;width:350px' class='btn btn-default' href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/Bad Case History - Replaced/'>
                            <label style='font-size:150%'>Bad Case History<hr style="margin: 0px" />Replaced</label>
                        </a>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
    </body>
</html>