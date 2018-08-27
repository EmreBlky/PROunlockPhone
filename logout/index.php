<?php
session_name('Client_Session');
session_start();
$_SESSION['start'] = false;
session_destroy();
session_unset();
$_SESSION = array();
header("Location: /login");
?>