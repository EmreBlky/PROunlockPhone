<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

$infoPath = pathinfo(urldecode((parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))));
$store = $infoPath['basename'];
$infoPath = pathinfo($infoPath['dirname']);
$countryName = $infoPath['basename'];
$infoPath = pathinfo($infoPath['dirname']);
$folder = $infoPath['basename'];
$bad_supported = ($infoPath['basename'] == "Fresh" ? "" : " AND bad_supported = 1");

?><!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title("iCloud " . $store . " " . $countryName) ?>
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
            height: 100px;
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
    <?php renderOutOfSessionHeader("icloud") ?>
    <div class="account">
        <div class="row no-margin">
            <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                <div style="font-size:24px;width:100%" align="center">
                    <hr />
                    <div class="steps">
                        <h3 class="nomargin"><a style="color:crimson"><u>Step 6</u>: Select the appropriate service</a></h3>
                        <small>Services vary based on cost | processing time | and success rate.
                            <br/>
                            Pick the one that better fits your needs.
                        </small>
                    </div>
                    <hr />
                </div>
            </div>
        </div>

        <div class="container" style="margin-bottom: 50px">
            <a class="text-primary" href="https://www.prounlockphone.com/quick-order/">Quick Order</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/">iCloud Services</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/">iCloud Clean</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/<?php echo $folder ?>/"><?php echo $folder ?></a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/<?php echo $folder ?>/<?php echo $countryName ?>/"><?php echo $countryName ?></a> <b>></b> <?php echo $store ?>
            <div style="text-align: center" class="boxes">
                <ul>
                <?php
                $rows = mysqli_query($DB->Link, "SELECT DISTINCT success_rate, regular_{$_SESSION['currency']}, delivery_time, service, service_name FROM countries_per_service, services, stores, stores_per_service, countries WHERE 
(stores.store = '" . mysqli_real_escape_string($DB->Link, $store) . "' OR stores.id = 0)
AND stores_per_service.store = stores.id
AND combination = countries_per_service.id
AND countries_per_service.country = countries.country_code
AND (english_name = '{$countryName}' OR country_code = 'WW')
AND services.id = countries_per_service.service
AND icloud_clean_service = 1
AND service_status = 1" . $bad_supported . "
ORDER BY regular_{$_SESSION['currency']}");
                while($row = mysqli_fetch_array($rows)) {
                    if($row['success_rate'] < 50) {
                        $color = 'crimson';
                    } elseif($row['success_rate'] < 80) {
                        $color = 'orange';
                    } elseif($row['success_rate'] < 90) {
                        $color = 'blue';
                    } else {
                        $color = 'limegreen';
                    }
//                    echo "<a style='margin:30px' class='btn btn-default' href=\"https://www.prounlockphone.com/service/" . str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $row['service_name']))) . "/\">
//                                    <label style='font-weight:normal;font-size:150%'>{$row["regular_{$_SESSION['currency']}"]} {$_SESSION['symbol']} <b>|</b> " . $row['delivery_time'] . " <b>|</b> <b style='color:{$color}'>" . $row['success_rate'] . "%</b></label>
//                                </a>
//                            ";
                    echo "<li class='btn-b'>
                                    <a href='https://www.prounlockphone.com/service/" . str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $row['service_name']))) . "/'>
                                       <label>{$row["regular_{$_SESSION['currency']}"]} {$_SESSION['symbol']} <b>|</b> " . $row['delivery_time'] . " <b>|</b> <b style='color:{$color}'>" . $row['success_rate'] . "%</b></label>
                                       {$row["service_name"]}
                                    </a>
                                </li>
                                ";
                }
                ?>
                </ul>
        </div>
    </div>
    </section>
    <?php echo $footer ?>
</div>
<?php echo $common_foot ?>
</body>
</html>