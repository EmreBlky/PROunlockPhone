<?php
define('INCLUDE_CHECK', true);
require '../../common.php';
require '../../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../../login");
    exit();
}

$column_sort = 'relative_id';
switch($_GET['order'][0]['column']) {
    case 0:
        $column_sort = 'transaction_date';
        break;
    case 1:
        $column_sort = 'username';
        break;
    case 2:
        $column_sort = 'amount';
        break;
    case 3:
        $column_sort = 'currency';
        break;
    case 5:
        $column_sort = 'sender';
        break;
}
$validation_clause = "";
switch($_GET['category']) {
    case 1:
        $validation_clause = " AND validated = 0";
        break;
    case 2:
        $validation_clause = " AND validated = 1";
        break;
}
$search_clause = "";
if($_GET['search']['value'] != "") {
    $search_clause = " AND (UPPER(paypal) like '%" . strtoupper($_GET['search']['value']) . "%' OR UPPER(sender) like '%" . strtoupper($_GET['search']['value']) . "%' OR UPPER(username) like '%" . strtoupper($_GET['search']['value']) . "%' OR credit = '" . strtoupper($_GET['search']['value']) . "')";
}

if($_GET['length'] == -1) {
    $limit = "";
} else {
    $limit = " LIMIT " . $_GET['start'] . ", " . $_GET['length'];
}
$rows = mysqli_query($DB->Link, "SELECT users.id 'ID', username, transaction_date, credit, paypal, sender, validated, currency FROM users, statement WHERE users.id = client AND asGift = 1" . $validation_clause . $search_clause . " ORDER BY " . $column_sort . " " . $_GET['order'][0]['dir'] . $limit);

$results = [];
while($row = mysqli_fetch_assoc($rows)) {
    switch($row['validated']) {
        case 0:
            $class = "warning";
            $val = "Not ";
            break;
        case 1:
            $class = "success";
            $val = "";
            break;
    }

    $results[] = array(
        "username" => "<a target='_blank' href='https://www.prounlockphone.com/admin/statement.php?client={$row['ID']}'>" . $row['username'] . "</a>",
        "date" => '<div align="center">' . $row['transaction_date'] . '</div>',
        "amount" => '<div align="center">' . number_format($row['credit'], 2, ".", ",") . '</div>',
        "currency" => '<div align="center">' . $row['currency'] . '</div>',
        "trx" => '<div align="center">' . $row['paypal'] . '</div>',
        "sender" => $row['sender'],
        "action" => "<div align='center'><a style='margin:5px' class='btn btn-{$class}' data-toggle='modal' data-target='#modal' data-webx='https://www.prounlockphone.com/admin/toValidate/getDetails.php?trx={$row['paypal']}&currency={$row['currency']}&amount={$row['credit']}&valid={$row['validated']}'>{$val}Validated</a></div>"
    );
}

$response = array(
    "draw" => $_GET['draw'],
    "recordsTotal" => mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM statement WHERE asGift = 1")),
    "recordsFiltered" => mysqli_num_rows($rows),
    "data" => $results
);
echo json_encode($response);
?>