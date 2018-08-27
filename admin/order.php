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
    <?php echo admin_common_head_with_title("Order Details", "20") ?>
    <script src="scripts/order.js" type="text/javascript"></script>
    <script>
    $(function(){
        $('#service').select2({
            placeholder: "Update the service...",
            theme: "classic"
        });
    });
    </script>
</head>
<body>
<?php
require_once('superheader.php');
$req = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT backupLink, backupPwd, backupData, videoLink, fileLink, users.id 'users_id', services.id 'ser_id', orders.IMEI 'IMEI', orders.SN 'SN', orders.udid 'udid', clear_email, orders.phone 'phone', username, first_name, last_name, ebayer, tracker, owner_name, email FROM orders, services, users WHERE users.id = orders.client AND orders.id = '{$_GET['id']}' AND orders.service = services.id"));
$path = $req['IMEI'] != "" ? $req['IMEI'] : $req['SN'];
?>
    <div align="center" style="margin-top:80px;width:100%">
        <div align="left" style="width:40%">
            <fieldset>
                <input type="hidden" id="id" value="<?php echo $_GET['id'] ?>" />
                <label class="titles" for="service">Service Name</label>
                <select id="service" name="service" style="width:100%">
        <?php
        $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, service_status FROM services ORDER BY service_group, service_name");
        $optgroup = "";
        while($row = mysqli_fetch_array($rows)) {
        if($optgroup != $row['service_group']) {
            if($optgroup != "") echo "              </optgroup>\n";
            $optgroup = $row['service_group'];
            echo "              <optgroup label='" . $row["service_group"] . "'>\n";
        }
        echo "                  <option value='" . $row['id'] . "'" . ($row['service_status'] == 0 ? " disabled='true'" : "") . ">" . $row['id'] . " " . $row['service_name'] . "</option>\n";
        }
        ?>
                    </optgroup>
                </select><br />
                <label class="titles" for="client">Client</label>
                <br /><a href="statement.php?id=<?php echo $req['users_id'] ?>" target="_BLANK"><?php echo $req['first_name'] . " " . $req['last_name'] . " (" . $req['username'] . ") :: " . $req['email'] ?></a><br />
                <label class="titles" for="imei">IMEI</label>
                <input class="form-control" maxlength="15" type="text" name="imei" id="imei" value="<?php echo $req['IMEI'] ?>" />
                <label class="titles" for="serial">Serial Number</label>
                <input class="form-control" id="serial" name="serial" type="text" style="text-transform: uppercase" maxlength="12" value="<?php echo $req['SN'] ?>" />
                <label class="titles" for="backupLink">Backup Link</label>
                <input class="form-control" id="backupLink" name="backupLink" type="text" value="<?php if($req['backupData'] == "1") { echo $req['backupLink']; } ?>" />
                <label class="titles" for="backupPwd">Backup Password</label>
                <input class="form-control" id="backupPwd" name="backupPwd" type="text" value="<?php if($req['backupData'] == "1") { echo $req['backupPwd']; } ?>" />
                <label class="titles" for="videoLink">Video Link</label>
                <input class="form-control" id="videoLink" name="videoLink" type="text" value="<?php if($req['videoLink'] == "1") { echo $req['backupLink']; } ?>" />
                <label class="titles" for="fileLink">File Link</label>
                <input class="form-control" id="fileLink" name="fileLink" type="text" value="<?php if($req['fileLink'] == "1") { echo $req['backupLink']; } ?>" />
                <label class="titles" for="udid">UDID</label>
                <input class="form-control" id="udid" name="udid" type="text" style="text-transform: lowercase" maxlength="40" value="<?php echo $req['udid'] ?>" />
                <label class="titles" for="phone">Phone Number</label>
                <input class="form-control" id="phone" name="phone" type="text" style="text-transform: lowercase" maxlength="40" value="<?php echo $req['phone'] ?>" />
                <label class="titles" for="account">Apple Account</label>
                <input class="form-control" id="account" name="account" type="email" value="<?php echo $req['clear_email'] ?>" />
                <label class="titles" for="owner_name">Owner's Name</label>
                <input class="form-control" id="owner_name" name="owner_name" type="text" value="<?php echo $req['owner_name'] ?>" />
                <label class="titles" for="ebayer">eBayer</label>
                <input class="form-control" id="ebayer" name="ebayer" type="text" value="<?php echo $req['ebayer'] ?>" />
                <label class="titles" for="tracker">Tracker</label>
                <input class="form-control" id="tracker" name="tracker" type="text" value="<?php echo $req['tracker'] ?>" />
                <?php if(file_exists('images/Uploaded/' . $path . '.jpg')) {
                    echo "<br /><img style='border:solid 1px gray' height='600px' src='https://www.prounlockphone.com/images/Uploaded/" . $path . ".jpg' />\n";
                } ?>
            </fieldset>
            <input class="form-control" style="height:40px;margin-top:30px;margin-bottom:50px" type="button" id="update" value="Save" />
        </div>
    </div>
    <div id="Panel1" class="overlayer" style="display:none">
        <div id="Panel2" class="loading">
            <div>
                <img id="imgProcessingMaster" class="processing" src="https://www.prounlockphone.com/images/process.gif" alt="Processing" />
            </div>
        </div>
    </div>
    <script language="javascript">
        $("#service").val("<?php echo $req['ser_id'] ?>");
    </script>
</body>
</html>