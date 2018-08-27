<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?>
<form id="verifier" class="form" style="margin-bottom: 0;" method="POST" action="https://www.prounlockphone.com/orders/verify-do.php">
    <input type="hidden" name='id' value='<?php echo $_GET['id'] ?>' />
    <div class='modal-header'>
        <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>×</button>
        <h4 class='modal-title'>Sending Check Request</h4>
    </div>
    <div class='modal-body'>
        <div class='form-group'>
            <label>We'd like to know the reasons of such decision</label>
        </div>
        <div>
            <input name='completed' type='checkbox' class='js-switch' data-switchery='true' value="1" />
            <label>Order is not completed</label>
        </div>
        <div>
            <input name='expected' type='checkbox' class='js-switch' data-switchery='true' value="1">
            <label>Results do not match what you expected</label>
        </div>
        <div>
            <input name='unsatisfied' type='checkbox' class='js-switch' data-switchery='true' value="1">
            <label>Protest results, unsatisfied</label>
        </div>
        <div>
            <input name='submit' type='checkbox' class='js-switch' data-switchery='true' value="1">
            <label>You did not submit this order</label>
        </div>
        <div>
            <input name='reprocess' type='checkbox' class='js-switch' data-switchery='true' value="1">
            <label>Code / Results can't be read, need to reprocess</label>
        </div>
        <div class='form-group'>
            <label>Other reasons</label>
            <textarea name="other" type="text" class="form-control" placeholder="Add your own reasons here" style='width:100%'></textarea>
        </div>
    <div class='modal-footer'>
        <button class='btn btn-primary' id="confirm">Confirm</button>
        <button class='btn btn-default' data-dismiss='modal' type='button' id="close">Close</button>
    </div>
</form>
<script>
$(document).ready(function () {
    var elems = document.querySelectorAll('.js-switch');

    for (var i = 0; i < elems.length; i++) {
        if (!$(elems[i]).next('.switchery').length) {
            var switchery = new Switchery(elems[i], {
                size: 'small',
                color: '#418bca'
            });
        }
    }

    $('#verifier').submit(function(){
        $('#confirm').hide();
        $('#close').hide();
        $('#confirm').closest('div').append('<img class="loading" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
        return true;
    });

    // $('#confirm').on('click', function (ev) {
    //     ev.preventDefault();
    //     $(this).hide();
    //     $('#close').hide();
    //     $(this).closest('div').append('<img class="loading" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    //     $('#verifier').submit();
    //     alert($('#verifier').html());
    //     alert('done');
    // });
});
</script>