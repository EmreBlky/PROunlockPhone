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
    <?php echo admin_common_head_with_title("Order Price", "20") ?>
    <style>
        .overlayer {
            width:100%;
            height:100%;
            position:fixed;
            top:0;
            left:0;
            background:url('White_transparent.png');
            background: -moz-linear-gradient(rgba(255,255,255,0.6), rgba(255,255,255,0.6)) repeat-x rgba(255,255,255,0.6);
            background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(255,255,255,0.6)), to(rgba(255,255,255,0.6))) repeat-x rgba(255,255,255,0.6);
            z-index:10000000;
        }
        .loading {
            position:fixed;
            opacity:1.0;
            -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
            filter:alpha(opacity=100);
            left:50%;
            top:50%;
            margin:-70px 0 0 -144px;
        }
    </style>
    <script>
        $(function() {
            $(this).ajaxStart(function() {
                $('.overlayer').show();
            }).ajaxStop(function() {
                $('.overlayer').hide();
            });

            $("#update").on("click", function() {
                $.ajax({
                    type: 'POST',
                    url: 'https://www.prounlockphone.com/admin/updateOrderPrice.php',
                    data: 'id=<?php echo $_GET['id'] ?>&price=' + $("#price").val(),
                    success: function (response) {
                        alert(response);
                    }
                });
            });
        });
    </script>
</head>
<body>
<?php
require_once('superheader.php');
$req = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT price FROM orders WHERE id = {$_GET['id']}"));
?>
    <div align="center" style="margin-top:80px;width:100%">
        <div align="left" style="width:40%">
            <fieldset>
                <input type="hidden" id="id" value="<?php echo $_GET['id'] ?>" />
                <label class="titles" for="price">Price</label>
                <input class="form-control" type="text" name="price" id="price" value="<?php echo $req['price'] ?>" />
            </fieldset>
            <input class="form-control" style="height:40px;margin-top:30px;margin-bottom:50px" type="button" id="update" value="Save" />
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