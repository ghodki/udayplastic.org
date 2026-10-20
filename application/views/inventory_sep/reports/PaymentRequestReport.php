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
    .request-data td, .request-data th {
        padding: 0.3rem;
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
                        <li class="breadcrumb-item active">Payment Request</li>
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4 class="card-title">
                                        Payment Request
                                    </h4>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-danger btn-xs" title="Pending Request">
                                        <i class="fa fa-bell text-white"></i> Pending Request : 
                                        <?php
                                            $Where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                            if ($this->session->userdata('BranchRole')) {
                                                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                            }
                                            echo $this->db->where($Where)->where('status', 0)->get('paymentrequest')->num_rows(); 
                                        ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" id="FormData">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="Salesman">Salesman</label>
                                            <select name="Salesman" id="Salesman" class="form-control form-control-sm" title="Salesman">
                                                <option <?= $this->input->get('Salesman') == "All" ? "selected" : ""; ?> value="All">All</option>
                                                <?php if (isset($Salesmans) && !empty($Salesmans)) { foreach ($Salesmans as $key => $Salesman) { ?>
                                                <option <?= $this->input->get('Salesman') == $Salesman->Id ? "selected" : ""; ?> value="<?= $Salesman->Id; ?>"><?= $Salesman->Name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
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
                                            <span class="text-danger"><?= form_error('date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-5" style="margin-top: 31px;">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-xs">
                                                <i class="fa fa-search text-white"></i> Search
                                            </button>
                                            <a href="<?= base_url(); ?>Reports/PaymentRequestReport" class="btn btn-danger btn-xs" title="Refresh">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm request-table">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Date</th>
                                                    <th>Invoice Number</th>
                                                    <th>Party</th>
                                                    <th>Payment Date</th>
                                                    <th>Mode</th>
                                                    <th>Amount</th>
                                                    <th>Salesman</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $n = 0;
                                                    $TotalAmount = 0;
                                                    if (!empty($PaymentRequest)) {
                                                        foreach ($PaymentRequest as $key => $data) { 
                                                            $n++; 
                                                            $TotalAmount += (float) ($data->payment_amount ?? 0);
                                                ?>
                                                <tr>
                                                    <td><?= $n; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($data->date)); ?></td>
                                                    <td>
                                                        <?php 
                                                            if (is_array(json_decode($data->invoiceNumber))) {
                                                                $InvoiceNumber = "";
                                                                foreach (json_decode($data->invoiceNumber) as $key => $InvoceData) {
                                                                    $InvoiceNumber .= $InvoceData . ", ";
                                                                }
                                                                echo substr(trim($InvoiceNumber), 0, -1);
                                                            } else {
                                                                echo $data->invoiceNumber;
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?= get_title('parties', ['id' => $data->party_id], 'name'); ?></td>
                                                    <td><?= date('d-m-Y', strtotime($data->payment_date)); ?></td>
                                                    <td><?= get_title('payment_mode', ['id' => $data->payment_mode], 'payment_method'); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data->payment_amount); ?></td>
                                                    <td><?= get_title('employees', ['Id' => $data->SalesmanId], 'Name'); ?></td>
                                                    <td>
                                                        <?php if ($data->status == 0) { ?>
                                                        <a href="javascript:void(0);" onclick="getRequestInfo(<?= $data->id; ?>);" class="btn btn-warning btn-xs" title="Information">
                                                            <i class="fa fa-warning"></i> Pending
                                                        </a>
                                                        <?php } elseif ($data->status == 1) { ?>
                                                        <a href="javascript:void(0);" onclick="getRequestInfo(<?= $data->id; ?>);" class="btn btn-success btn-xs" title="Information">
                                                            <i class="fa fa-check-circle text-white"></i> Approved
                                                        </a>
                                                        <?php } elseif ($data->status == 2) { ?>
                                                        <a href="javascript:void(0);" onclick="getRequestInfo(<?= $data->id; ?>);" class="btn btn-danger btn-xs" title="Information">
                                                            <i class="fa fa-ban text-white"></i> Rejected
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="6" style="text-align: right;">Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($TotalAmount, 2); ?></th>
                                                    <th colspan="2"></th> 
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

<!-- request data -->
<div class="modal fade" id="requestdata" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"></div>
    </div>
</div>
<!-- request data end -->

<script type="text/javascript">
    $(function() {
        $(".request-table").DataTable({
            dom: 'lBfrtip',
            lengthMenu: [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
            buttons: []
        });
    });
    
    const getRequestInfo = (Id) => {
        jQuery.noConflict();
        $("#requestdata .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/PaymentRequestData/" + Id);
        $("#requestdata").modal("show");
    };
    
    const calculatePaidAmount = () => {
        var receivedPaidAmount = $("#receivedPaidAmount").val();
        var cashDiscountAmount = $("#cashDiscountAmount").val();
        var TotalPaidAmount = Number(receivedPaidAmount) + Number(cashDiscountAmount);
        $("#Amount").val(parseFloat(TotalPaidAmount).toFixed(2));
    };
    
    function onCheckPaymentEntry () {
        var PaymentDate = $("#PaymentDate").val();
        var PartyId = $("#PartyId").val();
        var receivedPaidAmount = $("#receivedPaidAmount").val();
        var Amount = $("#Amount").val();
        var Status = $(".Status").val();

        if (PaymentDate && PartyId && receivedPaidAmount && Amount) {
            return $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>Reports/CheckPaymentEntryFromPaymentRequest",
                data    :   {PaymentDate: PaymentDate, PartyId: PartyId, receivedPaidAmount: receivedPaidAmount, Amount: Amount, Status: Status},
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
    }
    
    const onPaymentRequestFromSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var PartyId = $("#PartyId").val();
        var PaymentDate = $("#PaymentDate").val();
        var receivedPaidAmount = $("#receivedPaidAmount").val();
        var Amount = $("#Amount").val();
        
        if (PartyId && PaymentDate && receivedPaidAmount && Amount) {
            var PaymentEntry = JSON.parse(onCheckPaymentEntry()).Data;
            if (PaymentEntry == 'Unique') {
                $.ajax({
                    type    :   "POST",
                    url     :   "<?= base_url(); ?>Reports/PaymentUpdate",
                    data    :   $("#PaymentRequest").serialize(),
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
                                            window.location.reload();
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
                    title: 'Duplicate Data Detected',
                    text: 'The data you are about to submit already exists. Do you want to proceed?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type    :   "POST",
                            url     :   "<?= base_url(); ?>Reports/PaymentUpdate",
                            data    :   $("#PaymentRequest").serialize(),
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
                                                    window.location.reload();
                                                }, 2000);
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
    
    const onPartyPaymentStatement = () => {
        var PartyId = $("#PartyId").val();

        if (PartyId != null && PartyId != '') {
            $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>Reports/PartyPaymentStatement",
                data    :   {PartyId: PartyId},
                success :   function(Response) {
                                if (JSON.parse(Response).Status == true) {
                                    $('#PartyPaymentStatementData').html(JSON.parse(Response).Data);
                                } else {
                                    $('#PartyPaymentStatementData').html(JSON.parse(Response).Msg);
                                }
                            }
            });
        }
    };
</script>