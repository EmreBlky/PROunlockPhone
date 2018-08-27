<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$query = "INSERT INTO price_client_service (service, client, price, nature)
          VALUES (
            \"" . $_POST['service'] . "\",
            \"" . $_POST['client'] . "\",
            \"" . $_POST['price'] . "\",
            \"" . $_POST['nature'] . "\"
        )";
mysqli_query($DB->Link, $query);
echo "<script type='text/javascript'>top.location = 'user_frame.php?id=" . $_POST['client'] . "';</script>";
?>