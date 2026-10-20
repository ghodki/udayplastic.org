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
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-maroon">
                                    Stock Manufacturer Report
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>StockManufacturer" class="btn btn-xs bg-maroon pull-right" title="Stock Manufacturer Create">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>StockManufacturer/Reports" method="GET">
                                <div class="row">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="From" value="<?= set_value('From', $this->input->get('From') ? $this->input->get('From') : date('Y-m-d')); ?>" id="From" class="form-control form-control-sm" title="From" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="To" value="<?= set_value('To', $this->input->get('To') ? $this->input->get('To') : date('Y-m-d')); ?>" id="To" class="form-control form-control-sm" title="To" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <button type="submit" class="btn btn-sm bg-maroon">
                                            <i class="fa fa-search text-white"></i>
                                        </button>
                                        <a href="<?= base_url(); ?>StockManufacturer/Reports" class="btn btn-default btn-sm" title="Refresh">
                                            <i class="fa fa-refresh text-dark"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="bg-maroon">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Date</th>
                                                    <th>Voucher</th>
                                                    <th>Company</th>
                                                    <th>Total</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $Sr = 0;
                                                    $TotalAmount = 0;
                                                    if (isset($StockManufacturers) && !empty($StockManufacturers)) {
                                                        foreach ($StockManufacturers as $key => $Data) { 
                                                            $Sr++;
                                                            $TotalAmount += (float) ($Data->SubTotal ?? 0);
                                                ?>
                                                <tr>
                                                    <td><?= $Sr; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($Data->Date)); ?></td>
                                                    <td><?= $Data->VoucherNumber; ?></td>
                                                    <td><?= get_title('inventory_billing', ['id' => $Data->CompId], 'company_name'); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= number_format((float) ($Data->SubTotal ?? 0), 2); ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-primary btn-xs" onclick="onStockManufacturerInfoHandler(<?= $Data->Id; ?>);" title="View">
                                                            <i class="fa fa-info-circle"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>StockManufacturer/Print/<?= $Data->Id; ?>" class="btn btn-xs bg-navy" target="_blank" title="Print">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                        <a href="<?= base_url(); ?>StockManufacturer/barcodePrint/<?= $Data->Id; ?>" class="btn btn-xs bg-fuchsia" target="_blank" title="Barcode Print">
                                                            <i class="fa fa-barcode"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="onStockManufacturerEditHandler(<?= $Data->Id; ?>);" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="onStockManufacturerDeleteHandler(<?= $Data->Id; ?>);" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" style="text-align: right;">Sub Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= number_format($TotalAmount ?? 0, 2); ?></th>
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

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<?php include('StockManufacturerJs.php'); ?>

<script type="text/javascript">
    $(function() {
        $(".table").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[20, 50, 100, 500, -1], [20, 50, 100, 500, "All"]],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script> 