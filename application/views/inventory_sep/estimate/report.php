<?php $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));"); ?>

<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    .stickyColumn {
        position: sticky;
        background: #ffffff !important;
    }
    th.stickyColumn:nth-child(2), td.stickyColumn:nth-child(2) {
        left: 20px;
    }
    th.stickyColumn:nth-child(3), td.stickyColumn:nth-child(3) {
        left: 100px;
    }
    th.stickyColumn:nth-child(4), td.stickyColumn:nth-child(4) {
        left: 200px;
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
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
                                    <h4 class="card-title">Estimate Report</h4>
                                </div>
                                <div class="col-md-8 text-center">
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
                                    <button type="button" class="btn btn-danger btn-xs" title="Print File" data-toggle="modal" data-target="#multipleInvoicePrint">
                                        <i class="fa fa-print text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-success btn-xs" title="PDF File" id="downloadPDF">
                                        <i class="fa fa-file-pdf-o text-white"></i>
                                    </button>
                                    <a href="<?= base_url(); ?>estimate/estimate_report" class="btn btn-info btn-xs" title="Refresh">
                                        <i class="fa fa-refresh text-white"></i>
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <div class="pull-right">
                                        <a href="<?= base_url(); ?>estimate" class="btn btn-info btn-xs" title="Back to Estimate Create">
                                            <i class="fa fa-arrow-circle-left"></i> Back
                                        </a>
                                        <a href="<?= base_url(); ?>Recycle/Estimate" class="btn btn-primary btn-xs" title="Estimate Cancelled invoices">
                                            <i class="fa fa-recycle"></i> Cancelled
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-2 col-sm-2">
                                        <label for="sale_register">Sale Register</label>
                                        <select name="sale_register" id="sale_register" onkeydown="firstRow(event);" required="" class="form-control form-control-sm">
                                            <option value="general" <?= $this->input->get('sale_register') == 'general' ? 'selected' : '' ?>>General Register</option>
                                            <option value="columnar" <?= $this->input->get('sale_register') == 'columnar' ? 'selected' : '' ?>>Columnar Register</option>
                                            <option value="hsn" <?= $this->input->get('sale_register') == 'hsn' ? 'selected' : '' ?>>HSN Wise Report</option>
                                            <option value="detailview" <?= $this->input->get('sale_register') == 'detailview' ? 'selected' : '' ?>>Detail View</option>
                                        </select>
                                    </div>
                                     <div class="form-group col-md-2 col-sm-2">
                                        <label for="party">Parties</label>
                                        <select name="party[]" id="party" class="form-control form-control-sm" title="Select Party Name" multiple="">
                                            <?php $selectedParties = function_exists('input_get_array') ? input_get_array('party') : (array) ($this->input->get('party') ?: []); ?>
                                            <option <?= in_array('All', $selectedParties, true) ? "selected" : ""; ?> value="All">All Party</option>
                                            <?php if (!empty($parties)) { foreach ($parties as $key => $party) { ?>
                                            <option <?= in_array($party->id, $selectedParties) ? "selected" : ""; ?> value="<?= $party->id; ?>"><?= $party->name; ?>, <?= $party->contact; ?></option>
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
                                        <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <!-- General Start -->
                            <div id="divas" class="table-responsive">
                                <table class="table table-bordered dataTable">
                                    <thead>
                                        <tr role="row">
                                            <th rowspan="1" colspan="1" aria-label="">
                                                <input type="checkbox" id="sale-selectall" />
                                                <label for="sale-selectall"></label>
                                            </th>
                                            <th class="stickyColumn">Invoice No</th>
                                            <th class="stickyColumn">Invoice Date</th>
                                            <th class="stickyColumn">Party Name</th>
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
                                            <th>Payment Mode</th>
                                            <th>Discount</th>
                                            <th>Freight Charge</th>
                                            <th>Cash Discount</th>
                                            <th>Salesman</th>
                                            <th>Delivery</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <?php
                                            $TotalTaxableAmount = 0;
                                            $TotalTax = 0;
                                            $TotalAmount = 0;
                                            $PaidAmount = 0;
                                            $BalanceAmount = 0;
                                            if ($result) {
                                                if ($this->session->userdata('RoleType')) {
                                                    $userId = $this->session->userdata('UserId');
                                                    $compId = $this->session->userdata('CompId');
                                                } else {
                                                    $userId = $this->session->userdata('user_id');
                                                    $compId = $this->session->userdata('comp_id');
                                                }
                                                $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
                                                $compBranchId = $this->session->userdata('CompBranchId');
                                                if ($this->session->userdata('BranchRole')) {
                                                    $where['CompanyBranchId'] = $compBranchId;
                                                }
                                                foreach ($result as $key => $row) {
                                                    $where['estimate_no'] = $row->invoiceNumber;
                                                    
                                                    if ($row->status == '1') {
                                                        $TextDecoration = 'unset';
                                                        $TotalTaxableAmount += $row->taxableAmount;
                                                        $TotalTax += $row->totalTax;
                                                        $TotalAmount += $row->total;
                                                        $PaidAmount += $row->paid_amount;
                                                        $BalanceAmount += ($row->total - $row->paid_amount);
                                                    } else {
                                                        $TextDecoration = 'line-through';
                                                    }
                                                    
                                                    $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$row->id\"]') AND transaction_type = 'DR'";
                                                    $query = $this->db->query($sql);
                                                    $Payment = $query->row();
                                                    $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, cashDiscountAmount, ChequeStatus')->where('invoice_id', $row->id)->where('transaction_type', 'DR')->get('payment')->row();
                                                    if (!$PaymentInfo) {
                                                        $PaymentInfo = (object) ['paymentmode' => null, 'cashDiscountAmount' => 0, 'ChequeStatus' => null];
                                                    }
                                        ?>
                                        <tr>
                                            <td class="cheking">
                                                <input class="checkdata" type="checkbox" name="product_id[]" data-type="<?= $row->type; ?>" value="<?= $row->id; ?>" />
                                            </td>
                                            <td class="stickyColumn"><?= $row->invoiceNumber; ?></td>
                                            <td class="stickyColumn"><?= $row->invoiceDate; ?></td>
                                            <td class="stickyColumn"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
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
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount); ?></td>
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->totalTax); ?></td>
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->total); ?></td>
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->roundOff); ?></td>
                                            <td>
                                                <?php if ($row->paid_amount == $row->total) { if (!empty($PaymentInfo->paymentmode) && get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $row->id; ?>);" title="Cheque Info">
                                                    <i class="fa fa-exclamation"></i> UnPaid/PDC
                                                </a>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } } else { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } } elseif ($row->paid_amount == NULL || $row->paid_amount == 0) { ?>
                                                <a href="<?= base_url(); ?>receipt/index/<?= $row->id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle"></i> Unpaid
                                                </a>
                                                <?php } else { if (!empty($PaymentInfo->paymentmode) && get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $row->id; ?>);" title="Cheque Info">
                                                    <i class="fa fa-exclamation"></i> UnPaid/PDC
                                                </a>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <a href="<?= base_url(); ?>receipt/index/<?= $row->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?>receipt/index/<?= $row->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } ?>
                                            </td>
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->paid_amount); ?></td>
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat(((float) $row->total - (float) ($row->paid_amount ?? 0))); ?></td>
                                            <td><?= !empty($PaymentInfo->paymentmode) ? get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') : ''; ?></td>
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($PaymentInfo->cashDiscountAmount ?? 0); ?></td>
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->freight_charge); ?></td>
                                            <td style="text-decoration: <?= $TextDecoration; ?>"><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->cash_discount); ?></td>
                                            <td>
                                                <?php
                                                    if (!empty($row->SalesmanId)) {
                                                        echo get_title('employees', ['Id' => get_title('parties', ['id' => $row->customerId], 'SalesmanId')], 'Name');
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $row->delivery_status == 1 ? "Yes" : "No"; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $row->id; ?>);" class="text-success" title="Delivery">
                                                    <i class="fa fa-bus"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/viewestimate/') . $row->id ?>" class="text-success" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/estimateEdit/') . $row->id ?>" class="text-info" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <?php if ($row->status == '1') { if ($row->paid_amount == NULL || $row->paid_amount == '0') { ?>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/estimate_deleted/') . $row->id ?>" class="text-danger" onclick="return confirm('Are you sure want to delete this invoice.');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php } } ?>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimate/') . $row->id ?>" class="text-success" title="Print" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimates/') . $row->id ?>" class="text-primary" title="Download">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                &nbsp;
                                                <?php if ($this->db->where($where)->get('sales')->num_rows() == 0 && $row->status == '1') { ?>
                                                <a href="<?= base_url('estimate/estimateToSale/') . $row->id ?>" class="text-navy" title="Create Sale Invoice"> 
                                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTaxableAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTax); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalAmount); ?></th>
                                            <th colspan="2"></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($PaidAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($BalanceAmount); ?></th>
                                            <th colspan="6"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- General End -->

                            <!-- Columnar Start -->
                            <div id="Diff" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th class="stickyColumn">Invoice No</th>
                                            <th class="stickyColumn">Invoice Date</th>
                                            <th class="stickyColumn">Customer</th>
                                            <th>GSTIN</th>
                                            <th>POS</th>
                                            <th>Payment Mode</th>
                                            <th>Taxable Amount</th>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->from('estimate_product');
                                                $this->db->where('type', 'Sale');
                                                $this->db->where('tax!=', null);
                                                $this->db->group_by('tax');
                                                $this->db->order_by('tax', 'asc');
                                                $tax = $this->db->get()->result();
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $row) {
                                                        if (is_numeric($row->tax) && !empty($row->tax)) {
                                                            $total_gsttype[$row->tax] = $row->tax;
                                            ?>
                                            <th style="text-align: center;"><span>Taxable Amt @ </span><?= $row->tax; ?>%</th>
                                            <?php } } } ?>
                                            <th style="text-align: center;">SGST</th>
                                            <th style="text-align: center;">CGST</th>
                                            <th style="text-align: center;">IGST</th>
                                            <th style="text-align: center;">CESS</th>
                                            <th>Invoice Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $TotalTaxableAmount1 = 0;
                                            $TotalAmount1 = 0;
                                            if ($result) {
                                                foreach ($result as $key => $row) {
                                                    $TotalTaxableAmount1 += $row->taxableAmount;
                                                    $TotalAmount1 += $row->total;
                                                    
                                                    $overall_igst = 0;
                                                    $overall_cgst = 0;
                                                    $overall_sgst = 0;
                                        ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td class="stickyColumn"><?= $row->invoiceNumber; ?></td>
                                            <td class="stickyColumn"><?= $row->invoiceDate; ?></td>
                                            <td class="stickyColumn"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                            <td><?= get_title('state_list', ['id' => get_title('parties', ['id' => $row->customerId], 'state')], 'state'); ?></td>
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
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount); ?></td>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->select_sum('price');
                                                $this->db->select_sum('sgst');
                                                $this->db->select_sum('cgst');
                                                $this->db->select_sum('igst');
                                                $this->db->select_sum('taxAmount');
                                                $this->db->from('estimate_product');
                                                $this->db->where('invoiceID', $row->id);
                                                $this->db->where('InvoiceNo', $row->invoiceNumber);
                                                $this->db->where('type', 'Sale');
                                                $this->db->group_by('tax');
                                                $tax = $this->db->get()->result();
                                                foreach ($total_gsttype as $total_tax) {
                                                    $this->db->select('tax');
                                                    $this->db->select_sum('price');
                                                    $this->db->select_sum('sgst');
                                                    $this->db->select_sum('cgst');
                                                    $this->db->select_sum('igst');
                                                    $this->db->select_sum('taxAmount');
                                                    $this->db->from('estimate_product');
                                                    $this->db->where('invoiceID', $row->id);
                                                    $this->db->where('InvoiceNo', $row->invoiceNumber);
                                                    $this->db->where('tax', $total_tax);
                                                    $this->db->where('type', 'Sale');
                                                    $this->db->group_by('tax');
                                                    $this->db->order_by('tax', 'asc');
                                                    $tax = $this->db->get()->result();
                                                    if (!empty($tax)) {
                                                        $overall_sgst += $tax[0]->sgst;
                                                        $overall_cgst += $tax[0]->cgst;
                                                        $overall_igst += $tax[0]->igst;
                                            ?>
                                            <td style="text-align: center;"><?= indianMoneyFormat($tax[0]->price); ?></td>
                                            <?php } else { ?>
                                            <td style="text-align: center;">0.00</td>
                                            <?php } } ?>
                                            <td><?= indianMoneyFormat($overall_sgst); ?></td>
                                            <td><?= indianMoneyFormat($overall_cgst); ?></td>
                                            <td><?= indianMoneyFormat($overall_igst); ?></td>
                                            <td><?= 0; ?></td>
                                            <td><?= indianMoneyFormat($row->totalValue); ?></td>
                                            <td>
                                                <a href="<?= base_url('estimate/viewestimate/') . $row->id ?>" class="text-success" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/estimateEdit/') . $row->id ?>" class="text-info" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <?php if ($row->paid_amount == NULL || $row->paid_amount == '0') { ?>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/estimate_deleted/') . $row->id ?>" class="text-danger" onclick="return confirm('Are you sure want to delete this invoice.');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php } ?>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimate/') . $row->id ?>" class="text-success" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimates/') . $row->id ?>" class="text-primary" title="Download">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalTaxableAmount1); ?></th>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->from('estimate_product');
                                                $this->db->where('type', 'Sale');
                                                $this->db->group_by('tax');
                                                $this->db->order_by('tax', 'ASC');
                                                $tax = $this->db->get()->result();
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $row) {
                                                        if (is_numeric($row->tax)) {
                                                            $total_gsttype[$row->tax] = $row->tax;
                                            ?>
                                            <th></th>
                                            <?php } } } ?>
                                            <th colspan="4"></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalAmount1); ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Columnar End -->

                            <!-- HSN Start -->
                            <div id="hsn_report" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>HSN No</th>
                                            <th>Description</th>
                                            <th>Total Qty</th>
                                            <th>TAX</th>
                                            <th>Taxable Amount</th>
                                            <th style="text-align:center;">IGST</th>
                                            <th style="text-align:center;">CGST</th>
                                            <th style="text-align:center;">SGST</th>
                                            <th>Cess Amount</th>
                                            <th>Total Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $this->db->select('*');
                                            $this->db->select('hsn');
                                            $this->db->select('invoiceID');
                                            $this->db->select('productname');
                                            $this->db->select_sum('price');
                                            $this->db->select_sum('netAmount');
                                            $this->db->select_sum('qty');
                                            $this->db->select_sum('sgst');
                                            $this->db->select_sum('cgst');
                                            $this->db->select_sum('igst');
                                            $this->db->select_sum('taxAmount');
                                            $this->db->select_sum('purchase_price');
                                            // taxable amount
                                            $this->db->from('estimate_product');
                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId')); 
                                            }
                                            $this->db->where('invoiceDate >=', $fromDate);
                                            $this->db->where('invoiceDate <=', $toDate);
                                            $this->db->where('type', 'Sale');
                                            $this->db->group_by("hsn", "tax");
                                            $result_of_products = $this->db->get()->result();
                                            if (!empty($result_of_products)) {
                                                foreach ($result_of_products as $key => $tax) {
                                        ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td style="text-align:center;"><?= $tax->hsn; ?></td>
                                            <td style="text-align:center;"><?= $tax->productname; ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->qty); ?></td>
                                            <td style="text-align:center;"><?= $tax->tax; ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->taxAmount); ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->igst); ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->cgst); ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->sgst); ?></td>
                                            <td style="text-align:center;"><?= 0.00; ?></td>
                                            <td style="text-align:center;"><?= indianMoneyFormat($tax->netAmount); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- HSN End -->
                            
                            <!-- Details View Start -->
                            <div id="details_view" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Party Name</th>
                                            <th>City</th>
                                            <th>GST No</th>
                                            <th>Voucher Type</th>
                                            <th>Taxable Amt</th>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->from('estimate_product');
                                                $this->db->where('type', 'Sale');
                                                $this->db->group_by('tax');
                                                $this->db->order_by('tax', 'ASC');
                                                $tax = $this->db->get()->result();
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $row) {
                                                        if (is_numeric($row->tax)) {
                                                            $total_gsttype[$row->tax] = $row->tax;
                                            ?>
                                            <th style="text-align: center;"><span>Taxable Amt @ </span><?= $row->tax; ?>%</th>
                                            <th style="text-align: center;">SGST</th>
                                            <th style="text-align: center;">CGST</th>
                                            <th style="text-align: center;">IGST</th>
                                            <?php } } } ?>
                                            <th>Invoice Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if ($result) {
                                            foreach ($result as $key => $row) { 
                                        ?>
                                        <tr id="trp<?= $key ?>">
                                            <td><?= $key + 1 ?></td>
                                            <td><?= $row->invoiceNumber; ?></td>
                                            <td><?= $row->invoiceDate; ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'name') ?></td>
                                            <td><?= get_title('districts', ['id' => get_title('parties', ['id' => $row->customerId], 'city')], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                            <td><?= "Estimate"; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->taxableAmount); ?></td>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->select_sum('price');
                                                $this->db->select_sum('sgst');
                                                $this->db->select_sum('cgst');
                                                $this->db->select_sum('igst');
                                                $this->db->select_sum('taxAmount');
                                                $this->db->from('estimate_product');
                                                $this->db->where('invoiceID', $row->id);
                                                $this->db->where('InvoiceNo', $row->invoiceNumber);
                                                $this->db->where('type', 'Sale');
                                                $this->db->group_by('tax');
                                                $tax = $this->db->get()->result();
                                                    foreach ($total_gsttype as $total_tax) {
                                                        $this->db->select('tax');
                                                        $this->db->select_sum('price');
                                                        $this->db->select_sum('sgst');
                                                        $this->db->select_sum('cgst');
                                                        $this->db->select_sum('igst');
                                                        $this->db->select_sum('taxAmount');
                                                        $this->db->from('estimate_product');
                                                        $this->db->where('invoiceID', $row->id);
                                                        $this->db->where('InvoiceNo', $row->invoiceNumber);
                                                        $this->db->where('tax', $total_tax);
                                                        $this->db->where('type', 'Sale');
                                                        $this->db->group_by('tax');
                                                        $this->db->order_by('tax', 'asc');
                                                        $tax = $this->db->get()->result();
                                                        if (!empty($tax)) {
                                            ?>
                                            <td style="text-align: center;"><?= indianMoneyFormat($tax[0]->price); ?></td>
                                            <td style="text-align: center;"><?= indianMoneyFormat($tax[0]->sgst); ?></td>
                                            <td style="text-align: center;"><?= indianMoneyFormat($tax[0]->cgst); ?></td>
                                            <td style="text-align: center;"><?= indianMoneyFormat($tax[0]->igst); ?></td>
                                            <?php } else { ?>
                                            <td style="text-align:center;">--</td>
                                            <td style="text-align:center;">--</td>
                                            <td style="text-align:center;">--</td>
                                            <td style="text-align:center;">--</td>
                                            <?php } } ?>
                                            <td><?= indianMoneyFormat($row->totalValue); ?></td>
                                            <td>
                                                <a href="<?= base_url('estimate/viewestimate/') . $row->id ?>" class="text-success" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/estimateEdit/') . $row->id ?>" class="text-info" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <?php if ($row->paid_amount == NULL || $row->paid_amount == '0') { ?>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/estimate_deleted/') . $row->id ?>" class="text-danger" onclick="return confirm('Are you sure want to delete this invoice.');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php } ?>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimate/') . $row->id ?>" class="text-success" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimates/') . $row->id ?>" class="text-primary" title="Download">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Details View End -->
                        </div>
                        <div class="card-footer">
                            <p class="text-center">
                                Note : <span class="text-danger font-italic">** Deleted invoices stored in cancelled invoices **</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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
                            <label for="EstimateInvoice">Invoice Type <span class="text-danger">*</span></label>
                            <input type="checkbox" name="EstimateInvoice" value="<?= set_value('EstimateInvoice', 'Estimate'); ?>" id="EstimateInvoice" checked="" title="Estimate Type" required="" />
                            Estimate
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" onclick="onPrintMultipleInvoices();">
                    <i class="fa fa-print text-white"></i> Print
                </button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
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

    $("#sale-selectall").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    const onPrintMultipleInvoices = () => {
        var selected = new Array();
        var type = new Array();
        var NumberOfInvoice = $("#NumberOfInvoice").val();
        var EstimateInvoice = $("#EstimateInvoice:checked").val();

        $(".cheking input[type=checkbox]:checked").each(function() {
            selected.push(this.value);
            type.push($(this).data('type'));
        });

        if (selected.length > 0) {
            if (!confirm('Are you sure to Print All Invoice?')) return false;
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Sale/salePrintMultipleInvoice",
                data    :   {id: selected, type: type, NumberOfInvoice: NumberOfInvoice, EstimateInvoice: EstimateInvoice},
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

    $(document).ready(function() {
        var sale_register = document.getElementById('sale_register').value;
        if (sale_register == 'columnar') {
            document.getElementById('divas').style.display = "none";
            document.getElementById('details_view').style.display = "none";
            document.getElementById('hsn_report').style.display = "none";
            document.getElementById('Diff').style.display = "block";

        } else if (sale_register == 'hsn') {
            document.getElementById('divas').style.display = "none";
            document.getElementById('details_view').style.display = "none";
            document.getElementById('Diff').style.display = "none";
            document.getElementById('hsn_report').style.display = "block";

        } else if (sale_register == 'detailview') {
            document.getElementById('details_view').style.display = "block";
            document.getElementById('divas').style.display = "none";
            document.getElementById('Diff').style.display = "none";
            document.getElementById('hsn_report').style.display = "none";

        } else {
            document.getElementById('details_view').style.display = "none";
            document.getElementById('Diff').style.display = "none";
            document.getElementById('hsn_report').style.display = "none";
            document.getElementById('divas').style.display = "block";
        }

    });

    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        href = "<?= base_url() ?>estimate/exportestimate?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "file.xls");
        $a[0].click();
        $a.remove();
    })

    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        href = "<?= base_url() ?>estimate/estimate_report?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    }

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

    function PreMonth() {
        var fromDate = document.getElementById('fromDate').value;
        console.log(fromDate, 'fromDate')
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD')
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD')
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $('#reportForm').serialize();
        window.location.href = "<?= base_url(); ?>estimate/estimate_report?" + form;
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
        today = yyyy + '-' + mm + '-' + dd;
        month_first = yyyy + '-' + mm + '-' + '01';
        document.getElementById('fromDate').value = month_first;
        document.getElementById('toDate').value = today;
        var form = $('#reportForm').serialize();
        window.location.href = "<?= base_url(); ?>estimate/estimate_report?" + form;
    }

    function nextmonth() {
        var fromDate = document.getElementById('fromDate').value;
        console.log(fromDate, 'fromDate')
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD')
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD')
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $('#reportForm').serialize();
        window.location.href = "<?= base_url(); ?>estimate/estimate_report?" + form;
    }

    var inputs = document.querySelectorAll("input,select");
    // //(inputs);
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                // alert(nextInput);

                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                nextInput[0].focus();
            }
        })
    }


    function enterKeyPressed(event) {
        if (event.keyCode == 13 || event.which == 13) {
            // alert("okkk")
            var start = document.getElementById('trp0');
            start.focus();
            start.style.backgroundColor = 'blue';
            start.style.color = 'white';
            console.log("Yes colors changr")

        }
    }

    function keyDownForParty(e, row) {
        //down arrow key
        // alert("yes");

        if (e.keyCode == '40') {
            console.log(row, "rowpaykyah");
            var elmtTable = document.getElementById('example');
            var tableRow = document.getElementById('tbody');
            var tableRows = tableRow.getElementsByTagName('tr');
            console.log(tableRows, "TableRows");
            var rowCount = tableRows.length - 1;
            console.log(rowCount, "rowCounter")
            console.log(row, "row")

            if (rowCount > row) {
                if (row < 0) {
                    row = 0;
                }
                row = row + 1;
                previrow = row - 1;
                console.log(row, "tr");
                var start = document.getElementById('trp' + row);
                var previous = document.getElementById('trp' + previrow);

                console.log(previous, "previousrow");
                // var searchparty = document.getElementById('searchproduct').value;
                console.log(start, "start");
                if (previous) {
                    previous.style.backgroundColor = 'white';
                    previous.style.color = 'black';
                    // }
                }
                // }
                // console.log(start,"startrowcount")
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(start);
                // row++;
                // row++;
                // }
            } else {
                // row = row - 1;
                var start = document.getElementById('trp' + row);
                console.log(start, "eleswala");
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
            }
        }
    }

    function keyUpForParty(e, row) {
        //up arrow key
        if (e.keyCode == '38') {
            if (row == 0) {
                var previous = document.getElementById('trp' + row);
                previous.style.backgroundColor = 'white';
                previous.style.color = 'black'
                document.getElementById('searchparty').focus();
                return;
            }
            console.log(row, "rowpaykyahupwalep");
            var elmtTable = document.getElementById('example');
            var tableRow = document.getElementById('tbody');
            var tableRows = elmtTable.getElementsByTagName('tr');
            console.log(tableRows, "TableRows");
            var rowCount = tableRows.length - 1;
            console.log(rowCount, "rowCounter")
            console.log(row, "row")
            previrow = '';
            if (rowCount > row) {
                if (row <= 0) {
                    row = 0;
                    // previrow = row + 1;

                } else {
                    row = row - 1;
                    previrow = row + 1;
                }

                console.log(row, "tr");
                var start = document.getElementById('trp' + row);
                if (previrow) {
                    var previous = document.getElementById('trp' + previrow);

                    console.log(previous, "previousrow");
                    // var searchparty = document.getElementById('searchproduct').value;
                    console.log(start, "start");
                    if (previous) {
                        previous.style.backgroundColor = 'white';
                        previous.style.color = 'black';
                    }
                }
                // }
                // }
                // console.log(start,"startrowcount")
                start.focus();
                start.style.backgroundColor = 'blue';
                start.style.color = 'white';
                // alert("keydown")
                // console.log(start);
                // row++;
                // row++;
                // }
            }
        }
    }

    const getCustomerDetail = (sale_id, key) => {
        window.location.href = '<?= base_url() ?>estimate/estimateEdit/' + sale_id;
    };
    
    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print', 'pageLength']
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
            confirmButtonText: "Yes, delete it!",
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
            type: "get",
            url: url,
            success: function(success) {
                success = JSON.parse(success);
                if (success.status == true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: success.msg,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }

    $('#sale_register').focus();
    
    const OnChequeInfo = (SaleId) => {
        $("#ChequeInfo .modal-dialog .modal-content").load("<?=base_url(); ?>Sale/ChequeInfo/" + SaleId);
        $("#ChequeInfo").modal("show");
    };
    
    const onDeliveryInfoHandler = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/DeliveryInfo/" + Id);
        $('#Info').modal('show');
    };
</script>