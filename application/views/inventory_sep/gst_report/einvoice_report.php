<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    @media (min-width: 1024px) {
        .stickyColumn {
            position: sticky;
            background: #ffffff !important;
        }
        th.stickyColumn:nth-child(3), td.stickyColumn:nth-child(3) {
            left: 30px;
        }
        th.stickyColumn:nth-child(4), td.stickyColumn:nth-child(4) {
            left: 165px;
        }
        th.stickyColumn:nth-child(5), td.stickyColumn:nth-child(5) {
            left: 270px;
        }
    }
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
                                <div class="col-md-2">
                                    <h2 class="card-title">E-Invoice Report</h2>
                                </div>
                                <div class="col-md-6 text-center" style="display:none;">
                                    <button type="button" class="btn btn-primary btn-xs" title="Previous Month" id="pre" onclick="return PreMonth();">
                                        <i class="fa fa-calendar-minus-o text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs" title="Current Month" id="currentMont" onclick="return curent();">
                                        <i class="fa fa-calendar-check-o text-white"></i>
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
                                    <button type="button" class="btn btn-danger btn-xs" title="Print Invoices" data-toggle="modal" data-target="#multipleInvoicePrint">
                                        <i class="fa fa-print text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-xs" title="PDF File" id="downloadPDF">
                                        <i class="fa fa-file-pdf-o text-white"></i>
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <div class="pull-right" style="display:none;">
                                        <a href="<?= base_url(); ?>sale" class="btn btn-info btn-xs" title="Back to Sale Create">
                                            <i class="fa fa-arrow-circle-left"></i> Back
                                        </a>
                                        <a href="<?= base_url(); ?>sale_recycle" class="btn btn-primary btn-xs" title="Sale Cancelled Invoices">
                                            <i class="fa fa-recycle"></i> Cancelled
                                        </a>
                                        <a href="<?= base_url(); ?>Sale/SalesInovices" class="btn btn-danger btn-xs" title="All Invoices" target="_blank">
                                            <i class="fa fa-th-list"></i> All Invoices
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-1 col-sm-1" style="display:none;">
                                        <label for="estimate">Estimate</label>
                                        <br />
                                        <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                        Estimate
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2" style="display:none;">
                                        <label>Sale Register</label>
                                        <select name="sale_register" id="sale_register" onkeydown="firstRow(event);" required="" class="form-control form-control-sm">
                                            <option value="general" <?= $this->input->get('sale_register') == 'general' ? 'selected' : '' ?>>General Register</option>
                                            <option value="columnar" <?= $this->input->get('sale_register') == 'columnar' ? 'selected' : '' ?>>Columnar Register</option>
                                            <option value="hsn" <?= $this->input->get('sale_register') == 'hsn' ? 'selected' : '' ?>>HSN Wise Report</option>
                                            <option value="detailview" <?= $this->input->get('sale_register') == 'detailview' ? 'selected' : '' ?>>Detail View</option>
                                            <option value="updateview" <?= $this->input->get('sale_register') == 'updateview' ? 'selected' : '' ?>>Updated Report</option>
                                            <option value="description" <?= $this->input->get('sale_register') == 'description' ? 'selected' : '' ?>>Description Report</option>
                                        </select>
                                    </div>
                                    <?php
                                        $selectedParty = $this->input->get('party');
                                        $selectedParty = is_array($selectedParty) ? $selectedParty : [];
                                        $selectedSalesman = $this->input->get('Salesman');
                                        $selectedSalesman = is_array($selectedSalesman) ? $selectedSalesman : [];
                                    ?>
                                    <div class="form-group col-md-3 col-sm-3">
                                        <label for="party">
                                            Parties 
                                            <a href="javascript:void(0);" id="partyRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </label>
                                        <select name="party[]" id="party" class="form-control form-control-sm" title="Select Party Name" multiple="">
                                            <option <?= in_array('All', $selectedParty, true) ? "selected" : ""; ?> value="All">All Party</option>
                                            <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                            <option <?= in_array($party->id, $selectedParty) ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?>, <?= $party->contact; ?>, <?= $party->area; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2" style="display:none;">
                                        <label for="Salesman">
                                            Salesman 
                                            <a href="javascript:void(0);" id="SalesmanRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </label>
                                        <select name="Salesman[]" id="Salesman" class="form-control form-control-sm" title="Select Salesman Name" multiple="">
                                            <option <?= in_array('All', $selectedSalesman, true) ? "selected" : ""; ?> value="All">All Salesman</option>
                                            <?php if (!empty($Salesmans)) { foreach ($Salesmans as $key => $Salesman) { ?>
                                            <option <?= in_array($Salesman->Id, $selectedSalesman) ? "selected" : ""; ?> value="<?= $Salesman->Id; ?>"><?= $Salesman->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" name="fromDate" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" onkeydown="firstRow(event);" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="toDate">To Date</label>
                                        <input type="date" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" onkeydown="firstRow(event);" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-2" style="display:none;">
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
                                    <div class="form-group col-md-1 col-sm-1" style="display:none;">
                                        <label for="payment">Payment</label>
                                        <select name="payment" id="payment" class="form-control form-control-sm" title="Payment Status">
                                            <option value="All" <?= $this->input->get('payment') == "All" ? "selected" : ""; ?>>All</option>
                                            <option value="Paid" <?= $this->input->get('payment') == "Paid" ? "selected" : ""; ?>>Paid</option>
                                            <option value="Unpaid" <?= $this->input->get('payment') == "Unpaid" ? "selected" : ""; ?>>Unpaid</option>
                                            <option value="Partial" <?= $this->input->get('payment') == "Partial" ? "selected" : ""; ?>>Partial</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <div class="form-group" style="margin-top: 31px;">
                                            <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                                <i class="fa fa-search text-white"></i> Search
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" title="Refresh" id="resetbtn">
                                                <i class="fa fa-refresh text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="divas" class="table-responsive">
                                <table class="table table-bordered dataTable" id="example">
                                    <thead>
                                        <tr role="row">
                                            <th rowspan="1" colspan="1" aria-label="">
                                                <input type="checkbox" id="sale-selectall">
                                                <label for="sale-selectall"></label>
                                            </th>
                                            <th>Sr.</th>
                                            <th class="stickyColumn">Invoice Number</th>
                                            <th class="stickyColumn">Invoice Date</th>
                                            <th class="stickyColumn">Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th> 
                                            <th>Taxable Amount</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Round Off</th> 
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php if ($this->input->get('estimate') == '') { ?>
                                    <tbody id="tbody">
                                        <form class="form_select" method="POST" id="form_select">
                                            <?php
                                                $TotalTaxableAmount = 0;
                                                $TotalTax = 0;
                                                $TotalAmount = 0;
                                                $RoundOff = 0;
                                                $PaidAmount = 0;
                                                $BalanceAmount = 0;
                                                if ($result) {
                                                    foreach ($result as $key => $row) {
                                                        $row->EinvoicePdf = $this->user_model->getRow('e_invoice_details', ['Irn' => $row->Irn])->EinvoicePdf;  
                                                        if ($row->status == '1') {
                                                            $TextDecoration = 'unset';
                                                            $TotalTaxableAmount += $row->taxableAmount;
                                                            $TotalTax += $row->totalTax;
                                                            $TotalAmount += $row->total;
                                                            $RoundOff += $row->roundOff;
                                                            $PaidAmount += $row->paid_amount;
                                                            $BalanceAmount += ($row->total - $row->paid_amount);
                                                        } else {
                                                            $TextDecoration = 'line-through';
                                                        }
                                                        
                                                       ?>
                                            <tr>
                                                <td class="cheking text-center">
                                                    <input class="checkdata" type="checkbox" name="product_id[]" data-type="<?= $row->type; ?>" value="<?php echo $row->id; ?>" />
                                                </td>
                                                <td><?= $key + 1; ?></td>
                                                <td class="stickyColumn"><?= $row->invoiceNumber; ?></td>
                                                <td class="stickyColumn"><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                                                <td class="stickyColumn"><?= get_title('parties', ['id' => $row->customerId], 'name') ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'contact'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                          
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->totalTax); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->total); ?></td>
                                                <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->roundOff); ?></td>
                                               
                                              
                                                <td>
                                                    <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $row->id; ?>);" class="text-success" title="Delivery">
                                                        <i class="fa fa-bus"></i>
                                                    </a>
                                                   
                                                    &nbsp;
                                                    <a href="<?= base_url('sale/viewsale/') . $row->id ?>" class="text-success" target="_blank" title="View">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a> 
                                                    <?php if ($row->status == '1') { if ($row->eInvoiceCancelStatus==0) { ?>
                                                    &nbsp;
                                                    <a href="javascript:void(0)" class="text-danger" onclick="cancel_e_invoiceConfirm('<?= $row->Irn ?>',<?= $row->id ?>)" title="Cancelled">
                                                        <i class="fa fa-trash"></i>
                                                    </a>   
                                                    <?php } } ?>
                                                    &nbsp;
                                                    <a href="<?= base_url('sale/printSale/') . $row->id ?>" class="text-success" target="_blank" title="Print">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= $row->EinvoicePdf ?>" class="text-primary" title="Download">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>credit_note/Create/<?= $row->id; ?>" class="text-fuchsia" target="_blank" title="Credit Note">
                                                        <i class="fa fa-comments"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" class="text-dark" title="Send Mail" onclick="sendmail(<?= $row->id; ?>);">
                                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="https://api.whatsapp.com/send?phone=+91<?= get_title('parties', ['id' => $row->customerId], 'contact'); ?>&text=<?= base_url(); ?>printInvoice/<?= $row->id; ?>" class="text-info" title="Send on WhatsApp" target="_blank">
                                                        <img src="<?= base_url(); ?>assets/images/whatsapp.png" alt="whatsapp" style="width: 20px;" />
                                                    </a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" class="text-navy" onclick="onDeliveryChatHandler(<?= $row->id; ?>);" title="Delivery Chat">
                                                        <i class="fa fa-comment"></i>
                                                    </a>
                                                    <?php 
                                                        $compWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                                        if ($this->session->userdata('BranchRole')) {
                                                            $compWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                        }
                                                        if (get_title('comp_profile', $compWhere, 'formE') == 'Y') {
                                                    ?>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>Sale/formE/<?= $row->id; ?>" title="From-E" target="_blank">
                                                        <i class="fa fa-paperclip text-purple"></i>
                                                    </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </form>
                                    </tbody>
                                    <tfoot>
                                        <th colspan="7">Total</th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTaxableAmount); ?></th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTax); ?></th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalAmount); ?></th>
                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($RoundOff); ?></th> 
                                        <th colspan="4"></th>
                                    </tfoot>
                                    <?php } ?>
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

<!-- Cheque Info -->
<div class="modal fade" id="ChequeInfo" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>
<!-- Cheque Info end -->

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="DeliveryChat" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<div class="modal fade" id="multipleInvoicePrint" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Print Multiple Invoices</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="form-group">
                            <label for="NumberOfInvoice">Number Of Invoice Copies <span class="text-danger">*</span></label>
                            <input type="number" name="NumberOfInvoice" value="<?= set_value('NumberOfInvoice'); ?>" id="NumberOfInvoice" class="form-control form-control-sm" placeholder="Number Of Invoice Copies" required="" />
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="form-group">
                            <label for="SaleInvoice">Invoice Type <span class="text-danger">*</span></label>
                            <input type="checkbox" name="SaleInvoice" value="<?= set_value('SaleInvoice', 'Sale'); ?>" id="SaleInvoice" title="Sale Invoice" required="" />
                            Sale
                            <input type="checkbox" name="EstimateInvoice" value="<?= set_value('EstimateInvoice', 'Estimate'); ?>" id="EstimateInvoice" title="Estimate Type" required="" />
                            Estimate
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" onclick="onPrintMultipleInvoices();">
                    <i class="fa fa-print text-white"></i> Print
                </button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    Close
                </button>
            </div>
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
    
    $('#Salesman').multiselect({
        buttonWidth: 'auto',
        enableFiltering: true,
        includeFilterClearBtn: true,
        enableCaseInsensitiveFiltering: true
    });
    
    $("#partyRefresh").click(function () {
        $("#party option:selected").each(function() {
            $(this).prop("selected", false);
        });
        $("#party").multiselect("refresh");
    });
    
    $("#SalesmanRefresh").click(function () {
        $("#Salesman option:selected").each(function() {
            $(this).prop("selected", false);
        });
        $("#Salesman").multiselect("refresh");
    });
    
    $("#sale-selectall").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    const onPrintMultipleInvoices = () => {
        var selected = new Array();
        var type = new Array();
        var NumberOfInvoice = $("#NumberOfInvoice").val();
        var SaleInvoice = $("#SaleInvoice:checked").val();
        var EstimateInvoice = $("#EstimateInvoice:checked").val();
        
        $(".cheking input[type=checkbox]:checked").each(function() {
            selected.push(this.value);
            type.push($(this).data('type'));
        });
        
        if (selected.length > 0) {
            if (!confirm('Are you sure to print all invoices?')) return false;
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Sale/salePrintMultipleInvoice",
                data    :   {id: selected, type: type, NumberOfInvoice: NumberOfInvoice, SaleInvoice: SaleInvoice, EstimateInvoice: EstimateInvoice},
                dataType:   "JSON",
                success :   function(Response) {
                                if (Response.status == true) {
                                    toastr.success("", Response.data);
                                    window.open("<?= base_url(); ?>Sale/" + Response.url);
                                } else {
                                    toastr.error("", Response.data);
                                }
                            },
                error   :   function() {
                                alert("Something is Wrong");
                            }
            });
        }
    };

    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>sale/exportsale?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "sale_report.xls");
        $a[0].click();
        $a.remove();
    });
    
    $('#downloadPDF').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>sale/pdfsale?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    });

    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>Einvoice/eInvoiceReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Einvoice/eInvoiceReport";
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
        window.location.href = "<?= base_url(); ?>sale/sale_report?" + form;
    }

    function curent() {
        var today = new Date();
        var dd = today.getDate();
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
        document.getElementById('fromDate').value = today;
        document.getElementById('toDate').value = monthlastdate;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>sale/sale_report?" + form;
    }

    function nextmonth() {
        var fromDate = document.getElementById('fromDate').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $("#reportForm").serialize();        
        window.location.href = "<?= base_url(); ?>sale/sale_report?" + form;
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
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_SaleReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_SaleReport";
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

    function sendmail(id) {
        $("#common_modal .modal-dialog .modal-body").load(baseurl + "Sale/sendmail/" + id);
        $("#common_modal_label").html("Send Mail");
        $("#common_modal").modal("show");
    }

    const OnChequeInfo = (SaleId) => {
        $("#ChequeInfo .modal-dialog .modal-content").load("<?=base_url(); ?>Sale/ChequeInfo/" + SaleId);
        $("#ChequeInfo").modal("show");
    };
    
    const onDeliveryInfoHandler = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/DeliveryInfo/" + Id);
        $('#Info').modal('show');
    };
        
    const onDeliveryChatHandler = (Id) => {
        jQuery.noConflict();
        $("#DeliveryChat .modal-dialog .modal-content").load("<?= base_url(); ?>Sale/DeliveryChatHandler/" + Id);
        $("#DeliveryChat").modal("show");
        getSaleDeliveryChat(Id);
    };
    
    const onChatFormSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var Chat = $("#Chat").val();
        
        if (Chat) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Sale/DeliveryChatSave",
                data    :   $("#ChatForm").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    toastr.success(ResponseData.Message);
                                    window.setTimeout(function() {
                                        $("#Chat").val("");
                                        
                                        getSaleDeliveryChat($("#InvoiceId").val());
                                        $("#submitButton").prop("disabled", false);
                                    }, 1000);
                                } else {
                                    toastr.error(ResponseData.Message);
                                    $("#submitButton").prop("disabled", false);
                                }
                            }
            });
        } else {
            toastr.error("Please enter required fields.");
            $("#submitButton").prop("disabled", false);
        }
    };
    
    const getSaleDeliveryChat = (InvoiceId) => {
        if (InvoiceId != '') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Sale/getSaleDeliveryChat",
                data    :   {InvoiceId: InvoiceId},
                success :   function (Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status == true) {
                                    $(".card-comments").html(ResponseData.Chat);
                                } else {
                                    $(".card-comments").html('<span class="text-danger text-center">No Record found.</span>');
                                }
                            }
            });
        }
    };
    
    const channel = new BroadcastChannel('reload-channel');
    channel.onmessage = (event) => {
        if (event.data === 'reload') {
            location.reload();
        }
    };
</script>