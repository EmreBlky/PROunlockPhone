$(function() {
    $(".pintotop").change(function() {
        var pintotop = $(this).prop("checked") == true ? 1 : 0;
        $.get('updatePinToTop.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&pintotop=' + pintotop + '&quick=yes');
        if(pintotop == 0) {
            $(this).parent().parent().parent().css('background-color', 'white');
        } else {
            $(this).parent().parent().parent().css('background-color', 'bisque');
        }
    });
    $(".message").on("click", function() {
        if($(this).html() == "Show message") {
            $(this).html("Hide message").prev().show("slow");
        } else {
            $(this).html("Show message").prev().hide("slow");
        }
    });
    $("#searchTable").submit(function() {
        if($('#IMEIsearch').val() == '' && $('#SNsearch').val() == '' && $('#relative_id').val() == '' && $('#nameSearch').val() == '' && $('#buyerSearch').val() == '' && $('#payerSearch').val() == '') {
            alert("Please fill at least one of the fields to make a valid query!");
            return false
        }
    });
    $(this).ajaxStart(function() {
        $('.overlayer').show();
    }).ajaxStop(function() {
        $('.overlayer').hide();
    });
    $("#searchRevealer").click(function() {
        $("#searchTable").slideToggle()
        $('#IMEIsearch').select();
    });
    var variation;
    $(".no_comments").click(function(){
        $(this).hide().next().next().show().children('.comments').select().css({
            'height': '300px',
            'width': '300px'
        });
    });
    $(".hidden_comments").click(function() {
        $(this).hide().next().show().children('.comments').select().css({
            'height': '300px',
            'width': '300px'
        });
    });
    $(".reset").click(function() {
        $textarea = $(this).prev().prev();
        var remarque = $textarea.val();
        if(remarque == "") $textarea.parent().hide().prev().prev().show();
        else {
            if(remarque.substring(0, 4).toLowerCase() == '<pre') {
                remarque = remarque.substr(0, remarque.length - 6);
                remarque = remarque.substring(remarque.indexOf('>') + 1);
            }
            $textarea.parent().hide().prev().html(remarque).show();
        }
    });
    $(".comments").focus(function() {
        variation = $(this).val();
    });
    $(".admin_comments").blur(function() {
        if($(this).val() == variation) return false;
        $.get('saveAdminComments.php?id=' + $(this).parent().parent().parent().find( ":hidden" ).val() + '&field=admin_response_comments&data=' + encodeURIComponent($(this).val()) + '&quick=yes');
    });
    $(".admin_notes").blur(function() {
        if($(this).val() == variation) return false;
        $.get('saveAdminComments.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&field=admin_private_notes&data=' + encodeURIComponent($(this).val()) + '&quick=yes');
    });
    // var variation;
    // $(".admin_comments").focus(function() {
    //     variation = $(this).val();
    // }).blur(function() {
    //     if($(this).val() == variation) return false;
    //     $.get('saveAdminComments.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&field=admin_response_comments&data=' + encodeURIComponent($(this).val()) + '&quick=yes');
    // });
    // $(".admin_notes").focus(function() {
    //     variation = $(this).val();
    // }).blur(function() {
    //     if($(this).val() == variation) return false;
    //     $.get('saveAdminComments.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&field=admin_private_notes&data=' + encodeURIComponent($(this).val()) + '&quick=yes');
    // });
    $(".status").click(function() {
        $.post('update_quickOrder.php?id=' + $(this).parent().parent().find(":hidden").val() + '&data=' + $(this).html());
        $(this).parent().css("backgroundColor", $(this).css("backgroundColor"));
        $(".status").attr("disabled", false);
        $(this).attr("disabled", true);
        if($(this).html() != "In process" && $(this).html() != "Pending") {
            $(this).parent().parent().css("backgroundColor", "white").find(".revoke").remove();
            var text = $(this).parent().parent().find("textarea").eq(0);
            if(text.text() == "CANCEL REQUEST PENDING") {
                text.text("");
            } else if(text.text().substr(0, 23) == "CANCEL REQUEST PENDING\n") {
                text.text(text.text().substr(23));
            } else if(text.text().substr(0, 22) == "CANCEL REQUEST PENDING") {
                text.text(text.text().substr(22));
            }
        }
    });
    $(".payment_status").click(function() {
        $.post('update_paymentStatus.php?id=' + $(this).parent().parent().find(":hidden").val() + '&data=' + $(this).html());
        $(this).parent().css("backgroundColor", $(this).css("backgroundColor"));
        $(".payment_status").attr("disabled", false);
        $(this).attr("disabled", true);
    });
});