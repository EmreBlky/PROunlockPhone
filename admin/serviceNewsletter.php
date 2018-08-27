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
    <?php echo admin_common_head_with_title("Newsletter", "", true) ?>
    <script src="scripts/serviceNewsletter.js" type="text/javascript"></script>
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
$rows = mysqli_query($DB->Link, "SELECT id, service_name FROM services WHERE service_status = 1 ORDER BY service_name");
while($row = mysqli_fetch_array($rows)) {
    echo "                    <li class='list-group-item' value='" . $row['id'] . "'>" . $row['id'] . " " . $row['service_name'] . "</li>\n";
}
?>
                    </ul>
                </div>
                <input id="prices" class="form-control" type="button" value="Broadcast New Prices" style="font-size:16px;width:49%;margin-right:20px;display:inline" /><input id="get-checked-data" class="form-control" type="button" value="Broadcast New Services" style="font-size:16px;width:49%;display:inline" />
            </td>
        </tr>
    </table>
    <div id="Panel1" class="overlayer" style="display:none">
        <div id="Panel2" class="loading">
            <div>
                <img id="imgProcessingMaster" class="processing" src="https://www.prounlockphone.com/images/process.gif" alt="Processing" />
            </div>
        </div>
    </div>	            
</body>
</html>