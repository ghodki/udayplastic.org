<style type="text/css">
    .loader-bg {
        background: #161616 url("<?= base_url(); ?>assets/images/loader.gif") no-repeat center center;
        opacity: 0.5;
        background-size: 5%;
        z-index: 9999;
        width: 100%;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
    }

    #progress {
        position: relative;
        width: 400px;
        border: 1px solid #ddd;
        padding: 1px;
        border-radius: 3px;
    }

    #bar {
        background-color: #B4F5B4;
        width: 0%;
        height: 10px;
        border-radius: 3px;
        padding-bottom: 10px;
    }

    #percent {
        position: absolute;
        top: 3px;
        left: 48%;
    }

    .form-control {
        border: none;
        border-bottom: 1px solid black;
    }

    #products tr th,
    tr td {
        white-space: nowrap;
    }

    #products tbody tr td {
        padding: 0.25rem;
    }

    .select2-container {
        width: 100% !important;
    }

    h1,
    h3 {
        color: #333;
    }

    h4 {
        color: #3a607a;
        background: #e9e9e9;
    }

    h2 {
        color: #3a607a;
        background: #f0f3f0;
        margin-top: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;

    }

    th {
        color: #fff;
        background-color: #00277a;
    }

    .summary-section {
        margin-bottom: 30px;
    }

    .calendar {
        display: inline-block;
        margin-top: 20px;
    }

    .calendar table {
        border-collapse: collapse;
        width: 100%;
    }

    .calendar th,
    .calendar td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .calendar th {
        background-color: #f4f4f4;
    }

    .span_btn {
        width: 156px;
        margin-bottom: 5px;
    }

    .tab {
        cursor: pointer;
        padding: 2px 8px;
        display: inline-block;
        background: #ddd;
        margin-right: 5px;
        border-radius: 5px 5px 0 0;
    }

    .tab.active {
        background: #3498db;
        color: white;
        background:#0b3389;
    }

    .sheet {
        display: none;
        padding: 10px;
        border: 1px solid #ccc;
        border-top: none;
    }

    .sheet.active {
        display: block;
    }
</style>
 
<div class="loader-bg"></div>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                            <?= $this->session->flashdata('msg'); ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-primary">
                                    Monthly GSTR1
                                </h5>
                            </div>

                        </div>
                        <div class="card-body">
                            <form id="gst-frm" action="<?= base_url(); ?>Gst_report/gstr1" method="POST">
                                <div class="row">
                                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                        <div class="intervalInput">
                                            <input id="startDate" type="text" placeholder="Select Month" min="02/2025" max="11/2025" />
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-9">
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Get Pending GST Sales Invoice List from our portal" style="border-color: #900000;color:blue" class="btn btn-primary text-white span_btn" onclick="getGstr('PENDING')">Pending Gstr1</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2" id="upload_gstr_div">
                                                <span title="Upload selected data on GSP portal" id="upload_gstr" style="background-color:#8e83e5;color:#fff;" class="btn span_btn">Upload Sales</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Get Gstr1 uploaded sales invoice list from our portal" style="background-color:#6aa76a;color:#fff;" onclick="getGstr('DONE')" class="btn span_btn">Done Gstr1</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Get GSTR1 DATA from GSP Portal" style="background-color:#0ecdcd;color:#fff;" id="getGstrData" class="btn span_btn">Get Gstr1 Data</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Get GSTR Invoice Summary from GSP Portal" style="background-color:#3b16d1;color:#fff;" id="invoiceSummary" class="btn span_btn">Invoice Summary</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Upload GSTR1 data from GSP to GSTR portal" style="background-color:#1a9d78;color:#fff;" id="uploadGstr1" class="btn span_btn">Upload Gstr1</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Get GSTR 1 Summary from GST Portal" style="background-color:#ff704f;color:#fff;" id="get-gstr1-summary" class="btn span_btn">Gstr1 summary</span>
                                            </div>

                                      
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Get GSTR1 Upload History from GST Portal" style="background-color:#b300ad;color:#fff;" id="list-upload-history" class="btn span_btn">Upload history</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Proceed file" style="background-color:#b3083f;color:#fff;" id="proceed-file" class="btn span_btn">Proceed file</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="file submit" style="background-color:#ef9c05;color:#fff;" id="file-submit" class="btn span_btn">File Submit</span>
                                            </div>
                                            <div class="col-lg-2 col-xl-2">
                                                <span title="Receive OTP from GST portal on GST registered mobile number" id="gstr1_evc_otp" style="background-color:#c7740e;color:#fff;" class="btn span_btn">Gstr1 OTP</span>
                                            </div>
                                            
                                        </div>
 
                                    </div>

                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12">
                                    <div class="table-responsive mt-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import File End -->


<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>

<?php include_once 'monthpicker.php'; ?>
<script type="text/javascript">
    $(document).ready(() => {
        $("#startDate").val('05/2025');
        $("#upload_gstr_div").hide();
        $(".loader-bg").hide();
    })
    const getGstr = (type) => {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        let get_gstr_url = "einvoice/getGstrList";
        if (type == "DONE") {
            get_gstr_url = "einvoice/getUploadedGstrList";
        }
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>" + get_gstr_url,
            data: {
                selected_month: selected_month,
                type: type
            },
            success: function(Response) {
                $(".loader-bg").hide();
                $("#upload_gstr_div").hide();
                if (type == "PENDING") {
                    $("#upload_gstr_div").show();
                }
                if (Response) {
                    $(".table-responsive").html(Response);
                    // console.log(Response,'Response'); 
                    /*
                    $("#gstr-table").DataTable({
                        dom: "Bfrtip",
                        pageLength: 20,
                        lengthMenu: [
                            [20, 50, 100, -1],
                            [20, 50, 100, "All"]
                        ],
                        buttons: [{
                                extend: 'copyHtml5',
                                footer: true,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                footer: true,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                footer: true,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'print',
                                footer: true,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pageLength'
                            }
                        ]
                    });
                    */
                    $(".loader-bg").css("display", "none");
                } else {
                    $(".table-responsive").html('<span class="text-danger text-center">No Record found.</span>');
                }
            }
        });
    };

    $("#filterProducts").click(function() {
        $(".loader-bg").css("display", "block");
        getGstr();
    });

    var inputs = document.querySelectorAll("input,select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                nextInput[0].focus();
            }
        });
    }

    // Select All
    $("#product-selectAll").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    $("#upload_gstr").click(function() {
        Swal.fire({
            title: "Are you sure ? you want to Upload Selected Sale Invoices on GST Portal !",
            showCancelButton: true,
            confirmButtonText: "Yes",
        }).then((result) => {
            if (result.isConfirmed) {
                // generateEwayBill(invoice_id);
                uploadSelectedSaleInvoice();
            }
        });
    })

    function uploadSelectedSaleInvoice() {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/uploadGstr",
            data: {
                selected_month: selected_month,
            },
            success: function(Response) {
                $(".loader-bg").hide();
                // console.log(Response, 'Response');
              
            },
        });
    }

    $("#getGstrData").click(function() {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/getGstrData",
            data: {
                selected_month: selected_month
            },
            success: function(Response) {
                $(".loader-bg").hide();
                $(".table-responsive").html("");
                if (Response) {
                    $(".table-responsive").html(Response);
                }
            },
        });
    });

    $("#invoiceSummary").click(function() {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/invoiceSummary",
            data: {
                selected_month: selected_month,
            },
            success: function(Response) {
                $(".loader-bg").hide();
                $(".table-responsive").html("");
                if (Response) {
                    $(".table-responsive").html(Response);
                }
            },
        });
    })

    $("#uploadGstr1").click(function() {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/uploadGstr1",
            data: {
                selected_month: selected_month
            },
            success: function(Response) {
                $(".loader-bg").hide();
                $(".table-responsive").html("");
                if (Response) {
                    $(".table-responsive").html(Response);
                }
            },
        });
    })

    $("#startDate").Monthpicker({
        onSelect: function() {
            var selected_month = $("#startDate").val();
        }
    });

    $("#get-gstr1-summary").click(() => {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/gstr1Summary",
            data: {
                selected_month: selected_month
            },
            success: function(Response) {
                // console.log(Response, 'Response');
                $(".loader-bg").hide();
                $(".table-responsive").html("");
                if (Response) {
                    $(".table-responsive").html(Response);
                }
            },
        });
    })

    $("#list-upload-history").click(() => {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/uploadHistory",
            data: {
                selected_month: selected_month
            },
            success: function(Response) {
                $(".loader-bg").hide();
                $(".table-responsive").html("");
                if (Response) {
                    $(".table-responsive").html(Response);
                }
            },
        });
    })

    $("#proceed-file").click(() => {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/proceedFile",
            data: {
                selected_month: selected_month
            },
            success: function(Response) {
                $(".loader-bg").hide();
                let res = JSON.parse(Response);
                $(".table-responsive").html("");
                if (res.success == false) {
                    // console.log(res, 'res');
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 10000
                    })
                } else {
                    $(".table-responsive").html(Response);
                }
            },
        });
    })

    $("#file-submit").click(() => {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/fileSubmit",
            data: {
                selected_month: selected_month
            },
            success: function(Response) {
                $(".loader-bg").hide();
                let res = JSON.parse(Response);
                $(".table-responsive").html("");
                if (res.success == false) {
                    // console.log(res, 'res');
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 10000
                    })
                } else {
                    $(".table-responsive").html(Response);
                }
            },
        });
    })

    $("#gstr1_evc_otp").click(() => {
        $(".loader-bg").show();
        var selected_month = $("#startDate").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>einvoice/gstr1EvcOtp",
            data: {
                selected_month: selected_month
            },
            success: function(Response) {
                $(".loader-bg").hide();
                let res = JSON.parse(Response);
                $(".table-responsive").html("");
                if (res.success == false) {
                    // console.log(res, 'res');
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 10000
                    })
                } else {
                    $(".table-responsive").html(Response);
                }
            },
        });
    })


    function openSheet(sheetId) {
        $(".loader-bg").show();
        // console.log(sheetId + 'sheetid');
        for (i = 0; i <= 10; i++) {
            if (sheetId == i) {
                $("#sheet" + sheetId).addClass('active');
                $("#tab_"+i).addClass('active');
                $(".loader-bg").hide(); 
            } else {
                $("#sheet" + i).removeClass('active');
                $("#tab_"+i).removeClass('active');
            }
        } 
    }
</script>