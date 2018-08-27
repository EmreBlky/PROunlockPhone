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
    <?php echo admin_common_head_with_title("Add Service", 20, true) ?>
    <script src="scripts/service.js" type="text/javascript"></script>
    <link rel="stylesheet" href="style/service.css" />
    <script>
        $(function() {
            $("#details").summernote({
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
        });
    </script>
</head>
<body>
<?php
require_once('superheader.php');
?>
    <div align="center" style="margin-top:80px;width:100%">
        <div id="success_div" style="display:none;margin-bottom:10px;width:72%;border:solid 1px green;border-radius:5px;background-color: ghostwhite;color:green"><h3>Service added :: new ID <span id="success"></span></h3></div>
        <div id="failure_div" style="display:none;margin-bottom:10px;width:72%;border:solid 2px red;border-radius:5px;background-color:pink;color:red"><h3 id="failure"></h3></div>
        <h3>Adding new service</h3>
        <div style="font-size:20px;text-align:left;width:72%;margin-top:10px;margin-bottom:50px">
            <form id="service" method="post">
                <fieldset>
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
                            <td style="width: 150px;"><div class="radiobox"><label><input type="radio" name="delivery_time" id="instant" style="margin-left: 30px" value="Instant" /> Instant</label></div></td>
                        </tr>
                        <tr>
                            <td><div class="radiobox"><label><input type="radio" name="delivery_time" id="minutes" style="margin-left: 30px" value="5 Minutes" /> Minutes</label></div></td>
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
                            <td><div class="radiobox"><label><input type="radio" name="delivery_time" id="hours" style="margin-left: 30px" value="1 Hour" /> Hours</label></div></td>
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
                            <td><div class="radiobox"><label><input type="radio" checked="true" name="delivery_time" id="days" style="margin-left: 30px" value="1 Day" /> Days</label></div></td>
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
                    <textarea rows="7" class="form-control" id="details" name="details" class="textarea ui-widget-content ui-corner-all"></textarea>
                    
                    <label class="titles">Required Fields</label>
                    <table style="width: 100%; font-size:20px; margin-top: 10px;" cellpadding="10px">
                        <tr>
                            <td style="width: 33%"><div class="radiobox"><label><input name="imei" type="checkbox" /> IMEI required</label></div></td>
                            <td style="width: 33%"><div class="radiobox"><label><input name="sn" type="checkbox" /> S/N required</label></div></td>
                            <td style="width: *"><div class="radiobox"><label><input name="bulk" type="checkbox" /> Bulk accepted</label></div></td>
                        </tr>
                        <tr>
                            <td><div class="radiobox"><label><input name="phone" type="checkbox" /> Phone required</label></div></td>
                            <td><div class="radiobox"><label><input name="account" type="checkbox" /> Account required</label></div></td>
                            <td><div class="radiobox"><label><input name="udid" type="checkbox" /> UDID required</label></div></td>
                        </tr>
                        <tr>
                            <td><div class="radiobox"><label><input name="status_mode" type="checkbox" /> Clean / Blacklisted</label></div></td>
                            <td><div class="radiobox"><label><input name="photo" type="checkbox" /> Picture required</label></div></td>
                            <td><div class="radiobox"><label><input name="itools" type="checkbox" /> iTools required</label></div></td>
                        </tr>
                        <tr>
                            <td><div class="radiobox"><label><input name="backup_link" type="checkbox" /> Backup Link</label></div></td>
                            <td><div class="radiobox"><label><input name="video_link" type="checkbox" /> Video Link</label></div></td>
                            <td><div class="radiobox"><label><input name="file_link" type="checkbox" /> File Link</label></div></td>
                        </tr>
                    </table>
                    
                    <label class="titles">Service cost</label>
                    <table style="text-align:center; width: 50%; font-size:20px; margin-top: 10px;">
                        <tr>
                            <td style="padding: 10px; width: 20%"></td><td style="color:darkblue; border:solid 2px black; padding: 10px; width: 40%">Reseller</td><td style="border:solid 2px black; padding: 10px; width: 40%">Regular</td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black;">USD</td><td style="border:solid 2px black;"><input name="reseller_USD" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td><td style="border:solid 2px black;"><input name="regular_USD" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black;">EUR</td><td style="border:solid 2px black;"><input name="reseller_EUR" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td><td style="border:solid 2px black;"><input name="regular_EUR" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black;">GBP</td><td style="border:solid 2px black;"><input name="reseller_GBP" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td><td style="border:solid 2px black;"><input name="regular_GBP" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
                        </tr>
                        <tr>
                            <td style="color:darkblue; border:solid 2px black;">TND</td><td style="border:solid 2px black;"><input name="reseller_TND" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td><td style="border:solid 2px black;"><input name="regular_TND" style="color: crimson; font-size:24px;text-align: center;width:100%;height: 100%;" type="text" value="00.00" /></td>
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
                    Provider's service details: <input class="form-control" name="provider_details" type="text" style="width: 100%;" />
                    Original price (USD): <input class="form-control" name="originalPrice" type="text" value="00.00" style="color:crimson;width: 100%;" />
                    
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
                        <option value="Multi">Multi</option>
                        <option value="Multi" disabled="true">-----------------------------------------------------------------------------------------</option>
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
                            <td style="width: 33%"><div class="radiobox"><label><input name="clean" type="checkbox" /> Clean</label></div></td>
                            <td style="width: 33%"><div class="radiobox"><label><input name="barred" type="checkbox" /> Barred</label></div></td>
                            <td style="width:*"><div class="radiobox"><label><input name="blacklisted" type="checkbox" /> Blacklisted</label></div></td>
                        </tr>
                    </table>
                </fieldset>
                <input class="form-control" style="height:40px;margin-top:30px" type="submit" value="Add Service" />
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
</body>
</html>