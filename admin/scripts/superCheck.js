$(function() {
    $(this).ajaxStart(function() {
        $('.overlayer').show();
    }).ajaxStop(function() {
        $('.overlayer').hide();
    });
        
    $("#order").on("submit", function() {
        $('#results').fadeOut('2000');
        $('#server').fadeOut('2000');
        $('#low').fadeOut('2000');
        $("#bulk").css({
            "border": "solid 1px gray"
        });        
         if($("#bulk").val() != "") {
            $("#tips").html("Please try filling all fields or the processing will be delayed until we do it manually.").css("color", "black");
            $("#bulk").css({
                "border": "solid 1px gray"
            });
            $('.overlayer').show();
            var $form = $("#order");
            var formdata = (window.FormData) ? new FormData($form[0]) : null;
            var data = (formdata !== null) ? formdata : $form.serialize();
            $.ajax({
                url: 'superCheck.php', // $form.attr('action'),
                type: 'POST', //$form.attr('method'),
                contentType: false, // obligatoire pour de l'upload
                processData: false, // obligatoire pour de l'upload
                data: data,
                success: function (response) {
                    $('#low').html(response);
                    $('#low').fadeIn('5000');
                    $("#bulk").val("");
                }
            });
        } else {
            updateTips('Bulk field must be filled. Place IMEI and/or SN in order to execute the check.');
            $("#bulk").css("border", "solid 2px red").select();
        }
        return false;
    });
});

function updateTips(tip) {
    $("#tips").html(tip).css("color", "red");
    $('html, body').animate({
        scrollTop: 0
    }, 1000);
}