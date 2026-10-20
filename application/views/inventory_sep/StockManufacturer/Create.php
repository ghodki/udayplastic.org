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
                    <div class="card card-navy card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-navy">
                                    Today Stock Manufacturer
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>StockManufacturer/Reports" class="btn btn-xs bg-navy pull-right" title="Stock Manufacturer Report">
                                <i class="fa fa-list"></i> Stock Manufacturer Report
                            </a>
                        </div>
                        <form  method="POST" id="StockManufacturer" onsubmit="onStockManufacturerFromSubmitHandler(event);">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', date('Y-m-d')); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="VoucherNumber">Voucher Number <span class="text-danger">*</span></label>
                                            <input type="text" name="VoucherNumber" value="<?= set_value('VoucherNumber', $VoucherNumber); ?>" id="VoucherNumber" class="form-control form-control-sm" placeholder="Voucher Number" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="CompId">Company <span class="text-danger">*</span></label>
                                            <select name="CompId" id="CompId" class="form-control form-control-sm" required="" title="Company">
                                                <option value="" <?= $this->session->userdata('comp_id') == "" ? "selected" : ""; ?> disabled="">Select Company</option>
                                                <?php if ($Company) { foreach ($Company as $key => $Data) { ?>
                                                <option value="<?= $Data->id; ?>" <?= $this->session->userdata('comp_id') == $Data->id ? "selected" : ""; ?> <?= set_select('CompId', $Data->id)?>><?= $Data->company_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-navy" />
                                <div class="row" style="min-height: 250px; height: auto;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <table class="table table-bordered table-sm ProductsDropDown">
                                            <thead class="bg-navy" style="position: sticky; top: 0; z-index: 99;">
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
                                                        <i class="fa fa-inr"></i> <span id="SubTotalTxt"><?= set_value('SubTotal'); ?></span>
                                                        <input type="hidden" name="SubTotal" value="<?= set_value('SubTotal'); ?>" id="SubTotal" />
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="Remark">Remark</label>
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <button type="submit" class="btn btn-sm bg-navy" id="submitButton">
                                        Submit
                                    </button>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('StockManufacturerJs.php'); ?>