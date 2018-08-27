$(function() {
    $(this).ajaxStart(function() {
        $('.overlayer').show();
    }).ajaxStop(function() {
        $('.overlayer').hide();
    });

    $("#imei").on("keyup keypress blur change paste", function(){
        var $imei = $(this).val();
        if(!isNaN($imei) && $imei.length == 14) {
            var total = 0;
            var i = 0;
            while(i < 14) {
                total = total + Number($imei[i]);
                i = i + 2;
            }
            i = 1;
            while(i < 14) {
                if(Number($imei[i]) > 4) {
                    switch(Number($imei[i])) {
                        case 5:
                            total = total + 1;
                            break;
                        case 6:
                            total = total + 3;
                            break;
                        case 7:
                            total = total + 5;
                            break;
                        case 8:
                            total = total + 7;
                            break;
                        case 9:
                            total = total + 9;
                            break;
                    }
                } else {
                    total = total + Number($imei[i]) * 2;
                }
                i = i + 2;
            }
            var last_digit = Math.ceil(total / 10) * 10 - total;
            $("#last_digit").val(last_digit);
            $("#imei").css({
                "color": "green",
                "border": "solid 1px gray"
            });
            $.get('Check_IMEI.php?imei=' + $imei + last_digit + '&service=' + $("#service").val(), function( response ) {
                if(response != "0") {
                    //$("#last_digit").val("");
                    //$("#imei").css("border", "solid 2px red").select();
                    updateTips("IMEI already ordered, please contact Administrator.<br />You still can place your order but make sure you are not double ordering the same service or ensure that you solved any previous issue that may caused rejection.");
                } //else {
                //}
            });
        } else {
            $("#last_digit").val("");
            $(this).css("color", "red");
        }
    });
    $('#photo').change(function (e) {
        var f = this.files[0];
        if (f.size > 4194304 || f.fileSize > 4194304) {
            alert("Allowed file size exceeded. (Max. 4 MB)")
            this.value = null;
        } else if(f.type != 'image/png' && f.type != 'image/jpg' && f.type != 'image/bmp' && f.type != 'image/gif' && f.type != 'image/jpeg' ) {
            alert("File doesnt match png, bmp, jpg or gif");
            this.value = null;
        } else {
            $('#image_preview').attr('src', window.URL.createObjectURL(f))
                                .css('display', 'block');
        }
    });
    $("#order").on("submit", function() {
        
        $("#imei").css({
            "color": "red",
            "border": "solid 1px gray"
        });
        $("#serial").css({
            "color": "black",
            "border": "solid 1px gray"
        });
        $("#udid").css({
            "color": "black",
            "border": "solid 1px gray"
        });
        $("#account").css({
            "color": "black",
            "border": "solid 1px gray"
        });
        $("#photo").css({
            "border": "solid 1px gray"
        });
        $("#phone").css({
            "color": "black",
            "border": "solid 1px gray"
        });
        $("#bulk").css({
            "border": "solid 1px gray"
        });
        
        var valid = true;
        
        if($("#imei_zone").css("display") != "none") {
            if($("#last_digit").val() == "") {
                if($("#bulk_zone").css("display") == "none" || (($("#bulk_zone").css("display") != "none") && $("#bulk").val() == "")) {
                    updateTips('Please enter a valid IMEI to save your order.');
                    $("#imei").css("border", "solid 2px red").select();
                }
                else if(($("#bulk_zone").css("display") != "none") && !valid_bulk()) {
                    updateTips('Please check the validity of the entred IMEIs.');
                    $("#bulk").css("border", "solid 2px red").select();
                }
            } else if($("#bulk").val() != "") {
                updateTips('You must either use the <b>IMEI</b> field for single order or the <b>BULK</b> field for multi orders.');
                $("#imei").css("border", "solid 2px red").select();
                $("#bulk").css("border", "solid 2px red").select();
            }
            valid = ($("#last_digit").val() != "" && ($("#bulk_zone").css("display") == "none" || (($("#bulk_zone").css("display") != "none") && $("#bulk").val() == ""))) || ($("#last_digit").val() == "" && valid_bulk());
        }
        if(valid && $("#sn_zone").css("display") != "none") {
            if(!valid_bulk2() && $("#bulk").val() != "") {
                updateTips('Please check the validity of the entred SNs.');
                $("#bulk").css("border", "solid 2px red").select();
                valid = false;
            }
            if($("#bulk").val() == "" && ($("#serial").val().length < 11 || $("#serial").val().length > 15)) {
                updateTips('Please type the SN of the device.');
                $("#serial").css({
                    "border": "solid 2px red",
                    "color": "red"
                }).select();
                valid = false;
            }
        }
        if(valid && $("#udid_zone").css("display") != "none") {
            if($("#udid").val().length < 40) {
                updateTips('Please type the UDID of the device.');
                $("#udid").css({
                    "border": "solid 2px red",
                    "color": "red"
                }).select();
                valid = false;
            } else {
                $("#udid").css({
                    "border": "solid 1px gray",
                    "color": "black"
                });
            }
        }
        if(valid && $("#phone_zone").css("display") != "none") {
            if($("#phone").val().length < 6) {
                updateTips('Please type the phone number.');
                $("#phone").css({
                    "border": "solid 2px red",
                    "color": "red"
                }).select();
                valid = false;
            } else {
                $("#phone").css({
                    "border": "solid 1px gray",
                    "color": "black"
                });
            }
        }
        if(valid && $("#account_zone").css("display") != "none") {
            var emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            if(!emailRegex.test($("#account").val())) {
                updateTips('Please type the Apple ID of the device.');
                $("#account").css({
                    "border": "solid 2px red",
                    "color": "red"
                }).select();
                valid = false;
            } else {
                $("#account").css({
                    "border": "solid 1px gray",
                    "color": "black"
                });
            }
        }
        if(valid && $("#photo_zone").css("display") != "none") {
            if($("#photo").val() == "") {
                updateTips('Please upload the screenshot of the device.');
                $("#photo").css({
                    "border": "solid 2px red",
                    "color": "red"
                }).select();
                valid = false;
            } else {
                $("#photo").css({
                    "border": "solid 1px gray",
                    "color": "black"
                });
            }
        }
        
        if(valid) {
            $('.overlayer').show();
            var $form = $("#order");
            var formdata = (window.FormData) ? new FormData($form[0]) : null;
            var data = (formdata !== null) ? formdata : $form.serialize();
            $.ajax({
                url: 'Place_Ordre.php', // $form.attr('action'),
                type: 'POST', //$form.attr('method'),
                contentType: false, // obligatoire pour de l'upload
                processData: false, // obligatoire pour de l'upload
                data: data,
                success: function (response) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);
                    if(response == "Failure") {
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
                    $("#tips").html("Please try filling all fields or the processing will be delayed until we do it manually.").css("color", "black");
                    $("#imei").css({
                        "color": "red",
                        "border": "solid 1px gray"
                    }).val("");
                    $("#last_digit").val("");
                    $("#serial").css({
                        "color": "black",
                        "border": "solid 1px gray"
                    }).val("");
                    $("#udid").css({
                        "color": "black",
                        "border": "solid 1px gray"
                    }).val("");
                    $("#account").css({
                        "color": "black",
                        "border": "solid 1px gray"
                    }).val("");
                    $("#photo").css({
                        "border": "solid 1px gray"
                    }).val("");
                    $("#phone").css({
                        "color": "black",
                        "border": "solid 1px gray"
                    }).val("");
                    $("#bulk").css({
                        "border": "solid 1px gray"
                    }).val("");
                    $("#image_preview").hide();
                    $("#client_order_comments").val("");
                    $("#client_personal_notes").val("");
                    $("#ebayer").val("");
                    $("#tracker").val("");
                    $("#owner_name").val("");
                }
            });
        }
        return false;
    });
    $(this).ajaxStart(function() {
        $('.overlayer').show();
    }).ajaxStop(function() {
        $('.overlayer').hide();
    });
});
function updateTips(tip) {
    $("#tips").html(tip).css("color", "red");
    $('html, body').animate({
        scrollTop: 0
    }, 1000);
}
function valid_bulk() {
    var bulk = $("#bulk");
    if(bulk.css("display") == "none") {
        return false;
    } else if(bulk.val() == "") {
        return false;
    } else {
        var imeis = bulk.val().split(/\r\n|\r|\n/g);
        var valid = true;
        $.each(imeis, function(index, value) {
            valid = valid && calculate_last_digit(value);
        });
        return valid;
    }
}
function valid_bulk2() {
    var bulk = $("#bulk");
    if(bulk.css("display") == "none") {
        return false;
    } else {
        var sns = bulk.val().split(/\r\n|\r|\n/g);
        var valid = true;
        $.each(sns, function(index, value) {
            valid = valid && value.length > 10 && value.length < 16;
        });
        return valid;
    }
}
function calculate_last_digit(imei) {
    if(!isNaN(imei) && imei.length == 15) {
        var total = 0;
        var i = 0;
        while(i < 14) {
            total = total + Number(imei[i]);
            i = i + 2;
        }
        i = 1;
        while(i < 14) {
            if(Number(imei[i]) > 4) {
                switch(Number(imei[i])) {
                    case 5:
                        total = total + 1;
                        break;
                    case 6:
                        total = total + 3;
                        break;
                    case 7:
                        total = total + 5;
                        break;
                    case 8:
                        total = total + 7;
                        break;
                    case 9:
                        total = total + 9;
                        break;
                }
            } else {
                total = total + Number(imei[i]) * 2;
            }
            i = i + 2;
        }
        return imei[14] == Math.ceil(total / 10) * 10 - total;
    } else {
        return false;
    }
}