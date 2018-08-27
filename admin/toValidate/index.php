<?php
define('INCLUDE_CHECK', true);
require '../../common.php';
require '../../online.php';
$DB = new DBConnection();

if($_SESSION['client_type'] != "admin") {
    header("Location: ../../login");
    exit();
}

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Validate Transactions") ?>
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/datatables.min.css">
	    <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/responsive.dataTables.min.css">
	    <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/tables.css">
        <link rel="stylesheet" type="text/css" href="https://www.prounlockphone.com/common/jquery.dataTables.min.css">
        <style id="fit-vids-style">
            td.details-control {
                background: url('https://www.prounlockphone.com/images/details_open.png') no-repeat center center;
                cursor: pointer;
            }
            tr.shown td.details-control {
                background: url('https://www.prounlockphone.com/images/details_close.png') no-repeat center center;
            }
            pre {
                white-space: pre-wrap;       /* Since CSS 2.1 */
                white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
                white-space: -pre-wrap;      /* Opera 4-6 */
                white-space: -o-pre-wrap;    /* Opera 7 */
                word-wrap: break-word;       /* Internet Explorer 5.5+ */
            }
        </style>
    </head>
    <body class="stretched device-lg">
	    <div id="wrapper" class="clearfix" style="animation-duration: 1.5s; opacity: 1;">
            <?php header_render("toValidate") ?>
            <section id="content" class="account">
                <div class="container">
                    <div class="col-md-16 margin30 notopmargin">
                        <div class="curved-widget widget-white">
                            <div class="widget-name-rev center-text">Gift PayPal Payments</div>
                            <div class="widget-content container-fluid">
                                <div class="col-md-3 form-group">
                                    <label>Filter by status</label>
                                    <select style="margin: 0" id="category" class="form-control">
                                        <option value="0" selected="selected">All</option>
                                        <option value="1">Not Validated</option>
                                        <option value="2">Validated</option>
                                    </select>
                                </div>
                                <div class="portlet-body" style="font-size: 14px;">
                                    <table id="transactions" class="display nowrap" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Username</th>
                                                <th>Amount</th>
                                                <th>Currency</th>
                                                <th>Transaction ID</th>
                                                <th>Sender</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php echo $footer ?>
	    </div>
        <?php echo $common_foot ?>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/masonry.min.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/datatables.min.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/dataTables.responsive.min.js"></script>
        
        <script type="text/javascript" src="https://www.prounlockphone.com/common/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/buttons.print.min.js"></script>        
        <script>          

$(document).ready(function() {
    var table = $('#transactions').on('preXhr.dt', function (e, settings, data) {
        data.category = $('#category').val();
    }).DataTable( {
        "order": [[0, "desc"]],
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "responsive": true,
        "ajax": "https://www.prounlockphone.com/admin/toValidate/getHistory.php",
        "columns": [
            {   "data": "date" },
            {   "data": "username" },
            {   "data": "amount" },
            {   "data": "currency" },
            {
                "data": "trx",
                "orderable": false
            },
            {   "data": "sender" },
            {
                "data": "action",
                "orderable": false
            }
        ],
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "dom": 'frtiBlp',
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "language": {
            "emptyTable":     "No transactions to show",
            "info":           "_START_ - _END_ / _TOTAL_ transactions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
            "infoEmpty":      "0 transactions",
            "infoFiltered":   "(filtered from _MAX_ total transactions)",
            "lengthMenu":     "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Show _MENU_ transactions",
            "loadingRecords": "Loading...",
            "processing":     "Processing...",
            "search":         "Search:",
            "zeroRecords":    "No matching transactions found",
        }
    });
    
    $('#category').on('change', function () {
        table.ajax.reload();
    });
});
	</script>
    </body>
</html>