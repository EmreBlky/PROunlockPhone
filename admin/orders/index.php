<?php
define('INCLUDE_CHECK', true);
require '../admin.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Orders Panel") ?>
<!--********************************************-->
        <!-- include summernote css/js -->
        <link href='https://www.prounlockphone.com/common/aa/summernote.css' rel='stylesheet'>
        <script src='https://www.prounlockphone.com/common/aa/summernote.js'></script>
<!--********************************************-->
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
            <?php admin_header_render("orders") ?>
            <section id="content" class="account">
                <div class="container">
                    <div class="col-md-12 margin30 notopmargin">
                        <div class="curved-widget widget-white">
                            <div class="widget-name-rev center-text">Orders Panel</div>
                            <div class="widget-content container-fluid">
                                <div class="col-md-3 form-group">
                                    <label>Filter by status</label>
                                    <select style="margin: 0" id="status" class="form-control">
                                        <option value="0" selected="selected">All</option>
                                        <option value="1">Pending</option>
                                        <option value="2">In process</option>
                                        <option value="3">Pending + Processing</option>
                                        <option value="4">Canceled</option>
                                        <option value="5">Rejected</option>
                                        <option value="6">Canceled + Rejected</option>
                                        <option value="7">Success</option>
                                    </select>
                                </div>
                                <div class="col-md-9 form-group">
                                    <label>Filter by service</label>
                                    <select style="margin: 0;display:inline" id="service" class="form-control">
                                        <option value="0" selected="selected">All</option>
                                        <?php
                                        $rows = mysqli_query($DB->Link, "SELECT DISTINCT services.id 'ID', service_name FROM services, orders WHERE services.id = service AND client = " . $_SESSION['client_id'] . " ORDER BY service_name");
                                        while($row = mysqli_fetch_assoc($rows)) {
                                            echo '                                            <option value="' . $row['ID'] . '">' . $row['service_name'] . '</option>';

                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="portlet-body" style="font-size: 14px;">
                                    <table id="orders" class="display nowrap" width="100%" cellspacing="0"></table>
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
    <?php
        if(!isset($_GET['ref']) || $_GET['ref'] != '') {
            echo "$('#status').val('3');";
        }
    ?>

    var table = $('#orders').on('preXhr.dt', function (e, settings, data) {
        data.service = $('#service').val();
        data.status = $('#status').val();
    }).DataTable( {
        "processing": true,
        "serverSide": true,
        "stateSave": false,
        "responsive": true,
        "ajax": {
            "url": "https://www.prounlockphone.com/admin/orders/getHistory.php",
            "complete": function (response) {
                $('.comments').summernote({
                    toolbar: [
                        // [groupName, [list of button]]
                        ['fontname', ['fontname']],
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['view', ['codeview']],
                    ],
                    width: 350,
                    minWidth: 200,
                    height: 210,
                    minHeight:200
                });
                var elems = $('.js-switch');
                for (var i = 0; i < elems.length; i++) {
                    if (!$(elems[i]).next('.switchery').length) {
                        var switchery = new Switchery(elems[i], {
                            size: 'small',
                            color: '#418bca'
                        });
                    }
                }
            }
        },
        "columns": [
            {
                "title": "Date",
                "data": "order_date",
                "orderable": true,
                "className": "never"
            },
            {
                "title": "User",
                "data": "user",
                "orderable": false
            },
            {
                "title": "IMEI / SN",
                "data": "ref",
                "orderable": false
            },
            {
                "title": "Services",
                "data": "service",
                "orderable": false
            },
            {
                "title": "Admin comments",
                "data":      "comments",
                "orderable": false
            },
            {
                "title": "Actions",
                "data":      "action",
                "orderable": false
            }
        ],
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "dom": 'frtiBlp',
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "language": {
            "emptyTable":     "No orders to show",
            "info":           "_START_ - _END_ / _TOTAL_ orders&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
            "infoEmpty":      "0 orders",
            "infoFiltered":   "(filtered from _MAX_ total orders)",
            "lengthMenu":     "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Show _MENU_ orders",
            "loadingRecords": "Loading...",
            "processing":     "Processing...",
            "search":         "Search:",
            "zeroRecords":    "No matching orders found",
        },
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if(aData.pinToTop == "1" ) {
                $('td', nRow).css('background-color', 'bisque');
            } else if(aData.cancelRequest == "1" ) {
                $('td', nRow).css('background-color', '#FFE1EB');
            } else if(aData.checkRequest == "1" ) {
                $('td', nRow).css('background-color', '#FCF3CF');
            }
        }
    });
    
    $('#status').on('change', function () {
        table.ajax.reload();
    });
    $('#service').on('change', function () {
        table.ajax.reload();
    });

    <?php
        if(isset($_GET['ref']) && $_GET['ref'] != '') {
            echo "
    table.search('{$_GET['ref']}').draw();
            ";
        }

        if(isset($_GET['error'])) {
            if($_GET['error'] == 'noverify') {
                ?>
    $.jGrowl("There was a problem processing your check-request!<br />Check whether your order satisfies the required conditions.", {theme: 'growlFail'});
                <?php
            } elseif($_GET['error'] == 'nocancel') {
                ?>
    $.jGrowl("There was a problem processing your cancel-request!<br />Check whether your order satisfies the required conditions to be canceled.", {theme: 'growlFail'});
                <?php
            }
        }
    ?>
});
	</script>
    </body>
</html>