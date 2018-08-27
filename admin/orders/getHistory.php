<?php
define('INCLUDE_CHECK', true);
require '../admin.php';
$DB = new DBConnection();

$status_clause = "";
switch($_GET['status']) {
    case 0:
        $status_clause = "";
        break;
    case 1:
        $status_clause = " AND orders.status = 'Pending'";
        break;
    case 2:
        $status_clause = " AND orders.status = 'In process'";
        break;
    case 3:
        $status_clause = " AND orders.status IN('Pending', 'In process')";
        break;
    case 4:
        $status_clause = " AND orders.status = 'Canceled'";
        break;
    case 5:
        $status_clause = " AND orders.status = 'Rejected'";
        break;
    case 6:
        $status_clause = " AND orders.status IN('Canceled', 'Rejected')";
        break;
    case 7:
        $status_clause = " AND orders.status = 'Success'";
        break;
    default:
        $status_clause = " AND orders.status IN('Pending', 'In process')";
        break;
}
if($_GET['service'] == 0) {
    $service_clause = "";
} else {
    $service_clause = " AND service = " . $_GET['service'];
}

if($_GET['search']['value'] != "") {
    $search_clause = " AND (UPPER(orders.IMEI) like '%" . strtoupper($_GET['search']['value']) . "%' OR UPPER(orders.SN) like '%" . strtoupper($_GET['search']['value']) . "%' OR UPPER(service_name) like '%" . strtoupper($_GET['search']['value']) . "%' OR UPPER(relative_id) like '%" . strtoupper($_GET['search']['value']) . "%' OR UID like '%" . strtoupper($_GET['search']['value']) . "%')";
} else {
    $search_clause = "";
}

if($_GET['length'] == -1) {
    $limit = "";
} else {
    $limit = " LIMIT " . $_GET['start'] . ", " . $_GET['length'];
}
$rows = mysqli_query($DB->Link, "SELECT services.id 'serviceID', originalPrice, regular_USD, regular_EUR, regular_GBP, regular_TND, provider, provider_details, balance, currency, users.id 'user', username, english_name, pinToTop, cancelRequest, checkRequest, orders.id 'ID', orders.IMEI 'IMEI', orders.SN 'SN', service_name, orders.status 'status', admin_response_comments, price, last_update, orders.udid 'UDID', clear_email, orders.phone 'Phone', client_personal_notes, client_order_comments FROM users, countries, orders, services WHERE users.id = client AND country_code = users.country AND services.id = service" . $status_clause . $service_clause . $search_clause . " ORDER BY checkRequest DESC, cancelRequest DESC, pinToTop DESC, order_date DESC" . $limit);

$results = [];
while($row = mysqli_fetch_assoc($rows)) {
    switch($row['status']) {
        case "Pending":
            $color = 'orange';
            break;
        case "In process":
            $color = 'blue';
            break;
        case "Success":
            $color = '#01BC8C;';
            break;
        case "Canceled":
            $color = 'black';
            break;
        case "Rejected":
            $color = '#EF6F6C;';
            break;
    }
    if($row['cancelRequest'] == "1") {
        $cancel = "<br/><a style='margin:5px' class='btn btn-default'>Discard Cancel Request</a>";
    } else {
        $cancel = "";
    }
    if($row['checkRequest'] == "1") {
        $verify = "<br/><a style='margin:5px'' class='btn btn-default'>Discard Check Request</a>";
    } else {
        $verify = "";
    }
    $duplicates = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE " . ($row['IMEI'] != "" ? "IMEI = '{$row['IMEI']}'" : "SN = '{$row['SN']}'")));
    if($duplicates > 1) $duplicates = "<a class='text-danger' href='?" . ($row['IMEI'] != "" ? "ref={$row['IMEI']}" : "ref={$row['SN']}") . "' target='_blank'>" . ($duplicates - 1) . " duplicate" . ($duplicates > 2 ? "s" : "") . "</a><br />";
    else {
        $duplicates = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM quick_orders WHERE " . ($row['IMEI'] != "" ? "IMEI = '{$row['IMEI']}'" : "SN = '{$row['SN']}'")));
        if($duplicates > 1) $duplicates = "<a class='text-danger' href='?" . ($row['IMEI'] != "" ? "ref={$row['IMEI']}" : "ref={$row['SN']}") . "' target='_blank'>" . ($duplicates - 1) . " duplicate" . ($duplicates > 2 ? "s" : "") . "</a><br />";
        else $duplicates = "";
    }
    $results[] = array(
        "user" => "<div align='center'><a href='https://www.prounlockphone.com/admin/statement.php?id={$row['user']}' target='_blank' class='text-primary'>{$row['username']}</a><br/>[{$row['english_name']}]<br/><a class='text-" . ($row['balance'] < 0 ? 'danger' : 'success') . "'>" . number_format($row['balance'], 2, ".", ",") . " {$row['currency']}</a>
                <div>
                    <label><input type='checkbox' class='js-switch' data-switchery='true'" . ($row['pinToTop'] == "1" ? " checked='checked'" : "") . "' /></label>
                </div>
            </div>",
        "ref" => "<div align='center' style='color:{$color}'>" . ($row['IMEI'] != "" ? $row['IMEI'] : $row['SN']) . "<br/>" . $duplicates . "
<a href='https://www.prounlockphone.com/admin/order.php?id={$row['ID']}' target='_blank' class='btn btn-default btn-sm'>
        <span class='glyphicon glyphicon-pencil'></span> Edit
    </a>
</div>",
        "service" => "#{$row['serviceID']} {$row['service_name']}<br/><a class='text-info small'>{$row['provider']}</a><br/><a class='text-muted small'>{$row['provider_details']}</a><br/>Price: <a" . ($row['price'] < $row['originalPrice'] ? " class='text-danger'" : "") . ">{$row['price']} {$row['currency']}</a> | Regular: " . ($row['regular_' . $row['currency']] > $row['price'] ? "<strike>" : "<a>") . "{$row['regular_' . $row['currency']]} {$row['currency']}" . ($row['regular_' . $row['currency']] > $row['price'] ? "</strike>" : "</a>") . " | Cost: <a>\${$row['originalPrice']}</a>
<br/>
<a style='font-size:10px' class='btn btn-success' onlick='Success({$row['ID']})'>Success</a>
<a style='font-size:10px' class='btn btn-primary' onlick='Process({$row['ID']})'>In process</a>
<a style='font-size:10px' class='btn btn-warning' onlick='Pending({$row['ID']})'>Pending</a>
<a style='font-size:10px' class='btn' type='button' style='width:100px;margin:5px;background:black;color:white' onlick='Canceled({$row['ID']})'>Canceled</a>
<a style='font-size:10px' class='btn btn-danger' onlick='Rejected({$row['ID']})'>Rejected</a>",
        "pinToTop" => $row['pinToTop'],
        "cancelRequest" => $row['cancelRequest'],
        "checkRequest" => $row['checkRequest'],
//        "comments" => $row['admin_response_comments'] == "" ? "" : '<pre style="overflow:visible;margin:0px;color:crimson;height:100%;white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;">' . $row['admin_response_comments'] . '</pre>',
        "comments" => "<textarea class='comments'>" . nl2br($row['admin_response_comments']) . "</textarea>",
        "order_date" => $row['order_date'],
        "price" => number_format($row['price']) . " {$_SESSION['symbol']}",
        "last_update" => $row['last_update'],
        "udid" => $row['UDID'],
        "clear_email" => $row['clear_email'],
        "phone" => $row['Phone'],
        "client_personal_notes" => $row['client_personal_notes'],
        "client_order_comments" => $row['client_order_comments'],
        "action" => "<div align='center'>
            <a style='width:100px;margin:5px' class='btn btn-default' data-toggle='modal' data-target='#modal' data-webx='https://www.prounlockphone.com/admin/orders/getDetails.php?id={$row['ID']}'>Details</a>{$cancel}{$verify}"
    );
}

$response = array(
    "draw" => $_GET['draw'],
    "recordsTotal" => mysqli_num_rows($rows = mysqli_query($DB->Link, "SELECT id FROM orders WHERE client = " . $_SESSION['client_id'] . $status_clause . $service_clause . $search_clause)),
    "recordsFiltered" => mysqli_num_rows($rows),
    "data" => $results
);
echo json_encode($response);
?>