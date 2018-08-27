<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

$infoPath = pathinfo(urldecode((parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))));
$model = $infoPath['basename'];
$infoPath = pathinfo($infoPath['dirname']);
$carrier = $infoPath['basename'];
$countryName = basename($infoPath['dirname']);
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT country_code FROM countries WHERE english_name = '{$countryName}'"));
$country = $row['country_code'];

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title($model . " " . $carrier . " " . $country) ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
        <style>
            .boxes  {
                padding: 0 20px;
            }
            .boxes:after {
                content: "";
                display: block;
                clear: both;
            }
            .boxes ul{
                padding: 0;
                list-style: none;
                text-align: center;
            }
            .boxes li{
                float: left;
                width: 250px;
                border: 1px solid #dedede;
                border-radius: 10px;
                padding: 10px 0;
                background-image: -webkit-linear-gradient(top, #FFFFFF 0%, #F1F1F1 100%);
                background-image: linear-gradient(to bottom, #FFFFFF 0%, #F1F1F1 100%);
                margin: 10px;
            }
            .boxes li:hover{
                background-image: -webkit-linear-gradient(top, #FFFFFF 0%, #e1e1e1 100%);
                background-image: linear-gradient(to bottom, #FFFFFF 0%, #e1e1e1 100%);
            }
            .boxes label{
                margin-left: 5px;
                padding-top: 10px;
                display: block;
                text-align: center;
            }
            .boxes a{
                text-decoration: none;
                color: #333;
                cursor: pointer;
            }
            .boxes a:hover{
                color: #666;
            }
            .boxes img{
                margin: auto;
                display: block;
                height: 70px;
            }
            .btn-b {
                border: 1px solid #dedede;
                border-radius: 3px;
                background-image: -webkit-linear-gradient(top, #ffffff 0%, #f1f1f1 100%);
                background-image: linear-gradient(to bottom, #ffffff 0%, #f1f1f1 100%);
                margin: 5px;
                text-decoration: initial;
                color: inherit;
                display: inline-block;
                padding: 10px 0;
                text-align: center;
            }
            .btn-b.active {
                background-image: -webkit-linear-gradient(top, #7fb6fc 0%, #3364a3 100%);
                background-image: linear-gradient(top, #7fb6fc 0%, #3364a3 100%);
            }
            .btn-b.active:hover {
                background-image: -webkit-linear-gradient(top, #7fb6fc 0%, #447cc5 100%);
                background-image: linear-gradient(top, #7fb6fc 0%, #447cc5 100%);
            }
            .btn-b:hover {
                background-image: -webkit-linear-gradient(top, #ffffff 0%, #e1e1e1 100%);
                background-image: linear-gradient(to bottom, #ffffff 0%, #e1e1e1 100%);
            }
        </style>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php renderOutOfSessionHeader("factory") ?>
            <div class="account">
                <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div style="font-size:24px;width:100%" align="center">
                            <hr />
                            <div class="steps">
                                <h3 class="nomargin"><a style="color:crimson"><u>Step 5</u>: Pick the appropriate service</a></h3>
                                <small>Tell us about the status of your IMEI / ESN / Contract.
                                <br/>Select the service suitable for your ESN status.</small>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>

		        <div class="container" style="margin-bottom: 50px">
                    <a class="text-primary" href="https://www.prounlockphone.com/quick-order/">Quick Order</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iPhone Factory Unlock/">iPhone Factory Unlock</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iPhone Factory Unlock/<?php echo $countryName ?>/"><?php echo $countryName ?></a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iPhone Factory Unlock/<?php echo $countryName ?>/<?php echo $carrier ?>/"><?php echo $carrier ?></a> <b>></b> <?php echo $model ?>
                    <div class="boxes">
                        <ul>
                            <?php
                                $rows = mysqli_query($DB->Link, "SELECT DISTINCT ESNstatus.status 'status', regular_{$_SESSION['currency']}, delivery_time, service, service_name FROM iPhoneFactoryUnlock, services, carriers, models, ESNstatus WHERE iPhoneFactoryUnlock.status = ESNstatus.id AND iPhoneFactoryUnlock.country = '" . $country . "' AND iPhoneFactoryUnlock.carrier = carriers.id AND carriers.carrier = '" . $carrier . "' AND iPhoneFactoryUnlock.model = models.id AND models.model = '" . $model . "' AND services.id = service AND service_status = 1 ORDER BY ESNstatus.id");
                                while($row = mysqli_fetch_array($rows)) {
                                    echo "<li class='btn-b'>
                                <a href=\"https://www.prounlockphone.com/service/" . str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $row['service_name']))) . "/\">
                                    <label>{$row['status']}</label>
                                    {$row["regular_{$_SESSION['currency']}"]} {$_SESSION['symbol']} / {$row['delivery_time']}
                                </a>
                            </li>
                            ";
                            }
                        ?></ul>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
    </body>
</html>