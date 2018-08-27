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
    <?php echo admin_common_head_with_title("Statement") ?>
    <style>
th {
    text-align: center;
    padding: 5px;
}
td {
    padding: 2px 5px 2px 5px;
}
    </style>
</head>
<body>
<?php
require_once('superheader.php');
$query = "
SELECT *
FROM users, countries
WHERE country = country_code
AND users.id = " . $_GET['client'];
$row = mysqli_fetch_assoc(mysqli_query($DB->Link, $query));
$query = "
SELECT * FROM statement
WHERE client = " . $_GET['client'] . "
ORDER BY transaction_date DESC, id DESC";
$rows = mysqli_query($DB->Link, $query);
switch($row['currency']) {
    case "USD":
        $currency = "$";
        break;
    case "EUR":
        $currency = "&euro;";
        break;
    case "GBP":
        $currency = "&pound;";
        break;
    case "TND":
        $currency = "DT";
        break;
}
$balance = $row['balance'];
?>
    <a id="refund_a" style="float: right;margin-right:50px" href="#">Refund</a><a id="funds_a" style="float: right;margin-right:50px" href="#">Add credits</a><a id="details_a" style="float: right;margin-right:50px" href="#">Account details</a>
<?php
    if($row['requestMoneyBack'] == "1") {
        ?><a id="grantMB" onclick="$(this).css('display', 'none')" style="float: right;margin-right:50px;color:red" href='grantMoneyBack.php?id=<?php echo $_GET["client"] ?>' target="_blank">Grant access to request <i>Money Back</i></a>
<?php
    }
    if(isset($_GET['failed']) and $_GET['failed'] == "Yes") {
        if(isset($_GET['action']) and $_GET['action'] == "refund") {
            $action = 'refund';
        } elseif(isset($_GET['action']) and $_GET['action'] == "topup") {
            $action = 'top up';
        }
        echo "<h2 style=\"color:red;margin-top:80px;margin-left:50px\">Last {$action} failed: ";
        if(isset($_GET['reason'])) {
            if($_GET['reason'] == "duplicate") echo "duplicated PayPal transaction: " . (isset($_GET['trx']) ? $_GET['trx'] : "") . "</h2>";
            elseif($_GET['reason'] == "amount") echo "amount = 0.00</h2>";
            elseif($_GET['reason'] == "null") echo "no information provided</h2>";
            elseif($_GET['reason'] == "trx") echo "no transaction provided</h2>";
        }
    }
?>
    <h3 style="color:gray;margin-top:180px;margin-left:50px"><?php echo $row['first_name'] . " " . $row['last_name'] . " [" . $row['username'] . "] Current balance <span style='color:" . ($balance < 0 ? "red" : "green") . "'>" . number_format($balance, 2, ".", ",") . " " . $currency ?></span><?php
?></h3>
    <div id="details_div" style="display:none;margin-left:50px;font-size:1em;padding-bottom:10px">
        ID: <?php echo $_GET['client'] ?><br />
        Account type: <?php echo $row['type'] ?><br />
        Phone: <a href='phone:<?php echo $row['phone'] ?>'>+<?php echo $row['phone'] ?></a><br />
        eMail: <a href='mailto:<?php echo $row['email'] ?>'><?php echo $row['email'] ?></a><br />
        WhatsApp: <a href='whatsapp:<?php echo $row['whatsapp'] ?>'><?php echo $row['whatsapp'] ?></a><br />
        Viber: <a href='viber:<?php echo $row['viber'] ?>'><?php echo $row['viber'] ?></a><br />
        Skype: <a href='skype:<?php echo $row['skype'] ?>?chat'><?php echo $row['skype'] ?></a><br />
        Address: <?php echo $row['address1'] . ($row['address1'] != "" ? " " : "") . $row['address2'] . ((($row['address1'] != "") or ($row['address2'] != "")) ? ", " : "") . $row['city'] . ($row['city'] != "" ? ", " : "") . $row['post_code'] . ($row['post_code'] != "" ? " " : "") . $row['state'] . ((($row['post_code'] != "") or ($row['state'] != "")) ? ", " : "") . $row['english_name'] ?><br />
        Company: <?php echo $row['company'] ?><br />
        Web Site: <?php echo $row['web_site'] ?><br />
        Status: <?php echo $row['status'] ?><br />
        IP address: <?php echo $row['ip'] ?><br />
        Last Connection: <?php echo $row['last_connection'] ?><br />
        Creation Date: <?php echo $row['creation_date'] ?><br />
        Clear password: <?php echo $row['clear_pwd'] ?><br />
        Notes: <?php echo $row['notes'] ?><br />
    </div>
    <div id="funds_div" style="display:none;margin-left:50px;font-size:1em;padding-bottom:10px">
        <form method="post" action="add_funds.php">
            <input type="hidden" name="client" value="<?php echo $_GET['client'] ?>" />
            Amount: <input name="amount" type="text" value="0.00" onfocus="this.select()" style="margin-right:30px;width:80px;text-align:right;border-radius:5px;border:solid 1px gray;padding-right:5px" />
            Description: <select style="border-radius:5px;border:solid 1px gray;padding:2px" name="nature" onchange="if(this.value == 'PayPal' || this.value == 'Skrill' || this.value == 'Neteller'){senderLabel.style.display='';trxLabel.style.display='';commentLabel.style.display='none';trx.focus()}else{trxLabel.style.display='none';senderLabel.style.display='none';commentLabel.style.display='';comment.focus()}">
                <option value="PayPal">PayPal</option>
                <option value="Skrill">Skrill</option>
                <option value="Neteller">Neteller</option>
                <option value="Livraison main à main">Livraison main à main</option>
                <option value="Dépôt ATB">Dépôt ATB</option>
                <option value="">Put your comment</option>
            </select>
            <span id="trxLabel" style="margin-left:30px;margin-right:10px">Transaction: <input id="trx" name="trx" type="text" style="text-transform:uppercase;text-align:center;border-radius:5px;border:solid 1px gray;padding-right:5px" /></span>
            <span id="senderLabel" style="margin-left:30px;margin-right:10px">Sender: <input id="sender" name="sender" type="text" style="text-transform:lowercase;text-align:center;border-radius:5px;border:solid 1px gray;padding-right:5px" value="<?php echo $row['email'] ?>" /></span>
            <span id="commentLabel" style="margin-left:30px;margin-right:10px;display:none">Comment: <input id="comment" name="comment" type="text" style="text-align:center;border-radius:5px;border:solid 1px gray;padding-right:5px" /></span>
            <input type="submit" style="border-radius:5px;background-color:#177EE5;color:white" value="Deposit" />
        </form>
    </div>
    <div id="refund_div" style="display:none;margin-left:50px;font-size:1em;padding-bottom:10px">
        <form method="post" action="refund.php">
            <input type="hidden" name="client" value="<?php echo $_GET['client'] ?>" />
            Amount: <input id="to_refund" name="amount" type="text" value="0.00" onfocus="this.select()" style="margin-right:30px;width:80px;text-align:right;border-radius:5px;border:solid 1px gray;padding-right:5px" />
            Description: <select style="border-radius:5px;border:solid 1px gray;padding:2px" name="nature" onchange="if(this.value == 'PayPal'){refsenderLabel.style.display='';reftrxLabel.style.display='';refcommentLabel.style.display='none';reftrx.focus()}else{reftrxLabel.style.display='none';refsenderLabel.style.display='none';refcommentLabel.style.display='';refcomment.focus()}">
                <option value="PayPal">PayPal</option>
                <option value="Livraison main à main">Livraison main à main</option>
                <option value="">Put your comment</option>
            </select>

            <span id="reftrxLabel" style="margin-left:30px;margin-right:10px">Transaction: <input id="reftrx" name="trx" type="text" style="text-transform:uppercase;text-align:center;border-radius:5px;border:solid 1px gray;padding-right:5px" /></span>
            <span id="refsenderLabel" style="margin-left:30px;margin-right:10px">Sender: <input id="refsender" name="sender" type="text" style="text-transform:lowercase;text-align:center;border-radius:5px;border:solid 1px gray;padding-right:5px" value="<?php echo $row['email'] ?>" /></span>
            <span id="refcommentLabel" style="margin-left:30px;margin-right:10px;display:none">Comment: <input id="refcomment" name="comment" type="text" style="text-align:center;border-radius:5px;border:solid 1px gray;padding-right:5px" /></span>
            <input type="submit" style="border-radius:5px;background-color:#177EE5;color:white" value="Refund" />
        </form>
    </div>
    <table style="font-size:100%;width:80%" class="main_table" align="center" border="solid 1px #CCC">
        <tr>
            <th style="width:10%">Transaction ID</th>
            <th style="width:10%">Transaction Type</th>
            <th style="width:36%">Description</th>
            <th style="width:12%">Date</th>
            <th style="width:9%">Credit</th>
            <th style="width:9%">Debit</th>
            <th style="width:9%">Balance</th>
        </tr>
        <?php
while($row = mysqli_fetch_array($rows)) {
    switch($row['status']) {
        case "0":
            $req = mysqli_fetch_assoc(mysqli_query($DB->Link, "SELECT status FROM orders WHERE id = " . $row['order_id']));
            if($req['status'] == 'Pending') {
                $style = " style='background-color:orange;color:white'";
            } else {
                $style = " style='background-color:blue;color:white'";
            }
            break;
        case "1":
            $style = " style='background-color:green;color:white'";
            break;
        case "2":
            $style = " style='background-color:olive;color:white'";
            break;
        case "3":
            $style = "";
            break;
        case "4":
            $style = " style='background-color:brown;color:white'";
            break;
        case "5":
            $style = " style='background-color:#FFE1EB'";
            break;
    }
    echo "<tr" . $style . ">
            <td align='center'><input type='hidden' value='{$row['id']}' />" . $row['relative_id'] . "</td>
            <td>" . $row['transaction_type'] . "</td>
            <td>" . $row['description'] . " " . $row['paypal'] . "</td>
            <td align='center'>" . $row['transaction_date'] . "</td>
            <td align='right'><input class='credit' style='width:100%;background-color:inherit;border-style:none;text-align:right' value='" . ($row['credit'] == "0" ? "" : number_format($row['credit'], 2, ".", ",")) . "' readonly='true' /></td>
            <td align='right'>" . ($row['debit'] == "0" ? "" : number_format($row['debit'], 2, ".", ",") . " " . $currency) . " </td>
            <td align='right'>" . number_format($row['balance_after'], 2, ".", ",") . " " . $currency . "</td>
        </tr>\n";
}
?>
    </table><br /><br />
    <div id="Panel1" class="overlayer" style="display:none">
        <div id="Panel2" class="loading">
            <div>
                <img id="imgProcessingMaster" class="processing" src="https://www.prounlockphone.com/images/process.gif" alt="Processing" />
            </div>
        </div>
    </div>
    <script>
        var current = 0;
        var color;
        $(".credit").on('click', function() {
            color = $(this).css('color');
            $(this).css({
                'background-color': 'white',
                'color': 'red',
                'border': 'solid 1px black'
            }).removeAttr("readOnly").select();
            current = $(this).val();
            if(current == "") current = 0;
        }).on('blur submit', function() {
            if($(this).val() == current || (($(this).val() == 0 || $(this).val() == "") && current == 0)) {
                $(this).css({
                    'background-color': 'inherit',
                    'color': color,
                    'border': 'none'
                }).prop("readonly", true);
            } else {
                console.log($(this).parent().parent().find( ":hidden" ).val());
                $.get('updateStatement.php?id=' + $(this).parent().parent().find( ":hidden" ).val() + '&credit=' + $(this).val(), function() {
                    console.log('done');
                });
            }
        });
$("#details_a").on('click', function() {
    $("#details_div").slideToggle("slow");
    $("#refund_div").hide();
    $("#funds_div").hide();
});
$("#funds_a").on('click', function() {
    $("#funds_div").slideToggle("slow");
    $("#details_div").hide();
    $("#refund_div").hide();
});
$("#refund_a").on('click', function() {
    $("#to_refund").val("<?php echo $balance > 0 ? number_format($balance, 2, ".", ",") : "0" ?>");
    $("#refund_div").slideToggle("slow");
    $("#funds_div").hide();
    $("#details_div").hide();
});
    </script>
</body>
</html>