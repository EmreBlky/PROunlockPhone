<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

$data = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT status, checkRequest, checkRevoke, last_update FROM orders WHERE id = {$_POST['id']}"));
if($data['status'] == "Success" and $data['checkRequest'] == 0 and $data['checkRevoke'] == 0 and (strtotime(date("Y-m-d H:i")) - strtotime($data['last_update']) < 432000)) {
    $data = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT first_name, last_name, username, email, relative_id, service_name, order_date, orders.imei 'IMEI', orders.sn 'SN', client_order_comments
    FROM orders, services, users
    WHERE 
        orders.id = {$_POST['id']}
        AND users.id = client
        AND services.id = service"));
    $body = "Client: {$data['first_name']} {$data['last_name']} ({$data['username']}: {$data['email']})<br />"
        . "Order ID: {$data['relative_id']}<br />"
        . "IMEI: {$data['IMEI']}<br />"
        . "S/N: {$data['SN']}<br />"
        . "Service: {$data['service_name']}<br />"
        . "Order date: {$data['order_date']}<br />"
        . "<br />"
        . "Reasons:";
    if(isset($_POST['completed']) and $_POST['completed']) $body .= "<br />- Order is not completed";
    if(isset($_POST['expected']) and $_POST['expected']) $body .= "<br />- Results do not match what you expected";
    if(isset($_POST['unsatisfied']) and $_POST['unsatisfied']) $body .= "<br />- Protest results, unsatisfied";
    if(isset($_POST['submit']) and $_POST['submit']) $body .= "<br />- You did not submit this order";
    if(isset($_POST['reprocess']) and $_POST['reprocess']) $body .= "<br />- Code / Results can't be read, need to reprocess";
    if(isset($_POST['other']) and $_POST['other'] != "") $body .= "<br />- Other reason(s):<div style='margin-left:40px'>" . nl2br($_POST['other']) . "</div>";
    mysqli_query($DB->Link, "
    UPDATE orders SET
        status = 'In process',
        checkRequest = 1
     WHERE orders.id = {$_POST['id']}");
    mysqli_query($DB->Link, "
    UPDATE statement SET
        status = 0,
        transaction_type = \"order checking\"
    WHERE
        order_id = {$_POST['id']}
        AND status = 1
        ORDER BY transaction_date DESC LIMIT 1");
    require_once('../eMail.php');
    Notify_me("Check Request", $body);
    header("Location: https://www.prounlockphone.com/orders/");
} else {
    header("Location: https://www.prounlockphone.com/orders/?error=noverify");
}
?>