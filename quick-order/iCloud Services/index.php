<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT service_name FROM services WHERE id = 321"));
?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("iCloud Services") ?>
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
                                <h3 class="nomargin"><a style="color:crimson"><u>Step 2</u>: Indicate the iCloud status</a></h3>
                                <small>Tell us if your iDevice is activated (full access to menu and apps, <u>not bypassed</u>) or stuck at the activation phase (white screen).<br/>If stuck at the activation, please indicate whether it is in Clean or Lost mode.<br/>
                                If you don't know the status of your iCloud, please use <a href="https://www.prounlockphone.com/service/<?php echo str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $row['service_name']))) ?>" target="_blank"><?php echo $row['service_name'] ?></a> service first.</small>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>

		        <div class="container" style="margin-bottom: 50px">
                    <a class="text-primary" href="https://www.prounlockphone.com/quick-order/">Quick Order</a> <b>></b> iCloud Services
                    <div class="boxes">
                        <ul>
                            <li class='btn-b'>
                                <a href='https://www.prounlockphone.com/service/?id=27'>
                                    <img alt='' src='https://www.prounlockphone.com/images/quick/activated.jpg' />
                                    <label>Activated iPhone/iPad</label>
                                </a>
                            </li>
                            <li class='btn-b'>
                                <a href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Clean/'>
                                    <img alt='' src='https://www.prounlockphone.com/images/quick/clean.png' />
                                    <label>iCloud Clean</label>
                                </a>
                            </li>
                            <li class='btn-b'>
                                <a href='https://www.prounlockphone.com/quick-order/iCloud Services/iCloud Lost/'>
                                    <img alt='' src='https://www.prounlockphone.com/images/quick/lost.png' />
                                    <label>iCloud Lost</label>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
    </body>
</html>