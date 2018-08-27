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
    <?php echo admin_common_head_with_title("Place Order", "20") ?>
    <script src="scripts/superplace.js" type="text/javascript"></script>
    <link rel="stylesheet" href="style/place.css" />
    <script>
    $(function(){
        $('#client').select2({
            placeholder: "Choose the client...",
            theme: "classic",
            allowClear: true
        }).on('change', function() {
            if($(this).val() != null && $(this).val() != "58") {
                $('#eMailNotification').prop('checked', true);
                $('#SMSNotification').prop('checked', false);
            } else {
                $('#eMailNotification').prop('checked', false);
                $('#SMSNotification').prop('checked', false);
            }
        });
        $('#theSelect').select2({
            placeholder: "Choose your service...",
            theme: "classic"
        }).change(function() {
            $("#service").val($(this).val());
            $.getJSON("superget_service_details.php?service=" + $(this).val(), function(data) {
                $("#order_content").show();
                $("#service_name").html(data.service_name);
                var description = data.description;
                var table = "";
                /*if(data.models != "") {
                    var models = data.models.split("-");
                    table = "<br /><table style='width:100%'><tr style='text-align:center'>";
                    for(var i = 0; i < models.length; i++) {
                        table += "<td style='width:" + Math.round(100 / models.length) + "%'><img src='images/" + models[i] + ".png' style='width:100%;max-width:80px' /></td>";
                    }
                    table += "</tr><tr style='text-align:center'>";
                    for(var i = 0; i < models.length; i++) {
                        table += "<td>" + models[i] + "</td>";
                    }
                    table += "</tr></table>";
                    description += table;
                }*/
                description += "<br /><table cellpadding='10px' style='width:100%'><tr style='text-align:center'><td style='width:33%'><img src='https://www.prounlockphone.com/images/clean" + data.clean + ".png' style='height:40px' /></td><td style='width:33%'><img src='https://www.prounlockphone.com/images/barred" + data.barred + ".png' style='height:40px' /></td><td style='width:33%'><img src='https://www.prounlockphone.com/images/blacklisted" + data.blacklisted + ".png' style='height:40px' /></td></tr></table>";
                $("#description").html(description);
                $("#delivery_time").html(data.delivery_time);
                $("#price").html(data.price);
                $("#details").html(data.details);
                $("#imei_zone").css("display", data.imei == "1" ? "" : "none");
                $("#bulk_zone").css("display", data.bulk == "1" ? "" : "none");
                $("#sn_zone").css("display", data.sn == "1" ? "" : "none");
                $("#udid_zone").css("display", data.udid == "1" ? "" : "none");
                $("#account_zone").css("display", data.account == "1" ? "" : "none");
                $("#photo_zone").css("display", data.photo == "1" ? "" : "none");
                $("#phone_zone").css("display", data.phone == "1" ? "" : "none");
                $("#status_zone").css("display", data.status_mode == "1" ? "" : "none");
                $("#itools_zone").css("display", data.itools == "1" ? "" : "none");
                $("#bulk").val("");
                $("#tips").html("Please try filling all fields or the processing will be delayed until we do it manually.").css("color", "black");
                $("#imei").css({
                    "color": "red",
                    "border": "solid 1px gray"
                }).val("");
                $("#last_digit").val("");
                $("#serial").css({
                    "color": "black",
                    "border": "solid 1px gray"
                }).val("");
                $("#udid").css({
                    "color": "black",
                    "border": "solid 1px gray"
                }).val("");
                $("#account").css({
                    "color": "black",
                    "border": "solid 1px gray"
                }).val("");
                $("#photo").css({
                    "border": "solid 1px gray"
                }).val("");
                $("#phone").css({
                    "color": "black",
                    "border": "solid 1px gray"
                }).val("");
                $("#itools").css({
                    "color": "black",
                    "border": "solid 1px gray"
                }).val("");
                $("#bulk").val("");
                $("#image_preview").hide();
                $("#client_personal_notes").val("");
                $("#client_order_comments").val("");
                $("#ebayer").val("");
                $("#tracker").val("");
                $("#owner_name").val("");
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
        <div id="success_div" style="display:none;margin-bottom:10px;width:72%;border:solid 1px green;border-radius:5px;background-color: ghostwhite;color:green"><h3>Order Placed Successfully :: Order ID <span id="success"></span></h3></div>
        <div id="failure_div" style="display:none;margin-bottom:10px;width:72%;border:solid 2px red;border-radius:5px;background-color:pink;color:red"><h3>Order Failure :: Low balance, add funds to your account first</h3></div>
        <div align="left" style="width:72%">
            <select id="theSelect" style="width:100%">
                <option value=""></option>
<?php
$rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, regular_USD, reseller_USD FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' ORDER BY service_group, service_name");
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
                </optgroup>
            </select>
        </div>
        <div id="order_content" style="display:none;width:72%;margin-top:30px;margin-bottom:50px">
            <table>
                <tr>
                    <td style="vertical-align:top;width:50%;padding-left:10px;padding-right:20px">
                        <div>
                            <p id="tips">Please try filling all fields or the processing will be delayed until we do it manually.</p>
                            <form id="order" method="post">
                                <input type="hidden" id="service" name="service" />
                                <fieldset>
                                    <label class="titles" for="client">Client</label>
                                    <select class="form-control" id="client" name="client" style="width:100%">
                                        <option value='58'></option>
<?php
$rows = mysqli_query($DB->Link, "SELECT id, first_name, last_name, username FROM users WHERE status = 'Active' ORDER BY first_name, last_name");
while($row = mysqli_fetch_array($rows)) {
    echo "                                        <option value='" . $row['id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . " (" . $row['username'] . ")</option>\n";
}
?>
                                    </select>
                                    <span id="imei_zone">
                                        <label class="titles" for="imei">IMEI</label>
                                        <input class="form-control" style="display:inline;text-align:center;width:86%;color:red;" maxlength="14" type="text" name="imei" id="imei" />
                                        <input class="form-control" style="width:10%;display:inline;color:green;text-align:center;padding:0px" id="last_digit" name="last_digit" type="text" readonly="true" />
                                    </span>
                                    <span id="bulk_zone">
                                        <label class="titles" for="id">Bulk Order</label>
                                        <textarea name="bulk" id="bulk" class="form-control" rows="5" style="text-transform: uppercase"></textarea>
                                    </span>
                                    <span id="sn_zone">
                                        <label class="titles" for="serial">Serial Number</label>
                                        <input class="form-control" id="serial" name="serial" type="text" style="text-transform: uppercase" maxlength="12" />
                                    </span>
                                    <span id="udid_zone">
                                        <label class="titles" for="udid">UDID</label>
                                        <input class="form-control" id="udid" name="udid" type="text" style="text-transform: lowercase" maxlength="40" />
                                    </span>
                                    <span id="phone_zone">
                                        <label class="titles" for="phone">Phone Number</label>
                                        <input class="form-control" id="phone" name="phone" type="text" style="text-transform: lowercase" maxlength="40" />
                                    </span>
                                    <span id="account_zone">
                                        <label class="titles" for="account">Apple Account</label>
                                        <input class="form-control" id="account" name="account" type="email" />
                                    </span>
                                    <span id="status_zone">
                                        <label class="titles" for="status_mode">iCloud Status</label>
                                        <select class="form-control" id="status_mode" name="status_mode">
                                            <option value="L">Lost</option>
                                            <option value="LE">Lost & Erased</option>
                                            <option value="S">Stolen</option>
                                            <option value="C">Clean</option>
                                        </select>
                                    </span>
                                    <span id="photo_zone">
                                        <label class="titles" for="photo">Attached photo</label>
                                        <input class="form-control" type="file" id="photo" name="photo" accept="image/*" />
                                        <img align="center" id="image_preview" src="" alt="" style="border:solid 1px gray;margin-top:20px;display:none;max-width:285px;max-height:900px" />
                                    </span>
                                    <span id="itools_zone">
                                        <label class="titles" for="itools">iTools Info</label>
                                        <textarea rows="5" class="form-control" id="itools" name="itools" class="textarea ui-widget-content ui-corner-all"></textarea>
                                    </span>
                                    <label class="titles" for="client_order_comments">Notes for us</label>
                                    <textarea rows="5" class="form-control" id="client_order_comments" name="client_order_comments" class="textarea ui-widget-content ui-corner-all"></textarea>
                                    <label class="titles" for="owner_name">Owner's Name</label>
                                    <input class="form-control" id="owner_name" name="owner_name" type="text" />
                                    <label class="titles" for="ebayer">eBayer</label>
                                    <input class="form-control" id="ebayer" name="ebayer" type="text" />
                                    <label class="titles" for="tracker">Tracker</label>
                                    <input class="form-control" id="tracker" name="tracker" type="email" />
                                    <label class="titles" for="client_personal_notes">Personal notes</label>
                                    <textarea rows="5" class="form-control" id="client_personal_notes" name="client_personal_notes" class="textarea ui-widget-content ui-corner-all"></textarea>
                                    <div class="checkbox"><label><input id="eMailNotification" name="eMailNotification" type="checkbox" /> Notify him by eMail</label><label style="float:right"><input id="SMSNotification" name="SMSNotification" type="checkbox" /> Notify him by SMS (0.10/SMS)</label></div>
                                    <input class="form-control" style="height:40px;margin-top:30px" type="submit" value="Place Order" />
                                </fieldset>
                            </form>
                        </div>
                    </td>
                    <td style="vertical-align:top;width:50%;padding-left:15px;border-left:dashed 2px black;padding-top:0px">
                        <h2 id="service_name" style="margin-top:0px;color:blue;margin-bottom:-15px"></h2><hr />
                        <h3 id="description" style="margin-top:-15px"></h3>
                        <b style="color:blue">Delivery time:</b> <span id="delivery_time"></span>
                        <span style="float:right"><b style="color:blue">Price:</b> <span id="price"></span></span>
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