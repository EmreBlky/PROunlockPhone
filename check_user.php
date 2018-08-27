<?php
define('INCLUDE_CHECK', true);
require 'common.php';
$DB = new DBConnection();

if(isset($_POST['identifier'])) {
    echo mysqli_num_rows(mysqli_query($DB->Link, 'SELECT id FROM users WHERE status = "Active" AND (username = "' . strtolower($_POST['identifier']) . '" OR email = "' . strtolower($_POST['identifier']) . '")'));
} elseif(isset($_POST['password'])) {
    $rows = mysqli_query($DB->Link, 'SELECT id, status FROM users WHERE (username = "' . strtolower($_POST['username']) . '" OR email = "' . strtolower($_POST['username']) . '") AND password = "' . md5($_POST['password']) . '"');
    $nbr = mysqli_num_rows($rows);
    if($nbr == 0) echo "0";
    else {
        $row = mysqli_fetch_assoc($rows);
        if($row['status'] == 'Suspended') echo 'KO';
    }
} elseif(isset($_GET['username'])) {
    echo mysqli_num_rows(mysqli_query($DB->Link, 'SELECT id FROM users WHERE username = "' . strtolower($_GET['username']) . '" OR email = "' . strtolower($_GET['username']) . '"'));
} elseif(isset($_GET['email'])) {
    echo mysqli_num_rows(mysqli_query($DB->Link, 'SELECT id FROM users WHERE email = "' . strtolower($_GET['email']) . '"'));
}
?>