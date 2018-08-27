<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: /login");
    exit();
}

$services = mysqli_query($DB->Link, "SELECT service_name FROM services ORDER BY service_name");
while($service = mysqli_fetch_array($services)) {
    $service_name = str_replace("%", "percent", str_replace("+", "plussign", str_replace("/", "---", $service['service_name'])));
    if(file_exists("./" . $service_name)) {
        echo "Deleting ./service/{$service_name}<br/>";
        deleteFolder("./" . $service_name);
    } else {
        echo "Not existing and no need to delete ./{$service_name}<br/>";
    }

    if (!file_exists("./" . $service_name)) {
        echo "Creating ./service/{$service_name}<br/>";
        mkdir("./" . $service_name);
        copy("./service-index.php", "./{$service_name}/index.php");
    } else {
        echo "Existing and no need to create ./{$service_name}<br/>";
    }

    echo "<hr/>";
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