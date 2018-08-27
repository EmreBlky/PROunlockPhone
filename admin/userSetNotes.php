<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

mysqli_query($DB->Link, "UPDATE users SET notes = \"" . mysqli_real_escape_string($DB->Link, $_GET['notes']) . "\" WHERE id = \"" . $_GET['user'] . "\"");
?>