<style type="text/css">
    .card-body {
        padding: 0.25rem 0.25rem;
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
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-outline card-olive">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-olive">Order Form Edit</h5>
                            </div>
                            <a href="<?= base_url(); ?>Orderform" class="btn bg-cyan btn-xs pull-right" title="Orderform Report">
                                <i class="fa fa-list"></i> OrderForm Report
                            </a>
                        </div>
                        <?php if (!empty($OrderFormId)) { ?>
                        <form action="<?= base_url(); ?>Orderform/Update/<?= $OrderFormId; ?>" method="POST" id="Orderform">
                            <input type="hidden" name="OrderFormId" value="<?= set_value('OrderFormId', $OrderForm->Id); ?>" id="OrderFormId" />
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', $OrderForm->OrderDate); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                            <span class="text-danger"><?= form_error('Date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="PartyId">Party <span class="text-danger">*</span></label>
                                            <select name="PartyId" id="PartyId" class="form-control form-control-sm" required="" title="Party">
                                                <option value="" selected="" disabled="">Select Party</option>
                                                <?php if ($Parties) { foreach ($Parties as $key => $Data) { ?>
                                                <option value="<?= $Data->id; ?>" <?= $OrderForm->PartyId == $Data->id ? "selected" : ""; ?> <?= set_select('PartyId', $Data->id)?>><?= $Data->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger"><?= form_error('PartyId'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="VoucherType">Voucher Type <span class="text-danger">*</span></label>
                                            <select name="VoucherType" id="VoucherType" class="form-control form-control-sm" required="" title="Voucher Type">
                                                <option value="" <?= $OrderForm->VoucherType == "" ? "selected" : ""; ?> disabled="">Select Voucher</option>
                                                <option value="Sale" <?= $OrderForm->VoucherType == "Sale" ? "selected" : ""; ?> <?= set_select('VoucherType', 'Sale'); ?>>Sale</option>
                                                <option value="Estimate" <?= $OrderForm->VoucherType == "Estimate" ? "selected" : ""; ?> <?= set_select('VoucherType', 'Estimate'); ?>>Estimate</option>
                                            </select>
                                            <span class="text-danger"><?= form_error('VoucherType'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-purple" />
                                <div class="row" style="min-height: 250px; height: auto;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm ProductsDropDown">
                                                <thead class="bg-olive" style="position: sticky; top: 0; z-index: 99;">
                                                    <tr>
                                                        <th class="text-white">Sr.</th>
                                                        <th class="text-white" style="width: 45%;">Product Name</th>
                                                        <th class="text-white">Price</th>
                                                        <th class="text-white">Quantity</th>
                                                        <th class="text-white">Total</th>
                                                        <th class="text-white"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="4" style="text-align: right;">Sub Total</th>
                                                        <th colspan="2">
                                                            <i class="fa fa-inr"></i> 
                                                            <span id="SubTotalTxt">
                                                                <?= set_value('SubTotal', $OrderForm->SubTotal); ?>
                                                            </span>
                                                            <input type="hidden" name="SubTotal" value="<?= set_value('SubTotal', $OrderForm->SubTotal); ?>" id="SubTotal" />
                                                            <span class="text-danger"><?= form_error('SubTotal'); ?></span>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="SalesmanId">Salesman <span class="text-danger">*</span></label>
                                            <select name="SalesmanId" id="SalesmanId" class="form-control form-control-sm" title="Salesman" required="">
                                            </select>
                                            <span class="text-danger"><?= form_error('SalesmanId'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="Remark">Remark</label>
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark', $OrderForm->Remark); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <button type="submit" class="btn btn-sm bg-olive">
                                        Make Changes
                                    </button>
                                    <a href="<?= base_url(); ?>Orderform" class="btn btn-danger btn-sm">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                        <?php } ?>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('OrderFormJs.php'); ?>