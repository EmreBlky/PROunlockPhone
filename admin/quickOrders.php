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
    <?php echo admin_common_head_with_title("Quick Orders Panel", "10") ?>
    <script type="text/javascript" src="scripts/quickOrders.js"></script>
    <link href='style/orders.css' rel='stylesheet' />
    <script>
        $(function(){
            $('#service').select2({
                placeholder: "Service...",
                theme: "classic",
                allowClear: true
            }).on('change', function() {
                if($(this).val() == null) $(this).val("");
                window.location.replace('quickOrders.php?payment_status=' + $('#payment_status').val() + '&status=' + $('#status').val() + '&service=' + $(this).val());
            }).on('select2:open',function(){
                if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                    $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                    $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
                }
            });
            $('#status').select2({
                theme: "classic",
                minimumResultsForSearch: Infinity
            }).on('change', function() {
                window.location.replace('quickOrders.php?payment_status=' + $('#payment_status').val() + '&status=' + $(this).val() + '&service=' + $('#service').val());
            }).on('select2:open',function(){
                if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                    $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                    $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
                }
            });
            $('#payment_status').select2({
                theme: "classic",
                minimumResultsForSearch: Infinity
            }).on('change', function() {
                window.location.replace('quickOrders.php?payment_status=' + $(this).val() + '&status=' + $('#status').val() + '&service=' + $('#service').val());
            }).on('select2:open',function(){
                if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                    $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                    $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
                }
            });
        });
    </script>
    <style>
        pre {
            white-space: pre-wrap;       /* Since CSS 2.1 */
            white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
            white-space: -pre-wrap;      /* Opera 4-6 */
            white-space: -o-pre-wrap;    /* Opera 7 */
            word-wrap: break-word;       /* Internet Explorer 5.5+ */
            word-break: keep-all;
        }
    </style>
</head>
<body>
<?php
require_once('superheader.php');

$query = "SELECT
    quick_orders.id 'ID',
    relative_id,
    last_update,
    order_date,
    quick_orders.IMEI 'IMEI',
    quick_orders.SN 'SN',
    quick_orders.phone 'data_phone',
    quick_orders.account 'data_account',
    quick_orders.udid 'data_udid',
    quick_orders.status 'status',
    quick_orders.service 'ser_id',
    service_name,
    backupLink,
    backupPwd,
    quick_orders.videoLink 'videoLink',
    quick_orders.fileLink 'fileLink',
    quick_orders.price,
    notes,
    comment,
    admin_response_comments,
    admin_private_notes,
    cancelRequest,
    checkRequest,
    firstname,
    lastname,
    email,
    smsEnabled,
    sms,
    payment_status,
    paypal,
    currency,
    sender,
    amount,
    pintotop,
    provider,
    provider_details,
    originalPrice
FROM quick_orders, services WHERE service = services.id";
if(isset($_GET['check']) and $_GET['check'] == "yes") $query .= " AND checkRequest = 1";
if(isset($_GET['toBeRefunded']) and $_GET['toBeRefunded'] == "yes") {
    $query .= " AND  (status = 'Rejected' OR status = 'Canceled') AND (payment_status = 'Payment Pending Clearance' OR payment_status = 'Payment Received' OR payment_status = 'PayPal Case Solved')";
    $toBeRefunded = 0;
} else {
    $toBeRefunded = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM quick_orders WHERE (status = 'Rejected' OR status = 'Canceled') AND (payment_status = 'Payment Pending Clearance' OR payment_status = 'Payment Received' OR payment_status = 'PayPal Case Solved')"));
}
if(isset($_GET['IMEI'])) $query .= " AND quick_orders.IMEI = \"{$_GET['IMEI']}\"";
if(isset($_GET['SN'])) $query .= " AND quick_orders.SN = \"{$_GET['SN']}\"";
if(isset($_GET['cancel']) && $_GET['cancel'] == "yes") $query .= " AND cancelRequest = 1";
if(isset($_POST['IMEIsearch']) && $_POST['IMEIsearch'] != "") $query .= " AND quick_orders.IMEI like \"%{$_POST['IMEIsearch']}%\"";
if(isset($_POST['SNsearch']) && $_POST['SNsearch'] != "") $query .= " AND quick_orders.SN like \"%{$_POST['SNsearch']}%\"";
if(isset($_POST['relative_id']) && $_POST['relative_id'] != "") $query .= " AND quick_orders.relative_id like \"%{$_POST['relative_id']}%\"";
if(isset($_POST['buyerSearch']) && $_POST['buyerSearch'] != "") $query .= " AND email = \"" . trim($_POST['buyerSearch']) . "\"";
if(isset($_POST['payerSearch']) && $_POST['payerSearch'] != "") $query .= " AND sender = \"" . trim($_POST['payerSearch']) . "\"";
if(isset($_POST['nameSearch']) && $_POST['nameSearch'] != "") $query .= " AND (LCASE(firstname) like \"%" . strtolower(trim($_POST['nameSearch'])) . "%\" OR LCASE(lastname) like \"%" . strtolower(trim($_POST['nameSearch'])) . "%\" OR LCASE(firstname | ' ' | lastname) like \"%" . strtolower(trim($_POST['nameSearch'])) . "%\")";
if(isset($_GET['service']) and $_GET['service'] != "") $query .= " AND service = " . $_GET['service'];
if(isset($_GET['status2']) and $_GET['status2'] != "") {
    $query .= " AND (quick_orders.status = '" . $_GET['status'] . "' OR quick_orders.status = '" . $_GET['status2'] . "')";
} elseif(isset($_GET['status']) and $_GET['status'] == "PinToTop") {
    $query .= " AND pintotop = 1";
} elseif(isset($_GET['status']) and $_GET['status'] != "") $query .= " AND (quick_orders.status = '" . $_GET['status'] . "')";
if(isset($_GET['payment_status']) and $_GET['payment_status'] != "") $query .= " AND payment_status = '" . $_GET['payment_status'] . "'";
$query .= " ORDER BY pintotop DESC, order_date DESC";
if(isset($_GET['limit']) and $_GET['limit']) $query .= " LIMIT " . $_GET['limit'];
$rows = mysqli_query($DB->Link, $query);
$rows2 = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT COUNT(id) 'TOT' FROM quick_orders"));
?>
<div style="margin-top:180px"></div>
<a id="searchRevealer" href="#" style="margin-right:50px;float:right">Custom search</a>
<a style="margin-right:50px;float:right" href="export.php<?php echo strchr($_SERVER['REQUEST_URI'], "?", false); ?>&quick=quick_" target="_blank">Export this list</a>
<h2 style="color:gray;margin-left:50px">Total orders: <?php echo mysqli_num_rows($rows) . " / " . $rows2['TOT'] ?><?php if($toBeRefunded > 0) { echo '<a class="text-danger small" style="margin-left:30px" href="quickOrders.php?toBeRefunded=yes" target="_blank">' . $toBeRefunded . ' Transaction' . ($toBeRefunded > 1 ? 's' : '') . ' to reimburse</a>';} ?><a id="cancels" class="text-warning small" style="margin-left:30px" href="quickOrders.php?cancel=yes"></a><a id="checks" class="text-warning small" style="margin-left:30px" href="quickOrders.php?check=yes"></a></h2>
<form id="searchTable" method="POST" action="quickOrders.php" style="display:none">
    <table style="width:100%;font-size:100%;margin-bottom:30px" align="center">
        <tr>
            <td colspan="2" style="font-size: 24px; padding-bottom: 20px">
                Looking for specific orders
            </td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px;width:40%"><b>IMEI</b></td>
            <td style="padding-bottom: 5px;width:60%"><input class="form-control" name="IMEIsearch" id="IMEIsearch" type="text" size="15" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px"><b>S/N</b></td>
            <td style="padding-bottom: 5px"><input class="form-control" name="SNsearch" id="SNsearch" type="text" size="12" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px"><b>Relative ID</b></td>
            <td style="padding-bottom: 5px"><input class="form-control" name="relative_id" id="relative_id" type="text" size="20" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px"><b>Buyer Name (firstname / lastname / both)</b></td>
            <td style="padding-bottom: 5px"><input class="form-control" name="nameSearch" id="nameSearch" type="text" size="100" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px"><b>Buyer eMail</b></td>
            <td style="padding-bottom: 5px"><input class="form-control" name="buyerSearch" id="buyerSearch" type="text" size="20" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px"><b>Payer eMail</b></td>
            <td style="padding-bottom: 5px"><input class="form-control" name="payerSearch" id="payerSearch" type="text" size="20" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input id="submitter" class="form-control" type="submit" id="searchButton" style="width:200px" value="Search" /></td>
        </tr>
    </table>
</form>
<table style="font-size:100%;" class="main_table" align="center" border="solid 1px #CCC">
    <tr>
        <th style="width:5%">Order ID</th>
        <th style="width:4%">IMEI / SN</th>
        <th style="width:20%;text-align:left">
            <select id="service" style="width: 100%">
                <option value="">All services</option><?php
                $rows2 = mysqli_query($DB->Link, "SELECT DISTINCT service_name, services.id 'ID' FROM services, quick_orders WHERE service = services.id ORDER BY service_name");
                while($row = mysqli_fetch_array($rows2)) {
                    echo "
                    <option value='" . $row['ID'] . "'";
                    if(isset($_GET['service']) and $_GET['service'] == $row['ID']) echo " selected='true'";
                    echo ">#" . $row['ID'] . " " . $row['service_name'] . "</option>";
                }
                ?>
            </select>
        </th>
        <th style="width:10%;text-align:left">
            <select id="status" style="width: 100%">
                <option value="">All status</option>
                <option value="Success">Success</option>
                <option value="Pending&status2=In process">Both progressing</option>
                <option value="In process">In process</option>
                <option value="Pending">Pending</option>
                <option value="PinToTop">Pin To top</option>
                <option value="Canceled&status2=Rejected">Both refused</option>
                <option value="Canceled">Canceled</option>
                <option value="Rejected&limit=0, 50">Rejected (50)</option>
                <option value="Rejected">Rejected (All)</option>
            </select>
            <script>
                $("#status").val("<?php
                    if(isset($_GET['status']) and $_GET['status'] != "") {
                        echo $_GET['status'];
                        if(isset($_GET['status2']) and $_GET['status2'] != "") echo "&status2=" . $_GET['status2'];
                    }
                    ?>");
            </script>
        </th>
        <th style="width:10%;text-align:left">
            <select id="payment_status" style="width: 100%">
                <option value="">All status</option>
                <option value="Payment Received">Received</option>
                <option value="Payment Pending Clearance">Pending Clearance</option>
                <option value="PayPal Case Solved">Case Closed</option>
                <option value="Payment Canceled">Canceled</option>
                <option value="Unpaid">Unpaid</option>
                <option value="Payment Refunded">Refunded</option>
                <option value="New PayPal Case">Open Case</option>
                <option value="Waiting Payment">Waiting Payment</option>
            </select>
            <script>
                $("#payment_status").val("<?php echo isset($_GET['payment_status']) ? $_GET['payment_status'] : "" ?>");
            </script>
        </th>
        <th style="width:21%">Client's message</th>
        <th style="width:20%">Our comments</th>
        <th style="width:20%">Personal notes</th>
    </tr>
    <?php
    $Tunis_time = new DateTimeZone('Africa/Tripoli');
    $GMT_time = new DateTimeZone('Europe/London');
    $Denver_time = new DateTimeZone('America/Denver');
    while($row = mysqli_fetch_array($rows)) {
        echo "          <tr";
        if($row['cancelRequest'] or $row['checkRequest']) {
            echo " style='background-color:#FFE1EB'";
        } elseif($row['pintotop'] == 1) {
            echo " style='background-color:bisque'";
        }
        echo ">
                <td align='center'>
                    <input type='hidden' value='" . $row['ID'] . "' />
                    " . $row['firstname'] . " " . $row['lastname'] . "
                    <br /><a href='mailto:\"" . $row['firstname'] . " " . $row['lastname'] . "\"<" . $row['email'] . ">?subject=Order " . $row['relative_id'] . "'>" . $row['email'] . "</a>";
        if($row['smsEnabled'] == 1 && $row['sms'] != "") {
            echo "<br /><a href='tel:" . $row['sms'] . "'>" . $row['sms'] . "</a>";
        }
        switch($row['payment_status']) {
            case "Payment Refunded":
                $bgcolor = "gray";
                $color = "white";
                break;
            case "New PayPal Case":
                $bgcolor = "red";
                $color = "white";
                break;
            case "Payment Pending Clearance":
                $bgcolor = "orange";
                $color = "white";
                break;
            case "Payment Received":
                $bgcolor = "green";
                $color = "white";
                break;
            case "PayPal Case Solved":
                $bgcolor = "blue";
                $color = "white";
                break;
            case "Unpaid":
                $bgcolor = "black";
                $color = "white";
                break;
            case "Payment Canceled":
                $bgcolor = "black";
                $color = "white";
                break;
            default:
                $bgcolor = "white";
                $color = "black";
                break;
        }
        echo "<br /><span style='border:solid 1px black;border-radius:3px;background-color: " . $bgcolor . ";color:" . $color . ";margin:3px'>" . $row['payment_status'] . "</span>";
        if($row['paypal'] != "") {
            echo "<br />Trx ID #" . $row['paypal'] . "
            <br />Sender: " . ($row['sender'] == $row['email'] ? "same" : "<a href='mailto:\"" . $row['firstname'] . " " . $row['lastname'] . "\"<" . $row['sender'] . ">?subject=Order " . $row['relative_id'] . "'>" . $row['sender'] . "</a>");
            $paid = $row['price'] . " / " . $row['amount'] . " " . $row['currency'];
            if ($row['amount'] < $row['price']) {
                echo "<br /><span style='border:solid 1px black;border-radius:3px;background-color:red;color:white;margin:3px'>" . $paid . "</span>";
            } else echo "<br />" . $paid;
        }
        echo "<br /><label><input type='checkbox' class='pintotop' " . ($row['pintotop'] == 0 ? "" : "checked") . "> Pin To Top</label>";
        $duplicates = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM quick_orders WHERE " . ($row['IMEI'] != "" ? "IMEI = '{$row['IMEI']}'" : "SN = '{$row['SN']}'")));
        if($duplicates > 1) {
            echo "<br /><a href='quickOrders.php?" . ($row['IMEI'] != "" ? "IMEI={$row['IMEI']}" : "SN={$row['SN']}") . "' target='_blank'>" . ($duplicates - 1) . " duplicate" . ($duplicates > 2 ? "s" : "") . "</a>";
        } else {
            $duplicates = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE " . ($row['IMEI'] != "" ? "IMEI = '{$row['IMEI']}'" : "SN = '{$row['SN']}'")));
            if($duplicates > 1) {
                echo "<br /><a href='superorders.php?" . ($row['IMEI'] != "" ? "IMEI={$row['IMEI']}" : "SN={$row['SN']}") . "' target='_blank'>" . ($duplicates - 1) . " duplicate" . ($duplicates > 2 ? "s" : "") . "</a>";
            }
        }
        echo "</td>
                <td align='center'>";
        if($row['IMEI']) {
            echo $row['IMEI'];
        } else {
            echo $row['SN'];
        }
        echo "<br /><a href='https://www.prounlockphone.com/track/order-status.php?ref=" . $row['relative_id'] . "' target='_blank'>" . $row['relative_id'] . "</a>";
        switch($row['status']) {
            case "Success":
                $status_color = "green";
                break;
            case "In process":
                $status_color = "blue";
                break;
            case "Pending":
                $status_color = "orange";
                break;
            case "Canceled":
                $status_color = "#222";
                break;
            case "Rejected":
                $status_color = "red";
                break;
        }
        echo "</td>
                <td><a href='https://www.prounlockphone.com/admin/updateService.php?service={$row['ser_id']}' target='_blank' style='color:forestgreen'>#{$row['ser_id']}</a> <a href='https://www.prounlockphone.com/service/?id=" . $row['ser_id'] . "' target='_blank'>{$row['service_name']}</a><br />
                <a style='color:pink'>{$row['provider']}</a>
                <br/><a style='color:purple'>{$row['provider_details']}</a><br />
                <a style='color:gray'>" . $row['price'] . " " . $row['currency'] . " / " . $row['originalPrice'] . "</a>
                    </td>
                <td align='center' style='background:" . $status_color . "'>
                    <button class='actions status'";
        if($row['status'] == "Success") echo " disabled='true'";
        echo " style='background:green'>Success</button><br />
                    <button class='actions status'";
        if($row['status'] == "In process") echo " disabled='true'";
        echo " style='background:blue'>In process</button><br />
                    <button class='actions status'";
        if($row['status'] == "Pending") echo " disabled='true'";
        echo " style='background:orange'>Pending</button><br />
                    <button class='actions status'";
        if($row['status'] == "Canceled") echo " disabled='true'";
        echo " style='background:#222'>Canceled</button><br />
                    <button class='actions status'";
        if($row['status'] == "Rejected") echo " disabled='true'";
        echo " style='background:red'>Rejected</button>
                </td>
                <td align='center' style='background:" . $bgcolor . "'>
                    <button class='actions payment_status' style='background:green;color:white'>Payment Received</button><br />
                    <button class='actions payment_status' style='background:blue;color:white'>PayPal Case Solved</button><br />
                    <button class='actions payment_status' style='background:orange;color:white'>Payment Pending Clearance</button><br />
                    <button class='actions payment_status' style='background:black;color:white'>Payment Canceled</button><br />
                    <button class='actions payment_status' style='background:black;color:white'>Unpaid</button><br />
                    <button class='actions payment_status' style='background:gray;color:white'>Payment Refunded</button><br />
                    <button class='actions payment_status' style='background:red;color:white'>New PayPal Case</button>
                    <button class='actions payment_status' style='background:white;color:black'>Waiting Payment</button>
                </td>
                <td style='word-wrap:break-word;word-break:break-all;vertical-align:top'>\n";
        echo "                  <u style='display:block'>Order date:</u> ";
        $order_date = new DateTime($row['order_date'], $GMT_time);
        $order_date->setTimezone($Tunis_time);
        echo $order_date->format('Y-m-d H:i:s') . "<br />\n";

        $last_update = new DateTime($row['last_update'], $Denver_time);
        $last_update->setTimezone($Tunis_time);

        if($last_update != $order_date) {
            echo "                  <u style='display:block'>Last update:</u> " . $last_update->format('Y-m-d H:i:s') . "<br />\n";
        }
        if($row['data_phone'] != "") {
            echo "                  <u style='display:block'>Phone Number:</u> " . $row['data_phone'] . "<br />\n";
        }
        if($row['data_account'] != "") {
            echo "                  <u style='display:block'>eMail Address:</u> " . $row['data_account'] . "<br />\n";
        }
        if($row['data_udid'] != "") {
            echo "                  <u style='display:block'>UDID:</u> " . $row['data_udid'] . "<br />\n";
        }
        if($row['backupLink'] != "") {
            echo "                  <u style='display:block'>Link:</u> " . $row['backupLink'] . "<br />\n";
        }
        if($row['backupPwd'] != "") {
            echo "                  <u>Pass:</u> " . $row['backupPwd'] . "<br />\n";
        }
        if($row['videoLink'] != "") {
            echo "                  <u>Video Link:</u> " . $row['videoLink'] . "<br />\n";
        }
        if($row['fileLink'] != "") {
            echo "                  <u>File Link:</u> " . $row['fileLink'] . "<br />\n";
        }
        if($row['comment'] != "") {
            if(strlen($row['comment']) > 50) {
                echo "<i style='display:none;color:purple'>" . nl2br($row['comment']) . "<br /></i>&rarr; <a class='message' style='color:red;cursor:pointer;border:none'>Show message</a>";
            } else {
                echo "<i style='color:red'>" . nl2br($row['comment']) . "</i>";
            }
        }
        echo "</td>
                <td style='padding:2px'>
                    <div class='no_comments' style='text-align:center" . ($row['admin_response_comments'] == "" ? "" : ";display:none") . "'>
                        <a style='cursor:pointer'>no comments</a>
                    </div>
                    <pre class='hidden_comments'" . ($row['admin_response_comments'] == "" ? " style='display:none'" : "") . ">";
        if(strtolower(substr($row['admin_response_comments'], 0, 4)) == "<pre") {
            echo substr($row['admin_response_comments'], strpos($row['admin_response_comments'], '>') + 1, -6);
        } else {
            echo $row['admin_response_comments'];
        }
        echo "</pre>
                <div style='text-align:center;display:none'>
                    <textarea class='admin_comments comments' style='width:100%' rows='7'>" . $row['admin_response_comments'] . "</textarea>
                    <br/>
                    <small class='reset'><a class='text-primary' style='cursor: pointer'>reset HTML</a></small>
                </div>
                </td>
                <td style='padding:2px'><textarea class='admin_notes comments' style='width:100%' rows='7'>" . $row['admin_private_notes'] . "</textarea></td>
            </tr>\n";
    }
    ?>
</table>
<br /><br />
<div id="Panel1" class="overlayer" style="display:none">
    <div id="Panel2" class="loading">
        <div>
            <img id="imgProcessingMaster" src="https://www.prounlockphone.com/images/process.gif" alt="Processing" />
        </div>
    </div>
</div>
</body>
</html>