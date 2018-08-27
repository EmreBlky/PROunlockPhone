<?php
if(isset($_GET['session'])) {
    session_id($_GET['session']);
}
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Place Order") ?>
        <style>
            .details ul {
                list-style-position: inside;
                margin-left: 15px;
            }
        </style>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php header_render("order") ?>
            <div class="clear"></div>
            <section id="content" class="account" style="margin-bottom: 0px;margin-top:50px">
		    <div class="container">
                    <div class="col-md-12">
                        <select id="service" name='service' class="form-control selectpicker " style="margin: 10px 0;">
                            <option value="">Please choose a service to place an order...</option>
<?php
$rows = mysqli_query($DB->Link, "SELECT id, service_name, delivery_time, service_group, " . ($_SESSION['client_type'] == "admin" ? "reseller" : $_SESSION['client_type']) . "_" . $_SESSION['currency'] . " FROM services WHERE service_status = 1 AND delivery_time <> 'Instant' AND service_group <> 'iPhone Factory Unlock' ORDER BY service_group, service_name");
$optgroup = "";
while($row = mysqli_fetch_array($rows)) {
    if($optgroup != $row['service_group']) {
        if($optgroup != "") echo "                            </optgroup>\n";
        $optgroup = $row['service_group'];
        echo "                            <optgroup label=\"" . $row["service_group"] . "\">\n";
    }
    $bargains = mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$_SESSION['client_id']}' AND service = '{$row['id']}'");
    $bargain = mysqli_fetch_assoc($bargains);
    if(mysqli_num_rows($bargains)) {
        if($bargain['nature'] == 'impose') {
            $price = $bargain['price'];
        } else {
            if($_SESSION['client_type'] == "reseller" or $_SESSION['client_type'] == "admin") {
                if($row['reseller_' . $_SESSION['currency']] < $bargain['price']) {
                    $price = $row['reseller_' . $_SESSION['currency']];
                } else {
                    $price = $bargain['price'];
                }
            } else {
                if($bargain['price'] <= $row['regular_' . $_SESSION['currency']]) {
                    $price = $bargain['price'];
                } else {
                    $price = $row['regular_' . $_SESSION['currency']];
                }
            }
        }
    } else {
        $price = $row[($_SESSION['client_type'] == "admin" ? "reseller" : $_SESSION['client_type']) . "_" . $_SESSION['currency']];
    }
    echo "                                <option value=\"" . $row['id'] . "\">" . $row['service_name'] . " [service #" . $row['id'] . "]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $row['delivery_time'] . "&nbsp;&nbsp;|&nbsp;&nbsp;{$price} {$_SESSION['symbol']}</option>\n";
}
echo "                            </optgroup>\n";
$rows = mysqli_query($DB->Link, "SELECT id, service_name, delivery_time, country, " . ($_SESSION['client_type'] == "admin" ? "reseller" : $_SESSION['client_type']) . "_" . $_SESSION['currency'] . " FROM services WHERE service_status = 1 AND delivery_time <> 'Instant' AND service_group = 'iPhone Factory Unlock' AND country <> 'Multi' ORDER BY country, service_name");
$optgroup = "";
while($row = mysqli_fetch_array($rows)) {
    if($optgroup != $row['country']) {
        if($optgroup != "") echo "                            </optgroup>\n";
        $optgroup = $row['country'];
        $req = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT english_name FROM countries WHERE country_code = '" . $row['country'] . "'"));
        echo "                            <optgroup label=\"" . $req["english_name"] . "\">\n";
    }
    $bargains = mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$_SESSION['client_id']}' AND service = '{$row['id']}'");
    $bargain = mysqli_fetch_assoc($bargains);
    if(mysqli_num_rows($bargains)) {
        if($bargain['nature'] == 'impose') {
            $price = $bargain['price'];
        } else {
            if($_SESSION['client_type'] == "reseller" or $_SESSION['client_type'] == "admin") {
                if($row['reseller_' . $_SESSION['currency']] < $bargain['price']) {
                    $price = $row['reseller_' . $_SESSION['currency']];
                } else {
                    $price = $bargain['price'];
                }
            } else {
                if($bargain['price'] <= $row['regular_' . $_SESSION['currency']]) {
                    $price = $bargain['price'];
                } else {
                    $price = $row['regular_' . $_SESSION['currency']];
                }
            }
        }
    } else {
        $price = $row[($_SESSION['client_type'] == "admin" ? "reseller" : $_SESSION['client_type']) . "_" . $_SESSION['currency']];
    }
    echo "                                <option value=\"" . $row['id'] . "\">" . $row['service_name'] . " [service #" . $row['id'] . "]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $row['delivery_time'] . "&nbsp;&nbsp;|&nbsp;&nbsp;{$price} {$_SESSION['symbol']}</option>\n";
}
echo "                            </optgroup>\n";
$rows = mysqli_query($DB->Link, "SELECT id, service_name, delivery_time, country, " . ($_SESSION['client_type'] == "admin" ? "reseller" : $_SESSION['client_type']) . "_" . $_SESSION['currency'] . " FROM services WHERE service_status = 1 AND delivery_time <> 'Instant' AND service_group = 'iPhone Factory Unlock' AND country = 'Multi' ORDER BY country, service_name");
if(mysqli_num_rows($rows) > 0) echo "                            <optgroup label=\"Worldwide\">\n";
while($row = mysqli_fetch_array($rows)) {
    $bargains = mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$_SESSION['client_id']}' AND service = '{$row['id']}'");
    $bargain = mysqli_fetch_assoc($bargains);
    if(mysqli_num_rows($bargains)) {
        if($bargain['nature'] == 'impose') {
            $price = $bargain['price'];
        } else {
            if($_SESSION['client_type'] == "reseller" or $_SESSION['client_type'] == "admin") {
                if($row['reseller_' . $_SESSION['currency']] < $bargain['price']) {
                    $price = $row['reseller_' . $_SESSION['currency']];
                } else {
                    $price = $bargain['price'];
                }
            } else {
                if($bargain['price'] <= $row['regular_' . $_SESSION['currency']]) {
                    $price = $bargain['price'];
                } else {
                    $price = $row['regular_' . $_SESSION['currency']];
                }
            }
        }
    } else {
        $price = $row[($_SESSION['client_type'] == "admin" ? "reseller" : $_SESSION['client_type']) . "_" . $_SESSION['currency']];
    }
    echo "                                <option value=\"" . $row['id'] . "\">" . $row['service_name'] . " [service #" . $row['id'] . "]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;~&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $row['delivery_time'] . "&nbsp;&nbsp;|&nbsp;&nbsp;{$price} {$_SESSION['symbol']}</option>\n";
}
if(mysqli_num_rows($rows) > 0) echo "                            </optgroup>\n";
?>                            
                        </select>
                    </div>
                    <div id="serviceContainer" style="display: none;">
                        <div class="col-md-6 margin30 notopmargin">
                            <div class="curved-widget widget-white">
                                <div class="widget-content" id="form"></div>
                            </div>
                        </div>
                        <div class="col-md-6 margin30 notopmargin">
                            <div class="curved-widget widget-white">
                                <div class="widget-content" id="info"></div>
                            </div>
                        </div>
                    </div>

                    <div id="serviceContainerTip">
                        <div class="col-md-12 margin30 notopmargin">
                            <div class="curved-widget widget-white center-text" style="padding: 60px 20px; font-size: 16px; color: rgb(117, 116, 116);">
                                Select the service you want to order<br />
                                Read description and conditions...<br />
                                Fill all the fields to ensure good reception of your order<br />
                                <span style="color:red">All services are subject to suspension without prenotice</span><br />
                                In such case, your order will be rejected and credits refunded (you can always request money-back)<br />
                                This happens especially when prices go up, so simply resubmit your order<hr style="border-color:black" />
                                Placing your order does not guarantee it will be processed<br />
                                You <b><u>must</u></b> ensure having sufficient credits or obtain approval from our business team
                            </div>
                        </div>
                    </div>
                    <div id="serviceLoader" style="display: none;">
                        <div class="col-md-12">
                            <div class="curved-widget widget-white center-text" style="padding: 60px 20px; font-size: 40px; font-weight: bold; color: rgb(117, 116, 116);">
                                <img style="display: block; margin: 30px auto;" src="https://www.prounlockphone.com/images/loading.gif">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php echo $footer ?>
	</div>
	<div id="gotoTop" class="fa fa-caret-up"></div>
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1041;">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </div>
        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1041;">
            <div class="modal-dialog" style="width: 96%;">
                <div class="modal-content"></div>
            </div>
        </div>
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="false" style="display:none; z-index: 1100;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" class="close" data-dismiss="modal" type="button">×</button>
                        <h4 class="modal-title" style="color: red;">Error!</h4>
                    </div>
                    <div class="modal-body container-fluid">
                        <p>A problem occured while we were trying to serve your query. Please refresh the page and try again!</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="../common/bootstrap.min.js"></script>
        <script src="../common/plugins.js"></script>
        <script src="../common/functions.js"></script>
        <script type="text/javascript" src="../common/jquery.jgrowl.min.js"></script>
        <script type="text/javascript" src="../common/jquery.form.min.js"></script>
        <script type="text/javascript" src="../common/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="../common/script.js"></script>
        <script type="text/javascript" src="../common/switchery.js"></script>
	<script>
$(document).ready(function () {
    <?php
    if(isset($_GET['return']) && $_GET['return'] == 'success') {
    ?>
    $.jGrowl("We successfully received your payment.", {theme: 'growlSuccess'});
    $.jGrowl("Your balance has been updated.", {theme: 'growlSuccess'});
    $.jGrowl("We sent you a confirmation eMail.", {theme: 'growlSuccess'});
    <?php
    }
    ?>
    $('#service').on('change', function () {
        if(this.value == "") {
            $('#serviceContainerTip').show();
            $('#serviceContainer').hide();
            return;
        }
        $('#serviceContainerTip').hide();
        $('#serviceContainer').hide();
        $('#serviceLoader').show();
        $.ajax({
            type: "POST",
            url: 'https://www.prounlockphone.com/order/getService.php',
            data: 'service=' + this.value,
            success: function (resp) {
                $('#serviceLoader').hide();
                var responseData = JSON.parse(resp);
                if (responseData.type == 0) {
                    responseData.msg.forEach(function (item) {
                        $.jGrowl(item, {theme: 'growlFail'});
                        $('#serviceContainerTip').show();
                    });
                } else {
                    $('#form').html(responseData.data.form);
                    $('#info').html(responseData.data.info);
                    $('#serviceContainer').show();
                }
            },
            error: showError
        });
    });
    $('.selectpicker').selectpicker({
        liveSearch: true,
        mobile: false,
        style: 'select-service',
        size: 10
    });
    <?php if(isset($_GET['param'])) {
    echo "$('#service').val('" . $_GET['param'] . "').trigger('change');";
}
    ?>
});
	</script>
    </body>
</html>