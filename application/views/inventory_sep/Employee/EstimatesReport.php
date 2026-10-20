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
                    <h3 class="m-0">Estimates</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Estimates</a></li>
                        <li class="breadcrumb-item active">Estimate Report</li>
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
                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-danger">Estimate Report</h4>
                            </div>
                            <div class="pull-right">
                                <button type="button" title="Previous Month" id="pre" onclick="return PreMonth();" class="btn btn-primary btn-sm">
                                    <i class="fa fa-calendar-minus text-white"></i>
                                </button>
                                <button type="button" title="Current Month" id="currentMont" onclick="return curent();" class="btn btn-warning btn-sm">
                                    <i class="fa fa-calendar-check text-white"></i>
                                </button>
                                <button type="button" title="Next Month" id="nextMonth" onclick="return nextmonth();" class="btn btn-danger btn-sm">
                                    <i class="fa fa-calendar-plus text-white"></i>
                                </button>
                                <a href="<?= base_url(); ?>Employee/EstimatesReportGrid" title="Grid View" class="btn bg-maroon btn-sm">
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
                                                $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');

                                                $salesmans = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $SalesmanIds)->get('employees')->result();

                                                $salesman_selected = [];
                                                foreach (input_get_array('salesman') as $key => $sids) {
                                                    $salesman_selected[] = $sids;
                                                }
                                        ?>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="salesman">Salesman</label>
                                            <select name="salesman[]" id="salesman" class="form-control form-control-sm" multiple="multiple">
                                                <option <?= in_array('All', $salesman_selected) ? 'selected' : ''; ?> value="All" <?= set_select('salesman', 'All'); ?>>All</option>
                                                <?php if (!empty($salesmans)) { foreach ($salesmans as $key => $sdata) { ?>
                                                <option <?= in_array($sdata->Id, $salesman_selected) ? 'selected' : ''; ?> value="<?= $sdata->Id; ?>" <?= set_select('salesman', $sdata->Id); ?>><?= $sdata->Name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                        <?php } ?>                                    
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="fromDate">From Date</label>
                                            <input type="date" name="fromDate" onkeydown="firstRow(event);" id="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-2 col-sm-2">
                                            <label for="toDate">To Date</label>
                                            <input type="date" onkeydown="firstRow(event);" name="toDate" id="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" min="<?= $fy[0] . '-03-01'; ?>" max="<?= '20' . $fy[1] . '-03-31'; ?>" class="form-control form-control-sm" />
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4" style="margin-top: 31px;">
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
                                    <thead class="bg-danger">
                                        <tr role="row">
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
                                            <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') { ?>
                                            <th class="text-white">Salesman</th>
                                            <?php } ?>
                                            <th class="text-white">Delivery</th>
                                            <th class="text-white">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <form class="form_select" method="POST" id="form_select">
                                            <?php
                                                $TotalTaxableAmount = 0;
                                                $TotalTax = 0;
                                                $TotalAmount = 0;
                                                $PaidAmount = 0;
                                                $BalanceAmount = 0;
                                                if ($Estimates) {
                                                    foreach ($Estimates as $key => $row) {
                                                        $estimateCheck = get_title('sales', ['estimate_no' => $row->invoiceNumber], 'estimate_no');
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
                                                    <a href="<?= base_url(); ?>Employee/ViewEstimate/<?= $row->id; ?>" class="text-info" title="Invoice Detail">
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
                                                    <?php } } elseif ($row->paid_amount == NULL || $row->paid_amount == 0) { if (employee_perm($EmployeePermission, 'Payments', 'Create')) { ?>
                                                    <a href="<?= base_url(); ?>Employee/Payments/<?= $row->id; ?>" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-exclamation-triangle text-white"></i> Unpaid
                                                    </a>
                                                    <?php } else { ?>
                                                    <label class="btn btn-danger btn-xs"><i class="fa fa-exclamation-triangle text-white"></i> Unpaid</label>
                                                    <?php } } else { if (employee_perm($EmployeePermission, 'Payments', 'Create')) { if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") { if ($PaymentInfo->ChequeStatus == 0) { ?>
                                                    <label class="btn bg-maroon btn-xs">
                                                        <i class="fa fa-exclamation text-white"></i> UnPaid/PDC
                                                    </label>
                                                    <?php } elseif ($PaymentInfo->ChequeStatus == 1) { ?>
                                                    <a href="<?= base_url(); ?>Employee/Payments/<?= $row->id; ?>" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-exclamation-circle"></i> Partial
                                                    </a>
                                                    <?php } } else { ?>
                                                    <a href="<?= base_url(); ?>Employee/Payments/<?= $row->id; ?>" class="btn btn-warning btn-xs">
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
                                                <?php if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') { ?>
                                                <td><?= get_title('employees', ['Id' => get_title('parties', ['id' => $row->customerId], 'SalesmanId')], 'Name'); ?></td>
                                                <?php } ?>
                                                <td><?= $row->delivery_status == 1 ? "Yes" : "No"; ?></td>
                                                <td>
                                                    <?php if (employee_perm($EmployeePermission, 'SalesDeliveries', 'Edit')) { ?>
                                                    <a href="javascript:void(0);" onclick="onDeliveryInfoHandler(<?= $row->id; ?>);" title="Delivery">
                                                        <i class="fa fa-bus text-danger"></i>
                                                    </a>
                                                    &nbsp;
                                                    <?php } if (employee_perm($EmployeePermission, 'Estimates', 'Edit')) { ?>
                                                    <a href="<?= base_url(); ?>Employee/EstimateEdit/<?= $row->id; ?>" title="Edit">
                                                        <i class="fa fa-edit text-success"></i>
                                                    </a>
                                                    &nbsp;
                                                    <?php } if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Create')) { if ($row->paid_amount != $row->total) { ?>
                                                    <a href="javascript:void(0);" onclick="PaymentRequestForm(<?= $row->id; ?>);" title="Payment Request">
                                                        <i class="fa fa-money text-danger"></i>
                                                    </a>
                                                    &nbsp;
                                                    <?php } } ?>
                                                    <a href="<?= base_url(); ?>Employee/PrintEstimate/<?= $row->id; ?>" target="_blank" title="Print">
                                                        <i class="fa fa-print text-primary"></i>
                                                    </a>
                                                    &nbsp;
                                                    <?php if (empty($estimateCheck)) { ?>
                                                    <a href="<?= base_url(); ?>Employee/EstimateToSale/<?= $row->id; ?>" title="Create Sale Invoice"> 
                                                        <i class="fa fa-shopping-bag text-navy"></i>
                                                    </a>
                                                    <?php } if (employee_perm($EmployeePermission, 'Estimates', 'Delete')) { ?>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>Employee/DeleteEstimate/<?= $row->id; ?>" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript">
    const PaymentRequestForm = (Id) => {
        jQuery.noConflict();
        $("#PaymentRequest .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/PaymentRequestForm/" + Id);
        $("#PaymentRequest").modal("show");
    };
    
    $('#salesman').multiselect({
        buttonWidth: 'auto'
    });
    
    $('#resetbtn').click(function(e) {
        window.location.href = "<?= base_url(); ?>Employee/EstimatesReport";
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
        window.location.href = "<?= base_url(); ?>Employee/EstimatesReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Employee/EstimatesReport?" + form;
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
        window.location.href = "<?= base_url(); ?>Employee/EstimatesReport?" + form;
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