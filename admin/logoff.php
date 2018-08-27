<?php
session_name('Client_Session');
session_start();
$_SESSION['start'] = false;
$_SESSION = array();
session_destroy();
session_unset();
header("Location: https://www.prounlockphone.com/login/");
?>