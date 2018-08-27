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
        $rows_countries = mysqli_query($DB->Link, "SELECT DISTINCT country FROM iPhoneFactoryUnlock WHERE service = " . $_POST['service']);
        while($row_country = mysqli_fetch_array($rows_countries)) {
            $country_name = mysqli_fetch_assoc(mysqli_query($DB->Link,"SELECT english_name FROM countries WHERE country_code = '{$row_country['country']}'"));
            if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM iPhoneFactoryUnlock WHERE country = '{$row_country['country']}' AND service <> " . $_POST['service'])) == 0) {
                if(file_exists("../quick-order/iPhone Factory Unlock/" . $country_name['english_name'])){
                    deleteFolder("../quick-order/iPhone Factory Unlock/" . $country_name['english_name']);
                }
            } else {
                $rows_carriers = mysqli_query($DB->Link, "SELECT DISTINCT carrier FROM iPhoneFactoryUnlock WHERE country = '{$row_country['country']}' AND service = " . $_POST['service']);
                while($row_carrier = mysqli_fetch_array($rows_carriers)) {
                    $carrier_name = mysqli_fetch_assoc(mysqli_query($DB->Link,"SELECT carrier FROM carriers WHERE id = " . $row_carrier['carrier']));
                    if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM iPhoneFactoryUnlock WHERE carrier = {$row_carrier['carrier']} AND country = '{$row_country['country']}' AND service <> " . $_POST['service'])) == 0) {
                        if(file_exists("../quick-order/iPhone Factory Unlock/{$country_name['english_name']}/" . $carrier_name['carrier'])){
                            deleteFolder("../quick-order/iPhone Factory Unlock/{$country_name['english_name']}/" . $carrier_name['carrier']);
                        }
                    } else {
                        $rows_models = mysqli_query($DB->Link, "SELECT DISTINCT model FROM iPhoneFactoryUnlock WHERE carrier = {$row_carrier['carrier']} AND country = '{$row_country['country']}' AND service = " . $_POST['service']);
                        while($row_model = mysqli_fetch_array($rows_models)) {
                            $model_name = mysqli_fetch_assoc(mysqli_query($DB->Link,"SELECT model FROM models WHERE id = " . $row_model['model']));
                            if(mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM iPhoneFactoryUnlock WHERE model = {$row_model['model']} AND carrier = {$row_carrier['carrier']} AND country = '{$row_country['country']}' AND service <> " . $_POST['service'])) == 0) {
                                if(file_exists("../quick-order/iPhone Factory Unlock/{$country_name['english_name']}/{$carrier_name['carrier']}/" . $model_name['model'])){
                                    deleteFolder("../quick-order/iPhone Factory Unlock/{$country_name['english_name']}/{$carrier_name['carrier']}/" . $model_name['model']);
                                }
                            }
                        }
                    }
                }
            }
        }
        mysqli_query($DB->Link, "DELETE FROM iPhoneFactoryUnlock WHERE service = " . $_POST['service']);
        if ($_POST['action'] == 'inject') {
            $data = json_decode($_POST['data']);
            foreach ($data->countries as $country) {
                $row = mysqli_fetch_assoc(mysqli_query($DB->Link,"SELECT english_name FROM countries WHERE country_code = '{$country}'"));
                $country_name = $row['english_name'];
                if(!file_exists("../quick-order/iPhone Factory Unlock/" . $country_name)) {
                    mkdir("../quick-order/iPhone Factory Unlock/" . $country_name);
                    copy("../quick-order/iPhone Factory Unlock/country-index.php", "../quick-order/iPhone Factory Unlock/{$country_name}/index.php");
                }
                foreach ($data->carriers as $carrier) {
                    $row = mysqli_fetch_assoc(mysqli_query($DB->Link,"SELECT carrier FROM carriers WHERE id = '{$carrier}'"));
                    $carrier_name = $row['carrier'];
                    if(!file_exists("../quick-order/iPhone Factory Unlock/{$country_name}/" . $carrier_name)) {
                        mkdir("../quick-order/iPhone Factory Unlock/{$country_name}/" . $carrier_name);
                        copy("../quick-order/iPhone Factory Unlock/carrier-index.php", "../quick-order/iPhone Factory Unlock/{$country_name}/{$carrier_name}/index.php");
                    }
                    foreach ($data->models as $model) {
                        $row = mysqli_fetch_assoc(mysqli_query($DB->Link,"SELECT model FROM models WHERE id = '{$model}'"));
                        $model_name = $row['model'];
                        if(!file_exists("../quick-order/iPhone Factory Unlock/{$country_name}/{$carrier_name}/" . $model_name)) {
                            mkdir("../quick-order/iPhone Factory Unlock/{$country_name}/{$carrier_name}/" . $model_name);
                            copy("../quick-order/iPhone Factory Unlock/model-index.php", "../quick-order/iPhone Factory Unlock/{$country_name}/{$carrier_name}/{$model_name}/index.php");
                        }
                        foreach ($data->status as $status) {
                            $request = "INSERT INTO iPhoneFactoryUnlock (country, carrier, model, status, service) VALUES ('{$country}', '{$carrier}', '{$model}', '{$status}', '{$_POST['service']}')";
                            try {
                                mysqli_query($DB->Link, $request);
                            } catch (Exception $e) {
                                echo "FAILURE<br/>" . $e->getMessage();
                            }
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
        echo "FAILURE<br/>" . $e->getMessage();
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