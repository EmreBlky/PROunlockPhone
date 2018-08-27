<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}
$current = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT client, price FROM orders WHERE id = {$_POST['id']}"));

mysqli_query($DB->Link, "UPDATE orders SET price = {$_POST['price']} WHERE id = {$_POST['id']}");

mysqli_query($DB->Link, "UPDATE statement SET debit = {$_POST['price']} WHERE order_id = {$_POST['id']} AND debit <> 0");
mysqli_query($DB->Link, "UPDATE statement SET credit = {$_POST['price']} WHERE order_id = {$_POST['id']} AND credit <> 0");

$rows = mysqli_query($DB->Link, "SELECT id, credit, debit FROM statement WHERE client = {$current['client']} ORDER BY id");
$balance = 0;
while($row = mysqli_fetch_array($rows)) {
    $balance = $balance + $row['credit'] - $row['debit'];
    mysqli_query($DB->Link, "UPDATE statement SET balance_after = {$balance} WHERE id = {$row['id']}");
}

mysqli_query($DB->Link, "UPDATE users SET balance = {$balance} WHERE id = {$current['client']}");

echo "Update complete";
?>