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
    <?php echo admin_common_head_with_title("iPhone Factory Unlock", "20") ?>
    <link rel="stylesheet" href="style/service.css" />
    <script>
    $(function(){
        $('.list-group.checked-list-box .list-group-item').each(function () {
            var $widget = $(this),
                $checkbox = $('<input type="checkbox" class="hidden" />'),
                color = ($widget.data('color') ? $widget.data('color') : "primary"),
                style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };

            $widget.css('cursor', 'pointer')
            $widget.append($checkbox);

            // Event Handlers
            $widget.on('click', function () {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
            });
            $checkbox.on('change', function () {
                updateDisplay();
            });


            // Actions
            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');

                // Set the button's state
                $widget.data('state', (isChecked) ? "on" : "off");

                // Set the button's icon
                $widget.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$widget.data('state')].icon);

                // Update the button's color
                if (isChecked) {
                    $widget.addClass(style + color + ' active');
                } else {
                    $widget.removeClass(style + color + ' active');
                }
            }

            // Initialization
            function init() {

                if ($widget.data('checked') == true) {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                }

                updateDisplay();

                // Inject the icon if applicable
                if ($widget.find('.state-icon').length == 0) {
                    $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
                }
            }
            init();
        });
        $('.country').click(function() {
            var content = '';
            $('.country.active').each(function(idx, li) {
                if(content == '') content = $(li).text();
                else content += ' - ' + $(li).text();
            });
            $('#countryList').html(content);
        });
        $('.carrier').click(function() {
            var content = '';
            $('.carrier.active').each(function(idx, li) {
                if(content == '') content = $(li).text();
                else content += ' - ' + $(li).text();
            });
            $('#carrierList').html(content);
        });
        $('.model').click(function() {
            var content = '';
            $('.model.active').each(function(idx, li) {
                if(content == '') content = $(li).text();
                else content += ' - ' + $(li).text();
            });
            $('#modelList').html(content);
        });
        $('.status').click(function() {
            var content = '';
            $('.status.active').each(function(idx, li) {
                if(content == '') content = $(li).text();
                else content += ' - ' + $(li).text();
            });
            $('#statusList').html(content);
        });
        $("#iPhoneFactoryUnlockService").change(function() {
            if($(this).prop('checked')) {
                $("#serviceElements").show();
            } else {
                $("#serviceElements").hide();
            }
        });
        $('#theSelect').select2({
            placeholder: "Choose the service...",
            theme: "classic"
        }).change(function() {
            $.getJSON("getiPhoneFactoryUnlockDetails.php?service=" + $(this).val(), function(data) {
                $("#serviceDetails").show();
                $("#iPhoneFactoryUnlockService").prop("checked", data.iPhoneFactoryUnlockService);
                if(data.iPhoneFactoryUnlockService) {
                    $("#serviceElements").show();

                    $("#countryList").html(data.countryList);
                    $('.country').each(function (idx, li) {
                        $(this).find('input').prop('checked', false);
                        $(this).data('state', "off").removeClass('active');
                        $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-unchecked');
                    });
                    data.countries.forEach(function(elem) {
                        var $li = $('#' + elem);
                        $li.find('input').prop('checked', true);
                        $li.data('state', "on").addClass('active');
                        $li.find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-check');
                    });

                    $("#carrierList").html(data.carrierList);
                    $('.carrier').each(function (idx, li) {
                        $(this).find('input').prop('checked', false);
                        $(this).data('state', "off").removeClass('active');
                        $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-unchecked');
                    });
                    data.carriers.forEach(function(elem) {
                        var $li = $('#carrier' + elem);
                        $li.find('input').prop('checked', true);
                        $li.data('state', "on").addClass('active');
                        $li.find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-check');
                    });

                    $("#modelList").html(data.modelList);
                    $('.model').each(function (idx, li) {
                        $(this).find('input').prop('checked', false);
                        $(this).data('state', "off").removeClass('active');
                        $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-unchecked');
                    });
                    data.models.forEach(function(elem) {
                        var $li = $('#model' + elem);
                        $li.find('input').prop('checked', true);
                        $li.data('state', "on").addClass('active');
                        $li.find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-check');
                    });

                    $("#statusList").html(data.statusList);
                    $('.status').each(function (idx, li) {
                        $(this).find('input').prop('checked', false);
                        $(this).data('state', "off").removeClass('active');
                        $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-unchecked');
                    });
                    data.status.forEach(function(elem) {
                        var $li = $('#status' + elem);
                        $li.find('input').prop('checked', true);
                        $li.data('state', "on").addClass('active');
                        $li.find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-check');
                    });
                } else {
                    $("#serviceElements").hide();
                }
            });
        }).on('select2:open',function(){
            if($('.select2-dropdown--above').attr('class') == "select2-dropdown select2-dropdown--above") {
                $('.select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                $('.select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
            }
        });
        $(this).ajaxStart(function() {
            $('.overlayer').show();
        }).ajaxStop(function() {
            $('.overlayer').hide();
        });
        $("#service").on("submit", function() {
            if($('#iPhoneFactoryUnlockService').prop('checked')) {
                var countries = [];
                $(".country.active").each(function(idx, li) {
                    countries.push($(li).prop('id'));
                });
                if(countries.length == 0) {
                    alert('You must select at least one country!');
                    return false;
                }
                var carriers = [];
                $(".carrier.active").each(function(idx, li) {
                    carriers.push($(li).val());
                });
                if(carriers.length == 0) {
                    alert('You must select at least one carrier!');
                    return false;
                }
                var models = [];
                $(".model.active").each(function(idx, li) {
                    models.push($(li).val());
                });
                if(models.length == 0) {
                    alert('You must select at least one model!');
                    return false;
                }
                var status = [];
                $(".status.active").each(function(idx, li) {
                    status.push($(li).val());
                });
                if(status.length == 0) {
                    alert('You must select at least one status!');
                    return false;
                }
                var data = JSON.stringify({
                    "countries": countries,
                    "carriers":  carriers,
                    "models":    models,
                    "status":    status
                });
                $.ajax({
                    type: "POST",
                    url: "injectiPhoneFactoryUnlock.php",
                    data: 'service=' + $('#theSelect').val() + '&action=inject&data=' + data,
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
            } else {
                $.ajax({
                    url: 'injectiPhoneFactoryUnlock.php',
                    type: 'POST',
                    data: 'service=' + $('#theSelect').val() + '&action=revoke',
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
            return false;
        });
        $('#check-countries').click(function() {
            $('.country').each(function (idx, li) {
                $(this).find('input').prop('checked', true);
                $(this).data('state', "on").addClass('active');
                $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-check');
            });
            $('#countryList').html('');
        });
        $('#uncheck-countries').click(function() {
            $('.country').each(function (idx, li) {
                $(this).find('input').prop('checked', false);
                $(this).data('state', "off").removeClass('active');
                $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-unchecked');
            });
            $('#countryList').html('');
        });
        $('#check-carriers').click(function() {
            $('.carrier').each(function (idx, li) {
                $(this).find('input').prop('checked', true);
                $(this).data('state', "on").addClass('active');
                $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-check');
            });
            $('#carrierList').html('');
        });
        $('#uncheck-carriers').click(function() {
            $('.carrier').each(function (idx, li) {
                $(this).find('input').prop('checked', false);
                $(this).data('state', "off").removeClass('active');
                $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-unchecked');
            });
            $('#carrierList').html('');
        });
        $('#check-models').click(function() {
            $('.model').each(function (idx, li) {
                $(this).find('input').prop('checked', true);
                $(this).data('state', "on").addClass('active');
                $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-check');
            });
            $('#modelList').html('');
        });
        $('#uncheck-models').click(function() {
            $('.model').each(function (idx, li) {
                $(this).find('input').prop('checked', false);
                $(this).data('state', "off").removeClass('active');
                $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-unchecked');
            });
            $('#modelList').html('');
        });
        $('#check-status').click(function() {
            $('.status').each(function (idx, li) {
                $(this).find('input').prop('checked', true);
                $(this).data('state', "on").addClass('active');
                $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-check');
            });
            $('#statusList').html('');
        });
        $('#uncheck-status').click(function() {
            $('.status').each(function (idx, li) {
                $(this).find('input').prop('checked', false);
                $(this).data('state', "off").removeClass('active');
                $(this).find('.state-icon').removeClass().addClass('state-icon glyphicon glyphicon-unchecked');
            });
            $('#statusList').html('');
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
        <h3><a href='iPhoneFactoryUnlock.php'>iPhone Factory Unlock Candidates</a><a href='iPhoneFactoryUnlock.php?all=current' style="margin-left:30px">Only iPhone Factory Unlock Services</a><a href='iPhoneFactoryUnlock.php?all=yes' style="margin-left:30px">All Services</a></h3>
        <div id="success_div" style="display:none;margin-bottom:10px;width:72%;border:solid 1px green;border-radius:5px;background-color: ghostwhite;color:green"><h3 id="success">Service successfully update</h3></div>
        <div id="failure_div" style="display:none;margin-bottom:10px;width:72%;border:solid 2px red;border-radius:5px;background-color:pink;color:red"><h3 id="failure"></h3></div>
        <div align="left" style="width:72%">
            <select id="theSelect" style="width:100%">
                <option value=""></option>
<?php
if(isset($_GET['all'])) {
    if($_GET['all'] == 'yes') {
        $rows = mysqli_query($DB->Link, "SELECT id, service_name, service_group, service_status FROM services ORDER BY service_group, service_name");
    } elseif($_GET['all'] == 'current') {
        $rows = mysqli_query($DB->Link, "SELECT DISTINCT services.id 'id', service_name, service_group, service_status FROM services, iPhoneFactoryUnlock WHERE services.id = service ORDER BY service_group, service_name");
    }
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
        <form id="service" method="post">
            <div id="serviceDetails" style="display:none;font-size:20px;text-align:left;width:72%;margin-top:10px;margin-bottom:50px">
                <label class="titles" for="iPhoneFactoryUnlockService">iPhone Factory Unlock Service</label>
                <input id="iPhoneFactoryUnlockService" type="checkbox" name="iPhoneFactoryUnlockService" style="margin-left: 30px;" value="1" /> <label for="iPhoneFactoryUnlockService">Yes / No</label>

                <div id="serviceElements">
<!--*****************************************************************************************************-->
                    <hr style="border: solid 1px black" />
                    <label class="titles" for="countries">Countries <a id="check-countries" class="text-primary" style="margin-left:30px;cursor:pointer">Check all</a> <a id="uncheck-countries" class="text-primary" style="margin-left:30px;cursor:pointer">Uncheck all</a></label>
                    <p id="countryList" style="color: crimson"></p>
                    <div style="font-size:16px;max-height:400px;overflow: auto;">
                        <ul class="list-group checked-list-box">
                            <?php
                            $rows = mysqli_query($DB->Link, "SELECT country_code, english_name FROM countries ORDER BY english_name");
                            while($row = mysqli_fetch_array($rows)) {
                                echo "                    <li id='" . $row['country_code'] . "' class='country list-group-item'> " . $row['english_name'] . "</li>\n";
                            }
                            ?>
                        </ul>
                    </div>
<!--*****************************************************************************************************-->
                    <hr style="border: solid 1px black" />
                    <label class="titles" for="carriers">Carriers <a id="check-carriers" class="text-primary" style="margin-left:30px;cursor:pointer">Check all</a> <a id="uncheck-carriers" class="text-primary" style="margin-left:30px;cursor:pointer">Uncheck all</a></label>
                    <p id="carrierList" style="color: crimson"></p>
                    <div style="font-size:16px;max-height:400px;overflow: auto;">
                        <ul class="list-group checked-list-box">
                            <?php
                            $rows = mysqli_query($DB->Link, "SELECT id, carrier FROM carriers ORDER BY carrier");
                            while($row = mysqli_fetch_array($rows)) {
                                echo "                    <li id='carrier" . $row['id'] . "' class='carrier list-group-item' value='" . $row['id'] . "'> " . $row['carrier'] . "</li>\n";
                            }
                            ?>
                        </ul>
                    </div>
<!--*****************************************************************************************************-->
                    <hr style="border: solid 1px black" />
                    <label class="titles" for="models">Models <a id="check-models" class="text-primary" style="margin-left:30px;cursor:pointer">Check all</a> <a id="uncheck-models" class="text-primary" style="margin-left:30px;cursor:pointer">Uncheck all</a></label>
                    <p id="modelList" style="color: crimson"></p>
                    <div style="font-size:16px;max-height:400px;overflow: auto;">
                        <ul class="list-group checked-list-box">
                            <?php
                            $rows = mysqli_query($DB->Link, "SELECT id, model FROM models WHERE make = 'Apple' ORDER BY id");
                            while($row = mysqli_fetch_array($rows)) {
                                echo "                    <li id='model" . $row['id'] . "' class='model list-group-item' value='" . $row['id'] . "'> " . $row['model'] . "</li>\n";
                            }
                            ?>
                        </ul>
                    </div>
<!--*****************************************************************************************************-->
                    <hr style="border: solid 1px black" />
                    <label class="titles" for="status">Status <a id="check-status" class="text-primary" style="margin-left:30px;cursor:pointer">Check all</a> <a id="uncheck-status" class="text-primary" style="margin-left:30px;cursor:pointer">Uncheck all</a></label>
                    <p id="statusList" style="color: crimson"></p>
                    <div style="font-size:16px;max-height:400px;overflow: auto;">
                        <ul class="list-group checked-list-box">
                            <?php
                            $rows = mysqli_query($DB->Link, "SELECT id, status FROM ESNstatus ORDER BY id");
                            while($row = mysqli_fetch_array($rows)) {
                                echo "                    <li id='status" . $row['id'] . "' class='status list-group-item' value='" . $row['id'] . "'> " . $row['status'] . "</li>\n";
                            }
                            ?>
                        </ul>
                    </div>
<!--*****************************************************************************************************-->
                </div>
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
</body>
</html>