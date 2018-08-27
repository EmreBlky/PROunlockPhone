<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Update Profile") ?>
    </head>
    <body class="stretched device-lg">
    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
        <?php header_render("profile");
//        $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT company, web_site, address1, address2, city, state, post_code, country, phone, whatsapp, viber, skype FROM users WHERE id = '" . $_SESSION['client_id'] . "'"));
        $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT showAds, country, phone, whatsapp, viber, skype, phone_code FROM users, countries WHERE country_code = country AND users.id = " . $_SESSION['client_id']));
        ?>
        <section id="content" style="margin-bottom: 0px;">
            <div class="row no-margin">
                <div class="col-lg-6 no-padding" style="display: block; margin: 0 auto; float: none;">
                    <div class="boxed-grey" id="register">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group col-md-6">
                                    <label for="firstname">First Name<b style='color:crimson'>*</b></label>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                        <input style="text-transform: capitalize" value="<?php echo $_SESSION['client_short'] ?>" id='firstname' type="name" class="form-control names" name="firstname" placeholder="Enter first name">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lastname">Last Name<b style='color:crimson'>*</b></label>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-user"></span>
                                            </span>
                                        <input style="text-transform: capitalize" value="<?php echo $_SESSION['last_name'] ?>" id="lastname" type="text" class="form-control names" name="lastname" placeholder="Enter last name">
                                    </div>
                                </div>
<!--                                <div class="form-group col-md-6">-->
<!--                                    <label for="company">Company</label>-->
<!--                                    <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-education"></span>-->
<!--                                            </span>-->
<!--                                        <input value="--><?php //echo $row['company'] ?><!--" id='company' type="text" class="form-control" name="company" placeholder="Enter company name">-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="form-group col-md-6">-->
<!--                                    <label for="website">Website</label>-->
<!--                                    <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-link"></span>-->
<!--                                            </span>-->
<!--                                        <input value="--><?php //echo $row['web_site'] ?><!--" id="website" type="url" class="form-control" name="website" placeholder="Enter url link">-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="form-group col-md-6">-->
<!--                                    <label for="address1">Address<b style='color:crimson'>*</b></label>-->
<!--                                    <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-tent"></span>-->
<!--                                            </span>-->
<!--                                        <input value="--><?php //echo $row['address1'] ?><!--" id='address1' type="text" class="form-control" name="address1" placeholder="Enter address">-->
<!--                                    </div>-->
<!--                                    <label for="address2">Address</label>-->
<!--                                    <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-home"></span>-->
<!--                                            </span>-->
<!--                                        <textarea id='address2' style='height:98px' type="text" class="form-control" name="address2" placeholder="Enter address"  rows="4">--><?php //echo $row['address2'] ?><!--</textarea>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="form-group col-md-6">-->
<!--                                    <label for="city">City<b style='color:crimson'>*</b></label>-->
<!--                                    <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-home"></span>-->
<!--                                            </span>-->
<!--                                        <input value="--><?php //echo $row['city'] ?><!--" id="city" type="text" class="form-control" name="city" placeholder="Enter city" value='--><?php //echo $row['city'] ?><!--'>-->
<!--                                    </div>-->
<!--                                    <label for="state">State</label>-->
<!--                                    <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-home"></span>-->
<!--                                            </span>-->
<!--                                        <input value="--><?php //echo $row['state'] ?><!--" id="state" type="text" class="form-control" name="state" placeholder="Enter state" value='--><?php //echo $row['state'] ?><!--'>-->
<!--                                    </div>-->
<!--                                    <label for="zipcode">Zipcode<b style='color:crimson'>*</b></label>-->
<!--                                    <div class="input-group">-->
<!--                                            <span class="input-group-addon">-->
<!--                                                <span class="glyphicon glyphicon-map-marker"></span>-->
<!--                                            </span>-->
<!--                                        <input value="--><?php //echo $row['post_code'] ?><!--" id="zipcode" type="text" class="form-control" name="zipcode" placeholder="Enter zipcode" value='--><?php //echo $row['post_code'] ?><!--'>-->
<!--                                    </div>-->
<!--                                </div>-->
                                <div class="form-group col-md-6">
                                    <label for="country">Country<b style='color:crimson'>*</b></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-globe"></span>
                                        </span>
                                        <select id="country" class="form-control" name="country" onchange="$('#phone_code').val($('#country').val())">
                                            <?php
                                            $reqs = mysqli_query($DB->Link, "SELECT country_code, english_name, phone_code FROM countries ORDER BY english_name");
                                            while($req = mysqli_fetch_array($reqs)) {
                                                echo "<option value='{$req['country_code']}'>{$req['english_name']} (+{$req['phone_code']})</option>\n";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-phone-alt"></span>
                                        </span>
                                        <input value="<?php echo str_replace($row['phone_code'], "", substr($row['phone'], 0, 3)) . substr($row['phone'], 3) ?>" id='phone' type="tel" class="form-control nbr" name="phone" placeholder="without leading (0)">
                                        <select id="phone_code" class="form-control" name="phone_code" style="display:none">
                                            <?php
                                            $reqs = mysqli_query($DB->Link, "SELECT country_code, phone_code FROM countries ORDER BY english_name");
                                            while($req = mysqli_fetch_array($reqs)) {
                                                echo "<option value='{$req['country_code']}'>{$req['phone_code']}</option>\n";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="whatsapp">Whatsapp</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-phone"></span>
                                        </span>
                                        <input value="<?php echo $row['whatsapp'] ?>" id="whatsapp" type="tel" class="form-control nbr" name="whatsapp" placeholder="Enter whatsapp number">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="viber">Viber</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-phone"></span>
                                        </span>
                                        <input value="<?php echo $row['viber'] ?>" id='viber' type="tel" class="form-control nbr" name="viber" placeholder="Enter viber number">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="skype">Skype</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-link"></span>
                                        </span>
                                        <input value="<?php echo $row['skype'] ?>" id="skype" type="text" class="form-control" name="skype" placeholder="Enter skype nickname">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="skype">Ads behavior</label>
                                    <div id="ads-div" class="input-group" style="cursor:pointer">
                                        <span class="input-group-addon">
                                            <span id="ads-glyphicon" class="glyphicon glyphicon-eye-<?php echo $row['showAds'] == '1' ? 'open' : 'close' ?>"></span>
                                        </span>
                                        <input class="form-control" id="ads" name="ads" value="<?php echo $row['showAds'] == '1' ? 'S' : 'Not s' ?>howing ads" readonly="true" style="cursor:pointer<?php if($row['showAds'] == '0') echo ";text-decoration:line-through"; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success pull-right submit">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php echo $footer ?>
    </div>
    <?php echo $common_foot ?>
    <script language="JavaScript">
        $(document).ready(function () {
            $('#ads-div').click(function(){
                $('#ads-glyphicon').toggleClass('glyphicon-eye-open').toggleClass('glyphicon-eye-close');
                if($('#ads').val() == "Showing ads") {
                    $('#ads').css("text-decoration", "line-through").val("Not showing ads");
                } else {
                    $('#ads').css("text-decoration", "").val("Showing ads");
                }
            });
            $('#country').val('<?php echo $row['country'] ?>');
            $('#phone_code').val('<?php echo $row['country'] ?>');
            $(".names").on('change keyup paste', function() {
                $(this).val($(this).val().replace(/[/\\\ "^\,!?~|°¨;:+_#&%*£€$@()\[\]{}]/gi, ''));
            });
            $(".nbr").on('change keyup paste', function() {
                $(this).val($(this).val().replace(/[^0-9\-+ ()]/gi, ''));
            });
        });
    </script>
    </body>
</html>