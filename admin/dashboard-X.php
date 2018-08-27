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
    <?php echo admin_common_head_with_title("Users") ?>
    <style>
th {
    text-align: center;
    padding: 5px;
}
td {
    padding: 2px 5px 2px 5px;
}
    </style>
</head>
<body>
<?php
require_once('superheader.php');
$query = "
SELECT id, username, first_name, last_name, balance, currency, email
FROM users
WHERE currency = '{$_GET['curr']}'
    AND status = 'Active'
    AND id NOT IN (134, 58)
ORDER BY
    first_name, last_name, username";
$rows = mysqli_query($DB->Link, $query);

$balance = mysqli_fetch_assoc(mysqli_query($DB->Link, "
SELECT SUM(balance) 'bal'
FROM users
WHERE currency = '{$_GET['curr']}'
    AND status = 'Active'
    AND id NOT IN (134, 58)"));
$locked = mysqli_fetch_assoc(mysqli_query($DB->Link, "
SELECT SUM(price) 'lock' 
FROM users, orders 
WHERE
    currency = '{$_GET['curr']}'
    AND users.status = 'Active'
    AND users.id NOT IN (134, 58)
    AND orders.client = users.id
    AND (orders.status = 'Pending' OR orders.status = 'In process')"));
?>

    <h2 style="color:gray;margin-top:60px;margin-left:50px">Clients management:</h2>
    <table style="font-size:100%;width:80%" class="main_table" align="center" border="solid 1px #CCC">
        <tr>
            <th style="width:40%">Client</th>
            <th style="width:15%">Balance</th>
            <th style="width:15%">Locked amount</th>
            <th style="width:15%">Credits</th>
            <th style="width:15%">Actual debt</th>
        </tr>
        <tr>
            <td align="right"><b>Total</b></td>
            <td align='right'><?php echo number_format($balance['bal'], 2, ",", ".") . " " . $_GET['curr'] ?></td>
            <td align='right'><?php echo number_format($locked['lock'], 2, ",", ".") . " " . $_GET['curr'] ?></td>
            <td align='right'><?php echo number_format(max($balance['bal'] + $locked['lock'], 0), 2, ",", ".") . " " . $_GET['curr'] ?></td>
            <td align='right'><?php echo number_format(min($balance['bal'] + $locked['lock'], 0), 2, ",", ".") . " " . $_GET['curr'] ?></td>
        </tr>
        <?php
while($row = mysqli_fetch_array($rows)) {
    $query = "
SELECT SUM(price) 'locked' 
FROM orders 
WHERE
    client = " . $row['id'] . " 
    AND (orders.status = 'Pending' OR orders.status = 'In process')";
    $req = mysqli_fetch_assoc(mysqli_query($DB->Link, $query));
    if($row['balance'] != 0 || $req['locked'] != 0) {
        echo "<tr style='background-color:" . couleur($row['balance'] + $req['locked']) . "'>
            <td>" . $row['first_name'] . " " . $row['last_name'] . " (<a href='statement.php?client=" . $row['id'] . "'>" . $row['username'] . "</a>) [" . $row['email'] . "]</td>
            <td align='right'>" . number_format($row['balance'], 2, ",", ".") . " " . $row['currency'] . "</td>
            <td align='right'>" . number_format($req['locked'], 2, ",", ".") . " " . $row['currency'] . "</td>
            <td align='right'>" . number_format(max($row['balance'] + $req['locked'], 0), 2, ",", ".") . " " . $row['currency'] . "</td>
            <td align='right'>" . number_format(min($row['balance'] + $req['locked'], 0), 2, ",", ".") . " " . $row['currency'] . "</td>
        </tr>\n";
    }
}
function couleur($amount) {
    if($amount < 0) {
        return "red;color:white";
    } elseif($amount > 0) {
        return "green;color:white";
    }
}
?>
    </table><br /><br />
</body>
</html>