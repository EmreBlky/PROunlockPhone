<?php
define('INCLUDE_CHECK', true);
require 'common.php';
require 'offline.php';
$DB = new DBConnection();
?><!DOCTYPE html>

<html lang="en">
    <head>
        <?php echo common_head_with_title("") ?>
        <meta name="msapplication-TileColor" content="#FFFFFF">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
        <link href="https://www.prounlockphone.com/homepage-styles/file-1.css" rel="stylesheet" type="text/css">
        <link href="https://www.prounlockphone.com/homepage-styles/file-2.css" rel="stylesheet" type="text/css">
        <link href="https://www.prounlockphone.com/homepage-styles/file-3.css" rel="stylesheet" type="text/css">
        <link href="https://www.prounlockphone.com/homepage-styles/file-4.css" rel="stylesheet" type="text/css">
        <link href="https://www.prounlockphone.com/homepage-styles/file-5.css" rel="stylesheet" type="text/css">
        <link href="https://www.prounlockphone.com/homepage-styles/file-6.css" rel="stylesheet" type="text/css">
        <link href="https://www.prounlockphone.com/homepage-styles/file-7.css" rel="stylesheet" type="text/css">

        <link href="https://www.prounlockphone.com/homepage-styles/skin-dots.css" rel="stylesheet" type="text/css" media="all">
        <link href="https://www.prounlockphone.com/homepage-styles/slide1-positions-fullscreen.css" rel="stylesheet" type="text/css" media="all">
        <link href="https://www.prounlockphone.com/homepage-styles/slide1-colors.css" rel="stylesheet" type="text/css" media="all">

        <link href="https://www.prounlockphone.com/homepage-styles/dark1.css" rel="stylesheet">
        <link href="https://www.prounlockphone.com/homepage-styles/cinematic.css" rel="stylesheet">
        <link href="https://www.prounlockphone.com/homepage-styles/loading-bars.css" rel="stylesheet">
</head>

<body>
    <div class="iS-Loading">
        <div class="iS-Loadingbox">
            <div class="loader">Loading...</div>
        </div>
    </div>

    <a class="interLink" href="#home">
        <div class="WebX-Back WebX-animate WebX-animate-hide">
            <i class="fa fa-caret-up WebX-Back-Icon"></i>
            <p>TOP</p>
        </div>
    </a>

    <div id="slider" class="iS iS-SkinDots epeoFullscreen" style="width: 1600px; height: 701px;">
        <div class="iS-Commands" style="opacity: 1;">
            <div class="WebX-Scroll WebX-animate">
                <p><a class="interLink" href="#about">SCROLL DOWN</a></p>
                <i class="fa fa-caret-down WebX-Scroll-Icon"></i>
            </div>
            <div class="iS-Previous"><i class="fa fa-chevron-left"></i></div>
            <div class="iS-Next"><i class="fa fa-chevron-right"></i></div>
            <div class="iS-Play"><i class="fa fa-play"></i></div>
            <div class="iS-Stop iS-Stopactive"><i class="fa fa-pause"></i></div>
            <div class="iS-Loopline" style="animation-duration: 5000ms;"></div>
            <div class="iS-Dots">
                <div class="iS-Dot iS-Dotactive"></div>
                <div class="iS-Dot"></div>
                <div class="iS-Dot"></div>
                <div class="iS-Dot"></div>
            </div>
        </div>
        <div class="iS-Content" id="home" style="opacity: 1;">
            <div class="iS-Items iS-Active iS-Activede" data-looptime="5000">
                <div class="iS-Item iS-Cinematic iS-CinematicCustom" data-effectin="zoomout fadein" data-effectintime="500" data-effectindelay="0" data-effectout="zoomout fadein" data-effectouttime="500" data-effectoutdelay="1000" data-effectinback="zoomout fadein" data-effectintimeback="500" data-effectindelayback="0" data-effectoutback="zoomout fadein" data-effectouttimeback="500" data-effectoutdelayback="1000">
                    <img class="iS-Image iS-ImageAutofit md-invisible lg-invisible" src="https://www.prounlockphone.com/images/slide1.jpg" alt="image1" style="width: 100%; height: auto; margin-top: -149.5px;">
                    <img class="iS-Image iS-ImageAutofit xs-invisible sm-invisible" src="https://www.prounlockphone.com/images/slide1.jpg" alt="image1res" style="width: 100%; height: auto; margin-top: -149.5px;">
                </div>
                <div class="iS-Item" data-effectin="slideright fadein" data-effectintime="500" data-effectindelay="500" data-effectout="fadein" data-effectouttime="500" data-effectoutdelay="0" data-effectinback="slideright fadein" data-effectintimeback="500" data-effectindelayback="500" data-effectoutback="fadein" data-effectouttimeback="500" data-effectoutdelayback="0">
                    <div class="iS-Bg iS-Grey-Mask"></div>
                </div>
                <div class="iS-Item" data-effectin="zoomin fadein" data-effectintime="1000" data-effectindelay="1000" data-effectout="zoomout fadein" data-effectouttime="500" data-effectoutdelay="0" data-effectinback="zoomin fadein" data-effectintimeback="1000" data-effectindelayback="500" data-effectoutback="zoomout fadein" data-effectouttimeback="500" data-effectoutdelayback="0">
                    <div class="slide1-text1 epeoCenter epeoMiddle" style="left: auto; right: auto; margin: 278.5px auto auto 406px; top: auto; bottom: auto;">Looking for <span class="WebX-colored">FAST, CHEAP & RELIABLE</span> Service?</div>
                </div>
            </div>
            <div class="iS-Items iS-Proactive" data-looptime="5000">
                <div class="iS-Item iS-Cinematic iS-CinematicCustom" data-effectin="slidebottom fadein" data-effectintime="500" data-effectindelay="0" data-effectout="zoomout fadein" data-effectouttime="500" data-effectoutdelay="1000" data-effectinback="slidebottom fadein" data-effectintimeback="500" data-effectindelayback="0" data-effectoutback="zoomout fadein" data-effectouttimeback="500" data-effectoutdelayback="1000">
                    <img class="iS-Image iS-ImageAutofit md-invisible lg-invisible" src="https://www.prounlockphone.com/images/slide2.jpg" alt="image2" style="width: 100%; height: auto; margin-top: -99.5px;">
                    <img class="iS-Image iS-ImageAutofit xs-invisible sm-invisible" src="https://www.prounlockphone.com/images/slide2.jpg" alt="image2res" style="width: 100%; height: auto; margin-top: -99.5px;">
                </div>
                <div class="iS-Item" data-effectin="slidebottom fadein" data-effectintime="500" data-effectindelay="500" data-effectout="fadein" data-effectouttime="500" data-effectoutdelay="0" data-effectinback="slidebottom fadein" data-effectintimeback="500" data-effectindelayback="500" data-effectoutback="fadein" data-effectouttimeback="500" data-effectoutdelayback="0">
                    <div class="iS-Bg iS-Grey-Mask"></div>
                </div>
                <div class="iS-Item" data-effectin="zoomin slidebottomscreen fadein" data-effectintime="1000" data-effectindelay="1000" data-effectout="zoomout fadein" data-effectouttime="500" data-effectoutdelay="0" data-effectinback="zoomin slidebottomscreen fadein" data-effectintimeback="1000" data-effectindelayback="500" data-effectoutback="zoomout fadein" data-effectouttimeback="500" data-effectoutdelayback="0">
                    <div class="slide1-text1 epeoCenter epeoMiddle" style="left: auto; right: auto; margin: 278.5px auto auto 341.5px; top: auto; bottom: auto;">Enough of being pissed of by <span class="WebX-colored">SCAMMERS</span></div>
                </div>
            </div>
            <div class="iS-Items iS-Proactive" data-looptime="5000">
                <div class="iS-Item iS-Cinematic iS-CinematicCustom" data-effectin="slideleft fadein" data-effectintime="500" data-effectindelay="0" data-effectout="zoomout fadein" data-effectouttime="500" data-effectoutdelay="1000" data-effectinback="slideleft fadein" data-effectintimeback="500" data-effectindelayback="0" data-effectoutback="zoomout fadein" data-effectouttimeback="500" data-effectoutdelayback="1000">
                    <img class="iS-Image iS-ImageAutofit md-invisible lg-invisible" src="https://www.prounlockphone.com/images/slide3.jpg" alt="image3" style="width: 100%; height: auto; margin-top: -99.5px;">
                    <img class="iS-Image iS-ImageAutofit xs-invisible sm-invisible" src="https://www.prounlockphone.com/images/slide3.jpg" alt="image3res" style="width: 100%; height: auto; margin-top: -99.5px;">
                </div>
                <div class="iS-Item" data-effectin="fadein" data-effectintime="500" data-effectindelay="500" data-effectout="fadein" data-effectouttime="500" data-effectoutdelay="0" data-effectinback="fadein" data-effectintimeback="500" data-effectindelayback="500" data-effectoutback="fadein" data-effectouttimeback="500" data-effectoutdelayback="0">
                    <div class="iS-Bg iS-Grey-Mask"></div>
                </div>
                <div class="iS-Item" data-effectin="zoomin slidetopscreen fadein" data-effectintime="1000" data-effectindelay="1000" data-effectout="zoomout fadein" data-effectouttime="500" data-effectoutdelay="0" data-effectinback="zoomin slidetopscreen fadein" data-effectintimeback="1000" data-effectindelayback="500" data-effectoutback="zoomout fadein" data-effectouttimeback="500" data-effectoutdelayback="0">
                    <div class="slide1-text1 epeoCenter epeoMiddle" style="left: auto; right: auto; margin: 278.5px auto auto 326px; top: auto; bottom: auto;">Prefer <span class="WebX-colored">1-to-1</span> assistance</div>
                </div>
            </div>
            <div class="iS-Items iS-Proactive" data-looptime="5000">
                <div class="iS-Item iS-Cinematic iS-CinematicCustom" data-effectin="slidebottom fadein" data-effectintime="500" data-effectindelay="0" data-effectout="zoomout fadein" data-effectouttime="500" data-effectoutdelay="1000" data-effectinback="slidebottom fadein" data-effectintimeback="500" data-effectindelayback="0" data-effectoutback="zoomout fadein" data-effectouttimeback="500" data-effectoutdelayback="1000">
                    <img class="iS-Image iS-ImageAutofit md-invisible lg-invisible" src="https://www.prounlockphone.com/images/slide4.jpg" alt="image4" style="width: 100%; height: auto; margin-top: -99.5px;">
                    <img class="iS-Image iS-ImageAutofit xs-invisible sm-invisible" src="https://www.prounlockphone.com/images/slide4.jpg" alt="image4res" style="width: 100%; height: auto; margin-top: -99.5px;">
                </div>
                <div class="iS-Item" data-effectin="slidebottom fadein" data-effectintime="500" data-effectindelay="500" data-effectout="fadein" data-effectouttime="500" data-effectoutdelay="0" data-effectinback="slidebottom fadein" data-effectintimeback="500" data-effectindelayback="500" data-effectoutback="fadein" data-effectouttimeback="500" data-effectoutdelayback="0">
                    <div class="iS-Bg iS-Grey-Mask"></div>
                </div>
                <div class="iS-Item" data-effectin="zoomin slidebottomscreen fadein" data-effectintime="1000" data-effectindelay="1000" data-effectout="zoomout fadein" data-effectouttime="500" data-effectoutdelay="0" data-effectinback="zoomin slidebottomscreen fadein" data-effectintimeback="1000" data-effectindelayback="500" data-effectoutback="zoomout fadein" data-effectouttimeback="500" data-effectoutdelayback="0">
                    <div class="slide1-text1 epeoCenter epeoMiddle" style="left: auto; right: auto; margin: 278.5px auto auto 253.5px; top: auto; bottom: auto;"><img src="images/pup.png" class="WebX-footer-logo" alt="PRO Unlock Phone"> PRO <span class="WebX-colored">Unlock</span> Phone</div>
                </div>
            </div>
        </div>
	<div class="iS-Style">
            <style>
#slider .iS-Preactive > .iS-Item[data-effectIn~="fadein"]{opacity: 0;}
#slider .iS-Preactive > .iS-Item[data-effectIn~="zoomout"] > *{-webkit-transform:  scale(2); transform:  scale(2);}
#slider .iS-Preactive > .iS-Item[data-effectIn~="slideright"] > *{-webkit-transform:  translateX(100%); transform:  translateX(100%);}
#slider .iS-Preactive > .iS-Item[data-effectIn~="zoomin"] > *{-webkit-transform:  scale(0); transform:  scale(0);}
#slider .iS-Preactive > .iS-Item[data-effectIn~="slidebottom"] > *{-webkit-transform:  translateY(100%); transform:  translateY(100%);}
#slider .iS-Preactive > .iS-Item[data-effectIn~="slidebottomscreen"]{-webkit-transform:  translateY(100%); transform:  translateY(100%);}
#slider .iS-Preactive > .iS-Item[data-effectIn~="slideleft"] > *{-webkit-transform:  translateX(-100%); transform:  translateX(-100%);}
#slider .iS-Preactive > .iS-Item[data-effectIn~="slidetopscreen"]{-webkit-transform:  translateY(-100%); transform:  translateY(-100%);}
#slider .iS-Proactive > .iS-Item[data-effectOut~="fadein"]{opacity: 0;}
#slider .iS-Proactive > .iS-Item[data-effectOut~="zoomout"] > *{-webkit-transform:  scale(2); transform:  scale(2);}
#slider .iS-Pruactive > .iS-Item[data-effectInBack~="fadein"]{opacity: 0;}
#slider .iS-Pruactive > .iS-Item[data-effectInBack~="zoomout"] > *{-webkit-transform:  scale(2); transform:  scale(2);}
#slider .iS-Pruactive > .iS-Item[data-effectInBack~="slideright"] > *{-webkit-transform:  translateX(100%); transform:  translateX(100%);}
#slider .iS-Pruactive > .iS-Item[data-effectInBack~="zoomin"] > *{-webkit-transform:  scale(0); transform:  scale(0);}
#slider .iS-Pruactive > .iS-Item[data-effectInBack~="slidebottom"] > *{-webkit-transform:  translateY(100%); transform:  translateY(100%);}
#slider .iS-Pruactive > .iS-Item[data-effectInBack~="slidebottomscreen"]{-webkit-transform:  translateY(100%); transform:  translateY(100%);}
#slider .iS-Pruactive > .iS-Item[data-effectInBack~="slideleft"] > *{-webkit-transform:  translateX(-100%); transform:  translateX(-100%);}
#slider .iS-Pruactive > .iS-Item[data-effectInBack~="slidetopscreen"]{-webkit-transform:  translateY(-100%); transform:  translateY(-100%);}
#slider .iS-Priactive > .iS-Item[data-effectOutBack~="fadein"]{opacity: 0;}
#slider .iS-Priactive > .iS-Item[data-effectOutBack~="zoomout"] > *{-webkit-transform:  scale(2); transform:  scale(2);}
#slider .iS-Activede > .iS-Item[data-effectInTime="500"],
#slider .iS-Activede > .iS-Item[data-effectInTime="500"] > *{-webkit-transition: -webkit-transform 500ms ease-in-out, opacity 500ms ease-in-out; transition: transform 500ms ease-in-out, opacity 500ms ease-in-out;}
#slider .iS-Proactive > .iS-Item[data-effectOutTime="500"],
#slider .iS-Proactive > .iS-Item[data-effectOutTime="500"] > *{-webkit-transition: -webkit-transform 500ms ease-in-out, opacity 500ms ease-in-out; transition: all 500ms ease-in-out, opacity 500ms ease-in-out;}
#slider .iS-Activede > .iS-Item[data-effectInTime="1000"],
#slider .iS-Activede > .iS-Item[data-effectInTime="1000"] > *{-webkit-transition: -webkit-transform 1000ms ease-in-out, opacity 1000ms ease-in-out; transition: transform 1000ms ease-in-out, opacity 1000ms ease-in-out;}
#slider .iS-Activedu > .iS-Item[data-effectInTimeBack="500"],
#slider .iS-Activedu > .iS-Item[data-effectInTimeBack="500"] > * {-webkit-transition: -webkit-transform 500ms ease-in-out, opacity 500ms ease-in-out; transition: transform 500ms ease-in-out, opacity 500ms ease-in-out;}
#slider .iS-Activedu > .iS-Item[data-effectInTimeBack="1000"],
#slider .iS-Activedu > .iS-Item[data-effectInTimeBack="1000"] > * {-webkit-transition: -webkit-transform 1000ms ease-in-out, opacity 1000ms ease-in-out; transition: transform 1000ms ease-in-out, opacity 1000ms ease-in-out;}
#slider .iS-Priactive > .iS-Item[data-effectOutTimeBack="500"],
#slider .iS-Priactive > .iS-Item[data-effectOutTimeBack="500"] > * {-webkit-transition: -webkit-transform 500ms ease-in-out, opacity 500ms ease-in-out; transition: transform 500ms ease-in-out, opacity 500ms ease-in-out;}
#slider .iS-Proactive > .iS-Item[data-effectOutDelay="1000"],
#slider .iS-Proactive > .iS-Item[data-effectOutDelay="1000"] > *{-webkit-transition-delay: 1000ms;	transition-delay: 1000ms;}
#slider .iS-Activede > .iS-Item[data-effectInDelay="500"],
#slider .iS-Activede > .iS-Item[data-effectInDelay="500"] > *{-webkit-transition-delay: 500ms; transition-delay: 500ms;}
#slider .iS-Activede > .iS-Item[data-effectInDelay="1000"],
#slider .iS-Activede > .iS-Item[data-effectInDelay="1000"] > *{-webkit-transition-delay: 1000ms; transition-delay: 1000ms;}
#slider .iS-Activedu > .iS-Item[data-effectInDelayBack="500"],
#slider .iS-Activedu > .iS-Item[data-effectInDelayBack="500"] > *{-webkit-transition-delay: 500ms; transition-delay: 500ms;}
#slider .iS-Priactive > .iS-Item[data-effectOutDelayBack="1000"],
#slider .iS-Priactive > .iS-Item[data-effectOutDelayBack="1000"] > *{-webkit-transition-delay: 1000ms; transition-delay: 1000ms;}
.iS-Active {z-index: 2!important;}
.iS-Notime > .iS-Item, .iS-Notime > .iS-Item > * {-webkit-transition: all 0s!important;	transition: all 0s!important;-webkit-transition-delay: 0s!important;transition-delay: 0s!important;}
.iS-Item > * {-webkit-perspective: 1000px;perspective: 1000px;}
.iS-Item > * {-webkit-backface-visibility: hidden;backface-visibility: hidden;}
@-webkit-keyframes looplineactive {0% {-webkit-transform: scaleX(0);}100% {-webkit-transform: scaleX(1);}}
@keyframes looplineactive {0% {transform: scaleX(0);}100% {transform: scaleX(1);}}
.iS-Loopline {	-webkit-transform: scaleX(0);transform: scaleX(0);}
.iS-Looplineactive {-webkit-animation: looplineactive infinite;animation: looplineactive infinite;	-webkit-animation-timing-function: ease-in-out;animation-timing-function: ease-in-out;-webkit-transform-origin: 0% 0% 0; transform-origin: 0% 0% 0;}
            </style>
        </div>
        <div class="iS-Firstload"></div>
    </div>
	<!--End Slider -->

	<nav id="WebX-menu" class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand interLink" href="#home"><img class="navbar-logo" src="images/pup.png" alt="PRO Unlock Phone"></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
                    <li><a href="https://www.prounlockphone.com/login/">Login</a></li>
                    <li><a href="https://www.prounlockphone.com/register/">Register</a></li>
                    <li><a href="#services" class="interLink">Services</a></li>
                    <li><a href="https://www.prounlockphone.com/quick-order/">Quick Order</a></li>
                    <li><a href="https://www.prounlockphone.com/quick-order/Apple Check Services/">Check IMEI/SN</a></li>
                    <li><a href="https://www.prounlockphone.com/track/">Track Order</a></li>
                    <li><a href="#payment" class="interLink">Payment Methods</a></li>
                    <li><a href="#support" class="interLink">Support Team</a></li>
                    <li><a href="#contact-us" class="interLink">Contact Us</a></li>
                    <li><a href="https://www.prounlockphone.com/forum/">Forum</a></li>
                    <li><a href="https://www.prounlockphone.com/terms/">Terms</a></li>
					<li><a href="#about" class="interLink">About</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>

	<div class="WebX-title-bg" id="about">
		<div class="container">

			<div class="row">
				<div class="col-md-12 text-center WebX-uppercase">
					<h4 class="WebX-border-title">What is prounlockphone.com?</h4>
				</div>
			</div>

		</div>
	</div>

	<div class="container">

		<div class="row WebX-margin20">
			<div class="col-md-12 text-center WebX-uppercase">
				<h1 class="WebX-title WebX-margin40">Best Phone <span class="WebX-colored">Unlocking Server</span>
				</h1>
				<p class="WebX-margin10">Our specialized team work hard everyday trying to keep up with all existing services 
                                    and try to offer our clients the best quality with the minimum of expense. We provide a large variety of
                                    services for all different brands<br />Apple - Samsung - Nokia - BlackBerry - LG - HTC - Sony Xperia</p>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 WebX-divisor-border40 WebX-divisor-topborder10 WebX-margin50">
				<div class="col-md-4 WebX-margin50 WebX-icon-hover WebX-animate-slow WebX-animate-right WebX-sequence-slow">
					<i class="center-block WebX-ticket-icon fa fa-rocket"></i>
					<div class="WebX-ticket-paragraph">
						<h3 class="WebX-title">FAST SERVICES</h3>
						<p>We use API and other technologies to boost our services in order
                                                    to shorten the processing time of all orders.</p>
					</div>
				</div>
				<div class="col-md-4 WebX-margin50 WebX-icon-hover WebX-animate-slow WebX-animate-right WebX-sequence-slow">
					<i class="center-block WebX-ticket-icon fa fa-paw"></i>
					<div class="WebX-ticket-paragraph">
						<h3 class="WebX-title">DIRECT SOURCE</h3>
						<p>Large part of our services is directly processed by our team which helps keeping
                                                    the prices reasonable and optimizes the processing time and cancellation conditions.</p>
					</div>
				</div>
				<div class="col-md-4 WebX-margin50 WebX-icon-hover WebX-animate-slow WebX-animate-right WebX-sequence-slow">
					<i class="center-block WebX-ticket-icon fa fa-building"></i>
					<div class="WebX-ticket-paragraph">
						<h3 class="WebX-title">MONEYBACK POLICY</h3>
						<p>We are the only phone unlocking provider who guarantees 100% money-back with no question asked.
                            We offer flexible payment methods to resellers and store owners.</p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 text-center WebX-uppercase WebX-margin60 WebX-animate-slow WebX-animate-left">
				<p class="WebX-margin10">Short summary about our community and services...</p>
			</div>
		</div>

	</div>

	<div class="WebX-grey-bg WebX-margin60">
		<div class="container">

			<div class="row">
				<div class="col-md-3 text-center WebX-animate-slow WebX-animate-bottom WebX-sequence">
					<div class="WebX-number WebX-title">
<?php
$part1 = "0";
$part2 = "0";
require_once('./admin/Mobile-Detect-2.8.22/Mobile_Detect.php');
$detect = new Mobile_Detect;
$isPhone = $detect->isMobile() || $detect->isTablet();
$rows = mysqli_query($DB->Link, "SELECT DISTINCT country FROM users");
$ones = mysqli_num_rows($rows) + 42 - 100;
if($isPhone) {
    $part1 = "1";
    $part2 = $ones;
}
?>
						<span class="WebX-colored numberRoll" data-number="1"><?php echo $part1 ?></span><span class="numberRoll" data-number="<?php echo $ones ?>"><?php echo $part2 ?></span>
					</div>
					<h3 class="WebX-title">Represented Countries</h3>
					<p class="WebX-margin10">Our community is spread all around the globe and we welcome all new representatives.</p>
				</div>
				<div class="col-md-3 text-center WebX-animate-slow WebX-animate-bottom WebX-sequence">
					<div class="WebX-number WebX-title">
<?php
$rows = mysqli_query($DB->Link, "SELECT id FROM users");
if($isPhone) {
    $part1 = "3";
    $part2 = mysqli_num_rows($rows);
}
?>
						<span class="WebX-colored numberRoll" data-number="3"><?php echo $part1 ?></span><span class="numberRoll" data-number="<?php echo mysqli_num_rows($rows) ?>"><?php echo $part2 ?></span>
					</div>
					<h3 class="WebX-title">Our Communtity</h3>
					<p class="WebX-margin10">We daily have new people joining our communty and trusting our services.</p>
				</div>
				<div class="col-md-3 text-center WebX-animate-slow WebX-animate-bottom WebX-sequence">
					<div class="WebX-number WebX-title">
<?php
$rows = mysqli_query($DB->Link, "SELECT id FROM services");
$tens = floor(mysqli_num_rows($rows) / 100);
$ones = mysqli_num_rows($rows) - $tens * 100;
if($isPhone) {
    $part1 = $tens;
    $part2 = $ones;
}
?>
						<span class="WebX-colored numberRoll" data-number="<?php echo $tens ?>"><?php echo $part1 ?></span><span class="numberRoll" data-number="<?php echo $ones ?>"><?php echo $part2 ?></span>
					</div>
					<h3 class="WebX-title">Active Services</h3>
					<p class="WebX-margin10">Every single day, we open new services and update our pricing.</p>
				</div>
				<div class="col-md-3 text-center WebX-animate-slow WebX-animate-bottom WebX-sequence">
					<div class="WebX-number WebX-title">
<?php
$rows = mysqli_query($DB->Link, "SELECT id FROM orders");
$ones = 10 - (mysqli_num_rows($rows) % 10);
if(mysqli_num_rows($rows) < 10000) {
    $tens = floor(mysqli_num_rows($rows) / 1000);
    $ones = (mysqli_num_rows($rows) - $tens * 1000) * 10 + $ones;
} else {
    $tens = floor(mysqli_num_rows($rows) / 10000);
    $ones = (mysqli_num_rows($rows) - $tens * 10000) * 10 + $ones;
}
if($isPhone) {
    $part1 = $tens;
    $part2 = $ones;
}
?>
						<span class="WebX-colored numberRoll" data-number="<?php echo $tens ?>"><?php echo $part1 ?></span><span class="numberRoll" data-number="<?php echo $ones ?>"><?php echo $part2 ?></span>
					</div>
					<h3 class="WebX-title">Total Orders</h3>
					<p class="WebX-margin10">Since we started providing service, we deliver everyday results
                                            and we keep record of this progress to witness our efficiency and professionalism.</p>
				</div>
			</div>

		</div>
	</div>

	<div class="WebX-parallax1 WebX-parallax-content">
		<div class="WebX-background">
			<div class="container">

				<div class="row text-center">
					<div class="col-md-12 WebX-uppercase WebX-animate-slow WebX-animate-fade">
						<h3 class="WebX-margin40">"Serving you is 
							<span class="WebX-colored">OUR MOTTO</span>."</h3>
                                                        <p class="WebX-margin10">PROUNLOCKPHONE TEAM</p>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="WebX-title-bg" id="services">
		<div class="container">

			<div class="row">
				<div class="col-md-12 text-center WebX-uppercase">
					<h4 class="WebX-border-title">Our most famous products.</h4>
				</div>
			</div>

		</div>
	</div>

	<div class="container">

		<div class="row WebX-margin20">
			<div class="col-md-12 text-center WebX-uppercase">
				<h3 class="WebX-margin40 WebX-title">This is just part of a <span class="WebX-colored">LARGE</span> variety of services.
                                    <br />Check all <a href="https://www.prounlockphone.com/services/">here</a>
                                </h3>
			</div>
		</div>

	</div>
	<div class="WebX-grey-bg WebX-margin60">
		<div class="container">

			<div class="row">

				<!-- First Team Member -->
				<div class="col-sm-6 col-md-3 WebX-margin20 WebX-animate WebX-animate-right WebX-sequence">
					<div class="WebX-team-box center-block">
						<div class="WebX-team-img-container">
							<img src="https://www.prounlockphone.com/images/service-1.jpg" class="WebX-team-img-effect" alt="iCloud">
							<img src="https://www.prounlockphone.com/images/service-1.jpg" class="WebX-team-img" alt="iCloud">
						</div>
						<div class="text-center">
                                                    <h3 class="WebX-margin10"><span class="WebX-colored">iCloud</span> Unlock<br />CLEAN / PREMIUM</h3>
							<div class="WebX-team-social">
								<a target="_blank" href="https://www.prounlockphone.com/quick-order/iCloud Services/"><i class="fa fa-link WebX-icon-social"></i></a>
							</div>
						</div>
					</div>
				</div>
				<!-- First Team Member End -->

				<!-- Second Team Member -->
				<div class="col-sm-6 col-md-3 WebX-margin20 WebX-animate WebX-animate-right WebX-sequence">
					<div class="WebX-team-box center-block">
						<div class="WebX-team-img-container">
							<img src="https://www.prounlockphone.com/images/service-2.jpg" class="WebX-team-img-effect" alt="iPhone">
							<img src="https://www.prounlockphone.com/images/service-2.jpg" class="WebX-team-img" alt="iPhone">
						</div>
						<div class="text-center">
                                                    <h3 class="WebX-margin10"><span class="WebX-colored">iPhone</span> Worldwide<br />Unlocking Services</h3>

							<div class="WebX-team-social">
								<a target="_blank" href="https://www.prounlockphone.com/quick-order/iPhone Factory Unlock/"><i class="fa fa-link WebX-icon-social"></i></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Second Team Member End -->

				<!-- Third Team Member -->
				<div class="col-sm-6 col-md-3 WebX-margin20 WebX-animate WebX-animate-right WebX-sequence">
					<div class="WebX-team-box center-block">
						<div class="WebX-team-img-container">
							<img src="https://www.prounlockphone.com/images/service-3.jpg" class="WebX-team-img-effect" alt="Samsung">
							<img src="https://www.prounlockphone.com/images/service-3.jpg" class="WebX-team-img" alt="Samsung">
						</div>
						<div class="text-center">
                                                    <h3 class="WebX-margin10"><span class="WebX-colored">Samsung</span> services<br />NCK / FRP</h3>
							<div class="WebX-team-social">
								<a target="_blank" href="https://www.prounlockphone.com/services/"><i class="fa fa-link WebX-icon-social"></i></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Third Team Member End -->

				<!-- Fourth Team Member -->
				<div class="col-sm-6 col-md-3 WebX-margin20 WebX-animate WebX-animate-right WebX-sequence">
					<div class="WebX-team-box center-block">
						<div class="WebX-team-img-container">
							<img src="https://www.prounlockphone.com/images/service-4.jpg" class="WebX-team-img-effect" alt="Check">
							<img src="https://www.prounlockphone.com/images/service-4.jpg" class="WebX-team-img" alt="Check">
						</div>
						<div class="text-center">
                                                    <h3 class="WebX-margin10"><span class="WebX-colored">Check IMEI</span> Services<br />GSX / FMI</h3>
							<div class="WebX-team-social">
								<a target="_blank" href="https://www.prounlockphone.com/quick-order/Apple Check Services/"><i class="fa fa-link WebX-icon-social"></i></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Fourt Team Member End -->

			</div><!-- Row End -->

		</div>
	</div>

	<div class="WebX-parallax2 WebX-margin60 WebX-parallax-content">
		<div class="WebX-background">
			<div class="container">

				<div class="row text-center">
					<div class="col-md-12 WebX-uppercase WebX-animate-slow WebX-animate-fade">
						<h3 class="WebX-margin40">"Never be concerned about money, confidence is way more valuable than that.
                                                    <br>Just keep something in mind, <span class="WebX-colored">either totally satisfied or fully reimbursed</span>"</h3>
						<p class="WebX-margin10">PROUNLOCKPHONE TEAM</p>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="WebX-title-bg" id="register">
		<div class="container">

			<div class="row">
				<div class="col-md-12 text-center WebX-uppercase">
					<h4 class="WebX-border-title">Still hesitating to join?</h4>
				</div>
			</div>

		</div>
	</div>

	<div class="container">

		<div class="row text-center WebX-margin20">
			<div class="col-md-12 WebX-uppercase">
				<h2 class="WebX-margin40 WebX-title">We also provide very flexible modalities depending on the volume of <span class="WebX-colored">your business</span>.</h2>
                                <p class="WebX-margin10">First, your private data are kept safe and secure and we only use them to contact you concerning your orders.<br />You should also know that you don't have to
                                    overload your account with unnecessary credits, add them on the fly.<br />If you are a reseller, you can even pay after success.</p>
			</div>
		</div>

		<div class="row text-center">
			<div class="col-md-4 col-sm-12 WebX-margin40 WebX-animate WebX-animate-bottom WebX-sequence">
				<div class="WebX-pricing-box center-block">
					<div class="WebX-pricing-title">Regular Client</div>
					<div class="WebX-pricing-price">&nbsp;$0&nbsp;</div>
					<div class="WebX-pricing-detail">No Entry Fees</div>
					<div class="WebX-pricing-detail">Access to All Services</div>
                                        <div class="WebX-pricing-detail">24 / 7 Support</div>
					<a href="https://www.prounlockphone.com/register/">
                                            <div class="WebX-pricing-button btn btn-default">Register</div>
					</a>
				</div>
			</div>
			<div class="col-md-4 col-sm-12 WebX-margin40 WebX-animate WebX-animate-bottom WebX-sequence">
				<div class="WebX-pricing-box-special center-block">
					<div class="WebX-pricing-title">Store Owner</div>
					<div class="WebX-pricing-price">&nbsp;10% or more&nbsp;</div>
					<div class="WebX-pricing-detail">Discounts on all services</div>
                                        <div class="WebX-pricing-detail">Notifications and Updates</div>
					<div class="WebX-pricing-detail">24 / 7 Personalized Support</div>
					<div class="WebX-pricing-detail">Requires Admin Approval</div>
					<a href="https://www.prounlockphone.com/register/">
                                            <div class="WebX-pricing-button btn btn-default">Register</div>
					</a>
				</div>
			</div>
			<div class="col-md-4 col-sm-12 WebX-margin40 WebX-animate WebX-animate-bottom WebX-sequence">
				<div class="WebX-pricing-box center-block">
					<div class="WebX-pricing-title">Resellers</div>
					<div class="WebX-pricing-price">Negotiable</div>
					<div class="WebX-pricing-detail">Customized Services & Pricing</div>
					<div class="WebX-pricing-detail">Flexible Payment Methods</div>
					<div class="WebX-pricing-detail">API access</div>
					<div class="WebX-pricing-detail">24 / 7 Personalized Support</div>
					<div class="WebX-pricing-detail">Requires Admin Approval</div>
					<a href="https://www.prounlockphone.com/register/">
                                            <div class="WebX-pricing-button btn btn-default">Register</div>
					</a>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 WebX-margin20 WebX-animate-slow WebX-animate-left">
				<img src="https://www.prounlockphone.com/images/factory-unlock.png" class="center-block img-responsive WebX-list-img" alt="factory unlock">
			</div>
			<div class="col-md-6 WebX-list WebX-animate-slow WebX-animate-right">
				<div class="WebX-margin30 WebX-icon-hover">
					<div class="center-block WebX-list-icon">1</div>
					<div class="WebX-list-paragraph">
						<h3 class="WebX-title">Select Service</h3>
						<p>Choose the appropriate service for your order.</p>
					</div>
				</div>
				<div class="WebX-margin30 WebX-icon-hover">
					<div class="center-block WebX-list-icon">2</div>
					<div class="WebX-list-paragraph">
						<h3 class="WebX-title">Add Details</h3>
						<p>Add the IMEI, S/N, UDID, IP, Account, etc. depending on the case.</p>
					</div>
				</div>
				<div class="WebX-margin30 WebX-icon-hover">
					<div class="center-block WebX-list-icon">3</div>
					<div class="WebX-list-paragraph">
						<h3 class="WebX-title">Pay Your Bill</h3>
						<p>No need to load extra credits, just add as much as needed.</p>
					</div>
				</div>
			</div>
		</div>

	</div>
        
        <div class="WebX-parallax3 WebX-margin60 WebX-parallax-content">
		<div class="WebX-background">
			<div class="container">

				<div class="row text-center">
					<div class="col-md-12 WebX-uppercase WebX-animate-slow WebX-animate-fade">
						<h3 class="WebX-margin40">"You can contact us in English, Spanish, French, Italian and Arabic.<br><span class="WebX-colored">We do our best to serve the best business community.</span>"
						</h3>
						<p class="WebX-margin10">PROUNLOCKPHONE TEAM</p>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="WebX-title-bg" id="payment">
		<div class="container">

			<div class="row">
				<div class="col-md-12 text-center WebX-uppercase">
					<h4 class="WebX-border-title">Payment Methods</h4>
				</div>
			</div>

		</div>
	</div>

	<div class="container">
            <div class="row WebX-margin20">
                <div class="col-md-12 text-center WebX-uppercase">
                    <h3 class="WebX-margin40 WebX-title">We accept most of the common payment means.<br />
                    You can also find our products on <span class="WebX-colored">eBay: </span><a target="_blank" href="http://www.ebay.com/usr/prounlockphone">#store1</a> <a target="_blank" href="http://www.ebay.com/usr/prounlockphone-us">#store2</a> <a target="_blank" href="http://www.ebay.com/usr/customgsm-us">#store3</a> <a target="_blank" href="http://www.ebay.com/usr/customgsm-fr">#store4</a></h3>
                    <div class="container">
                        <div class="row text-center WebX-margin20">
                            <div class="col-md-12 WebX-uppercase">
                                <div class="col-md-4 WebX-padding10-all">
                                    <div class="WebX-pricing-box WebX-padding10-all">
                                        <p class="WebX-margin10 demo-links">PAYPAL</p>
                                        <img src="https://www.prounlockphone.com/images/paypal.png" class="WebX-footer-logo" alt="PayPal">
                                    </div>
                                </div>
                                <div class="col-md-4 WebX-padding10-all">
                                    <div class="WebX-pricing-box WebX-padding10-all">
                                        <p class="WebX-margin10 demo-links">SKRILL</p>
                                        <img src="https://www.prounlockphone.com/images/skrill-picture.png" class="WebX-footer-logo" alt="Skrill">
                                    </div>
                                </div>
                                <div class="col-md-4 WebX-padding10-all">
                                    <div class="WebX-pricing-box WebX-padding10-all">
                                        <p class="WebX-margin10 demo-links">NETELLER</p>
                                        <img src="https://www.prounlockphone.com/images/neteller-picture.png" class="WebX-footer-logo" alt="Neteller">
                                    </div>
                                </div>
                                <div class="col-md-4 WebX-padding10-all">
                                    <div class="WebX-pricing-box WebX-padding10-all">
                                        <p class="WebX-margin10 demo-links">BITCOIN</p>
                                        <img src="https://www.prounlockphone.com/images/bitcoin.png" class="WebX-footer-logo" alt="PayPal">
                                    </div>
                                </div>
                                <div class="col-md-4 WebX-padding10-all">
                                    <div class="WebX-pricing-box WebX-padding10-all">
                                        <p class="WebX-margin10 demo-links">WESTERN UNION</p>
                                        <img src="https://www.prounlockphone.com/images/western-union.png" class="WebX-footer-logo" alt="Skrill">
                                    </div>
                                </div>
                                <div class="col-md-4 WebX-padding10-all">
                                    <div class="WebX-pricing-box WebX-padding10-all">
                                        <p class="WebX-margin10 demo-links">VENMO</p>
                                        <img src="https://www.prounlockphone.com/images/venmo.png" class="WebX-footer-logo" alt="Neteller">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	</div>
	

	
        <div class="WebX-parallax4 WebX-margin60 WebX-parallax-content">
		<div class="WebX-background">
			<div class="container">

				<div class="row text-center">
					<div class="col-md-12 WebX-uppercase WebX-animate-slow WebX-animate-fade">
						<h3 class="WebX-margin40">"If you have a good offer or you are direct source of any kind of service,<br>
                                                    <span class="WebX-colored">Don't think twice.</span> Hurry up and let us know to improve profits for everyone."
						</h3>
						<p class="WebX-margin10">PROUNLOCKPHONE TEAM</p>
					</div>
				</div>

			</div>
		</div>
	</div>
        
        <div class="WebX-title-bg" id="contact-us">
		<div class="container">

			<div class="row">
				<div class="col-md-12 text-center WebX-uppercase">
					<h4 class="WebX-border-title">How to get in touch with us?</h4>
				</div>
			</div>

		</div>
	</div>
        <div class="container">
            <div class="row text-center WebX-margin20">
                <div class="col-md-8 col-md-offset-2 WebX-uppercase">
                    <h2 class="WebX-margin40 WebX-title">Any thoughts? Simply <span class="WebX-colored">Contact Us</span></h2>
                </div>
            </div>
            <form id='thisForm' action="https://www.prounlockphone.com/contact/received.php" method="post" role="form">
                <span style="color:red;font-size:18px" id="msgError"></span>
                <div class="row WebX-margin40">
                    <div class="col-md-4 col-md-offset-2">
                        <div class="form-group WebX-animate WebX-animate-left WebX-sequence-slow">
                            <input id='name' name="name" type="text" class="form-control" placeholder="Your Name">
                        </div>
                        <div class="form-group WebX-animate WebX-animate-left WebX-sequence-slow">
                            <input id='guest_email' name="guest_email" type="email" class="form-control" placeholder="Your Email">
                        </div>
                        <div class="form-group WebX-animate WebX-animate-left WebX-sequence-slow">
                            <input id='subject' name="subject" type="text" class="form-control" placeholder="Subject">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group WebX-animate WebX-animate-right WebX-sequence-slow">
                            <textarea id='message' name="message" class="form-control WebX-message" placeholder="Message" rows="3"></textarea>
                        </div>
                        <button class="btn btn-default center-block WebX-submit WebX-animate-slow WebX-animate-right WebX-sequence-slow full-width">
                            Submit
                        </button>
                    </div>
                    <div class="col-md-12" align="center">
                        <div class="g-recaptcha" data-sitekey="6Lc4RREUAAAAAAE8igcAvJHtrA46STBiIVxrYcbj"></div>
                    </div>
                </div>
            </form>
	</div>
	<div class="WebX-title-bg" id="support">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center WebX-uppercase">
                        <h4 class="WebX-border-title">Need one-to-one assistance?</h4>
                    </div>
                </div>
            </div>
	</div>
	<div class="container">
            <div class="row text-center WebX-margin20">
                <div class="col-md-12 WebX-uppercase">
                    <h2 class="WebX-margin40 WebX-title">Our <span class="WebX-colored">Support</span> Team</h2>
                    <div class="col-md-4 WebX-padding10-all">
                        <div class="WebX-pricing-box WebX-padding10-all">
                            <p class="WebX-margin10 demo-links">Name: Khoubeib</p>
                            <p class="WebX-margin10 demo-links">Location: United States of America</p>
                            <p class="WebX-margin10 demo-links">E-mail: <a href="mailto:support@prounlockphone.com">support@prounlockphone.com</a></p>
                            <p class="WebX-margin10 demo-links">Skype: <a href='skype:support@prounlockphone.com?chat'>support@prounlockphone.com</a></p>
                            <p class="WebX-margin10 demo-links">Whatsapp/Viber: <a href='tel:+12104544850'>+1 (210) 454-4850</a></p>
                        </div>
                    </div>
                    <div class="col-md-4 WebX-padding10-all">
                        <div class="WebX-pricing-box WebX-padding10-all">
                            <p class="WebX-margin10 demo-links">Name: Nabil</p>
                            <p class="WebX-margin10 demo-links">Location: Tunisia</p>
                            <p class="WebX-margin10 demo-links">E-mail: <a href="mailto:nabil@prounlockphone.com">nabil@prounlockphone.com</a></p>
                            <p class="WebX-margin10 demo-links">Skype: <a href='skype:jerbigsm?chat'>jerbigsm</a></p>
                            <p class="WebX-margin10 demo-links">Whatsapp/Viber: <a href='tel:+21620645911'>+216 20 645 911</a></p>
                        </div>
                    </div>
                    <div class="col-md-4 WebX-padding10-all">
                        <div class="WebX-pricing-box WebX-padding10-all">
                            <p class="WebX-margin10 demo-links">Name: Ahmed</p>
                            <p class="WebX-margin10 demo-links">Location: France</p>
                            <p class="WebX-margin10 demo-links">E-mail: <a href="mailto:ahmed@prounlockphone.com">ahmed@prounlockphone.com</a></p>
                             <p class="WebX-margin10 demo-links">Skype: <a href='skype:wlidha73?chat'>wlidha73</a></p>
                            <p class="WebX-margin10 demo-links">Whatsapp/Viber: <a href='tel:+33605760115'>+33 6 05 76 01 15</a></p>
                        </div>
                    </div>
                </div>
            </div>
	</div>

	<div class="WebX-footer WebX-margin60">
		<div class="container">

			<div class="row text-center">
				<div class="col-md-12 WebX-uppercase WebX-margin10 WebX-animate-slow WebX-animate-fade">
					<img src="https://www.prounlockphone.com/images/pup.png" class="WebX-footer-logo" alt="PRO Unlock Phone">
				</div>
				<div class="col-md-12 WebX-margin20">
					<p class="WebX-margin10">PROUnlockPhone - Together, let's make benefits</p>
				</div>
			</div>

		</div>
	</div>

<div class="modal fade standard-modal" id="modal" tabindex="-1" role="dialog" aria-hidden="false" style="overflow: hidden; outline: none;">
	<div class="modal-dialog">
		<div class="modal-content dark-background"></div>
	</div>
</div>
<div class="modal fade standard-modal" id="largeModal" tabindex="-1" role="dialog" aria-hidden="false" style="overflow: hidden; outline: none;">
	<div class="modal-dialog large-dialog">
		<div class="modal-content dark-background"></div>
	</div>
</div>
<div class="modal fade error-modal" id="errorModal" tabindex="-1" role="dialog" aria-hidden="false" style="overflow: hidden; outline: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" class="close" data-dismiss="modal" type="button">×</button>
				<h4 class="modal-title red-text">
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
        
        <footer>
            <script src="https://www.prounlockphone.com/common/bootstrap.min.js"></script>
            <script src="https://www.prounlockphone.com/common/jquery.easing.min.js"></script>
            <script type="text/javascript" src="https://www.prounlockphone.com/common/jquery.jgrowl.min.js"></script>
            <script type="text/javascript" src="https://www.prounlockphone.com/common/jquery.form.min.js"></script>
            <script type="text/javascript" src="https://www.prounlockphone.com/common/bootstrap-select.min.js"></script>
            <script type="text/javascript" src="https://www.prounlockphone.com/common/script-index.js"></script>
            <script src="https://www.prounlockphone.com/common/loader.js"></script>
            <script src="https://www.prounlockphone.com/common/cinematic.js"></script>
            <script src="https://www.prounlockphone.com/common/is_plus_extract.js"></script>
            <script src="https://www.prounlockphone.com/common/infinitySlider.min.js"></script>
            <script src="https://www.prounlockphone.com/common/numberRoll.js"></script>
            <script src="https://www.prounlockphone.com/common/scrollSpy.js"></script>
            <script src="https://www.prounlockphone.com/common/smoothInterLink.js"></script>
            <script src="https://www.prounlockphone.com/common/jquery.nicescroll.min.js"></script>
            <script src="https://www.prounlockphone.com/common/main.js"></script>
        </footer>

        <div id="ascrail2000" class="nicescroll-rails" style="width: 5px; z-index: 99999; cursor: default; position: fixed; top: 0px; height: 100%; right: 0px; opacity: 0;">
            <div style="position: relative; top: 0px; float: right; width: 5px; height: 71px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>
        </div>
        <div id="ascrail2000-hr" class="nicescroll-rails" style="height: 5px; z-index: 99999; position: fixed; left: 0px; width: 100%; bottom: 0px; cursor: default; display: none; opacity: 0;">
            <div style="position: relative; top: 0px; height: 5px; width: 1600px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>       
        </div>
        <div id="ascrail2001" class="nicescroll-rails" style="width: 5px; z-index: 99999; cursor: default; position: fixed; top: 0px; left: 384px; height: 180px; display: none;">
            <div style="position: relative; top: 0px; float: right; width: 5px; height: 0px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>    
        </div>
        <div id="ascrail2001-hr" class="nicescroll-rails" style="height: 5px; z-index: 99999; top: 175px; left: 0px; position: fixed; cursor: default; display: none;">
            <div style="position: relative; top: 0px; height: 5px; width: 0px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>
        </div>
        <div id="ascrail2002" class="nicescroll-rails" style="width: 5px; z-index: 99999; cursor: default; position: fixed; top: 0px; left: 1595px; height: 701px; display: none;">
            <div style="position: relative; top: 0px; float: right; width: 5px; height: 0px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div> 
        </div>
        <div id="ascrail2002-hr" class="nicescroll-rails" style="height: 5px; z-index: 99999; top: 696px; left: 0px; position: fixed; cursor: default; display: none;">
            <div style="position: relative; top: 0px; height: 5px; width: 0px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>
        </div>
        <div id="ascrail2003" class="nicescroll-rails" style="width: 5px; z-index: 99999; cursor: default; position: fixed; top: 0px; left: 1595px; height: 701px; display: none;">
            <div style="position: relative; top: 0px; float: right; width: 5px; height: 0px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>
        </div>
        <div id="ascrail2003-hr" class="nicescroll-rails" style="height: 5px; z-index: 99999; top: 696px; left: 0px; position: fixed; cursor: default; display: none;">
            <div style="position: relative; top: 0px; height: 5px; width: 0px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>
        </div>
        <div id="ascrail2004" class="nicescroll-rails" style="width: 5px; z-index: 99999; cursor: default; position: fixed; top: 0px; left: 1595px; height: 701px; display: none;">
            <div style="position: relative; top: 0px; float: right; width: 5px; height: 0px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>
        </div>
        <div id="ascrail2004-hr" class="nicescroll-rails" style="height: 5px; z-index: 99999; top: 696px; left: 0px; position: fixed; cursor: default; display: none;">
            <div style="position: relative; top: 0px; height: 5px; width: 0px; background-color: rgb(0, 173, 238); border: none; background-clip: padding-box; border-radius: 0px;"></div>
        </div>
        <script language="javascript">
$(function() {
    $('#thisForm').on('submit', function() {
        if($('#name').val() == "") {
            $('#msgError').html("Name cannot be empty").css('visibility', 'visible');
            $('#name').select();
            return false;
        } else if($('#name').val().length < 2) {
            $('#msgError').html("Name must have at least 2 characters").css('visibility', 'visible');
            $('#name').select();
            return false;
        } else {
            var emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            if(!emailRegex.test($("#guest_email").val())) {
                $('#msgError').html("You must use a valid email address").css('visibility', 'visible');
                $('#guest_email').select();
                return false;
            } else if($('#subject').val() == "") {
                $('#msgError').html("Subject cannot be empty").css('visibility', 'visible');
                $('#subject').select();
                return false;
            } else if($('#subject').val().length < 6) {
                $('#msgError').html("Subject must have at least 6 characters").css('visibility', 'visible');
                $('#subject').select();
                return false;
            } else if($('#message').val() == "") {
                $('#msgError').html("Message cannot be empty").css('visibility', 'visible');
                $('#message').select();
                return false;
            } else if($('#message').val().length < 20) {
                $('#msgError').html("Message must have at least 20 characters").css('visibility', 'visible');
                $('#message').select();
                return false;
            }
        }
    });
});
    </script>
    </body>
</html>