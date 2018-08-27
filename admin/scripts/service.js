$(function() {
    $("#minutes-select").on("change", function() {
        $("#minutes").val($(this).val());
    });
    $("#hours-select").on("change", function() {
        $("#hours").val($(this).val());
    });
    $("#days-select").on("change", function() {
        $("#days").val($(this).val());
    });
    $("#suggest-short").on("click", function() {
        $("#short-list").slideToggle("2000");
    });
    $("#suggest-group").on("click", function() {
        $("#group-list").slideToggle("2000");
    });
    $("#suggest-provider").on("click", function() {
        $("#provider-list").slideToggle("2000");
    });
    $("#suggest-models").on("click", function() {
        $("#models-list").slideToggle("2000");
    });
    $("#short-list").on("change", function() {
        $("#short_name").val($(this).val());
        $(this).slideToggle();
    });
    $("#group-list").on("change", function() {
        $("#service_group").val($(this).val());
        $(this).slideToggle();
    });
    $("#provider-list").on("change", function() {
        $("#provider").val($(this).val());
        $(this).slideToggle();
    });
    $("#models-list").on("change", function() {
        $("#models").val($(this).val());
        $(this).slideToggle();
    });
    $(this).ajaxStart(function() {
        $('.overlayer').show();
    }).ajaxStop(function() {
        $('.overlayer').hide();
    });
    $("#service").on("submit", function() {
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        $.ajax({
            url: 'adding_service.php',
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function (response) {
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                if(response.substr(0, 7) == "FAILURE") {
                    $("#failure").html("Service not added :: " + response.substr(7));
                    $("#failure_div").slideDown("slow");
                    setTimeout(function() {
                        $("#failure_div").slideUp("slow");
                    }, 3000);    
                } else {
                    $("#success_div").slideDown("slow");
                    $("#success").html(response);
                    setTimeout(function() {
                        $("#success_div").slideUp("slow");
                    }, 3000);
                }
            }
        });
        return false;
    });
});