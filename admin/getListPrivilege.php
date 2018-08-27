<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

$req = mysqli_query($DB->Link , "SELECT price_client_service.id 'globID', username, first_name, last_name, currency, nature, price, last_update
FROM users, price_client_service
WHERE service = " . $_GET['id'] . "
AND client = users.id
ORDER BY last_update DESC");
echo '<tr>
        <th style="padding:10px;text-align:center;width:20%">Username</th>
        <th style="padding:10px;text-align:center;width:30%">Name</th>
        <th style="padding:10px;text-align:center;width:10%">Price</th>
        <th style="padding:10px;text-align:center;width:10%">Currency</th>
        <th style="padding:10px;text-align:center;width:10%">Type</th>
        <th style="padding:10px;text-align:center;width:20%">Last Update</th>
    </tr>';
while($row = mysqli_fetch_assoc($req)) {
    echo "<tr>"
    . "<td style='padding:5px 0px 5px 10px'><img alt='delete' onclick='revoke(" . $row['globID'] . ", this)' src='https://www.prounlockphone.com/images/delete.png' style='cursor:pointer;margin-right:10px' />" . $row['username'] . "</td>"
    . "<td style='padding-left:10px'>" . $row['first_name'] . " " . $row['last_name'] . "</td>"
    . "<td style='text-align:center'>" . number_format($row['price'], 2, ".", ",") . "</td>"
    . "<td style='text-align:center'>" . $row['currency'] . "</td>"
    . "<td style='text-align:center'>" . strtoupper($row['nature']) . "</td>"
    . "<td style='text-align:center'>" . $row['last_update'] . "</td>"
    . "</tr>";
}
?>