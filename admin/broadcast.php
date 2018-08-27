<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

runCompaign($DB, "regular", "USD");
runCompaign($DB, "regular", "EUR");
runCompaign($DB, "regular", "GBP");
runCompaign($DB, "regular", "TND");
runCompaign($DB, "reseller", "USD");
runCompaign($DB, "reseller", "EUR");
runCompaign($DB, "reseller", "GBP");
runCompaign($DB, "reseller", "TND");
function runCompaign($link, $type, $currency) {
    switch($currency) {
        case "USD":
            $devise = "$";
            break;
        case "EUR":
            $devise = "&euro;";
            break;
        case "GBP":
            $devise = "&pound;";
            break;
        case "TND":
            $devise = "TND";
            break;
    }
    $services = mysqli_query($link->Link, "SELECT id, service_name, delivery_time, " . $type . "_" . $currency . " 'price' FROM services WHERE id IN (" . $_GET['services'] . ") ORDER BY service_name");
    $bulletin = "<table>";
    while($service = mysqli_fetch_assoc($services)) {
        $bulletin .= '<tr style="height:30px"><td style="width:680px;border-bottom:solid 1px black"><a href="https://www.prounlockphone.com/service/?id=' . $service['id'] . '&currency=' . $currency . '" target="_blank">' . $service['service_name'] . '</a></td><td style="width:120px;border-bottom:solid 1px black">' . $service['delivery_time'] . '</td><td style="text-align:right;width:100px;border-bottom:solid 1px black">' . $service['price'] . ' ' . $devise . '</td></tr>';
    }
    $bulletin .= "</table>";
    $clients = mysqli_query($link->Link, "SELECT id, email FROM users WHERE currency = '" . $currency . "' AND type = '" . $type . "' AND status = 'Active' AND newsletter = 1");
    $BCC = "";    
    while($client = mysqli_fetch_assoc($clients)) {
        $BCC .= $client['email'] . ",";
        $body = $link->Link->real_escape_string(renderEmail($bulletin));
        mysqli_query($link->Link, "INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert) VALUES (" . $client['id'] . ", 'eMail', 'delivered', '" . $client['email'] . "', 'Check out our new prices!!!', '" . $body . "', 'Newsletter :: New prices')");
    }
    require_once('../eMail.php');
    Compaign("🎁 Check out our new prices!!!", renderEmail($bulletin), substr($BCC, 0, -1), "Our distinguished customer");
}
function renderEmail($bulletin) {
    return '
<html xmlns="https://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;"/>
        <title>ProUnlockPhone Newsletter</title>
        <style type="text/css">
html {width: 100%;} 
body {width:100% !important;}
.ExternalClass {width:100%;}
.ExternalClass * {line-height: 100%}
@media only screen and (max-width:580px) {
*[class].h{display: none !important;}
*[class].fl{float:left !important;}
*[class].wr{display:block !important;}
*[class].w10{width:10px !important;}
*[class].w14{width:14px !important;}
*[class].w15{width:15px !important;}
*[class].w16{width:16px !important;}
*[class].w20{width:20px !important;}
*[class].w25{width:25px !important;}
*[class].w30{width:30px !important;}
*[class].w35{width:35px !important;}
*[class].w50{width:50px !important;}
*[class].w85{width:85px !important;}
*[class].w112{width:112px !important;}
*[class].w113{width:113px !important;}
*[class].w123{width:123px !important;}
*[class].w128{width:128px !important;}
*[class].w143{width:143px !important;}
*[class].w157{width:157px !important;}
*[class].w230{width:230px !important; text-align: left !important;}
*[class].w234{width:234px !important;}
*[class].w235{width:235px !important;}
*[class].w259{width:259px !important;}
*[class].w260{width:260px !important;}
*[class].w280{width:280px !important;}
*[class].w292{width:292px !important;}
*[class].w300{width:300px !important;}
*[class].w320{width:320px !important;}
*[class].h1{height:1px !important;}
*[class].h3{height:3px !important;}
*[class].h5{height:5px !important;}
*[class].h8{ height:8px !important;}
*[class].h9{height:9px !important;}
*[class].h10{ height:10px !important;}
*[class].h15{height:15px !important;}
*[class].h20{height:20px !important;}
*[class].h22{height:22px !important;}
*[class].h25{height:25px !important;}
*[class].h37{ height:37px !important;}
*[class].h120{ height:120px !important;}
*[class].f12{ font-size:12px !important; line-height:15px !important; color:#3c3c3c !important;}
*[class].f13{ font-size:13px !important; line-height:16px !important; color:#3c3c3c !important;}
*[class].f14{ font-size:14px !important; line-height:17px !important; color:#3c3c3c !important;}
*[class].f15{ font-size:15px !important; line-height:18px !important; color:#3c3c3c !important;}
*[class].f16{ font-size:16px !important; line-height:19px !important; color:#862165 !important;}
*[class].f18{ font-size:18px !important; line-height:21px !important; color:#862165 !important;}
*[class].f20{ font-size:20px !important; line-height:23px !important;}
*[class].f32{font-size:32px !important; line-height: 36px !important;}
*[class].footer{font-size:16px !important; line-height: 16px !important; color: #ffffff !important; text-decoration:none !important;}
*[class].autoh{height:auto !important;}
*[class].centered{margin: 0 auto !important; padding-left: 5px !important; padding-right: 5px !important;}
*[class].bgnone{background-image:none !important;}
*[class].bgcolournone{background-color:transparent !important;}
*[class].padlr10{padding-left:10px !important; padding-right:10px !important;}
*[class].padl20{padding-left:20px !important;}
*[class].padr20{padding-right:20px !important;}
*[class].padl30{padding-left:30px !important;}
*[class].padb16{padding-bottom:16px !important;}
*[class].text_center{ text-align:center !important;}
*[class].greybg{background-color:#FFFFFF !important;}
*[class].footerbg{background-color:#83BA3B !important;width: 320px !important; height: 166px !important;}
*[class].footercta{background: url(https://content.skrill.com/fileadmin/content/Emails_2015/Campaigns/130/Neteller/footer_cta.png) no-repeat; width: 24px !important; height: 24px !important; padding-right: 15px !important;}
*[class].footersplit{background: url(https://content.skrill.com/fileadmin/content/Emails_2015/Campaigns/130/Neteller/refresh_footsplit_new.png) no-repeat; width: 260px !important; height: 1px !important; margin: 0 auto !important; text-align: center !important;}
*[class].facebook{background: url(http://responsys.hs.llnwd.net/i5/responsysimages/content/csemeaz0/Skrill_ModTemplate_Facebook.jpg) no-repeat; width: 32px !important; height: 32px !important;}
*[class].twitter{background: url(http://responsys.hs.llnwd.net/i5/responsysimages/content/csemeaz0/Skrill_ModTemplate_Twitter.jpg) no-repeat; width: 32px !important; height: 32px !important;}
*[class].MobBanner1{background: url(http://storage.neteller.com/emails/369/hero_1_mob.jpg) no-repeat; width: 320px !important; height: 300px !important;}
*[class].logomob{background: url(https://www.skrill.com/fileadmin/content/Emails_2015/Campaigns/369/TEST_IMAGES/paysafe_logo_mob_new.png) no-repeat; width: 320px !important; height: 79px !important;}
*[class].ModSixImg{background: url(###) no-repeat; width: 290px !important; height: 281px !important;}
*[class].w100pc{width:100% !important;}
*[class].MobBanner1{background: url(https://content.skrill.com/fileadmin/content/Emails_2015/Master_Images/Neteller_Master_Template/Banner_Mob.jpg) no-repeat; width: 320px !important; height: 298px !important;}
*[class].MobBanner2{background: url(https://www.skrill.com/fileadmin/content/Emails_2015/Neteller/Master_Template/deep_baner_mob_new.png) no-repeat; width: 320px !important; height: 298px !important;}
*[class].Heroswap1{background: url(http://storage.neteller.com/emails/431/net_logo_mob.jpg) no-repeat; width: 320px !important; height: 60px !important;}
*[class].Heroswap2{background: url(https://www.skrill.com/fileadmin/content/Emails_2015/Campaigns/456/Neteller/WEEK5/week_5_hero_m.jpg) no-repeat; width: 320px !important; height: 261px !important;}
*[class].Heroswap3{background: url(https://www.skrill.com/fileadmin/content/Emails_2015/Campaigns/456/Neteller/WEEK2/week2_hero2_m.jpg) no-repeat; width: 320px !important; height: 291px !important;}
}
        </style>
    </head>
    <body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" style="padding: 0px; margin: 0px;">
        <table width="100%" style="table-layout: fixed; margin: 0 auto;" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" class="bgnone" bgcolor="#FFFFFF">
                    <table width="659" class="w320" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="659">
                                <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" class="h">
                                    <tbody>
                                        <tr>
                                            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #555555; text-align: left; line-height: 14px;"><a href="https://www.prounlockphone.com" target="_blank" style="color:blue; text-decoration:none"><span style="color:red">PRO</span>Unlock<b style="color:red">Phone</b>.com</a> newsletter, check out what\'s new</td>
                                            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #555555; text-align: right; line-height: 14px;"><a href="https://www.prounlockphone.com/login/" target="_blank" style="color:#555555; text-decoration:none">Connect to your account</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="https://www.prounlockphone.com/services/" target="_blank" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #555555; text-align: right; line-height: 14px; text-decoration:none;">Consult our services</a></td>
                                        </tr>
                                        <tr>
                                            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #555555; text-align: left; line-height: 14px;">&nbsp;</td>
                                            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #555555; text-align: right; line-height: 14px;" width="300">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div style="clear: both"></div>
                                <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" class="w320">
                                    <tr>
                                        <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:44px;line-height:50px;color:#4A5163;padding:10px 20px 0px 20px;" class="f32">Looking for a bargain!!!</td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="font-family:Georgia, \'Times New Roman\', Times, serif; font-size:30px; line-height:34px;color:#8dc640;padding:10px 20px 10px 20px; font-style:italic" class="f20">Here is a list of our last adjusted prices. Connect, place orders and make profits :)</td>
                                    </tr>
                                </table>
                                <div style="clear: both;"></div>
                                <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" class="w320">
                                    <tr>
                                        <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:18px;line-height:50px;color:#4A5163;padding:10px 20px 0px 20px;" class="f32">
                                            ' . $bulletin . '
                                        </td>
                                    </tr>
                                </table>
                                <div style="clear: both;"></div>
                                <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" class="w320">
                                    <tr>
                                        <td style="padding:20px 0px 0px 0px;">
                                            <table width="1" border="0" align="center" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td width="13"><a href="https://www.prounlockphone.com/services/" target="_blank"><img src="https://www.prounlockphone.com/images/cta_blue_l.png" width="25" height="50" style="display: block;" alt="" border="0" /></a></td>
                                                        <td bgcolor="#33A1C3" valign="middle">
                                                            <table width="1" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #fffffe; line-height: 16px; white-space: nowrap"><a href="https://www.prounlockphone.com/services/" target="_blank" style="color:#FFFFFF; text-decoration:none">Take a look at our products</a></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td width="39"><a href="https://www.prounlockphone.com/services/" target="_blank"><img src="https://prounlockphone.com/images/cta_blue_r.png" width="25" height="50" style="display: block;" alt="" border="0" /></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <div style="clear: both;"></div>
                                <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" class="w320">
                                    <tr>
                                        <td align="center" style="font-family:Georgia, \'Times New Roman\', Times, serif; font-size:30px; line-height:34px;color:#8dc640;padding:10px 20px 10px 20px; font-style:italic" class="f20">We work daily to improve our services<br />diversity ~ quality ~ rapidity ~ price<br /><br />You have bulk orders!? Let us know.<br />We will give you unbeatable prices.</td>
                                    </tr>
                                </table>
                                <div style="clear: both;"></div>
                                



                                <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" class="w320" >
                                    <tr>
                                        <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:18px;color:#888888;padding: 0px 40px 20px 40px;">If you have any issue accessing your account or with a current order,<br/>please report it so we can work on it.<br/><br/>You are a direct source or you can make us a good offer?<br/>Contact us and probably we can redirect our orders to you.</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:0 0px 0px 0px;">
                                            <table width="1" border="0" align="center" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                    <tr>
                                                        <td width="13"><a href="https://www.prounlockphone.com/services/" target="_blank"><img src="https://www.prounlockphone.com/images/cta_blue_l.png" width="25" height="50" style="display: block;" alt="" border="0" /></a></td>
                                                        <td bgcolor="#33A1C3" valign="middle">
                                                            <table width="1" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-family: Arial, Helvetica, sans-serif; font-size: 18px; color: #fffffe; line-height: 16px; white-space: nowrap"><a href="mailto:support@prounlockphone.com" target="_blank" style="color:#FFFFFF; text-decoration:none">Share your thougths with us</a></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td width="39"><a href="https://www.prounlockphone.com/services/" target="_blank"><img src="https://prounlockphone.com/images/cta_blue_r.png" width="25" height="50" style="display: block;" alt="" border="0" /></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <div style="clear: both;"></div>
                                <br /><br />
                                <table width="640" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" class="w320" >
                                    <tr>
                                        <td style="font-family:Arial, Helvetica, sans-serif; font-size:15px;line-height:18px;color:#888888;padding: 0px 40px 20px 40px;">
                                            Best Regards,<br /><b>ProUnlockPhone Team</b><br /><br />
                                            <table>
                                                <tr><td><a href="https://www.prounlockphone.com/"><img src="https://www.prounlockphone.com/images/e-pup.png" width="100px" /></a></td><td><i>Fast<br />Cheap<br />Reliable</i></td></tr>
                                            </table>
                                            <hr />
                                            <table>
                                                <tr><td><a href="mailto:support@prounlockphone.com"><img src="https://www.prounlockphone.com/images/email.png" /></a></td><td>eMail <a href="mailto:support@prounlockphone.com">support@prounlockphone.com</a></td></tr>
                                                <tr><td><a href="tel:+12104544850"><img src="https://www.prounlockphone.com/images/Whatsapp.png" /></a></td><td>Whatsapp <a href="tel:+12104544850">+1 (210) 454-4850</a></td></tr>
                                                <tr><td><a href="skype:support@prounlockphone.com?chat"><img src="https://www.prounlockphone.com/images/Skype.png" /></a></td><td>Skype <a href="skype:support@prounlockphone.com?chat">support@prounlockphone.com</a></td></tr>
                                                <tr><td><a href="tel:+12104544850"><img src="https://www.prounlockphone.com/images/Viber.png" /></a></td><td>Viber <a href="tel:+12104544850">+1 (210) 454-4850</a></td></tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center"><img src="https://prounlockphone.com/images/Facebook.png" />&nbsp;&nbsp;&nbsp;<img src="https://prounlockphone.com/images/Twitter.png" /></td>
                                    </tr>
                                </table>
                                <div style="clear: both;"></div>
                                <br/>
                                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; line-height: 13px; color: #666666; text-align: center;">
                                            <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; line-height: 13px; color:#666666; text-align: center;"><a href="https://www.prounlockphone.com/unsubscribe/?notif=newsletter" target="_blank" style="color:#8DC641; text-decoration:underline">Unsubscribe</a> | <a href="https://www.prounlockphone.com/login/" target="_blank" style="color:#8DC641; text-decoration:underline">My Account</a></a> | <a href="https://www.prounlockphone.com/support/" target="_blank" style="color:#8DC641; text-decoration:underline">Contact</a> | <a href="https://www.prounlockphone.com/services/" target="_blank" style="color:#8DC641; text-decoration:underline">Services\' Gallery</a> | <a href="mailto:support@prounlockphone.com" target="_blank" style="color:#8DC641; text-decoration:underline">Support</a></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; line-height: 13px; color: #666666; text-align: center;">
                                            &copy; ' . date('Y') . ' PROUnlockPhone Inc. Monterey CA, USA. All rights reserved.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br/><br/><br/>
    </body>
</html>';
/*
                    <hr />
                    <table>
                        <tr>
                            <td><img src="https://www.prounlockphone.com/images/Apple-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/FMI-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/Android-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/Windows-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/Samsung-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/Blackberry-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/LG-100.png" /></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.prounlockphone.com/images/Huawei-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/ZTE-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/Xperia-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/HTC-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/Nokia-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/Motorola-100.png" /></td>
                            <td><img src="https://www.prounlockphone.com/images/Sony-100.png" /></td>
                        </tr>
                    </table>
 * 
 */
}
?>