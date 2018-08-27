<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin" or !isset($_POST['id'])) {
    header("Location: ../login");
    exit();
}

try {
    mysqli_query($DB->Link, "UPDATE orders SET admin_response_comments = '" . mysqli_real_escape_string($DB->Link, (isset($_POST['comments']) ? $_POST['comments'] : "")) . "' WHERE id = " . $_POST['id']);
} catch (Exception $e) {
    echo "FAILURE" . $e->getMessage(); 
}
?>