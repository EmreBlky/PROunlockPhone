<?php
function showSupported($DB, $service) {
    $countries = mysqli_query($DB->Link, "SELECT countries_per_service.id 'id', english_name FROM countries_per_service, countries WHERE country_code = country AND service = {$service} ORDER BY english_name");
    $supported = "<tr><th class='text-center' width='50%'>Countries</th><th class='text-center' width='50%'>Stores</th></tr>";
    while($country = mysqli_fetch_array($countries)) {
        $stores = mysqli_query($DB->Link, "SELECT stores_per_service.id 'id', stores.store 'store' FROM stores_per_service, stores WHERE stores_per_service.store = stores.id AND combination = {$country['id']} ORDER BY stores.store");
        $store = mysqli_fetch_array($stores);
        $supported .= "<tr style='border-top:solid 2px black'><td rowspan='" . mysqli_num_rows($stores) . "'><img class='remover' onclick='drop_country(\"{$country['id']}\")' src='https://www.prounlockphone.com/images/delete.png' /> {$country['english_name']}</td>" . storeLine($store['id'], $store['store']);
        while($store = mysqli_fetch_array($stores)) {
            $supported .= "<tr>" . storeLine($store['id'], $store['store']);
        }
    }
    return $supported;
}

function storeLine($store, $storeName) {
    return "<td><img class='remover' onclick='drop_store({$store})' src='https://www.prounlockphone.com/images/delete.png' /> " . $storeName . "</td></tr>";
}
?>