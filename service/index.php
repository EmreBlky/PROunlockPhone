<?php

if(isset($_GET['id'])) $query = "id = " . $_GET['id'];
else $query = "service_name = '" . str_replace("percent", "%", str_replace("plussign", "+", str_replace("---", "/", urldecode(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)))))) . "'";

define('INCLUDE_CHECK', true);
require '/home/khoubeib/public_html/common.php';
require '/home/khoubeib/public_html/offline.php';
$DB = new DBConnection();

$row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT id, short_name, service_name, service_group, service_status, details, description, delivery_time, regular_{$_SESSION['currency']}, service_status FROM services WHERE " . $query));

if(!$row['id']) {
    header("Location: https://www.prounlockphone.com/services/");
    exit();
}
?><!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <?php echo common_head_with_title($row['service_name']) ?>
    <style>
        .details ul {
            list-style-position: inside;
            margin-left: 15px;
        }
    </style>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    <script>
        $(function() {
            $('#modal').on('hidden.bs.modal', function () {
                $(this).removeData('bs.modal');
            });
        });
    </script>
    <script src="//platform-api.sharethis.com/js/sharethis.js#property=5b0e802243a707001159fdd5&product=sticky-share-buttons"></script>
    <script type='application/ld+json'>
    {
        "@context": "http://www.schema.org",
        "@type": "product",
        "brand": "<?php echo $row['service_name'] ?>",
        "logo": "https://www.prounlockphone.com/images/<?php echo $row['service_group'] ?>.png",
        "name": "<?php echo $row['short_name'] ?>",
        "category": "<?php echo $row['service_group'] ?>",
        "image": "https://www.prounlockphone.com/images/<?php echo $row['service_group'] ?>.png",
        "description": "This service was made to help you unlock your phone within the shortest delay.",
        "aggregateRating": {
            "@type": "aggregateRating",
            "ratingValue": "5",
            "reviewCount": "36"
        },
        "offers": {
            "@type": "Offer",
            "priceCurrency": "<?php echo $_SESSION['currency'] ?>",
            "price": "<?php echo number_format($row["regular_{$_SESSION['currency']}"], 2, ".", ",") ?>",
            "priceValidUntil": "<?php
                $date = strtotime("+7 day");
                echo date('M d, Y', $date); ?>",
            "acceptedPaymentMethod": {
                "@type": "PaymentMethod",
                "name": "PayPal"
            },
            "itemCondition": "http://schema.org/NewCondition",
            "availability": "http://schema.org/OnlineOnly",
            "seller": {
                "@type": "Organization",
                "name": "PROunlockPhone"
            }
        }
    }
</script>
</head>
<body class="stretched device-lg">
<div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
    <?php renderOutOfSessionHeader("") ?>
    <section id="content" style="margin-bottom: 0px;">
        <div class="container static-content">
            <h3 class="title text-center"><?php
                echo $row['service_name'];
                if($row['service_status'] == "0") {
                    echo " [<span style='color:crimson'>temporary down</span>]</h3>";
                } else {
                    echo "</h3>";
                    if(!isset($_SESSION['start'])) {
                        if($row['delivery_time'] == "Instant") {
                            echo "<div align='center' style='clear:both'><a style='font-size:150%;margin:5px' class='btn btn-primary' data-backdrop='static' data-keyboard='false' data-toggle='modal' data-target='#modal' href='https://www.prounlockphone.com/service/" . ($row["regular_{$_SESSION['currency']}"] == 0 ? "" : "order-") . "check.php?service={$row['id']}'>process IMEI/SN <span style='color:greenyellow'>now</span> - " . ($row["regular_{$_SESSION['currency']}"] == 0 ? "FREE" : "PayPal only") . "</a></div>";
                        } else {
                            echo "<div align='center' style='clear:both'><a style='font-size:150%;margin:5px' class='btn btn-primary' data-backdrop='static' data-keyboard='false' data-toggle='modal' data-target='#modal' href='https://www.prounlockphone.com/service/order.php?service={$row['id']}'>place order - PayPal only</a></div>";
                        }
                    } else {
                        if($row['delivery_time'] == "Instant") {
                            echo "<div align='center' style='clear:both'><a style='font-size:150%;margin:5px' class='btn btn-primary' href='https://www.prounlockphone.com/check/?param={$row['id']}'>place order</a></div>";
                        } else {
                            echo "<div align='center' style='clear:both'><a style='font-size:150%;margin:5px' class='btn btn-primary' href='https://www.prounlockphone.com/order/?param={$row['id']}'>place order</a></div>";
                        }
                    }
                }
                ?>
                <div class="col-md-6">
                    <div class="curved-widget widget-white" style="padding: 20px;">
                        <h4 class="title black noshadow center-text">Description</h4>
                        <hr class="hr-description hr-black">
                        <div class="details">
                            <?php echo $row['details'] ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="curved-widget widget-green center-text">
                                <div class="widget-amount">
                                    <span class="regular-price"><?php echo number_format($row["regular_{$_SESSION['currency']}"], 2, ".", ",") . " " . $_SESSION['symbol'] ?></span>
                                </div>
                                <div class="widget-name">Price</div>
                            </div>
                        </div>
                    </div>
                    <div class="curved-widget widget-blue" style="padding: 20px;">
                        <h4 class="title center-text">Information</h4>
                        <hr class="hr-description">
                        <p class="info-row"><b>Summary:</b> <?php echo $row['description'] ?></p>
                        <p class="info-row"><b>Delivery Time:</b> <?php echo $row['delivery_time'] ?></p>
                    </div>
                </div>
                <?php
                if($row['service_status'] == "1") {
                    if(!isset($_SESSION['start'])) {
                        if($row['delivery_time'] == "Instant") {
                            echo "<div align='center' style='clear:both'><a style='font-size:150%;margin:5px' class='btn btn-primary' data-backdrop='static' data-keyboard='false' data-toggle='modal' data-target='#modal' href='https://www.prounlockphone.com/service/" . ($row["regular_{$_SESSION['currency']}"] == 0 ? "" : "order-") . "check.php?service={$row['id']}'>process IMEI/SN <span style='color:greenyellow'>now</span> - " . ($row["regular_{$_SESSION['currency']}"] == 0 ? "FREE" : "PayPal only") . "</a></div>";
                        } else {
                            echo "<div align='center' style='clear:both'><a style='font-size:150%;margin:5px' class='btn btn-primary' data-backdrop='static' data-keyboard='false' data-toggle='modal' data-target='#modal' href='https://www.prounlockphone.com/service/order.php?service={$row['id']}'>place order - PayPal only</a></div>";
                        }
                    } else {
                        echo "<div align='center' style='clear:both'><a style='font-size:150%;margin:5px' class='btn btn-primary' href='https://www.prounlockphone.com/order/?param={$row['id']}'>place order</a></div>";
                    }
                }
                ?>
        </div>
        <div class="section parallax dark notopmargin nobottommargin nobottomborder" data-stellar-background-ratio="0.3" style="background-position: 50% 435px;">
            <div class="container clearfix">
                <div class="heading-block center">
                    <h2>Keep in mind</h2>
                    <span>
                                <p>Processing time is quite frequently subject to extension.</p>
                                <p>Price can unexpectedly go up or down.</p>
                                <p>We offer unbeatable prices for resellers.</p>
                            </span>
                </div>
            </div>
        </div>
    </section><?php echo $footer ?>
</div>
<?php echo $common_foot ?>
<?php
if(isset($_GET['return']) && $_GET['return'] == 'cancel') {
    ?><script>
        $(document).ready(function () {
            $.jGrowl("Your transaction was canceled.<br />No payment received and your PayPal balance was not affected.<br />Please start over.", {theme: 'growlFail'});
            $.jGrowl("You should have received a notification from us.<br />If you don't see our email, check your junk/spam folder.", {theme: 'growlFail'});
            $.ajax({
                type: 'POST',
                url: 'https://www.prounlockphone.com/service/cancelOrder.php',
                data: 'relative_id=<?php echo $_GET['relative_id'] ?>',
                success: function (response) {
                    if(response == 'OK') {
                        $.jGrowl("Your order was canceled due to non-payment.", {theme: 'growlFail'});
                        $.jGrowl("An email was sent to you for your records.", {theme: 'growlFail'});
                    } else if(response == 'Treated') {
                        $.jGrowl("Your order was previously canceled and closed.", {theme: 'growlFail'});
                    }
                }
            });
        });
    </script>
    <?php
}
?>
</body>
</html>