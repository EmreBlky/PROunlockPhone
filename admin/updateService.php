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
    <?php echo admin_common_head_with_title("Update Service", "20", true) ?>
    <script src="scripts/updateService.js" type="text/javascript"></script>
    <link rel="stylesheet" href="style/service.css" />
</head>
<body>
<?php
require_once('superheader.php');
?>
    <div align="center" style="margin-top:<?php
    require_once 'Mobile-Detect-2.8.22/Mobile_Detect.php';
    $detect = new Mobile_Detect;
    if($detect->isMobile()) {
        echo "200";
    } else {
        echo "80";
    }
    ?>px;width:100%">
        <h3>Updating services<a href='updateService.php?all=yes' style="margin-left:30px">all services</a></h3>
        <div id="success_div" style="display:none;margin-bottom:10px;width:72%;border:solid 1px green;border-radius:5px;background-color: ghostwhite;color:green"><h3 id="success">Service successfully update</h3></div>
        <div id="failure_div" style="display:none;margin-bottom:10px;width:72%;border:solid 2px red;border-radius:5px;background-color:pink;color:red"><h3 id="failure"></h3></div>
        <div align="left" style="width:72%">
            <select id="theSelect" style="width:100%">
                <option value=""></option>
<?php
if((isset($_GET['all']) and $_GET['all'] == 'yes') or (isset($_GET['service']) and $_GET['service'] != "")) {
    $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, service_status FROM services ORDER BY service_group, service_name");
} else {
    $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, service_status FROM services WHERE service_status = '1' ORDER BY service_group, service_name");
}
$optgroup = "";
while($row = mysqli_fetch_array($rows)) {
    if($optgroup != $row['service_group']) {
        if($optgroup != "") echo "              </optgroup>\n";
        $optgroup = $row['service_group'];
        echo "              <optgroup label='" . $row["service_group"] . "'>\n";
    }
    echo "                  <option value='" . $row['id'] . "'>#" . $row['id'] . " " . $row['service_name'] . " [" . ($row['service_status'] == 1? "ON" : "off") . "]</option>\n";
}
?>
                </optgroup>
            </select>
        </div>
        <div id="serviceDetails" style="display:none;font-size:20px;text-align:left;width:72%;margin-top:10px;margin-bottom:50px">
            <form id="service" method="post">
                <fieldset>
                    <label class="titles" for="service_status">Privileged users: </label>
                    <table id="listPrivilege" border="solid 1px black" style="width:100%">
                    </table>
                    <input type="hidden" id="id" name="id" />
                    
                    <label class="titles" for="service_status">Service status</label>
                    <table>
                        <tr>
                            <td style="width: 150px;"><input id="active" type="radio" name="service_status" style="margin-left: 30px;" value="1" /> Active</td>
                        </tr>
                        <tr>
                            <td><input id="standby" type="radio" name="service_status" style="margin-left: 30px;" value="0" /> Stand By</td>
                        </tr>
                    </table>
                    
                    <label class="titles" for="service_name">Service label</label>
                    <input class="form-control" id="service_name" name="service_name" type="text" />
                    
                    <label class="titles" for="short_name">Short name<i id="suggest-short" style="margin-left: 20px;text-decoration:underline;font-size: 12px;cursor: pointer;">suggest</i></label>
                    <input class="form-control" id="short_name" name="short_name" type="text" />
                    <select class="form-control" id="short-list" style="display:none;margin-top: 20px;">
<?php
$rows = mysqli_query($DB->Link, "SELECT DISTINCT short_name FROM services ORDER BY short_name");
while($row = mysqli_fetch_array($rows)) {
    echo "                      <option value='" . $row['short_name'] . "'>" . $row['short_name'] . "</option>\n";
}
?>
                    </select>
                    
                    <label class="titles" for="service_group">Service group<i id="suggest-group" style="margin-left: 20px;text-decoration:underline;font-size: 12px;cursor: pointer;">suggest</i></label>
                    <input class="form-control" id="service_group" name="service_group" type="text" />
                    <select class="form-control" id="group-list" style="display:none;margin-top: 20px;">
<?php
$rows = mysqli_query($DB->Link, "SELECT DISTINCT service_group FROM services ORDER BY service_group");
while($row = mysqli_fetch_array($rows)) {
    echo "                      <option value='" . $row['service_group'] . "'>" . $row['service_group'] . "</option>\n";
}
?>
                    </select>
                    
                    <label class="titles" for="delivery_time">Delivery time</label>
                    <table>
                        <tr>
                            <td style="width: 150px"><div class="radiobox"><label><input type="radio" name="delivery_time" id="instant" style="margin-left: 30px;" value="Instant" /> Instant</label></div></td>
                        </tr>
                        <tr>
                            <td><div class="radiobox"><label><input type="radio" name="delivery_time" id="minutes" style="margin-left: 30px;" value="5 Minutes" /> Minutes</label></div></td>
                            <td>
                                <select class="form-control" id="minutes-select">
                                    <option value="5 Minutes">5</option>
                                    <option value="10 Minutes">10</option>
                                    <option value="15 Minutes">15</option>
                                    <option value="20 Minutes">20</option>
                                    <option value="30 Minutes">30</option>
                                    <option value="45 Minutes">45</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><div class="radiobox"><label><input type="radio" name="delivery_time" id="hours" style="margin-left: 30px;" value="1 Hour" /> Hours</label></div></td>
                            <td>
                                <select class="form-control" id="hours-select">
                                    <option value="1 Hour">1</option>
                                    <option value="2 Hours">2</option>
                                    <option value="3 Hours">3</option>
                                    <option value="6 Hours">6</option>
                                    <option value="12 Hours">12</option>
                                    <option value="24 Hours">24</option>
                                    <option value="36 Hours">36</option>
                                    <option value="48 Hours">48</option>
                                    <option value="72 Hours">72</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><div class="radiobox"><label><input type="radio" checked="true" name="delivery_time" id="days" style="margin-left: 30px;" value="1 Day" /> Days</label></div></td>
                            <td>
                                <select class="form-control" id="days-select">
                                    <option value="1 Day">1</option>
                                    <option value="2 Days">2</option>
                                    <option value="3 Days">3</option>
                                    <option value="4 Days">4</option>
                                    <option value="5 Days">5</option>
                                    <option value="6 Days">6</option>
                                    <option value="7 Days">7</option>
                                    <option value="8 Days">8</option>
                                    <option value="9 Days">9</option>
                                    <option value="10 Days">10</option>
                                    <option value="12 Days">12</option>
                                    <option value="14 Days">14</option>
                                    <option value="15 Days">15</option>
                                    <option value="18 Days">18</option>
                                    <option value="21 Days">21</option>
                                    <option value="25 Days">25</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    
                    <label class="titles" for="description">Description</label>
                    <textarea rows="3" class="form-control" id="description" name="description" class="textarea ui-widget-content ui-corner-all"></textarea>
                    
                    <label class="titles" for="details">Details</label>
                    <textarea rows="7" class="form-control" id="details" name="details" class="textarea ui-widget-content ui-corner-all" style="display:none"></textarea>
                    <div id="dtls"></div>
                    
                    <label class="titles">Required Fields</label>
                    <table style="width: 100%; font-size:20px; margin-top: 10px;" cellpadding="10px">
                        <tr>
                            <td style="width: 33%"><div class="checkbox"><label><input id="imei" name="imei" type="checkbox" /> IMEI required</label></div></td>
                            <td style="width: 33%"><div class="checkbox"><label><input id="sn" name="sn" type="checkbox" /> S/N required</label></div></td>
                            <td style="width: *"><div class="checkbox"><label><input id="bulk" name="bulk" type="checkbox" /> Bulk accepted</label></div></td>
                        </tr>
                        <tr>
                            <td><div class="checkbox"><label><input id="phone" name="phone" type="checkbox" /> Phone required</label></div></td>
                            <td><div class="checkbox"><label><input id="account" name="account" type="checkbox" /> Account required</label></div></td>
                            <td><div class="checkbox"><label><input id="udid" name="udid" type="checkbox" /> UDID required</label></div></td>
                        </tr>
                        <tr>
                            <td><div class="checkbox"><label><input id="status_mode" name="status_mode" type="checkbox" /> Clean / Blacklisted specification</label></div></td>
                            <td><div class="checkbox"><label><input id="photo" name="photo" type="checkbox" /> Picture required</label></div></td>
                            <td><div class="checkbox"><label><input id="itools" name="itools" type="checkbox" /> iTools required</label></div></td>
                        </tr>
                        <tr>
                            <td><div class="checkbox"><label><input id="backupData" name="backupData" type="checkbox" /> Backup Data</label></div></td>
                            <td><div class="checkbox"><label><input id="videoLink" name="videoLink" type="checkbox" /> Video Link</label></div></td>
                            <td><div class="checkbox"><label><input id="fileLink" name="fileLink" type="checkbox" /> File Link</label></div></td>
                        </tr>
                    </table>
                    
                    <label class="titles">Service cost</label>
                    <table id="priceInEur" style="display:none;text-align:center; width: 80%; font-size:20px; margin-top: 10px;">
                        <tr>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%" rowspan="2">Original Price<br />~ EUR ~<br /><input id="xrate" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="0.8207" /></td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 40%" colspan="2">Price + (10% :: 15%) only</td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 40%" colspan="2">Price + (10% :: 15%) + 5%</td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%">Reseller</td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%">Regular</td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%">Reseller</td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%">Regular</td>
                        </tr>
                        <tr>
                            <td style="border:solid 2px black"><input id="org_priceEUR" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center"><span id="price_10oEUR">00.00</span></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center"><span id="price_15oEUR">00.00</span></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center"><span id="price_5_10EUR">00.00</span></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center"><span id="price_5_15EUR">00.00</span></td>
                        </tr>
                    </table>
                    <table style="text-align:center; width: 80%; font-size:20px; margin-top: 10px;">
                        <tr>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%" rowspan="2">Original Price<br />~ USD ~<br /><a style="font-size:14px;cursor:pointer" onclick="$('#priceInEur').toggle('slow')">click here to show in EUR</a></td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 40%" colspan="2">Price + (10% :: 15%) only</td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 40%" colspan="2">Price + (10% :: 15%) + 5%</td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%">Reseller</td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%">Regular</td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%">Reseller</td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 20%">Regular</td>
                        </tr>
                        <tr>
                            <td style="border:solid 2px black"><input id="org_price" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center"><span id="price_10o">00.00</span></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center"><span id="price_15o">00.00</span></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center"><span id="price_5_10">00.00</span></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center"><span id="price_5_15">00.00</span></td>
                        </tr>
                    </table>
                    <table style="text-align:center; width: 80%; font-size:20px; margin-top: 10px;">
                        <tr>
                            <td style="padding: 10px; width: 10%"></td>
                            <td style="color:darkblue; border:solid 2px black; padding: 10px; width: 30%">Reseller</td>
                            <td style="color:darkblue;border:solid 2px black; padding: 10px; width: 30%">Regular</td>
                            <td style="color:darkblue;border:solid 2px black; padding: 10px; width: 30%">Converter<br />
                                <a id="set0" style="margin-left:10px;cursor:pointer">0%</a>
                                <a id="set5" style="margin-left:10px;cursor:pointer">5%</a>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black;">USD</td>
                            <td style="border:solid 2px black;"><input id="reseller_USD" name="reseller_USD" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;"><input id="regular_USD" name="regular_USD" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center" /><span id="USD">--.--</span></td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black;">EUR</td>
                            <td style="border:solid 2px black;"><input id="reseller_EUR" name="reseller_EUR" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;"><input id="regular_EUR" name="regular_EUR" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center" /><span id="EUR">--.--</span></td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black;">GBP</td>
                            <td style="border:solid 2px black;"><input id="reseller_GBP" name="reseller_GBP" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;"><input id="regular_GBP" name="regular_GBP" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center" /><span id="GBP">--.--</span></td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black;">TND</td>
                            <td style="border:solid 2px black;"><input id="reseller_TND" name="reseller_TND" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;"><input id="regular_TND" name="regular_TND" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                            <td style="border:solid 2px black;color: crimson; font-size:24px;text-align: center" /><span id="TND">--.--</span></td>
                        </tr>
                    </table>
                    
                    <label class="titles" for="provider">Provider<i id="suggest-provider" style="margin-left: 20px;text-decoration:underline;font-size: 12px;cursor: pointer;">suggest</i></label>
                    <input style="margin-bottom: 10px;" class="form-control" id="provider" name="provider" type="text" />
                    <select class="form-control" id="provider-list" style="display:none;margin-top: 20px;">
<?php
$rows = mysqli_query($DB->Link, "SELECT DISTINCT provider FROM services ORDER BY provider");
while($row = mysqli_fetch_array($rows)) {
    echo "                      <option value='" . $row['provider'] . "'>" . $row['provider'] . "</option>\n";
}
?>
                    </select>
                    Provider's service details: <input class="form-control" id="provider_details" name="provider_details" type="text" style="width: 100%;" />
                    Original price (USD): <input class="form-control" id="originalPrice" name="originalPrice" type="text" style="color:crimson;width: 100%;" />
                    
                    <label class="titles" for="country">Country</label>
                    <select class="form-control" id="country" name="country">
                        <option value='Multi'>International / Multi</option>
                        <option value="Multi" disabled="true">-----------------------------------------------------------------------------------------</option>
<?php
$rows = mysqli_query($DB->Link, "SELECT country_code, english_name FROM countries WHERE country_code <> '' ORDER BY english_name");
while($row = mysqli_fetch_array($rows)) {
    echo "                      <option value='" . $row['country_code'] . "'>" . $row['english_name'] . "</option>\n";
}
?>
                    </select>
                    
                    <label class="titles" for="manufacturer">Manufacturer</label>
                    <select class="form-control" id="manufacturer" name="manufacturer">
                        <option value=""></option>
                        <option value='Alcatel'>Alcatel</option>
                        <option value='Apple'>Apple</option>
                        <option value='BlackBerry'>BlackBerry</option>
                        <option value='Dell'>Dell</option>
                        <option value='Generic'>Generic</option>
                        <option value='Google'>Google</option>
                        <option value='HTC'>HTC</option>
                        <option value='Huawei'>Huawei</option>
                        <option value='Lenovo'>Lenovo</option>
                        <option value='LG'>LG</option>
                        <option value='Mototrola'>Mototrola</option>
                        <option value='Multi'>Multi</option>
                        <option value='Nokia'>Nokia</option>
                        <option value='Pantech'>Pantech</option>
                        <option value='Samsung'>Samsung</option>
                        <option value='Sony'>Sony</option>
                        <option value='ZTE'>ZTE</option>
                    </select>
                    
                    <label class="titles" for="carrier">Carrier</label>
                    <select class="form-control" id="carrier" name="carrier">
                        <option value='Multi'>Multi</option>
                        <option value='Apple'>Apple</option>
                        <option value='Generic'>Generic</option>
                        <option value="Multi" disabled="true">-----------------------------------------------------------------------------------------</option>
<?php
$rows = mysqli_query($DB->Link, "SELECT DISTINCT carrier FROM services WHERE carrier not in ('Multi', 'Apple', 'Generic') ORDER BY carrier");
while($row = mysqli_fetch_array($rows)) {
    echo "                      <option value='" . $row['carrier'] . "'>" . $row['carrier'] . "</option>\n";
}
?>
                    </select>
                    
                    <label class="titles" for="models">Models concerned<i id="suggest-models" style="margin-left: 20px;text-decoration:underline;font-size: 12px;cursor: pointer;">suggest</i></label>
                    <input class="form-control" id="models" name="models" type="text" />
                    <select class="form-control" id="models-list" style="display:none;margin-top: 20px;">
<?php
$rows = mysqli_query($DB->Link, "SELECT DISTINCT models FROM services ORDER BY models");
while($row = mysqli_fetch_array($rows)) {
    echo "                      <option value='" . $row['models'] . "'>" . $row['models'] . "</option>\n";
}
?>
                    </select>
                    
                    <label class="titles">ESN status</label>
                    <table style="width: 100%; font-size:20px; margin-top: 10px;" cellpadding="10px">
                        <tr>
                            <td style="width: 33%;"><div class="checkbox"><label><input id="clean" name="clean" type="checkbox" /> Clean</label></div></td>
                            <td style="width: 33%;"><div class="checkbox"><label><input id="barred" name="barred" type="checkbox" /> Barred</label></div></td>
                            <td style="width:*"><div class="checkbox"><label><input id="blacklisted" name="blacklisted" type="checkbox" /> Blacklisted</label></div></td>
                        </tr>
                    </table>
                    <label class="titles">Success Rate</label>
                    <div id="slider"></div>
                    <b id="visual_success_rate" style="color:green;margin:10px">100%</b>
                    <input id="success_rate" name="success_rate" type="hidden" />
                </fieldset>
                <input class="form-control" style="height:40px;margin-top:30px" type="submit" value="Update Service" />
            </form>
        </div>
    </div>
    <div id="Panel1" class="overlayer" style="display:none">
        <div id="Panel2" class="loading">
            <div>
                <img id="imgProcessingMaster" class="processing" src="https://www.prounlockphone.com/images/process.gif" alt="Processing" />
            </div>
        </div>
    </div>
<script>
    $(function(){
        $("#slider").slider({
            mim: 10,
            max: 100,
            step: 5,
            value: 100,
            change: function( event, ui ) {
                $('#success_rate').val(ui.value);
                $('#visual_success_rate').html(ui.value + '%').css('color', ui.value < 50 ? 'red' : ui.value < 80 ? 'orange' : ui.value < 90 ? 'blue' : 'green');
            }
        });
        $('#theSelect').select2({
            placeholder: "Choose the service...",
            theme: "classic"
        }).change(function() {
            $("#id").val($(this).val());
            $.get('getListPrivilege.php?id=' + $(this).val(), function(response) {
                $("#listPrivilege").html(response);
            });
            $.getJSON("getServiceDetails.php?service=" + $(this).val(), function(data) {
                $("#serviceDetails").show();
                $('input:radio[name=service_status]').filter('[value=' + data.service_status + ']').prop('checked', true);
                $("#service_name").val(data.service_name);
                $("#short_name").val(data.short_name);
                $("#short-list").val(data.short_name);
                $("#service_group").val(data.service_group);
                $("#group-list").val(data.service_group);
                if(data.delivery_time == "Instant") {
                    $('input:radio[name=delivery_time]').filter('[value=Instant]').prop('checked', true);
                } else if(data.delivery_time.search("Minutes") != -1) {
                    $('input:radio[name=delivery_time]').filter('[id=minutes]').prop('checked', true).val(data.delivery_time);
                    $("#minutes-select").val(data.delivery_time);
                } else if(data.delivery_time.search("Hour") != -1) {
                    $('input:radio[name=delivery_time]').filter('[id=hours]').prop('checked', true).val(data.delivery_time);
                    $("#hours-select").val(data.delivery_time);
                } else {
                    $('input:radio[name=delivery_time]').filter('[id=days]').prop('checked', true).val(data.delivery_time);
                    $("#days-select").val(data.delivery_time);
                }
                $("#description").val(data.description);
                $("#details").val(data.details);
                $("#dtls").summernote({
                    toolbar: [
                        // [groupName, [list of button]]
                        ['fontname', ['fontname']],
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['view', ['codeview']],
                    ],
                    minHeight: 200
                });
                $("#dtls").summernote('code', data.details);
                $("#imei").prop("checked", data.imei == "1" ? true : false);
                $("#sn").prop("checked", data.sn == "1" ? true : false);
                $("#bulk").prop("checked", data.bulk == "1" ? true : false);
                $("#phone").prop("checked", data.phone == "1" ? true : false);
                $("#account").prop("checked", data.account == "1" ? true : false);
                $("#udid").prop("checked", data.udid == "1" ? true : false);
                $("#status_mode").prop("checked", data.status_mode == "1" ? true : false);
                $("#photo").prop("checked", data.photo == "1" ? true : false);
                $("#itools").prop("checked", data.itools == "1" ? true : false);
                $("#backupData").prop("checked", data.backupData == "1" ? true : false);
                $("#videoLink").prop("checked", data.videoLink == "1" ? true : false);
                $("#fileLink").prop("checked", data.fileLink == "1" ? true : false);
                $("#reseller_USD").val(data.reseller_USD);
                $("#regular_USD").val(data.regular_USD);
                $("#reseller_EUR").val(data.reseller_EUR);
                $("#regular_EUR").val(data.regular_EUR);
                $("#reseller_GBP").val(data.reseller_GBP);
                $("#regular_GBP").val(data.regular_GBP);
                $("#reseller_TND").val(data.reseller_TND);
                $("#regular_TND").val(data.regular_TND);
                $("#provider").val(data.provider);
                $("#provider-list").val(data.provider);
                $("#provider_details").val(data.provider_details);
                $("#country").val(data.country);
                $("#manufacturer").val(data.manufacturer);
                $("#carrier").val(data.carrier);
                $("#models").val(data.models);
                $("#models-list").val(data.models);
                $("#clean").prop("checked", data.clean == "1" ? true : false);
                $("#barred").prop("checked", data.barred == "1" ? true : false);
                $("#blacklisted").prop("checked", data.blacklisted == "1" ? true : false);
                $("#originalPrice").val(data.originalPrice);
                $("#org_price").val(data.originalPrice);
                $("#org_price").keyup();
                $("#slider").slider('value', data.success_rate);
                $('#visual_success_rate').html(data.success_rate + '%').css('color', data.success_rate < 50 ? 'red' : data.success_rate < 80 ? 'orange' : data.success_rate < 90 ? 'blue' : 'green');
                $("#success_rate").val(data.success_rate);
            });
        }).on('select2:open',function(){
            if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
            }
        });
        <?php
        if(isset($_GET['service']) and $_GET['service'] != "") {
            echo "$('#theSelect').val('{$_GET['service']}').trigger('change');";
        }
        ?>
    });
</script>
</body>
</html>