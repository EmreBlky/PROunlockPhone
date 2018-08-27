<?php
if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    header('HTTP/1.1 301 Moved Permanently');
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}
session_name('Client_Session');
session_start();
if(!isset($_SESSION['currency'])) {
    $_SESSION['currency'] = 'USD';
    $_SESSION['symbol'] = '$';
}

if(isset($_SESSION['start']) and $_SESSION['start']) {
    if(isset($_GET['url'])) {
        if(isset($_GET['param'])) header("Location: ../{$_GET['url']}/?param=" . $_GET['param']);
        else header("Location: ../{$_GET['url']}/");
    } elseif($_SESSION['client_type'] == "admin") header("Location: ../supermain.php");
    elseif(isset($_GET['error'])) header("Location: ../main/?error=" . $_GET['error']);
    else header("Location: ../main/?error=offline");
    exit();
}

function common_head_with_title($title = "") {
    $ogImage = 'https://www.prounlockphone.com/images/ogpup.png';
    $ogURL = 'https://prounlockphone.com/';
    $ogDescription = 'Fast, reliable and cheap service. Check your IMEI then get it unlocked. 100% Money-Back Guarantee';
    $ogTitle = $title == "" ? ('PROunlockPhone&copy; ' . date('Y') . ' | Official Online IMEI Services | USA') : ("PROunlockPhone - " . $title);
    $ogSiteName = 'PROUnlockPhone';
    $websiteTitle = $title == "" ? "PROunlockPhone - unlock iPhone iCloud Samsung AT&T USA - check IMEI" : ($title . " - PROunlockPhone");
    $websiteDescription = 'Instant Check IMEI. iPhone Factory Unlock, iCloud Unlock, iPad, Samsung, Nokia, BlackBerry, all brands. Worldwide Services.';
    $common_head = '
        <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-115016691-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \'UA-115016691-1\');
</script>';

    $common_head .= '
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta property="og:title" content="' . $ogTitle . '">
        <meta property="og:type" content="website">
        <meta property="og:url" content="' . $ogURL . '">
        <meta property="og:image:secure_url" itemprop="image" content="' . $ogImage . '">
        <meta property="og:description" content="' . $ogDescription . '">
        <meta property="og:site_name" content="' . $ogSiteName . '">
        <meta property="og:updated_time" content="' . time() . '" />
        
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/css.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/jquery.jgrowl.min.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/animate.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/dark.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/magnific-popup.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/style.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/responsive.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/bootstrap-select.min.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/style(1).css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/switchery.css">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <meta name="description" content="' . $websiteDescription . '" />
        <meta name="keywords" content="apple iphone unlock icloud unlock imei check clean clacklisted samsung lg nokia blackberry sony at&t sprint orange vodafone sfr usa worldwide" />
        <title>' . $websiteTitle . '</title>
        
	    <script src="https://www.prounlockphone.com/common/jquery-1.11.1.min.js"></script>
        <link rel="shortcut icon" href="https://www.prounlockphone.com/images/pup-ico-16x16.png" type="image/x-icon" />
		
	    <style id="fit-vids-style">
.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}
.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}
        </style>
        <script type="application/ld+json">
        { 
            "@context": "http://schema.org", 
            "@type": "WebSite", 
            "url": "https://www.prounlockphone.com", 
            "name": "PROunlockPhone - iCloud / iPhone / Samsung Unlocking Solutions",
            "author": {
                "@type": "Person",
                "name": "Khoubeib Bouthour"
            },
            "description": "Worldwide Factory Unlock Codes. iPhone, iCloud, Samsung, etc. Unlock iPhone iCloud, US AT&T, T-Mobile, Canada. Cheapest Prices 100% Money-Back",
            "publisher": {
                "@type": "Person",
                "name": "Khoubeib Bouthour"
            },
            "potentialAction": { 
                "@type": "SearchAction", 
                "target": {
                    "@type": "EntryPoint",
                    "urlTemplate": "https://www.prounlockphone.com/track/order-status.php?ref={IMEI}"
                },
                "query-input": {
                    "@type": "PropertyValueSpecification",
                    "valueRequired": "http://schema.org/True",
                    "valueName": "IMEI"
                }
            } 
}
</script>';
    return $common_head;
}

$footer1 = 'All prices are subject to change without notice. If you have any progressing orders, they should not be affected. However, in rare cases, the orders will be rejected and your credits refunded to your account and you should receive a notification explaining the reason.';
$footer2 = 'Feel free to contact us if you have any concern. Our team is here to provide continuous help and support.';
$footer3 = '<div class="col_half">PROUnlockPhone© 2010 - ' . date('Y') . '</div>
                        <div class="col_half col_last tright">
                            <div class="social">
                                <a target="_blank" href="https://fb.me/prounlockphone"><i class="fa fa-facebook"></i></a>
								<a target="_blank" href="https://twitter.com/prounlockphone"><i class="fa fa-twitter"></i></a>
								<a target="_blank" href="https://plus.google.com/prounlockphone"><i class="fa fa-google-plus"></i></a>
								<a target="_blank" href="https://www.linkedin.com/in/prounlockphone"><i class="fa fa-linkedin"></i></a>
							</div>
                            <i class="fa fa-envelope"></i> <a href="mailto:support@prounlockphone.com">support@prounlockphone.com</a>
                            <span class="middot">   </span>
                            <i class="fa fa-phone"></i> <a href="tel:+12104544850">+1 (210) 454-4850</a>
                            <span class="middot">   </span>
                            <a href="skype:support@prounlockphone.com"><img src="https://www.prounlockphone.com/images/Skype.png" alt="skype" /> Skype message</a>
			</div>';

$footer = '<footer id="footer" class="dark" z-index="1">
                <div class="margin30 container-fluid">
                    <p class="p1" style="text-align: center; ">
                        <span style="font-size: 14px;">
                            <font color="#9c9c94">' . $footer1 . '</font>
                        </span>
                    </p>
                    <p class="p2" style="text-align: center;">
                        <font color="#9c9c94"><br></font>
                    </p>
                    <p class="p1" style="text-align: center;">
                        <span style="font-size: 14px;">
                            <font color="#9c9c94">' . $footer2 . '</font>
                        </span>
                    </p>
                    <p>
                        <font color="#9c9c94">
                            <span style="font-size: 14px;"></span>
                            <span style="font-size: 14px;">
                                <span style="font-size: 16px;">
                                    <style type="text/css">
p.p1 {
    margin: 0.0px 0.0px 0.0px 0.0px;
    font: 12.0px Helvetica
}
p.p2 {
    margin: 0.0px 0.0px 0.0px 0.0px;
    font: 12.0px Helvetica;
    min-height: 14.0px
}
                                    </style>
                                </span>
                            </span>
                            <span style="font-size: 14px;"></span>
                        </font>
                    </p>
                    <p class="p2" style="text-align: center; "><br></p>
                </div>
                <div id="copyrights">
                    <div class="container clearfix">
                        ' . $footer3 . '
                    </div>
                </div>
            </footer>';

function header_render($actual) {
    $DB = new DBConnection();
    ?>
            <header id="header" class="transparent-header full-header" data-sticky-class="not-dark">
                <div id="header-wrap" class="">
                    <div class="container clearfix">
			<div id="primary-menu-trigger"><i class="fa fa-bars"></i></div>
			<div id="logo">
                            <a href="https://www.prounlockphone.com/"><img src='https://www.prounlockphone.com/images/pup1.png' /></a>
                            <a href="https://www.prounlockphone.com/profile/" style="font-size:50%"><? echo $_SESSION['username'] ?></a>
			</div>
			<nav id="primary-menu">
                            <ul class="sf-menu" style="touch-action: pan-y;">
				<li>
                                    <a href="https://www.prounlockphone.com/statement/" style="font-size: 10px">
                                        <?php
    switch($_SESSION['currency']) {
        case "USD":
            $currency = "$";
            break;
        case "EUR":
            $currency = "&euro;";
            break;
        case "GBP":
            $currency = "&pound;";
            break;
        case "TND":
            $currency = "DT";
            break;
    }
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance FROM users WHERE users.id = " . $_SESSION['client_id']));
                                            ?><div id="balance" style="color:<? echo ($row['balance'] < 0 ? $color = "red" : ($row['balance'] < 10 ? $color = "orange" : $color = "green")) ?>">Balance <? echo number_format($row['balance'], 2, ".", ",") . " " . $currency ?></div>
                                    </a>
                                </li>
                                <li<?php echo $actual == "main" ? ' class="current"' : "" ?>>
                                    <a href="https://www.prounlockphone.com/main/" style="font-size: 10px">
                                        <div>Home</div>
                                    </a>
                                </li>
                                <li<?php
                                if($actual == "orders" || $actual == "order" || $actual == "check") {
                                    echo ' class="current"';
                                }
                                ?>>
                                    <a href="#" class="sf-with-ul" style="font-size: 10px">
                                        <div>Orders Panel <b class="caret"></b></div>
                                    </a>
                                    <ul style="display: none;">
                                        <li<?php echo $actual == "order" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/order/" style="font-size: 10px">
                                                <div>Place Order</div>
                                            </a>
                                        </li>
                                        <li<?php echo $actual == "check" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/check/" style="font-size: 10px">
                                                <div>Instant IMEI Check</div>
                                            </a>
                                        </li>
                                        <hr style="margin: 0px"/>
                                        <li<?php echo $actual == "orders" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/orders/" style="font-size: 10px">
                                                <div>Orders History</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li<?php
                                if($actual == "profile" || $actual == "statement" || $actual == "notifications" || $actual == "payment" || $actual == "refund" || $actual == "change") {
                                    echo ' class="current"';
                                }
                                ?>>
                                    <a href="#" class="sf-with-ul" style="font-size: 10px">
                                        <div>My account <b class="caret"></b></div>
                                    </a>
                                    <ul style="display: none;">
                                        <li<?php echo $actual == "statement" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/statement/" style="font-size: 10px">
                                                <div>
                                                    <img src="https://www.prounlockphone.com/images/payment.png" style="margin-right:10px" alt="statement"><b>My Statement</b>
                                                </div>
                                            </a>
                                        </li>
                                        <li<?php echo $actual == "notifications" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/notifications/" style="font-size: 10px">
                                                <div>
                                                    <img src="https://www.prounlockphone.com/images/notification.png" style="margin-right:10px" alt="notification"><b>Notification History</b>
                                                </div>
                                            </a>
                                        </li>
                                        <li<?php echo $actual == "payment" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="#" class="sf-with-ul" style="font-size: 10px">
                                                <div>
                                                    <img src="https://www.prounlockphone.com/images/coins.png" style="margin-right:10px" alt="credits"><b>Add credits</b> <b class="caret"></b>
                                                </div>
                                            </a>
                                            <ul style="display: none;">
                                                <li class="sf-options" style="border-bottom:solid 1px gray">
                                                    <a href="https://www.prounlockphone.com/payment/" style="font-size: 10px">
                                                        <div align="center">
                                                            <img src="https://www.prounlockphone.com/images/paypal-menu.png" height="23px" alt="PayPal">
                                                        </div>
                                                    </a>
                                                </li>
<!--                                                <li class="sf-options" style="border-bottom:solid 1px gray">-->
<!--                                                    <a href="https://www.prounlockphone.com/skrill/">-->
<!--                                                        <div align="center">-->
<!--                                                            <img src="https://www.prounlockphone.com/images/skrill-menu.png" height="23px" alt="Skrill">-->
<!--                                                        </div>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li class="sf-options" style="border-bottom:solid 1px gray">-->
<!--                                                    <a href="https://www.prounlockphone.com/neteller/">-->
<!--                                                        <div align="center">-->
<!--                                                            <img src="https://www.prounlockphone.com/images/neteller-menu.png" height="23px" alt="Neteller">-->
<!--                                                        </div>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li class="sf-options" style="border-bottom:solid 1px gray">-->
<!--                                                    <a href="https://www.prounlockphone.com/moneygram/">-->
<!--                                                        <div align="center">-->
<!--                                                            <img src="https://www.prounlockphone.com/images/moneygram-menu.png" height="23px" alt="MoneyGram">-->
<!--                                                        </div>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li class="sf-options">-->
<!--                                                    <a href="https://www.prounlockphone.com/westernunion/">-->
<!--                                                        <div align="center">-->
<!--                                                            <img src="https://www.prounlockphone.com/images/westernunion-menu.png" height="23px" alt="Western Union">-->
<!--                                                        </div>-->
<!--                                                    </a>-->
<!--                                                </li>-->
                                            </ul>
                                        </li>
                                        <li<?php echo $actual == "refund" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/refund/" style="font-size: 10px">
                                                <div>
                                                    <img src="https://www.prounlockphone.com/images/refund.png" style="margin-right:10px" alt="refund"><b>Request Money Back</b>
                                                </div>
                                            </a>
                                        </li>
                                        <li<?php echo $actual == "profile" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/profile/" style="font-size: 10px">
                                                <div>
                                                    <img src="https://www.prounlockphone.com/images/profile.png" style="margin-right:10px" alt="profile"><b>Edit My Profile</b>
                                                </div>
                                            </a>
                                        </li>
                                        <li<?php echo $actual == "change" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/change/" style="font-size: 10px">
                                                <div>
                                                    <img src="https://www.prounlockphone.com/images/key.png" style="margin-right:10px" alt="profile"><b>Change Password</b>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li<?php
                                if($actual == "contactus" || $actual == "forum") {
                                    echo ' class="current"';
                                }
                                ?>>
                                    <a href="#" class="sf-with-ul" style="font-size: 10px">
                                        <div>Support <b class="caret"></b></div>
                                    </a>
                                    <ul style="display: none;">
                                        <li<?php echo $actual == "contactus" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/contactus/" style="font-size: 10px">
                                                <div>Contact us</div>
                                            </a>
                                        </li>
                                        <hr style="margin: 0px"/>
                                        <li<?php echo $actual == "forum" ? ' class="current"' : "" ?> class="sf-options">
                                            <a href="https://www.prounlockphone.com/forum/" style="font-size: 10px">
                                                <div>Forum</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="https://www.prounlockphone.com/logout/" style="font-size: 10px">
                                        <div>Logout</div>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>
            <div class="clear"></div>
    <?php

    if((!isset($_SESSION['showAds']) || $_SESSION['showAds'] == '1') && $_SERVER['REQUEST_URI'] <> '/login/') echo '
            <div align="center">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-5111821234953725"></ins>
                <script>
                     (adsbygoogle = window.adsbygoogle || []).push({
                          google_ad_client: "ca-pub-5111821234953725"
                     });
                </script>
            </div>';
}
class DBConnection {
    
    public $Link;
    
    public function __construct() {
        $this->Link = mysqli_connect("", "touwereg", "Kh0ube1b$");
        $this->Link->set_charset('utf8mb4');
        mysqli_select_db($this->Link, "prounlockphone");
//        mysqli_query($this->Link, "SET names UTF8");
    }
}

$contact_information = '<hr class="hr-description">
                            <p class="info-row"><b>Admin:</b> Khoubeib</p><br />
                            <p class="info-row"><i class="fa fa-envelope"></i> <b>eMail:</b> <a href="mailto:support@prounlockphone.com" style="color:white">support@prounlockphone.com</a></p>
                            <p class="info-row"><b>Whatsapp:</b> <a href="whatsapp:+12104544850" style="color:white">+1 (210) 454-4850</p>
                            <p class="info-row"><b>Viber:</b> <a href="viber:+12104544850" style="color:white">+1 (210) 454-4850</p>
                            <p class="info-row"><b>Skype:</b> <a href="skype:support@prounlockphone.com?chat" style="color:white">support@prounlockphone.com</a></p>
                            <p class="info-row"><i class="fa fa-phone"></i> <b>Phone:</b> <a href="tel:+12104544850" style="color:white">+1 (210) 454-4850</a></p>
                            <p class="info-row"><b>Supported languages:</b>
                                <br /><img src="https://www.prounlockphone.com/images/flags/US.png" height="16px" style="margin-left:50px" /> English
                                <br /><img src="https://www.prounlockphone.com/images/flags/FR.png" height="16px" style="margin-left:50px" /> French
                                <br /><img src="https://www.prounlockphone.com/images/flags/TN.png" height="16px" style="margin-left:50px" /> Arabic
                                <br /><img src="https://www.prounlockphone.com/images/flags/ES.png" height="16px" style="margin-left:50px" /> Spanish
                                <br /><img src="https://www.prounlockphone.com/images/flags/IT.png" height="16px" style="margin-left:50px" /> Italian
                            </p>';

function renderOutOfSessionHeader($currentTab) {
    ?>
    <header id="header" class="transparent-header full-header" data-sticky-class="not-dark">
        <div id="header-wrap" class="">
            <div class="container clearfix">
                <div id="primary-menu-trigger"><i class="fa fa-bars"></i></div>
                <div id="logo">
                    <a href="https://www.prounlockphone.com/"><img src='https://www.prounlockphone.com/images/pup1.png' /></a>
                </div>
                <nav id="primary-menu">
                    <ul class="sf-menu" style="touch-action: pan-y;">
                        <li>
                            <a href="https://www.prounlockphone.com/">
                                <div>Home</div>
                            </a>
                        </li>
                        <li<?php echo $currentTab == "login" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/login/">
                                <div>Login</div>
                            </a>
                        </li>
                        <li<?php echo $currentTab == "register" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/register/">
                                <div>Register</div>
                            </a>
                        </li>
                        <li<?php echo $currentTab == "services" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/services/" class="sf-with-ul">
                                <div>List of services</div>
                            </a>
                        </li>
                        <li<?php echo $currentTab == "check" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/quick-order/Apple Check Services/?quick=no">
                                <div>Check IMEI</div>
                            </a>
                        </li>
                        <li<?php
                        if($currentTab == "quick" || $currentTab == "factory" || $currentTab == "icloud" || $currentTab == "gsx" || $currentTab == "generic") {
                            echo ' class="current"';
                        }
                        ?>>
                            <a href="https://www.prounlockphone.com/quick-order/" class="sf-with-ul">
                                <div>Quick Order <b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <li<?php echo $currentTab == "factory" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/quick-order/iPhone Factory Unlock/" style="font-size: 10px">
                                        <div>iPhone Factory Unlock</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $currentTab == "icloud" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/quick-order/iCloud Services/" style="font-size: 10px">
                                        <div>iCloud Services</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $currentTab == "gsx" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/quick-order/Apple Check Services/" style="font-size: 10px">
                                        <div>Apple Check Services</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $currentTab == "generic" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/services/" style="font-size: 10px">
                                        <div>Remove Network Simlock</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li<?php echo ($currentTab == "track" ? ' class="current"' : "") ?>>
                            <a href="https://www.prounlockphone.com/track/">
                                <div>Track Order</div>
                            </a>
                        </li>
                        <li<?php
                        if($currentTab == "contact" || $currentTab == "forum") {
                            echo ' class="current"';
                        }
                        ?>>
                            <a href="#" class="sf-with-ul">
                                <div>Support <b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <li<?php echo $currentTab == "contact" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/contact/" style="font-size: 10px">
                                        <div>Contact us</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $currentTab == "forum" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/forum/" style="font-size: 10px">
                                        <div>Forum</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php
                            $currencies = array('USD', 'EUR', 'GBP', 'TND');
                            $currencies = array_diff($currencies, [$_SESSION['currency']]);
                            ?>
                            <a href="#" class="sf-with-ul">
                                <div><?php echo $_SESSION['currency'] ?> <img style="margin-top:-3px" src="https://www.prounlockphone.com/images/currencies/<?php echo $_SESSION['currency'] ?>.png" alt="<?php echo $_SESSION['currency'] ?>" /><b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <?php
                                foreach ($currencies as $cur) {
                                    ?>
                                    <hr style="margin: 0px"/>
                                    <li class="sf-options">
                                        <a href="https://www.prounlockphone.com/currency.php?url=<?php echo $_SERVER['REQUEST_URI'] ?>&currency=<?php echo $cur ?>">
                                            <div><?php echo $cur ?>&nbsp;&nbsp;&nbsp;<img style="margin-top:-3px" src="https://www.prounlockphone.com/images/currencies/<?php echo $cur ?>.png" alt="<?php echo $cur ?>" /></div>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <div class="clear"></div>
    <?php

    if($_SERVER['REQUEST_URI'] <> '/login/') echo '
    <div align="center">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-5111821234953725"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-5111821234953725"
            });
        </script>
    </div>';
}

$common_foot = '<div id="gotoTop" class="fa fa-caret-up" style="display: none;"></div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1041;">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>
    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1041;">
        <div class="modal-dialog" style="width: 96%;">
            <div class="modal-content"></div>
        </div>
    </div>
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1100;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" class="close" data-dismiss="modal" type="button">x</button>
                    <h4 class="modal-title" style="color: red;">
                        Error!
                    </h4>
                </div>
                <div class="modal-body container-fluid">
                    <p>Communication token expired, please refresh the page and try again!</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.prounlockphone.com/common/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.prounlockphone.com/common/plugins.js"></script>
    <script type="text/javascript" src="https://www.prounlockphone.com/common/functions.js"></script>
    <script type="text/javascript" src="https://www.prounlockphone.com/common/jquery.jgrowl.min.js"></script>
    <script type="text/javascript" src="https://www.prounlockphone.com/common/jquery.form.min.js"></script>
    <script type="text/javascript" src="https://www.prounlockphone.com/common/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="https://www.prounlockphone.com/common/script.js"></script>
    <script type="text/javascript" src="https://www.prounlockphone.com/common/switchery.js"></script>
    <div id="jGrowl" class="top-right jGrowl">
        <div class="jGrowl-notification"></div> 
    </div>';
?>