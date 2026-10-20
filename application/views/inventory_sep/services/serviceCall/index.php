<?php
    $Sr = 0;
    $totalAmount = 0;
?>
<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .select2-container {
        display: block;
    }    
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12">
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="title text-purple">
                                    Service Call
                                </h3>
                            </div>
                            <a href="<?= base_url(); ?>services/serviceCallCreate" class="btn bg-purple btn-sm pull-right" title="Create">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <form method="GET" action="<?= base_url(); ?>services/serviceCall">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <input type="date" name="fromDate" value="<?= $this->input->get('fromDate') ? $this->input->get('fromDate') : date('Y-m-01'); ?>" id="fromDate" class="form-control form-control-sm" title="From Date" />
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <input type="date" name="toDate" value="<?= $this->input->get('toDate') ? $this->input->get('toDate') : date('Y-m-d'); ?>" id="toDate" class="form-control form-control-sm" title="To Date" />
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn bg-purple btn-sm">
                                                        Search
                                                    </button>
                                                    <a href="<?= base_url(); ?>services/serviceCall" class="btn btn-danger btn-sm" title="Refresh">
                                                        Reset
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>                                    
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-purple">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Bill Number</th>
                                                    <th>Installation</th>
                                                    <th>Manual Report</th>
                                                    <th>Customer Name</th>
                                                    <th>Contact No</th>
                                                    <th>Created</th>
                                                    <th>Amount</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    if (isset($serviceCalls) && !empty($serviceCalls)) {
                                                        foreach ($serviceCalls as $key => $data) {
                                                            $Sr++;
                                                            $totalAmount += (float) ($data->grandTotalAmount ?? 0);
                                                ?>
                                                <tr>
                                                    <td><?= $Sr; ?></td>
                                                    <td><?= $data->billNumber; ?></td>
                                                    <td><?= $data->installationReportNo; ?></td>
                                                    <td><?= $data->manualReportNo; ?></td>
                                                    <td><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                                                    <td><?= get_title('parties', ['id' => $data->customerId], 'contact'); ?></td>
                                                    <td><?= $data->created; ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data->grandTotalAmount ?? 0); ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" onclick="serviceCallInfo(<?= $data->id; ?>);" class="btn bg-navy btn-xs" title="Info">
                                                            <i class="fa fa-info-circle text-white"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>services/serviceCallPrint/<?= $data->id; ?>" class="btn btn-success btn-xs" title="Print" target="_blank">
                                                            <i class="fa fa-print text-white"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>services/serviceCallDelete/<?= $data->id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                            <i class="fa fa-trash text-white"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7" style="text-align: right;">Total</th>
                                                    <th colspan="2"><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount ?? 0); ?></th>
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
    </div>
 </div>

<!--Info-->
<div class="modal fade" id="serviceCallInfo" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });
    
    const serviceCallInfo = (serviceCallId) => {
        $("#serviceCallInfo .modal-dialog .modal-content").load("<?= base_url(); ?>services/serviceCallInfo/" + serviceCallId);
        $('#serviceCallInfo').modal('show');
    };
</script>