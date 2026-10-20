<style type="text/css">
    .form-group {
        margin-bottom: 0.5rem;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-blue card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-blue">
                                    Vestiges Report
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>vestige" class="btn btn-xs bg-blue pull-right" title="Vestige Create">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>vestige/reports" method="GET">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="from" value="<?= set_value('from', $this->input->get('from') ? $this->input->get('from') : date('Y-m-01')); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="to" value="<?= set_value('to', $this->input->get('to') ? $this->input->get('to') : date('Y-m-d')); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <button type="submit" class="btn btn-sm bg-blue">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>vestige/reports" class="btn btn-danger btn-sm" title="Refresh">
                                            <i class="fa fa-refresh text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="bg-blue">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white">Date</th>
                                                    <th class="text-white">Voucher</th>
                                                    <th class="text-white">Company</th>
                                                    <th class="text-white">Total</th>
                                                    <th class="text-white"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sr = 0;
                                                    $totalAmount = 0;
                                                    if (isset($vestiges) && !empty($vestiges)) {
                                                        foreach ($vestiges as $key => $data) { 
                                                            $sr++;
                                                            $totalAmount += (float) ($data->subTotal ?? 0);
                                                ?>
                                                <tr>
                                                    <td><?= $sr; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($data->date)); ?></td>
                                                    <td><?= $data->voucherNumber; ?></td>
                                                    <td><?= get_title('inventory_billing', ['id' => $data->compId], 'company_name'); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data->subTotal ?? 0); ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-primary btn-xs" onclick="onVestigeInfoHandler(<?= $data->id; ?>);" title="View">
                                                            <i class="fa fa-info-circle"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>vestige/print/<?= $data->id; ?>" class="btn btn-xs bg-navy" target="_blank" title="Print">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="onVestigeEditHandler(<?= $data->id; ?>);" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="onVestigeDeleteHandler(<?= $data->id; ?>);" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" style="text-align: right;">Sub Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount ?? 0); ?></th>
                                                    <th></th>
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
    <!-- /.content -->
 </div>

<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<?php include('vestigeJs.php'); ?>

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script> 