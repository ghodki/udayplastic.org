<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .card {
        margin-bottom: 0rem;
    }
    .card-footer p {
        margin-bottom: 0rem;
    }
    .card-header, .card-body, .card-footer {
        padding: 5px;
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
        font-size: 14px;
    }
    table.dataTable tfoot th {
        padding: 10px 5px 6px 5px;
    }
    .multiselect-container {
        transform: translate3d(0px, 32px, 0px) !important;
    }
    .multiselect-container .multiselect-option {
        padding: 0px 5px 0px 5px;
    }
    .dropdown-menu.show {
        height: auto;
        max-height: 200px;
        overflow-y: scroll;
    }
    .dropdown-menu {
        min-width: 24.6rem;
    }
    th {
        padding: 5px !important;
    }
    td {
        padding: 5px !important;
        font-size: 14px;
        font-weight: 500;
        vertical-align: middle !important;
    }
    table.dataTable {
        border-collapse: collapse;
    }
    td label {
        margin-bottom: 0px;
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
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                    <!-- Index Start -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <h2 class="card-title">Estimate Purchase Report</h2>
                                </div>
                                <div class="col-md-6 text-center">
                                    <button type="button" class="btn btn-primary btn-xs" title="Previous Month" id="pre" onclick="return PreMonth();">
                                        <i class="fa fa-calendar-minus-o text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs" title="Current Month" id="currentMont" onclick="return curent();">
                                        <i class="fa fa-calendar-check-o"></i>
                                    </button>
                                    <button type="button" class="btn btn-dark btn-xs" title="Next Month" id="nextMonth" onclick="return nextmonth();">
                                        <i class="fa fa-calendar-plus-o text-white"></i>
                                    </button>                                    
                                    <button type="button" class="btn btn-default btn-xs" title="JSON File" id="json">
                                        <i class="fa fa-file-code"></i>
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-xs" title="Excel File" id="excelExport" >
                                        <i class="fa fa-file-excel-o text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs" title="Print File" id="printmultiple">
                                        <i class="fa fa-print text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-xs" title="PDF File" id="downloadPDF">
                                        <i class="fa fa-file-pdf-o text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-xs" title="Refresh" id="resetbtn">
                                        <i class="fa fa-refresh text-white"></i>
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <div class="pull-right">
                                        <a href="<?= base_url(); ?>estimate_purchase" class="btn btn-info btn-xs" title="Back to Estimate Purchase Create">
                                            <i class="fa fa-arrow-circle-left"></i> Back
                                        </a>
                                        <a href="<?= base_url(); ?>Recycle/EstimatePurchase" class="btn btn-primary btn-xs" title="Estimate Purchase Cancelled invoices">
                                            <i class="fa fa-recycle"></i> Cancelled
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label>Sale Register</label>
                                        <select name="sale_register" id="sale_register" onkeydown="firstRow(event);" required="" class="form-control form-control-sm">
                                            <option value="general" <?= $this->input->get('sale_register') == 'general' ? 'selected' : '' ?>>General</option>
                                            <option value="columnar" <?= $this->input->get('sale_register') == 'columnar' ? 'selected' : '' ?>>Columnar</option>
                                            <option value="hsn" <?= $this->input->get('sale_register') == 'hsn' ? 'selected' : '' ?>>HSN Wise</option>
                                            <option value="detail" <?= $this->input->get('sale_register') == 'detail' ? 'selected' : '' ?>>Detail</option>
                                        </select>
                                    </div>
                                    <?php
                                        $selectedParty = $this->input->get('party');
                                        $selectedParty = is_array($selectedParty) ? $selectedParty : [];
                                    ?>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="party">Parties</label>
                                        <select name="party[]" id="party" class="form-control form-control-sm" title="Select Party Name" multiple="">
                                            <option <?= in_array('All', $selectedParty, true) ? "selected" : ""; ?> value="All">All Party</option>
                                            <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                            <option <?= in_array($party->id, $selectedParty) ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="toDate">To Date</label>
                                        <input type="date" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="invoiceNumber">Invoice Number</label>
                                        <input type="text" name="invoiceNumber" id="invoiceNumber" value="<?= $this->input->get('invoiceNumber'); ?>" class="form-control form-control-sm" placeholder="Invoice Number" />
                                    </div>
                                    <div class="form-group col-md-1 col-sm-1">
                                        <label for="invoiceStatus">Status</label>
                                        <select name="invoiceStatus" id="invoiceStatus" class="form-control form-control-sm" title="Invoice Status">
                                            <option value="All" <?= $this->input->get('invoiceStatus') == "All" ? "selected" : ""; ?>>All</option>
                                            <option value="1" <?= $this->input->get('invoiceStatus') == "1" ? "selected" : ""; ?>>Active</option>
                                            <option value="0" <?= $this->input->get('invoiceStatus') == "0" ? "selected" : ""; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-1 col-sm-1" style="margin-top: 28px;">
                                        <button type="submit" class="btn btn-primary btn-xs" onClick="return search();" onkeydown="firstRow(event);">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="divas" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="example">
                                    <thead>
                                        <tr role="row">
                                            <th rowspan="1" colspan="1" aria-label="">
                                                <input type="checkbox" id="sale-selectall">
                                                <label for="sale-selectall"></label>
                                            </th>
                                            <th>Sr.</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Payment Mode</th> 
                                            <th>Taxable Amount</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Round Off</th>
                                            <th>Payment</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <form class="form_select" method="POST" id="form_select">
                                            <?php
                                                $TotalTaxableAmount = 0;
                                                $TotalTax = 0;
                                                $TotalAmount = 0;
                                                $RoundOff = 0;
                                                $PaidAmount = 0;
                                                $BalanceAmount = 0;
                                                if (!empty($result)) {
                                                    foreach ($result as $key => $row) {
                                                        if ($row->status == '1') {
                                                            $TextDecoration = 'unset';
                                                            $TotalTaxableAmount += (float) ($row->taxableAmount ?? 0);
                                                            $TotalTax += (float) ($row->totalTax ?? 0);
                                                            $TotalAmount += (float) ($row->total ?? 0);
                                                            $RoundOff += (float) ($row->roundOff ?? 0);
                                                            $PaidAmount += (float) ($row->paid_amount ?? 0);
                                                            $BalanceAmount += ((float) ($row->total ?? 0) - (float) ($row->paid_amount ?? 0));
                                                        } else {
                                                            $TextDecoration = 'line-through';
                                                        }
                                            ?>
                                            <tr id="trp<?= $key; ?>" tabindex="<?= $key + 5; ?>">
                                                <td class="cheking text-center">
                                                    <input class="checkdata" type="checkbox" name="product_id[]" value="<?php echo $row->id; ?>" />
                                                </td>
                                                <td><?= $key + 1; ?></td>
                                                <td><?= $row->invoiceNumber; ?></td>
                                                <td><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'contact'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                                <td>
                                                    <?php
                                                        if ($row->voucherType == 1) {
                                                            $payment_mode = "Cash";
                                                        } elseif ($row->voucherType == 2) {
                                                            $payment_mode = "Credit";
                                                        } else {
                                                            $payment_mode = $row->voucherType;
                                                        }
                                                        echo $payment_mode;
                                                    ?>
                                                </td> 
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount ?? 0); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->totalTax ?? 0); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->total ?? 0); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->roundOff ?? 0); ?></td>
                                                <td>
                                                    <?php if ((float) ($row->paid_amount ?? 0) == (float) ($row->total ?? 0) && (float) ($row->total ?? 0) > 0) { ?>
                                                    <label class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> Paid</label>
                                                    <?php } elseif ($row->paid_amount == NULL || $row->paid_amount == 0) { ?>
                                                    <a href="<?= base_url(); ?>Payment/index/<?= $row->id; ?>" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-exclamation-triangle"></i> Unpaid
                                                    </a>
                                                    <?php } else { ?>
                                                    <a href="<?= base_url(); ?>Payment/index/<?= $row->id; ?>" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-exclamation-circle"></i> Partial
                                                    </a>
                                                    <?php } ?>
                                                </td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->paid_amount ?? 0); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat(((float) ($row->total ?? 0) - (float) ($row->paid_amount ?? 0))); ?></td>
                                                <td>
                                                    <a href="<?= base_url('estimate_purchase/view/') . $row->id ?>" class="text-success" target="_blank" title="View">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('estimate_purchase/edit/') . $row->id ?>" class="text-info" target="_blank" title="Edit">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    <?php if ($row->status == '1') { if ($row->paid_amount == NULL || $row->paid_amount == '0') { ?>
                                                    &nbsp;
                                                    <a href="<?= base_url('estimate_purchase/recycle/') . $row->id; ?>" class="text-danger" onclick="return confirm('Are you sure to want to delete this invoice');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <?php } } ?>
                                                    &nbsp;
                                                    <a href="<?= base_url('estimate_purchase/print/') . $row->id ?>" class="text-success" target="_blank" title="Print">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('estimate_purchase/download/') . $row->id ?>" class="text-primary" target="_blank" title="Download">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="#" class="text-dark" title="Send Mail" onclick="sendmail(<?= $row->id; ?>);">
                                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="https://api.whatsapp.com/send?phone=+91<?= get_title('parties', ['id' => $row->customerId], 'contact'); ?>&text=<?= base_url(); ?>printInvoice/<?= $row->id; ?>" class="text-info" title="Send on WhatsApp" target="_blank">
                                                        <img src="<?= base_url(); ?>assets/images/whatsapp.png" alt="whatsapp" style="width: 20px;" />
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </form>
                                    </tbody>
                                    <tfoot>
                                        <th colspan="8">Total</th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTaxableAmount); ?></th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTax); ?></th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalAmount); ?></th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($RoundOff); ?></th>
                                        <th></th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($PaidAmount); ?></th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($BalanceAmount); ?></th>
                                        <th></th>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-center">
                                Note : <span class="text-danger font-italic">** Deleted invoices stored in cancelled invoices **</span>
                            </p>
                        </div>
                    </div>
                    <!-- Index End -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="common_modal" tabindex="-1" role="dialog" aria-labelledby="common_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="common_modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $('#party').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });

    $("#sale-selectall").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    $(function() {
        $("#printmultiple").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                if (!confirm('Are you sure to print all invoices?')) return false;
                console.log(selected, "ids");
                $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url('estimate_purchase/PrintMultiple'); ?>",
                    data    :   {id: selected},
                    dataType:   "JSON",
                    success :   function(data) {
                                    console.log(data);
                                    if (data.status == true) {
                                        toastr.success("", data.data);
                                        window.location.href = "<?= base_url() ?>estimate_purchase/" + data.url;
                                    } else {
                                        toastr.error("", data.data);
                                    }
                                }
                });
            }
        });
    });

    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>estimate_purchase/export_estimate_purchase?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "estimate_purchase_report.xls");
        $a[0].click();
        $a.remove();
    });
    
    $('#downloadPDF').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>estimate_purchase/pdf_estimate_purchase?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    });

    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>estimate_purchase/report?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    };

    function getValueOfSale() {
        var value = document.getElementById('example').value;
        if (value == 'columnar') {
            document.getElementById('example').display = "none";
        } else if (value == 'hsn') {
            document.getElementById('example').display = "none";
        } else {
            document.getElementById('example').display = "show";
        }
    }

    $('#resetbtn').click(function(e) {
        window.location.href = "<?= base_url(); ?>estimate_purchase/report";
    });

    function PreMonth() {
        var fromDate = document.getElementById('fromDate').value;
        console.log(fromDate, 'fromDate');
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>estimate_purchase/report?" + form;
    }

    function curent() {
        var today = new Date();
        var dd = today.getDate();
        console.log(today);
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }
        today = yyyy + '-' + mm + '-' + '01';
        var monthlastdate = yyyy + '-' + mm + '-' + dd;
        console.log(today);
        document.getElementById('fromDate').value = today;
        document.getElementById('toDate').value = monthlastdate;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>estimate_purchase/report?" + form;
    }

    function nextmonth() {
        var fromDate = document.getElementById('fromDate').value;
        console.log(fromDate, 'fromDate');
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>estimate_purchase/report?" + form;
    }
    
    var inputs = document.querySelectorAll("input, select");
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

    $(function() {
        <?php if ($this->session->userdata('BranchRole')) { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_EstimatePurchaseReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_EstimatePurchaseReport";
        <?php } ?>
            
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                            [20, 50, 100, -1], [20, 50, 100, "All"]
            ],
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions:  {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: FileName,
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pageLength',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });
    });

    function deleteRow(url) {
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
                removeRow(url);
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
    }

    function removeRow(url) {
        $.ajax({
            type    :   "GET",
            url     :   url,
            success :   function(success) {
                            var successJson = JSON.parse(success);
                            if (successJson.status == true) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: successJson.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "error",
                                    title: successJson.msg,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }
        });
    }

    $('#sale_register').focus();

    function firstRow(event) {
        var start = document.getElementById('trp0');
        start.focus();
        start.style.backgroundColor = 'blue';
        start.style.color = 'white';
    }

    function sendmail(id) {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "estimate_purchase/sendmail/" + id);
        $("#common_modal_label").html("Send Mail");
        $("#common_modal").modal("show");
    }
</script>