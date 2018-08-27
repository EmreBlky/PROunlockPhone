$(function() {
    $(".pintotop").change(function() {
        var pintotop = $(this).prop("checked") == true ? 1 : 0;
        $.get('updatePinToTop.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&pintotop=' + pintotop);
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
        if($('#IMEIsearch').val() == '' && $('#SNsearch').val() == '' && $('#eBayerSearch').val() == '' && $('#AppleIDsearch').val() == '' && $('#PhoneSearch').val() == '') {
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
    $(".no_comments, .hidden_comments").click(function() {
        $(this).hide();
        var that = $(this).next();
        if($(this).hasClass("no_comments")) that = that.next();
        that.show().children('.comments').summernote({
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
            minHeight: 200,
            height: 300,
            minWidth: 600,
            width: 600,
            callbacks : {
                onFocus : function(){
                    variation = $(this).val();
                },
                onBlur : function(){
                    if($(this).val() == variation) return false;
                    if($(this).summernote('isEmpty')) $(this).val("");
                    $.get('saveAdminComments.php?id=' + $(this).parent().parent().parent().find( ":hidden" ).val() + '&field=admin_' + ($(this).hasClass("admin_comments") ? "response_comments" : "private_notes") + '&data=' + encodeURIComponent($(this).val()));
                    // $.get('saveAdminComments.php?id=' + $(this).parent().parent().parent().find( ":hidden" ).val() + '&field=admin_response_comments&data=' + encodeURIComponent($(this).val()));
                }
            }
        }).summernote('focus');
    });
    // $(".hidden_comments").click(function() {
    //     $(this).hide().next().show().children('.comments').select().css({
    //         'height': '300px',
    //         'width': '300px'
    //     });
    // });
    $(".reset").click(function() {
        var offset = $(this).offset();
        offset.top -= 20;

        $textarea = $(this).parent().prev().prev();
        if($textarea.summernote('isEmpty')) {
            $textarea.val("");
            $textarea.parent().hide().prev().prev().show();
        }
        else {
            var remarque = $textarea.val();
            if(remarque.substring(0, 4).toLowerCase() == '<pre') {
                remarque = remarque.substr(0, remarque.length - 6);
                remarque = remarque.substring(remarque.indexOf('>') + 1);
            }
            $textarea.parent().hide().prev().html(remarque).show();
        }

        $('html, body').animate('scrollTop', offset.top);
    });
    $(".comments").focus(function() {
        variation = $(this).val();
    });
    $(".admin_comments").blur(function() {
        alert('now');
        if($(this).val() == variation) {
            alert('this');
            return false;
        }
        if($(this).summernote('isEmpty')) $(this).val("");
        $.get('saveAdminComments.php?id=' + $(this).parent().parent().parent().find( ":hidden" ).val() + '&field=admin_' + ($(this).hasClass("admin_comments") ? "response_comments" : "private_notes") + '&data=' + encodeURIComponent($(this).val()));
    });
    $(".admin_notes").blur(function() {
        if($(this).val() == variation) return false;
        $.get('saveAdminComments.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&field=admin_' + ($(this).hasClass("admin_comments") ? "response_comments" : "private_notes") + '&data=' + encodeURIComponent($(this).val()));
    });
    $("button").click(function() {
        var that = $(this);
        $.post('update_order.php?id=' + $(this).parent().parent().find(":hidden").val() + '&data=' + $(this).html(), function() {
            that.parent().css("backgroundColor", that.css("backgroundColor"));
            $("button").attr("disabled", false);
            that.attr("disabled", true);
            if(that.html() != "In process" && that.html() != "Pending") {
                that.parent().parent().css("backgroundColor", "white").find(".revoke").remove();
                var text = that.parent().parent().find("textarea").eq(0);
                if(text.text() == "CANCEL REQUEST PENDING") {
                    text.text("");
                } else if(text.text().substr(0, 23) == "CANCEL REQUEST PENDING\n") {
                    text.text(text.text().substr(23));
                } else if(text.text().substr(0, 22) == "CANCEL REQUEST PENDING") {
                    text.text(text.text().substr(22));
                }
            }
        });
        /*if($(this).parent().parent().find( ":hidden:eq(1)" ).val() != "58" && $(this).html() != "In process") {
            $.get('notify_client.php?order=' + $(this).parent().parent().find( ":hidden" ).val() + "&transaction=" + money, function(response) {
                alert(response);
            });
        }
        /*
        var money = "neutral";
        if((($(this).html() == "Rejected") || ($(this).html() == "Canceled")) && ($(this).parent().css("backgroundColor") != "rgb(34, 34, 34)") && ($(this).parent().css("backgroundColor") != "rgb(255, 0, 0)")) {
            if(confirm("Would you like to consider the refund?")) money = "refund";
        } else if((($(this).html() != "Rejected") && ($(this).html() != "Canceled")) && (($(this).parent().css("backgroundColor") == "rgb(34, 34, 34)") || ($(this).parent().css("backgroundColor") == "rgb(255, 0, 0)"))) {
            if(confirm("Do you want to recharge the order?")) money = "recharge";
        }
        $.get('update_order.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&field=status&data=' + $(this).html() + "&transaction=" + money);
        $(this).parent().css("backgroundColor", $(this).css("backgroundColor"));
        $("button").attr("disabled", false);
        $(this).attr("disabled", true);
        if(confirm("Send client notification?")) {
            $.get('notify_client.php?order=' + $(this).parent().parent().find( ":hidden" ).val() + "&transaction=" + money);
        }
        */
    });
    $(".revoke").click(function() {
        $.post('revokeCancelRequest.php?id=' + $(this).parent().parent().find(":hidden").val());
        var text = $(this).parent().parent().find("textarea").eq(0);
        if(text.text() == "CANCEL REQUEST PENDING") {
            text.text("");
        } else if(text.text().substr(0, 23) == "CANCEL REQUEST PENDING\n") {
            text.text(text.text().substr(23));
        } else if(text.text().substr(0, 22) == "CANCEL REQUEST PENDING") {
            text.text(text.text().substr(22));
        }
        $(this).parent().parent().css("backgroundColor", "white").find(".revoke").remove();
    });
    $(".confirm").click(function() {
        $.post('revokeCheckRequest.php?id=' + $(this).parent().parent().find(":hidden").val());
        var text = $(this).parent().parent().find("textarea").eq(0);
        if(text.text() == "CHECK REQUEST PENDING") {
            text.text("");
        } else if(text.text().substr(0, 22) == "CHECK REQUEST PENDING\n") {
            text.text(text.text().substr(22));
        } else if(text.text().substr(0, 21) == "CHECK REQUEST PENDING") {
            text.text(text.text().substr(21));
        }
        $(this).parent().parent().css("backgroundColor", "white").find(".confirm").remove();
    });
});