<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT backupData, videoLink, fileLink, backupLink, backupPwd, label, relative_id, orders.IMEI 'imei', orders.SN 'sn', services.id 'serviceID', service_name, orders.status 'statut', order_date, price, last_update, orders.udid 'UDID', clear_email, orders.phone 'Phone', client_personal_notes, client_order_comments FROM orders, services WHERE services.id = service AND orders.id = " . $_GET['id']));
switch($row['statut']) {
    case "Pending":
        $color = 'orange';
        break;
    case "In process":
        $color = 'blue';
        break;
    case "Success":
        $color = '#01BC8C;';
        break;
    case "Canceled":
        $color = 'black';
        break;
    case "Rejected":
        $color = '#EF6F6C;';
        break;
}
?>
<div class='modal-header'>
    <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>×</button>
    <h4 class='modal-title'>Order <?php echo $row['relative_id'] ?></h4>
</div>
<div class='modal-body'>
    <p class="info-row">Service: <b>#<?php echo $row['serviceID'] . ' ' . $row['service_name'] ?></b></p>
    <hr>
    <p class="info-row"><?php
    if($row['imei'] != "") {
        echo 'IMEI: <b>' . $row['imei'] . '</b>';
    } else {
        echo 'S/N: <b>' . $row['sn'] . '</b>';
    }
    if($row['label'] != '') {
        echo '&nbsp;&nbsp;&nbsp;&nbsp;<< ' . $row['label'] . ' >>';
    }
    ?></p>
    <hr>
    <p class="info-row">Price: <b><?php echo $row['price'] . " {$_SESSION['symbol']}" ?></b></p>
    <p class="info-row">Order Date:  <b><?php echo $row['order_date'] ?> GMT</b></p>
    <p class="info-row">Last Update:  <b><?php
            date_default_timezone_set('America/Denver');
            $last_update = new DateTime($row['last_update']);
            $london_time = new DateTimeZone('Europe/London');
            $last_update->setTimezone($london_time);
            echo $last_update->format('Y-m-d H:i:s');
            ?> GMT</b></p>
    <hr>
    <p class="info-row">Status: <b style="color:<?php echo $color ?>"><?php echo $row['statut'] ?></b></p>
    <?php
if($row['backupData'] == "1") {
    echo '<hr><p class="info-row">Backup Link: <b>' . $row['backupLink'] . '</b></p>';
    echo '<p class="info-row">Backup Password: <b>' . $row['backupPwd'] . '</b></p>';
}
if($row['videoLink'] == "1") {
    echo '<hr><p class="info-row">Video Link: <b>' . $row['backupLink'] . '</b></p>';
}
if($row['fileLink'] == "1") {
    echo '<hr><p class="info-row">File Link: <b>' . $row['backupLink'] . '</b></p>';
}
if($row['UDID'] != "") {
    echo '<hr><p class="info-row">UDID: <b>' . $row['UDID'] . '</b></p>';
}
if($row['clear_email'] != "") {
    echo '<hr><p class="info-row">Apple ID: <b>' . $row['clear_email'] . '</b></p>';
}
if($row['Phone'] != "") {
    echo '<hr><p class="info-row">Associated Phone: <b>' . $row['Phone'] . '</b></p>';
}
    ?>
    <?php
if($row['client_personal_notes'] != "") {
    echo '
    <hr><p class="info-row">Personal notes:</p>
    <div class="info-content">' . nl2br($row['client_personal_notes']) . '</div>';
}
if($row['client_order_comments'] != "") {
    echo '
    <hr><p class="info-row">Note left to admin:</p>
    <div class="info-content">' . nl2br($row['client_order_comments']) . '</div>';
}
    ?>
</div>
<div class='modal-footer'>
    <button class='btn btn-default' data-dismiss='modal' type='button'>Close</button>
</div>