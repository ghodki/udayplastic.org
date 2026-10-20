<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    .card-header {
        padding: .5rem .5rem;
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
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Sales</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Sales</a></li>
                        <li class="breadcrumb-item active">Sale Report</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
                
                <div class="col-lg-12">
                    <!-- Index Start -->
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-maroon">Sales Report</h5>
                            </div>
                            <div class="pull-right">
                                <button type="button" title="Previous Month" onclick="return PreMonth();" class="btn btn-primary btn-sm">
                                    <i class="fa fa-calendar-minus text-white"></i>
                                </button>
                                <button type="button" title="Current Month" id="currentMont" onclick="return curent();" class="btn btn-warning btn-sm">
                                    <i class="fa fa-calendar-check text-white"></i>
                                </button>
                                <button type="button" title="Next Month" id="nextMonth" onclick="return nextmonth();" class="btn btn-danger btn-sm">
                                    <i class="fa fa-calendar-plus text-white"></i>
                                </button>
                                <a href="<?= base_url(); ?>Employee/SalesReportGrid" title="Grid View" class="btn bg-maroon btn-sm">
                                    <i class="fa fa-th-large text-white"></i> Grid View
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                    <div class="row">
                                        <?php 
                                            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                                $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeInfo->Id, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                                                if (!is_array($SalesmanIds)) {
                                                    $SalesmanIds = [];
                                                }
                                                $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');

                                                $salesmans = !empty($SalesmanIds)
                                                    ? $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $SalesmanIds)->get('employees')->result()
                                                    : [];
                                                $salesman_selected = [];
                                                foreach (input_get_array('salesman') as $key => $sids) {
                                                    $salesman_selected[] = $sids;
                                                }
                                        ?>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="salesman">Salesman</label>
                                            <select name="salesman[]" id="salesman" class="form-control form-control-sm" multiple="multiple" title="Salesman">
                                                <option <?= in_array('All', $salesman_selected) ? 'selected' : ''; ?> value="All" <?= set_select('salesman', 'All'); ?>>All</option>
                                                <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $sdata) { ?>
                                                <option <?= in_array($sdata->Id, $salesman_selected) ? 'selected' : ''; ?> value="<?= $sdata->Id; ?>" <?= set_select('salesman', $sdata->Id); ?>><?= $sdata->Name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="Party">Party</label>
                                            <select name="Party" id="Party" class="form-control form-control-sm" title="Party">
                                                <option value="All" <?= $this->input->get('Party') == 'All' ? 'selected' : ''; ?><?= set_select('Party', 'All'); ?>>All</option>
                                                <?php
                                                    $PartyWhere = ['user_id' => $this->session->userdata('UserId'), 'comp_id' => $this->session->userdata('CompId')];
                                                    if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                                        $PartySalesman = json_decode(get_title('employees', ['Id' => $EmployeeInfo->Id, 'IsDeleted' => 1], 'WarehouseSalesman'), true);
                                                        if (!is_array($PartySalesman)) {
                                                            $PartySalesman = [];
                                                        }
                                                        $PartySalesman[] = $EmployeeInfo->Id;
                                                    } else {
                                                        $PartySalesman = [$EmployeeInfo->Id];
                                                    }
                                                    $PartySalesman = array_values(array_unique(array_filter($PartySalesman, static function ($id) {
                                                        return $id !== null && $id !== '';
                                                    })));
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $PartyWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                    }

                                                    $Parties = !empty($PartySalesman)
                                                        ? $this->db->select('id, name')->where($PartyWhere)->where_in('SalesmanId', $PartySalesman)->order_by('id', 'DESC')->get('parties')->result()
                                                        : [];
                                                    if (isset($Parties) && !empty($Parties)) {
                                                        foreach ($Parties as $key => $Party) {
                                                ?>
                                                <option value="<?= $Party->id; ?>" <?= $this->input->get('Party') == $Party->id ? 'selected' : ''; ?><?= set_select('Party', $Party->id); ?>><?= $Party->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-1 col-sm-1">
                                            <label>Estimate</label>
                                            <br />
                                            <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                            Estimate
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="fromDate">From Date</label>
                                            <input type="date" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="toDate">To Date</label>
                                            <input type="date" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-1 col-sm-1">
                                            <label for="Delivery">Delivery</label>
                                            <select name="Delivery" id="Delivery" class="form-control form-control-sm" title="Delivery">
                                                <option value="All" <?= $this->input->get('Delivery') == "All" ? "selected" : ""; ?>>All</option>
                                                <option value="1" <?= $this->input->get('Delivery') == "1" ? "selected" : ""; ?>>Yes</option>
                                                <option value="0" <?= $this->input->get('Delivery') == "0" ? "selected" : ""; ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2" style="margin-top: 31px;">
                                            <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                                Search
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" title="Refresh" id="resetbtn">
                                                <i class="fa fa-refresh text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <table class="table table-striped table-bordered table-sm">
                                    <?php if ($this->input->get('estimate') == "") { ?>
                                    <thead class="bg-maroon">
                                        <tr>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white">Invoice No</th>
                                            <th class="text-white">Invoice Date</th>
                                            <th class="text-white">Party Name</th>
                                            <th class="text-white">Mobile</th>
                                            <th class="text-white">GST No</th>
                                            <th class="text-white">Payment Mode</th> 
                                            <th class="text-white">Taxable Amount</th>
                                            <th class="text-white">Tax</th>
                                            <th class="text-white">Total</th>
                                            <th class="text-white">Payment</th>
                                            <th class="text-white">Paid Amount</th>
                                            <th class="text-white">Balance</th>
                                            <th class="text-white">Payment Mode</th>
                                            <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor' || $this->session->userdata('RoleType') == "Team Leader") { ?>
                                            <th class="text-white">Salesman</th>
                                            <?php } ?>
                                            <th class="text-white">Delivery</th>
                                            <th class="text-white">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form class="form_select" method="POST" id="form_select">
                                            <?php
                                                $TotalTaxableAmount = 0;
                                                $TotalTax = 0;
                                                $TotalAmount = 0;
                                                $PaidAmount = 0;
                                                $BalanceAmount = 0;
                                                if ($Sales) {
                                                    foreach ($Sales as $key => $row) {
                                                        $TotalTaxableAmount += $row->taxableAmount;
                                                        $TotalTax += $row->totalTax;
                                                        $TotalAmount += $row->total;
                                                        $PaidAmount += $row->paid_amount;
                                                        $BalanceAmount += ($row->total - $row->paid_amount);
                                                        
                                                        $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$row->id\"]') AND transaction_type = 'DR'";
                                                        $query = $this->db->query($sql);
                                                        $Payment = $query->row();
                                                        $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, ChequeStatus')->where('invoice_id', $row->id)->where('transaction_type', 'DR')->get('payment')->row();
                                            ?>
                                            <tr>
                                                <td><?= $key + 1; ?></td>
                                                <td>
                                                    <a href="<?= base_url(); ?>Employee/ViewSale/<?= $row->id; ?>" class="text-primary" title="Invoice Detail">
                                                        <?= $row->invoiceNumber; ?>
                                                    </a>
                                                </td>
                                                <td><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                                                <td><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
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
                                                <td><i class="fa fa-inr"></i> <?= number_format($row->taxableAmount); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format($row->totalTax); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format($row->total); ?></td>
                                                <td>
                                                    <?php if ($row->paid_amount == $row->total) { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                    <label class="btn bg-maroon btn-xs">
                                                        <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                    </label>
                                                    <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                    <label class="btn btn-success btn-xs">
                                                        <i class="fa fa-check-circle text-white"></i> Paid
                                                    </label>
                                                    <?php } } else { ?>
                                                    <label class="btn btn-success btn-xs">
                                                        <i class="fa fa-check-circle text-white"></i> Paid
                                                    </label>
                                                    <?php } } elseif ($row->paid_amount == NULL || $row->paid_amount == 0) { if (employee_perm($EmployeePermission, 'receipts', 'Create')) { ?>
                                                    <a href="<?= base_url(); ?>employee/receipts/<?= $row->id; ?>" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-exclamation-triangle text-white"></i> Unpaid
                                                    </a>
                                                    <?php } else { ?>
                                                    <label class="btn btn-danger btn-xs"><i class="fa fa-exclamation-triangle text-white"></i> Unpaid</label>
                                                    <?php } } else { if (employee_perm($EmployeePermission, 'receipts', 'Create')) { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                    <label class="btn bg-maroon btn-xs">
                                                        <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                    </label>
                                                    <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                    <a href="<?= base_url(); ?>employee/receipts/<?= $row->id; ?>" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-exclamation-circle"></i> Partial
                                                    </a>
                                                    <?php } } else { ?>
                                                    <a href="<?= base_url(); ?>employee/receipts/<?= $row->id; ?>" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-exclamation-circle"></i> Partial
                                                    </a>
                                                    <?php } } else { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                    <label class="btn bg-maroon btn-xs">
                                                        <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                    </label>
                                                    <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                    <label class="btn btn-warning btn-xs">
                                                        <i class="fa fa-exclamation-circle"></i> Partial
                                                    </label>
                                                    <?php } } else { ?>
                                                    <label class="btn btn-warning btn-xs">
                                                        <i class="fa fa-exclamation-circle"></i> Partial
                                                    </label>    
                                                    <?php } } } ?>
                                                </td>
                                                <td><i class="fa fa-inr"></i> <?= number_format($row->paid_amount); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format($row->total - $row->paid_amount); ?></td>
                                                <td><?= get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method'); ?></td>
                                                <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor' || $this->session->userdata('RoleType') == "Team Leader") { ?>
                                                <td><?= get_title('employees', ['Id' => get_title('parties', ['id' => $row->customerId], 'SalesmanId')], 'Name'); ?></td>
                                                <?php } ?>
                                                <td><?= $row->delivery_status == 1 ? "Yes" : "No"; ?></td>
                                                <td>
                                                    <?php if (employee_perm($EmployeePermission, 'SalesDeliveries', 'Edit')) { ?>
                                                    <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $row->id; ?>);" title="Delivery">
                                                        <i class="fa fa-bus text-danger"></i>
                                                    </a>
                                                    &nbsp;
                                                    <?php } if (employee_perm($EmployeePermission, 'Sales', 'Edit')) { ?>
                                                    <a href="<?= base_url(); ?>Employee/SaleEdit/<?= $row->id; ?>" title="Edit">
                                                        <i class="fa fa-edit text-success"></i>
                                                    </a>
                                                    &nbsp;
                                                    <?php } if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Create')) { if ($row->paid_amount != $row->total) { ?>
                                                    <a href="javascript:void(0);" onclick="PaymentRequestForm(<?= $row->id; ?>);" title="Payment Request">
                                                        <i class="fa fa-money text-danger"></i>
                                                    </a>
                                                    &nbsp;
                                                    <?php } } ?>
                                                    <a href="<?= base_url(); ?>Employee/PrintSale/<?= $row->id; ?>" target="_blank" title="Print">
                                                        <i class="fa fa-print text-primary"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>Employee/CopySale/<?= $row->id; ?>" title="Copy Invoice" target="_blank">
                                                        <i class="fa fa-copy text-navy"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" onclick="onDeliveryChatHandler(<?= $row->id; ?>);" title="Delivery Chat">
                                                        <i class="fa fa-comment text-fuchsia"></i>
                                                    </a>
                                                    <?php if (employee_perm($EmployeePermission, 'Sales', 'Delete')) { ?>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>Employee/DeleteSale/<?= $row->id; ?>" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } } ?>
                                        </form>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align: right;">Total</th>
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
                                                <i class="fa fa-inr"></i> <?= number_format($BalanceAmount); ?>
                                            </th>
                                            <th></th>
                                            <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') { ?>
                                            <th></th>
                                            <?php } ?>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <?php } elseif ($this->input->get('estimate') == true) { ?>
                                    <thead class="bg-maroon">
                                        <tr>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white">Type</th>
                                            <th class="text-white">Invoice No</th>
                                            <th class="text-white">Invoice Date</th>
                                            <th class="text-white">Party Name</th>
                                            <th class="text-white">Mobile</th>
                                            <th class="text-white">GST No</th>
                                            <th class="text-white">Payment Mode</th> 
                                            <th class="text-white">Taxable Amount</th>
                                            <th class="text-white">Tax</th>
                                            <th class="text-white">Total</th>
                                            <th class="text-white">Payment</th>
                                            <th class="text-white">Paid Amount</th>
                                            <th class="text-white">Balance</th>
                                            <th class="text-white">Payment Mode</th>
                                            <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor' || $this->session->userdata('RoleType') == "Team Leader") { ?>
                                            <th class="text-white">Salesman</th>
                                            <?php } ?>
                                            <th class="text-white">Delivery</th>
                                            <th class="text-white">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $TotalTaxableAmount = 0;
                                            $TotalTax = 0;        
                                            $TotalAmount = 0;
                                            $PaidAmount = 0;
                                            $BalanceAmount = 0;
                                            
                                            $salesman = [];
                                            foreach (input_get_array('salesman') as $key => $wdata) {
                                                $salesman[] = $wdata;
                                            }
                                            
                                            $salesmans = json_decode(get_title('employees', ['Id' => $EmployeeInfo->Id, 'IsDeleted' => 1], 'WarehouseSalesman'), true);
                                            if (!is_array($salesmans)) {
                                                $salesmans = [];
                                            }
                                            $salesmans[] = $EmployeeInfo->Id;
                                            $salesmans = array_values(array_unique(array_filter($salesmans, static function ($id) {
                                                return $id !== null && $id !== '';
                                            })));
                                            if (empty($salesmans)) {
                                                $salesmans = [$EmployeeInfo->Id];
                                            }
                                            $from = $this->input->get('fromDate');
                                            $to = $this->input->get('toDate');
                                            $user_id = $this->session->userdata('UserId');
                                            $comp_id = $this->session->userdata('CompId');
                                            $status = 1;
                                            $financial_year = $this->session->userdata('financial_year');

                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, taxableAmount, totalTax, total, type, paid_amount, delivery_status, SalesmanId');
                                            $this->db->from('sales');
                                            if (!empty($this->input->get('salesman'))) {
                                                if (!in_array('All', $salesman)) {
                                                    $this->db->where_in('SalesmanId', $salesman);
                                                } else {
                                                    $this->db->where_in('SalesmanId', $salesmans);
                                                }
                                            } else {
                                                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                                    $this->db->where_in('SalesmanId', $salesmans);
                                                } else {
                                                    $this->db->where_in('SalesmanId', [$EmployeeInfo->Id]);
                                                }
                                            }
                                            if (!empty($this->input->get('Party'))) {
                                                if ($this->input->get('Party') != 'All') {
                                                    $this->db->where('customerId', $this->input->get('Party'));
                                                }
                                            }
                                            if ($this->input->get('Delivery') != "") {
                                                if ($this->input->get('Delivery') != 'All') {
                                                    $this->db->where('delivery_status', $this->input->get('Delivery'));
                                                }
                                            }
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            $query1 = $this->db->get_compiled_select();
                                            
                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, taxableAmount, totalTax, total, type, paid_amount, delivery_status, SalesmanId');
                                            $this->db->from('estimates');
                                            if (!empty($this->input->get('salesman'))) {
                                                if (!in_array('All', $salesman)) {
                                                    $this->db->where_in('SalesmanId', $salesman);
                                                } else {
                                                    $this->db->where_in('SalesmanId', $salesmans);
                                                }
                                            } else {
                                                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                                    $this->db->where_in('SalesmanId', $salesmans);
                                                } else {
                                                    $this->db->where_in('SalesmanId', [$EmployeeInfo->Id]);
                                                }
                                            }
                                            if (!empty($this->input->get('Party'))) {
                                                if ($this->input->get('Party') != 'All') {
                                                    $this->db->where('customerId', $this->input->get('Party'));
                                                }
                                            }
                                            if ($this->input->get('Delivery') != "") {
                                                if ($this->input->get('Delivery') != 'All') {
                                                    $this->db->where('delivery_status', $this->input->get('Delivery'));
                                                }
                                            }
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            $query2 = $this->db->get_compiled_select();
                                            
                                            $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC")->result();
                                            if (!empty($allsales)) {
                                                $n = 0;
                                                foreach ($allsales as $key => $data) {
                                                    $n++;
                                                    $TotalTaxableAmount += $data->taxableAmount;
                                                    $TotalTax += $data->totalTax;
                                                    $TotalAmount += $data->total;
                                                    $PaidAmount += $data->paid_amount;
                                                    $BalanceAmount += ($data->total - $data->paid_amount);
                                                    
                                                    $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$data->id\"]') AND transaction_type = 'DR'";
                                                    $query = $this->db->query($sql);
                                                    $Payment = $query->row();
                                                    $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, ChequeStatus')->where('invoice_id', $data->id)->where('transaction_type', 'DR')->get('payment')->row();
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $data->type; ?></td>
                                            <td>
                                                <?php if ($data->type == "Sale") { ?>
                                                <a href="<?= base_url(); ?>Employee/ViewSale/<?= $data->id; ?>" class="text-info" title="Invoice Detail">
                                                    <?= $data->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($data->type == "Estimate") { ?>
                                                <a href="<?= base_url(); ?>Employee/ViewEstimate/<?= $data->id; ?>" class="text-info" title="Invoice Detail">
                                                    <?= $data->invoiceNumber; ?>
                                                </a>                                                
                                                <?php } ?>
                                            </td>
                                            <td><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                                            <td><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
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
                                                <?= !empty($data->taxableAmount) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($data->taxableAmount) : ""; ?>
                                            </td>
                                            <td>
                                                <?= !empty($data->totalTax) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($data->totalTax) : ""; ?>
                                            </td>                                            
                                            <td><i class="fa fa-inr"></i> <?= number_format($data->total); ?></td>
                                            <td>
                                                <?php if ($data->paid_amount == $data->total) { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <label class="btn bg-maroon btn-xs">
                                                    <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                </label>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } } else { ?>
                                                <label class="btn btn-success btn-xs">
                                                    <i class="fa fa-check-circle text-white"></i> Paid
                                                </label>
                                                <?php } } elseif ($data->paid_amount == NULL || $data->paid_amount == 0) { if (employee_perm($EmployeePermission, 'Payments', 'Create')) { ?>
                                                <a href="<?= base_url(); ?>Employee/receipts/<?= $data->id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle text-white"></i> Unpaid
                                                </a>
                                                <?php } else { ?>
                                                <label class="btn btn-danger btn-xs"><i class="fa fa-exclamation-triangle text-white"></i> Unpaid</label>
                                                <?php } } else { if (employee_perm($EmployeePermission, 'Payments', 'Create')) { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <label class="btn bg-maroon btn-xs">
                                                    <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                </label>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <a href="<?= base_url(); ?>Employee/receipts/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } else { ?>
                                                <a href="<?= base_url(); ?>Employee/receipts/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } } else { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                <label class="btn bg-maroon btn-xs">
                                                    <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                </label>
                                                <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                <label class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </label>
                                                <?php } } else { ?>
                                                <label class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </label>    
                                                <?php } } } ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($data->paid_amount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($data->total - $data->paid_amount); ?></td>
                                            <td><?= get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method'); ?></td>
                                            <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor' || $this->session->userdata('RoleType') == "Team Leader") { ?>
                                            <td><?= get_title('employees', ['Id' => get_title('parties', ['id' => $data->customerId], 'SalesmanId')], 'Name'); ?></td>
                                            <?php } ?>
                                            <td><?= $data->delivery_status == 1 ? "Yes" : "No"; ?></td>
                                            <td>
                                                <?php if ($data->type == "Sale") { if (employee_perm($EmployeePermission, 'SalesDeliveries', 'Edit')) { ?>
                                                <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $data->id; ?>);" title="Delivery">
                                                    <i class="fa fa-bus text-danger"></i>
                                                </a>
                                                &nbsp;
                                                <?php } if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Create')) { if ($data->paid_amount != $data->total) { ?>
                                                <a href="javascript:void(0);" onclick="PaymentRequestForm(<?= $data->id; ?>);" title="Payment Request">
                                                    <i class="fa fa-money text-danger"></i>
                                                </a>
                                                &nbsp;
                                                <?php } } ?>
                                                <a href="<?= base_url(); ?>Employee/PrintSale/<?= $data->id; ?>" target="_blank" title="Print">
                                                    <i class="fa fa-print text-primary"></i>
                                                </a>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>Employee/CopySale/<?= $data->id; ?>" title="Copy Invoice" target="_blank">
                                                    <i class="fa fa-copy text-navy"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" onclick="onDeliveryChatHandler(<?= $data->id; ?>);" title="Delivery Chat">
                                                    <i class="fa fa-comment text-fuchsia"></i>
                                                </a>
                                                <?php if (employee_perm($EmployeePermission, 'Sales', 'Delete')) { ?>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>Employee/DeleteSale/<?= $data->id; ?>" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                                <?php } } elseif ($data->type == "Estimate") { if (employee_perm($EmployeePermission, 'SalesDeliveries', 'Edit')) { ?>
                                                <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $data->id; ?>);" title="Delivery">
                                                    <i class="fa fa-bus text-danger"></i>
                                                </a>
                                                &nbsp;
                                                <?php } if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Create')) { if ($data->paid_amount != $data->total) { ?>
                                                <a href="javascript:void(0);" onclick="PaymentRequestForm(<?= $data->id; ?>);" title="Payment Request">
                                                    <i class="fa fa-money text-danger"></i>
                                                </a>
                                                &nbsp;
                                                <?php } } ?>
                                                <a href="<?= base_url(); ?>Employee/PrintEstimate/<?= $data->id; ?>" target="_blank" title="Print">
                                                    <i class="fa fa-print text-primary"></i>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:void(0);" onclick="onDeliveryChatHandler(<?= $data->id; ?>);" title="Delivery Chat">
                                                    <i class="fa fa-comment text-fuchsia"></i>
                                                </a>
                                                <?php if (employee_perm($EmployeePermission, 'Estimates', 'Delete')) { ?>
                                                &nbsp;
                                                <a href="<?= base_url(); ?>Employee/DeleteEstimate/<?= $data->id; ?>" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                                <?php } } ?>
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
                                                <i class="fa fa-inr"></i> <?= number_format($BalanceAmount); ?>
                                            </th>
                                            <th></th>
                                            <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') { ?>
                                            <th></th>
                                            <?php } ?>
                                            <th></th>
                                            <th></th>
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
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="PaymentRequest" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Party').select2();
    });
        
    const PaymentRequestForm = (Id) => {
        jQuery.noConflict();
        $("#PaymentRequest .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/PaymentRequestForm/" + Id);
        $("#PaymentRequest").modal("show");
    };
    
    const onDeliveryChatHandler = (Id) => {
        jQuery.noConflict();
        $("#DeliveryChat .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/DeliveryChatHandler/" + Id);
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
                url     :   "<?= base_url(); ?>Employee/DeliveryChatSave",
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
                url     :   "<?= base_url(); ?>Employee/getSaleDeliveryChat",
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
    
    $('#salesman').multiselect({
        buttonWidth: 'auto'
    });

    $('#resetbtn').click(function(e) {
        window.location.href = "<?= base_url(); ?>Employee/SalesReport";
    });

    function PreMonth() {
        var fromDate = document.getElementById('fromDate').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() - 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $('#reportForm').serialize();        
        window.location.href = "<?= base_url(); ?>Employee/SalesReport?" + form;
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
        var form = $('#reportForm').serialize();
        window.location.href = "<?= base_url(); ?>Employee/SalesReport?" + form;
    }

    function nextmonth() {
        var fromDate = document.getElementById('fromDate').value;
        fromDate = new Date(fromDate);
        fromDate.setMonth(fromDate.getMonth() + 1);
        var firstDay = moment(fromDate, 'YYYY-MM-DD h:m').startOf('month').format('YYYY-MM-DD');
        var lastDay = moment(fromDate, 'YYYY-MM-DD h:m').endOf('month').format('YYYY-MM-DD');
        document.getElementById('toDate').value = lastDay;
        document.getElementById('fromDate').value = firstDay;
        var form = $('#reportForm').serialize();        
        window.location.href = "<?= base_url(); ?>Employee/SalesReport?" + form;
    }
        
    $(function() {        
        $(".table").DataTable({
            dom: 'Bfrtip',
            pageLength: 20,
            lengthMenu: [
                            [20, 50, 100, -1], [20, 50, 100, "All"]
            ],
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time() ?>",
                    footer: true,
                    exportOptions:  {
                        columns: []
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time() ?>",
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time() ?>",
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time() ?>",
                    footer: true,
                    exportOptions: {
                        columns: []
                    }
                },
                {
                    extend: 'print',
                    title: "<?= get_title('inventory_billing', ['id' => $this->session->userdata('comp_id')], 'gst_no'); ?>SaleReport<?= time() ?>",
                    footer: true,
                    exportOptions: {
                        columns: []
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
    
    const ifCheque = () => {
        if ($('#payment_mode').val() == 6) {
            $('.Cheque').removeClass('d-none');
        } else {
            $('.Cheque').addClass('d-none');
        }
    };
    
    const onDeliveryInfoHandler = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/DeliveryInfo/" + Id);
        $('#Info').modal('show');
    };
</script>