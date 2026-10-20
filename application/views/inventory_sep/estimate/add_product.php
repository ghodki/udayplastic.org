<div class="row">
    <div class="" id="productMsg" style="opacity: 500; display: none;"></div>

    <form id="onProductFormSubmitHandler" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/savePro" method="POST" enctype="multipart/form-data" onsubmit="onProductFormSubmitHandler(event)">
        <div class="row">
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="product_name">Product Name<span class="text-danger">*</span></label>
                    <input id="product_name" name="name" tabindex="1001" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name') ?>">
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
                    $where = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')){
                        $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
                    }
                    $productCode = $this->user_model->getRow('productcode', $where);
                    if ($productCode) {
                        $prefix_product_code = $productCode->prefix_product_code;
                        $productcodes = $productCode->productcode;
                        $productcodes = $prefix_product_code . "" . $productcodes;


                    ?>
                        <input type="text" class="form-control" tabindex="1002" require name="pcode" id="pcode" value="<?= $productcodes ?>">


                    <?php
                    } else {
                        $user_id = $this->session->userdata('user_id');
                        $comp_id = $this->session->userdata('comp_id');
                        $where = ['comp_id' => $comp_id, 'user_id' => $user_id,];
                        if ($this->session->userdata('BranchRole')){
                            $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
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
                        <input type="text" class="form-control" tabindex="1003" require name="pcode" id="pcode" value="<?= $inital + 1 ?>">

                    <?php
                    }



                    ?>

                    <span class="col-sm-4 control-label">
                        <?php echo form_error('p_code'); ?>

                    </span>
                </div>
            </div>

            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="price">Selling Price<span class="text-danger">*</span></label>

                    <input id="price" name="price" tabindex="1004" min="1" placeholder="Selling Price" type="text" class="form-control" value="<?= set_value('price') ?>">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('price'); ?>

                </span>
            </div>

            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="cost">Purchase Price<span class="text-danger"></span></label>

                    <input id="cost" name="sale_rate" tabindex="1005" min="0" placeholder="Purchase Price" type="text" class="form-control" value="<?= set_value('sale_rate') ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="mrp">MRP<span class="text-danger"></span></label>

                    <input id="mrp" name="mrp" min="0" tabindex="1006" placeholder="MRP" type="text" class="form-control" value="<?= set_value('mrp') ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="qty">Product Qunatity<span class="text-danger">*</span></label>

                    <input id="qty" name="qty" min="1" tabindex="1007" placeholder="Product Qunatity" type="text" class="form-control" value="<?= set_value('qty') ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="segement">Segement<span class="text-danger"></span></label>
                    <input id="segement" name="segement" placeholder="Product Segement" type="text" class="form-control" value="<?= set_value('segement') ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="discount">Discount%<span class="text-danger"></span></label>

                    <input id="discount" name="discount" min="0" tabindex="1008" placeholder="Discount in %" type="text" class="form-control" value="<?= set_value('discout') ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="hsn">HSN CODE<span class="text-danger"></span></label>

                    <input id="hsn_code" name="hsn_code" tabindex="1009" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code') ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="min_qty">MIN QTY<span class="text-danger"></span></label>

                    <input id="min_qty" name="min_qty" min="0" tabindex="1010" placeholder="Min Qty" type="text" class="form-control" value="<?= set_value('min_qty') ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="weight">Weight<span class="text-danger"></span></label>

                    <input id="weight" name="weight" min="0" tabindex="1011" placeholder="Weight" type="text" class="form-control" value="<?= set_value('weight') ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <label for="unit">Unit<span class="text-danger">*</span></label>

                <select id="unit" name="unit" class="form-control" tabindex="1012" required>
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

                                $user_id = $this->session->userdata('user_id');
                                $comp_id = $this->session->userdata('comp_id');

                                $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
                                if ($this->session->userdata('BranchRole')){
                                    $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
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

                    <select id="tax" name="tax" class="form-control" tabindex="1013" required>
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

                                    $user_id = $this->session->userdata('user_id');
                                    $comp_id = $this->session->userdata('comp_id');

                                    $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
                                    if ($this->session->userdata('BranchRole')){
                                        $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
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



            <input id="customFile" name="image" type="file" class="custom-file-input" hidden value="<?= set_value('image') ?>">


            <div class="col-md-4 col-sm-6 col-lg-4">
                <div class="form-group">
                    <label for="shortdescription">SHORT DESC<span class="text-danger"></span></label>

                    <textarea id="shortdescription" tabindex="1014" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= set_value('short_des') ?></textarea>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-lg-4">
                <div class="form-group">
                    <label for="description">Remark<span class="text-danger"></span></label>

                    <textarea id="description" tabindex="1015" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= set_value('long_des') ?></textarea>
                </div>
            </div>

        </div>


        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">
                    SAVE
                </button>
                <button type="reset" id="productResetBtn" class="btn btn-danger">RESET</button>

            </div>
        </div>
    </form>

</div>