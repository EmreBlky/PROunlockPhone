<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}
if(!isset($_GET['ref']) or $_GET['ref'] == '') die("Must specify the order's Unique ID");

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT id, UID, admin_response_comments FROM orders WHERE UID = '{$_GET['ref']}'"));

?>
<html>
<head>
    <?php echo admin_common_head_with_title("Format Comments", 20, true) ?>
    <script>
        $(function() {
            $("#comments").summernote({
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
            //$("#comments").summernote('code', '<?php //echo nl2br($row['admin_response_comments']) ?>//');
            $(this).ajaxStart(function() {
                $('.overlayer').show();
            }).ajaxStop(function() {
                $('.overlayer').hide();
            });
            $("#order").on("submit", function() {
                var $form = $(this);
                var formdata = (window.FormData) ? new FormData($form[0]) : null;
                var data = (formdata !== null) ? formdata : $form.serialize();
                $.ajax({
                    url: 'updating_comments.php',
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: data,
                    success: function (response) {
                        console.log(response);
                        $('html, body').animate({
                            scrollTop: 0
                        }, 1000);
                        if(response.substr(0, 7) == "FAILURE") {
                            $("#failure").html("Update Failed :: " + response.substr(7));
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
                    }
                });
                return false;
            });
        });
    </script>
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
</head>
<body>
<?php
require_once('superheader.php');
?>
    <div align="center" style="margin-top:80px;width:100%">
        <div id="success_div" style="display:none;margin-bottom:10px;width:72%;border:solid 1px green;border-radius:5px;background-color: ghostwhite;color:green"><h3>Order successfully updated</h3></div>
        <div id="failure_div" style="display:none;margin-bottom:10px;width:72%;border:solid 2px red;border-radius:5px;background-color:pink;color:red"><h3 id="failure"></h3></div>
        <h3>Format comments for order #<?php echo $row['UID'] ?></h3>
        <div style="font-size:20px;text-align:left;width:72%;margin-top:10px;margin-bottom:50px">
            <form id="order" method="post">
                <fieldset>
                    <input type="hidden" id="id" name="id" value="<?php echo $row['id'] ?>" />
                    <label class="titles" for="details">Details</label>
                    <textarea rows="7" class="form-control" id="comments" name="comments" class="textarea ui-widget-content ui-corner-all"><?php echo $row['admin_response_comments'] ?></textarea>
                </fieldset>
                <input class="form-control" style="height:40px;margin-top:30px" type="submit" value="Update Comments" />
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