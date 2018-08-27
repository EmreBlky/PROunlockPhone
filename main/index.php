<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();
?><!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title($_SESSION['client_long']) ?>
    <script src="//platform-api.sharethis.com/js/sharethis.js#property=5b0e802243a707001159fdd5&product=gdpr-compliance-tool"></script>
</head>
<?php
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT last_connection, balance, waived, phone, address1, address2, post_code, city, state, english_name, whatsapp, skype, viber, company, web_site, creation_date FROM users, countries WHERE countries.country_code = users.country AND users.id = " . $_SESSION['client_id']));
$row['balance'] < 0 ? $color = "red" : $color = "green";
?>
<body class="stretched device-lg">
    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
        <?php header_render("main") ?>
        <section id="content" class="account" style="margin-bottom: 0px;margin-top:50px">
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
            <div class="container">
                <div class="col-md-8 margin30 notopmargin">
			        <div class="curved-widget widget-orange center-text">
                        <div class="widget-amount">Profile</div>
                        <div class="widget-name" align="left" style="padding-left:30px;padding-right:30px">
                            <p class="info-row"><i class="fa fa-user"></i> <?php echo $_SESSION['client_long'] ?></p>
                            <p class="info-row"><i class="fa fa-envelope"></i> <?php echo $_SESSION['client_email'] ?></p>
                            <p class="info-row"><i class="fa fa-phone"></i> <?php echo strlen($row['phone']) < 8 ? "<i class='text-muted' style='font-weight: normal'>missing</i>" : "+" . $row['phone'] ?></p>
<!--				            <p class="info-row">-->
<!--                                <label><i class="fa fa-building"></i> --><?php
//                            echo $row['address1'];
//                            if($row['address2']) {
//                                echo "<br />" . $row['address2'];
//                            }
//                            echo "<br />";
//                            if($row['state']) {
//                                echo $row['state'] . ", ";
//                            }
//                            echo $row['city'] . " - " . $row['post_code'] . "<br />" . $row['english_name']; ?><!--</label>-->
<!--                            </p>-->
<!--				            <p class="info-row">&nbsp;</p>-->
<!--				            <p class="info-row"><b>Company:</b> --><?php //echo $row['company'] == "" ? "-" : $row['company'] ?><!--</p>-->
<!--                            <p class="info-row"><b>Website:</b> --><?php //echo $row['web_site'] == "" ? "-" : $row['web_site'] ?><!--</p>-->
                            <p class="info-row">&nbsp;</p>
                            <p class="info-row"><b>Whatsapp:</b> <?php echo $row['whatsapp'] == "" ? "-" : $row['whatsapp'] ?></p>
                            <p class="info-row"><b>Skype:</b> <?php echo $row['skype'] == "" ? "-" : $row['skype'] ?></p>
                            <p class="info-row"><b>Viber:</b> <?php echo $row['viber'] == "" ? "-" : $row['viber'] ?></p>
                            <p class="info-row">&nbsp;</p>
                            <p class="info-row">Member since: <?php echo $row['creation_date'] ?> GMT</p>
                            <p class="info-row">Last access: <?php echo $row['last_connection'] ?> GMT</p>
                        </div>
			        </div>
                </div>
                <div class="col-md-4 margin30 notopmargin">
                    <div class="curved-widget widget-blue">
                        <div class="widget-name-rev center-text">Orders' Summary</div>
                        <div class="widget-content">
<?php
    $rows = mysqli_query($DB->Link, "SELECT id FROM orders WHERE (status = 'Pending' OR status = 'In process') AND client = " . $_SESSION['client_id']);
    $process = mysqli_num_rows($rows);
    $rows = mysqli_query($DB->Link, "SELECT id FROM orders WHERE status = 'Success' AND client = " . $_SESSION['client_id']);
    $success = mysqli_num_rows($rows);
    $rows = mysqli_query($DB->Link, "SELECT id FROM orders WHERE status = 'Canceled' AND client = " . $_SESSION['client_id']);
    $canceled = mysqli_num_rows($rows);
    $rows = mysqli_query($DB->Link, "SELECT id FROM orders WHERE status = 'Rejected' AND client = " . $_SESSION['client_id']);
    $rejected = mysqli_num_rows($rows);
    $rows = mysqli_query($DB->Link, "SELECT id FROM orders WHERE client = " . $_SESSION['client_id']);
    $total = mysqli_num_rows($rows);
    if($success + $rejected == 0) {
        $rate = "not applicable";
    } else {
        $rate = round($success / ($success + $rejected) * 100) . "%";
    }
?>
                            <table>
                                <tr>
                                    <td align='right' style='padding-right:10px'>In Process:</td>
                                    <td><b><?php echo $process ?></b></td>
                                </tr>
                                <tr>
                                    <td align='right' style='padding-right:10px'>Success:</td>
                                    <td><b><?php echo $success ?></b></td>
                                </tr>
                                <tr>
                                    <td align='right' style='padding-right:10px'>Canceled:</td>
                                    <td><b><?php echo $canceled ?></b></td>
                                </tr>
                                <tr>
                                    <td align='right' style='padding-right:10px'>Rejected:</td>
                                    <td><b><?php echo $rejected ?></b></td>
                                </tr>
                                <tr>
                                    <td align='right' style='padding-right:10px'>Total:</td>
                                    <td><b><?php echo $total ?></b></td>
                                </tr>
                                <tr>
                                    <td align='right' style='padding-right:10px'>Success rate:</td>
                                    <td><b><?php echo $rate ?></b></td>
                                </tr>
                            </table>
                            <a href="https://www.prounlockphone.com/orders/" style="color:white">&rarr; Orders' History</a>
                        </div>
                    </div>
                    <div class="curved-widget widget-blue" style="margin-top: 20px;">
                        <div class="widget-name-rev center-text">Payment Summary</div>
                        <div class="widget-content">
                            <p class="info-row">Total processed orders: <b><?php
$processed = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'processed' FROM orders WHERE status = 'Success' AND client = " . $_SESSION['client_id']));
echo ($processed['processed'] == "" ? "0.00" : number_format($processed['processed'], 2, ".", ",")) . " " . $_SESSION['symbol'];
                                ?></b></p>
                            <a href="https://www.prounlockphone.com/statement/" style="color:white">&rarr; My Statement</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php echo $footer ?>
    </div>
    <?php echo $common_foot ?>
    <?php
if(isset($_GET['error'])) {
?>
    <script>
$(document).ready(function () {
    <?php
    if($_GET['error'] == 'refund') {
    ?>
    $.jGrowl("There was a problem executing your request. Access and check your balance prior to sending money-back request.<br/>Avoid abusing of this feature!", {theme: 'growlFail'});
    <?php
    } elseif($_GET['error'] == 'offline') {
    ?>
    $.jGrowl("The page you attempted to access is an offline service.<br/>Logout from your acount then you can have access to that page again.", {theme: 'growlFail'});
    <?php
    }
    ?>
});
    </script>
<?php
}
?>
</body>
</html>