<?php
if(!isset($_GET['relative_id'])) {
    header("Location: https://www.prounlockphone.com/services/");
    exit();
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT quick_orders.IMEI 'IMEI', quick_orders.SN 'SN', firstname, lastname, email, payment_status, short_name, service FROM quick_orders, services WHERE services.id = service AND relative_id = '{$_GET['relative_id']}'"));
if($row['payment_status'] == 'Waiting Payment') {
    mysqli_query($DB->Link, "UPDATE quick_orders SET payment_status = 'Payment Pending Clearance', status = 'In process' WHERE relative_id = '{$_GET['relative_id']}'");
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Order Placed") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </head>
    <body class="stretched device-lg">
	    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php renderOutOfSessionHeader("") ?>
            <section id="content" style="margin-bottom: 0px;">
                <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div align="center" style="font-size:24px;width:100%;margin-bottom: 50px">
                            <h2 style='color:crimson'><?php echo ($row['firstname'] != "" ? $row['firstname'] . ", y" : ($row['lastname'] != "" ? $row['lastname'] . ", y" : "Y")) ?>ou're all set!</h2>
                            <a class="btn btn-success" style="padding:20px;font-size:24px;display: block" href="https://www.prounlockphone.com/track/order-status.php?ref=<?php echo $_GET['relative_id'] ?>" target="_blank">Check your order status</a>
                            <img height="100px" src="https://www.prounlockphone.com/images/check.gif" /><br />
                            <hr /><h3><a href="https://www.prounlockphone.com/track/order-status.php?ref=<?php echo $_GET['relative_id'] ?>" target="_blank"><?php echo ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . "</a> <a href='https://www.prounlockphone.com/service/?id={$row['service']}' target='_blank'>" . $row['short_name'] ?></a></h3><hr />
                            Check your email box <a class="text-warning"><?php echo $row['email'] ?></a><br /><a class="small">(check your spam/junk folder if necessary)</a><br />We will be sending you notifications regarding your order.
                        </div>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
        </div>
        <?php echo $common_foot ?>
    </body>
</html>