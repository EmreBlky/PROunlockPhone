<?php
function check_loaded_session() {
    if(isset($_SESSION['start']) and $_SESSION['start']) {
        if(isset($_GET['url'])) {
            if(isset($_GET['param'])) header("Location: ../{$_GET['url']}/?param=" . $_GET['param']);
            else header("Location: ../{$_GET['url']}/");
        } elseif(isset($_GET['urlx'])) header("Location: ..{$_GET['urlx']}");
        elseif(isset($_SESSION['client_type']) && $_SESSION['client_type'] == "admin") header("Location: ../admin/supermain.php");
        elseif(isset($_GET['error'])) header("Location: ../main/?error=" . $_GET['error']);
        else header("Location: ../main/?error=offline");
        exit();
    }
}

function renderOutOfSessionHeader($currentTab) {
    if(isset($_SESSION['start']) and $_SESSION['start']) {
        require "online.php";
        header_render("");
        return;
    }
    ?>
    <header id="header" class="transparent-header full-header" data-sticky-class="not-dark">
        <div id="header-wrap">
            <div class="container clearfix">
                <div id="primary-menu-trigger"><i class="fa fa-bars"></i></div>
                <div id="logo">
                    <a href="https://www.prounlockphone.com/"><img src='https://www.prounlockphone.com/images/pup1.png' /></a>
                </div>
                <nav id="primary-menu">
                    <ul class="sf-menu" style="touch-action: pan-y;">
                        <li>
                            <a href="https://www.prounlockphone.com/">
                                <div style="font-size:10px">Home</div>
                            </a>
                        </li>
                        <li<?php echo $currentTab == "login" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/login/">
                                <div style="font-size:10px">Login</div>
                            </a>
                        </li>
                        <li<?php echo $currentTab == "register" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/register/">
                                <div style="font-size:10px">Register</div>
                            </a>
                        </li>
                        <li<?php echo $currentTab == "services" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/services/" class="sf-with-ul">
                                <div style="font-size:10px">List of services</div>
                            </a>
                        </li>
                        <li<?php echo $currentTab == "check" ? ' class="current"' : "" ?>>
                            <a href="https://www.prounlockphone.com/quick-order/Apple Check Services/?quick=no">
                                <div style="font-size:10px">Check IMEI</div>
                            </a>
                        </li>
                        <li<?php
                        if($currentTab == "quick" || $currentTab == "factory" || $currentTab == "icloud" || $currentTab == "gsx" || $currentTab == "generic") {
                            echo ' class="current"';
                        }
                        ?>>
                            <a href="https://www.prounlockphone.com/quick-order/" class="sf-with-ul">
                                <div style="font-size:10px">Quick Order <b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <li<?php echo $currentTab == "factory" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/quick-order/iPhone Factory Unlock/" style="font-size: 10px">
                                        <div>iPhone Factory Unlock</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $currentTab == "icloud" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/quick-order/iCloud Services/" style="font-size: 10px">
                                        <div>iCloud Services</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $currentTab == "gsx" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/quick-order/Apple Check Services/" style="font-size: 10px">
                                        <div>Apple Check Services</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $currentTab == "generic" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/services/" style="font-size: 10px">
                                        <div>Remove Network Simlock</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li<?php echo ($currentTab == "track" ? ' class="current"' : "") ?>>
                            <a href="https://www.prounlockphone.com/track/">
                                <div style="font-size:10px">Track Order</div>
                            </a>
                        </li>
                        <li<?php
                        if($currentTab == "contact" || $currentTab == "forum") {
                            echo ' class="current"';
                        }
                        ?>>
                            <a href="#" class="sf-with-ul">
                                <div style="font-size:10px">Support <b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <li<?php echo $currentTab == "contact" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/contact/" style="font-size: 10px">
                                        <div>Contact us</div>
                                    </a>
                                </li>
                                <hr style="margin: 0px"/>
                                <li<?php echo $currentTab == "forum" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/forum/" style="font-size: 10px">
                                        <div>Forum</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li<?php
                        if($currentTab == "terms" || $currentTab == "refund" || $currentTab == "rules" || $currentTab == "gdpr") {
                            echo ' class="current"';
                        }
                        ?>>
                            <a href="https://www.prounlockphone.com/terms/" class="sf-with-ul">
                                <div style="font-size:10px">Terms <b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <li<?php echo $currentTab == "refund" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/terms/refund-policy/" style="font-size: 10px">
                                        <div>Refund / Reimbursement Policy</div>
                                    </a>
                                </li>
                                <hr style="margin:0px"/>
                                <li<?php echo $currentTab == "rules" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/terms/rules-of-service/" style="font-size: 10px">
                                        <div>Service Rules and Conditions</div>
                                    </a>
                                </li>
                                <hr style="margin:0px"/>
                                <li<?php echo $currentTab == "gdpr" ? ' class="current"' : "" ?> class="sf-options">
                                    <a href="https://www.prounlockphone.com/terms/privacy/" style="font-size: 10px">
                                        <div>Privacy and GDPR</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php
                            $currencies = array('USD', 'EUR', 'GBP', 'TND');
                            $currencies = array_diff($currencies, [$_SESSION['currency']]);
                            ?>
                            <a href="#" class="sf-with-ul">
                                <div><?php echo $_SESSION['currency'] ?> <img style="margin-top:-3px" src="https://www.prounlockphone.com/images/currencies/<?php echo $_SESSION['currency'] ?>.png" alt="<?php echo $_SESSION['currency'] ?>" /><b class="caret"></b></div>
                            </a>
                            <ul style="display: none;">
                                <?php
                                foreach ($currencies as $cur) {
                                    ?>
                                    <hr style="margin: 0px"/>
                                    <li class="sf-options">
                                        <a href="https://www.prounlockphone.com/currency.php?url=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>&currency=<?php echo $cur ?>">
                                            <div><?php echo $cur ?>&nbsp;&nbsp;&nbsp;<img style="margin-top:-3px" src="https://www.prounlockphone.com/images/currencies/<?php echo $cur ?>.png" alt="<?php echo $cur ?>" /></div>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <div class="clear"></div>
    <?php

    if(!stripos($_SERVER['REQUEST_URI'], 'login') && $_SERVER['REQUEST_URI'] <> '/' && $_SERVER['REQUEST_URI'] <> '') echo '
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