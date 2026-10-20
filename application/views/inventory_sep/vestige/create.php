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
                    <div class="card card-blue card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-blue">
                                    Today Vestiges
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>vestige/reports" class="btn btn-xs bg-blue pull-right" title="Vestiges Report">
                                <i class="fa fa-list"></i> Vestiges Report
                            </a>
                        </div>
                        <form  method="POST" id="vestige" onsubmit="onVestigeFromSubmitHandler(event);">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="date" value="<?= set_value('date', date('Y-m-d')); ?>" id="date" class="form-control form-control-sm" title="Date" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="voucherNumber">Voucher Number <span class="text-danger">*</span></label>
                                            <input type="text" name="voucherNumber" value="<?= set_value('voucherNumber', $voucherNumber); ?>" id="voucherNumber" class="form-control form-control-sm" placeholder="Voucher Number" required="" readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="compId">Company <span class="text-danger">*</span></label>
                                            <select name="compId" id="compId" class="form-control form-control-sm" required="" title="Company">
                                                <option value="" <?= $this->session->userdata('comp_id') == "" ? "selected" : ""; ?> disabled="">Select Company</option>
                                                <?php if ($companies) { foreach ($companies as $key => $data) { ?>
                                                <option value="<?= $data->id; ?>" <?= $this->session->userdata('comp_id') == $data->id ? "selected" : ""; ?> <?= set_select('compId', $data->id); ?>><?= $data->company_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-blue" />
                                <div class="row" style="min-height: 250px; height: auto;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <table class="table table-bordered table-sm productsDropDown">
                                            <thead class="bg-blue" style="position: sticky; top: 0; z-index: 99;">
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
                                                        <i class="fa fa-inr"></i> <span id="subTotalTxt"><?= set_value('subTotal'); ?></span>
                                                        <input type="hidden" name="subTotal" value="<?= set_value('subTotal'); ?>" id="subTotal" />
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="remark">Remark</label>
                                            <textarea name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('remark'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <button type="submit" class="btn btn-sm bg-blue" id="submitButton">
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

<?php include('vestigeJs.php'); ?>