<?php
if(!isset($_POST['username'])) {
    header("Location: ./");
    exit();
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
check_loaded_session();
$_SESSION['start'] = true;
require '../online.php';
$DB = new DBConnection();

$_POST['username'] = strtolower(mysqli_real_escape_string($DB->Link, trim($_POST['username'])));
$_POST['password'] = mysqli_real_escape_string($DB->Link, trim($_POST['password']));
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT showAds, id, username, first_name, last_name, language, balance, currency, status, email, type, clear_pwd, last_connection FROM users WHERE (username = '".strtolower($_POST['username'])."' OR email = '".strtolower($_POST['username'])."') AND password='".md5($_POST['password'])."'"));
if($row['id']) {
    if($row['status'] == "Idle") {
        $_SESSION['start'] = false;
        header('Location: ../activate/?username=' . $_POST['username'] . '&password=' . $_POST['password']);
        exit();
    }
    if($row['clear_pwd'] == "") mysqli_query($DB->Link, "UPDATE users SET clear_pwd = \"" . $_POST['password'] . "\" WHERE id = " . $row['id']);

    $_SESSION['username'] = $row['username'];
    $_SESSION['client_id'] = $row['id'];
    $_SESSION['client_email'] = $row['email'];
    if($row['status'] == "Pending") {
        $_SESSION['start'] = false;
        $_SESSION['client_long'] = $row['first_name'] . " " . $row['last_name'];
        header("Location: https://www.prounlockphone.com/processing_profile.php");
        exit();
    } elseif($row['status'] == "Suspended") {
        $_SESSION['start'] = false;
        $_SESSION['client_long'] = $row['first_name'] . " " . $row['last_name'];
        header("Location: https://www.prounlockphone.com/suspended_account.php");
        exit();
    } else {
        $_SESSION['client_short'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        $_SESSION['client_long'] = $row['first_name'] . " " . $row['last_name'];
        $_SESSION['language'] = $row['language'];
        $_SESSION['balance'] = $row['balance'];
        $_SESSION['currency'] = $row['currency'];
        switch($_SESSION['currency']) {
            case "USD":
                $_SESSION['symbol'] = "$";
                break;
            case "EUR":
                $_SESSION['symbol'] = "&euro;";
                break;
            case "GBP":
                $_SESSION['symbol'] = "&pound;";
                break;
            case "TND":
                $_SESSION['symbol'] = "DT";
                break;
        }
        $_SESSION['client_type'] = $row['type'];
        $_SESSION['showAds'] = $row['showAds'];

        mysqli_query($DB->Link, "UPDATE users SET last_connection = '" . gmdate("Y-m-d H:i:s") . "' WHERE id='{$_SESSION['client_id']}'");
        if($_POST['urlx'] != "") {
            header("Location: .." . $_POST['urlx']);
        } elseif($_POST['url'] != "") {
            if($_POST['param'] != "") {
                header("Location: ../{$_POST['url']}/?param=" . $_POST['param']);
            } else {
                header("Location: ../{$_POST['url']}/");
            }
        } elseif($_SESSION['client_type'] == "admin") {
            header("Location: ../admin/superorders.php?status=Pending&status2=In%20process");
        } else {
            header("Location: ../main/");
        }
    }
} else {
    $_SESSION['start'] = false;
    header("Location: https://www.prounlockphone.com/");
}
?>