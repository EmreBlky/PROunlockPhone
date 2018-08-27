<?php
if(isset($_GET['session'])) {
    session_id($_GET['session']);
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance FROM users WHERE id = " . $_SESSION['client_id']));

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Payment Success") ?>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php header_render("payment") ?>
            <div class="clear"></div>
            <section id="content" style="margin-bottom: 0px;">
		        <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                            <div align="center" style="font-size:20px;width:100%">
                                <h2 class="text-success">Your payment was successful!</h2>
                                <hr /><h1>Your new balance is <?php echo "{$row['balance']} {$_SESSION['symbol']}" ?></h1><hr />
                                <div class="small">
                                A confimation email was sent to you.<br />We appreciate your business.
                                </div>
                                <br/>
                            </div>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
    </body>
</html>