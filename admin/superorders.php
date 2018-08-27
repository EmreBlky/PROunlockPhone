<?php

define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

?>
<html>
<head>
    <?php echo admin_common_head_with_title("Orders Panel", "10") ?>
    <!--********************************************-->
    <!-- include summernote css/js -->
    <link href='https://www.prounlockphone.com/common/aa/summernote.css' rel='stylesheet'>
    <script src='https://www.prounlockphone.com/common/aa/summernote.js'></script>
    <!--********************************************-->
    <script type='text/javascript' src='scripts/superorders.js'></script>
    <link href='style/orders.css' rel='stylesheet' />
    <script>
        // var variation;
        $(function(){
            $('#client').select2({
                placeholder: "Client...",
                theme: "classic",
                allowClear: true
            }).on('change', function() {
                if($(this).val() == null) $(this).val("");
                window.location.replace('superorders.php?client=' + $(this).val() + '&status=' + $('#status').val() + '&service=' + $('#service').val());
            }).on('select2:open',function(){
                if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                    $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                    $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
                }
            });
            $('#service').select2({
                placeholder: "Service...",
                theme: "classic",
                allowClear: true
            }).on('change', function() {
                if($(this).val() == null) $(this).val("");
                window.location.replace('superorders.php?client=' + $('#client').val() + '&status=' + $('#status').val() + '&service=' + $(this).val());
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
                window.location.replace('superorders.php?client=' + $('#client').val() + '&status=' + $(this).val() + '&service=' + $('#service').val());
            }).on('select2:open',function(){
                if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                    $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                    $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
                }
            });
            // $('.comments').summernote({
            //     toolbar: [
            //         // [groupName, [list of button]]
            //         ['fontname', ['fontname']],
            //         ['style', ['bold', 'italic', 'underline', 'clear']],
            //         ['font', ['strikethrough', 'superscript', 'subscript']],
            //         ['fontsize', ['fontsize']],
            //         ['color', ['color']],
            //         ['para', ['ul', 'ol', 'paragraph']],
            //         ['height', ['height']],
            //         ['table', ['table']],
            //         ['view', ['codeview', 'undo', 'redo']],
            //     ],
            //     // placeholder: 'Add notes here...',
            //     width: 170,
            //     minWidth: 100,
            //     height: 100,
            //     minHeight: 100,
            //     callbacks : {
            //         onFocus : function(){
            //             variation = $(this).val();
            //             // $(this).find('.note-toolbar')
            //             //     .addClass('active')
            //             //     .slideDown();
            //             $(this).closest('.note-toolbar').show();
            //             $(this).children('.note-toolbar').show();
            //             $(this).find('.note-toolbar').show();
            //         },
            //         onBlur : function(){
            //             if($(this).val() == variation) return false;
            //             $.get('saveAdminComments.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&field=admin_' + ($(this).hasClass("admin_comments") ? "response_comments" : "private_notes") + '&data=' + encodeURIComponent($(this).val()));
            //         }
            //     }
            // });
            // $('.note-toolbar').hide();
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
    users.id 'client',
    services.id 'ser_id',
    orders.phone 'data_phone',
    clear_email 'data_account',
    orders.udid 'data_udid',
    orders.status_mode 'data_status_mode',
    services.phone 'phone',
    services.account 'account',
    services.udid 'udid',
    services.status_mode 'status_mode',
    services.photo 'photo',
    username,
    client_order_comments,
    admin_private_notes,
    admin_response_comments,
    orders.id 'ID',
    relative_id,
    orders.IMEI 'IMEI',
    orders.SN 'SN',
    service_name,
    orders.status 'status',
    order_date,
    users.country,
    last_update,
    cancelRequest,
    checkRequest,
    price,
    currency,
    provider,
    provider_details,
    originalPrice,
    ebayer,
    tracker,
    owner_name,
    balance,
    backupLink,
    backupPwd,
    pintotop,
    UID
FROM users, orders, services WHERE users.id = orders.client AND orders.service = services.id";
if(isset($_GET['client']) and $_GET['client'] != "") $query .= " AND client={$_GET['client']}";
if(isset($_GET['IMEI']) and $_GET['IMEI'] != "") $query .= " AND orders.IMEI = \"{$_GET['IMEI']}\"";
if(isset($_GET['SN']) and $_GET['SN'] != "") $query .= " AND orders.SN = \"{$_GET['SN']}\"";
if(isset($_GET['check']) && $_GET['check'] == "yes") $query .= " AND checkRequest = 1";
if(isset($_GET['cancel']) && $_GET['cancel'] == "yes") $query .= " AND cancelRequest = 1";
if(isset($_POST['IMEIsearch']) && $_POST['IMEIsearch'] != "") $query .= " AND orders.IMEI like \"%{$_POST['IMEIsearch']}%\"";
if(isset($_POST['SNsearch']) && $_POST['SNsearch'] != "") $query .= " AND orders.SN like \"%{$_POST['SNsearch']}%\"";
if(isset($_POST['eBayerSearch']) && $_POST['eBayerSearch'] != "") $query .= " AND (ebayer like \"%{$_POST['eBayerSearch']}%\" OR client_order_comments like \"%{$_POST['eBayerSearch']}%\" OR admin_response_comments like \"%{$_POST['eBayerSearch']}%\" OR ebayer like \"%{$_POST['eBayerSearch']}%\")";
if(isset($_POST['AppleIDsearch']) && $_POST['AppleIDsearch'] != "") $query .= " AND orders.clear_email like \"%{$_POST['AppleIDsearch']}%\"";
if(isset($_POST['PhoneSearch']) && $_POST['PhoneSearch'] != "") $query .= " AND orders.phone like \"%{$_POST['PhoneSearch']}%\"";
if(isset($_GET['service']) and $_GET['service'] != "") $query .= " AND service = " . $_GET['service'];
if(isset($_GET['status2'])) {
    $query .= " AND (orders.status = '" . $_GET['status'] . "' OR orders.status = '" . $_GET['status2'] . "')";
} elseif(isset($_GET['status']) and $_GET['status'] == "PinToTop") {
    $query .= " AND pintotop = 1";
} elseif(isset($_GET['status']) and $_GET['status'] != "") $query .= " AND (orders.status = '" . $_GET['status'] . "')";
$query .= " ORDER BY cancelRequest DESC, checkRequest DESC, pintotop DESC, order_date DESC";
if(isset($_GET['limit'])) $query .= " LIMIT " . $_GET['limit'];
$toBeValidated = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM statement WHERE asgift = 1 AND validated = 0"));
$quickOrders = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM quick_orders WHERE pintotop = 1 OR status = 'Pending'"));
$rows = mysqli_query($DB->Link, $query);
$rows2 = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT COUNT(id) 'TOT' FROM orders"));
?>
<div style="margin-top:180px"></div>
<a id="searchRevealer" href="#" style="margin-right:50px;float:right">Custom search</a>
<a style="margin-right:50px;float:right" href="export.php<?php echo strchr($_SERVER['REQUEST_URI'], "?", false); ?>" target="_blank">Export this list</a>
<h2 style="color:gray;margin-left:50px">Total orders: <?php echo mysqli_num_rows($rows) . " / " . $rows2['TOT'] ?><?php if($toBeValidated > 0) { echo '<a class="text-danger small" style="margin-left:30px" href="toValidate/" target="_blank">' . $toBeValidated . ' Transaction' . ($toBeValidated > 1 ? 's' : '') . ' to validate</a>';} ?><?php if($quickOrders > 0) { echo '<a class="text-danger small" style="margin-left:30px" href="quickOrders.php?status=Pending&status2=In process" target="_blank">' . $quickOrders . ' Quick Order' . ($quickOrders > 1 ? 's' : '') . ' to process</a>';} ?><a id="cancels" class="text-warning small" style="margin-left:30px" href="superorders.php?cancel=yes"></a><a id="checks" class="text-warning small" style="margin-left:30px" href="superorders.php?check=yes"></a></h2>
<form id="searchTable" method="POST" action="superorders.php" style="display:none">
    <table style="width:350px;font-size:100%;margin-bottom:30px" align="center">
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
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px"><b>eBayer</b></td>
            <td style="padding-bottom: 5px"><input class="form-control" name="eBayerSearch" id="eBayerSearch" type="text" size="50" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px"><b>Apple ID</b></td>
            <td style="padding-bottom: 5px"><input class="form-control" name="AppleIDsearch" id="AppleIDsearch" type="text" size="100" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px;padding-bottom: 5px"><b>Phone Number</b></td>
            <td style="padding-bottom: 5px"><input class="form-control" name="PhoneSearch" id="PhoneSearch" type="text" size="20" style="width:200px" onkeypress="if(event.keyCode == 13 && this.value != '')submit()" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input id="submitter" class="form-control" type="submit" id="searchButton" style="width:200px" value="Search" /></td>
        </tr>
    </table>
</form>
<table style="font-size:100%;" class="main_table" align="center" border="solid 1px #CCC">
    <tr>
        <th style="width:5%;text-align:left">
            <select id="client" style="width: 100%">
                <option value="">All clients</option><?php
                $rows2 = mysqli_query($DB->Link, "SELECT id, username FROM users ORDER BY username");
                while($row = mysqli_fetch_array($rows2)) {
                    echo "
                    <option value='" . $row['id'] . "'";
                    if(isset($_GET['client']) and $_GET['client'] == $row['id']) echo " selected='true'";
                    echo ">" . $row['username'] . "</option>";
                }
                ?>
            </select>
        </th>
        <th style="width:4%">IMEI / SN</th>
        <th style="width:20%;text-align:left">
            <select id="service" style="width: 100%">
                <option value="">All services</option><?php
                $rows2 = mysqli_query($DB->Link, "SELECT DISTINCT service_name, services.id 'ID' FROM services, orders WHERE service = services.id ORDER BY service_name");
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
        <th style="width:21%">Client's message</th>
        <th style="width:20%">Our comments</th>
        <th style="width:20%">Personal notes</th>
    </tr>
    <?php
    $cancels = 0;
    $checks = 0;
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
                    <input type='hidden' value='" . $row['client'] . "' />
                    <a href='statement.php?client=" . $row['client'] . "' target='_blank'>" . $row['country'] . "<br />" . $row['username'] . "</a>
                    <br />[<b style='color:" . ($row['balance'] < 0 ? "red" : "green") . "'>" . number_format($row['balance'], 2, ".", ",") . "</b>]
                    <br />(<a href='order.php?id=" . $row['ID'] . "' target='_blank'>" . $row['relative_id'] . "</a>)
                    <br /><label><input type='checkbox' class='pintotop' " . ($row['pintotop'] == 0 ? "" : "checked") . "> Pin To Top</label>";
        $duplicates = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE " . ($row['IMEI'] != "" ? "IMEI = '{$row['IMEI']}'" : "SN = '{$row['SN']}'")));
        if($duplicates > 1) {
            echo "<br /><small><a href='superorders.php?" . ($row['IMEI'] != "" ? "IMEI={$row['IMEI']}" : "SN={$row['SN']}") . "' target='_blank'>" . ($duplicates - 1) . " duplicate" . ($duplicates > 2 ? "s" : "") . "</a></small>";
        } else {
            $duplicates = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM quick_orders WHERE " . ($row['IMEI'] != "" ? "IMEI = '{$row['IMEI']}'" : "SN = '{$row['SN']}'")));
            if($duplicates > 1) {
                echo "<br /></small><a href='quickOrders.php?" . ($row['IMEI'] != "" ? "IMEI={$row['IMEI']}" : "SN={$row['SN']}") . "' target='_blank'>" . ($duplicates - 1) . " duplicate" . ($duplicates > 2 ? "s" : "") . "</a></small>";
            }
        }
        if($row['cancelRequest']) {
            echo "<br /><br /><b style='color:red;cursor:pointer' class='revoke'>REVOKE</b>";
            $cancels++;
        } elseif($row['checkRequest']) {
            echo "<br /><br /><b style='color:red;cursor:pointer' class='confirm'>CONFIRM</b>";
            $checks++;
        }
        echo "</td>
                <td align='center'>";
        if($row['IMEI']) {
            echo $row['IMEI'];
        } else {
            echo $row['SN'];
        }
        switch($row['status']) {
            case "Success":
                $color = "green";
                break;
            case "In process":
                $color = "blue";
                break;
            case "Pending":
                $color = "orange";
                break;
            case "Canceled":
                $color = "#222";
                break;
            case "Rejected":
                $color = "red";
                break;
        }
        echo "<br/><small><a href='https://www.prounlockphone.com/track/order-status.php?ref=" . $row['UID'] . "' target='_blank'>preview</a></small>
        <br/><small><a href='https://www.prounlockphone.com/admin/update_comments.php?ref=" . $row['UID'] . "' target='_blank'>format comments</a></small></td>
                <td><a href='https://www.prounlockphone.com/admin/updateService.php?service={$row['ser_id']}' target='_blank' style='color:forestgreen'>#{$row['ser_id']}</a> <a href='https://www.prounlockphone.com/service/?id=" . $row['ser_id'] . "' target='_blank'>{$row['service_name']}</a><br />
                <a style='color:pink'>{$row['provider']}</a>
                <br/><a style='color:purple'>{$row['provider_details']}</a><br />
                <a style='color:gray' href='https://www.prounlockphone.com/admin/orderPrice.php?id={$row['ID']}' target='_blank'>" . $row['price'] . " " . $row['currency'] . " / " . $row['originalPrice'] . "</a>
                    </td>
                <td align='center' style='background:" . $color . "'>
                    <button class='actions'";
        if($row['status'] == "Success") echo " disabled='true'";
        echo " style='background:green'>Success</button><br />
                    <button class='actions'";
        if($row['status'] == "In process") echo " disabled='true'";
        echo " style='background:blue'>In process</button><br />
                    <button class='actions'";
        if($row['status'] == "Pending") echo " disabled='true'";
        echo " style='background:orange'>Pending</button><br />
                    <button class='actions'";
        if($row['status'] == "Canceled") echo " disabled='true'";
        echo " style='background:#222'>Canceled</button><br />
                    <button class='actions'";
        if($row['status'] == "Rejected") echo " disabled='true'";
        echo " style='background:red'>Rejected</button>                    
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
        if($row['status_mode'] == 1) {
            switch($row['data_status_mode']) {
                case "L":
                    $data_status_mode = "Lost";
                    break;
                case "LE":
                    $data_status_mode = "Lost & Erased";
                    break;
                case "S":
                    $data_status_mode = "Stolen";
                    break;
                case "C":
                    $data_status_mode = "Clean";
                    break;
            }
            echo "                  <u style='display:block'>iCloud Status:</u> " . $data_status_mode . "<br />\n";
        }
        if($row['photo'] == 1) {
            echo "                  <a style='display:block' target='_blank' href='photo.php?order=" . $row['ID'] . "'>Photo</a>\n";
        }
        if($row['owner_name'] != "") {
            echo "                  <u>Owner's name:</u> " . $row['owner_name'] . "<br />\n";
        }
        if($row['ebayer'] != "") {
            echo "                  <u>eBayer:</u> " . $row['ebayer'] . "<br />\n";
        }
        if($row['tracker'] != "") {
            echo "                  <u style='display:block'>Tracker:</u> " . $row['tracker'] . "<br />\n";
        }
        if($row['client_order_comments'] != "") {
            if(strlen($row['client_order_comments']) > 50) {
                echo "<i style='display:none;color:purple'>" . nl2br($row['client_order_comments']) . "<br /></i>&rarr; <a class='message' style='color:red;cursor:pointer;border:none'>Show message</a>";
            } else {
                echo "<i style='color:red'>" . nl2br($row['client_order_comments']) . "</i>";
            }
        }
        echo "</td>
                <td style='padding:2px'>
                    <div class='no_comments' style='text-align:center" . ($row['admin_response_comments'] == "" ? "" : ";display:none") . "'>
                        <a style='cursor:pointer'>no comments</a>
                    </div>
                    <pre class='hidden_comments'" . ($row['admin_response_comments'] == "" ? " style='display:none'" : "") . ">" . cleanResponse($row['admin_response_comments'], ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN'])) . "</pre>
                <div style='display:none'>
                    <textarea class='admin_comments comments' style='width:100%' rows='7'>" . $row['admin_response_comments'] . "</textarea>
                    <div style='text-align:center'><small class='reset'><a class='text-primary' style='cursor: pointer'>reset HTML</a></small></div>
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
<?php
if($cancels > 0) {
    echo "<script language='javascript'>
    $('#cancels').html('" . $cancels . " cancel request(s)');
</script>";
}
if($checks > 0) {
    echo "<script language='javascript'>
    $('#checks').html('" . $checks . " check request(s)');
</script>";
}
?>
</body>
</html>