<style type="text/css">
    .content-header {
        padding: 5px 5px 0px 0px;
    }
    .content-wrapper>.content {
        padding: 0 0rem;
    }
    label:not(.form-check-label):not(.custom-file-label) {
        font-weight: 600;
    }
    .card-body {
        padding: 0.25rem;
    }
    .dataTables_wrapper .dataTables_length {
        font-size: 16px;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Payment Request</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Reports</a></li>
                        <li class="breadcrumb-item active">Payment Request Report</li>
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
                <div class="col-md-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12">
                    <div class="card card-navy card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-navy">
                                    Payment Request Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" id="FormData">
                                <div class="row">
                                    <div class="col-md-1 col-lg-1 col-sm-1">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control form-control-sm" title="Status Type">
                                                <option <?= $this->input->get('status') == "All" ? "selected" : ""; ?> value="All">All</option>
                                                <option <?= $this->input->get('status') == "0" ? "selected" : ""; ?> value="0">Pending</option>
                                                <option <?= $this->input->get('status') == "1" ? "selected" : ""; ?> value="1">Approved</option>
                                                <option <?= $this->input->get('status') == "2" ? "selected" : ""; ?> value="2">Rejected</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="from">From</label>
                                            <input type="date" name="from" value="<?= !empty($this->input->get('from')) ? $this->input->get('from') : date('Y-m-d'); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="to">To</label>
                                            <input type="date" name="to" value="<?= !empty($this->input->get('to')) ? $this->input->get('to') : date('Y-m-d'); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6" style="margin-top: 31px;">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-xs">
                                                <i class="fa fa-search text-white"></i> Search
                                            </button>
                                            <a href="<?= base_url(); ?>Employee/PaymentRequestReport" class="btn btn-danger btn-xs" title="Refresh">
                                                <i class="fa fa-refresh text-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="bg-navy">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white">Date</th>
                                                    <th class="text-white">Type</th>
                                                    <th class="text-white">Invoice Number</th>
                                                    <th class="text-white">Party</th>
                                                    <th class="text-white">Payment Date</th>
                                                    <th class="text-white">Mode</th>
                                                    <th class="text-white">Amount</th>
                                                    <th class="text-white">Status</th>
                                                    <th class="text-white">Salesman</th>
                                                    <td class="text-white"></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $n = 0;
                                                    $TotalAmount = 0;
                                                    if (!empty($PaymentsRequests)) {
                                                        foreach ($PaymentsRequests as $key => $data) {
                                                            $n++;
                                                            $TotalAmount += (float) ($data->payment_amount ?? 0);
                                                            $requestTypes = json_decode($data->Type ?? '', true);
                                                            if (!is_array($requestTypes)) {
                                                                $requestTypes = [];
                                                            }
                                                            $invoiceNumbers = json_decode($data->invoiceNumber ?? '', true);
                                                            if (!is_array($invoiceNumbers)) {
                                                                $invoiceNumbers = [];
                                                            }
                                                ?>
                                                <tr>
                                                    <td><?= $n; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($data->date)); ?></td>
                                                    <td>
                                                        <?php
                                                            if (!empty($requestTypes)) {
                                                                if (in_array('Sale', $requestTypes)) {
                                                                    echo "Sale";
                                                                }
                                                                if (in_array('Estimate', $requestTypes)) {
                                                                    echo "Estimate";
                                                                }
                                                            } else {
                                                                echo $data->Type;
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            if (!empty($invoiceNumbers)) {
                                                                $InvoiceNumber = "";
                                                                foreach ($invoiceNumbers as $key => $InvoceData) {
                                                                    $InvoiceNumber .= $InvoceData . ", ";
                                                                }
                                                                echo rtrim($InvoiceNumber, ", ");
                                                            } else {
                                                                echo $data->invoiceNumber;
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?= get_title('parties', ['id' => $data->party_id], 'name'); ?></td>
                                                    <td><?= date('d-m-Y', strtotime($data->payment_date)); ?></td>
                                                    <td><?= get_title('payment_mode', ['id' => $data->payment_mode], 'payment_method'); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= number_format((float) ($data->payment_amount ?? 0), 2); ?></td>
                                                    <td>
                                                        <?php if ($data->status == 0) { ?>
                                                        <label class="btn btn-warning btn-xs"><i class="fa fa-warning"></i> Pending</label>
                                                        <?php } elseif ($data->status == 1) { ?>
                                                        <label class="btn btn-success btn-xs"><i class="fa fa-check-circle text-white"></i> Approved</label>
                                                        <?php } elseif ($data->status == 2) { ?>
                                                        <label class="btn btn-danger btn-xs"><i class="fa fa-ban text-white"></i> Rejected</label>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?= get_title('employees', ['id' => $data->SalesmanId], 'Name'); ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" onclick="getRequestInfo(<?= $data->id; ?>);" class="btn btn-primary btn-xs" title="Information">
                                                            <i class="fa fa-info-circle text-white"></i>
                                                        </a>
                                                        &nbsp;
                                                        <?php if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Edit')) { ?>
                                                        <a href="javascript:void(0);" onclick="getRequestEdit(<?= $data->id; ?>);" class="btn btn-success btn-xs" title="Edit">
                                                            <i class="fa fa-edit text-white"></i>
                                                        </a>
                                                        &nbsp;
                                                        <?php } if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Delete')) { ?>
                                                        <a href="<?= base_url(); ?>Employee/paymentRequestDelete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                            <i class="fa fa-trash text-white"></i>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7" style="text-align: right;">Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= number_format((float) $TotalAmount, 2); ?></th>
                                                    <th colspan="3"></th> 
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
 </div>

<!-- salesman request data -->
<div class="modal fade" id="requestdata" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"></div>
    </div>
</div>
<!-- salesman request data end -->

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'lBfrtip',
            lengthMenu: [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
            buttons: []
        });
    });
    
    const getRequestInfo = (Id) => {
        jQuery.noConflict();
        $("#requestdata .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/PaymentRequestData/" + Id);
        $("#requestdata").modal("show");
    };
    
    const getRequestEdit = (Id) => {
        jQuery.noConflict();
        $("#requestdata .modal-dialog .modal-content").load("<?= base_url(); ?>Employee/PaymentRequestEdit/" + Id);
        $("#requestdata").modal("show");
    };
</script>