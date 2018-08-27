<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

mysqli_query($DB->Link, "UPDATE users SET requestMoneyBack = 0 WHERE id = " . $_GET['id']);
?>
<script language='javascript'>window.close()</script>