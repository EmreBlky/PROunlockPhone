<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT type, typeAlert, instant, subject, destination, status, content FROM notifications WHERE id = " . $_GET['id']));
switch($row['status']) {
    case "delivered":
        $color = "green";
        break;
    case "sent":
        $color = "blue";
        break;
    case "queued":
        $color = "orange";
        break;
    case "undelivered":
        $color = "#222";
        break;
}
?>
<div class='modal-header'>
    <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>×</button>
    <h4 class='modal-title'><?php echo $row['type'] ?> notification</h4>
</div>
<div class='modal-body'>
    <p class="info-row">Category: <b><?php echo $row['typeAlert'] ?></b></p>
    <hr>
    <p class="info-row">Date: <b><?php echo $row['instant'] ?> GMT</b></p>
    <p class="info-row">Subject: <b><?php echo $row['subject'] ?></b></p>
    <p class="info-row">Destination:  <b><?php echo $row['destination'] ?></b></p>
    <p class="info-row">Status:  <b style="color:<?php echo $color ?>"><?php echo $row['status'] ?></b></p>
    <hr>
    <?php echo $row['content'] ?>
<div class='modal-footer'>
    <button class='btn btn-default' data-dismiss='modal' type='button'>Close</button>
</div>