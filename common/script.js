$(document).on('click', '.submit', function () {
    var error = false;
    if($(this).html() == "Add") {
        if($("#email").val() == 0) {
            $.jGrowl("eMail address cannot be empty.", {theme: 'growlFail'});
            $('#email').focus();
            return false;
        }
    } else if($(this).html() == "Find") {
        if($("#imei").val().length < 7) {
            $.jGrowl("IMEI must be 15 characters.<br />S/N must be at least 11 characters.<br />Order ID must be 7 characters.", {theme: 'growlFail'});
            $('#imei').select();
            error = true;
        }
        if(error) return false;
        var result = ""; 
        $.ajax({
            method: "POST",
            url: "order-exist.php",
            async: false,
            data: {
                imei: $('#imei').val()
            }
        }).done(function(response) {
            result = response;
        });
        if(result == "0") {
            $.jGrowl("No such order was found in our records!", {theme: 'growlFail'});
            $('#imei').select();
            return false;
        }
        $(this).hide().after('<img class="loading" style="float:right;width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    } else if($(this).html() == "Reset") {
        if($("#old").val().length < 6) {
            $.jGrowl("You must indicate your current password.<br />If you you forgot your password, you can reset it by requesting a link to be sent to your emailbox by visiting this <a href='https://www.prounlockphone.com/forgot/' target='_blank'>link</a>.", {theme: 'growlFail'});
            $('#old').focus();
            return false;
        }
        if($("#password").val().length < 6) {
            $.jGrowl("Password must be at least 6 characters", {theme: 'growlFail'});
            $('#password').val("").select();
            $("#confirm").val("");
            return false;
        }
        if($("#confirm").val() != $("#password").val()) {
            $.jGrowl("Passwords do not match", {theme: 'growlFail'});
            $('#password').val("").select();
            $("#confirm").val("");
            return false;
        }
        if($("#old").val() == $("#password").val()) {
            $.jGrowl("Your new password is the same as your old password!<br />No changes made.", {theme: 'growlFail'});
            $('#password').val("").select();
            $("#confirm").val("");
            return false;
        }
        $(this).hide().closest('div').append('<img class="loading pull-right" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    } else if($(this).html() == "Set a new password") {
        if($("#password").val().length < 6) {
            $.jGrowl("Password must be at least 6 characters", {theme: 'growlFail'});
            $('#password').val("").select();
            $("#confirm").val("");
            return false;
        }
        if($("#confirm").val() != $("#password").val()) {
            $.jGrowl("Passwords do not match", {theme: 'growlFail'});
            $('#password').val("").select();
            $("#confirm").val("");
            return false;
        }
        $(this).hide().closest('div').append('<img class="loading pull-right" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    } else if($(this).html() == "Submit") {
        if($("#identifier").val().length < 6) {
            $.jGrowl("Username / eMail should not be less than 6 characters", {theme: 'growlFail'});
            $('#identifier').select();
            return false;
        }
        $(this).hide().after('<img class="loading" style="float:right;width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    } else if($(this).html() == "Activate") {
        $("#firstname").val($("#firstname").val().trim());
        if($("#firstname").val().length < 2) {
            $.jGrowl("First name must be at least 2 characters", {theme: 'growlFail'});
            $('#firstname').select();
            error = true;
        }
        $("#lastname").val($("#lastname").val().trim());
        if($("#lastname").val().length < 2) {
            $.jGrowl("Last name must be at least 2 characters", {theme: 'growlFail'});
            if(!error) $('#lastname').select();
            error = true;
        }
        // // $("#address1").val($("#address1").val().trim());
        // // if($("#address1").val().length < 10) {
        // //     $.jGrowl("The address must be at least 10 characters", {theme: 'growlFail'});
        // //     if(!error) $('#address1').select();
        // //     error = true;
        // // }
        // // $("#city").val($("#city").val().trim());
        // // if($("#city").val().length < 3) {
        // //     $.jGrowl("City name must be at least 3 characters", {theme: 'growlFail'});
        // //     if(!error) $('#city').select();
        // //     error = true;
        // // }
        // // if($("#zipcode").val().length < 3) {
        // //     $.jGrowl("Zipcode must be at least 3 characters", {theme: 'growlFail'});
        // //     if(!error) $('#zipcode').select();
        // //     error = true;
        // // }
        $("#phone").val($("#phone").val().trim());
        // // if($("#phone").val().length < 8) {
        // //     $.jGrowl("Phone number must be at least 8 digits", {theme: 'growlFail'});
        // //     if(!error) $('#phone').select();
        // //     error = true;
        // // }
        if($("#country").val() == "") {
            $.jGrowl("You must indicate your country of residency", {theme: 'growlFail'});
            if(!error) $('#country').select();
            error = true;
        }
        $("#whatsapp").val($("#whatsapp").val().trim());
        $("#viber").val($("#viber").val().trim());
        // $("#password").val($("#password").val().trim());
        // // $("#address2").val($("#address2").val().trim());
        // // $("#zipcode").val($("#zipcode").val().trim());
        // // $("#state").val($("#state").val().trim());
        // // $("#company").val($("#company").val().trim());
        // // $("#website").val($("#website").val().trim());
        if($("#password").val().length < 6) {
            $.jGrowl("Password must be at least 6 characters", {theme: 'growlFail'});
            $('#password').val("");
            $("#confirm").val("");
            if(!error) $('#password').select();
            error = true;
        }
        if($("#confirm").val() != $("#password").val()) {
            $.jGrowl("Passwords do not match", {theme: 'growlFail'});
            $('#password').val("");
            $("#confirm").val("");
            if(!error) $('#password').select();
            error = true;
        }
        if(error) return false;

        if($("#phone").val() != "") {
            var $phone = $("#phone").val().replace(/[^0-9]/gi, '');
            while($phone.substring(0, 1) == "0") {
                $phone = $phone.substring(1);
            }
            if($phone.substring(0, $('#phone_code :selected').text().length) == $('#phone_code :selected').text()) {
                $phone = $phone.substring($('#phone_code :selected').text().length);
            }
            while($phone.substring(0, 1) == "0") {
                $phone = $phone.substring(1);
            }
            $phone = $('#phone_code :selected').text() + $phone;
            if($phone.length >= 8) {
                if (!confirm("+" + $phone + "\nIs your phone number correct?\nRemember, your phone must be in the international format (might need to remove the heading 0) and without spaces, paraenthesis, etc.\nWe will send you an SMS right after the activation.\nWe might need it to reach you out regarding your future orders.\nPlease double check it.")) {
                    return false;
                }
                $("#phone").val($phone);
            } else {
                $("#phone").val("");
            }
        }
        $(this).hide().after('<img class="loading" style="float:right;width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    } else if($(this).html() == "Login") {
        if($('#username').val() == "") {
            $.jGrowl("Username cannot be empty", {theme: 'growlFail'});
            $('#username').focus();
            error = true;
        }
        if($('#username').val().length < 6) {
            $.jGrowl("Username must be at least 6 characters", {theme: 'growlFail'});
            $('#username').select();
            error = true;
        }
        if($('#password').val() == "") {
            $.jGrowl("Password cannot be empty", {theme: 'growlFail'});
            $('#password').focus();
            error = true;
        }
        if($('#password').val().length < 6) {
            $.jGrowl("Password must be at least 6 characters", {theme: 'growlFail'});
            $('#password').val("").focus();
            error = true;
        }
        if(error) return false;
        var result = ""; 
        $.ajax({
            method: "POST",
            url: "../check_user.php",
            async: false,
            data: {
                username: $('#username').val(),
                password: $('#password').val()
            }
        }).done(function(response) {
            result = response;
        });
        if(result == "0") {
            $.jGrowl("Wrong credentials, <a href='https://prounlockphone.com/forgot/' style='text-decoration:none'>need assistance?</a>", {theme: 'growlFail'});
            $('#password').val("").focus();
            return false;
        }
        if(result == "KO") {
            $.jGrowl("Account suspended.", {theme: 'growlFail'});
            $.jGrowl("Contact our <a href='https://prounlockphone.com/contact/' class='txt-warning'>support team</a>.", {theme: 'growlFail'});
            $('#username').val("").focus();
            $('#password').val("");
            return false;
        }
    } else if($(this).html() == "Send") {
        $('#message').val($('#message').val().trim());
        $('#subject').val($('#subject').val().trim());
        $('#guest_email').val($('#guest_email').val().trim());
        $('#name').val($('#name').val().trim());
        if($('#message').val() == "") {
            $.jGrowl("Message cannot be empty", {theme: 'growlFail'});
            $('#message').focus();
            error = true;
        } else if($('#message').val().length < 10) {
            $.jGrowl("Message must be at least 10 characters", {theme: 'growlFail'});
            $('#message').select();
            error = true;
        }
        if($('#subject').val() == "") {
            $.jGrowl("Subject cannot be empty", {theme: 'growlFail'});
            $('#subject').focus();
            error = true;
        } else if($('#subject').val().length < 6) {
            $.jGrowl("Subject must be at least 6 characters", {theme: 'growlFail'});
            $('#subject').select();
            error = true;
        }
        if($('#guest_email').val() == "") {
            $.jGrowl("eMail address cannot be empty", {theme: 'growlFail'});
            $('#guest_email').focus();
            error = true;
        } else {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            if(!pattern.test($('#guest_email').val())) {
                $.jGrowl('eMail address is not valid', {theme: 'growlFail'});
                $('#guest_email').select();
                error = true;
            }
        }
        if($('#name').val() == "") {
            $.jGrowl("Name cannot be empty", {theme: 'growlFail'});
            $('#name').focus();
            error = true;
        } else if($('#name').val().length < 2) {
            $.jGrowl("Name must be at least 2 characters", {theme: 'growlFail'});
            $('#name').select();
            error = true;
        }
        if(error) {
            $('.loading').hide();
            $(this).show();
            return false;
        }
        $(this).hide().after('<img class="loading" style="float:right;width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    } else if($(this).html() == "Register") {
        if($('#username').val() == "") {
            $.jGrowl("Username cannot be empty", {theme: 'growlFail'});
            $('#username').select();
            error = true;
        }
        if($('#username').val().length < 6) {
            $.jGrowl("Username must be at least 6 characters", {theme: 'growlFail'});
            $('#username').select();
            error = true;
        }
        if($('#email').val() == "") {
            $.jGrowl("eMail address cannot be empty", {theme: 'growlFail'});
            $('#email').select();
            error = true;
        }
        var emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
        if(!emailRegex.test($("#email").val())) {
            $.jGrowl("You must use a valid email address", {theme: 'growlFail'});
            $('#email').select();
            error = true;
        }
        if(error) return false;
        var result = "";
        $.ajax({
            method: "GET",
            url: "../check_user.php",
            async: false,
            data: { username: $('#username').val() }
        }).done(function(response) {
            result = response;
        });
        if(result != "0") {
            $.jGrowl("This username already exists, <a href='https://prounlockphone.com/forgot/' style='text-decoration:none'>need assistance?</a>", {theme: 'growlFail'});
            $('#username').select();
            return false;
        } else {
            $.ajax({
                method: "GET",
                url: "../check_user.php",
                async: false,
                data: { email: $('#email').val() }
            }).done(function(response) {
                result = response;
            });
            if(result != "0") {
                $.jGrowl("This email already exists, <a href='https://prounlockphone.com/forgot/' style='text-decoration:none'>need assistance?</a>", {theme: 'growlFail'});
                $('#email').select();
                return false;
            }
        }
        $(this).hide().closest('div').append('<img class="loading pull-right" style="width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
    } else if($(this).html() == "Update") {
        $("#firstname").val($("#firstname").val().trim());
        if($("#firstname").val().length < 2) {
            $.jGrowl("First name must be at least 2 characters", {theme: 'growlFail'});
            $('#firstname').select();
            error = true;
        }
        $("#lastname").val($("#lastname").val().trim());
        if($("#lastname").val().length < 2) {
            $.jGrowl("Last name must be at least 2 characters", {theme: 'growlFail'});
            if(!error) $('#lastname').select();
            error = true;
        }
        // $("#address1").val($("#address1").val().trim());
        // if($("#address1").val().length < 10) {
        //     $.jGrowl("The address must be at least 10 characters", {theme: 'growlFail'});
        //     if(!error) $('#address1').select();
        //     error = true;
        // }
        // $("#city").val($("#city").val().trim());
        // if($("#city").val().length < 3) {
        //     $.jGrowl("City name must be at least 3 characters", {theme: 'growlFail'});
        //     if(!error) $('#city').select();
        //     error = true;
        // }
        // if($("#zipcode").val().length < 3) {
        //     $.jGrowl("Zipcode must be at least 3 characters", {theme: 'growlFail'});
        //     if(!error) $('#zipcode').select();
        //     error = true;
        // }
        // $("#phone").val($("#phone").val().replace(/[^0-9]/gi, ''));
        $("#phone").val($("#phone").val().trim());
        // if($("#phone").val().length < 8) {
        //     $.jGrowl("Phone number must be at least 8 digits", {theme: 'growlFail'});
        //     if(!error) $('#phone').select();
        //     error = true;
        // }
        if($("#country").val() == "") {
            $.jGrowl("You must indicate your country of residency", {theme: 'growlFail'});
            if(!error) $('#country').select();
            error = true;
        }
        // $("#whatsapp").val($("#whatsapp").val().replace(/[^0-9\-+ ()]/gi, ''));
        $("#whatsapp").val($("#whatsapp").val().trim());
        // $("#viber").val($("#viber").val().replace(/[^0-9\-+ ()]/gi, ''));
        $("#viber").val($("#viber").val().trim());
        // $("#address2").val($("#address2").val().trim());
        // $("#zipcode").val($("#zipcode").val().trim());
        // $("#state").val($("#state").val().trim());
        // $("#company").val($("#company").val().trim());
        // $("#website").val($("#website").val().trim());
        if(error) return false;

        var $phone = "";
        if($("#phone").val() != "") {
            $phone = $("#phone").val().replace(/[^0-9]/gi, '');
            while($phone.substring(0, 1) == "0") {
                $phone = $phone.substring(1);
            }
            if($phone.substring(0, $('#phone_code :selected').text().length) == $('#phone_code :selected').text()) {
                $phone = $phone.substring($('#phone_code :selected').text().length);
            }
            while($phone.substring(0, 1) == "0") {
                $phone = $phone.substring(1);
            }
            $("#phone").val($phone);
            $phone = $('#phone_code :selected').text() + $phone;
            if($phone.length < 8) $("#phone").val("");
        }
        $that = $(this);
        $that.hide().after('<img class="loading" style="float:right;width: 25px; margin: 0 auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://www.prounlockphone.com/images/loading.gif">');
        $.ajax({
            type: "POST",
            url: 'https://www.prounlockphone.com/profile/update.php',
            data: 'firstname=' + $('#firstname').val()
            + '&lastname=' + $('#lastname').val()
            // + '&company=' + $('#company').val()
            // + '&website=' + $('#website').val()
            // + '&address1=' + $('#address1').val()
            // + '&address2=' + $('#address2').val()
            // + '&city=' + $('#city').val()
            // + '&state=' + $('#state').val()
            // + '&zipcode=' + $('#zipcode').val()
            + '&country=' + $('#country').val()
            + '&phone=' + $phone
            + '&whatsapp=' + $('#whatsapp').val()
            + '&viber=' + $('#viber').val()
            + '&skype=' + $('#skype').val()
            + '&showAds=' + ($('#ads').val() == "Showing ads" ? "1" : "0"),
            success: function(resp) {
                var responseData = JSON.parse(resp);
                if (responseData.type == 0) {
                    $.jGrowl(responseData.msg, {theme: 'growlFail'});
                } else {
                    $.jGrowl(responseData.msg, {theme: 'growlSuccess'});
                }
                $('.loading').hide();
                $that.show();
            },
            error: showError
        });
        return false;
    }
});

$(document).on('click', 'a[data-toggle=modal]', function (ev) {
	ev.preventDefault();
	var content = $(this).attr("data-webx");
	var target = $(this).attr("data-target");
	$(target + " .modal-content").html('<img style="display: block; margin: 30px auto;" src="https://prounlockphone.com/images/process.gif">');

	// load the url and show modal on success
	$(target + " .modal-content").load(content, function () {
		$(target).modal("show");
	});
});

function showLoading(button) {
	button.hide();
	button.parent().append('<img class="loading" style="width: 25px; height: 25px; display: inline-block; margin: auto; opacity: 0.4; filter: alpha(opacity=40);" src="https://prounlockphone.com/images/process.gif">');
}

function showButton() {
	$('.submit').show();
	$('.loading').remove();
}

// pre-submit callback
function executeRequest(formData, jqForm, options) {
	showLoading($(this.clicked));
	return true;
}

// post-submit callback
function executeResponse(responseText, statusText, xhr, $form) {
    $(window).trigger('responseExecuted');
    var responseData = JSON.parse(responseText);
    if (responseData.type == -1) {
        showButton();
        responseData.msg.forEach(function (item) {
            $.jGrowl(item, {theme: 'growlFail'});
        });
    } else if (responseData.type == 1) {
	window.location = "https://www.prounlockphone.com/register/registration-success.php";
    }
}

function showError() {
	$('#errorModal').modal('toggle');
}

function showConfirmation(htmlVal) {
	$('#confirmationModal .modal-body').html(htmlVal);
	$('#confirmationModal').modal('toggle');
}

var openModals = 0;
$(document).on('shown.bs.modal', '.modal', function () {
	openModals++;
	$('body').removeClass('modal-open');
	$('body').addClass('modal-open');
});
$(document).on('hidden.bs.modal', '.modal', function () {
	openModals--;
	$('body').removeClass('modal-open');
	if (openModals > 0) {
		$('body').addClass('modal-open');
	}
});