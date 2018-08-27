<?php
session_name('Client_Session');
session_start();
if(isset($_GET['currency'])) {
    $currencies = array('USD', 'EUR', 'GBP', 'TND');
    if(in_array(strtoupper($_GET['currency']), $currencies)) {
        $_SESSION['currency'] = strtoupper($_GET['currency']);
        if($_SESSION['currency'] == 'GBP') {
            $_SESSION['symbol'] = '&pound;';
        } elseif($_SESSION['currency'] == 'EUR') {
            $_SESSION['symbol'] = "&euro;";
        } elseif($_SESSION['currency'] == 'TND') {
            $_SESSION['symbol'] = "DT";
        } else {
            $_SESSION['symbol'] = "$";
        }
        header("Location: https://" . $_SERVER['HTTP_HOST'] . urldecode($_GET['url']));
    } else {
        die('Currency not supported!');
    }
} else die('You are not allowed to execute this file directly!');
?>