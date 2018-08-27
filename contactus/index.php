<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Contact us") ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body class="stretched device-lg">
        <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php header_render("contactus") ?>
            <div class="clear"></div>
            <section id="content" style="margin-bottom: 0px;">
                <div class="container">
                    <h3 class="title center-text">Contact Us</h3>
                    <div class="col-md-7">
                        <div class="curved-widget widget-white" style="padding: 20px;">
                            <h4 class="title black noshadow center-text">Contact Form</h4>
                            <hr class="hr-description hr-black">
                            <div class="widget-content container-fluid" id="contactForm">
                                <form action="https://www.prounlockphone.com/contactus/received.php" method="post">
                                    <div class="col-md-6 form-group">
                                        <label>Name</label>
                                        <input id="name" name="name" type="text" class="form-control" value="<?php echo $_SESSION['client_long'] ?>" readonly="true" />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Reply-to eMail</label>
                                        <input id="guest_email" name="guest_email" type="email" class="form-control" value="<?php echo $_SESSION['client_email'] ?>" readonly="true" />
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label>Subject</label>
                                        <input id="subject" name="subject" type="text" class="form-control" placeholder="Add the subject" />
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label>Message</label>
                                        <textarea id="message" name="message" rows="5" class="form-control" placeholder="Type your message"></textarea>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <button class='btn btn-primary submit' style="float:right">Send</button>
                                        <div class="g-recaptcha" data-sitekey="6Lc4RREUAAAAAAE8igcAvJHtrA46STBiIVxrYcbj"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="curved-widget widget-blue" style="padding: 20px;">
                            <h4 class="title center-text">Contact Information</h4>
                            <?php echo $contact_information ?>
                        </div>
                    </div>
		        </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
<?php
if(isset($_GET['retry']) && $_GET['retry'] == 'yes') {
    echo '
        <script>
            $(document).ready(function () {
                $.jGrowl("You did not solve the captcha!", {theme: \'growlFail\'});
            });
        </script>';
}
?>

    </body>
</html>