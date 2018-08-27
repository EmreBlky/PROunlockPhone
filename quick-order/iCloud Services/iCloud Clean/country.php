<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

$infoPath = pathinfo(urldecode((parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))));
$countryName = $infoPath['basename'];
$infoPath = pathinfo($infoPath['dirname']);
$folder = $infoPath['basename'];
$bad_supported = ($infoPath['basename'] == "Fresh" ? "" : " AND bad_supported = 1");
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT service_name FROM services WHERE id = 462"));
?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("iCloud " . $countryName) ?>
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
            <?php renderOutOfSessionHeader("icloud") ?>
            <div class="account">
                <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div style="font-size:24px;width:100%" align="center">
                            <hr />
                            <div class="steps">
                                <h3 class="nomargin"><a style="color:crimson"><u>Step 5</u>: Pick the store of origin</a></h3>
                                <small>
                                    Identify the original store where your device was initially bought from.
                                    <br/>
                                    This information sometimes appears complicated to obtain.
                                    <br/>
                                    If you have tried to get it before and failed, try <a href="https://www.prounlockphone.com/service/<?php echo str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $row['service_name']))) ?>" target="_blank"><?php echo $row['service_name'] ?></a> service which will 100% reveal the original store (instant).
                                </small>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="container" style="margin-bottom: 50px">
                    <a class="text-primary" href="https://www.prounlockphone.com/quick-order/">Quick Order</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/">iCloud Services</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/">iCloud Clean</a> <b>></b> <a class="text-primary" href="https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/<?php echo $folder ?>/"><?php echo $folder ?></a> <b>></b> <?php echo $countryName ?>
                    <div class="boxes">
                        <ul>
                            <?php
                            $rows = mysqli_query($DB->Link, "SELECT DISTINCT stores.store 'store' FROM countries, stores, stores_per_service, countries_per_service, services WHERE
stores.id = 0
AND stores.id = stores_per_service.store
AND combination = countries_per_service.id
AND countries_per_service.country = countries.country_code
AND english_name = '{$countryName}'
AND services.id = countries_per_service.service
AND service_status = 1
AND icloud_clean_service = 1" . $bad_supported);
                            if(mysqli_num_rows($rows) > 0) {
                                $row = mysqli_fetch_assoc($rows);
                                echo "<li class='btn-b'>
                                        <a href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/{$folder}/{$countryName}/{$row['store']}/'>
                                        <img alt='{$row['store']}' src='https://www.prounlockphone.com/images/networks/{$row['store']}.png'>
                                        <label>{$row['store']}</label>
                                    </a>
                                </li>
                                ";
                            } else {
                                $rows = mysqli_query($DB->Link, "SELECT DISTINCT stores.store 'store', english_name FROM countries, stores, stores_per_service, countries_per_service, services WHERE
stores.id = 0
AND stores.id = stores_per_service.store
AND combination = countries_per_service.id
AND countries_per_service.country = countries.country_code
AND countries.country_code = 'WW'
AND services.id = countries_per_service.service
AND service_status = 1
AND icloud_clean_service = 1" . $bad_supported);
                                if(mysqli_num_rows($rows) > 0) {
                                    $row = mysqli_fetch_assoc($rows);
                                    echo "<li class='btn-b'>
                                        <a href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/{$folder}/{$row['english_name']}/{$row['store']}/'>
                                        <img alt='{$row['store']}' src='https://www.prounlockphone.com/images/networks/{$row['store']}.png'>
                                        <label>{$row['store']}</label>
                                    </a>
                                </li>
                                ";
                                }
                            }

                            $query = "SELECT DISTINCT stores.store 'store' FROM countries, stores, stores_per_service, countries_per_service, services WHERE
stores.id <> 0
AND stores.id = stores_per_service.store
AND combination = countries_per_service.id
AND countries_per_service.country = countries.country_code
AND english_name = '{$countryName}'
AND services.id = countries_per_service.service
AND service_status = 1
AND icloud_clean_service = 1" . $bad_supported;
                            $rows = mysqli_query($DB->Link, $query);
                            $stores = [];
                            while($row = mysqli_fetch_array($rows)) {
                                $stores[$row['store']] = $countryName;
                            }
                            $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT english_name FROM countries WHERE country_code = 'WW'"));
                            $ww = $row['english_name'];
                            $rows = mysqli_query($DB->Link, "SELECT DISTINCT stores.store 'store', english_name FROM countries, stores, stores_per_service, countries_per_service, services WHERE
stores.id <> 0
AND stores.id = stores_per_service.store
AND combination = countries_per_service.id
AND countries_per_service.country = countries.country_code
AND countries.country_code = 'WW'
AND services.id = countries_per_service.service
AND service_status = 1 AND icloud_clean_service = 1" . $bad_supported . "
AND stores.store NOT IN ({$query})");
                            while($row = mysqli_fetch_array($rows)) {
                                $stores[$row['store']] = $ww;
                            }
                            ksort($stores);
                            foreach ($stores as $key => $val) {
                                if($val == $countryName) $destination = $countryName;
                                else $destination = $ww;
                                echo "<li class='btn-b'>
                                        <a href=\"https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/{$folder}/{$destination}/{$key}/\">
                                            <img alt='{$key}' src=\"https://www.prounlockphone.com/images/networks/{$key}.png\">
                                            <label>{$key}</label>
                                        </a>
                                    </li>
                                    ";
                            }
                        ?></ul>
                    </div>
                </div>
                <?php echo $footer ?>
            </div>
        </div>
        <?php echo $common_foot ?>
    </body>
</html>