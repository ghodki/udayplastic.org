<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    @media (min-width: 1024px) {
        .stickyColumn {
            position: sticky;
            background: #ffffff !important;
        }
        <?php if ($this->input->get('estimate') == "") { ?>
        th.stickyColumn:nth-child(2), td.stickyColumn:nth-child(2) {
            left: 30px;
        }
        th.stickyColumn:nth-child(3), td.stickyColumn:nth-child(3) {
            left: 182px;
        }
        th.stickyColumn:nth-child(4), td.stickyColumn:nth-child(4) {
            left: 315px;
        }
        <?php } else { ?>
        th.stickyColumn:nth-child(3), td.stickyColumn:nth-child(3) {
            left: 30px;
        }
        th.stickyColumn:nth-child(4), td.stickyColumn:nth-child(4) {
            left: 182px;
        }
        th.stickyColumn:nth-child(5), td.stickyColumn:nth-child(5) {
            left: 315px;
        }
        <?php } ?>
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
    .btn-group{
        display: block;
    }
    .custom-select {
        height: calc(2rem + 1px);
        line-height: 1;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                
                <div class="col-lg-12">
                    <!-- Index Start -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Sales Report By Salesman</h4>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" title="Previous Month" id="pre" onclick="return PreMonth();" class="btn btn-primary btn-sm">
                                        Previous Month
                                    </button>
                                    <button type="button" title="Current Month" id="currentMont" onclick="return curent();" class="btn btn-warning btn-sm">
                                        Current Month
                                    </button>
                                    <button type="button" title="Next Month" id="nextMonth" onclick="return nextmonth();" class="btn btn-danger btn-sm">
                                        Next Month
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <div class="pull-right">
                                        <a href="<?= base_url(); ?>sale/sale_report" class="btn btn-info btn-sm" title="Back to Sale Report">
                                            <i class="fa fa-file-alt"></i> Sale Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                <div class="row">
                                    <div class="form-group col-md-2 col-sm-6">
                                        <?php
                                            $salesman = $this->input->get('salesman');
                                            $salesman = is_array($salesman) ? $salesman : [];
                                        ?>
                                        <label for="salesman">Salesman</label>
                                        <select name="salesman[]" id="salesman" class="form-control form-control-sm" multiple="multiple" title="Salesman">
                                            <option <?= in_array('All', $salesman, true) ? "selected" : ""; ?> value="All" <?= set_select('salesman', 'All'); ?>>All</option>
                                            <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $sdata) { ?>
                                            <option <?= in_array($sdata->Id, $salesman) ? "selected" : ""; ?> value="<?= $sdata->Id; ?>" <?= set_select('salesman', $sdata->Id); ?>><?= $sdata->Name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6">
                                        <label for="Party">Parties</label>
                                        <select name="Party" id="Party" class="form-control form-control-sm" title="Party">
                                            <option value="All" <?= $this->input->get('party') == 'All' ? 'selected' : ''; ?><?= set_select('Party', 'All'); ?>>All</option>
                                            <?php if (isset($Parties) && !empty($Parties)) { foreach ($Parties as $key => $Party) { ?>
                                            <option value="<?= $Party->id; ?>" <?= $this->input->get('party') == $Party->id ? 'selected' : ''; ?><?= set_select('Party', $Party->id); ?>><?= $Party->name; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-1 col-sm-6">
                                        <label>Estimate</label>
                                        <br />
                                        <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                        Estimate
                                    </div>
                                    <div class="form-group col-md-2 col-sm-6">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-6">
                                        <label for="toDate">To Date</label>
                                        <input type="date" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                    </div>
                                    <div class="form-group col-md-2 col-sm-6" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                            Search
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" title="Refresh" id="resetbtn">
                                            <i class="fa fa-refresh text-white"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" title="JSON File" id="json">
                                            <i class="fa fa-file-code"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" title="Excel File" id="excelExport" >
                                            <i class="fa fa-file-excel-o text-white"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" title="PDF File" id="downloadPDF">
                                            <i class="fa fa-file-pdf-o text-white"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="divas" class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="example">
                                    <?php if ($this->input->get('estimate') == "") { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th class="stickyColumn">Invoice No</th>
                                            <th class="stickyColumn">Invoice Date</th>
                                            <th class="stickyColumn">Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Payment Mode</th> 
                                            <th>Taxable Amount</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>Payment Mode</th>
                                            <th>Discount</th>
                                            <th>Salesman</th>
                                            <th>Delivery</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form class="form_select" method="POST" id="form_select">
                                            <?php
                                                $TotalTaxableAmount = 0;
                                                $TotalTax = 0;
                                                $TotalAmount = 0;
                                                $PaidAmount = 0;
                                                $Balance = 0;
                                                if ($result) {
                                                    foreach ($result as $key => $row) {
                                                        $TotalTaxableAmount += (float) ($row->taxableAmount ?? 0);
                                                        $TotalTax += (float) ($row->totalTax ?? 0);
                                                        $TotalAmount += (float) ($row->total ?? 0);
                                                        $PaidAmount += (float) ($row->paid_amount ?? 0);
                                                        $Balance += ((float) ($row->total ?? 0) - (float) ($row->paid_amount ?? 0));
                                                        
                                                        $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$row->id\"]') AND transaction_type = 'DR'";
                                                        $query = $this->db->query($sql);
                                                        $Payment = $query->row();
                                                        $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, cashDiscountAmount, ChequeStatus')->where('invoice_id', $row->id)->where('transaction_type', 'DR')->get('payment')->row();
                                                        if (!$PaymentInfo) {
                                                            $PaymentInfo = (object) ['paymentmode' => null, 'cashDiscountAmount' => 0, 'ChequeStatus' => null];
                                                        }
                                            ?>
                                            <tr>
                                                <td><?= $key + 1; ?></td>
                                                <td class="stickyColumn"><?= $row->invoiceNumber; ?></td>
                                                <td class="stickyColumn"><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                                                <td class="stickyColumn"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'contact'); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'gst'); ?></td>
                                                <td>
                                                    <?php
                                                        if ($row->voucherType == 1) {
                                                            $payment_mode = "Cash";
                                                        } else if ($row->voucherType == 2) {
                                                            $payment_mode = "Credit";
                                                        } else {
                                                            $payment_mode = $row->voucherType;
                                                        }
                                                        echo $payment_mode;
                                                    ?>
                                                </td> 
                                                <td><i class="fa fa-inr"></i> <?= number_format((float) ($row->taxableAmount ?? 0)); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format((float) ($row->totalTax ?? 0)); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format((float) ($row->total ?? 0)); ?></td>
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
                                                <td><i class="fa fa-inr"></i> <?= number_format((float) ($row->paid_amount ?? 0)); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format((float) ($row->total ?? 0) - (float) ($row->paid_amount ?? 0)); ?></td>
                                                <td><?= !empty($PaymentInfo->paymentmode) ? get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') : ''; ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format((float) ($PaymentInfo->cashDiscountAmount ?? 0)); ?></td>
                                                <td><?= get_title('employees', ['Id' => get_title('parties', ['id' => $row->customerId], 'SalesmanId')], 'Name'); ?></td>
                                                <td><?= $row->delivery_status == 1 ? "Yes" : "No"; ?></td>
                                                <td>
                                                    <a href="<?= base_url('sale/viewsale/') . $row->id; ?>" class="text-info" target="_blank" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url('sale/printSale/') . $row->id; ?>" class="text-success" target="_blank" title="Print">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $row->id; ?>);" class="text-navy" title="Delivery">
                                                        <i class="fa fa-bus"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </form>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align: right;">Total</th>
                                            <th>
                                                <i class="fa fa-inr total"></i> <?= number_format($TotalTaxableAmount); ?>
                                            </th>
                                            <th>
                                                <i class="fa fa-inr"></i> <?= number_format($TotalTax); ?>
                                            </th>
                                            <th>
                                                <i class="fa fa-inr"></i> <?= number_format($TotalAmount); ?>
                                            </th>
                                            <th></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($PaidAmount); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($Balance); ?></th>
                                            <th colspan="5"></th>
                                        </tr>
                                    </tfoot>
                                    <?php } elseif ($this->input->get('estimate') == true) { ?>
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Type</th>
                                            <th class="stickyColumn">Invoice No</th>
                                            <th class="stickyColumn">Invoice Date</th>
                                            <th class="stickyColumn">Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Payment Mode</th> 
                                            <th>Taxable Amount</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>Payment Mode</th>
                                            <th>Discount</th>
                                            <th>Salesman</th>
                                            <th>Delivery</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $TotalTaxableAmount = 0;
                                            $TotalTax = 0;        
                                            $TotalAmount = 0;
                                            $PaidAmount = 0;
                                            $Balance = 0;
                                            
                                            $getsalesman = $this->input->get('salesman');
                                            $getsalesman = is_array($getsalesman) ? $getsalesman : [];
                                            $from = $this->input->get('fromDate') ?: date('Y-m-d');
                                            $to = $this->input->get('toDate') ?: date('Y-m-d');
                                            $user_id = $this->session->userdata('user_id');
                                            $comp_id = $this->session->userdata('comp_id');
                                            $CompanyBranchId = $this->session->userdata('CompBranchId');
                                            $status = 1;
                                            $financial_year = $this->session->userdata('financial_year');
                                            
                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, taxableAmount, totalTax, total, type, payment_status, paid_amount, SalesmanId');
                                            $this->db->from('sales');
                                            if (!empty($getsalesman) && !in_array('All', $getsalesman, true)) {
                                                $this->db->where_in('SalesmanId', $getsalesman);
                                            }
                                            if (!empty($this->input->get('Party'))) {
                                                if ($this->input->get('Party') != 'All') {
                                                    $this->db->where('customerId', $this->input->get('Party'));
                                                }
                                            }
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $CompanyBranchId);
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            $query1 = $this->db->get_compiled_select();
                                            
                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, taxableAmount, totalTax, total, type, payment_status, paid_amount, SalesmanId');
                                            $this->db->from('estimates');
                                            if (!empty($getsalesman) && !in_array('All', $getsalesman, true)) {
                                                $this->db->where_in('SalesmanId', $getsalesman);
                                            }
                                            if (!empty($this->input->get('Party'))) {
                                                if ($this->input->get('Party') != 'All') {
                                                    $this->db->where('customerId', $this->input->get('Party'));
                                                }
                                            }
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $CompanyBranchId);
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            $query2 = $this->db->get_compiled_select();
                                            
                                            $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC")->result();
                                            if (!empty($allsales)) {
                                                $n = 0;
                                                foreach ($allsales as $key => $data) {
                                                    $n++;
                                                    $TotalTaxableAmount += (float) ($data->taxableAmount ?? 0);
                                                    $TotalTax += (float) ($data->totalTax ?? 0);
                                                    $TotalAmount += (float) ($data->total ?? 0);
                                                    $PaidAmount += (float) ($data->paid_amount ?? 0);
                                                    $Balance += ((float) ($data->total ?? 0) - (float) ($data->paid_amount ?? 0));
                                                    
                                                    $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$data->id\"]') AND transaction_type = 'DR'";
                                                    $query = $this->db->query($sql);
                                                    $Payment = $query->row();
                                                    $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, cashDiscountAmount, ChequeStatus')->where('invoice_id', $data->id)->where('transaction_type', 'DR')->get('payment')->row();
                                                    if (!$PaymentInfo) {
                                                        $PaymentInfo = (object) ['paymentmode' => null, 'cashDiscountAmount' => 0, 'ChequeStatus' => null];
                                                    }
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $data->type; ?></td>
                                            <td class="stickyColumn"><?= $data->invoiceNumber; ?></td>
                                            <td class="stickyColumn"><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                                            <td class="stickyColumn"><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $data->customerId], 'contact'); ?></td>
                                            <td><?= get_title('parties', ['id' => $data->customerId], 'gst'); ?></td>
                                            <td>
                                                <?php
                                                    if ($data->voucherType == 1) {
                                                        $payment_mode = "Cash";
                                                    } elseif ($data->voucherType == 2) {
                                                        $payment_mode = "Credit";
                                                    } else {
                                                        $payment_mode = $data->voucherType;
                                                    }
                                                    echo $payment_mode;
                                                ?>
                                            </td>
                                            <td>
                                                <?= !empty($data->taxableAmount) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format((float) $data->taxableAmount) : ""; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data->totalTax) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format((float) $data->totalTax) : ""; ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->total ?? 0)); ?></td>
                                            <td>
                                                <?php if ($data->paid_amount == $data->total) { if (!empty($PaymentInfo->paymentmode) && get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $data->id; ?>);" title="Cheque Info">
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
                                                <?php } } elseif ($data->paid_amount == NULL || $data->paid_amount == 0) { ?>
                                                <a href="<?= base_url(); ?>Payment/index/<?= $data->id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle"></i> Unpaid
                                                </a>
                                                <?php } else { if (!empty($PaymentInfo->paymentmode) && get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $data->id; ?>);" title="Cheque Info">
                                                    <i class="fa fa-exclamation"></i> UnPaid/PDC
                                                </a>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <a href="<?= base_url(); ?>Payment/index/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?>Payment/index/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->paid_amount ?? 0)); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->total ?? 0) - (float) ($data->paid_amount ?? 0)); ?></td>
                                            <td><?= !empty($PaymentInfo->paymentmode) ? get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') : ''; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format((float) ($PaymentInfo->cashDiscountAmount ?? 0)); ?></td>
                                            <td><?= get_title('employees', ['Id' => get_title('parties', ['id' => $data->customerId], 'SalesmanId')], 'Name'); ?></td>
                                            <td><?= $data->delivery_status == 1 ? "Yes" : "No"; ?></td>
                                            <td>
                                                <?php if ($data->type == "Sale") { ?>
                                                <a href="<?= base_url('sale/viewsale/') . $data->id; ?>" class="text-info" target="_blank" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('sale/printSale/') . $data->id; ?>" class="text-success" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $data->id; ?>);" class="text-navy" title="Delivery">
                                                    <i class="fa fa-bus"></i>
                                                </a>
                                                <?php } if ($data->type == "Estimate") { ?>
                                                <a href="<?= base_url('estimate/viewestimate/') . $data->id; ?>" class="text-info" target="_blank" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url('estimate/printEstimate/') . $data->id; ?>" class="text-success" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $data->id; ?>);" class="text-navy" title="Delivery">
                                                    <i class="fa fa-bus"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8" style="text-align: right;">Total</th>
                                            <th>
                                                <i class="fa fa-inr"></i> <?= number_format($TotalTaxableAmount); ?>
                                            </th>
                                            <th>
                                                <i class="fa fa-inr"></i> <?= number_format($TotalTax); ?>
                                            </th>
                                            <th>
                                                <i class="fa fa-inr"></i> <?= number_format($TotalAmount); ?>
                                            </th>
                                            <th></th>
                                            <th>
                                                <i class="fa fa-inr"></i> <?= number_format($PaidAmount); ?>
                                            </th>
                                            <th>
                                                <i class="fa fa-inr"></i> <?= number_format($Balance); ?>
                                            </th>
                                            <th colspan="5"></th>
                                        </tr>
                                    </tfoot>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Index End -->
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

<div class="modal fade" id="DeliveryChat" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Party').select2();
    });
    
    $('#salesman').multiselect({
        buttonWidth: 'auto'
    });

    $("#sale-selectall").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>Reports/exportsale?" + form;
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
        var href = "<?= base_url(); ?>Reports/pdfsale?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a[0].click();
        $a.remove();
    });

    const onFormSubmitHandler = (e) => {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>Reports/SalesmanWiseSaleReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Reports/SalesmanWiseSaleReport";
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
        var form = $('#reportForm').serialize();
        window.location.href = "<?= base_url(); ?>Reports/SalesmanWiseSaleReport?" + form;
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
        var form = $('#reportForm').serialize();
        window.location.href = "<?= base_url(); ?>Reports/SalesmanWiseSaleReport?" + form;
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
        var form = $('#reportForm').serialize();
        window.location.href = "<?= base_url(); ?>Reports/SalesmanWiseSaleReport?" + form;
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
        var FileName = "<?= str_replace(" ", "_", get_title('companybranch', ['Id' => $this->session->userdata('CompBranchId')], 'BranchName')); ?>_SalesmanWiseSaleReport";
        <?php } else { ?>
        var FileName = "<?= str_replace(" ", "_", get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'company_name')); ?>_SalesmanWiseSaleReport";
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
</script>