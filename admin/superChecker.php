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
    <?php echo admin_common_head_with_title("Instant Check", "20") ?>
    <script src="scripts/superCheck.js" type="text/javascript"></script>
    <link rel="stylesheet" href="style/place.css" />
    <script>
    $(function(){
        $('#client').select2({
            placeholder: "Choose the client...",
            theme: "classic",
            allowClear: true
        });
        $('#theSelect').select2({
            placeholder: "Choose your service...",
            theme: "classic"
        }).on("change", function() {
            $("#service").val($(this).val());
            $('#results').fadeOut('2000');
            $('#server').fadeOut('2000');
            $('#low').fadeOut('2000');
            $.getJSON("Get_service_details.php?service=" + $(this).val(), function(data) {
                $("#order_content").show();
                $("#service_name").html(data.service_name);
                var description = data.description;
                var table = "";
                if(data.models != "") {
                    var models = data.models.split("-");
                    table = "<br /><table style='width:100%'><tr style='text-align:center'>";
                    for(var i = 0; i < models.length; i++) {
                        table += "<td style='width:" + Math.round(100 / models.length) + "%'><img src='https://www.prounlockphone.com/images/" + models[i] + ".png' style='width:100%;max-width:80px' /></td>";
                    }
                    table += "</tr><tr style='text-align:center'>";
                    for(var i = 0; i < models.length; i++) {
                        table += "<td>" + models[i] + "</td>";
                    }
                    table += "</tr></table>";
                    description += table;
                }
                description += "<br /><table cellpadding='10px' style='width:100%'><tr style='text-align:center'><td style='width:33%'><img src='https://www.prounlockphone.com/images/clean" + data.clean + ".png' style='height:40px' /></td><td style='width:33%'><img src='https://www.prounlockphone.com/images/barred" + data.barred + ".png' style='height:40px' /></td><td style='width:33%'><img src='https://www.prounlockphone.com/images/blacklisted" + data.blacklisted + ".png' style='height:40px' /></td></tr></table>";
                $("#description").html(description);
                $("#delivery_time").html(data.delivery_time);
                $("#price").html(data.price);
                $("#details").html(data.details);
                $("#bulk_zone").css("display", "");
                $("#tips").html("Please try filling all fields or the processing will be delayed until we do it manually.").css("color", "black");
                $("#bulk").val("");
            });
        });
    });
    </script>
</head>
<body>
<?php
require_once('superheader.php');
?>
    <div align="center" style="margin-top:<?php
    require_once 'Mobile-Detect-2.8.22/Mobile_Detect.php';
    $detect = new Mobile_Detect;
    if($detect->isMobile()) {
        echo "180";
    } else {
        echo "60";
    }
    ?>px;width:100%">
        <div align="left" style="width:72%">
            <select id="theSelect" style="width:100%">
                <option></option>
                <?php
$rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, regular_USD, reseller_USD FROM services WHERE service_status = 1 AND delivery_time = 'Instant' ORDER BY service_group, service_name");
$optgroup = "";
while($row = mysqli_fetch_array($rows)) {
    if($optgroup != $row['service_group']) {
        if($optgroup != "") echo "              </optgroup>\n";
        $optgroup = $row['service_group'];
        echo "              <optgroup label='" . $row["service_group"] . "'>\n";
    }
    echo "                  <option value='{$row['id']}'>#{$row['id']} {$row['service_name']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$row['regular_USD']} | {$row['reseller_USD']}</option>\n";
}
?>
        </select>
        </div>
        <div id="order_content" style="display:none;width:72%;margin-top:30px;margin-bottom:50px">
            <table>
                <tr>
                    <td style="vertical-align:top;width:50%;padding-left:10px;padding-right:20px">
                        <p id="tips">Please try filling all fields or the processing will be delayed until we do it manually.</p>
                        <form id="order" method="post">
                            <input type="hidden" id="service" name="service" />
                            <fieldset><label class="titles" for="client">Client</label>
                                <select class="form-control" id="client" name="client" style="width:100%">
                                        <option></option>
<?php
$rows = mysqli_query($DB->Link, "SELECT id, first_name, last_name, username FROM users WHERE status = 'Active' ORDER BY first_name, last_name");
while($row = mysqli_fetch_array($rows)) {
    echo "                                        <option value='" . $row['id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . " (" . $row['username'] . ")</option>\n";
}
?>
                                </select>
                                <span id="bulk_zone">
                                    <label class="titles" for="id">Bulk Order (1 IMEI or SN per line, no space no extra character)</label>
                                    <textarea name="bulk" id="bulk" class="form-control" rows="5"></textarea>
                                </span>
                                <input class="form-control" style="height:40px;margin-top:30px" type="submit" value="Check" />
                            </fieldset>
                        </form>
                        <div id="server" style="display:none">
                            <pre style="word-break: keep-all;white-space: pre-wrap;width:100%;background-color: pink; border-color: red">Server Temporarly Down!
A notification was sent to the administrator.
Please allow some time to fix this issue and come back to check later.</pre>
                        </div>
                        <div id="low" style="display:none">
                            <pre style="word-break: keep-all;white-space: pre-wrap;width:100%;background-color: pink; border-color: red">Low balance in your account!
Add funds to your account then come back to place your checking orders (not free, all paid orders).
If you are a waived customer, you should know that this service is prepaid and requires having sufficient credits to run.
If you have any question concerning your account's status, feel free to contact the administrator.</pre>
                        </div>
                        <div id="results" style="display:none">
<pre style="word-break: keep-all;white-space: pre-wrap;width:100%">Model: IPHONE 5 BLACK 16GB GSM
IMEI Number: 013738001977032
Serial Number: DQGM1HKSDTWD
Find My iPhone: OFF
Coverage Status: Out Of Warranty (No Coverage)
Product Sold by: ORANGE SA.
Purchased In: France
Registered Purchase Date: 24/09/12
Replacement devices:
DQGM1HKSDTWD 013738001977032 Active 21/01/14
DQGL588YDTWD 013724000016576 Replaced 14/08/13
C39J8N6JDTWD 013345007485554 Original 14/08/13</pre>
                        </div>
                    </td>
                    <td style="vertical-align:top;width:50%;padding-left:15px;border-left:dashed 2px black;padding-top:0px">
                        <h2 id="service_name" style="margin-top:0px;color:blue;margin-bottom:-15px"></h2><hr />
                        <h3 id="description" style="margin-top:-15px"></h3>
                        <b style="color:blue">Delivery time:</b> <span id="delivery_time"></span><span style="float:right"><b style="color:blue">Price:</b> <span id="price"></span></span>
                        <p id="details" style="margin-top:10px"></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div id="Panel1" class="overlayer" style="display:none">
        <div id="Panel2" class="loading">
            <div>
                <img id="imgProcessingMaster" class="processing" src="https://www.prounlockphone.com/images/process.gif" alt="Processing" />
            </div>
        </div>
    </div>
</body>
</html>