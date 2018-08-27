<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../login");
    exit();
}

?>
<html>
<head>
    <?php echo admin_common_head_with_title("Special Treatment") ?>
    <style>
        th {
            text-align: center;
            padding: 5px;
        }
        td {
            padding: 2px 5px 2px 5px;
        }
        .overlayer {
            width:100%;
            height:100%;
            position:fixed;
            top:0;
            left:0;
            background:url('White_transparent.png');
            background: -moz-linear-gradient(rgba(255,255,255,0.6), rgba(255,255,255,0.6)) repeat-x rgba(255,255,255,0.6);
            background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(255,255,255,0.6)), to(rgba(255,255,255,0.6))) repeat-x rgba(255,255,255,0.6);
            z-index:10000000;
        }
        .loading {
            position:fixed;
            opacity:1.0;
            -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
            filter:alpha(opacity=100);
            left:50%;
            top:50%;
            margin:-70px 0 0 -144px;
        }
    </style>
</head>
<body>
<?php
require_once('superheader.php');
if(isset($_GET['user'])) {
    switch($_GET['action']) {
        case 'makeReseller':
            $rows = mysqli_query($DB->Link, "UPDATE users SET type = 'reseller' WHERE id = {$_GET['user']}");
            break;
        case 'makeRegular':
            $rows = mysqli_query($DB->Link, "UPDATE users SET type = 'regular' WHERE id = {$_GET['user']}");
            break;
        case 'Activate':
            $row = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT first_name FROM user WHERE id = " . $_GET['user']));
            if($row['first_name'] == "") {
                $rows = mysqli_query($DB->Link, "UPDATE users SET status = 'Idle' WHERE id = {$_GET['user']}");
            } else {
                $rows = mysqli_query($DB->Link, "UPDATE users SET status = 'Active' WHERE id = {$_GET['user']}");
            }
            break;
        case 'Suspend':
            $rows = mysqli_query($DB->Link, "UPDATE users SET status = 'Suspended' WHERE id = {$_GET['user']}");
            break;
        case 'delete':
            $rows = mysqli_query($DB->Link, "DELETE FROM notifications WHERE user = {$_GET['user']}");
            $rows = mysqli_query($DB->Link, "DELETE FROM orders WHERE client = {$_GET['user']}");
            $rows = mysqli_query($DB->Link, "DELETE FROM price_client_service WHERE client = {$_GET['user']}");
            $rows = mysqli_query($DB->Link, "DELETE FROM statement WHERE client = {$_GET['user']}");
            $rows = mysqli_query($DB->Link, "DELETE FROM users WHERE id = {$_GET['user']}");
            break;
    }
}
$query = "
SELECT users.id 'ID', username, first_name, last_name, balance, currency, email, status, type, english_name, notes
FROM users, countries
WHERE
    country = country_code";
if(isset($_GET['list']) and $_GET['list'] != "all") {
    switch($_GET['list']) {
        case "active":
            $query .= " AND status = 'Active' AND balance <> 0";
            break;
        case "inactive":
            $query .= " AND status = 'Idle'";
            break;
        case "suspended":
            $query .= " AND status = 'Suspended'";
            break;
        case "0balance":
            $query .= " AND status = 'Active' AND balance = 0";
            break;
        case "reseller":
            $query .= " AND type = 'reseller'";
            break;
        case "moneyback":
            $query .= " AND requestMoneyBack = 1";
            break;
    }
} elseif(!isset($_GET['list'])) {
    $query .= " AND status = 'Active' AND balance <> 0";
}
$query .= "
ORDER BY
    first_name, last_name, username";
$rows = mysqli_query($DB->Link, $query);

$nums = mysqli_num_rows(mysqli_query($DB->Link, "SELECT id FROM users WHERE requestMoneyBack = 1"));
if($nums > 0) {
    $moneyback = '<a style="margin-left:100px" href="panel.php?list=moneyback">' . $nums . ' Money-Back requests pending!</a>';
} else {
    $moneyback = '';
}
?>

<h2 style="color:gray;margin-top:60px;margin-left:50px">Clients management: (<?php echo mysqli_num_rows($rows) ?> users)<?php echo $moneyback ?></h2>
<a style="margin-left:100px" href="panel.php?list=all">All</a>
<a style="margin-left:100px" href="panel.php?list=active">Active</a>
<a style="margin-left:100px" href="panel.php?list=reseller">Resellers</a>
<a style="margin-left:100px" href="panel.php?list=inactive">Inactive</a>
<a style="margin-left:100px" href="panel.php?list=suspended">Suspended</a>
<a style="margin-left:100px" href="panel.php?list=0balance">0 Balance</a>
<table style="font-size:100%;width:80%" class="main_table" align="center" border="solid 1px #CCC">
    <tr>
        <th style="width:35%">Client</th>
        <th style="width:15%">Notes</th>
        <th style="width:10%">Actions</th>
    </tr>
    <?php
    while($row = mysqli_fetch_array($rows)) {
        echo "<tr>
            <td>" . $row['first_name'] . " " . $row['last_name'] . " (<a href='user_frame.php?id=" . $row['ID'] . "'>" . $row['username'] . "</a>)<span style='color:white;padding:10px;background-color:black;float:right'><span style='text-transform:capitalize;color:" . ($row['status'] == "Suspended" ? "red" : "green") . "'>" . $row['status'] . "</span> | <span style='text-transform:capitalize;color:" . ($row['type'] == "regular" ? "white" : "gold") . "'>" . $row['type'] . "</span></span><br />
             " . $row['email'] . "<br />
             " . $row['english_name'] . "</td>
            <td><textarea class='notes' style='color:black;width:100%;height:100%'>" . $row['notes'] . "</textarea><input type='hidden' value='" . $row['ID'] . "' /></td>
            <td align='center'>
                <input type='button' value='" . ($row['type']  == 'regular' ? 'Reseller' : 'Regular') . "' style='width:100px;color:black' onclick='apply(" . $row['ID'] . ", \"" . ($row['type']  == 'regular' ? 'makeReseller' : 'makeRegular') . "\")' /><br/>
                <input type='button' value='" . ($row['status']  == 'Suspended' ? 'Uns' : 'S') . "uspend' style='width:100px;color:black' onclick='apply(" . $row['ID'] . ", \"" . ($row['status']  == 'Suspended' ? 'Activate' : 'Suspend') . "\")' /><br/>
                <input type='button' value='Delete' style='width:100px;color:black' onclick='apply(" . $row['ID'] . ", \"delete\")' />
            </td>
        </tr>\n";
    }
    ?>
</table><br /><br />
<script language="JavaScript">
    function apply(user, action) {
        if(action == 'delete') {
            if(!confirm('This operation is irreveersible. Are you sure?')) return;
        }
        window.location.href = 'https://www.prounlockphone.com/panel.php?<?php if(isset($_GET['list'])) { echo 'list=' . $_GET['list'] . '&'; } ?>user=' + user + '&action=' + action;
    }
    $(document).ready(function () {
        var variation;
        $(this).ajaxStart(function() {
            $('.overlayer').show();
        }).ajaxStop(function() {
            $('.overlayer').hide();
        });
        $(".notes").focus(function() {
            variation = $(this).val();
        }).blur(function() {
            if($(this).val() == variation) return false;
            $.get('https://www.prounlockphone.com/userSetNotes.php?user=' + $(this).parent().find( ":hidden" ).val() + '&notes=' + encodeURIComponent($(this).val()));
        });
    });
</script>
<br /><br />
<div class="overlayer" style="display:none">
    <div class="loading">
        <div>
            <img src="https://www.prounlockphone.com/images/process.gif" alt="Processing" />
        </div>
    </div>
</div>
</body>
</html>