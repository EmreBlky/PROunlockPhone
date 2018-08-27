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
    <?php echo admin_common_head_with_title("User Special Prices") ?>
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
SELECT username, first_name, last_name, balance, currency, email, type
FROM users
WHERE id = " . $_GET['id'];
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, $query));
$currency_raw = $row['currency'];
switch($row['currency']) {
    case "USD":
        $currency = "$";
        break;
    case "EUR":
        $currency = "&euro;";
        break;
    case "GBP":
        $currency = "&pound;";
        break;
    case "TND":
        $currency = "DT";
        break;
}
?>
    <a id="funds" style="float: right;margin-right:50px" href="#">Adjust Price</a><h3 style="color:gray;margin-top:180px;margin-left:50px"><span style="color:blue"><?php echo ucfirst($row['type']) . "</span>: " . $row['first_name'] . " " . $row['last_name'] . " (" . $row['username'] . ") [" . $row['email'] . "]: Current balance <span style='color:" . ($row['balance'] < 0 ? "red" : "green") . "'>" . number_format($row['balance'], 2, ".", ",") . " " . $currency ?></span></h3>
    <div id="funds_div" style="display:none;font-size:1.5em;text-align:center;padding-bottom:10px">
        <form method="post" action="add_exception.php">
        <input type="hidden" name="client" value="<?php echo $_GET['id'] ?>" />
        Price: <input name="price" type="text" value="0.00" onfocus="this.select()" style="margin-right:70px;width:80px;text-align:right;border-radius:5px;border:solid 1px gray;padding-right:5px" />
        Service: <select name="service" style="margin-right:70px">
<?php
$query = "SELECT id, service_name FROM services WHERE service_status = 1 AND id NOT IN (SELECT service FROM price_client_service WHERE client = " . $_GET['id'] . ") ORDER BY service_name";
$rows = mysqli_query($DB->Link, $query);
while($row = mysqli_fetch_array($rows)) {
    echo '<option value="' . $row['id'] . '">' . $row['service_name'] . '</option>\n';
}
?>
        </select>
        <br />Nature: <input style='margin-left:50px' name="nature" type="radio" value="yield" checked="true"/> Yield discount
        <input style='margin-left:50px' name="nature" type="radio" value="impose"/> Impose price
        <input type="submit" style="margin-left:50px;border-radius:5px;background-color:#177EE5;color:white" value="Save" />
        </form>
    </div>
    <table style="font-size:100%;width:80%" class="main_table" align="center" border="solid 1px #CCC">
        <tr>
            <th rowspan="2" style="width:7%">Nature</th>
            <th rowspan="2" style="width:46%">Service Name</th>
            <th colspan="3" style="width:24%">Service Price</th>
            <th rowspan="2" style="width:8%">Client Price</th>
            <th rowspan="2" style="width:15%">Last Update</th>
        </tr>
        <tr>
            <th>Regular</th>
            <th>Reseller</th>
            <th>Original</th>
        </tr>
        <?php
$query = "
SELECT price_client_service.id 'globID', services.id 'id', service_name, service, client, price, nature, last_update, regular_" . $currency_raw . ", reseller_" . $currency_raw . ", originalPrice
   FROM services, price_client_service
WHERE services.id = service
AND client = " . $_GET['id'] . "
ORDER BY service_name";
$rows = mysqli_query($DB->Link, $query);
while($row = mysqli_fetch_array($rows)) {
    if($row['nature'] == "impose") {
        $style = " style='background-color:crimson;color:white'";
    } else {
        $style = "";
    }
    echo "<tr align='center'" . $style . ">
            <td>" . strtoupper($row['nature']) . "</td>
            <td align='left'><img onclick='revoke(" . $row['globID'] . ")' src='https://www.prounlockphone.com/images/delete.png' style='cursor:pointer;margin-right:10px' />#" . $row['id'] . " " . $row['service_name'] . "</td>
            <td>" . number_format($row['regular_' . $currency_raw], 2, ".", ",") . " " . $currency . "</td>
            <td>" . number_format($row['reseller_' . $currency_raw], 2, ".", ",") . " " . $currency . "</td>
            <td>" . number_format($row['originalPrice'], 2, ".", ",") . " $</td>
            <td>" . number_format($row['price'], 2, ".", ",") . " " . $currency . "</td>
            <td>" . $row['last_update']. "</td>
        </tr>\n";
}
?>
    </table><br /><br />
    <div id="Panel1" class="overlayer" style="display:none">
        <div id="Panel2" class="loading">
            <div>
                <img id="imgProcessingMaster" class="processing" src="https://www.prounlockphone.com/images/process.gif" alt="Processing" />
            </div>
        </div>
    </div>
    <script>
$("#funds").on('click', function() {
    $("#funds_div").slideToggle("slow");
});
function revoke(id) {
    if(confirm("You are about to delete this exception.\nClick 'Yes' to confirm.")) {
        window.location = 'drop_exception.php?id=' + id + '&client=<?php echo $_GET['id'] ?>';
    }
}
    </script>
</body>
</html>