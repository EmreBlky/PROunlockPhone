<?php
if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

?>

    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <img src="https://www.prounlockphone.com/images/pup82x50.png" style="float:left;margin-top:5px;margin-left:5px" />
        <a class="navbar-brand" href="supermain.php">PROunlockPhone</a>
        <div class="container">
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="supermain.php">Home</a></li>
                    <li><a href="superorders.php?status=Pending&status2=In process">Orders</a></li>
                    <li><a href="quickOrders.php?status=Pending&status2=In process">Quick Orders</a></li>
                    <li><a href="dashboard.php">Statements</a></li>
                    <li><a href="panel.php">Special Prices</a></li>
<!--                        <ul class="dropdown-menu">-->
<!--                        -->
<?php
//foreach (range('A', 'Z') as $letter) {
//echo "                      <li><a href=\"#\">" . $letter . " <span class=\"caret\"></span></a>
//                            <ul class=\"dropdown-menu\">\n";
//$rows = mysqli_query($DB->Link, "SELECT id, username, first_name, last_name FROM users WHERE first_name like '" . $letter . "%' ORDER BY first_name, last_name, username");
//while($row = mysqli_fetch_array($rows)) {
//    echo "                              <li><a href=\"user_frame.php?id=" . $row ['id'] . "\">" . $row['first_name'] . " " . $row['last_name'] . " (" . $row['username'] . ")</a></li>\n";
//}
//echo '                          </ul>
//                        </li>
//  ';
//}
?>
<!--                        </ul>-->
<!--                    </li>-->
                    <li><a href="superplace.php">Place Order <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="superplace.php">Regular Services</a></li>
                            <li class="divider"></li>
<!--                            <li class="dropdown-header">Look up by category</li>-->
<!--                            <li><a href="#">Country <span class="caret"></span></a>-->
<!--                                <ul class="dropdown-menu">-->
<!--                                    <li><a href="#">Worldwide <span class='caret'></span></a>-->
<!--                                        <ul class="dropdown-menu">-->
<!--                                            <li><a href='#'>All</a></li>-->
<!--                                            <li class="divider"></li>-->
<?php
//$rows = mysqli_query($DB->Link, "SELECT DISTINCT manufacturer FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' AND country = 'Multi' ORDER BY manufacturer");
//while($row = mysqli_fetch_array($rows)) {
//    echo "                                            <li><a href='#'>" . $row['manufacturer'] . "</a></li>\n";
//}
?>
<!--                                        </ul>-->
<!--                                    </li>-->
<!--                                    <li class="divider"></li>-->
<?php
//$rows = mysqli_query($DB->Link, "SELECT DISTINCT country, english_name FROM countries, services WHERE service_status = '1' AND delivery_time <> 'Instant' AND country = country_code ORDER BY english_name");
//while($row = mysqli_fetch_array($rows)) {
//    echo "                                    <li><a href=\"#\">" . $row['english_name'] . " <span class='caret'></span></a>
//                                        <ul class='dropdown-menu'>
//                                            <li><a href='#'>All</a></li>
//                                            <li class='divider'></li>
//                                            <li><a href='#'>Carrier <span class='caret'></span></a>
//                                                <ul class='dropdown-menu'>\n";
//    $reqs = mysqli_query($DB->Link, "SELECT DISTINCT carrier FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' AND country = '" . $row['country'] . "' ORDER BY carrier");
//    if(mysqli_num_rows($reqs) > 1) {
//        echo "                                                    <li><a href='#'>All</a></li>
//                                                    <li class='divider'></li>\n";
//    }
//    while($req = mysqli_fetch_array($reqs)) {
//        echo "                                                    <li><a href='#'>" . $req['carrier'] . " <span class='caret'></span></a>
//                                                        <ul class='dropdown-menu'>\n";
//        $reqs2 = mysqli_query($DB->Link, "SELECT DISTINCT manufacturer FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' AND country = '" . $row['country'] . "' AND carrier = '" . $req['carrier'] . "' ORDER BY manufacturer");
//        if(mysqli_num_rows($reqs2) > 1) {
//            echo "                                                            <li><a href='#'>All</a></li>
//                                                            <li class='divider'></li>\n";
//        }
//        while($req2 = mysqli_fetch_array($reqs2)) {
//            echo "                                                            <li><a href='#'>" . $req2['manufacturer'] . "</a></li>\n";
//        }
//        echo "                                                        </ul>
//                                                    </li>\n";
//    }
//    echo "                                                </ul>
//                                            </li>
//                                            <li><a href='#'>Manufacturer <span class='caret'></span></a>
//                                                <ul class='dropdown-menu'>\n";
//    $reqs = mysqli_query($DB->Link, "SELECT DISTINCT manufacturer FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' AND country = '" . $row['country'] . "' ORDER BY manufacturer");
//    if(mysqli_num_rows($reqs) > 1) {
//        echo "                                                    <li><a href='#'>All</a></li>
//                                                    <li class='divider'></li>\n";
//    }
//    while($req = mysqli_fetch_array($reqs)) {
//        echo "                                                    <li><a href='#'>" . $req['manufacturer'] . " <span class='caret'></span></a>
//                                                        <ul class='dropdown-menu'>\n";
//        $reqs2 = mysqli_query($DB->Link, "SELECT DISTINCT carrier FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' AND country = '" . $row['country'] . "' AND manufacturer = '" . $req['manufacturer'] . "' ORDER BY carrier");
//        if(mysqli_num_rows($reqs2) > 1) {
//            echo "                                                            <li><a href='#'>All</a></li>
//                                                            <li class='divider'></li>\n";
//        }
//        while($req2 = mysqli_fetch_array($reqs2)) {
//            echo "                                                            <li><a href='#'>" . $req2['carrier'] . "</a></li>\n";
//        }
//        echo "                                                        </ul>
//                                                    </li>\n";
//    }
//    echo "                                                </ul>
//                                            </li>
//                                        </ul>
//                                    </li>\n";
//}
?>
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li><a href="#">Manufacturer <span class="caret"></span></a>-->
<!--                                <ul class="dropdown-menu">-->
<?php
//$rows = mysqli_query($DB->Link, "SELECT DISTINCT manufacturer FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' ORDER BY manufacturer");
//while($row = mysqli_fetch_array($rows)) {
//    echo "                                    <li><a href=\"#\">" . $row['manufacturer'] . " <span class='caret'></span></a>
//                                        <ul class='dropdown-menu'>
//                                            <li><a href='#'>All</a></li>
//                                            <li class='divider'></li>
//                                            <li><a href='#'>Country <span class='caret'></span></a>
//                                                <ul class='dropdown-menu'>\n";
//    $reqs = mysqli_query($DB->Link, "SELECT DISTINCT country, english_name FROM services, countries WHERE service_status = '1' AND delivery_time <> 'Instant' AND manufacturer = '" . $row['manufacturer'] . "' AND country = country_code ORDER BY english_name");
//    if(mysqli_num_rows($reqs) > 1) {
//        echo "                                                    <li><a href='#'>All</a></li>
//                                                    <li class='divider'></li>\n";
//    }
//    while($req = mysqli_fetch_array($reqs)) {
//        echo "                                                    <li><a href='#'>" . $req['english_name'] . " <span class='caret'></span></a>
//                                                        <ul class='dropdown-menu'>\n";
//        $reqs2 = mysqli_query($DB->Link, "SELECT DISTINCT carrier FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' AND manufacturer = '" . $row['manufacturer'] . "' AND country = '" . $req['country'] . "' ORDER BY carrier");
//        if(mysqli_num_rows($reqs2) > 1) {
//            echo "                                                            <li><a href='#'>All</a></li>
//                                                            <li class='divider'></li>\n";
//        }
//        while($req2 = mysqli_fetch_array($reqs2)) {
//            echo "                                                            <li><a href='#'>" . $req2['carrier'] . "</a></li>\n";
//        }
//        echo "                                                        </ul>
//                                                    </li>\n";
//    }
//    echo "                                                </ul>
//                                            </li>
//                                            <li><a href='#'>Carrier <span class='caret'></span></a>
//                                                <ul class='dropdown-menu'>\n";
//    $reqs = mysqli_query($DB->Link, "SELECT DISTINCT carrier FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' AND manufacturer = '" . $row['manufacturer'] . "' ORDER BY carrier");
//    if(mysqli_num_rows($reqs) > 1) {
//        echo "                                                    <li><a href='#'>All</a></li>
//                                                    <li class='divider'></li>\n";
//    }
//    while($req = mysqli_fetch_array($reqs)) {
//        echo "                                                    <li><a href='#'>" . $req['carrier'] . " <span class='caret'></span></a>
//                                                        <ul class='dropdown-menu'>\n";
//        $reqs2 = mysqli_query($DB->Link, "SELECT DISTINCT english_name FROM services, countries WHERE service_status = '1' AND delivery_time <> 'Instant' AND manufacturer = '" . $row['manufacturer'] . "' AND carrier = '" . $req['carrier'] . "' AND country = country_code ORDER BY carrier");
//        if(mysqli_num_rows($reqs2) > 1) {
//            echo "                                                            <li><a href='#'>All</a></li>
//                                                            <li class='divider'></li>\n";
//        }
//        while($req2 = mysqli_fetch_array($reqs2)) {
//            echo "                                                            <li><a href='#'>" . $req2['english_name'] . "</a></li>\n";
//        }
//        echo "                                                        </ul>
//                                                    </li>\n";
//    }
//    echo "                                                </ul>
//                                            </li>
//                                        </ul>
//                                    </li>\n";
//}
?>
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li><a href="#">Carrier <span class="caret"></span></a>-->
<!--                                <ul class="dropdown-menu">-->
<?php
//$rows = mysqli_query($DB->Link, "SELECT DISTINCT carrier, country FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' ORDER BY carrier, country");
//while($row = mysqli_fetch_array($rows)) {
//    echo "                                    <li><a href=\"#\">" . $row['carrier'] . " (" . $row['country'] . ") <span class='caret'></span></a>
//                                        <ul class='dropdown-menu'>\n";
//    $reqs = mysqli_query($DB->Link, "SELECT DISTINCT manufacturer FROM services WHERE service_status = '1' AND delivery_time <> 'Instant' AND carrier = '" . $row['carrier'] . "' AND country = '" . $row['country'] . "' ORDER BY manufacturer");
//    if(mysqli_num_rows($reqs) > 1) {
//        echo "                                              <li><a href='#'>All</a></li>
//                                            <li class='divider'></li>\n";
//    }
//    while($req = mysqli_fetch_array($reqs)) {
//        echo "                                              <li><a href='#'>" . $req['manufacturer'] . "</a></li>\n";
//    }
//    echo "                                          </ul>
//                                            </li>\n";
//}
?>
<!--                                </ul>-->
<!--                            </li>-->
<!--                            <li class="divider"></li>-->
<!--                            <li class="dropdown-header">Instant Services</li>-->
                            <li><a href="superChecker.php">Instant Services</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Manage services <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="addservice.php">Add new service</a></li>
                            <li><a href="updateService.php">Update existant service</a></li>
                            <li><a href="iPhoneFactoryUnlock.php">iPhone Factory Unlock Services</a></li>
                            <li><a href="iCloudClean.php">iCloud Clean Services</a></li>
                            <li class="divider"></li>
                            <li><a href="serviceNewsletter.php">Services' Newsletters</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a><?php echo $_SESSION["username"] ?></a></li>
                    <li><a href="logoff.php">Sign out</a></li>
                </ul>      
            </div>
        </div>
    </div>
