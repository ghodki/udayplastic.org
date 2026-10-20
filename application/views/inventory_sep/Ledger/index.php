<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form id="LedgerForm" method="POST" onsubmit="onLedgerFormSubmitHandler(event);">
                        <div class="card card-outline card-navy">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-navy">
                                        Ledger Entries Create
                                    </h5>
                                </div>
                                <?php if ($this->session->userdata('RoleType')) { ?>
                                <a href="<?= base_url(); ?>Employee/LedgerReport" class="btn btn-primary btn-sm pull-right" title="Ledger Report">
                                    <i class="fa fa-table text-white"></i> Ledger Report
                                </a>
                                <?php } else { ?>
                                <a href="<?= base_url(); ?>Ledger/Report" class="btn btn-primary btn-sm pull-right" title="Ledger Report">
                                    <i class="fa fa-table text-white"></i> Ledger Report
                                </a>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', date('Y-m-d')); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                                        <div class="form-group">
                                            <label for="LedgerId">Ledger <span class="text-danger">*</span></label>
                                            <select name="LedgerId" id="LedgerId" class="form-control form-control-sm" title="Ledger" required="" onchange="getLedgerStatement(this.value);">
                                                <option selected="" value="" disabled="">Select Ledger</option>
                                                <?php 
                                                    if ($Ledgers) { 
                                                        foreach ($Ledgers as $key => $Ledger) {
                                                            $AccountGroupName = "(" . get_title('accounting_group', ['id' => $Ledger->accounting_group], 'name') . ")";
                                                ?>
                                                <option value="<?= $Ledger->id; ?>" <?= set_select('LedgerId', $Ledger->id); ?>><?= $AccountGroupName; ?> <?= $Ledger->name; ?>, <?= $Ledger->ContactPerson; ?>, <?= $Ledger->Mobile; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <label for="Amount">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="Amount" value="<?= set_value('Amount'); ?>" id="Amount" class="form-control form-control-sm" placeholder="Amount" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <label for="Type">Type <span class="text-danger">*</span></label>
                                            <select name="Type" id="Type" class="form-control form-control-sm" title="Type" required="">
                                                <option value="" selected="" disabled="">Select Type</option>
                                                <option value="CR" <?= set_select('Type', 'CR'); ?>>CR</option>
                                                <option value="DR" <?= set_select('Type', 'DR'); ?>>DR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <label for="PaymentMode">Payment Mode <span class="text-danger">*</span></label>
                                            <select name="PaymentMode" id="PaymentMode" class="form-control form-control-sm" title="Payment Mode" required="">
                                                <option value="" selected="" disabled="">Select Payment Mode</option>
                                                <?php if ($PaymentModes) { foreach($PaymentModes as $key => $Mode) { ?>
                                                <option value="<?= $Mode->id; ?>" <?= set_select('PaymentMode', $Mode->id); ?>><?= $Mode->payment_method; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="Remark">Remark</label>
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <input type="submit" class="btn btn-primary btn-sm" value="Submit" id="submitButton" />
                                        <input type="reset" class="btn btn-danger btn-sm" value="Reset" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-12">
                    <div class="card card-outline card-purple">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-purple">
                                    Ledger Entries Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" onsubmit="onLedgerSearchHandler(event);" class="mb-2">
                                <div class="row">
                                    <div class="form-group col-md-2 col-lg-2 col-sm-2">
                                        <input type="date" name="fromDate" id="fromDate" class="form-control form-control-sm" required="" />
                                    </div>
                                    <div class="form-group col-md-2 col-lg-2 col-sm-2">
                                        <input type="date" name="toDate" id="toDate" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-lg-2 col-sm-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <button type="reset" class="btn btn-danger btn-sm">
                                            <i class="fa fa-refresh text-white"></i>
                                        </button>
                                        <a href="javascript:void(0);" class="btn btn-success btn-sm" title="Print" onclick="printStatement();">
                                            <i class="fa fa-print text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive"></div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript">
    const onLedgerFormSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var Date = $("#Date").val();
        var LedgerId = $("#LedgerId").val();
        var Amount = $("#Amount").val();
        var Type = $("#Type").val();
        
        if (Date && LedgerId && Amount && Type) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Ledger/Save",
                data    :   $("#LedgerForm").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        $("#Amount").val("");
                                        $("#Type").val("");
                                        $("#Remark").val("");
                                        
                                        getLedgerStatement($("#LedgerId").val());
                                        $("#submitButton").prop("disabled", false);
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#submitButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#submitButton").prop("disabled", false);
        }
    };
    
    const getLedgerStatement = (LedgerId) => {
        if (LedgerId != '') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Ledger/getLedgerStatement",
                data    :   {LedgerId: LedgerId},
                success :   function (Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status == true) {
                                    $("#fromDate").val(ResponseData.From);
                                    $("#toDate").val(ResponseData.To);
                                    $(".table-responsive").html(ResponseData.Table);
                                    $("table").DataTable({
                                        dom: "Bfrtip",
                                        order: [6, "asc"],
                                        pageLength: 20,
                                        lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
                                        buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "print", "pageLength"]
                                    });
                                } else {
                                    $(".table-responsive").html('<span class="text-danger text-center">No Record found.</span>');
                                }
                            }
            });
        }
    };
    
    const onLedgerSearchHandler = (e) => {
        e.preventDefault();
        var LedgerId = $("#LedgerId").val();
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        
        if (LedgerId && fromDate && toDate) {    
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Ledger/getLedgerStatement",
                data    :   {LedgerId: LedgerId, fromDate: fromDate, toDate: toDate},
                success :   function (Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status == true) {
                                    $(".table-responsive").html(ResponseData.Table);
                                    $("table").DataTable({
                                        dom: "Bfrtip",
                                        order: [6, "asc"],
                                        pageLength: 20,
                                        lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
                                        buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "print", "pageLength"]
                                    });
                                } else {
                                    $(".table-responsive").html('<span class="text-danger text-center">No Record found.</span>');
                                }
                            }
            });
        } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Select required fields.",
                showConfirmButton: false,
                timer: 2000
            });
        }
    };
    
    const printStatement = () => {
        var LedgerId = $("#LedgerId").val();
        var from = $("#fromDate").val();
        var to = $("#toDate").val();
        
        if (LedgerId && from && to) {
            window.open("<?= base_url(); ?>Ledger/PrintStatement?LedgerId="+LedgerId+"&fromDate="+from+"&toDate="+to);
        } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Select required fields.",
                showConfirmButton: false,
                timer: 2000
            });
        }
    };
    
    const onGetLedgerInfoHandler = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Ledger/Info/" + Id);
        $('#Info').modal('show');
    };
    
    const onLedgerEditHandler = (Id) => {        
        if (Id) {    
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Ledger/getLedgerEdit",
                data    :   {Id: Id},
                success :   function (Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status == true) {
                                    <?php if ($this->session->userdata('RoleType')) { ?>
                                    window.location.href = "<?= base_url(); ?>Employee/LedgerEntryEdit/" + ResponseData.LedgerId;
                                    <?php } else { ?>
                                    window.location.href = "<?= base_url(); ?>Ledger/Edit/" + ResponseData.LedgerId;
                                    <?php } ?>
                                } else {
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "error",
                                        title: ResponseData.msg,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            }
            });
        } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Id required fields.",
                showConfirmButton: false,
                timer: 2000
            });
        }
    };
    
    const onLedgerDeleteHandler = (Id) => {
        if (Id == 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Id missing",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        
        var URL = "<?= base_url(); ?>Ledger/AjaxLedgerDelete/" + Id;
            
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                onLedgerEntryDelete(URL);
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    title: "Cancelled",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    };
    
    function onLedgerEntryDelete(URL) {
        $.ajax({
            type    :   "GET",
            url     :   URL,
            success :   function(Response) {
                            var JSONResponse = JSON.parse(Response);
                            if (JSONResponse.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: JSONResponse.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function() {
                                    $("#Amount").val("");
                                    $("#Type").val("");
                                    $("#Remark").val("");

                                    getLedgerStatement($("#LedgerId").val());
                                }, 1000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: JSONResponse.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }
        });
    }
</script>