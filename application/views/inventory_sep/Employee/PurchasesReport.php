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
</style>

 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Purchases</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Reports</a></li>
                        <li class="breadcrumb-item active">Purchase Report</li>
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
                            <div class="row">
                                <div class="col-md-4">
                                    <h4 class="card-title text-maroon">Purchases Report</h4>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" title="Previous Month" onclick="return PreMonth();" class="btn btn-primary btn-sm">
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
                                </div>
                            </div>
                            <form action="" id="reportForm" onsubmit="onFormSubmitHandler(event);" method="GET">
                                <div class="row">
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
                                    <div class="form-group col-md-3 col-sm-3" style="margin-top: 31px;">
                                        <button type="submit" class="btn btn-primary btn-sm" onClick="return search();" onkeydown="firstRow(event);">
                                            Search
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" title="Refresh" id="resetbtn">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm d-none" title="Excel File" id="excelExport" >
                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="divas" class="table-responsive">
                                <table class="table table-striped table-bordered table-sm">
                                    <?php if ($this->input->get('estimate') == "") { ?>
                                    <thead class="bg-maroon">
                                        <tr>
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
                                            <th>Payment</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>#</th>
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
                                                if ($Purchases) {
                                                    foreach ($Purchases as $key => $row) {
                                                        $TotalTaxableAmount += $row->taxableAmount;
                                                        $TotalTax += $row->totalTax;
                                                        $TotalAmount += $row->total;
                                                        $PaidAmount += $row->paid_amount;
                                                        $BalanceAmount += ($row->total - $row->paid_amount);
                                            ?>
                                            <tr>
                                                <td><?= $key + 1; ?></td>
                                                <td>
                                                    <a href="<?= base_url(); ?>Purchases/ViewPurchases/<?= $row->id; ?>" class="text-primary" title="Invoice Detail">
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
                                                    <?php if ($row->paid_amount == $row->total) { ?>
                                                    <label class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> Paid</label>
                                                    <?php } elseif ($row->paid_amount == NULL || $row->paid_amount == 0) { if (employee_perm($EmployeePermission, 'Payments', 'Create')) { ?>
                                                    <a href="<?= base_url(); ?>Employee/Payments/<?= $row->id; ?>" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-exclamation-triangle"></i> Unpaid
                                                    </a>
                                                    <?php } else { ?>
                                                    <label class="btn btn-danger btn-xs"><i class="fa fa-exclamation-triangle"></i> Unpaid</label>
                                                    <?php } } else { if (employee_perm($EmployeePermission, 'Payments', 'Create')) { ?>
                                                    <a href="<?= base_url(); ?>Employee/Payments/<?= $row->id; ?>" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-exclamation-circle"></i> Partial
                                                    </a>
                                                    <?php } else { ?>
                                                    <label class="btn btn-warning btn-xs"><i class="fa fa-exclamation-circle"></i> Partial</label>
                                                    <?php } } ?>
                                                </td>
                                                <td><i class="fa fa-inr"></i> <?= number_format($row->paid_amount); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= number_format($row->total - $row->paid_amount); ?></td>
                                                <td>
                                                    <?php if (employee_perm($EmployeePermission, 'Purchases', 'Edit')) { ?>
                                                    <a href="<?= base_url(); ?>Employee/PurchasesEdit/<?= $row->id; ?>" class="text-success" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    &nbsp;
                                                    <?php } ?>
                                                    <a href="<?= base_url(); ?>Employee/PrintPurchases/<?= $row->id; ?>" class="text-primary" target="_blank" title="Print">
                                                        <i class="fa fa-print"></i>
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
                                        </tr>
                                    </tfoot>
                                    <?php } elseif ($this->input->get('estimate') == true) { ?>
                                    <thead class="bg-maroon">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Type</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Party Name</th>
                                            <th>Mobile</th>
                                            <th>GST No</th>
                                            <th>Payment Mode</th> 
                                            <th>Taxable Amount</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Paid Amount</th>
                                            <th>Balance</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $TotalTaxableAmount = 0;
                                            $TotalTax = 0;        
                                            $TotalAmount = 0;
                                            $PaidAmount = 0;
                                            $BalanceAmount = 0;
                                            
                                            $from = $this->input->get('fromDate');
                                            $to = $this->input->get('toDate');
                                            $EmployeeId = $this->session->userdata('EmployeeId');
                                            $user_id = $this->session->userdata('UserId');
                                            $comp_id = $this->session->userdata('CompId');
                                            $status = 1;
                                            $financial_year = $this->session->userdata('financial_year');

                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, taxableAmount, totalTax, total, type, paid_amount');
                                            $this->db->from('purchases');
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            $this->db->where('EmployeeId', $EmployeeId);
                                            if ($this->session->userdata('BranchRole')) {
                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                            }
                                            $this->db->where('status', $status);
                                            $this->db->where('financial_year', $financial_year);
                                            $query1 = $this->db->get_compiled_select();
                                            
                                            $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, taxableAmount, totalTax, total, type, paid_amount');
                                            $this->db->from('estimate_purchase');                                            
                                            $this->db->where('invoiceDate >=', $from);
                                            $this->db->where('invoiceDate <=', $to);
                                            $this->db->where('user_id', $user_id);
                                            $this->db->where('comp_id', $comp_id);
                                            $this->db->where('EmployeeId', $EmployeeId);
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
                                        ?>
                                        <tr>
                                            <td><?= $n; ?></td>
                                            <td><?= $data->type; ?></td>
                                            <td>
                                                <?php if ($data->type == "Purchase") { ?>
                                                <a href="<?= base_url(); ?>Employee/ViewPurchases/<?= $data->id; ?>" class="text-info" title="Invoice Detail">
                                                    <?= $data->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($data->type == "Estimate Purchase") { ?>
                                                <a href="<?= base_url(); ?>Employee/ViewEstimatePurchase/<?= $data->id; ?>" class="text-info" title="Invoice Detail">
                                                    <?= $data->invoiceNumber; ?>
                                                </a>                                                
                                                <?php } ?>
                                            </td>
                                            <td><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                                            <td><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                                            <td><?= $data->customerMobile; ?></td>
                                            <td><?= $data->gstNo; ?></td>
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
                                                <?php if ($data->paid_amount == $data->total) { ?>
                                                <label class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> Paid</label>
                                                <?php } elseif ($data->paid_amount == NULL || $data->paid_amount == 0) { if (employee_perm($EmployeePermission, 'Payments', 'Create')) { ?>
                                                <a href="<?= base_url(); ?>Employee/Payments/<?= $data->id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-exclamation-triangle"></i> Unpaid
                                                </a>
                                                <?php } else { ?>
                                                <label class="btn btn-danger btn-xs"><i class="fa fa-exclamation-triangle"></i> Unpaid</label>
                                                <?php } } else { if (employee_perm($EmployeePermission, 'Payments', 'Create')) { ?>
                                                <a href="<?= base_url(); ?>Employee/Payments/<?= $data->id; ?>" class="btn btn-warning btn-xs">
                                                    <i class="fa fa-exclamation-circle"></i> Partial
                                                </a>
                                                <?php } else { ?>
                                                <label class="btn btn-warning btn-xs"><i class="fa fa-exclamation-circle"></i> Partial</label>
                                                <?php } } ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($data->paid_amount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($data->total - $data->paid_amount); ?></td>
                                            <td>
                                                <?php if ($data->type == "Purchases") { ?>
                                                <a href="<?= base_url(); ?>Employee/PrintPurchases/<?= $data->id; ?>" class="text-primary" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                &nbsp;
                                                <?php } elseif ($data->type == "Estimate Purchase") { ?>
                                                <a href="<?= base_url(); ?>Employee/PrintEstimatePurchase/<?= $data->id; ?>" class="text-primary" target="_blank" title="Print">
                                                    <i class="fa fa-print"></i>
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
                                                <i class="fa fa-inr"></i> <?= number_format($BalanceAmount); ?>
                                            </th>
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

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript">

    $('#resetbtn').click(function(e) {
        window.location.href = "<?= base_url(); ?>Employee/PurchasesReport";
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
        window.location.href = "<?= base_url(); ?>Employee/PurchasesReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Employee/PurchasesReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Employee/PurchasesReport?" + form;
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
</script>