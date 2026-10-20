<style type="text/css">
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
    .search-list-hsn-sac-code:hover, .search-list-hsn-sac-code.active {
        background-color: blue;
        color: white;
    }
</style>

<div class="modal-header">
    <h5 class="modal-title text-center" id="productModalLabel">Add Product</h5>
    <button type="button" class="close" data-dismiss="modal" onclick="closeModel()" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <!-- Card stats -->
    <div class="row">
        <div class="" id="productMsg" style="opacity: 500; display: none;"></div>

        <form id="onProductFormSubmitHandler" action="<?= base_url() ?>product/savePro" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandler(event)">
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


                        <?php
                        $productcodes = 0;
                        if ($this->session->userdata('RoleType')) {
                            $UserId = $this->session->userdata('UserId');
                            $CompId = $this->session->userdata('CompId');
                        } else {
                            $UserId = $this->session->userdata('user_id');
                            $CompId = $this->session->userdata('comp_id');
                        }

                        $where = ['user_id' => $UserId, 'comp_id' => $CompId];
                        if ($this->session->userdata('BranchRole')) {
                            $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        $productCode = $this->user_model->getRow('productcode', $where);
                        if ($productCode) {
                            $prefix_product_code = $productCode->prefix_product_code;
                            $productcodes = $productCode->productcode;
                            $productcodes = $prefix_product_code . "" . $productcodes;


                        ?>
                            <input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $productcodes ?>">


                        <?php
                        } else {
                            $where = ['user_id' => $UserId, 'comp_id' => $CompId];
                            if ($this->session->userdata('BranchRole')) {
                                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                            }                                        
                            $query = $this->user_model->getRow('product_code_for_product_only', $where);
                            if ($query) {
                                $inital = $query->order_product_code;
                                if (!is_numeric($inital)) {
                                    $inital = 0;
                                }
                            } else {
                                $inital = 0;
                            }
                        ?>
                            <input type="text" class="form-control" tabindex="2" require name="pcode" id="pcode" value="<?= $inital + 1 ?>">

                        <?php
                        }



                        ?>

                        <span class="col-sm-4 control-label">
                            <?php echo form_error('p_code'); ?>

                        </span>
                    </div>
                </div>
                <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                    <label for="is_service">Is Service <span class="text-danger">*</span></label>
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
                        <label for="qty">Product Qunatity<span class="text-danger">*</span></label>

                        <input id="qty" name="qty" min="1" tabindex="6" placeholder="Product Qunatity" type="text" class="form-control" value="<?= set_value('qty') ?>">
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
                                    <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                    </option>

                                    <?php
                                }
                            }
                        } else {
                            if (isset($unit) && !empty($unit)) {
                                foreach ($unit as $units) {
                                    $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'bill_id' => $units->id];
                                    if ($this->session->userdata('BranchRole')) {
                                        $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                    }                                                
                                    $resu = $this->user_model->getRow('unit_status', $where);
                                    // $this->session->userdata('user_id');
                                    if (isset($resu) && !empty($resu)) {
                                        if ($resu->status == 1) {
                                    ?>
                                            <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                            </option>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="<?= $units->id ?>" <?= set_value('unit') == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                        </option>

                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </select>
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('unit'); ?>

                    </span>
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
                                        <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
                                            <?= $units->name; ?></option>

                                        <?php
                                    }
                                }
                            } else {
                                if (isset($tax) && !empty($tax)) {
                                    foreach ($tax as $units) {
                                        $where = ['user_id' => $UserId, 'comp_id' => $CompId, 'bill_id' => $units->id];
                                        if ($this->session->userdata('BranchRole')) {
                                            $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                        }                                                    
                                        $resu = $this->user_model->getRow('tax_status', $where);
                                        // $this->session->userdata('user_id');
                                        if (isset($resu) && !empty($resu)) {
                                            if ($resu->status == 1) {
                                        ?>
                                                <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
                                                    <?= $units->name; ?></option>

                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="<?= $units->id ?>" <?= set_value('tax') == $units->id ? 'selected' : ''; ?>>
                                                <?= $units->name; ?></option>

                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
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
                        <select name="categoryId" id="categoryId" class="form-control select2Dropdown" title="Category" style="width: 100%;">
                            <option value="" selected="">Select Category</option>
                            <?php if (!empty($categories)) { foreach ($categories as $key => $category) { ?>
                            <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label for="brandId">Brand</label>
                        <select name="brandId" id="brandId" class="form-control select2Dropdown" title="Brand" style="width: 100%;">
                            <option value="" selected="">Select Brand</option>
                            <?php if (!empty($brands)) { foreach ($brands as $key => $brand) { ?>
                            <option value="<?= $brand->id; ?>"><?= $brand->name; ?></option>
                            <?php } } ?>
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
                <div class="col-md-3 col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label for="shortdescription">Short Description</label>
                        <textarea id="shortdescription" tabindex="13" name="short_des" placeholder="Product Short Description" class="form-control"><?= set_value('short_des') ?></textarea>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label for="description">Remark</label>
                        <textarea id="description" tabindex="14" name="long_des" placeholder="Product Long Description" class="form-control"><?= set_value('long_des') ?></textarea>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-lg-3">
                    <div class="form-group">
                        <label for="warehouse">Warehouse</label>
                        <select name="warehouse" id="warehouse" class="form-control" title="Select Warehouse">
                            <option value="">Select Warehouse</option>
                            <?php
                                $this->db->select('id, name, location');
                                $this->db->from('warehouse');
                                $this->db->where('status', 1);
                                $this->db->where('user_id', $UserId);
                                $this->db->where('comp_id', $CompId);
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $warehouse = $this->db->get()->result();
                                if (!empty($warehouse)) {
                                    foreach ($warehouse as $key => $data) {
                            ?>   
                            <option value="<?= $data->id; ?>" <?= set_select('warehouse', $data->id); ?>><?= $data->name; ?> - <?= $data->location; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
            </div>


            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                    <button type="submit" class="btn btn-primary">
                        SAVE
                    </button>
                    <?php } else { ?>
                    <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                    <?php } } else { ?>
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

<script type="text/javascript">
    $('.select2Dropdown').each(function() { 
        $(this).select2({dropdownParent: $(this).parent()});
    });
    
     // hsn sac
    let hsnSacCodeWarningShown = false;
    const getHsnSacCode = (hsnSacCode) => {
        if (hsnSacCode == "" || hsnSacCode == null) {
            $('#hsn-sac-code-list').html('');
            return;
        }
        
        $.ajax({
            method   :   "POST",
            url          :   "<?= base_url(); ?>product/getHsnSacCode",
            data      :   {hsnSacCode: hsnSacCode},
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