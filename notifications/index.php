<?php
define('INCLUDE_CHECK', true);
require '../common.php';
require '../online.php';
$DB = new DBConnection();

?><!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <?php echo common_head_with_title("Notifications History") ?>
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
            <?php header_render("notifications") ?>
            <section id="content" class="account">
                <div class="container">
                    <div class="col-md-16 margin30 notopmargin">
                        <div class="curved-widget widget-white">
                            <div class="widget-name-rev center-text">Notifications</div>
                            <div class="widget-content container-fluid">
                                <div class="col-md-3 form-group">
                                    <label>From</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input id="notificationFrom" type="text" class="form-control datemask" data-mask="9999-99-99" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>To</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input id="notificationTo" type="text" class="form-control datemask" data-mask="9999-99-99" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Media</label>
                                    <select id="notificationMedia" class="form-control">
                                        <option value="">All medias</option>
                                        <option value="eMail">eMail</option>
                                        <option value="SMS">SMS</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Type</label>
                                    <select id="notificationType" class="form-control">
                                        <option value="">All notifications</option>
                                        <?php
                                        $rows = mysqli_query($DB->Link, "SELECT DISTINCT typeAlert FROM notifications ORDER BY typeAlert");
                                        while($row = mysqli_fetch_array($rows)) {
                                            echo '<option value="' . $row['typeAlert'] . '">' . $row['typeAlert'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="portlet-body" style="font-size: 14px;">
                                    <table id="notifications" class="display nowrap" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Date [GMT]</th>
                                                <th>Media</th>
                                                <th>Type</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Destination</th>
                                                <th>Actions</th>
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

        <script type="text/javascript" src="https://www.prounlockphone.com/common/jquery.inputmask.js"></script>
        <script type="text/javascript" src="https://www.prounlockphone.com/common/jquery.inputmask.date.extensions.js"></script>
        <script>          

$(document).ready(function() {
    var table = $('#notifications').on('preXhr.dt', function (e, settings, data) {
        data.from = $('#notificationFrom').val();
        data.to = $('#notificationTo').val();
        data.media = $('#notificationMedia').val();
        data.type = $('#notificationType').val();
    }).DataTable( {
        "order": [[0, "desc"]],
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "responsive": true,
        "ordering": false,
        "searching": false,
        "ajax": "https://www.prounlockphone.com/notifications/getHistory.php",
        "columns": [
            {
                "data":     "notif_date",
                "orderable": false
            },
            {
                "data":     "media",
                "orderable": false
            },
            {
                "data":     "type",
                "orderable": false
            },
            {
                "data":      "subject",
                "orderable": false
            },
            {
                "data":      "status",
                "orderable": false
            },
            {
                "data":      "destination",
                "orderable": false
            },
            {
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
            "emptyTable":     "No notifications to show",
            "info":           "_START_ - _END_ / _TOTAL_ notifications&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
            "infoEmpty":      "no notifications",
            "infoFiltered":   "(filtered from _MAX_ total notifications)",
            "lengthMenu":     "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Show _MENU_ notifications",
            "loadingRecords": "Loading...",
            "processing":     "Processing...",
            "zeroRecords":    "No matching notification found",
        }
    });
    $(".datemask").inputmask("yyyy-mm-dd", {
        "placeholder": "YYYY-MM-DD"
    });
    $('#notificationFrom').on('change', function () {
        table.ajax.reload();
    });

    $('#notificationTo').on('change', function () {
        table.ajax.reload();
    });

    $('#notificationMedia').on('change', function () {
        table.ajax.reload();
    });

    $('#notificationType').on('change', function () {
        table.ajax.reload();
    });
});
	</script>
    </body>
</html>