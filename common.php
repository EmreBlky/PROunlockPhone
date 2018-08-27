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
set_time_limit(300);
header('Content-Type: text/html; charset=utf-8');

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
        <meta property="og:locale" content="en_US" />
        <meta name="twitter:description" content="<?php echo $ogDescription ?>">
        
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
        <meta name="application-name" content="PROunlockPhone 2015-' . date('Y') . '™">

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

function admin_common_head_with_title($title, $select = "", $summernote = false, $chosen = false) {
    $toReturn = "<title>{$title} - PROunlockPhone</title>
    <link rel='shortcut icon' href='https://www.prounlockphone.com/images/pup-ico-16x16.png' type='image/x-icon' />
    <script type='text/javascript' src='https://www.prounlockphone.com/scripts/jquery-1.11.3.min.js'></script>
    <script src='https://www.prounlockphone.com/scripts/bootstrap.min.js'></script>
    <script type='text/javascript' src='https://www.prounlockphone.com/scripts/jquery.smartmenus.js'></script>
    <script type='text/javascript' src='https://www.prounlockphone.com/scripts/jquery.smartmenus.bootstrap.js'></script>
    <link href='https://www.prounlockphone.com/style/bootstrap.min.css' rel='stylesheet' />
    <link href='https://www.prounlockphone.com/style/jquery.smartmenus.bootstrap.css' rel='stylesheet' />
    ";
    if($chosen) $toReturn .= "<script src='https://www.prounlockphone.com/scripts/chosen.jquery.min.js' type='text/javascript'></script>
    ";
    if($select != "") {
        $toReturn .= "<link rel='stylesheet' href='https://www.prounlockphone.com/style/select2.css' />
    <script src='https://www.prounlockphone.com/scripts/select2.min.js' type='text/javascript'></script>
    <style type='text/css'>
        ";
        if ($select == "20") {
            $toReturn .= ".select2-container { font-size:20px; }
        .select2-search input { font-size: 20px; }
        .select2-results__option { font-size:22px; }
        .select2-results__option[aria-selected] { font-size:20px; }
        .select2-container--classic .select2-selection--single .select2-selection__rendered { line-height: 42px; }
        .select2-container--classic .select2-selection--single .select2-selection__arrow { height: 42px; }
        .select2-container .select2-selection--single { height: 44px; }
        ";
        } elseif ($select == "10") {
            $toReturn .= ".select2-container { font-size:10px; }
        .select2-search input { font-size: 10px; }
        .select2-results__option { font-size:10px; }
        .select2-results__option[aria-selected] { font-size:10px; }
        .select2-container--classic .select2-selection--single .select2-selection__rendered { line-height: 18px; }
        .select2-container--classic .select2-selection--single .select2-selection__arrow { height: 18px; }
        .select2-container .select2-selection--single { height: 20px; }
        ";
        }
        $toReturn .= ".select2-results { max-height: 400px; }
        .select2-container--classic .select2-results > .select2-results__options { max-height: 400px; }
        .select2-results { max-height: 400px; }
        .select2-container .select2-selection--single .select2-selection__rendered { white-space: nowrap; }
    </style>
    ";
    }
    if($summernote) $toReturn .= "<link href='https://www.prounlockphone.com/common/aa/summernote.css' rel='stylesheet'>
    <script src='https://www.prounlockphone.com/common/aa/summernote.js'></script>
    <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
    <link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>";
    return $toReturn;
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

class DBConnection {
    
    public $Link;
    
    public function __construct() {
        $this->Link = mysqli_connect("", "touwereg", "Kh0ube1b$");
        $this->Link->set_charset('utf8mb4');
        mysqli_select_db($this->Link, "prounlockphone");
        mysqli_set_charset($this->Link, "utf8mb4");
        mysqli_query($this->Link, "SET names = 'utf8mb4', SET character_set_results = 'utf8mb4', character_set_client = 'utf8mb4', character_set_connection = 'utf8mb4', character_set_database = 'utf8mb4', character_set_server = 'utf8mb4'");
    }
}

function cleanResponse($response, $serial = "") {
    $start = stripos($response, "<pre");
    while($start !== false) {
        $response = substr($response, 0, $start) . substr($response, stripos(substr($response, $start), ">") + $start + 1);
        $start = stripos($response, "<pre");
    }
    $response = str_replace("</pre>", "", $response);
    $response = str_replace("</ pre>", "", $response);
    if(substr($response, 0, 16) == $serial . " ") $response = substr($response, 16);
    return $response;
}
?>