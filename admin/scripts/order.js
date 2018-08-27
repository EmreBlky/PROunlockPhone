$(function() {
    $(this).ajaxStart(function() {
        $('.overlayer').show();
    }).ajaxStop(function() {
        $('.overlayer').hide();
    });

    $("#update").on("click", function() {
        $.ajax({
            type: 'POST',
            url: 'https://www.prounlockphone.com/admin/updateOrder.php',
            data: 'id=' + $("#id").val() + '&backupLink=' + $("#backupLink").val() + '&backupPwd=' + $("#backupPwd").val() + '&videoLink=' + $("#videoLink").val() + '&fileLink=' + $("#fileLink").val() + '&service=' + $("#service").val() + '&imei=' + $("#imei").val() + '&serial=' + $("#serial").val().toUpperCase() + '&udid=' + $("#udid").val().toLowerCase() + '&phone=' + encodeURIComponent($("#phone").val()) + '&account=' + $("#account").val().toLowerCase() + '&ebayer=' + $("#ebayer").val().toLowerCase() + '&tracker=' + $("#tracker").val().toLowerCase() + '&owner_name=' + $("#owner_name").val(),
            success: function (response) {
                alert(response);
            }
        });
    });
});