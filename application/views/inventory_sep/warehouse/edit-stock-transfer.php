<style type="text/css">
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 33px;
    }
    div.product_data_append {
        position: absolute;
        top: 40px;
        left: 0px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
                <?php } ?>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-header-sticky">
                        <div class="card-header">
                            <h3 class="card-title">Update Stock Transfer</h3>
                            <div class="pull-right">
                                <a href="<?= base_url(); ?>warehouse/report" class="btn btn-outline-info btn-xs" title="Stock transfer report">
                                    <i class="fa fa-file-alt"></i> Report
                                </a>
                            </div>
                        </div>
                    </div>
                    <form action="<?= base_url('warehouse/update_stock_transfer'); ?>" method="POST" autocomplete="off">
                        <div class="card-boby p-1">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-1 col-lg-1 col-sm-1">
                                            <div class="form-group">
                                                <label for="transfer_id">Transfer Id <span class="text-danger">*</span></label>
                                                <input type="text" name="transfer_id" value="<?= set_value('transfer_id', $transfer_id); ?>" id="transfer_id" class="form-control form-control-sm" placeholder="Transfer Id" readonly="" required="" />
                                                <span class="text-danger"><?= form_error('transfer_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-lg-1 col-sm-1">
                                            <div class="form-group" style="margin-top: 32px;">
                                                <button type="button" class="btn btn-outline-primary btn-xs" id="enable" title="Enable">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2 col-sm-2">
                                            <label for="transfer_type">Transfer Type <span class="text-danger">*</span></label>
                                            <select name="transfer_type" id="transfer_type" class="form-control form-control-sm" title="Transfer Type" required="">
                                                <option <?= set_select('transfer_type', 'Estimate'); ?> value="Estimate">Estimate</option>
                                                <option <?= set_select('transfer_type', 'Tax'); ?> value="Tax">Tax</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-lg-1 col-sm-1">
                                            <div class="form-group">
                                                <label for="transfer_date">Transfer Date <span class="text-danger">*</span></label>
                                                <input type="date" name="transfer_date" value="<?= set_value('transfer_date', date('Y-m-d')); ?>" id="transfer_date" class="form-control form-control-sm" placeholder="Transfer Date" required="" />
                                                <span class="text-danger"><?= form_error('transfer_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <div class="form-group">
                                                <label for="warehouse_form">Warehouse From <span class="text-danger">*</span></label>
                                                <select name="warehouse_form" id="warehouse_form" class="form-control form-control-sm select" title="Warehouse From" onchange="getWarehouse();" required="">
                                                    <option selected="" disabled="" value="">Select Warehouse From</option>
                                                </select>
                                                <span class="text-danger"><?= form_error('warehouse_form'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-lg-1 col-sm-1">
                                            <div class="form-group" style="margin-top: 32px;">
                                                <button type="button" class="btn btn-outline-info btn-xs" title="Create" onclick="createWarehouse();">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-xs" title="Edit" onclick="editWarehouse();">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-sm-3">
                                            <div class="form-group">
                                                <label for="warehouse_to">Warehouse To <span class="text-danger">*</span></label>
                                                <select name="warehouse_to" id="warehouse_to" class="form-control form-control-sm select_1" title="Warehouse To" required="">
                                                    <option selected="" disabled="" value="">First Select Warehouse From</option>
                                                </select>
                                                <span class="text-danger"><?= form_error('warehouse_to'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12" style="width:100%; min-height: 350px; height: auto;">
                                    <table class="table table-bordered table-sm" id="ProductTable">
                                        <thead class="bg-dark" style="position: sticky; top: 0; z-index: 99;">
                                            <tr>
                                                <th class="text-white" style="width: 50px;">Sr</th>
                                                <th class="text-white" style="width: 150px;">P Code</th>
                                                <th class="text-white" style="width: 300px;">Product Name</th>
                                                <th class="text-white" style="width: 200px;">Description</th>
                                                <th class="text-white" style="width: 150px;">Unit</th>
                                                <th class="text-white" style="width: 150px;">HSN</th>
                                                <th class="text-white" style="width: 100px;">Available Qty</th>
                                                <th class="text-white" style="width: 100px;">Quantity</th>
                                                <th class="text-white" style="width: 100px;">Rate</th>
                                                <th class="text-white" style="width: 100px;">Total</th>
                                                <th class="text-white" style="width: 80px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-6">
                                    <label for="amount_word">Amount in words <span class="text-danger">*</span></label>
                                    <input type="text" name="amount_word" value="<?= set_value('amount_word'); ?>" id="amount_word" class="form-control form-control-sm" placeholder="Amount in words" readonly="" required="" />
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-6">
                                    <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="total_amount" value="<?= set_value('total_amount'); ?>" id="total_amount" class="form-control form-control-sm" placeholder="Total Amount" readonly="" required="" />
                                </div>
                                <input type="hidden" name="roundoff" value="" id="roundoff" />
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-sm-8">
                                    <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-save"></i> Transfer Stock
                                    </button>
                                    <?php } else { ?>
                                    <span class="text-danger">Your license has been expired,<br />Please contact your administrator.</span>
                                    <?php } } else { ?>
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-save"></i> Transfer Stock
                                    </button>
                                    <?php } ?>
                                    <button type="submit" class="btn btn-outline-warning btn-sm">
                                        <i class="fa fa-check-circle"></i> Update
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="return deleteTransfer();">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="printTransfer();">
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                    <button type="reset" class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-refresh"></i> Reset
                                    </button>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-4">
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="return preview();">
                                            <i class="fa fa-angle-double-left"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="return next();">
                                            Next <i class="fa fa-angle-double-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Warehouse -->
<div class="modal fade" id="edit_warehouse" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content edit"></div>
    </div>
</div>
<!-- Edit Warehouse End -->

<!-- Create Warehouse -->
<div class="modal fade" id="create_warehouse" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content create"></div>
    </div>
</div>
<!-- Create Warehouse End -->

<!-- edit product -->
<div class="modal fade" id="edit_product" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1300px;">
        <div class="modal-content product"></div>
        <input type="hidden" name="rownumber" id="rownumber" />
    </div>
</div>
<!-- edit product End -->

<script type="text/javascript">
    $(document).ready(function() {
        $('.select').select2();
        $('.select_1').select2();
        
        document.body.style.zoom = 0.8;
        jQuery.noConflict();
        
        var transfer_id = document.getElementById('transfer_id').value;
        getStockTransferInfo(transfer_id);
    });
</script>

<?php $this->load->view('inventory_sep/warehouse/commonjs.php'); ?>