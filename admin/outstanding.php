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
    <?php echo admin_common_head_with_title("Outstanding Balance Users") ?>
    <link rel="stylesheet" href="style/newsletter.css" />
</head>
<body>
<?php
    require_once('superheader.php');
?>
    <table align="center" style="margin-top:50px;width:72%">
        <tr>
            <td>
                <div id="success_div" style="display:none;margin-bottom:10px;width:72%;border:solid 1px green;border-radius:5px;background-color: ghostwhite;color:green"><h3>Service successfully update</h3></div>
                <div id="failure_div" style="display:none;margin-bottom:10px;width:72%;border:solid 2px red;border-radius:5px;background-color:pink;color:red"><h3 id="failure"></h3></div>
                <h3 class="text-center">Broadcast New Prices</h3>
                <div class="well" style="max-height: 500px;overflow: auto;">
                        <ul class="list-group checked-list-box">
<?php
$query = "
SELECT id, username, first_name, last_name, balance, currency, email, country
FROM users
WHERE
    status <> 'Suspended'
ORDER BY
    first_name, last_name, username";
$rows = mysqli_query($DB->Link, $query);
while($row = mysqli_fetch_array($rows)) {
    $query = "SELECT SUM(price) 'locked' 
        FROM orders 
        WHERE
            client = " . $row['id'] . " 
            AND (orders.status = 'Pending' OR orders.status = 'In process')";
    $req = mysqli_fetch_assoc(mysqli_query($DB->Link, $query));
    if($row['balance'] + $req['locked'] < 0) {
        echo "<li class='list-group-item' value='" . $row['id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . " (<a href='statement.php?client=" . $row['id'] . "' target='_blank'>" . $row['username'] . "</a>) [<b style='color:red'>" . number_format(min($row['balance'] + $req['locked'], 0), 2, ",", ".") . " " . $row['currency'] . "</b>]</li>\n";
    }
}
?>
                    </ul>
                </div>
                <input id="send" class="form-control" type="button" value="Broadcast reminders" style="font-size:16px;width:49%;margin-right:20px;display:inline" />
            </td>
        </tr>
    </table>
    <div id="Panel1" class="overlayer" style="display:none">
        <div id="Panel2" class="loading">
            <div>
                <img id="imgProcessingMaster" class="processing" src="https:www.prounlockphone.com/mages/process.gif" alt="Processing" />
            </div>
        </div>
    </div>	            
</body>
</html>