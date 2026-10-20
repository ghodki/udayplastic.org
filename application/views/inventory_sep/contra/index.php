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
        display: none;
     }
    .form-group label {
        color: #fff;
    }
</style>

<div class="loader-bg"></div>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="header">
                    <div class="col-md-12 col-xl-12 col-lg-12">
                        <div class="header-body">
                            <h4 style="color: white; padding: 5px 0px 0px 0px;" class="text-center">
                                Add Contra
                            </h4>
                            <form id="dataForm" method="POST" onsubmit="onContraFormSubmitHandler(event);">
                                <input type="hidden" name="payment_type" value="Contra" id="payment_type" />
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="financial_year">Financial Year <span class="text-danger">*</span></label>
                                            <select name="financial_year" id="financial_year" class="form-control form-control-sm" title="Financial Year" required="">
                                                <?php if ($this->db->select('id, year')->get('year')->result()) { foreach ($this->db->select('id, year')->order_by('year', 'DESC')->get('year')->result() as $key => $ydata) { ?>
                                                <option <?= $this->session->userdata('financial_year') == $ydata->id ? "selected" : ""; ?> value="<?= $ydata->id; ?>"><?= $ydata->year; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="voucher_number">Voucher Number <span class="text-danger">*</span></label>
                                            <input type="text" name="voucher_number" id="voucher_number" value="<?= set_value('voucher_number'); ?>" class="form-control form-control-sm" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="date" id="date" value="<?= set_value('date', date('Y-m-d')); ?>" class="form-control form-control-sm" required="" />
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-white" />
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="ledgerIdTo">Select Ledger Name (To) <span class="text-danger">*</span></label>
                                            <select name="ledgerIdTo" id="ledgerIdTo" class="select2 form-control form-control-sm" onchange="getLedgerBalanceTo(); getLedgerFromList(this.value);" title="Ledger Name" required="">
                                                <option selected="" disabled="" value="">Select Ledger Name</option>
                                                <?php 
                                                    if (isset($ledgers) && !empty($ledgers)) {
                                                        foreach ($ledgers as $key => $ledger) {
                                                            $accountGroupName = get_title('accounting_group', ['id' => $ledger->group_head], 'name');
                                                ?>
                                                <option value="<?= $ledger->id; ?>"><?= $ledger->name; ?> (<?= $accountGroupName; ?>)</option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="balanceAmountTo">Balance Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="balanceAmountTo" id="balanceAmountTo" class="form-control form-control-sm" placeholder="Balance Amount" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="paid_amount">Paid Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="paid_amount" id="paid_amount" class="form-control form-control-sm" min="0" placeholder="Paid Amount" step="0.1" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="ledgerIdFrom">Select Ledger Name (From) <span class="text-danger">*</span></label>
                                            <select name="ledgerIdFrom" id="ledgerIdFrom" class="form-control form-control-sm" onchange="getLedgerBalanceFrom();" title="Ledger Name" required="">
                                                <option selected="" disabled="" value="">Select Ledger Name</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="balanceAmountFrom">Balance Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="balanceAmountFrom" id="balanceAmountFrom" class="form-control form-control-sm" placeholder="Balance Amount" required="" readonly="" />
                                        </div>
                                    </div>                                    
                                </div>
                                
                                <hr class="bg-white" />
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="payment_mode">Payment Mode <span class="text-danger">*</span></label>
                                            <select name="payment_mode" id="payment_mode" class="form-control form-control-sm" title="Payment Mode" required="">
                                                <option disabled="" value="">Select Payment Mode</option>
                                                <?php
                                                    $paymentModeList = $this->db->select('id, payment_method')->where('status', 1)->get('payment_mode')->result();
                                                    if ($paymentModeList) { foreach ($paymentModeList as $row) {
                                                ?>
                                                <option value="<?= $row->id; ?> <?= set_select('payment_mode', $row->id); ?>"><?= ucwords($row->payment_method); ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2 Cheque d-none">
                                        <div class="form-group">
                                            <label for="ChequeDate">Cheque/E-Transfer Date</label>
                                            <input type="date" name="ChequeDate" value="<?= date('Y-m-d'); ?>" id="ChequeDate" class="form-control form-control-sm" title="Cheque/E-Transfer Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2 Cheque d-none">
                                        <div class="form-group">
                                            <label for="chequeOrTransferNumber">Cheque/E-Transfer Number</label>
                                            <input type="text" name="chequeOrTransferNumber" id="chequeOrTransferNumber" class="form-control form-control-sm" placeholder="Cheque/E-Transfer Number" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2 Cheque d-none">
                                        <div class="form-group">
                                            <label for="bankName">Bank Name</label>
                                            <input type="text" name="bankName" id="bankName" class="form-control form-control-sm" placeholder="Bank Name" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('remark'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                            <button type="submit" class="btn btn-light btn-sm">
                                                Save
                                            </button>
                                            <?php } else { ?>
                                            <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                            <?php } } else { ?>
                                            <button type="submit" class="btn btn-light btn-sm" id="submitButton">
                                                Save
                                            </button>
                                            <?php } ?>
                                            <input type="reset" class="btn btn-danger btn-sm" placeholder="Reset">
                                        </div>
                                    </div>
                                </div>
                            </form>                        
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12 col-lg-12">
                        <div class="card card-primary card-outline mt-2">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="header-title text-dark">Contra Statement</h5>
                                </div>
                                <?php if ($this->session->userdata('RoleType')) { ?>
                                <a href="<?= base_url(); ?>employee/PaymentsStatement" class="btn btn-success btn-xs pull-right" title="Account Statement">
                                    <i class="fa fa-university"></i> Account Statement
                                </a>
                                <?php } else { ?>
                                <div class="pull-right">
                                    <a href="<?= base_url(); ?>payment/statement" class="btn btn-success btn-xs" title="Account Statement">
                                        <i class="fa fa-university"></i> Account Statement
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <form id="searchForm" method="POST" onsubmit="onContraSearchHandler(event);">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="date" name="From" value="<?= date('Y-m-01'); ?>" class="form-control form-control-sm" id="From" title="From" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="date" name="To" value="<?= date('Y-m-d'); ?>" class="form-control form-control-sm" id="To" title="To" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-search text-white"></i> Search
                                                </button>
                                                <button type="reset" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-refresh text-white"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive"></div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contraRemark" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
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
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.colVis.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        getVoucherNumber('Contra');
        getContraStatement('Contra');
        
        $("#ledgerIdTo").select2();
        $("#ledgerIdFrom").select2();
        
        var chequeDate = $('#ChequeDate').val();
        $('#payment_mode').change(function() {
                var selectedValue = $('#payment_mode option:selected').text();
                if (selectedValue === 'Cheque' || selectedValue === 'E-Transfer') {
                    $('#ChequeDate').attr('required', 'required').val(chequeDate);
                    $('.Cheque').removeClass('d-none');
                } else {
                    $('#ChequeDate').removeAttr('required').val('');
                    $('.Cheque').addClass('d-none');
                }
        });
    });
    
    const getVoucherNumber = (voucherType) => {
        if (voucherType != null && voucherType != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>contra/getVoucherNumber",
                data    :   {voucherType: voucherType},
                success :   function (response) {
                                var jsonResponse = JSON.parse(response);
                                if (jsonResponse.status == true) {
                                    $("#voucher_number").val(jsonResponse.data);
                                }
                            }
            });
        }
    };
    
    function getLedgerBalanceTo() {
        let ledgerId = $("#ledgerIdTo").val();
        if (ledgerId != '') {
            $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url(); ?>contra/getLedgerAccountBalance",
                    data    :   {ledgerId: ledgerId},
                    success :   function(response) {
                                    var responseData = JSON.parse(response);
                                    if (responseData.status == true) {
                                        $('#balanceAmountTo').val(parseFloat(responseData.ledgerAccountBalance).toFixed(2));
                                    } else {
                                        $('#balanceAmountTo').val(0);
                                    }
                                }
            });
        }
    }
    
    const getLedgerFromList = (ledgerId) => {
        if (ledgerId != null && ledgerId != "") {
                $.ajax({
                        type       :    "POST",
                        url         :    "<?= base_url(); ?>contra/getLedgerFromList",
                        data      :    {ledgerId: ledgerId},
                        success :    function (response) {
                                                if (response) {
                                                        $("#ledgerIdFrom").html("<option selected='' disabled='' value=''>Select Ledger From</option>" + response);
                                                }
                                            }
                });
        }
    };    
    
    function getLedgerBalanceFrom() {
        let ledgerId = $("#ledgerIdFrom").val();
        if (ledgerId != '') {
            $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url(); ?>contra/getLedgerAccountBalance",
                    data    :   {ledgerId: ledgerId},
                    success :   function(response) {
                                    var responseData = JSON.parse(response);
                                    if (responseData.status == true) {
                                        $('#balanceAmountFrom').val(parseFloat(responseData.ledgerAccountBalance).toFixed(2));
                                    } else {
                                        $('#balanceAmountFrom').val(0);
                                    }
                                }
            });
        }
    }
    
    const getContraStatement = (voucherType) => {
        $(".loader-bg").css("display", "block");
        var fromDate = $("#From").val();
        var toDate = $("#To").val();
        
        if (voucherType != '') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>contra/getContraStatement",
                data    :   {voucherType: voucherType, fromDate: fromDate, toDate: toDate},
                success :   function (response) {
                                if (response) {
                                    $(".table-responsive").html(response);
                                    $("#allPayments").DataTable({
                                        dom: "Bfrtip",
                                        order: [[7, "asc"]],
                                        pageLength: 20,
                                        lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
                                        buttons: [
                                            {
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
                                    $(".loader-bg").css("display", "none");
                                } else {
                                    $(".table-responsive").html('<span class="text-danger text-center">No Record found.</span>');
                                    $(".loader-bg").css("display", "none");
                                }
                            }
            });
        }
    };    
    
    const onContraEntryCheckHandler = () => {
        var contraDate = $("#date").val();
        var ledgerId = $("#ledgerIdTo").val();
        var amount = $("#paid_amount").val();

        if (contraDate && ledgerId && amount) {
            return $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>contra/checkContraEntry",
                data    :   {contraDate: contraDate, ledgerId: ledgerId, amount: amount},
                async   :   false,
                success :   function(response) {
                                if (JSON.parse(response).status == true) {
                                    JSON.parse(response);
                                }
                            }
            }).responseText;
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Required field should not be empty.',
                showConfirmButton: false,
                timer: 2000
            });
        }
    };
    
    const onContraFormSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var financialYear = $("#financial_year").val();
        var voucherNumber = $("#voucher_number").val();
        var date = $("#date").val();
        var ledgerIdTo = $("#ledgerIdTo").val();
        var balanceAmountTo = $("#balanceAmountTo").val();
        var paidAmount = $("#paid_amount").val();
        var ledgerIdFrom = $("#ledgerIdFrom").val();
        var balanceAmountFrom = $("#balanceAmountFrom").val();
        var paymentType = $("#payment_type").val();
        var paymentMode = $("#payment_mode").val();
        
        if (financialYear && voucherNumber && date && ledgerIdTo && balanceAmountTo && paidAmount && paymentType && paymentMode && ledgerIdFrom && balanceAmountFrom) {
            if (JSON.parse(onContraEntryCheckHandler()).data == 'unique') {
                $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url(); ?>contra/save",
                    data    :   $("#dataForm").serialize(),
                    success :   function(response) {
                                    var responseData = JSON.parse(response);
                                    if (responseData.status === true) {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'success',
                                            title: responseData.message,
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        window.setTimeout(function() {
                                            $("#ledgerIdTo option:first").prop("disabled", false);
                                            $("#ledgerIdFrom option:first").prop("disabled", false);
                                        
                                            $("#ledgerIdTo").val('');
                                            $("#ledgerIdFrom").val('');
                                            $("#balanceAmountTo").val("");
                                            $("#paid_amount").val("");
                                            $("#payment_mode").val("");                                            
                                            $("#balanceAmountFrom").val("");
                                            $("#ChequeDate").val("");
                                            $("#chequeOrTransferNumber").val("");
                                            $("#bankName").val("");
                                            $("#remark").val("");

                                            getContraStatement('Contra');
                                            getVoucherNumber('Contra');
                                            $("#submitButton").prop("disabled", false);
                                        }, 1000);
                                        $("#ledgerIdTo option:first").prop("disabled", true);
                                        $("#ledgerIdFrom option:first").prop("disabled", true);
                                    } else {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'error',
                                            title: responseData.message,
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        $("#submitButton").prop("disabled", false);
                                    }
                                }
                });
            } else {
                Swal.fire({
                    title: JSON.parse(onContraEntryCheckHandler()).title,
                    text: JSON.parse(onContraEntryCheckHandler()).text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type    :   "POST",
                            url     :   "<?= base_url(); ?>contra/save",
                            data    :   $("#dataForm").serialize(),
                            success :   function(response) {
                                            var responseData = JSON.parse(response);
                                            if (responseData.status === true) {
                                                Swal.fire({
                                                    position: 'top-end',
                                                    icon: 'success',
                                                    title: responseData.message,
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                });
                                                window.setTimeout(function() {
                                                    $("#ledgerIdTo option:first").prop("disabled", false);
                                                    $("#ledgerIdFrom option:first").prop("disabled", false);
                                                    
                                                    $("#ledgerIdTo").val('');
                                                    $("#ledgerIdFrom").val('');
                                                    $("#paid_amount").val("");
                                                    $("#payment_mode").val("");
                                                    $("#balanceAmountFrom").val("");
                                                    $("#ChequeDate").val("");
                                                    $("#chequeOrTransferNumber").val("");
                                                    $("#bankName").val("");
                                                    $("#remark").val("");

                                                    getContraStatement('Contra');
                                                    getVoucherNumber('Contra');
                                                    $("#submitButton").prop("disabled", false);
                                                }, 1000);
                                                $("#ledgerIdTo option:first").prop("disabled", true);
                                                $("#ledgerIdFrom option:first").prop("disabled", true);
                                            } else {
                                                Swal.fire({
                                                    position: 'top-end',
                                                    icon: 'error',
                                                    title: responseData.message,
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                });
                                                $("#submitButton").prop("disabled", false);
                                            }
                                        }
                        });           
                    } else {
                        $("#submitButton").prop("disabled", false);
                    }
                });
            }
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
    
    const onContraSearchHandler = (e) => {
        e.preventDefault();
        $(".loader-bg").css("display", "block");
        var voucherType = 'Contra';
        var fromDate = $("#From").val();
        var toDate = $("#To").val();
        
        if (voucherType && fromDate && toDate) {    
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>contra/getContraStatement",
                data    :   {voucherType: voucherType, fromDate: fromDate, toDate: toDate},
                success :   function (Response) {
                                if (Response) {
                                    $(".table-responsive").html(Response);
                                    $("#allPayments").DataTable({
                                        dom: "Bfrtip",
                                        order: [[7, "asc"]],
                                        pageLength: 20,
                                        lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
                                        buttons: [
                                            {
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
                                    $(".loader-bg").css("display", "none");
                                } else {
                                    $(".table-responsive").html('<span class="text-danger text-center">No Record found.</span>');
                                    $(".loader-bg").css("display", "none");
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
            $(".loader-bg").css("display", "none");
        }
    };    
    
    const onGetContraRemarkHandler = (Id) => {
        jQuery.noConflict();
        $("#contraRemark .modal-dialog .modal-content").load("<?= base_url(); ?>contra/getContraRemark/" + Id);
        $("#contraRemark").modal("show");
    };
    
    const onContraDeleteHandler = (Id) => {
        if (Id == 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Contra id missing",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        
        <?php if ($this->session->userdata('RoleType')) { ?>
        var url = "<?= base_url(); ?>" + Id;
        <?php } else { ?>
        var url = "<?= base_url(); ?>contra/ajaxDelete/" + Id;
        <?php } ?>
            
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
                onContraDelete(url);
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
    
    function onContraDelete(url) {
        $.ajax({
            type    :   "GET",
            url     :   url,
            success :   function(response) {
                            var JSONResponse = JSON.parse(response);
                            if (JSONResponse.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: JSONResponse.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function() {
                                        $("#ledgerIdTo option:first").prop("disabled", false);
                                        $("#ledgerIdFrom option:first").prop("disabled", false);
                                    
                                        $('#ledgerIdTo').val("");
                                        $('#ledgerIdFrom').val("");
                                        $("#balanceAmountTo").val("");
                                        $("#paid_amount").val("");
                                        $("#payment_mode").val("");
                                        $("#balanceAmountFrom").val("");
                                        $("#ChequeDate").val("");
                                        $("#chequeOrTransferNumber").val("");
                                        $("#bankName").val("");
                                        $("#remark").val("");

                                        getContraStatement('Contra');
                                        getVoucherNumber('Contra');
                                }, 1000);
                                $("#ledgerIdTo option:first").prop("disabled", true);
                                $("#ledgerIdFrom option:first").prop("disabled", true);
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