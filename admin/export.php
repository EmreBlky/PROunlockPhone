<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$query = "SELECT IMEI, SN
FROM " . (isset($_GET['quick']) && $_GET['quick'] != "" ? "quick_" : "") . "orders WHERE 1"     . (isset($_GET['client']) && $_GET['client'] != "" ? " AND client='{$_GET['client']}'" : "");
if(isset($_GET['service']) and $_GET['service'] != "") $query .= " AND service = " . $_GET['service'];
if(isset($_GET['status2']) and $_GET['status2'] != "") {
    $query .= " AND (" . (isset($_GET['quick']) && $_GET['quick'] != "" ? "quick_" : "") . "orders.status = '" . $_GET['status'] . "' OR " . (isset($_GET['quick']) && $_GET['quick'] != "" ? "quick_" : "") . "orders.status = '" . $_GET['status2'] . "')";
} elseif(isset($_GET['status']) and $_GET['status'] == "PinToTop") {
    $query .= " AND pintotop = 1";
} elseif(isset($_GET['status']) and $_GET['status'] != "") $query .= " AND (" . (isset($_GET['quick']) && $_GET['quick'] != "" ? "quick_" : "") . "orders.status = '" . $_GET['status'] . "')";
$query .= " ORDER BY order_date DESC";
if(isset($_GET['limit']) and $_GET['limit'] != "") $query .= " LIMIT " . $_GET['limit'];
$rows = mysqli_query($DB->Link, $query);
while($row = mysqli_fetch_assoc($rows)) {
    echo ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . "<br />";
}
echo "<hr />Total orders: " . mysqli_num_rows($rows);
?>