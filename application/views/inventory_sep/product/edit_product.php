<!-- Card stats -->
<div class="row">
    <div class="" id="respnce_msg" style="opacity: 500; display: none;"></div>
    
    <form id="updateProduct" action="<?= base_url() ?>product/update" method="POST" enctype="multipart/form-data" onsubmit="updateProduct(event)">
        <div class="row">
            <input type="hidden" name="id" value="<?= $result->id ?>">
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="product_name">Product Name<span class="text-danger">*</span></label>
                    <input id="product_name" name="name" autofocus tabindex="15" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name', $result->p_name) ?>">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('name'); ?>

                </span>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="pcode">Product Code<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" tabindex="16" require name="pcode" id="pcode" value="<?= $result->qrCode ?>">
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('p_code'); ?>
                    </span>
                </div>
            </div>
            
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <label for="is_service">Is Service<span class="text-danger">*</span></label>
                <select id="is_service" name="is_service" class="form-control" tabindex="25" required>
                    <option value="" <?= $result->is_service == "" ? 'selected' : ''; ?>>Select Is Service</option>
                    <option value="Y"<?= $result->is_service == "Y" ? 'selected' : ''; ?>>Yes</option>
                    <option value="N" <?= $result->is_service == "N" ? 'selected' : ''; ?>>No</option>
                </select>
            </div>

            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="price">Selling Price<span class="text-danger">*</span></label>
                    <input id="price" name="price" required tabindex="17" min="1" placeholder="Selling Price" type="text" class="form-control" value="<?= set_value('price', $result->price) ?>">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('price'); ?>

                </span>
            </div>

            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="cost">Purchase Price<span class="text-danger"></span></label>
                    <input id="cost" name="sale_rate" tabindex="18" min="0" placeholder="Purchase Price" type="text" class="form-control" value="<?= set_value('sale_rate', $result->purchase_rate) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="mrp">MRP Price<span class="text-danger"></span></label>

                    <input id="mrp" name="mrp" min="0" tabindex="19" placeholder="MRP" type="text" class="form-control" value="<?= set_value('mrp', $result->mrp) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="qty">Qty:<span class="text-danger">*</span></label>

                    <input id="qty" name="qty" min="1" tabindex="20" placeholder="Product Qunatity" type="text" class="form-control" value="<?= set_value('qty', $result->qty) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="segement">Segement<span class="text-danger"></span></label>
                    <input id="segement" name="segement" placeholder="Product Segement" type="text" class="form-control" value="<?= set_value('segement', $result->segement) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="discount">Discount:<span class="text-danger"></span></label>
                    <input id="discount" name="discount" min="0" tabindex="21" placeholder="Discount in %" type="text" class="form-control" value="<?= set_value('discout', $result->discount) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="hsn_code">HSN CODE: <a href="https://ewaybillgst.gov.in/Others/ProductSearch.aspx" target="_blank" > >>Check HSN</a><span class="text-danger">*</span></label>
                    <input id="hsn_code" name="hsn_code" tabindex="22" placeholder="HSN Code" type="text" class="form-control hsn-code-edit" value="<?= set_value('hsn_code', $result->hsn) ?>" oninput="getHsnSacCodeEdit(this.value);" autocomplete="off" />
                    <div id="hsn-sac-code-list-edit"></div>
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="min_qty">Min Qty:<span class="text-danger"></span></label>
                    <input id="min_qty" name="min_qty" min="0" tabindex="23" placeholder="Min Qty" type="text" class="form-control" value="<?= set_value('min_qty', $result->min_qty) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="weight">Weight:<span class="text-danger"></span></label>

                    <input id="weight" name="weight" min="0" tabindex="24" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight', $result->weight) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <label for="unit">Unit:<span class="text-danger">*</span></label>
                <select id="unit" name="unit" class="form-control" tabindex="25" required>
                    <option value="">Select Unit</option>
                    <?php
                        $unit = $this->user_model->getResult('unit');
                        $role = $this->session->userdata('roles');
                        if ($role == 1) {
                            if (isset($unit) && !empty($unit)) {
                                foreach ($unit as $units) {
                    ?>
                    <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                    <?php
                        } } } else {
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
                    <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                    <?php } } else { ?>
                    <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                    <?php } } } } ?>
                </select>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('unit'); ?>
                </span>
            </div>
            <div class="col-md-3 col-md-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <label for="SubUnit">Sub Unit</label>
                    <select name="SubUnit" id="SubUnit" class="form-control" title="Sub Unit">
                        <option value="" <?= $result->SubUnit == "" ? 'selected' : ''; ?> disabled="">Select Sub Unit</option>
                        <?php
                            $SubUnit = $this->user_model->getResult('sub_unit', ['Status' => 1]);
                            if (isset($SubUnit) && !empty($SubUnit)) {
                                foreach ($SubUnit as $key => $SUData) {
                        ?>
                        <option value="<?= $SUData->Id; ?>" <?= $result->SubUnit == $SUData->Id ? 'selected' : ''; ?> <?= set_select('SubUnit', $SUData->Id); ?>><?= $SUData->SubUnitName; ?></option>
                        <?php } } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="tax">TAX:<span class="text-danger">*</span></label>
                    <select id="tax" name="tax" class="form-control" tabindex="26" required>
                        <option value="">Select Tax</option>
                        <?php
                            $tax = $this->user_model->getResult('tax');
                            if ($role == 1) {
                                if (isset($tax) && !empty($tax)) {
                                    foreach ($tax as $units) {
                        ?>
                        <option value="<?= $units->id ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                        <?php } } } else {
                                    if (isset($tax) && !empty($tax)) {
                                        foreach ($tax as $units) {
                                            $UserId = $this->session->userdata('user_id');
                                            $CompId = $this->session->userdata('comp_id');
                                            $CompBranchId = $this->session->userdata('CompBranchId');
                                            
                                            $CompanyBranchWhere = ['bill_id' => $units->id, 'user_id' => $UserId, 'comp_id' => $CompId];
                                            if ($this->session->userdata('BranchRole')) {
                                                $CompanyBranchWhere['CompanyBranchId'] = $CompBranchId;
                                            } else {
                                                
                                            }
                                            $resu = $this->user_model->getRow('tax_status', $CompanyBranchWhere);
                                            if (isset($resu) && !empty($resu)) {
                                                if ($resu->status == 1) {
                        ?>
                        <option value="<?= $units->id ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                        <?php } } else { ?>
                        <option value="<?= $units->id ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                        <?php } } } } ?>
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
                    <input type="radio" name="OrderBook" value="Yes" <?= set_radio('OrderBook', 'Yes'); ?> id="Yes" <?= $result->OrderBook == "Yes" ? "checked" : ""; ?> tabindex="28" title="Product for OrderBook Yes" />
                    Yes
                    <input type="radio" name="OrderBook" value="No" <?= set_radio('OrderBook', 'No'); ?> id="No" <?= $result->OrderBook == "No" ? "checked" : ""; ?> tabindex="29" title="Product for OrderBook No" class="ml-5" />
                    No
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="packetQty">Master Packet Quantity</label>
                    <input type="text" name="packetQty" value="<?= set_value('packetQty', $result->packetQty); ?>" id="packetQty" class="form-control" placeholder="Master Packet Quantity" />
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="rewardPoint">Reward Point</label>
                    <input type="text" name="rewardPoint" value="<?= set_value('rewardPoint', $result->rewardPoint); ?>" id="rewardPoint" class="form-control" placeholder="reward Point" />
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="batchCode">Batch Code</label>
                    <input type="text" name="batchCode" value="<?= set_value('batchCode', $result->batchCode); ?>" id="batchCode" class="form-control" placeholder="Batch Code" />
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="dateOfManufacturing">Date Of Manufacturing</label>
                    <input type="date" name="dateOfManufacturing" value="<?= set_value('dateOfManufacturing', $result->dateOfManufacturing); ?>" id="dateOfManufacturing" class="form-control" title="Date Of Manufacturing" />
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="dateOfExpiry">Date Of Expiry</label>
                    <input type="date" name="dateOfExpiry" value="<?= set_value('dateOfExpiry', $result->dateOfExpiry); ?>" id="dateOfExpiry" class="form-control" title="Date Of Expiry" />
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="categoryId">Category</label>
                    <select name="categoryId" id="categoryId" class="form-control select2Dropdown2" title="Category">
                        <option value="" <?= $result->categoryId == "" ? "selected" : ""; ?>>Select Category</option>
                        <?php if (!empty($categories)) { foreach ($categories as $key => $category) { ?>
                        <option value="<?= $category->id; ?>" <?= $result->categoryId == $category->id ? "selected" : ""; ?>><?= $category->name; ?></option>
                        <?php } } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="brandId">Brand</label>
                    <select name="brandId" id="brandId" class="form-control select2Dropdown2" title="Brand">
                        <option value="" <?= $result->brandId == "" ? "selected" : ""; ?>>Select Brand</option>
                        <?php if (!empty($brands)) { foreach ($brands as $key => $brand) { ?>
                        <option value="<?= $brand->id; ?>" <?= $result->brandId == $brand->id ? "selected" : ""; ?>><?= $brand->name; ?></option>
                        <?php } } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-control" title="Type" required="">
                        <option value="" <?= $result->type == "" ? "selected" : ""; ?>>Select Type</option>
                        <option value="Raw" <?= $result->type == "Raw" ? "selected" : ""; ?>>Raw Goods</option>
                        <option value="Finish" <?= $result->type == "Finish" ? "selected" : ""; ?>>Finish Goods</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-lg-4">
                <div class="form-group">
                    <label for="shortdescription">Short Description:</label>
                    <textarea id="shortdescription" tabindex="27" name="short_des" placeholder="Product Short Description" class="form-control" rows="1"><?= $result->short_de ?></textarea>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-lg-4">
                <div class="form-group">
                    <label for="des">Description:</label>
                    <textarea id="description" tabindex="28" name="long_des" placeholder="Product Long Description" class="form-control" rows="1"><?= $result->long_de ?></textarea>
                </div>
            </div>
        </div>


        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">
                    Update
                </button>
                <button type="reset" id="reset" class="btn btn-danger">RESET</button>

            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2Dropdown2').each(function() { 
            $(this).select2({dropdownParent: $(this).parent()});
        });
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

    jQuery.noConflict();

    function updateProduct(e) {
        e.preventDefault();
        var url = $("#updateProduct").attr("action");
        var form = $("#updateProduct")[0];
        var data = new FormData(form);
        $.ajax({
            type        :   "POST",
            enctype     :   "multipart/form-data",
            url         :   url,
            data        :   data,
            processData :   false,
            contentType :   false,
            cache       :   false,
            dataType    :   "JSON",
            success     :   function(customerList) {
                                if (customerList.status == true) {
                                    toastr.success("Product Update Successfully!", "");
                                    console.log(customerList);
                                    $("#qrCode" + customerList.data.id).html(customerList.data.qrCode);
                                    $("#p_name" + customerList.data.id).html(customerList.data.p_name);
                                    $("#hsn" + customerList.data.id).html(customerList.data.hsn);
                                    $("#tax_qty" + customerList.data.id).html(customerList.data.tax_qty);
                                    $("#unit_name" + customerList.data.id).html(customerList.data.unit_name);
                                    $("#price" + customerList.data.id).html(customerList.data.price);
                                    $("#ProductBalanceQuanity" + customerList.data.id).html(customerList.data.total_qty);
                                    setTimeout(function() {
                                        $("#exampleModals").modal("hide");
                                    }, 2000);
                                } else {
                                    $("#respnce_msg").show();
                                    $("#respnce_msg").addClass("text");
                                    $("#respnce_msg").addClass("text-danger");
                                    $("#respnce_msg").html(customerList.msg);
                                }
                            }
        });
    }
    
     // hsn sac
    let hsnSacCodeWarningShownEdit = false;
    const getHsnSacCodeEdit = (hsnSacCode) => {
        if (hsnSacCode == "" || hsnSacCode == null) {
            $('#hsn-sac-code-list-edit').html('');
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
                                $('#hsn-sac-code-list-edit').html(hsnSacCodeOpt);
                                $('#hsn-sac-code-list-edit li:first-child').addClass("active").focus();
                                hsnSacCodeWarningShownEdit = false;
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
            onSelectGetHsnSacCodeEdit($this.text().trim());
        }
    });

    // Handle Click Selection
    $(document).on("click", ".search-list-hsn-sac-code", function () {
        onSelectGetHsnSacCodeEdit($(this).text().trim());
    });
    
    // Function to Set Selected Value and Hide List
    function onSelectGetHsnSacCodeEdit(value) {
        $(".hsn-code-edit").val(value);
        $("#hsn-sac-code-list-edit").html('');
    }
</script>