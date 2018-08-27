<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/online.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Check IMEI") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </head>
    <body class="stretched device-lg">
	<div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php
            header_render("check");
            ?>		
            <div class="clear"></div>
            <section id="content" class="account" style="margin-bottom: 0px;margin-top:50px">
		<div class="container">
                    <div class="col-md-12">                        
                        <select id="service" name='service' class="form-control selectpicker " style="margin: 10px 0;">
                            <option value="">Please choose a service to place an order...</option>-->
<?php
$rows = mysqli_query($DB->Link, "SELECT id, service_name, {$_SESSION['client_type']}_{$_SESSION['currency']} FROM services WHERE service_status = 1 AND delivery_time = 'Instant' ORDER BY service_name");
while($row = mysqli_fetch_array($rows)) {
    $bargains = mysqli_query($DB->Link, "SELECT price, nature FROM price_client_service WHERE client = '{$_SESSION['client_id']}' AND service = '{$row['id']}'");
    $bargain = mysqli_fetch_assoc($bargains);
    if(mysqli_num_rows($bargains)) {
        if($bargain['nature'] == 'impose') {
            $price = $bargain['price'];
        } else {
            if($_SESSION['client_type'] == "reseller") {
                if($row["reseller_{$_SESSION['currency']}"] < $bargain['price']) {
                    $price = $row["reseller_{$_SESSION['currency']}"];
                } else {
                    $price = $bargain['price'];
                }
            } else {
                if($bargain['price'] <= $row["regular_{$_SESSION['currency']}"]) {
                    $price = $bargain['price'];
                } else {
                    $price = $row["regular_{$_SESSION['currency']}"];
                }
            }
        }
    } else {
        $price = $row["{$_SESSION['client_type']}_{$_SESSION['currency']}"];
    }
    echo "                                <option value=\"" . $row['id'] . "\">#" . $row['id'] . " " . $row['service_name'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[{$price} {$_SESSION['symbol']}]</option>\n";
}
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
                                This is an instant checking system<br />
                                You <b><u>must</u></b> ensure having sufficient credits to process your requests<hr style="border-color:black" />
                                You can connect your server to our API<br />
                                Contact our Admin team for details
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
    <?php echo $common_foot ?>
	<script>
$(document).ready(function () {
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
            url: 'https://www.prounlockphone.com/check/getService.php',
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