<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?>
<form id="verifierCancel" class="form" style="margin-bottom: 0;" method="POST" action="https://www.prounlockphone.com/orders/cancel-do.php">
    <input type="hidden" name='id' value='<?php echo $_GET['id'] ?>' />
    <div class='modal-header'>
        <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>×</button>
        <h4 class='modal-title'>Sending Cancel Request</h4>
    </div>
    <div class='modal-body'>
        <div class='form-group'>
            <label>We'd like to know the reasons of such decision</label>
        </div>
        <div>
            <input name='time' type='checkbox' class='js-switch' data-switchery='true' value="1" />
            <label>Order passed the posted processing time</label>
        </div>
        <div>
            <input name='stop' type='checkbox' class='js-switch' data-switchery='true' value="1">
            <label>You need to stop at any cost, maybe due to your client or another reason</label>
        </div>
        <div>
            <input name='wrong' type='checkbox' class='js-switch' data-switchery='true' value="1">
            <label>Wrong service, willing to order the appropriate one</label>
        </div>
        <div>
            <input name='completed' type='checkbox' class='js-switch' data-switchery='true' value="1">
            <label>The order is completed and no need to the service</label>
        </div>
        <div>
            <input name='cheaper' type='checkbox' class='js-switch' data-switchery='true' value="1">
            <label>You found cheaper service</label>
        </div>
        <div class='form-group'>
            <label>Other reasons</label>
            <textarea name="other" type="text" class="form-control" placeholder="Add your own reasons here" style='width:100%'></textarea>
        </div>
    <div class='modal-footer'>
        <button class='btn btn-primary' id="confirmCancel">Confirm</button>
        <button class='btn btn-default' id="closeCancel" data-dismiss='modal' type='button'>Close</button>
    </div>
</form>
<script>
$(document).ready(function ($) {
    var elems = document.querySelectorAll('.js-switch');

    for (var i = 0; i < elems.length; i++) {
        if (!$(elems[i]).next('.switchery').length) {
            var switchery = new Switchery(elems[i], {
                size: 'small',
                color: '#418bca'
            });
        }
    }

    $('#verifierCancel').submit(function(){
        $('#confirmCancel').hide();
        $('#closeCancel').hide();
        $('#confirmCancel').closest('div').append('<img class="loading" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
        return true;
    });
});
</script>