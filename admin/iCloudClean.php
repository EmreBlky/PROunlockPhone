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
    <?php echo admin_common_head_with_title("iCloud Clean", "20") ?>
    <link rel="stylesheet" href="style/service.css" />
    <style type="text/css">
        #supported th,td {
            padding: 5px;
        }
        .remover {
            cursor: pointer;
        }
    </style>
    <script>
    function drop_country(combination) {
        $.ajax({
            type: "POST",
            url: "injectiCloudClean.php",
            data: 'service=' + $('#theSelect').val() + '&action=drop&combination=' + combination,
            success: function (response) {
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                if(response.substr(0, 7) == "FAILURE") {
                    $("#failure").html("Update procedure failed :: " + response.substr(7));
                    $("#failure_div").slideDown("slow");
                    setTimeout(function() {
                        $("#failure_div").slideUp("slow");
                    }, 3000);
                } else {
                    $("#supported").html(response);
                    $("#success_div").slideDown("slow");
                    setTimeout(function() {
                        $("#success_div").slideUp("slow");
                    }, 3000);
                }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
    }

    function drop_store(store) {
        $.ajax({
            type: "POST",
            url: "injectiCloudClean.php",
            data: 'service=' + $('#theSelect').val() + '&action=drop&store=' + store,
            success: function (response) {
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                if(response.substr(0, 7) == "FAILURE") {
                    $("#failure").html("Update procedure failed :: " + response.substr(7));
                    $("#failure_div").slideDown("slow");
                    setTimeout(function() {
                        $("#failure_div").slideUp("slow");
                    }, 3000);
                } else {
                    $("#supported").html(response);
                    $("#success_div").slideDown("slow");
                    setTimeout(function() {
                        $("#success_div").slideUp("slow");
                    }, 3000);
                }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
    }

    var iCloudService = false;
    var bad_supported = false;
    $(function(){
        $('#saveStatus').click(function() {
            if(!iCloudService) {
                $("#failure").html("Cannot update category!<br />Update the service status first by pressing the save button above.");
                $("#failure_div").slideDown("slow");
                setTimeout(function() {
                    $("#failure_div").slideUp("slow");
                }, 3000);
                return false;
            } else {
                var that = $(this);
                $.ajax({
                    type: "POST",
                    url: "injectiCloudClean.php",
                    data: 'service=' + $('#theSelect').val() + '&action=update&status=' + ($("#fresh").prop("checked") ? 'fresh' : 'bad'),
                    success: function (response) {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 1000);
                        if(response.substr(0, 7) == "FAILURE") {
                            $("#failure").html("Update procedure failed :: " + response.substr(7));
                            $("#failure_div").slideDown("slow");
                            setTimeout(function() {
                                $("#failure_div").slideUp("slow");
                            }, 3000);
                        } else {
                            bad_supported = $("#bad").prop("checked");
                            that.removeClass('btn-danger').addClass('disabled').addClass('btn-info');
                            $("#success_div").slideDown("slow");
                            setTimeout(function() {
                                $("#success_div").slideUp("slow");
                            }, 3000);
                        }
                    },
                    failure: function(errMsg) {
                        alert(errMsg);
                    }
                });
            }
        });

        $('#saveiCloud').click(function () {
            var that = $(this);
            $.ajax({
                type: "POST",
                url: "injectiCloudClean.php",
                data: 'service=' + $('#theSelect').val() + '&action=' +  ($("#iCloudClean").prop('checked') ? 'grant' : 'revoke') + '&status=' + ($("#fresh").prop("checked") ? 'fresh' : 'bad'),
                success: function (response) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);
                    if(response.substr(0, 7) == "FAILURE") {
                        $("#failure").html("Update procedure failed :: " + response.substr(7));
                        $("#failure_div").slideDown("slow");
                        setTimeout(function() {
                            $("#failure_div").slideUp("slow");
                        }, 3000);
                    } else {
                        iCloudService = !iCloudService;
                        that.removeClass('btn-danger').addClass('disabled').addClass('btn-info');
                        bad_supported = iCloudService && $("#bad").prop("checked");
                        $('#saveStatus').removeClass('btn-danger').addClass('disabled').addClass('btn-info');
                        if(!$("#iCloudClean").prop('checked')) {
                            $("#fresh").prop("checked", true);
                            $("#supported").html("<tr><th class='text-center' width='50%'>Countries</th><th class='text-center' width='50%'>Stores</th></tr>");
                        }
                        $("#success_div").slideDown("slow");
                        setTimeout(function() {
                            $("#success_div").slideUp("slow");
                        }, 3000);
                    }
                },
                failure: function(errMsg) {
                    alert(errMsg);
                }
            });
        });

        $("#iCloudClean").change(function() {
            if($(this).prop('checked')) {
                $("#serviceElements").show();
                if(!iCloudService) $('#saveiCloud').removeClass('btn-info').removeClass('disabled').addClass('btn-danger');
                else $('#saveiCloud').removeClass('btn-danger').addClass('disabled').addClass('btn-info');
                $("#serviceElements").show();
            } else {
                if(iCloudService) $('#saveiCloud').removeClass('btn-info').removeClass('disabled').addClass('btn-danger');
                else $('#saveiCloud').removeClass('btn-danger').addClass('disabled').addClass('btn-info');
                $("#serviceElements").hide();
            }
        });

        $('#country').select2({
            placeholder: "Select the country to support...",
            theme: "classic"
        }).on('select2:open',function(){
            if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
            }
        }).change(function(){
            if($(this).val() != "" && $('#store').val() != "") {
                $('#add-store').removeClass('disabled');
            } else {
                $('#add-store').addClass('disabled');
            }
        });
        $('#store').select2({
            placeholder: "Select the original store...",
            theme: "classic"
        }).on('select2:open',function(){
            if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
            }
        }).change(function(){
            if($(this).val() != "" && $('#country').val() != "") {
                $('#add-store').removeClass('disabled');
            } else {
                $('#add-store').addClass('disabled');
            }
        });

        $('#bad').on('change click', function() {
            if(!bad_supported) $('#saveStatus').removeClass('btn-info').removeClass('disabled').addClass('btn-danger');
            else $('#saveStatus').removeClass('btn-danger').addClass('disabled').addClass('btn-info');
        });
        $('#fresh').on('change click', function() {
            if(bad_supported) $('#saveStatus').removeClass('btn-info').removeClass('disabled').addClass('btn-danger');
            else $('#saveStatus').removeClass('btn-danger').addClass('disabled').addClass('btn-info');
        });

        $('#theSelect').select2({
            placeholder: "Choose the service...",
            theme: "classic"
        }).change(function() {
            $.getJSON("getiCloudCleanDetails.php?service=" + $(this).val(), function(data) {
                $("#iCloudClean").prop("checked", data.iCloudClean);
                iCloudService = data.iCloudClean;
                $("#serviceDetails").show();
                $("#bad").prop("checked", data.bad_supported);
                $("#fresh").prop("checked", !data.bad_supported);
                $("#supported").html(data.supported);
                bad_supported = data.bad_supported;
                if(data.iCloudClean) $("#serviceElements").show();
                else $("#serviceElements").hide();
            });
        }).on('select2:open',function(){
            if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
            }
        });

        $('#add-store').click(function() {
            var textAlert = "";
            var valid = true;
            if($('#country').val() == "") {
                textAlert = "Cannot insert store!<br />Start by selecting a valid country from the list.";
                valid = false;
                $('#country').focus();
            } else if($('#store').val() == "") {
                textAlert = "Cannot insert store!<br />Start by selecting a valid store from the list.";
                valid = false;
                $('#store').focus();
            } else if(!iCloudService) {
                textAlert = "Cannot insert store!<br />Update the service status first by pressing the save button above.";
                valid = false;
                $('#saveiCloud').focus();
            } else if(bad_supported != $('#bad').prop('checked')) {
                textAlert = "Cannot insert store!<br />Update the service status first by pressing the save button above.";
                valid = false;
                $('#saveStatus').focus();
            }
            if(!valid) {
                $("#failure").html(textAlert);
                $("#failure_div").slideDown("slow");
                setTimeout(function() {
                    $("#failure_div").slideUp("slow");
                }, 3000);
                return false;
            }
            $.ajax({
                type: "POST",
                url: "injectiCloudClean.php",
                data: 'service=' + $('#theSelect').val() + '&action=inject&status=' + ($("#fresh").prop("checked") ? 'fresh' : 'bad') + '&store=' + $('#store').val() + '&country=' + $('#country').val(),
                success: function (response) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);
                    if(response.substr(0, 7) == "FAILURE") {
                        $("#failure").html("Update procedure failed :: " + response.substr(7));
                        $("#failure_div").slideDown("slow");
                        setTimeout(function() {
                            $("#failure_div").slideUp("slow");
                        }, 3000);
                    } else {
                        $("#supported").html(response);
                        $("#success_div").slideDown("slow");
                        setTimeout(function() {
                            $("#success_div").slideUp("slow");
                        }, 3000);
                    }
                },
                failure: function(errMsg) {
                    alert(errMsg);
                }
            });
        });

        $(this).ajaxStart(function() {
            $('.overlayer').show();
        }).ajaxStop(function() {
            $('.overlayer').hide();
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
        echo "200";
    } else {
        echo "80";
    }
    ?>px;width:100%">
        <h3><a href='iCloudClean.php?all=candidate'>iCloud Clean Candidates</a><a href='iCloudClean.php?all=current' style="margin-left:30px">Only iCloud Clean Services</a><a href='iCloudClean.php?all=active' style="margin-left:30px">Active Services</a><a href='iCloudClean.php?all=yes' style="margin-left:30px">All Services</a></h3>
        <div id="success_div" style="display:none;margin-bottom:10px;width:72%;border:solid 1px green;border-radius:5px;background-color: ghostwhite;color:green"><h3 id="success">Service successfully update</h3></div>
        <div id="failure_div" style="display:none;margin-bottom:10px;width:72%;border:solid 2px red;border-radius:5px;background-color:pink;color:red"><h3 id="failure"></h3></div>
        <div align="left" style="width:72%">
            <select id="theSelect" style="width:100%">
                <option value=""></option>
<?php
if(isset($_GET['all']) && $_GET['all'] == 'current') {
    $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_status FROM services WHERE icloud_clean_service = 1 AND bad_supported = 1 ORDER BY service_name");
    if(mysqli_num_rows($rows) > 0) {
        echo "              <optgroup label='Bad Case History Services'>\n";
        while($row = mysqli_fetch_array($rows)) {
            echo "                  <option value='" . $row['id'] . "'>#" . $row['id'] . " " . $row['service_name'] . " [" . ($row['service_status'] == 1? "ON" : "off") . "]</option>\n";
        }
        echo "              </optgroup>\n";
    }
    $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_status FROM services WHERE icloud_clean_service = 1 AND bad_supported = 0 ORDER BY service_name");
    if(mysqli_num_rows($rows) > 0) {
        echo "              <optgroup label='Fresh Orders Services'>\n";
        while($row = mysqli_fetch_array($rows)) {
            echo "                  <option value='" . $row['id'] . "'>#" . $row['id'] . " " . $row['service_name'] . " [" . ($row['service_status'] == 1? "ON" : "off") . "]</option>\n";
        }
    }
} else {
    if(isset($_GET['all']) && $_GET['all'] == 'yes') {
        $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, service_status FROM services ORDER BY service_group, service_name");
    } elseif(isset($_GET['all']) && $_GET['all'] == 'active') {
        $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, service_status FROM services WHERE service_status = '1' ORDER BY service_group, service_name");
    } elseif(isset($_GET['all']) && $_GET['all'] == 'candidate') {
        $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, service_status FROM services WHERE service_group = 'iCloud Clean' AND icloud_clean_service = 0 AND service_status = 1 ORDER BY service_group, service_name");
    } else {
        $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, service_status FROM services WHERE service_group = 'iCloud Clean' AND icloud_clean_service = 0 AND service_status = 1 ORDER BY service_group, service_name");
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
}
?>
                </optgroup>
            </select>
        </div>
        <form id="service" method="post">
            <div id="serviceDetails" style="display:none;font-size:20px;text-align:left;width:72%;margin-top:10px;margin-bottom:50px">
                <label class="titles" for="iCloudClean">iCloud Clean</label>
                <input id="iCloudClean" type="checkbox" name="iCloudClean" style="margin-left: 30px;" value="1" /> <label for="iCloudClean">Yes / No</label>
                <a id="saveiCloud" class="btn btn-info disabled" style="margin-left: 35px">Save</a>
                <div id="serviceElements">
<!--*****************************************************************************************************-->
                    <hr style="border: solid 1px black" />
                    <label class="titles" for="history">History</label>
                    <input type="radio" name="history" id="fresh" style="margin-left: 30px;"> <label for="fresh">Fresh / Never Tried <i style="color:gray">only</i></label>
                    <br/>
                    <input type="radio" name="history" id="bad" style="margin-left: 30px;"> <label for="bad">Bad Case History / Replaced IMEI <b style="color: blue">+</b> Fresh / Never Tried</label>
                    <a id="saveStatus" class="btn btn-info disabled" style="margin-left: 35px">Save</a>
<!--*****************************************************************************************************-->
                    <hr style="border: solid 1px black" />
                    <label class="titles" for="countries">Supported Countries / Stores</label>
                    <table width="100%">
                        <tr>
                            <td width="45%">
                                <select id="country" style="width:100%">
                                    <option value=""></option>
                                    <option value="WW">All countries supported</option>
                                    <?php
                                    $rows = mysqli_query($DB->Link, "SELECT country_code, english_name FROM countries ORDER BY english_name");
                                    while($row = mysqli_fetch_array($rows)) {
                                        echo "<option value='" . $row['country_code'] . "'>" . $row['english_name'] . "</option>\n";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td width="45%">
                                <select id="store" style="width:100%">
                                    <option value=""></option>
                                    <?php
                                    $rows = mysqli_query($DB->Link, "SELECT id, store FROM stores ORDER BY store");
                                    while($row = mysqli_fetch_array($rows)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['store'] . "</option>\n";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td width="*"><a class="btn btn-primary disabled" id="add-store">Add store</a></td>
                        </tr>
                    </table>
                    <table id="supported" style="width:100%" border="solid 1px gray"></table>
<!--*****************************************************************************************************-->
                </div>
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