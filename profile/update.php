<?php
if(isset($_POST['firstname'])) {
    define('INCLUDE_CHECK', true);
    require '../common.php';
    require '../online.php';
    $DB = new DBConnection();

    $_POST['firstname'] = ucwords(strtolower(mysqli_real_escape_string($DB->Link, $_POST['firstname'])));
    $_POST['lastname'] = strtoupper(mysqli_real_escape_string($DB->Link, $_POST['lastname']));
//    $_POST['company'] = ucwords(strtolower(mysqli_real_escape_string($DB->Link, $_POST['company'])));
//    $_POST['website'] = strtolower(mysqli_real_escape_string($DB->Link, $_POST['website']));
//    $_POST['address1'] = ucwords(mysqli_real_escape_string($DB->Link, $_POST['address1']));
//    $_POST['address2'] = ucwords(mysqli_real_escape_string($DB->Link, $_POST['address2']));
//    $_POST['city'] = ucwords(strtolower(mysqli_real_escape_string($DB->Link, $_POST['city'])));
//    $_POST['state'] = ucwords(strtolower(mysqli_real_escape_string($DB->Link, $_POST['state'])));
//    $_POST['zipcode'] = mysqli_real_escape_string($DB->Link, $_POST['zipcode']);
    $_POST['country'] = mysqli_real_escape_string($DB->Link, $_POST['country']);
    $_POST['phone'] = mysqli_real_escape_string($DB->Link, $_POST['phone']);
    $_POST['viber'] = mysqli_real_escape_string($DB->Link, $_POST['viber']);
    $_POST['whatsapp'] = mysqli_real_escape_string($DB->Link, $_POST['whatsapp']);
    $_POST['skype'] = strtolower(mysqli_real_escape_string($DB->Link, $_POST['skype']));
    $_POST['showAds'] = strtolower(mysqli_real_escape_string($DB->Link, $_POST['showAds']));
    $_SESSION['client_long'] = $_POST['firstname'] . " " . $_POST['lastname'];
    $_SESSION['client_short'] = $_POST['firstname'];
    $_SESSION['showAds'] = $_POST['showAds'];

    $query = "UPDATE users SET
                        first_name = '{$_POST['firstname']}',
                        last_name = '{$_POST['lastname']}',
                        country = '{$_POST['country']}',
                        phone = '{$_POST['phone']}',
                        viber = '{$_POST['viber']}',
                        whatsapp = '{$_POST['whatsapp']}',
                        skype = '{$_POST['skype']}',
                        showAds = '{$_POST['showAds']}'
                        WHERE id='{$_SESSION['client_id']}'";
    $result = mysqli_query($DB->Link, $query);
    if($result === true) {
        $response = array(
            "type" => 1,
            "msg" => "Update completed!"
        );
    } else {
        $response = array(
            "type" => 0,
            "msg" => "Something went wrong during the update!<br />Please check your data."
        );
    }
    echo json_encode($response);
} else {
    header("Location: https://www.prounlockphone.com/login/?url=profile");
}
?>