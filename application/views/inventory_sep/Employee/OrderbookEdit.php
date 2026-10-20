<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <h3 class="m-0">Orderbook</h3>
                </div><!-- /.col -->
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Voucher</a></li>
                        <li class="breadcrumb-item active">Orderbook</li>
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
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-purple">
                                    Orderbook Edit
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/OrderbookReport" class="btn btn-xs bg-purple pull-right" title="Orderbook Report">
                                <i class="fa fa-file text-white"></i> Orderbook Report
                            </a>
                        </div>
                        <?php if (!empty($OrderBookId)) { ?>
                        <form action="<?= base_url(); ?>Employee/OrderbookUpdate/<?= $OrderBookId; ?>" method="POST" id="Orderbook">
                            <input type="hidden" name="OrderBookId" value="<?= set_value('OrderBookId', $OrderBook->Id); ?>" id="OrderBookId" />
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', $OrderBook->OrderDate); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                            <span class="text-danger"><?= form_error('Date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="PartyId">Party <span class="text-danger">*</span></label>
                                            <select name="PartyId" id="PartyId" class="form-control form-control-sm" required="" title="Party" onchange="SalesmanForOrderbook(this.value);">
                                                <option value="" selected="" disabled="">Select Party</option>
                                                <?php if ($Parties) { foreach ($Parties as $key => $Data) { ?>
                                                <option value="<?= $Data->id; ?>" <?= $OrderBook->PartyId == $Data->id ? "selected" : ""; ?> <?= set_select('PartyId', $Data->id)?>><?= $Data->name; ?>, <?= $Data->area; ?>, <?= $Data->contact; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger"><?= form_error('PartyId'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="VoucherType">Voucher Type <span class="text-danger">*</span></label>
                                            <select name="VoucherType" id="VoucherType" class="form-control form-control-sm" required="" title="Voucher Type">
                                                <option value="" <?= $OrderBook->VoucherType == "" ? "selected" : ""; ?> disabled="">Select Voucher</option>
                                                <option value="Sale" <?= $OrderBook->VoucherType == "Sale" ? "selected" : ""; ?> <?= set_select('VoucherType', 'Sale'); ?>>Sale</option>
                                                <option value="Estimate" <?= $OrderBook->VoucherType == "Estimate" ? "selected" : ""; ?> <?= set_select('VoucherType', 'Estimate'); ?>>Estimate</option>
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
                                                <thead class="bg-purple" style="position: sticky; top: 0; z-index: 99;">
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
                                                                <?= set_value('SubTotal', $OrderBook->SubTotal); ?>
                                                            </span>
                                                            <input type="hidden" name="SubTotal" value="<?= set_value('SubTotal', $OrderBook->SubTotal); ?>" id="SubTotal" />
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
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark', $OrderBook->Remark); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <button type="submit" class="btn btn-sm bg-purple">
                                        Make Changes
                                    </button>
                                    <a href="<?= base_url(); ?>Employee/OrderbookReport" class="btn btn-danger btn-sm">
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
    </section>
    <!-- /.content -->
 </div>

<?php include('OrderBookJs.php'); ?>