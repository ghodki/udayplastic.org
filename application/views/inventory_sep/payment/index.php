<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
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
    .select2-container--default .select2-results__option {
        padding: 5px 5px;
    }
    .select2-results__option--selectable, select.form-control-sm~.select2-container--default {
        font-size: 0.7rem !important;
    }
    .select2-container .select2-selection--single {
        height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        top: 60%;
    }
    .table:not(.table-dark) {
        color: #000000;
    }
    .form-group label {
        color: #fff;
    }
    .btn-group{
        display: block;
    }
    .custom-select {
        height: calc(2rem + 1px);
        line-height: 1;
    }
    .form-check-label {
        color: #000 !important;
    }
    table.dataTable tfoot th {
        padding: 10px 5px 6px 5px;
    }
    .multiselect-container {
        transform: translate3d(0px, 32px, 0px) !important;
    }
    .dropdown-menu.show {
        height: auto;
        max-height: 200px;
        overflow-y: scroll;
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
                                Add Payment
                            </h4>
                            <form id="dataForm" method="POST" onsubmit="onPaymentFormSubmitHandler(event);">
                                <?php
                                        $accountGroup = $this->db->where('status', 1)->where('name', 'Sundry Creditors')->get('accounting_group')->row();
                                        if ($accountGroup) {
                                ?>
                                <input type="hidden" name="account_group" value="<?= $accountGroup->id; ?>" id="account_group" />
                                <?php } ?>
                                <input type="hidden" name="payment_type" value="Payment" id="payment_type" />
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
                                            <label for="voucher_type">Voucher Type <span class="text-danger">*</span></label>
                                            <select name="voucher_type" id="voucher_type" class="form-control form-control-sm" title="Voucher Type" onchange="getVoucherNumber(this.value);" required="">
                                                <option selected="" disabled="" value="">Voucher Type</option>
                                                <option <?= set_select("voucher_type", "Tax"); ?> value="Tax">Tax</option>
                                                <option <?= set_select("voucher_type", "Estimate Voucher"); ?> value="Estimate Voucher">Estimate</option>
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
                                    <div class="col-md-1 col-sm-1 col-lg-1">
                                        <div class="form-group">
                                            <label for="estimate">Estimate</label>
                                            <br />
                                            <input type="checkbox" name="estimate" value="true" <?= set_checkbox('estimate', 'true'); ?> id="estimate" title="Estimate" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="party_id">Select Party Name <span class="text-danger">*</span></label>
                                            <select name="party_id" id="party_id" class="select2 form-control form-control-sm" onchange="getPartyBalance(); getPandingInvoices(this.value); getPaymentStatement(this.value);" title="Party Name" required="">
                                                <option selected="" disabled="" value="0">Select Party Name</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="balance_amount">Balance Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="balance_amount" value="<?= set_value('balance_amount'); ?>" id="balance_amount" class="form-control form-control-sm" placeholder="Balance Amount" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="invoices">Select Invoices <span class="text-danger">*</span></label>
                                            <select name="invoices[]" id="invoices" class="form-control form-control-sm" placeholder="Select Invoices" title="Select Invoices" onchange="getInvoiceTotal();" onclick="getInvoiceTotal();" required="">
                                                <option selected="" disabled="" value="">Select Invoices</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="invoice_amount">Invoice Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="invoice_amount" value="<?= set_value('invoice_amount'); ?>" id="invoice_amount" class="form-control form-control-sm" min="0" placeholder="Invoice Amount" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="receivedPaidAmount">Received Paid Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="receivedPaidAmount" value="<?= set_value('receivedPaidAmount'); ?>" id="receivedPaidAmount" class="form-control form-control-sm" min="0" placeholder="Received Paid Amount" step="0.1" onchange="calculatePaidAmount();" oninput="calculatePaidAmount();" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="cashDiscountAmount">Cash Discount Amount</label>
                                            <input type="number" name="cashDiscountAmount" value="<?= set_value('cashDiscountAmount'); ?>" id="cashDiscountAmount" class="form-control form-control-sm" min="0" placeholder="Cash Discount Amount" step="0.1" onchange="calculatePaidAmount();" oninput="calculatePaidAmount();" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="paid_amount">Paid Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="paid_amount" value="<?= set_value('paid_amount'); ?>" id="paid_amount" class="form-control form-control-sm" min="0" placeholder="Paid Amount" step="0.1" required="" readonly="" />
                                        </div>
                                    </div>
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
                                            <label for="ChequeDate">Cheque Date</label>
                                            <input type="date" name="ChequeDate" value="<?= date('Y-m-d'); ?>" id="ChequeDate" class="form-control form-control-sm" title="Cheque Date" />
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
                                    <h5 class="header-title text-dark">Payment Statement</h5>
                                </div>
                                <?php if ($this->session->userdata('RoleType')) { ?>
                                <a href="<?= base_url(); ?>Employee/PaymentsStatement" class="btn btn-success btn-xs pull-right" title="Account Statement">
                                    <i class="fa fa-university"></i> Account Statement
                                </a>
                                <?php } else { ?>
                                <div class="pull-right">
                                    <a href="<?= base_url(); ?>Payment/PartialUnpaidInvoices" class="btn btn-primary btn-xs" title="Partial & Unpaid Invoices">
                                        <i class="fa fa-table"></i> Partial & Unpaid Invoices
                                    </a>
                                    &nbsp;
                                    <a href="<?= base_url(); ?>payment/statement" class="btn btn-success btn-xs" title="Account Statement">
                                        <i class="fa fa-university"></i> Account Statement
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <form id="searchForm" method="POST" onsubmit="onPaymentSearchHandler(event);">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="date" name="From" class="form-control form-control-sm" id="From" title="From" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="date" name="To" class="form-control form-control-sm" id="To" title="To" />
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
                                                <a href="javascript:void(0);" class="btn btn-success btn-sm" title="Print Statement" onclick="printStatement();">
                                                    <i class="fa fa-print"></i>
                                                </a>
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

<div class="modal fade" id="paymentRemark" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<!-- Cheque Info -->
<div class="modal fade" id="ChequeInfo" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>
<!-- Cheque Info end -->

<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        
        <?php if (isset($InvoiceId) && !empty($InvoiceId)) { ?>
        getPaymentInfomationByInvoiceId();            
        <?php } elseif (isset($CustomerId) && !empty($CustomerId)) { ?>
        getPaymentInfomationByCustomerId();
        <?php } else { ?>
        var sundryCreditorsAccountGroupId = $("#account_group").val();
        getParties(sundryCreditorsAccountGroupId, null);
        <?php } ?>
            
        var chequeDate = $('#ChequeDate').val();
        $('#payment_mode').change(function() {
                var selectedValue = $('#payment_mode option:selected').text();
                if (selectedValue === 'Cheque') {
                    $('#ChequeDate').attr('required', 'required').val(chequeDate);
                    $('.Cheque').removeClass('d-none');
                } else {
                    $('#ChequeDate').removeAttr('required').val('');
                    $('.Cheque').addClass('d-none');
                }
        });
    });
    
    const getPaymentInfomationByInvoiceId = () => {
        var InvoiceId = "<?= isset($InvoiceId) ? $InvoiceId : ''; ?>";
        if (InvoiceId != null && InvoiceId != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>payment/getPaymentInfomationByInvoiceId",
                data    :   {InvoiceId: InvoiceId},
                success :   function (Response) {
                                var JsonResponse = JSON.parse(Response);
                                if (JsonResponse.status == true) {
                                    var InvoiceInfo = JsonResponse.InvoiceData;
                                    var partyInfo = JsonResponse.partyInfo;
                                    if (InvoiceInfo.type == "Sale" || InvoiceInfo.type == "Purchase") {
                                        var Type = "Tax";
                                    } else if (InvoiceInfo.type == "Estimate" || InvoiceInfo.type == "Estimate Purchase") {
                                        var Type = "Estimate Voucher";
                                        $('#estimate').prop('checked', true);
                                    } 
                                    $('#voucher_type option[value="'+ Type +'"]').attr("selected", "selected");
                                    getVoucherNumber(Type);
                                    $('#account_group option[value="'+ partyInfo.group_head +'"]').attr("selected", "selected");
                                    getParties(partyInfo.group_head, partyInfo.id);
                                    window.setTimeout(function() {
                                        getPartyBalance();
                                        getPandingInvoices(InvoiceInfo.customerId);
                                        AccountGroupChangePaymentType();
                                    }, 1000);
                                    getPaymentStatement(InvoiceInfo.customerId);
                                }
                            }
            });
        }
    };
    
    const getPaymentInfomationByCustomerId = () => {
        var CustomerId = "<?= isset($CustomerId) ? $CustomerId : ''; ?>";
        if (CustomerId != null && CustomerId != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>payment/getPaymentInfomationByCustomerId",
                data    :   {CustomerId: CustomerId},
                success :   function (Response) {
                                var JsonResponse = JSON.parse(Response);
                                if (JsonResponse.status == true) {
                                    var InvoiceInfo = JsonResponse.InvoiceData;
                                    var partyInfo = JsonResponse.partyInfo;
                                    if (InvoiceInfo.type == "Sale" || InvoiceInfo.type == "Purchase") {
                                        var Type = "Tax";
                                    } else if (InvoiceInfo.type == "Estimate" || InvoiceInfo.type == "Estimate Purchase") {
                                        var Type = "Estimate Voucher";
                                        $('#estimate').prop('checked', true);
                                    } 
                                    $('#voucher_type option[value="'+ Type +'"]').attr("selected", "selected");
                                    getVoucherNumber(Type);
                                    $('#account_group option[value="'+ partyInfo.group_head +'"]').attr("selected", "selected");
                                    getParties(partyInfo.group_head, partyInfo.id);
                                    window.setTimeout(function() {
                                        getPartyBalance();
                                        getPandingInvoices(InvoiceInfo.customerId);
                                        AccountGroupChangePaymentType();                                        
                                    }, 1000);
                                    getPaymentStatement(InvoiceInfo.customerId);
                                }
                            }
            });
        }
    };
    
    const calculatePaidAmount = () => {
        var receivedPaidAmount = $("#receivedPaidAmount").val();
        var cashDiscountAmount = $("#cashDiscountAmount").val();
        var TotalPaidAmount = Number(receivedPaidAmount) + Number(cashDiscountAmount);
        $("#paid_amount").val(parseFloat(TotalPaidAmount).toFixed(2));
    };
    
    const getVoucherNumber = (Type) => {
        if (Type != null && Type != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>payment/getVoucherNumber",
                data    :   {VoucherType: Type},
                success :   function (Response) {
                                var JsonResponse = JSON.parse(Response);
                                if (JsonResponse.status == true) {
                                    $("#voucher_number").val(JsonResponse.data);
                                }
                            }
            });
        }
    };
    
    $('#invoices').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    $("#estimate").click(function(){
        getParties($("#account_group").val());
    });

    const getParties = (accountId, customerId=null) => {
        if (accountId != "") {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>party/getPartiesByAccountGroup",
                data    :   {account_id: accountId, customerId: customerId},
                success :   function (Response) {
                                if (Response) {
                                    $("#party_id").html(Response);
                                }
                            }
            });
        }
    };

    function getPartyBalance() {
        let partyId = $("#party_id").val();
        if (partyId != '') {
            let Estimate = $('#estimate').is(':checked') ? "true" : "";
            $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url(); ?>payment/getPartyBalance/" + partyId,
                    data    :   {estimate: Estimate},
                    success :   function(customerList) {
                                    var customerListJson = JSON.parse(customerList);
                                    if (customerListJson.status == true) {
                                        $('#balance_amount').val(parseFloat(customerListJson.partyBalance).toFixed(2));
                                        $("#invoice_amount").val(0);
                                        $("#From").val(customerListJson.From);
                                        $("#To").val(customerListJson.To);
                                    } else {
                                        toastr.danger("Amount Not Found");
                                        $('#balance_amount').val(0);
                                        $("#From").val("");
                                        $("#To").val("");
                                    }
                                }
            });
        }
    }
    
    const getPandingInvoices = (PartyId) => {
        jQuery.noConflict();
        var AccountGroup = $("#account_group").val();
        if (PartyId != '' && AccountGroup != '') {
            var Estimate = $('#estimate').is(':checked') ? "true" : "";
        
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>payment/getPandingInvoices",
                data    :   {account_group: AccountGroup, party_id: PartyId, estimate: Estimate},
                success :   function (Response) {
                                if (Response) {
                                    $("#invoices").prop("multiple", true);
                                    $("#invoices").html(Response);
                                    $('#invoices').multiselect('rebuild');
                                }
                            }
            });
        }
    };

    const getPaymentStatement = (PartyId) => {
        $(".loader-bg").css("display", "block");
        var AccountGroup = $("#account_group").val();
        if (PartyId != '' && AccountGroup != "") {
            var Estimate = $('#estimate').is(':checked') ? "true" : "";
            
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>payment/getPaymentStatement",
                data    :   {account_group: AccountGroup, party_id: PartyId, estimate: Estimate},
                success :   function (Response) {
                                if (Response) {
                                    $(".table-responsive").html(Response);
                                    $("#allPayments").DataTable({
                                        dom: "Bfrtip",
                                        order: [[10, "asc"], [3, "desc"]],
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
    
    const getInvoiceTotal = () => {
        let AccountGroup = $("#account_group").val();
        let InvoiceData = $("#invoices").val();
        let PartyId = $("#party_id").val();
        let Estimate = $('#estimate').is(':checked') ? "true" : "";
        if (InvoiceData != '') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>payment/getInvoiceTotal",
                data    :   {account_group: AccountGroup, invoice_data: InvoiceData, party_id: PartyId, estimate: Estimate},
                success :   function (Response) {
                                if (Response) {
                                    $("#invoice_amount").val(Response);
                                } else {
                                    $("#invoice_amount").val(0);
                                }
                            }
            });
        } else {
            $("#invoice_amount").val(0);
        }
    };
    
    const onPaymentEntryCheckHandler = () => {
        var PaymentDate = $("#date").val();
        var PartyId = $("#party_id").val();
        var receivedPaidAmount = $("#receivedPaidAmount").val();
        var Amount = $("#paid_amount").val();

        if (PaymentDate && PartyId && receivedPaidAmount && Amount) {
            return $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>reports/checkPaymentEntry",
                data    :   {PaymentDate: PaymentDate, PartyId: PartyId, receivedPaidAmount: receivedPaidAmount, Amount: Amount},
                async   :   false,
                success :   function(Response) {
                                if (JSON.parse(Response).Status == true) {
                                    JSON.parse(Response);
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
    
    const onPaymentFormSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var financialYear = $("#financial_year").val();
        var voucherType = $("#voucher_type").val();
        var voucherNumber = $("#voucher_number").val();
        var date = $("#date").val();
        var accountGroup = $("#account_group").val();
        var partyId = $("#party_id").val();
        var balanceAmount = $("#balance_amount").val();
        var invoices = $("#invoices").val();
        var invoiceAmount = $("#invoice_amount").val();
        var receivedPaidAmount = $("#receivedPaidAmount").val();
        var paidAmount = $("#paid_amount").val();
        var paymentType = $("#payment_type").val();
        var paymentMode = $("#payment_mode").val();
        
        if (financialYear && voucherType && voucherNumber && date && accountGroup && partyId && balanceAmount && invoices && invoiceAmount && receivedPaidAmount && paidAmount && paymentType && paymentMode) {
            if (JSON.parse(onPaymentEntryCheckHandler()).Data == 'Unique') {
                $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url(); ?>payment/save",
                    data    :   $("#dataForm").serialize(),
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
                                            $("#balance_amount").val("");
                                            $("#invoice_amount").val("");
                                            $("#receivedPaidAmount").val("");
                                            $("#cashDiscountAmount").val("");
                                            $("#paid_amount").val("");
                                            $("#payment_mode").val("");
                                            $("#ChequeDate").val("");
                                            $("#remark").val("");

                                            getPartyBalance();
                                            getPandingInvoices($("#party_id").val());
                                            getPaymentStatement($("#party_id").val());
                                            getVoucherNumber($("#voucher_type").val());
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
                    title: JSON.parse(onPaymentEntryCheckHandler()).Title,
                    text: JSON.parse(onPaymentEntryCheckHandler()).Text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type    :   "POST",
                            url     :   "<?= base_url(); ?>payment/save",
                            data    :   $("#dataForm").serialize(),
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
                                                    $("#balance_amount").val("");
                                                    $("#invoice_amount").val("");
                                                    $("#receivedPaidAmount").val("");
                                                    $("#cashDiscountAmount").val("");
                                                    $("#paid_amount").val("");
                                                    $("#payment_mode").val("");
                                                    $("#ChequeDate").val("");
                                                    $("#remark").val("");

                                                    getPartyBalance();
                                                    getPandingInvoices($("#party_id").val());
                                                    getPaymentStatement($("#party_id").val());
                                                    getVoucherNumber($("#voucher_type").val());
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
    
    const onGetPaymentRemarkHandler = (Id) => {
        jQuery.noConflict();
        $("#paymentRemark .modal-dialog .modal-content").load("<?= base_url(); ?>payment/getPaymentRemark/" + Id);
        $("#paymentRemark").modal("show");
    };
    
    const onPaymentDeleteHandler = (Id) => {
        if (Id == 0) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Payment id missing",
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        
        <?php if ($this->session->userdata('RoleType')) { ?>
        var url = "<?= base_url(); ?>employee/paymentDeleteAjax/" + Id;
        <?php } else { ?>
        var url = "<?= base_url(); ?>payment/paymentDeleteAjax/" + Id;
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
                onPaymentDelete(url);
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
    
    function onPaymentDelete(url) {
        $.ajax({
            type    :   "GET",
            url     :   url,
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
                                    $("#balance_amount").val("");
                                    $("#invoice_amount").val("");
                                    $("#receivedPaidAmount").val("");
                                    $("#cashDiscountAmount").val("");
                                    $("#paid_amount").val("");
                                    $("#payment_mode").val("");
                                    $("#ChequeDate").val("");
                                    $("#remark").val("");

                                    getPartyBalance();
                                    getPandingInvoices($("#party_id").val());
                                    getPaymentStatement($("#party_id").val());
                                    getVoucherNumber($("#voucher_type").val());
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
    
    const onPaymentSearchHandler = (e) => {
        e.preventDefault();
        $(".loader-bg").css("display", "block");
        var accountGroup = $("#account_group").val();
        var partyId = $("#party_id").val();
        var fromDate = $("#From").val();
        var toDate = $("#To").val();
        var Estimate = $('#estimate').is(':checked') ? "true" : "";
        
        if (accountGroup && partyId && fromDate && toDate) {    
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>payment/getPaymentStatement",
                data    :   {account_group: accountGroup, party_id: partyId, estimate: Estimate, fromDate: fromDate, toDate: toDate},
                success :   function (Response) {
                                if (Response) {
                                    $(".table-responsive").html(Response);
                                    $("#allPayments").DataTable({
                                        dom: "Bfrtip",
                                        order: [[10, "asc"], [3, "desc"]],
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
    
    const printStatement = () => {
        var accountGroup = $("#account_group").val();
        var party = $("#party_id").val();
        var from = $("#From").val();
        var to = $("#To").val();
        
        if (accountGroup && party && from && to) {
            <?php if ($this->session->userdata('RoleType')) { ?>
            window.open("<?= base_url(); ?>employee/printPaymentStatement?accountGroupId="+accountGroup+"&ledgerId="+party+"&fromDate="+from+"&toDate="+to);
            <?php } else { ?>
            window.open("<?= base_url(); ?>payment/printStatement?accountGroupId="+accountGroup+"&ledgerId="+party+"&fromDate="+from+"&toDate="+to);
            <?php } ?>
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
    
    const onChequeInfo = (invoiceId) => {
        $("#ChequeInfo .modal-dialog .modal-content").load("<?=base_url(); ?>payment/chequeInfo/" + invoiceId);
        $("#ChequeInfo").modal("show");
    };
</script>