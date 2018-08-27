<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

?>
<html>
<head>
    <?php echo admin_common_head_with_title("Show Photo") ?>
</head>
<body>
<?php
require_once('superheader.php');
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT IMEI, SN FROM orders WHERE id = " . $_GET['order']));
$path = $row['IMEI'] != "" ? $row['IMEI'] : $row['SN'];
echo "  <img style='border:solid 1px gray' height='600px' src='images/Uploaded/" . $path . ".jpg' />\n";
?>
</body>
</html>