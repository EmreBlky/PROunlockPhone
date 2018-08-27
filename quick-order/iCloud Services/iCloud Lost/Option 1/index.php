<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title("Buy Full Info + Order iCloud Unlock") ?>
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
                        <h3 class="nomargin"><a style="color:crimson"><u>Step 4</u>: Pick your service</a></h3>
                        <small>If you first need to obtain the full info, select one of the services of the upper section.
                            <br/>
                            If you already have the full info on hand, proceed with the unlock service from lower section.
                        </small>
                    </div>
                    <hr />
                </div>
            </div>
        </div>
        <div class="container" style="margin-bottom: 50px">
            <a class="text-primary" href="https://www.prounlockphone.com/quick-order/">Quick Order</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/">iCloud Services</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Lost/">iCloud Lost</a> <b>></b> Option 1 [Buy Full Info + Order iCloud Unlock]
            <div style="text-align: center">
                <a style='margin:30px;width:350px' class='btn btn-default' href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Lost/Option 1/Step 1/'>
                    <label style='font-size:150%;font-weight: normal'>Buy Full Info<hr style="margin: 0px" /><b>Step 1</b></label>
                </a>
                <a style='margin:30px;width:350px' class='btn btn-default' href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Lost/Option 1/Step 2/'>
                    <label style='font-size:150%;font-weight: normal'>Order iCloud Unlock<hr style="margin: 0px" /><b>Step 2</b></label>
                </a>
            </div>
        </div>
    </section>
    <?php echo $footer ?>
</div>
<?php echo $common_foot ?>
</body>
</html>