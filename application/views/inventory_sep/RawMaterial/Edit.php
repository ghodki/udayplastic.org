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
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-secondary">
                                    Raw Material Edit
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>RawMaterial" class="btn btn-xs bg-secondary pull-right" title="Raw Material Report">
                                <i class="fa fa-list"></i> Raw Material Report
                            </a>
                        </div>
                        <?php if (!empty($rawMaterialId)) { ?>
                        <form  method="POST" id="RawMaterialUpdate" onsubmit="onRawMaterialFromSubmitUpdateHandler(event);">
                            <input type="hidden" name="RawMaterialId" value="<?= set_value('RawMaterialId', $rawMaterialData->Id); ?>" id="RawMaterialId" />
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="ProductId">Product <span class="text-danger">*</span></label>
                                            <select name="ProductId" id="ProductId" class="form-control form-control-sm" required="" title="Company">
                                                <option value="" <?= $rawMaterialData->ProductId == "" ? "selected" : ""; ?> disabled="">Select Product</option>
                                                <?php if ($products) { foreach ($products as $key => $data) { ?>
                                                <option value="<?= $data->id; ?>" <?= $rawMaterialData->ProductId == $data->id ? "selected" : ""; ?> <?= set_select('ProductId', $data->id)?>><?= $data->p_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="bg-secondary" />
                                <div class="row" style="min-height: 250px; height: auto;">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <table class="table table-bordered table-sm Products">
                                            <thead class="bg-secondary" style="position: sticky; top: 0; z-index: 99;">
                                                <tr>
                                                    <th class="text-white">Sr.</th>
                                                    <th class="text-white" style="width: 40%;">Product Name</th>
                                                    <th class="text-white">Quantity</th>
                                                    <th class="text-white">Price</th>
                                                    <th class="text-white">Weight</th>
                                                    <th class="text-white">Unit</th>
                                                    <th class="text-white"></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                        <div class="form-group">
                                            <label for="Remark">Remark</label>
                                            <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark', $rawMaterialData->Remark); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                                    <button type="submit" class="btn btn-sm bg-success" id="submitButton">
                                        Make Changes
                                    </button>
                                    <a href="<?= base_url(); ?>RawMaterial" class="btn btn-danger btn-sm">
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

<?php include('RawMaterialJs.php'); ?>