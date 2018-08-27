<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT description, service_name, details FROM services WHERE id = " . $_GET['service']));

?>

<div class='modal-header'>
    <button aria-hidden='true' class='close' data-dismiss='modal'>×</button>
    <h4 class='modal-title'><?php echo $row['service_name'] ?></h4>
</div>
<div class='modal-body'>
    <label>Description</label>
    <p><?php echo $row['description'] ?></p>
    <hr />
    <label>Sample</label>
    <p><?php echo $row['details'] ?></p>
    <div class='modal-footer'>
        <button class='btn btn-default' data-dismiss='modal'>Close</button>
        <a class='btn btn-success' href='https://www.prounlockphone.com/service/<?php echo str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $row['service_name']))) ?>/'>Order Service</a>
    </div>
</div>