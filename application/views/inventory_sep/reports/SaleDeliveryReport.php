<style type="text/css">
    @media (min-width: 1024px) {
        .stickyColumn {
            position: sticky;
        }
        td.stickyColumn {
            background: #ffffff !important;
        }
        <?php if ($this->input->get('estimate') == "") { ?>
        th.stickyColumn:nth-child(2), td.stickyColumn:nth-child(2) {
            left: 30px;
        }
        th.stickyColumn:nth-child(3), td.stickyColumn:nth-child(3) {
            left: 140px;
        }
        th.stickyColumn:nth-child(4), td.stickyColumn:nth-child(4) {
            left: 235px;
        }
        <?php } else { ?>
        th.stickyColumn:nth-child(3), td.stickyColumn:nth-child(3) {
            left: 30px;
        }        
        th.stickyColumn:nth-child(4), td.stickyColumn:nth-child(4) {
            left: 140px;
        }
        th.stickyColumn:nth-child(5), td.stickyColumn:nth-child(5) {
            left: 235px;
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
    .card-header, .card-body, .card-footer {
        padding: 5px;
    }
    table.dataTable tfoot th {
        padding: 10px 5px 6px 5px;
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
                    <div class="card card-outline card-danger">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-danger">Sale Delivery Report</h4>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary btn-xs" title="Previous Month" id="pre" onclick="return PreMonth();">
                                    <i class="fa fa-calendar-minus-o text-white"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-xs" title="Current Month" id="currentMont" onclick="return curent();">
                                    <i class="fa fa-calendar-check-o text-white"></i>
                                </button>
                                <button type="button" class="btn btn-dark btn-xs" title="Next Month" id="nextMonth" onclick="return nextmonth();">
                                    <i class="fa fa-calendar-plus-o text-white"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form action="" id="reportForm" method="GET">
                                    <div class="row mb-2">
                                        <div class="form-group col-md-1 col-sm-1">
                                            <label>Estimate</label>
                                            <br />
                                            <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                            Estimate
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="PartyId">Parties</label>
                                            <select name="PartyId" id="PartyId" class="form-control form-control-sm" title="Select Party Name">
                                                <option <?= $this->input->get('PartyId') == "All" ? "selected" : ""; ?> value="All">All Party</option>
                                                <?php if (!empty($Parties)) { foreach ($Parties as $key => $Party) { ?>
                                                <option <?= $Party->id == $this->input->get('PartyId') ? "selected" : ""; ?> value="<?= $Party->id; ?>"><?= $Party->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="SalesmanId">Salesman</label>
                                            <select name="SalesmanId" id="SalesmanId" class="form-control form-control-sm" title="Select Salesman Name">
                                                <option <?= $this->input->get('SalesmanId') == "All" ? "selected" : ""; ?> value="All">All Salesman</option>
                                                <?php if (!empty($Salesmans)) { foreach ($Salesmans as $key => $Salesman) { ?>
                                                <option <?= $Salesman->Id == $this->input->get('SalesmanId') ? "selected" : ""; ?> value="<?= $Salesman->Id; ?>"><?= $Salesman->Name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="fromDate">From Date</label>
                                            <input type="date" name="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" onkeydown="firstRow(event);" id="fromDate" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="toDate">To Date</label>
                                            <input type="date" name="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" onclick="enterKeyPressed(event);" onkeydown="firstRow(event);" id="toDate" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-1 col-sm-1">
                                            <label for="Delivery">Delivery</label>
                                            <select name="Delivery" id="Delivery" class="form-control form-control-sm" title="Delivery">
                                                <option value="All" <?= $this->input->get('Delivery') == "All" ? "selected" : ""; ?>>All</option>
                                                <option value="1" <?= $this->input->get('Delivery') == "1" ? "selected" : ""; ?>>Yes</option>
                                                <option value="0" <?= $this->input->get('Delivery') == "0" ? "selected" : ""; ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-1 col-sm-1">
                                            <label for="Payment">Payment</label>
                                            <select name="Payment" id="Payment" class="form-control form-control-sm" title="Payment">
                                                <option value="All" <?= $this->input->get('Payment') == "All" ? "selected" : ""; ?>>All</option>
                                                <option value="Paid" <?= $this->input->get('Payment') == "Paid" ? "selected" : ""; ?>>Paid</option>
                                                <option value="Unpaid" <?= $this->input->get('Payment') == "Unpaid" ? "selected" : ""; ?>>Unpaid</option>
                                                <option value="Partial" <?= $this->input->get('Payment') == "Partial" ? "selected" : ""; ?>>Partial</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-1 col-sm-1" style="margin-top: 31px;">
                                            <button type="submit" class="btn btn-primary btn-xs" onClick="return search();" onkeydown="firstRow(event);">
                                                <i class="fa fa-search text-white"></i> Search
                                            </button>
                                            <a href="<?= base_url(); ?>Reports/SaleDeliveryReport" class="btn btn-info btn-xs" title="Refresh" id="resetbtn">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                                <table class="table table-sm table-bordered dataTable">
                                    <?php if ($this->input->get('estimate') == "") { ?>
                                    <thead class="bg-danger">
                                        <tr>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white stickyColumn">Invoice No</th>
                                            <th class="text-white stickyColumn">Invoice Date</th>
                                            <th class="text-white stickyColumn">Party Name</th>
                                            <th class="text-white">Mobile</th>
                                            <th class="text-white">Total</th>
                                            <th class="text-white">Payment</th>
                                            <th class="text-white">Delivery Date</th>
                                            <th class="text-white">Delivery</th>
                                            <th class="text-white">Salesman</th>
                                            <th class="text-white">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($SaleResult) {
                                                $TotalAmount = 0;
                                                $Sr = 0;
                                                foreach ($SaleResult as $key => $row) {
                                                    $Sr++;
                                                    $TotalAmount += $row->total;
                                                    
                                                    $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$row->id\"]') AND transaction_type = 'DR'";
                                                    $query = $this->db->query($sql);
                                                    $Payment = $query->row();
                                                    $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, cashDiscountAmount, ChequeStatus')->where('invoice_id', $row->id)->where('transaction_type', 'DR')->get('payment')->row();
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td class="stickyColumn"><?= $row->invoiceNumber; ?></td>
                                            <td class="stickyColumn"><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                                            <td class="stickyColumn"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $row->customerId], 'contact'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($row->total); ?></td>
                                            <td>
                                                <?php if ($row->paid_amount == $row->total) { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
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
                                                <?php } } elseif ($row->paid_amount == NULL && $row->paid_amount == 0) { ?>
                                                <a href="<?= base_url(); ?>receipt/index/<?= $row->id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle"></i> Unpaid
                                                </a>
                                                <?php } else { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
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
                                            <td><?= $row->delivery_date ? date('d-m-Y', strtotime($row->delivery_date)) : ""; ?></td>
                                            <td><?= $row->delivery_status == 1 ? "Yes" : "No"; ?></td>
                                            <td><?=get_title('employees', ['Id' => get_title('parties', ['id' => $row->customerId], 'SalesmanId')], 'Name'); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $row->id; ?>);" class="text-success" title="Delivery">
                                                    <i class="fa fa-bus"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>sale/viewsale/<?= $row->id; ?>" class="text-info" target="_blank" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>sale/saleEdit/<?= $row->id; ?>" class="text-primary" target="_blank" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>sale/printSale/<?= $row->id; ?>" class="text-danger" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" class="text-navy" onclick="onDeliveryChatHandler(<?= $row->id; ?>);" title="Delivery Chat">
                                                    <i class="fa fa-comment"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: right;">Total</th>
                                            <th colspan="2"><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalAmount); ?></th>
                                            <th colspan="4"></th>
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
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Delivery Date</th>
                                            <th>Delivery</th>
                                            <th>Salesman</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php     
                                            $TotalAmount = 0;

                                            $PartyId = $this->input->get('PartyId');
                                            $SalesmanId = $this->input->get('SalesmanId');
                                            $Delivery = $this->input->get('Delivery');
                                            $Payment = $this->input->get('Payment');
                                            $from = $this->input->get('fromDate');
                                            $to = $this->input->get('toDate');
                                            $user_id = $this->session->userdata('user_id');
                                            $comp_id = $this->session->userdata('comp_id');
                                            $CompanyBranchId = $this->session->userdata('CompBranchId');
                                            $status = 1;
                                            $financial_year = $this->session->userdata('financial_year');

                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, total, type, paid_amount, delivery_status, delivery_date, SalesmanId');
                                            $this->db->from('sales');
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $CompanyBranchId);
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            if ($PartyId) {
                                                if ($PartyId != 'All') {
                                                    $this->db->where('customerId', $PartyId);
                                                }
                                            }
                                            if ($SalesmanId) {
                                                if ($SalesmanId != 'All') {
                                                    $this->db->where_in('SalesmanId', $SalesmanId);
                                                }
                                            }
                                            if ($Delivery != "") {
                                                if ($Delivery != 'All') {
                                                    $this->db->where('delivery_status', $Delivery);
                                                }
                                            }
                                            if ($Payment == 'Paid') {
                                                $this->db->where('paid_amount', 'total', false);
                                            } elseif ($Payment == 'Unpaid') {
                                                $this->db->group_start();
                                                $this->db->where('paid_amount', NULL);
                                                $this->db->or_where('paid_amount', 0);
                                                $this->db->group_end();
                                            } elseif ($Payment == 'Partial') {
                                                $this->db->where('paid_amount != ', 'total', false);
                                            }
                                            $query1 = $this->db->get_compiled_select();

                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, total, type, paid_amount, delivery_status, delivery_date, SalesmanId');
                                            $this->db->from('estimates');
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $CompanyBranchId);
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            if ($PartyId) {
                                                if ($PartyId != 'All') {
                                                    $this->db->where('customerId', $PartyId);
                                                }
                                            }
                                            if ($SalesmanId) {
                                                if ($SalesmanId != 'All') {
                                                    $this->db->where('SalesmanId', $SalesmanId);
                                                }
                                            }
                                            if ($Delivery != "") {
                                                if ($Delivery != 'All') {
                                                    $this->db->where('delivery_status', $Delivery);
                                                }
                                            }
                                            if ($Payment == 'Paid') {
                                                $this->db->where('paid_amount', 'total', false);
                                            } elseif ($Payment == 'Unpaid') {
                                                $this->db->group_start();
                                                $this->db->where('paid_amount', NULL);
                                                $this->db->or_where('paid_amount', 0);
                                                $this->db->group_end();
                                            } elseif ($Payment == 'Partial') {
                                                $this->db->where('paid_amount != ', 'total', false);
                                            }
                                            $query2 = $this->db->get_compiled_select();

                                            $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC")->result();
                                            if (!empty($allsales)) {
                                                $Sr = 0;
                                                foreach ($allsales as $key => $data) {
                                                    $Sr++;
                                                    $TotalAmount += (float) ($data->total ?? 0);
                                                    
                                                    $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$data->id\"]') AND transaction_type = 'DR'";
                                                    $query = $this->db->query($sql);
                                                    $Payment = $query->row();
                                                    $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, cashDiscountAmount, ChequeStatus')->where('invoice_id', $data->id)->where('transaction_type', 'DR')->get('payment')->row();
                                                    if (!$PaymentInfo) {
                                                        $PaymentInfo = (object) ['paymentmode' => null, 'cashDiscountAmount' => 0, 'ChequeStatus' => null];
                                                    }
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $data->type; ?></td>
                                            <td class="stickyColumn"><?= $data->invoiceNumber; ?></td>
                                            <td class="stickyColumn"><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                                            <td class="stickyColumn"><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                                            <td><?= get_title('parties', ['id' => $data->customerId], 'contact'); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat((float) ($data->total ?? 0)); ?></td>
                                            <td>
                                                <?php if ($data->paid_amount == $data->total) { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
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
                                                <a href="<?= base_url(); ?>receipt/index/<?= $data->id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle"></i> Unpaid
                                                </a>
                                                <?php } else { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <a href="javascript:void(0);" class="btn bg-maroon btn-xs" onclick="OnChequeInfo(<?= $data->id; ?>);" title="Cheque Info">
                                                    <i class="fa fa-exclamation"></i> UnPaid/PDC
                                                </a>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <a href="<?= base_url(); ?>receipt/index/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?>receipt/index/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } ?>
                                            </td>
                                            <td><?= $data->delivery_date ? date('d-m-Y', strtotime($data->delivery_date)) : ""; ?></td>
                                            <td><?= $data->delivery_status == 1 ? "Yes" : "No"; ?></td>
                                            <td><?= get_title('employees', ['Id' => get_title('parties', ['id' => $data->customerId], 'SalesmanId')], 'Name');?></td>
                                            <td>
                                                <?php if ($data->type == "Sale") { ?>
                                                <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $data->id; ?>);" class="text-success" title="Delivery">
                                                    <i class="fa fa-bus"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>sale/viewsale/<?= $data->id; ?>" class="text-info" target="_blank" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>sale/saleEdit/<?= $data->id; ?>" class="text-primary" target="_blank" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>sale/printSale/<?= $data->id; ?>" class="text-danger" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" class="text-navy" onclick="onDeliveryChatHandler(<?= $data->id; ?>);" title="Delivery Chat">
                                                    <i class="fa fa-comment"></i>
                                                </a>
                                                <?php } elseif ($data->type == "Estimate") { ?>
                                                <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $data->id; ?>);" class="text-success" title="Delivery">
                                                    <i class="fa fa-bus"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>estimate/viewestimate/<?= $data->id; ?>" class="text-info" target="_blank" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>estimate/estimateEdit/<?= $data->id; ?>" class="text-primary" target="_blank" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>estimate/printEstimate/<?= $data->id; ?>" class="text-danger" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" class="text-navy" onclick="onDeliveryChatHandler(<?= $data->id; ?>);" title="Delivery Chat">
                                                    <i class="fa fa-comment"></i>
                                                </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6">Total</th>
                                            <th colspan="2"><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalAmount); ?></th>
                                            <th colspan="4"></th>
                                        </tr>
                                    </tfoot>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">    
    $(document).ready(function() {
        $('#PartyId').select2();
        $('#SalesmanId').select2();
    });
    
    const onDeliveryInfoHandler = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/DeliveryInfo/" + Id);
        $('#Info').modal('show');
    };

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
        window.location.href = "<?= base_url(); ?>Reports/SaleDeliveryReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Reports/SaleDeliveryReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Reports/SaleDeliveryReport?" + form;
    }

    $(function() {
        $(".dataTable").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
    
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