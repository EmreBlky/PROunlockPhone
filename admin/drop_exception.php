<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$query = "DELETE FROM price_client_service WHERE id = " . $_GET['id'];
mysqli_query($DB->Link, $query);
if(isset($_GET['client'])) {
    echo "<script type='text/javascript'>top.location = 'user_frame.php?id=" . $_GET['client'] . "';</script>";
}
?>