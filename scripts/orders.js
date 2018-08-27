$(function() {
    $(this).ajaxStart(function() {
        $('.overlayer').show();
    }).ajaxStop(function() {
        $('.overlayer').hide();
    });
    $(".details").on('click', function() {
        if($(this).parent().parent().next().css("display") == 'none') $(".details_line").hide();
        $(this).parent().parent().next().fadeToggle("slow");
        var tiptop = $(this).offset().top - 148;
        $('html, body').animate({
            scrollTop: tiptop
        }, 1000);
    });
    $(".message").on("focus", function() {
        $(this).css({
            "backgroundColor": "yellow",
            "borderColor": "yellow"
        }).animate({
            borderWidth: "10px"
        }, 1000);
    });
});
function save(obj, id, type) {
    $.get('save_comments.php?id=' + id + '&type=' + type + '&comment=' + $(obj).val().replace(/\n\r?/g, '<br />'), function(response) {
        $(obj).css({
            "backgroundColor": "white",
            "borderColor": "white"
        }).animate({
            borderWidth: "1px"
        }, 1000);
    });
}
function sendCancelRequest(obj, id) {
    if($(obj).parent().parent().parent().parent().next().next().find('textarea').eq(1).val() != "") {
        if(confirm("Are the notes in 'notes for us' describe the reason of your request?\nIf not please add it before submitting.")) {
            $.post('sendCancelRequest.php?id=' + id, function(response) {
                var row = $(obj).parent().parent().parent().parent().parent().parent().prev();
                if(response == "done") {
                    row.css("backgroundColor", "#FFE1EB").find('.status').css({"backgroundColor": "black", "color": "white"}).html("In process");
                } else {
                    row.css("backgroundColor", "#FFE1EB");
                    var comment = row.children().eq(4);
                    comment.html("CANCEL REQUEST PENDING<br />" + comment.html());
                }
                $(obj).parent().html("");
            });
        } else {
            $(obj).parent().parent().parent().parent().next().next().find('textarea').eq(1).select();
        }
    } else {
        alert("Please add the reason of request in the field 'notes for us' before submitting.");
        $(obj).parent().parent().parent().parent().next().next().find('textarea').eq(1).focus();
    }
}
function sendCheckRequest(obj, id) {
    $.post('sendCheckRequest.php?id=' + id, function() {
        var row = $(obj).parent().parent().parent().parent().parent().parent().prev();
        row.css("backgroundColor", "#FFE1EB").find('.status').css("backgroundColor", "blue").html("In process");
        var comment = row.children().eq(4);
        comment.html("CHECK REQUEST PENDING<br />" + comment.html());
        $(obj).parent().html("");
    });
}