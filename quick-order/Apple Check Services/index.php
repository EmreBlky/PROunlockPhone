<?php
define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Apple iPhone IMEI Check Services") ?>
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
        <script>
$(function() {
    $('#modal').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
    });
});
        </script>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php
                if(isset($_GET['quick']) && $_GET['quick'] == "no") {
                    renderOutOfSessionHeader("check");
                    ?>
                    <section id="content" style="margin:0px">
                        <div class="slogan">
                            <h2>Instant GSX Services [Global Service eXchange]</h2>
                        </div>
                    </section>
                    <div class="container" style="margin-bottom: 50px">
                    <?php
                } else {
                    renderOutOfSessionHeader("gsx");
                    ?>
                <div class="row no-margin">
                    <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                        <div style="font-size:24px;width:100%" align="center">
                            <hr />
                            <div class="steps">
                                <h3 class="nomargin"><a style="color:crimson"><u>Step 3</u>: Choose your service</a></h3>
                                <small>Click on the service name to watch an example.</small>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>
                <div class="container" style="margin-bottom: 50px">
                    <a class="text-primary" href="https://www.prounlockphone.com/quick-order/">Quick Order</a> <b>></b> Apple Check Services
                    <br/><br/>
                <?php
                }
            ?>
                    <div style="text-align: center">
                        <table class='table table-bordered'>
                            <thead>
                                <tr class='title' style='display: table-row;'>
                                    <th style='width:60%'>Service Name</th>
                                    <th style='width:20%' class="text-center">Price</th>
                                    <th style='width:20%' class="text-center">Delivery Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $rows = mysqli_query($DB->Link, "SELECT id, regular_{$_SESSION['currency']}, delivery_time, service_name FROM services WHERE service_status = 1 AND delivery_time = 'Instant' ORDER BY UCASE(service_name)");
                                while($row = mysqli_fetch_array($rows)) {
                                    echo "<tr class='block' style='display: table-row;'>
                                    <td class='word-break' align='left'>
                                        <a data-toggle='modal' data-target='#modal' class='text-info' href='https://www.prounlockphone.com/quick-order/Apple%20Check%20Services/sample.php?service={$row['id']}'>{$row['service_name']}</a>
                                    </td>
                                    <td>" . $row["regular_{$_SESSION['currency']}"] . " {$_SESSION['symbol']}</td>
                                    <td>" . $row['delivery_time'] . "</td>
                                </tr>
                                ";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
    </body>
</html>