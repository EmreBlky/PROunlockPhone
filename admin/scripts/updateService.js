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
        $("#details").val($("#dtls").summernote('code'));
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        $.ajax({
            url: 'updatingService.php',
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function (response) {
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                if(response.substr(0, 7) == "FAILURE") {
                    $("#failure").html("Update procedure failed :: " + response.substr(7));
                    $("#failure_div").slideDown("slow");
                    setTimeout(function() {
                        $("#failure_div").slideUp("slow");
                    }, 3000);
                } else {
                    $("#success_div").slideDown("slow");
                    //$("#success").html(response);
                    setTimeout(function() {
                        $("#success_div").slideUp("slow");
                    }, 3000);
                }
            }
        });
        return false;
    });
    $("#reseller_USD").on("focus", function() {
        $(this).select();
    }).on("change", function() {
        convert($(this).val())
    });
    $("#regular_USD").on("focus", function() {
        $(this).select();
    }).on("change", function() {
        convert($(this).val())
    });
    $("#set0").on("click", function() {
        values = setValues($("#price_10o").html());
        $("#reseller_USD").val(values[0]);
        $("#reseller_EUR").val(values[1]);
        $("#reseller_GBP").val(values[2]);
        $("#reseller_TND").val(values[3]);

        values = setValues($("#price_15o").html());
        $("#regular_USD").val(values[0]);
        $("#regular_EUR").val(values[1]);
        $("#regular_GBP").val(values[2]);
        $("#regular_TND").val(values[3]);
    });
    $("#set5").on("click", function() {
        values = setValues($("#price_5_10").html());
        $("#reseller_USD").val(values[0]);
        $("#reseller_EUR").val(values[1]);
        $("#reseller_GBP").val(values[2]);
        $("#reseller_TND").val(values[3]);

        values = setValues($("#price_5_15").html());
        $("#regular_USD").val(values[0]);
        $("#regular_EUR").val(values[1]);
        $("#regular_GBP").val(values[2]);
        $("#regular_TND").val(values[3]);
    });
    $("#org_price").on("keyup", function() {
        compute($(this).val())
    });
    $("#org_priceEUR").on("keyup", function() {
        compute2($(this).val())
    });
});
function setValues(val) {
    return [treatFraction(val), treatFraction(val * 0.95 / 96 * 100), treatFraction(val * 0.81 / 96 * 100), treatFraction2(val * 0.95 * 3)];
}
function treatFraction(val) {
    fraction = (val - Math.floor(val)) * 100;
    if(fraction == 0) {
        return val;
    } else if(fraction <= 25) {
        return parseFloat(Math.floor(val)) + parseFloat(0.25);
    } else if(fraction <= 50) {
        return parseFloat(Math.floor(val)) + parseFloat(0.5);
    } else if(fraction <= 75) {
        return parseFloat(Math.floor(val)) + parseFloat(0.75);
    } else {
        return parseFloat(Math.floor(val)) + parseFloat(1);
    }
}
function treatFraction2(val) {
    fraction = (val - Math.floor(val)) * 100;
    if(fraction == 0) {
        return val;
    } else if(fraction <= 50) {
        return parseFloat(Math.floor(val)) + parseFloat(0.5);
    } else {
        return parseFloat(Math.floor(val)) + parseFloat(1);
    }
}
function compute2(val) {
    conv = val / $('#xrate').val();
    $("#org_price").val(conv.toFixed(2));
    $("#org_price").keyup();
    conv = val / 100 * 115;
    $("#price_15oEUR").html(conv.toFixed(2));
    conv = conv / 100 * 105;
    $("#price_5_15EUR").html(conv.toFixed(2));
    conv = val / 100 * 110;
    $("#price_10oEUR").html(conv.toFixed(2));
    conv = conv / 100 * 105;
    $("#price_5_10EUR").html(conv.toFixed(2));
}
function compute(val) {
    conv = val / 100 * 115;
    $("#price_15o").html(conv.toFixed(2));
    conv = conv / 100 * 105;
    $("#price_5_15").html(conv.toFixed(2));
    conv = val / 100 * 110;
    $("#price_10o").html(conv.toFixed(2));
    conv = conv / 100 * 105;
    $("#price_5_10").html(conv.toFixed(2));
}
function convert(val) {
    $("#USD").html(val);
    conv = val * 0.95 / 96 * 100;
    $("#EUR").html(conv.toFixed(2));
    conv = val * 0.95 * 3;
    $("#TND").html(conv.toFixed(2));
    conv = val * 0.81 / 96 * 100;
    $("#GBP").html(conv.toFixed(2));
}
function revoke(id, obj) {
    if(confirm("You are about to delete this exception.\nClick 'Yes' to confirm.")) {
        $.get('drop_exception.php?id=' + id, function() {
            el = obj.parentElement.parentElement
            el.parentElement.removeChild(el);
        });
    }
}