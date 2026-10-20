<style type="text/css">
    .loader-bg {
        background: #161616 url("<?= base_url(); ?>assets/images/loader.gif") no-repeat center center;
        opacity: 0.5;
        background-size: 5%;
        z-index: 9999;
        width: 100%;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
    }
    #progress {
        position: relative;
        width: 400px;
        border: 1px solid #ddd;
        padding: 1px;
        border-radius: 3px;
    }
    #bar {
        background-color: #B4F5B4;
        width: 0%;
        height: 10px;
        border-radius: 3px;
        padding-bottom: 10px;
    }
    #percent {
        position: absolute;
        top: 3px;
        left: 48%;
    }
    .form-control {
        border: none;
        border-bottom: 1px solid black;
    }
    #products tr th,
    tr td {
        white-space: nowrap;
    }
    #products tbody tr td {
        padding: 0.25rem;
    }
    .select2-container {
        width: 100% !important;
    }
    .search-list-hsn-sac-code {
        color: #000;
        list-style: none;
        position: relative;
        left: -25px;
        border-bottom: 0.5px solid #e7e7e7;
        padding-top: 5px;
        width: 150px;
        font-size: 12px;
        cursor: pointer;
    }
    .search-list {
        color: #000;
        list-style: none;
        position: relative;
        left: -25px;
        border-bottom: 0.5px solid #e7e7e7;
        padding-top: 5px;
        width: 150px;
        font-size: 12px;
    }
    .search-list-hsn-sac-code:hover, .search-list-hsn-sac-code.active {
        background-color: blue;
        color: white;
    }
</style>

<div class="loader-bg"></div>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                            <?= $this->session->flashdata('msg'); ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-primary">
                                    Products
                                </h5>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-success btn-sm" title="Change Sales Tax" id="change-sales-tax">
                                    <i class="fa fa-money text-white"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Delete" id="delete-product">
                                    <i class="fa fa-trash fa-lg text-white"></i>
                                </button>
                                <button class="btn btn-info btn-sm" title="Upload" id="upload-product" data-toggle="modal" data-target="#importModal">
                                    <i class="fa fa-upload fa-lg text-white"></i>
                                </button>
                                <button class="btn btn-info btn-sm" title="Export" id="export-button">
                                    <i class="fa fa-file-export fa-lg text-white"></i>
                                </button>
                                <button class="btn btn-primary btn-sm" title="Copy" id="copy-product">
                                    <i class="fa fa-clone fa-lg text-white"></i>
                                </button>
                                <button class="btn btn-default btn-sm" title="Barcode" id="print-barcode">
                                    <i class="fa fa-barcode fa-lg"></i>
                                </button>
                                <button class="btn btn-success btn-sm" title="Add" id="NewProductModal">
                                    <i class="fa fa-plus fa-lg text-white"></i>
                                </button>
                                <a href="<?= base_url(); ?>product/getProductsData" class="btn btn-warning btn-sm" title="Synchronize HSN Code">
                                    <i class="fa fa-refresh"></i>
                                </a>
                                <a href="<?= base_url(); ?>warehouse/stock_transfer" class="btn btn-info btn-sm" title="Stock Transfer">
                                    <i class="fa fa-home"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-lg-3">
                                        <div class="form-group">
                                            <select name="filterProductId" id="filterProductId" class="form-control select2Dropdown form-control-sm" title="Product">
                                                <option value="" selected="">Select Product</option>
                                                <?php if (!empty($products)) { foreach ($products as $key => $product) { ?>
                                                <option value="<?= $product->id; ?>"><?= $product->p_name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-lg-3">
                                        <div class="form-group">
                                            <select name="filterUnitId" id="filterUnitId" class="form-control select2Dropdown form-control-sm" title="Unit">
                                                <option value="" selected="">Select Unit</option>
                                                <?php if (!empty($units)) { foreach ($units as $key => $unit) { ?>
                                                <option value="<?= $unit->id; ?>"><?= $unit->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-lg-3">
                                        <div class="form-group">
                                            <select name="filterTaxId" id="filterTaxId" class="form-control select2Dropdown form-control-sm" title="Tax">
                                                <option value="" selected="">Select Tax</option>
                                                <?php if (!empty($taxes)) { foreach ($taxes as $key => $tax) { ?>
                                                <option value="<?= $tax->id; ?>"><?= $tax->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-lg-3">
                                        <div class="form-group">
                                            <input type="number" name="filterHsnCode" id="filterHsnCode" class="form-control form-control-sm" placeholder="HSN Code" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-lg-3">
                                        <div class="form-group">
                                            <select name="filterCategoryId" id="filterCategoryId" class="form-control select2Dropdown form-control-sm" title="Category">
                                                <option value="" selected="">Select Category</option>
                                                <?php if (!empty($categories)) { foreach ($categories as $key => $category) { ?>
                                                <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-lg-3">
                                        <div class="form-group">
                                            <select name="filterBrandId" id="filterBrandId" class="form-control select2Dropdown form-control-sm" title="Brand">
                                                <option value="" selected="">Select Brand</option>
                                                <?php if (!empty($brands)) { foreach ($brands as $key => $brand) { ?>
                                                <option value="<?= $brand->id; ?>"><?= $brand->name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-lg-3">
                                        <div class="form-group">
                                            <select name="filterHsnStatus" id="filterHsnStatus" class="form-control select2Dropdown form-control-sm" title="filterHsnStatus">
                                                <option value="" selected="">Select HSN Status</option>
                                                <?php $hsn_status=['Y'=>"verified",'N'=>"unverified"];
                                                 foreach($hsn_status as $key=>$hsn_s){
                                                ?>
                                                <option value="<?= $key; ?>"><?= $hsn_s; ?></option>
                                                <?php }  ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-lg-3">
                                        <button type="button" id="filterProducts" class="btn btn-primary btn-sm">Search</button>
                                        <a href="<?= base_url(); ?>product" class="btn btn-danger btn-sm">Reset</a>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12">
                                    <div class="table-responsive mt-2"></div>
                                    <div class="pagination mt-3 pull-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- IMport FIle -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Excel Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="import-frm" method="POST" action="<?= base_url() ?>product/importfile" enctype="multipart/form-data" novalidate="novalidate">
                <div class="modal-body">
                    <a href="<?= base_url() ?>sample/demo.xlsx" class="float-right" download>
                        <i class="fa fa-download"></i> Download Template
                    </a>
                    <div class="input-field">
                        <div class="file-field">
                            <div class="btn" style="margin-top: -15px;">
                                <span>Upload File</span>
                                <input data-error=".logo" required type="file" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                            <div class="logo"></div>
                        </div>
                    </div>
                    <p class="text-danger font-italic">
                        <b>Note :</b>
                        <br />
                        1. If the name of the product is same that will not be save and taxes and units are mandatory that should be match from given taxes and units.
                        <br />
                        2. Maximum product can be uploaded in single time only 4000.
                    </p>
                </div>
                <div class="modal-footer">
                    <?php if ($this->session->userdata('roles') == 3) {
                        if (IsLicensed()) { ?>
                            <button type="submit" class="btn btn-xs btn-primary">
                                <i class="fa fa-upload"></i> Import
                            </button>
                        <?php } else { ?>
                            <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                        <?php }
                    } else { ?>
                        <button type="submit" class="btn btn-xs btn-primary">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    <?php } ?>
                    <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Import File End -->

<!-- Add Product -->
<div class="modal fade" id="add-product" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Card stats -->
                <div class="row">
                    <div class="" id="productMsg" style="opacity: 500; display: none;"></div>
                    <form id="onProductFormSubmitHandler" action="<?= base_url(); ?>product/savePro" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandler(event);">
                        <div class="row">
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="product_name">Product Name<span class="text-danger">*</span></label>
                                    <input id="product_name" name="name" tabindex="1" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('name'); ?>
                                </span>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="product_code">Product Code<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" tabindex="2" required="" name="pcode" id="pcode" />
                                    <span class="col-sm-4 control-label">
                                        <?php echo form_error('p_code'); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <label for="is_service">Is Service<span class="text-danger">*</span></label>
                                <select id="is_service" name="is_service" class="form-control" tabindex="25" required>
                                    <option value="">Select Is Service</option>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select>
                            </div>

                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="price">Selling Price<span class="text-danger">*</span></label>
                                    <input id="price" name="price" tabindex="3" min="1" placeholder="Selling Price" type="text" class="form-control" value="<?= set_value('price') ?>">
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('price'); ?>
                                </span>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="cost">Purchase Price<span class="text-danger"></span></label>
                                    <input id="cost" name="sale_rate" tabindex="4" min="0" placeholder="Purchase Price" type="text" class="form-control" value="<?= set_value('sale_rate') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="mrp">MRP<span class="text-danger"></span></label>
                                    <input id="mrp" name="mrp" min="0" tabindex="5" placeholder="MRP" type="text" class="form-control" value="<?= set_value('mrp') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="qty">Product Quantity<span class="text-danger">*</span></label>
                                    <input id="qty" name="qty" min="1" tabindex="6" placeholder="Product Quantity" type="text" class="form-control" value="<?= set_value('qty') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="segement">Segment<span class="text-danger"></span></label>
                                    <input id="segement" name="segement" placeholder="Product Segment" type="text" class="form-control" value="<?= set_value('segement') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="discount">Discount%<span class="text-danger"></span></label>
                                    <input id="discount" name="discount" min="0" tabindex="7" placeholder="Discount in %" type="text" class="form-control" value="<?= set_value('discout') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="hsn">HSN CODE <a href="https://ewaybillgst.gov.in/Others/ProductSearch.aspx" target="_blank" > >>Check HSN</a><span class="text-danger"></span></label>
                                    <input id="hsn_code" name="hsn_code" tabindex="8" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>" oninput="getHsnSacCode(this.value);" autocomplete="off" />
                                    <div id="hsn-sac-code-list"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="min_qty">MIN QTY<span class="text-danger"></span></label>
                                    <input id="min_qty" name="min_qty" min="0" tabindex="9" placeholder="Min Qty" type="text" class="form-control" value="<?= set_value('min_qty') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="weight">Weight<span class="text-danger"></span></label>
                                    <input id="weight" name="weight" min="0" tabindex="10" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <label for="unit">Unit<span class="text-danger">*</span></label>
                                <select id="unit" name="unit" class="form-control" tabindex="11" required>
                                    <option value="">Select Unit</option>
                                    <?php
                                    $unit = $this->user_model->getResult('unit');
                                    $role = $this->session->userdata('roles');
                                    if ($role == 1) {
                                        if (isset($unit) && !empty($unit)) {
                                            foreach ($unit as $units) {
                                    ?>
                                                <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                                <?php }
                                        }
                                    } else {
                                        if (isset($unit) && !empty($unit)) {
                                            foreach ($unit as $units) {
                                                $UserId = $this->session->userdata('user_id');
                                                $CompId = $this->session->userdata('comp_id');
                                                $CompBranchId = $this->session->userdata('CompBranchId');

                                                $CompanyBranchWhere = ['bill_id' => $units->id, 'user_id' => $UserId, 'comp_id' => $CompId];
                                                if ($this->session->userdata('BranchRole')) {
                                                    $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                                                }

                                                $resu = $this->user_model->getRow('unit_status', $CompanyBranchWhere);
                                                if (isset($resu) && !empty($resu)) {
                                                    if ($resu->status == 1) {
                                                ?>
                                                        <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                                    <?php }
                                                } else { ?>
                                                    <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php }
                                            }
                                        }
                                    } ?>
                                </select>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error('unit'); ?>
                                </span>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                                <div class="form-group">
                                    <label for="SubUnit">Sub Unit</label>
                                    <select name="SubUnit" id="SubUnit" class="form-control" title="Sub Unit">
                                        <option value="" selected="" disabled="">Select Sub Unit</option>
                                        <?php
                                        $SubUnit = $this->user_model->getResult('sub_unit', ['Status' => 1]);
                                        if (isset($SubUnit) && !empty($SubUnit)) {
                                            foreach ($SubUnit as $key => $SUData) {
                                        ?>
                                                <option value="<?= $SUData->Id; ?>" <?= set_select('SubUnit', $SUData->Id); ?>><?= $SUData->SubUnitName; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="tax">TAX<span class="text-danger">*</span></label>
                                    <select id="tax" name="tax" class="form-control" tabindex="12" required>
                                        <option value="">Select Tax</option>
                                        <?php
                                        $tax = $this->user_model->getResult('tax');
                                        if ($role == 1) {
                                            if (isset($tax) && !empty($tax)) {
                                                foreach ($tax as $units) {
                                        ?>
                                                    <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                                    <?php }
                                            }
                                        } else {
                                            if (isset($tax) && !empty($tax)) {
                                                foreach ($tax as $units) {
                                                    $UserId = $this->session->userdata('user_id');
                                                    $CompId = $this->session->userdata('comp_id');
                                                    $CompBranchId = $this->session->userdata('CompBranchId');

                                                    $CompanyBranchWhere = ['bill_id' => $units->id, 'user_id' => $UserId, 'comp_id' => $CompId];
                                                    if ($this->session->userdata('BranchRole')) {
                                                        $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                                                    }

                                                    $resu = $this->user_model->getRow('tax_status', $CompanyBranchWhere);
                                                    if (isset($resu) && !empty($resu)) {
                                                        if ($resu->status == 1) {
                                                    ?>
                                                            <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                        <?php }
                                                }
                                            }
                                        } ?>
                                    </select>
                                </div>
                                <span class="col-sm-4 control-label">
                                    <?php echo form_error("tax"); ?>
                                </span>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="OrderBook">Order Book</label>
                                    <br />
                                    <input type="radio" name="OrderBook" value="Yes" <?= set_radio('OrderBook', 'Yes'); ?> id="Yes" checked="" tabindex="14" title="Product for OrderBook Yes" />
                                    Yes
                                    <input type="radio" name="OrderBook" value="No" <?= set_radio('OrderBook', 'No'); ?> id="No" tabindex="14" title="Product for OrderBook No" class="ml-5" />
                                    No
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="packetQty">Master Packet Quantity</label>
                                    <input type="text" name="packetQty" value="<?= set_value('packetQty'); ?>" id="packetQty" class="form-control" placeholder="Master Packet Quantity" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="rewardPoint">Reward Point</label>
                                    <input type="text" name="rewardPoint" value="<?= set_value('rewardPoint'); ?>" id="rewardPoint" class="form-control" placeholder="reward Point" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="batchCode">Batch Code</label>
                                    <input type="text" name="batchCode" value="<?= set_value('batchCode'); ?>" id="batchCode" class="form-control" placeholder="Batch Code" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="dateOfManufacturing">Date Of Manufacturing</label>
                                    <input type="date" name="dateOfManufacturing" value="<?= set_value('dateOfManufacturing'); ?>" id="dateOfManufacturing" class="form-control" title="Date Of Manufacturing" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="dateOfExpiry">Date Of Expiry</label>
                                    <input type="date" name="dateOfExpiry" value="<?= set_value('dateOfExpiry'); ?>" id="dateOfExpiry" class="form-control" title="Date Of Expiry" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="categoryId">Category</label>
                                    <select name="categoryId" id="categoryId" class="form-control select2Dropdown" title="Category">
                                        <option value="" selected="">Select Category</option>
                                        <?php if (!empty($categories)) {
                                            foreach ($categories as $key => $category) { ?>
                                                <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="brandId">Brand</label>
                                    <select name="brandId" id="brandId" class="form-control select2Dropdown" title="Brand">
                                        <option value="" selected="">Select Brand</option>
                                        <?php if (!empty($brands)) {
                                            foreach ($brands as $key => $brand) { ?>
                                                <option value="<?= $brand->id; ?>"><?= $brand->name; ?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="form-group">
                                    <label for="type">Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control" title="Type" required="">
                                        <option value="" selected="" disabled="">Select Type</option>
                                        <option value="Raw">Raw Goods</option>
                                        <option value="Finish">Finish Goods</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="shortdescription">Short Description</label>
                                    <textarea id="shortdescription" tabindex="13" name="short_des" placeholder="Product Short Description" class="form-control" rows="1"><?= set_value('short_des') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <label for="description">Remark</label>
                                    <textarea id="description" tabindex="14" name="long_des" placeholder="Product Long Description" class="form-control" rows="1"><?= set_value('long_des') ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12 text-center">
                                <?php if ($this->session->userdata('roles') == 3) {
                                    if (IsLicensed()) { ?>
                                        <button type="submit" class="btn btn-primary">
                                            SAVE
                                        </button>
                                    <?php } else { ?>
                                        <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                    <?php }
                                } else { ?>
                                    <button type="submit" class="btn btn-primary">
                                        SAVE
                                    </button>
                                <?php } ?>
                                <button type="reset" id="productResetBtn" class="btn btn-danger">RESET</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Product END-->

<div class="modal fade" id="exampleModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabels"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productStock" tabindex="-1" role="dialog" aria-labelledby="productStockLabels" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productStockLabels"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productHsnVerifie" tabindex="-1" role="dialog" aria-labelledby="productHsnVerifieLabels" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productHsnVerifieLabels"></h5>
                <button type="button" class="productHsnVerifieclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- UPDATE PRODUCT -->
<div class="modal fade" id="barcode_list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px; width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabels"></h5>
                <input type="hidden" id="rownumber">
                <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" onclick="" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url() ?>product/barcodegenerate">
                    <table class="table table-striped table-bordered table-responsive-sm bg-dark">
                        <thead>
                            <?php
                            $condition = ['comp_id' => $this->session->comp_id, 'user_id' => $this->session->user_id];
                            if ($this->session->userdata('BranchRole')) {
                                $condition['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                            }
                            $barcode_pre_row = get_title('comp_profile', $condition, 'barcode_pre_row');
                            ?>
                            <input type="number" min="1" max="4" width="100px;" required placeholder="Number Of Column" name="input_number" id="input_number" value="<?= $barcode_pre_row ?>">
                            <span class="text-danger">
                                (Till 4 Column)
                            </span>
                            <button class="btn btn-primary" type="submit">Print</button>
                            <br /><br />
                            <tr>
                                <th>Name</th>
                                <th>No Fo Copies</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic_data">
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-qty" tabindex="-1" role="dialog" aria-labelledby="add-qtyLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-qtyLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>

<script type="text/javascript">
    const getProductsReport = (page = 1) => {
        var productId = $("#filterProductId").val();
        var unitId = $("#filterUnitId").val();
        var taxId = $("#filterTaxId").val();
        var hsnCode = $("#filterHsnCode").val();
        var categoryId = $("#filterCategoryId").val();
        var brandId = $("#filterBrandId").val();
        var hsnStatus = $("#filterHsnStatus").val();
        
        $.ajax({
            type : "POST",
            url : "<?= base_url(); ?>Product/getProductsReport",
            data : {productId: productId, unitId: unitId, taxId: taxId, hsnCode: hsnCode, categoryId: categoryId, brandId: brandId, hsnStatus: hsnStatus, page: page},
            dataType : "JSON",
            success : function(response) {
                        if (response) {
                            $(".table-responsive").html(response.table);

                            let paginationHtml = "";
                            if (response.pagination != null) {
                                let totalPages = response.pagination.totalPages;
                                let currentPage = response.pagination.currentPage;
                                let visiblePages = 5; // Number of pages to show before adding ellipsis
                                
                                // First button
                                if (currentPage > 1) {
                                    paginationHtml += `<button class="btn btn-light" onclick="getProductsReport(1)">First</button> `;
                                }
                                
                                // Previous button
                                if (currentPage > 1) {
                                    paginationHtml += `<button class="btn btn-light" onclick="getProductsReport(${currentPage - 1})">&laquo;</button> `;
                                }
                                
                                // Show limited page numbers
                                let startPage = Math.max(1, currentPage - Math.floor(visiblePages / 2));
                                let endPage = Math.min(totalPages, startPage + visiblePages - 1);
                                
                                if (startPage > 1) {
                                    paginationHtml += `<button class="btn btn-light" onclick="getProductsReport(1)">1</button> `;
                                    if (startPage > 2) paginationHtml += `<span>...</span> `;
                                }
                                
                                for (let i = startPage; i <= endPage; i++) {
                                    paginationHtml += `<button class="btn ${i == currentPage ? 'btn-primary' : 'btn-light'}" onclick="getProductsReport(${i})">${i}</button> `;
                                }
                                
                                if (endPage < totalPages) {
                                    if (endPage < totalPages - 1) paginationHtml += `<span>...</span> `;
                                    paginationHtml += `<button class="btn btn-light" onclick="getProductsReport(${totalPages})">${totalPages}</button> `;
                                }
                                
                                // Next button
                                if (currentPage < totalPages) {
                                    paginationHtml += `<button class="btn btn-light" onclick="getProductsReport(${currentPage + 1})">&raquo;</button> `;
                                }
                                
                                // Last button
                                if (currentPage < totalPages) {
                                    paginationHtml += `<button class="btn btn-light" onclick="getProductsReport(${totalPages})">Last</button> `;
                                }
                                
                                // Render pagination
                                $(".pagination").html(paginationHtml);
    
                                $(".loader-bg").css("display", "none");
                            } else {
                                $(".table-responsive").html('<span class="text-danger text-center">No Record found.</span>');
                                $(".loader-bg").css("display", "none");
                            }                                
                        } else {
                            $(".table-responsive").html('<span class="text-danger text-center">No Record found.</span>');
                            $(".loader-bg").css("display", "none");
                        }
                     }
        });
    };
    
    $("#filterProducts").click(function() {
        $(".loader-bg").css("display", "block");
        getProductsReport();
    });

    var inputs = document.querySelectorAll("input,select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                nextInput[0].focus();
            }
        });
    }

    // Select All
    $("#product-selectAll").click(function() {
        $(".checkdata").prop('checked', $(this).prop('checked'));
    });

    // Delete Selected Value
    $(function() {
        $("#delete-product").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });

            if (selected.length > 0) {
                if (!confirm('Are you sure to Deletes the Product?')) return false;
                $.ajax({
                    type: "POST",
                    url: "<?= base_url(); ?>product/deleteProduct",
                    data: {
                        id: selected
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == true) {
                            toastr.success("", data.data);
                        } else {
                            toastr.error("", data.data);
                        }
                        setInterval(function() {
                            getProductsReport();
                        }, 1000);
                    },
                    error: function() {
                        alert("Something is Wrong");
                    }
                });
            } else {
                alert("Select First");
            }
        });
    });

    // COPY DATA
    $(function() {
        $("#copy-product").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });

            if (selected.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url(); ?>product/getProduct/" + selected[0],
                    dataType: "JSON",
                    success: function(product_data) {
                        if (product_data.status == true) {
                            var data = product_data.data;
                            $('#add-product').modal('show');
                            $("#product_name").val(data.p_name);
                            $("#is_service").val(data.is_service);
                            $("#price").val(data.price);
                            $("#cost").val(data.purchase_rate);
                            $("#mrp").val(data.mrp);
                            $("#discount").val(data.discount);
                            $("#qty").val(data.qty);
                            $("#hsn_code").val(data.hsn);
                            $("#min_qty").val(data.min_qty);
                            $("#segement").val(data.segement);
                            $("#weight").val(data.weight);
                            $("#unit").val(data.unit);
                            $("#SubUnit").val(data.SubUnit);
                            $("#tax").val(data.tax);
                            $("#packetQty").val(data.packetQty);
                            $("#rewardPoint").val(data.rewardPoint);
                            $("#batchCode").val(data.batchCode);
                            $("#dateOfManufacturing").val(data.dateOfManufacturing);
                            $("#dateOfExpiry").val(data.dateOfExpiry);
                            $("#categoryId").val(data.categoryId);
                            $("#brandId").val(data.brandId);
                            $("#type").val(data.type);
                            $("#shortdescription").val(data.short_de);
                            $("#description").val(data.long_de);
                        } else {
                            toastr.error(product_data.data);
                        }
                    },
                    error: function() {
                        alert("Something is wrong");
                    }
                });
            } else {
                alert("Select product first");
            }
        });
    });

    $(function() {
        $("#print-barcode").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url(); ?>product/selectProduct",
                    data: {
                        id: selected
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == true) {
                            rowCount = 0;
                            var product = data.data;
                            tabindex = 0;
                            document.getElementById('dynamic_data').innerHTML = "";
                            $("#barcode_list").modal("show");
                            product.forEach(function(products) {
                                $("#dynamic_data").append(
                                    '<tr id="tr' + rowCount + '">\n' +
                                    '<td style="padding:4px;">' +
                                    '<input type="text" readonly id="product' + rowCount + '" tabindex="' + (++tabindex) + '"  name="product[' + rowCount + '][p_name]" value="' + products.p_name + '" class="form-control">' +
                                    '</td>' +
                                    '<td style="padding:4px;">' +
                                    '<input type="text"  id="qty' + rowCount + '" tabindex="' + (++tabindex) + '"  name="product[' + rowCount + '][qty]" value="' + products.balanceQty + '" min="1" class="form-control">'+
                                    '</td>' +
                                    '<input type="hidden" readonly id="id' + rowCount + '" tabindex="' + (++tabindex) + '"  name="product[' + rowCount + '][id]" value="' + products.id + '" class="form-control">' +
                                    '<input type="hidden" readonly id="productcode' + rowCount + '" tabindex="' + (++tabindex) + '"  name="product[' + rowCount + '][productcode]" value="' + products.qrCode + '" class="form-control">' +
                                    '</tr>' +
                                    ++rowCount
                                );
                            });
                        };
                    }
                });
            } else {
                alert("Select First");
            }
        });
    });

    $(document).ready(function() {
        getProductsReport();

        $('.select2Dropdown').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent()
            });
        });

        $('#submitButton').click(function() {
            $('#import_form').ajaxForm({
                target: '#outputImage',
                url: '<?= base_url() ?>',
                beforeSubmit: function() {
                    $("#outputImage").hide();
                    if ($("#uploadImage").val() == "") {
                        $("#outputImage").show();
                        $("#outputImage").html("<div class='error'>Choose a file to upload.</div>");
                        return false;
                    }
                    $("#progressDivId").css("display", "block");
                    var percentValue = '0%';

                    $('#progressBar').width(percentValue);
                    $('#percent').html(percentValue);
                },
                uploadProgress: function(event, position, total, percentComplete) {

                    var percentValue = percentComplete + '%';
                    $("#progressBar").animate({
                        width: '' + percentValue + ''
                    }, {
                        duration: 5000,
                        easing: "linear",
                        step: function(x) {
                            percentText = Math.round(x * 100 / percentComplete);
                            $("#percent").text(percentText + "%");
                            if (percentText == "100") {
                                $("#outputImage").show();
                            }
                        }
                    });
                },
                error: function(response, status, e) {
                    alert('Oops something went.');
                },
                complete: function(xhr) {
                    if (xhr.responseText && xhr.responseText != "error") {
                        $("#outputImage").html(xhr.responseText);
                    } else {
                        $("#outputImage").show();
                        $("#outputImage").html("<div class='error'>Problem in uploading file.</div>");
                        $("#progressBar").stop();
                    }
                }
            });
        });
    });

    function submitHandler() {
        var checkboxes = $('input:checkbox:checked').length;
        if (length > 0) { // alert("select first");
            alert("select first");
            return false;
        }
        return true;
    }

    jQuery.noConflict();

    function onProductFormSubmitHandler(e) {
        e.preventDefault();
        var url = $("#onProductFormSubmitHandler").attr("action");
        var form = $("#onProductFormSubmitHandler")[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {
                    toastr.success("Product Added Successfully!", "");
                    setTimeout(function() {
                        $("#onProductFormSubmitHandler")[0].reset();
                        $("#add-product").modal("hide");
                        $(".show").css("opacity", "0");
                        $(".show").addClass("HideModal");
                        $(".show").removeClass("modal-backdrop");

                        getProductsReport();
                    }, 1000);
                } else {
                    $("#productMsg").show();
                    $("#productMsg").addClass("text");
                    $("#productMsg").addClass("text-danger");
                    $("#productMsg").html(customerList.msg);
                }
            }
        });
    }

    function getProductCode() {
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>Product/getProductCode",
            success: function(Response) {
                $("#pcode").val(Response);
            }
        });
    }

    $("#NewProductModal").click(function() {
        setTimeout(function() {
            $("#add-product").modal("show");
            $("#pcode").val(getProductCode());
            $("#add-product").css("display", "block");
            $(".HideModal").css("opacity", "0.5");
            $(".HideModal").addClass("modal-backdrop");
        }, 500);
    });

    const productEdit = (productId) => {
        jQuery.noConflict();
        $("#exampleModals .modal-dialog .modal-body").load(baseurl + "product/edit_product/" + productId);
        $("#exampleModalLabels").html("Update Product");
        $("#exampleModals").modal("show");
    };

    function productStock(productId) {
        jQuery.noConflict();
        $("#productStock .modal-dialog .modal-body").load(baseurl + "product/stock/" + productId);
        $("#productStockLabels").html("Product Stock Report");
        $("#productStock").modal("show");
    }

    $("#resetbtn").click(function() {
        window.location.href = "<?= base_url() ?>product";
    });

    $(function() {
        $("#export-button").click(function() {
            var selected = new Array();
            $(".cheking input[type=checkbox]:checked").each(function() {
                selected.push(this.value);
            });
            if (selected.length > 0) {
                var id = selected;
                href = "<?= base_url() ?>product/exportproduct";
                var $a = $("<a>");
                $a.attr("href", href);
                $("body").append($a);
                $a.attr("download", "product.xls");
                $a[0].click();
                $a.remove();
            } else {
                alert("Select First");
            }
        });
    });

    function add_qty(productId) {
        jQuery.noConflict();
        $("#add-qty .modal-dialog .modal-body").load(baseurl + "index.php/product/add_qty/" + productId);
        $("#add-qtyLabel").html("Add Qty");
        $("#add-qty").modal("show");
    }
    
    
    
    function productHsnVerifie(productId) {
        console.log(productId,'productId');
        jQuery.noConflict();
        $("#productHsnVerifie .modal-dialog .modal-body").load(baseurl + "product/productHsnVerifie/" + productId);
        $("#productHsnVerifieLabels").html("Product Hsn Verifie");
        $("#productHsnVerifie").modal("show");
    }

    function onProductHsnVerifiedFormSubmitHandler(e) {
        e.preventDefault();
        var url = $("#onProductHsnVerifiedFormSubmitHandler").attr("action");
        var form = $("#onProductHsnVerifiedFormSubmitHandler")[0];
        var data = new FormData(form);
        var product_id=$("#product_id").val(); 
        var tax_qty=$("#product_tax").val(); 
        var product_name=$("#hsn_product_name").val(); 
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customerList) {
                customerList = JSON.parse(customerList);
                if (customerList.status == true) {
                    toastr.success("Product HSN verified successfully!", "");
                    setTimeout(function() {
                    //    $("#onProductHsnVerifiedFormSubmitHandler")[0].reset();
                        $("#productHsnVerifie").modal("hide");
                    //     $(".show").css("opacity", "0");
                        // $(".productHsnVerifie").addClass("HideModal");
                        // $(".productHsnVerifie").removeClass("modal-backdrop"); 
                        $("#row_"+product_id).removeClass('btn-danger');
                        $("#row_"+product_id).addClass('btn-success');
                        $("#tax_qty"+product_id).html(tax_qty+"%");
                        $("#p_name"+product_id).html(product_name);
                        $(".productHsnVerifieclose").click(); 
                    }, 1000);
                } else {
                    $("#productMsg").show();
                    $("#productMsg").addClass("text");
                    $("#productMsg").addClass("text-danger");
                    $("#productMsg").html(customerList.msg);
                }
            }
        });
    }
    
     // hsn sac
    let hsnSacCodeWarningShown = false;
    const getHsnSacCode = (hsnSacCode) => {
        if (hsnSacCode == "" || hsnSacCode == null) {
            $('#hsn-sac-code-list').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>product/getHsnSacCode",
            data    :   {hsnSacCode: hsnSacCode},
            success :   function (response) {                                                      
                            if (response) {
                                var responseData = JSON.parse(response);  
                                var hsnSacCodeOpt = `<ul class="search-ul" id="hsn-sac-code-list-ul">`;
                                responseData.forEach((element, index) => {
                                    hsnSacCodeOpt += `<li class="search-list-hsn-sac-code" id="hsn-sac-code-li-${index}" tabindex="0" data-index="${index}">${element.hsnSacCode}</li>`;
                                });
                                hsnSacCodeOpt += `</ul>`;
                                $('#hsn-sac-code-list').html(hsnSacCodeOpt);
                                $('#hsn-sac-code-list li:first-child').addClass("active").focus();
                                hsnSacCodeWarningShown = false;
                            } else {
                                $('#hsn-sac-code-list').html('');
                                if (!hsnSacCodeWarningShown) {
                                    toastr.warning('Please verify HSN/SAC code on your end before proceeding.');
                                    hsnSacCodeWarningShown = true;
                                }
                            }
                        }
        });
    };
    
    // Handle Key Navigation (Arrow Down, Arrow Up, Enter)
    $(document).on("keydown", ".search-list-hsn-sac-code", function (e) {
        var $this = $(this);
        var index = parseInt($this.attr("data-index"));
        var totalItems = $(".search-list-hsn-sac-code").length;

        if (e.keyCode === 40) { // Arrow Down
            e.preventDefault();
            var nextIndex = (index + 1 < totalItems) ? index + 1 : index;
            $(".search-list-hsn-sac-code").removeClass("active");
            $("#hsn-sac-code-li-" + nextIndex).addClass("active").focus();
        }
        if (e.keyCode === 38) { // Arrow Up
            e.preventDefault();
            var prevIndex = (index - 1 >= 0) ? index - 1 : index;
            $(".search-list-hsn-sac-code").removeClass("active");
            $("#hsn-sac-code-li-" + prevIndex).addClass("active").focus();
        }
        if (e.keyCode === 13) { // Enter Key
            e.preventDefault();
            onSelectGetHsnSacCode($this.text().trim());
        }
    });

    // Handle Click Selection
    $(document).on("click", ".search-list-hsn-sac-code", function () {
        onSelectGetHsnSacCode($(this).text().trim());
    });

    // Function to Set Selected Value and Hide List
    function onSelectGetHsnSacCode(value) {
        $("#hsn_code").val(value);
        $("#hsn-sac-code-list").html('');
    }
</script>