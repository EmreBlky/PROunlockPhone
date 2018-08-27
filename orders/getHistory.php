<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$column_sort = 'relative_id';
switch($_GET['order'][0]['column']) {
    case 0:
        $column_sort = 'order_date';
        break;
    case 1:
        $column_sort = 'orders.status';
        break;
    case 2:
        $column_sort = 'service_name';
        break;
}
$status_clause = "";
switch($_GET['status']) {
    case 0:
        $status_clause = "";
        break;
    case 1:
        $status_clause = " AND status = 'Pending'";
        break;
    case 2:
        $status_clause = " AND status = 'In process'";
        break;
    case 3:
        $status_clause = " AND status IN('Pending', 'In process')";
        break;
    case 4:
        $status_clause = " AND status = 'Canceled'";
        break;
    case 5:
        $status_clause = " AND status = 'Rejected'";
        break;
    case 6:
        $status_clause = " AND status IN('Canceled', 'Rejected')";
        break;
    case 7:
        $status_clause = " AND status = 'Success'";
        break;
    default:
        $status_clause = " AND status IN('Pending', 'In process')";
        break;
}
if($_GET['service'] == 0) {
    $service_clause = "";
} else {
    $service_clause = " AND service = " . $_GET['service'];
}

if($_GET['search']['value'] != "") {
    $search_clause = " AND (UPPER(orders.IMEI) like '%" . strtoupper($_GET['search']['value']) . "%' OR UPPER(orders.SN) like '%" . strtoupper($_GET['search']['value']) . "%' OR UPPER(relative_id) like '%" . strtoupper($_GET['search']['value']) . "%')";
} else {
    $search_clause = "";
}

if($_GET['length'] == -1) {
    $limit = "";
} else {
    $limit = " LIMIT " . $_GET['start'] . ", " . $_GET['length'];
}
$rows = mysqli_query($DB->Link, "SELECT label, delivery_time, cancelRequest, checkRequest, cancelRevoke, checkRevoke, orders.id 'ID', relative_id, orders.IMEI 'imei', orders.SN 'sn', service_name, orders.status 'statut', admin_response_comments, order_date, price, last_update, orders.udid 'UDID', clear_email, orders.phone 'Phone', client_personal_notes, client_order_comments FROM orders, services WHERE services.id = service AND client = " . $_SESSION['client_id'] . $status_clause . $service_clause . $search_clause . " ORDER BY " . $column_sort . " " . $_GET['order'][0]['dir'] . $limit);
$results = [];
while($row = mysqli_fetch_assoc($rows)) {
    switch($row['statut']) {
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
    if($row['delivery_time'] == "Instant") {
        $cancel = "";
        $verify = "";
        $send_cancel = "";
        $send_verify = "";
    } else {
        if($row['cancelRequest'] == "1") {
            $cancel = '<br /><b style="color:crimson">Cancellation in progress</b>';
        } else {
            $cancel = "";
        }
        if($row['checkRequest'] == "1") {
            $verify = '<br /><b style="color:crimson">Verification in progress</b>';
        } else {
            $verify = "";
        }
        $send_cancel = "";
        $send_verify = "";
        if(($row['statut'] == "Pending" or $row['statut'] == "In process") and $row['cancelRequest'] == 0 and $row['cancelRevoke'] == 0 and $row['checkRequest'] == 0) {
            $send_cancel = '<br /><a style="background-color:black;color:white;margin: 5px;" class="btn" data-toggle="modal" data-target="#modal" data-webx="https://www.prounlockphone.com/orders/cancel.php?id=' . $row['ID'] . '">Cancel</a>';
        } elseif($row['statut'] == "Success" and $row['checkRequest'] == 0 and $row['checkRevoke'] == 0 and (strtotime(date("Y-m-d H:i")) - strtotime($row['last_update']) < 432000)) {
            $send_verify = '<br /><a style="background-color:black;color:white;margin: 5px;" class="btn" data-toggle="modal" data-target="#modal" data-webx="https://www.prounlockphone.com/orders/verify.php?id=' . $row['ID'] . '">Verify</a>';
        }
    }
    $row['admin_response_comments'] = cleanResponse($row['admin_response_comments'], ($row['imei'] != "" ? $row['imei'] : $row['sn']));
//    if(strtolower(substr($row['admin_response_comments'], 0, 4)) == "<pre") $row['admin_response_comments'] = substr($row['admin_response_comments'], strpos($row['admin_response_comments'], '>') + 1, -6);
//    if(strtolower(substr($row['admin_response_comments'], 0, 4)) != "<pre" and $row['admin_response_comments'] != "") $row['admin_response_comments'] = "<pre>{$row['admin_response_comments']}</pre>";
    if($row['statut'] == "In process") $row['statut'] = "Processing";
    $results[] = array(
        "relative_id" => $row['relative_id'],
        "ref" => '<div align="center">' . ($row['imei'] != "" ? $row['imei'] : $row['sn']) . ($row['label'] == '' ? '' : '<br/>' . $row['label']) . '</div>',
        "service" => $row['service_name'],
        "status" => '<span style="color:' . $color . '">' . $row['statut'] . '</span>',
//        "comments" => $row['admin_response_comments'] == "" ? "" : '<pre style="overflow:visible;margin:0px;color:crimson;height:100%;white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;">' . $row['admin_response_comments'] . '</pre>',
        "comments" => nl2br($row['admin_response_comments']),
        "order_date" => $row['order_date'],
        "price" => number_format($row['price']) . " {$_SESSION['symbol']}",
        "last_update" => $row['last_update'],
        "udid" => $row['UDID'],
        "clear_email" => $row['clear_email'],
        "phone" => $row['Phone'],
        "action" => '<div align="center"><a style="margin: 5px;" class="btn btn-success" data-toggle="modal" data-target="#modal" data-webx="https://www.prounlockphone.com/orders/getDetails.php?id=' . $row['ID'] . '">Details</a>' . $cancel . $verify . $send_cancel . $send_verify . '</div>'
    );
}

$count = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM orders WHERE client = " . $_SESSION['client_id'] . $status_clause . $service_clause . $search_clause));

$response = array(
    "draw" => $_GET['draw'],
//    "recordsTotal" => mysqli_num_rows($rows = mysqli_query($DB->Link, "SELECT id FROM orders WHERE client = " . $_SESSION['client_id'] . $status_clause . $service_clause . $search_clause)),
    "recordsTotal" => $count,
//    "recordsFiltered" => mysqli_num_rows($rows),
    "recordsFiltered" => $count,
    "data" => $results
);
echo json_encode($response);
?>