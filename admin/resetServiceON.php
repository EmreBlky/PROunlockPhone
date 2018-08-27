<?php
if(!isset($_GET['id'])) die("You are not allowed to run this file externally");
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/online.php';
$DB = new DBConnection();

mysqli_query($DB->Link, "UPDATE services SET service_status = 1 WHERE id = " . $_GET['id']);
?>
<script language='javascript'>window.close()</script>