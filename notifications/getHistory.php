<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_GET['from']) {
    $from_clause = " AND instant >=  '" . $_GET['from'] . "'";
} else {
    $from_clause = '';
}

if($_GET['to']) {
    $to_clause = " AND instant <=  '" . $_GET['to'] . "'";
} else {
    $to_clause = '';
}

if($_GET['media']) {
    $media_clause = " AND type = '" . $_GET['media'] . "'";
} else {
    $media_clause = '';
}

if($_GET['type']) {
    $type_clause = " AND typeAlert = '" . $_GET['type'] . "'";
} else {
    $type_clause = '';
}

if($_GET['length'] == -1) {
    $limit = "";
} else {
    $limit = " LIMIT " . $_GET['start'] . ", " . $_GET['length'];
}

$query = "SELECT id, instant, type, typeAlert, subject, status, destination FROM notifications WHERE user = " . $_SESSION['client_id'] . $from_clause . $to_clause . $media_clause . $type_clause;
$rows = mysqli_query($DB->Link, $query . " ORDER BY instant DESC, id DESC" . $limit);
date_default_timezone_set('America/Denver');
$results = [];
while($row = mysqli_fetch_assoc($rows)) {
    switch($row['status']) {
        case "delivered":
            $color = "green";
            break;
        case "sent":
            $color = "blue";
            break;
        case "queued":
            $color = "orange";
            break;
        case "undelivered":
            $color = "#222";
            break;
        default:
            $color = "black";
            break;
    }
    $notif_date = new DateTime($row['instant']);
    $london_time = new DateTimeZone('Europe/London');
    $notif_date->setTimezone($london_time);
    $results[] = array(
        "notif_date" => $notif_date->format('Y-m-d H:i:s'),
        "media" => $row['type'],
        "type" => $row['typeAlert'],
        "subject" => $row['subject'],
        "status" => '<span style="color:' . $color . '">' . $row['status'] . '</span>',
        "destination" => $row['destination'],
        "action" => '<div align="center"><a style="margin: 5px;" class="btn btn-success" data-toggle="modal" data-target="#modal" data-webx="https://www.prounlockphone.com/notifications/getDetails.php?id=' . $row['id'] . '">Details</a></div>'
    );
}

$response = array(
    "draw" => $_GET['draw'],
    "recordsTotal" => mysqli_num_rows($rows = mysqli_query($DB->Link, $query)),
    "recordsFiltered" => mysqli_num_rows($rows),
    "data" => $results
);
echo json_encode($response);
?>