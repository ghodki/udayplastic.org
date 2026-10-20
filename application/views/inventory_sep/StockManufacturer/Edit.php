<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
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
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-purple">
                                    Stock Manufacturer Edit
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>StockManufacturer/Reports" class="btn btn-xs bg-purple pull-right" title="Stock Manufacturer Report">
                                <i class="fa fa-list"></i> Stock Manufacturer Report
                            </a>
                        </div>
                        <?php if (!empty($StockManufacturerId)) { ?>
                        <form method="POST" id="StockManufacturerUpdate" onsubmit="onStockManufacturerFormSubmitUpdateHandler(event);">
                            <input type="hidden" name="StockManufacturerId" value="<?= set_value('StockManufacturerId', $StockManufacturerData->Id); ?>" id="StockManufacturerId" />
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', $StockManufacturerData->Date); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="VoucherNumber">Voucher Number <span class="text-danger">*</span></label>
                                            <input type="text" name="VoucherNumber" value="<?= set_value('VoucherNumber', $StockManufacturerData->VoucherNumber); ?>" id="VoucherNumber" class="form-control form-control-sm" placeholder="Voucher Number" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="CompId">Company <span class="text-danger">*</span></label>
                                            <select name="CompId" id="CompId" class="form-control form-control-sm" required="" title="Company">
                                                <option value="" <?= $StockManufacturerData->CompId == "" ? "selected" : ""; ?> disabled="">Select Company</option>
                                                <?php if ($Company) { foreach ($Company as $key => $Data) { ?>
                                                <option value="<?= $Data->id; ?>" <?= $StockManufacturerData->CompId == $Data->id ? "selected" : ""; ?> <?= set_select('CompId', $Data->id)?>><?= $Data->company_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-purple" />
                                <div class="row" style="min-height: 250px; height: auto;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <table class="table table-bordered table-sm ProductsDropDown">
                                            <thead class="bg-purple" style="position: sticky; top: 0; z-index: 99;">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th style="width: 45%;">Product Name</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Total</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" style="text-align: right;">Sub Total</th>
                                                    <th colspan="2">
                                                        <i class="fa fa-inr"></i> 
                                                        <span id="SubTotalTxt">
                                                            <?= set_value('SubTotal', $StockManufacturerData->SubTotal); ?>
                                                        </span>
                                                        <input type="hidden" name="SubTotal" value="<?= set_value('SubTotal', $StockManufacturerData->SubTotal); ?>" id="SubTotal" />
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="Remark">Remark</label>
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark', $StockManufacturerData->Remark); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <button type="submit" class="btn btn-sm bg-purple" id="submitButton">
                                        Make Changes
                                    </button>
                                    <a href="<?= base_url(); ?>StockManufacturer/Print/<?= $StockManufacturerData->Id; ?>" class="btn btn-sm bg-success" target="_blank" title="Print">
                                        Print
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-sm bg-danger" onclick="onStockManufacturerDeleteHandler(<?= $StockManufacturerData->Id; ?>);" title="Delete">
                                        Delete
                                    </a>
                                    <a href="<?= base_url(); ?>StockManufacturer/Report" class="btn btn-sm bg-maroon" title="Cancel">
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
    <!-- /.content -->
 </div>

<?php include('StockManufacturerJs.php'); ?>