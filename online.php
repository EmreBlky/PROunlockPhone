<?php
if(!isset($_SESSION['start'])) {
    header("Location: https://www.prounlockphone.com/login/?urlx=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

function header_render($actual) {
    $DB = new DBConnection();
    ?>
    <header id="header" class="transparent-header full-header" data-sticky-class="not-dark">
        <div id="header-wrap" class="">
            <div class="container clearfix">
                <div id="primary-menu-trigger"><i class="fa fa-bars"></i></div>
                <div id="logo">
                    <a href="https://www.prounlockphone.com/"><img src='https://www.prounlockphone.com/images/pup1.png' /></a>
                    <a href="https://www.prounlockphone.com/profile/" style="font-size:50%"><? echo $_SESSION['username'] ?></a>
                </div>
                <nav id="primary-menu">
                    <ul class="sf-menu" style="touch-action: pan-y;">
                        <li>
                            <a href="https://www.prounlockphone.com/statement/" style="font-size: 10px">
                                <?php
                                $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT balance FROM users WHERE users.id = " . $_SESSION['client_id']));
                                ?><div id="balance" style="color:<? echo ($row['balance'] < 0 ? $color = "red" : ($row['balance'] < 10 ? $color = "orange" : $color = "green")) ?>">Balance <? echo number_format($row['balance'], 2, ".", ",") . " " . $_SESSION['symbol'] ?></div>
                            </a>
                        </li>
                        <li<?php echo $actual == "main" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/main/" style="font-size: 10px">
                                <div>Home</div>
                            </a>
                        </li>
                        <li<?php
                        if($actual == "orders" || $actual == "order" || $actual == "check") {
                            echo ' class="current"';
                        }
                        ?>>
                            <a href="#" class="sf-with-ul" style="font-size: 10px">
                                <div>Orders Panel <b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <li<?php echo $actual == "order" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/order/" style="font-size: 10px">
                                        <div>Place Order</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $actual == "check" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/check/" style="font-size: 10px">
                                        <div>Instant IMEI Check</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $actual == "orders" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/orders/" style="font-size: 10px">
                                        <div>Orders History</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li<?php
                        if($actual == "profile" || $actual == "statement" || $actual == "notifications" || $actual == "payment" || $actual == "refund" || $actual == "change") {
                            echo ' class="current"';
                        }
                        ?>>
                            <a href="#" class="sf-with-ul" style="font-size: 10px">
                                <div>My account <b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <li<?php echo $actual == "statement" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/statement/" style="font-size: 10px">
                                        <div>
                                            <img src="https://www.prounlockphone.com/images/payment.png" style="margin-right:10px" alt="statement"><b>My Statement</b>
                                        </div>
                                    </a>
                                </li>
                                <li<?php echo $actual == "notifications" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/notifications/" style="font-size: 10px">
                                        <div>
                                            <img src="https://www.prounlockphone.com/images/notification.png" style="margin-right:10px" alt="notification"><b>Notification History</b>
                                        </div>
                                    </a>
                                </li>
                                <li<?php echo $actual == "payment" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="#" class="sf-with-ul" style="font-size: 10px">
                                        <div>
                                            <img src="https://www.prounlockphone.com/images/coins.png" style="margin-right:10px" alt="credits"><b>Add credits</b> <b class="caret"></b>
                                        </div>
                                    </a>
                                    <ul style="display: none;">
                                        <li class="sf-options" style="border-bottom:solid 1px gray">
                                            <a href="https://www.prounlockphone.com/payment/" style="font-size: 10px">
                                                <div align="center">
                                                    <img src="https://www.prounlockphone.com/images/paypal-menu.png" height="23px" alt="PayPal">
                                                </div>
                                            </a>
                                        </li>
                                        <!--                                                <li class="sf-options" style="border-bottom:solid 1px gray">-->
                                        <!--                                                    <a href="https://www.prounlockphone.com/skrill/">-->
                                        <!--                                                        <div align="center">-->
                                        <!--                                                            <img src="https://www.prounlockphone.com/images/skrill-menu.png" height="23px" alt="Skrill">-->
                                        <!--                                                        </div>-->
                                        <!--                                                    </a>-->
                                        <!--                                                </li>-->
                                        <!--                                                <li class="sf-options" style="border-bottom:solid 1px gray">-->
                                        <!--                                                    <a href="https://www.prounlockphone.com/neteller/">-->
                                        <!--                                                        <div align="center">-->
                                        <!--                                                            <img src="https://www.prounlockphone.com/images/neteller-menu.png" height="23px" alt="Neteller">-->
                                        <!--                                                        </div>-->
                                        <!--                                                    </a>-->
                                        <!--                                                </li>-->
                                        <!--                                                <li class="sf-options" style="border-bottom:solid 1px gray">-->
                                        <!--                                                    <a href="https://www.prounlockphone.com/moneygram/">-->
                                        <!--                                                        <div align="center">-->
                                        <!--                                                            <img src="https://www.prounlockphone.com/images/moneygram-menu.png" height="23px" alt="MoneyGram">-->
                                        <!--                                                        </div>-->
                                        <!--                                                    </a>-->
                                        <!--                                                </li>-->
                                        <!--                                                <li class="sf-options">-->
                                        <!--                                                    <a href="https://www.prounlockphone.com/westernunion/">-->
                                        <!--                                                        <div align="center">-->
                                        <!--                                                            <img src="https://www.prounlockphone.com/images/westernunion-menu.png" height="23px" alt="Western Union">-->
                                        <!--                                                        </div>-->
                                        <!--                                                    </a>-->
                                        <!--                                                </li>-->
                                    </ul>
                                </li>
                                <li<?php echo $actual == "refund" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/refund/" style="font-size: 10px">
                                        <div>
                                            <img src="https://www.prounlockphone.com/images/refund.png" style="margin-right:10px" alt="refund"><b>Request Money Back</b>
                                        </div>
                                    </a>
                                </li>
                                <li<?php echo $actual == "profile" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/profile/" style="font-size: 10px">
                                        <div>
                                            <img src="https://www.prounlockphone.com/images/profile.png" style="margin-right:10px" alt="profile"><b>Edit My Profile</b>
                                        </div>
                                    </a>
                                </li>
                                <li<?php echo $actual == "change" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/change/" style="font-size: 10px">
                                        <div>
                                            <img src="https://www.prounlockphone.com/images/key.png" style="margin-right:10px" alt="profile"><b>Change Password</b>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li<?php
                        if($actual == "contactus" || $actual == "forum") {
                            echo ' class="current"';
                        }
                        ?>>
                            <a href="#" class="sf-with-ul" style="font-size: 10px">
                                <div>Support <b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <li<?php echo $actual == "contactus" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/contactus/" style="font-size: 10px">
                                        <div>Contact us</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $actual == "forum" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/forum/" style="font-size: 10px">
                                        <div>Forum</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="https://www.prounlockphone.com/logout/" style="font-size: 10px">
                                <div>Logout</div>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <div class="clear"></div>
    <?php

    if((!isset($_SESSION['showAds']) || $_SESSION['showAds'] == '1') && $_SERVER['REQUEST_URI'] <> '/login/') echo '
            <div align="center">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-5111821234953725"></ins>
                <script>
                     (adsbygoogle = window.adsbygoogle || []).push({
                          google_ad_client: "ca-pub-5111821234953725"
                     });
                </script>
            </div>';
}
?>