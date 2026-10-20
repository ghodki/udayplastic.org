<style type="text/css">
    .loader-bg {
        background: #161616 url("<?= base_url(); ?>assets/images/loader.gif") no-repeat center center;
        opacity: 0.5;
        background-size: 5%;
    }
    .loader-bg {
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 10000;
    }
    .form-group {
        margin-bottom: 0rem;
    }
    th, td {
        white-space: nowrap;
    }
</style>

<div class="loader-bg"></div>

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
                    <div class="card card-navy card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-navy">
                                    Purchase Order Create
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>PurchaseOrder/index" class="btn btn-xs bg-navy pull-right" title="Purchase Order Report">
                                <i class="fa fa-list"></i> Purchase Order Report
                            </a>
                        </div>
                        <form action="<?= base_url(); ?>PurchaseOrder/PurchaseOrderSave" method="POST" id="Orderbook">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', date('Y-m-d')); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                            <span class="text-danger"><?= form_error('Date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="CompanyId">Parent Company <span class="text-danger">*</span></label>
                                            <select name="CompanyId" id="CompanyId" class="form-control form-control-sm" required="" title="Parent Company">
                                                <option value="" selected="" disabled="">Select Parent Company</option>
                                                <?php if ($ParentCompany) { foreach ($ParentCompany as $key => $Data) { ?>
                                                <option value="<?= $Data->id; ?>" <?= set_select('CompanyId', $Data->id)?>><?= $Data->company_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger"><?= form_error('CompanyId'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-navy" />
                                <div class="row" style="min-height: 250px; height: auto;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm ProductsDropDown">
                                                <thead class="bg-navy" style="position: sticky; top: 0; z-index: 99;">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th style="width: 40%;">Product Name</th>
                                                        <th>Min Qty.</th>
                                                        <th>Avail Stock</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="6" style="text-align: right;">Sub Total</th>
                                                        <th colspan="2">
                                                            <i class="fa fa-inr"></i> <span id="SubTotalTxt"><?= set_value('SubTotal'); ?></span>
                                                            <input type="hidden" name="SubTotal" value="<?= set_value('SubTotal'); ?>" id="SubTotal" />
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
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
                                    <button type="submit" class="btn btn-sm bg-navy">
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
    <!-- /.content -->
 </div>

<?php include('purchaseOrderjs.php'); ?>