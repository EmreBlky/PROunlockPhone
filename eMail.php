<?php
if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function Notify_client($subject, $body, $dest, $first_name, $client, $order, $sender = "support", $bcc = 0) {
    $body .= '<br /><br />
        Best Regards,<br /><b>PROunlockPhone Team</b><br /><br />
        Feel free to leave your feedback at <a href="https://www.trustpilot.com/evaluate/prounlockphone.com">TrustPilot</a>.
        <table>
            <tr><td><a href="https://www.prounlockphone.com/"><img src="https://www.prounlockphone.com/images/e-pup.png" width="100px" /></a></td><td><i>Fast<br />Cheap<br />Reliable</i></td></tr>
        </table>
        <hr />
        <table>
            <tr><td><a href="mailto:support@prounlockphone.com"><img src="https://www.prounlockphone.com/images/email.png" /></a></td><td>eMail <a href="mailto:support@prounlockphone.com">support@prounlockphone.com</a></td></tr>
            <tr><td><a href="tel:+12104544850"><img src="https://www.prounlockphone.com/images/Whatsapp.png" /></a></td><td>Whatsapp <a href="tel:+12104544850">+1 (210) 454-4850</a></td></tr>
            <tr><td><a href="skype:support@prounlockphone.com?chat"><img src="https://www.prounlockphone.com/images/Skype.png" /></a></td><td>Skype <a href="skype:support@prounlockphone.com?chat">support@prounlockphone.com</a></td></tr>
            <tr><td><a href="tel:+12104544850"><img src="https://www.prounlockphone.com/images/Viber.png" /></a></td><td>Viber <a href="tel:+12104544850">+1 (210) 454-4850</a></td></tr>
            <tr><td><a href="https://www.m.me/prounlockphone"><img src="https://www.prounlockphone.com/images/messenger.png" /></a></td><td>Messenger <a href="https://www.m.me/prounlockphone">@prounlockphone</a></td></tr>
        </table>
    </body>
</html>';

//    require_once('PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->CharSet='UTF-8';
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Port = 80;
    $mail->SMTPDebug = 0;
    $mail->Host = "smtpout.secureserver.net";
    $mail->Username = "support@prounlockphone.com";
    $mail->Password = "T1mePa$$";
    $mail->AddReplyTo("support@prounlockphone.com", 'PROunlockPhone');
    $mail->SetFrom('support@prounlockphone.com', 'PROunlockPhone');
    $mail->From = "support@prounlockphone.com";
    $mail->FromName = "PROunlockPhone";
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = "PROunlockPhone Server";
    $mail->WordWrap = 50;
    $mail->MsgHTML($body);
    $mail->AddAddress($dest, $first_name);
    $mail->IsHTML(true);
    $mail->Priority = null;

    if($bcc == 1 or $bcc == 3) $mail->addBcc("support@prounlockphone.com");
    if($bcc == 2 or $bcc == 3) $mail->addBcc("782c10f0ab@invite.trustpilot.com");

    $result = $mail->Send();
    if($result) {
//        $stream = imap_open("{pop.secureserver.net:995/pop3/ssl/novalidate-cert}INBOX.Sent", "support@prounlockphone.com", "T1mePa$$");
//        imap_append($stream, "{pop.secureserver.net:995/pop3/ssl/novalidate-cert}INBOX.Sent", $mail->getSentMIMEMessage(), "\\Seen");
//        imap_close($stream);
        $DB = new DBConnection();
        $body = $DB->Link->real_escape_string($body);
//        mysqli_query($DB->Link, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
        $DB->Link->set_charset("utf8mb4");
        if($client != 134) mysqli_query($DB->Link, 'INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert) VALUES (' . $client . ', "eMail", "delivered", "' . $dest . '", "' . mysqli_real_escape_string($DB->Link, $subject) . '", "' . $body . '", "' . $order . '")');
    } else {
        $mail->Username = "do-not-reply@prounlockphone.com";
        $mail->Password = "T1mePa$$";
        $mail->ClearReplyTos();
        $mail->AddReplyTo("support@prounlockphone.com", 'PROunlockPhone');
        $mail->SetFrom('do-not-reply@prounlockphone.com', 'PROunlockPhone');
        $mail->From = "do-not-reply@prounlockphone.com";
        $result = $mail->Send();
        if($result) {
            $stream = imap_open("{pop.secureserver.net:995/pop3/ssl/novalidate-cert}INBOX.Sent", "support@prounlockphone.com", "T1mePa$$");
            imap_append($stream, "{pop.secureserver.net:995/pop3/ssl/novalidate-cert}INBOX.Sent", $mail->getSentMIMEMessage(), "\\Seen");
            imap_close($stream);
            $DB = new DBConnection();
            $body = $DB->Link->real_escape_string($body);
//        mysqli_query($DB->Link, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
            $DB->Link->set_charset("utf8mb4");
            if ($client != 134) mysqli_query($DB->Link, 'INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert) VALUES (' . $client . ', "eMail", "delivered", "' . $dest . '", "' . $subject . '", "' . $body . '", "' . $order . '")');
        }
    }
    return $result;
}


function Notify_me($subject, $body, $replyTo = "", $replied = "") {
//    require_once('PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->CharSet='UTF-8';
    $mail->AddCustomHeader("Importance: High");
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Port = 80;
    $mail->SMTPDebug = 0;
    $mail->Host = "smtpout.secureserver.net";
    $mail->Username = "no-reply@prounlockphone.com";
    $mail->Password = "T1mePa$$";
    $mail->SetFrom('no-reply@prounlockphone.com', 'PROunlockPhone');
    $mail->From = "no-reply@prounlockphone.com";
    $mail->FromName = "PROunlockPhone";
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = "PROunlockPhone Server";
    $mail->WordWrap = 50;
    $mail->MsgHTML($body);
    $mail->AddAddress("support@prounlockphone.com", "PROunlockPhone");
    $mail->IsHTML(true);
    $mail->Priority = 2;
    if($replyTo != "") {
        $mail->ClearReplyTos();
        $mail->AddReplyTo($replyTo, $replied);
    }
    $result = $mail->Send();
//    if($result) {
//        $stream = imap_open("{pop.secureserver.net:995/pop3/ssl/novalidate-cert}INBOX.Sent", "no-reply@prounlockphone.com", "T1mePa$$");
//        imap_append($stream, "{pop.secureserver.net:995/pop3/ssl/novalidate-cert}INBOX.Sent", $mail->getSentMIMEMessage(), "\\Seen");
//        imap_close($stream);
//    }
    return $result;
}
/*
function Notify_me($subject, $body) {
    $url = "http://www.icloud-assist.com/notify_client.php";
    
    $fields = array(
        'subject' => urlencode($subject),
        'body' => urlencode($body),
        'dest' => urlencode($dest),
        'name' => urlencode("Custom GSM")
    );
    foreach($fields as $key=>$value) {
        $fields_string .= $key.'='.$value.'&';
    }
    rtrim($fields_string, '&');
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    $res = curl_exec($ch);
    curl_close($ch);
}*/

function Notify_me_without_relay($subject, $body, $replyTo = "", $replied = "") {
//    require_once('PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->CharSet='UTF-8';
    $mail->AddCustomHeader("Importance: High");
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Port = 587;
    $mail->SMTPDebug = 0;
    $mail->Host = "mail.prounlockphone.com";
    //$mail->SMTPSecure = 'tls';
    $mail->Username = "internal@prounlockphone.com";
    $mail->Password = "T1mePa$$";
    $mail->AddReplyTo("internal@prounlockphone.com", 'PROunlockPhone');
    $mail->SetFrom('internal@prounlockphone.com', 'PROunlockPhone');
    $mail->From = "internal@prounlockphone.com";
    $mail->FromName = "PROunlockPhone";
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = "Notification from PROunlockPhone Server";
    $mail->WordWrap = 50;
    $mail->MsgHTML($body);
    $mail->AddAddress("support@prounlockphone.com", "PROunlockPhone");
    $mail->IsHTML(true);
    $mail->Priority = 2;
    if($replyTo != "") {
        $mail->ClearReplyTos();
        $mail->AddReplyTo($replyTo, $replied);
    }
    $result = $mail->Send();
    if($result) {
        $stream = imap_open("{mail.prounlockphone.com:143/novalidate-cert}INBOX.Sent", "support@prounlockphone.com", "T1mePa$$");
        imap_append($stream, "{mail.prounlockphone.com:143/novalidate-cert}INBOX.Sent", $mail->getSentMIMEMessage(), "\\Seen");
        imap_close($stream);
    }
    return $result;
}

function Notify_client_without_relay($subject, $body, $dest, $first_name, $client, $order, $sender = "support", $bcc = 0) {
    $body .= '<br /><br />
        Best Regards,<br /><b>PROunlockPhone Team</b><br /><br />
        Feel free to leave your feedback at <a href="https://www.trustpilot.com/evaluate/prounlockphone.com">TrustPilot</a>.
        <table>
            <tr><td><a href="https://www.prounlockphone.com/"><img src="https://www.prounlockphone.com/images/e-pup.png" width="100px" /></a></td><td><i>Fast<br />Cheap<br />Reliable</i></td></tr>
        </table>
        <hr />
        <table>
            <tr><td><a href="mailto:support@prounlockphone.com"><img src="https://www.prounlockphone.com/images/email.png" /></a></td><td>eMail <a href="mailto:support@prounlockphone.com">support@prounlockphone.com</a></td></tr>
            <tr><td><a href="tel:+12104544850"><img src="https://www.prounlockphone.com/images/Whatsapp.png" /></a></td><td>Whatsapp <a href="tel:+12104544850">+1 (210) 454-4850</a></td></tr>
            <tr><td><a href="skype:support@prounlockphone.com?chat"><img src="https://www.prounlockphone.com/images/Skype.png" /></a></td><td>Skype <a href="skype:support@prounlockphone.com?chat">support@prounlockphone.com</a></td></tr>
            <tr><td><a href="tel:+12104544850"><img src="https://www.prounlockphone.com/images/Viber.png" /></a></td><td>Viber <a href="tel:+12104544850">+1 (210) 454-4850</a></td></tr>
            <tr><td><a href="https://www.m.me/prounlockphone"><img src="https://www.prounlockphone.com/images/messenger.png" /></a></td><td>Messenger <a href="https://www.m.me/prounlockphone">@prounlockphone</a></td></tr>
        </table>
    </body>
</html>';
    
    //$dest = "Rq3kdXSVcw9I6T@dkimvalidator.com";

//    require_once('PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->CharSet='UTF-8';
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Port = 587;
    $mail->SMTPDebug = 0;
    $mail->Host = "mail.prounlockphone.com";
    //$mail->SMTPSecure = 'tls';
    $mail->Username = $sender . "@prounlockphone.com";
    $mail->Password = "T1mePa$$";
    $mail->AddReplyTo("support@prounlockphone.com", 'PROunlockPhone');
    $mail->SetFrom($sender . '@prounlockphone.com', 'PROunlockPhone');
    $mail->From = $sender . "@prounlockphone.com";
    $mail->FromName = "PROunlockPhone";
    
/*    $mail->DKIM_domain = 'prounlockphone.com';
    $mail->DKIM_private = 'email.key';
    $mail->DKIM_selector = 'key1';
    $mail->DKIM_passphrase = '';
    $mail->DKIM_identity = $mail->From;*/
    
    $mail->DKIM_domain = 'prounlockphone.com';
    $mail->DKIM_private = './.htkeyprivate';
    $mail->DKIM_selector = 'default';
    $mail->DKIM_passphrase = '';
    $mail->DKIM_identity = $mail->From;
    
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = "Notification from PROunlockPhone Server";
    $mail->WordWrap = 50;
    $mail->MsgHTML($body);
    $mail->AddAddress($dest, $first_name);
    $mail->IsHTML(true);
    $mail->Priority = null;

    if($bcc == 1 or $bcc == 3) $mail->addBcc("support@prounlockphone.com");
    if($bcc == 2 or $bcc == 3) $mail->addBcc("782c10f0ab@invite.trustpilot.com");

    
    $result = $mail->Send();
    if($result) {
        $stream = imap_open("{mail.prounlockphone.com:143/novalidate-cert}INBOX.Sent", "support@prounlockphone.com", "T1mePa$$");
        imap_append($stream, "{mail.prounlockphone.com:143/novalidate-cert}INBOX.Sent", $mail->getSentMIMEMessage(), "\\Seen");
        imap_close($stream);
        $DB = new DBConnection();
        $body = $DB->Link->real_escape_string($body);
//        mysqli_query($DB->Link, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
        $DB->Link->set_charset("utf8mb4");
        if($client != 134) mysqli_query($DB->Link, 'INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert) VALUES (' . $client . ', "eMail", "delivered", "' . $dest . '", "' . $subject . '", "' . $body . '", "' . $order . '")');
    }
    return $result;
    
/*
    $url = "http://www.icloud-assist.com/notify_client.php";
    
    $fields = array(
        'subject' => urlencode($subject),
        'body' => urlencode($body),
        'dest' => urlencode($dest),
        'name' => urlencode($first_name)
    );
    foreach($fields as $key=>$value) {
        $fields_string .= $key.'='.$value.'&';
    }
    rtrim($fields_string, '&');
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    $res = curl_exec($ch);
    curl_close($ch);
    echo $res;
    $DB = new DBConnection();
    $body = $DB->Link->real_escape_string($body);
    if($client != 134) mysqli_query($DB->Link, 'INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert) VALUES (' . $client . ', "eMail", "delivered", "' . $dest . '", "' . $subject . '", "' . $body . '", "' . $order . '")');
    //echo 'INSERT INTO notifications (user, type, status, destination, subject, content, typeAlert) VALUES (' . $client . ', "eMail", "Delivered", "' . $dest . '", "' . $subject . '", "' . $body . '", "' . $order . '")';
 * 
 */
    
}

function Compaign($subject, $body, $dest, $name) {
    //$sender = "sales";
//    include_once('PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->CharSet='UTF-8';
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Port = 587;
    $mail->SMTPDebug = 0;
    $mail->Host = "mail.prounlockphone.com";
    //$mail->SMTPSecure = 'tls';
    $mail->Username = "sales@prounlockphone.com";
    $mail->Password = "T1mePa$$";
    $mail->AddReplyTo("support@prounlockphone.com", 'PROunlockPhone');
    $mail->SetFrom('sales@prounlockphone.com', 'PROunlockPhone');
    $mail->From = "sales@prounlockphone.com";
    $mail->FromName = "PROunlockPhone";
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = "Thank you for your business";
    $mail->WordWrap = 50;
    $mail->MsgHTML($body);
    $mail->AddAddress("support@prounlockphone.com", "PROunlockPhone");
    $BCC = split(",", $dest);
    foreach($BCC as $destBCC) {
        $mail->AddBCC($destBCC, $name);
    }
    $mail->IsHTML(true);
    $mail->Priority = null;
    $result = $mail->Send();
    if($result) {
        $stream = imap_open("{mail.prounlockphone.com:143/novalidate-cert}INBOX.Sent", "support@prounlockphone.com", "T1mePa$$");
        imap_append($stream, "{mail.prounlockphone.com:143/novalidate-cert}INBOX.Sent", $mail->getSentMIMEMessage(), "\\Seen");
    }
    return $result;
}
?>