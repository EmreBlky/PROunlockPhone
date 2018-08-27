<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../offline.php';
$DB = new DBConnection();

if(!isset($_POST['user'])) {
    $rows = mysqli_query($DB->Link, "SELECT id, first_name, last_name, username FROM users WHERE status <> 'Idle' ORDER BY username");
?>
<html>
<head>
    <?php echo admin_common_head_with_title("Special Login", "20") ?>
    <script>
    $(function(){
        $('#user').select2({
            placeholder: "Choose the client...",
            theme: "classic"
        });
    });
    </script>
</head>
<body style="padding:50px">
    <form method='post'>
        <select id="user" name='user' style="width:40%">
<?php
    while($row = mysqli_fetch_assoc($rows)) {
        echo "          <option value='{$row['id']}'>{$row['id']} {$row['first_name']} {$row['last_name']} ({$row['username']})</option>\n";
    }
?>      </select> <input type='password' name='pwd' /> <input type='submit' value='load' />
    </form>
</body>
</html>
<?php
} else {
    if($_POST['pwd'] != "2699") die("Check your password!");
    $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT id, username, first_name, last_name, language, balance, currency, status, email, type FROM users WHERE id='".$_POST['user']."'"));
    $_SESSION['username'] = $row['username'];
    $_SESSION['client_id'] = $row['id'];
    $_SESSION['client_email'] = $row['email'];
    $_SESSION['client_short'] = $row['first_name'];
    $_SESSION['last_name'] = $row['last_name'];
    $_SESSION['client_long'] = $row['first_name'] . " " . $row['last_name'];
    $_SESSION['language'] = $row['language'];
    $_SESSION['balance'] = $row['balance'];
    $_SESSION['currency'] = $row['currency'];
    switch($_SESSION['currency']) {
        case "USD":
            $_SESSION['symbol'] = "$";
            break;
        case "EUR":
            $_SESSION['symbol'] = "&euro;";
            break;
        case "GBP":
            $_SESSION['symbol'] = "&pound;";
            break;
        case "TND":
            $_SESSION['symbol'] = "DT";
            break;
    }
    $_SESSION['client_type'] = $row['type'];
    $_SESSION['RememberMe'] = "off";
    $_SESSION['showAds'] = "0";
    setcookie('RememberMe', "off");
    $_SESSION['start'] = true;
    header("Location: https://www.prounlockphone.com/main/");
}
?>