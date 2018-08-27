<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

if(isset($_POST['service'])) {
    try {
        if ($_POST['action'] == 'grant') {
            mysqli_query($DB->Link, "UPDATE services SET icloud_clean_service = 1, bad_supported = " . ($_POST['status'] == 'fresh' ? "0" : "1") . " WHERE id = " . $_POST['service']);
            echo showCurrent($DB, $_POST['service']);
        } elseif ($_POST['action'] == 'revoke') {
            mysqli_query($DB->Link, "UPDATE services SET icloud_clean_service = 0, bad_supported = 0 WHERE id = " . $_POST['service']);
            mysqli_query($DB->Link, "DELETE FROM countries_per_service WHERE service = {$_POST['service']}");
            resetFolders($DB);
            echo showCurrent($DB, $_POST['service']);
        } elseif($_POST['action'] == 'inject') {
            if(mysqli_num_rows(mysqli_query($DB->Link,"SELECT stores_per_service.id FROM stores_per_service, countries_per_service WHERE service = {$_POST['service']} AND country = '{$_POST['country']}' AND combination = countries_per_service.id AND store = {$_POST['store']}")) > 0) {
                echo "FAILURE<br/>Duplicated Entry";
                exit();
            }
            $rows = mysqli_query($DB->Link,"SELECT id FROM countries_per_service WHERE service = {$_POST['service']} AND country = '{$_POST['country']}'");
            if(mysqli_num_rows($rows) == 0) {
                mysqli_query($DB->Link, "INSERT INTO countries_per_service (country, service) VALUES ('{$_POST['country']}', {$_POST['service']})");
                $combination = mysqli_insert_id($DB->Link);
            } else {
                $row = mysqli_fetch_assoc($rows);
                $combination = $row['id'];
            }
            mysqli_query($DB->Link, "INSERT INTO stores_per_service (combination, store) VALUES (" . $combination . ", {$_POST['store']})");

            resetFolders($DB);
            echo showCurrent($DB, $_POST['service']);
        } elseif($_POST['action'] == 'drop') {
            if(isset($_POST['store'])) {
                $combination = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT combination FROM stores_per_service WHERE id = {$_POST['store']}"));
                mysqli_query($DB->Link, "DELETE FROM stores_per_service WHERE id = {$_POST['store']}");
                if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM stores_per_service WHERE combination = " . $combination['combination'])) == 0) {
                    mysqli_query($DB->Link, "DELETE FROM countries_per_service WHERE id = {$combination['combination']}");
                }
                resetFolders($DB);
                echo showCurrent($DB, $_POST['service']);
            } elseif(isset($_POST['combination'])) {
                mysqli_query($DB->Link, "DELETE FROM countries_per_service WHERE id = {$_POST['combination']}");
                resetFolders($DB);
                echo showCurrent($DB, $_POST['service']);
            }
        } elseif($_POST['action'] == 'update') {
            mysqli_query($DB->Link, "UPDATE services SET bad_supported = " . ($_POST['status'] == 'fresh' ? "0" : "1") . " WHERE id = " . $_POST['service']);
            resetFolders($DB);
            echo showCurrent($DB, $_POST['service']);
        }
    } catch (Exception $e) {
        echo "FAILURE<br/>" . $e->getMessage();
    }
}


function showCurrent($DB, $service) {
    require 'getiCloudCleanDetailsDeep.php';
    return showSupported($DB, $service);
}

function resetFolders($DB){
    deleteContent("../quick-order/iCloud Services/iCloud Clean/Fresh");
    $countries = mysqli_query($DB->Link, "SELECT DISTINCT country_code, english_name FROM countries_per_service, countries, services WHERE country_code = countries_per_service.country AND service = services.id AND icloud_clean_service = 1");
    while($country = mysqli_fetch_array($countries)) {
        mkdir("../quick-order/iCloud Services/iCloud Clean/Fresh/" . $country['english_name']);
        copy("../quick-order/iCloud Services/iCloud Clean/country-index.php", "../quick-order/iCloud Services/iCloud Clean/Fresh/{$country['english_name']}/index.php");

        $stores = mysqli_query($DB->Link, "SELECT DISTINCT stores.store 'store' FROM countries_per_service, stores_per_service, stores WHERE stores_per_service.store = stores.id AND countries_per_service.id = combination AND country = '{$country['country_code']}'");
        while($store = mysqli_fetch_array($stores)) {
            mkdir("../quick-order/iCloud Services/iCloud Clean/Fresh/{$country['english_name']}/{$store['store']}");
            copy("../quick-order/iCloud Services/iCloud Clean/store-index.php", "../quick-order/iCloud Services/iCloud Clean/Fresh/{$country['english_name']}/{$store['store']}/index.php");
        }
    }


    deleteContent("../quick-order/iCloud Services/iCloud Clean/Bad Case History - Replaced");
    $countries = mysqli_query($DB->Link, "SELECT DISTINCT country_code, english_name FROM countries_per_service, countries, services WHERE country_code = countries_per_service.country AND service = services.id AND icloud_clean_service = 1 AND bad_supported = 1");
    while($country = mysqli_fetch_array($countries)) {
        mkdir("../quick-order/iCloud Services/iCloud Clean/Bad Case History - Replaced/{$country['english_name']}");
        copy("../quick-order/iCloud Services/iCloud Clean/country-index.php", "../quick-order/iCloud Services/iCloud Clean/Bad Case History - Replaced/{$country['english_name']}/index.php");

        $stores = mysqli_query($DB->Link, "SELECT DISTINCT stores.store 'store' FROM countries_per_service, stores_per_service, stores WHERE stores_per_service.store = stores.id AND countries_per_service.id = combination AND country = '{$country['country_code']}'");
        while($store = mysqli_fetch_array($stores)) {
            mkdir("../quick-order/iCloud Services/iCloud Clean/Bad Case History - Replaced/{$country['english_name']}/{$store['store']}");
            copy("../quick-order/iCloud Services/iCloud Clean/store-index.php", "../quick-order/iCloud Services/iCloud Clean/Bad Case History - Replaced/{$country['english_name']}/{$store['store']}/index.php");
        }
    }
}

function deleteContent($path) {
    $it = new RecursiveDirectoryIterator($path,RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it,RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if($file->isDir()){
            deleteFolder($file->getRealPath());
        }
    }
}

function deleteFolder($path) {
    $it = new RecursiveDirectoryIterator($path,RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it,RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if($file->isDir()){
            deleteFolder($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($path);
}
?>