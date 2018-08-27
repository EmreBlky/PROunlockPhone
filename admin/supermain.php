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
    <?php echo admin_common_head_with_title("Admin Console") ?>
    <link rel="stylesheet" href="style/main.css" />
</head>
<body>
<?php
require_once('superheader.php');
?>
    <table style="margin-top:60px;" class="statement" align="center">
        <tr>
            <td style="text-align: center;font-size:150%;font-weight:bold" colspan="3">Budget in <a href="dashboard-X.php?curr=USD">USD</a></td>
        </tr>
        <tr align="center">
            <td style="width:34%">
                <div class="rectangle" style="margin-right:10px">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(balance) 'due' FROM users WHERE currency = 'USD' AND id NOT IN (134, 58) AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> $</span><small>USers' Balances</small>
                </div>
            </td>
            <td style="width:33%">
                <div class="rectangle" style="margin-right:10px">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'due' FROM orders, users WHERE (orders.status = 'Pending' OR orders.status = 'In process') AND client = users.id AND currency = 'USD' AND users.id NOT IN (134, 58) AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> $</span><small>Locked Amount</small>
                </div>
            </td>
            <td style="width:33%">
                <div class="rectangle">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'due' FROM orders, users WHERE orders.status = 'Success' AND client = users.id AND currency = 'USD' AND users.id NOT IN (134, 58) AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> $</span><small>Total Success</small>
                </div>
            </td>
        </tr>
    </table>
    <hr />
    <table style="margin-top:-5px;" class="statement" align="center">
        <tr>
            <td style="text-align: center;font-size:150%;font-weight:bold" colspan="3">Budget in <a href="dashboard-X.php?curr=EUR">EUR</a></td>
        </tr>
        <tr align="center">
            <td style="width:34%">
                <div class="rectangle" style="margin-right:10px">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(balance) 'due' FROM users WHERE currency = 'EUR' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> &euro;</span><small>USers' Balances</small>
                </div>
            </td>
            <td style="width:33%">
                <div class="rectangle" style="margin-right:10px">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'due' FROM orders, users WHERE (orders.status = 'Pending' OR orders.status = 'In process') AND client = users.id AND currency = 'EUR' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> &euro;</span><small>Locked Amount</small>
                </div>
            </td>
            <td style="width:33%">
                <div class="rectangle">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'due' FROM orders, users WHERE orders.status = 'Success' AND client = users.id AND currency = 'EUR' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> &euro;</span><small>Total Success</small>
                </div>
            </td>
        </tr>
    </table>
    <hr />
    <table style="margin-top:-5px;" class="statement" align="center">
        <tr>
            <td style="text-align: center;font-size:150%;font-weight:bold" colspan="3">Budget in <a href="dashboard-X.php?curr=GBP">GBP</a></td>
        </tr>
        <tr align="center">
            <td style="width:34%">
                <div class="rectangle" style="margin-right:10px">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(balance) 'due' FROM users WHERE currency = 'GBP' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> &pound;</span><small>USers' Balances</small>
                </div>
            </td>
            <td style="width:33%">
                <div class="rectangle" style="margin-right:10px">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'due' FROM orders, users WHERE (orders.status = 'Pending' OR orders.status = 'In process') AND client = users.id AND currency = 'GBP' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> &pound;</span><small>Locked Amount</small>
                </div>
            </td>
            <td style="width:33%">
                <div class="rectangle">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'due' FROM orders, users WHERE orders.status = 'Success' AND client = users.id AND currency = 'GBP' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> &pound;</span><small>Total Success</small>
                </div>
            </td>
        </tr>
    </table>
    <hr />
    <table style="margin-top:-5px;" class="statement" align="center">
        <tr>
            <td style="text-align: center;font-size:150%;font-weight:bold" colspan="3">Budget in <a href="dashboard-X.php?curr=TND">TND</a></td>
        </tr>
        <tr align="center">
            <td style="width:34%">
                <div class="rectangle" style="margin-right:10px">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(balance) 'due' FROM users WHERE currency = 'TND' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> DT</span><small>USers' Balances</small>
                </div>
            </td>
            <td style="width:33%">
                <div class="rectangle" style="margin-right:10px">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'due' FROM orders, users WHERE (orders.status = 'Pending' OR orders.status = 'In process') AND client = users.id AND currency = 'TND' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> DT</span><small>Locked Amount</small>
                </div>
            </td>
            <td style="width:33%">
                <div class="rectangle">
                    <span><?php
$due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT SUM(price) 'due' FROM orders, users WHERE orders.status = 'Success' AND client = users.id AND currency = 'TND' AND users.status = 'Active'"));
                     echo $due['due'] != "" ? number_format($due['due'], 0, ",", ".") : "0" ?> DT</span><small>Total Success</small>
                </div>
            </td>
        </tr>
    </table>
    <table align="center" class="progress-table">
        <tr>
            <td style="height:150px"><a href="superorders.php?status=Success"><canvas id="counter1" width="150" height="150"></canvas></a></td>
            <td><a href="superorders.php?status=Pending&status2=In process"><canvas id="counter2" width="150" height="150"></canvas> </a></td>
            <td><a href="superorders.php?status=Rejected&status2=Canceled"><canvas id="counter3" width="150" height="150"></canvas> </a></td>
        </tr>
        <tr>
            <?php
                $due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT COUNT(id) 'tot' FROM orders"));
                $tot = $due['tot'];
                if($tot == 0) {
                    $success = 0;
                    $success_percent = 0;
                    $progress = 0;
                    $progress_percent = 0;
                    $rejected = 0;
                    $rejected_percent = 0;
                } else {
                    $due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT COUNT(id) 'tot' FROM orders WHERE status = 'Success'"));
                    $success = $due['tot'];
                    $success_percent = round($success/$tot*100, 0);
                    $due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT COUNT(id) 'tot' FROM orders WHERE status = 'Pending' OR status = 'In process'"));
                    $progress = $due['tot'];
                    $progress_percent = round($progress/$tot*100, 0);
                    $due = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT COUNT(id) 'tot' FROM orders WHERE status = 'Canceled' OR status = 'Rejected'"));
                    $rejected = $due['tot'];
                    $rejected_percent = round($rejected/$tot*100, 0);
                }
            ?>
            <td><div style="margin-top: -105px;font-size: 25px;"><?php echo $success; ?><br /><div style="font-size:12px">SUCCESS</div><div style="font-size:10px"><?php echo $success_percent; ?>%</div></div></td>
            <td><div style="margin-top: -105px;font-size: 25px;"><?php echo $progress; ?><br /><div style="font-size:12px">PROCESSING</div><div style="font-size:10px"><?php echo $progress_percent; ?>%</div></div></td>
            <td><div style="margin-top: -105px;font-size: 25px;"><?php echo $rejected; ?><br /><div style="font-size:12px">REJECTED</div><div style="font-size:10px"><?php echo $rejected_percent; ?>%</div></div></div></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center"><br /><p>Total orders <strong><?php echo $tot; ?></strong></p></td>
        </tr>
    </table>
<script>
$(function() {
    drawCircle('#counter1', <?php echo $success_percent; ?> , 'green');
    drawCircle('#counter2', <?php echo $progress_percent; ?> , 'blue');
    drawCircle('#counter3', <?php echo $rejected_percent; ?> , 'red');                
});
                
function drawCircle(id, percentage, color) {
    var width = $(id).width();
	var height = $(id).height();
    var radius = parseInt(width/2.35);
    var position = width;
    var positionBy2 = position/2;
    var bg = $(id)[0];
	var bgClone = $(id).clone();
	id = id.split('#');
	$('#'+id[1]+'_gray').remove();
	$(bgClone).attr('id', id[1]+'_gray').css({'margin-left': -width+'px', 'z-index': 0, 'position': 'absolute'});
	var bgClone = $(bgClone)[0];
    var ctx = bg.getContext('2d');
    var ctxClone = bgClone.getContext('2d');
    var imd = null;
    var circ = Math.PI * 2;
    var quart = Math.PI / 2;
    ctx.beginPath();
    ctx.strokeStyle = color;
    ctx.lineCap = 'square';
    ctx.closePath();
    ctx.fill();
    ctx.lineWidth = 10;
    imd = ctx.getImageData(0, 0, position, position);
    var draw = function(current, ctxPass) {
        ctxPass.putImageData(imd, 0, 0);
        ctxPass.beginPath();
        ctxPass.arc(positionBy2, positionBy2, radius, -(quart), ((circ) * current) - quart, false);
        ctxPass.stroke();
    }
    draw(percentage / 100, ctx);
	ctxClone.beginPath();
    ctxClone.strokeStyle = '#ddd';
    ctxClone.lineCap = 'square';
    ctxClone.closePath();
    ctxClone.fill();
    ctxClone.lineWidth = 10;
    imd = ctxClone.getImageData(0, 0, position, position);
    draw(100, ctxClone);
	$('#'+id[1]).after(bgClone);
}
</script>
</body>
</html>