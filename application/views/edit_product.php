<!-- Card stats -->
<div class="row">
    <div class="" id="respnce_msg" style="opacity: 500; display: none;"></div>

    <form id="updateProduct" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>product/update" method="POST" enctype="multipart/form-data" onsubmit="updateProduct(event)">
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
                <div class="form-group">
                    <label for="price">Selling Price<span class="text-danger">*</span></label>
                    <input id="price" name="price" required tabindex="17" min="1" placeholder="Selling Price" type="number" class="form-control" value="<?= set_value('price', $result->price) ?>">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('price'); ?>

                </span>
            </div>

            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="cost">Purchase Price<span class="text-danger"></span></label>
                    <input id="cost" name="sale_rate" tabindex="18" min="0" placeholder="Purchase Price" type="number" class="form-control" value="<?= set_value('sale_rate', $result->purchase_rate) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="mrp">MRP Price<span class="text-danger"></span></label>

                    <input id="mrp" name="mrp" min="0" tabindex="19" placeholder="MRP" type="number" class="form-control" value="<?= set_value('mrp', $result->mrp) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="qty">Qty:<span class="text-danger">*</span></label>

                    <input id="qty" name="qty" min="1" tabindex="20" placeholder="Product Qunatity" type="number" class="form-control" value="<?= set_value('qty', $result->qty) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="discount">Discount:<span class="text-danger"></span></label>
                    <input id="discount" name="discount" min="0" tabindex="21" placeholder="Discount in %" type="number" class="form-control" value="<?= set_value('discout', $result->discount) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="hsn_code">HSN CODE:<span class="text-danger">*</span></label>
                    <input id="hsn_code" name="hsn_code" tabindex="22" placeholder="HSN Code" type="text" class="form-control" value="<?= set_value('hsn_code', $result->hsn) ?>">
                </div>
            </div>
            <div class="col-md-3 col-md-3 col-sm-6 col-lg-3">
                <div class="form-group">
                    <label for="min_qty">Min Qty:<span class="text-danger"></span></label>
                    <input id="min_qty" name="min_qty" min="0" tabindex="23" placeholder="Min Qty" type="number" class="form-control" value="<?= set_value('min_qty', $result->min_qty) ?>">
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
                                <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
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
                                $resu = $this->user_model->getRow('unit_status', $where);
                                // $this->session->userdata('user_id');
                                if (isset($resu) && !empty($resu)) {
                                    if ($resu->status == 1) {
                                ?>
                                        <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
                                        </option>

                                    <?php
                                    }
                                } else {
                                    ?>
                                    <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?>
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
                    <label for="tax">TAX:<span class="text-danger">*</span></label>

                    <select id="tax" name="tax" class="form-control" tabindex="26" required>
                        <option value="">Select Tax</option>
                        <?php
                        $tax = $this->user_model->getResult('tax');

                        if ($role == 1) {
                            if (isset($tax) && !empty($tax)) {
                                foreach ($tax as $units) {
                        ?>
                                    <option value="<?= $units->id ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>>
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
                                    $resu = $this->user_model->getRow('tax_status', $where);
                                    // $this->session->userdata('user_id');
                                    if (isset($resu) && !empty($resu)) {
                                        if ($resu->status == 1) {
                                    ?>
                                            <option value="<?= $units->id ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>>
                                                <?= $units->name; ?></option>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="<?= $units->id ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>>
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


            <!-- <div class="col-md-3 col-md-3 col-sm-6 col-lg-3 form-group">
								<div class="custom-file"> -->
            <input id="customFile" name="image" type="file" class="custom-file-input" hidden value="<?= set_value('image') ?>">
            <!-- <label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div> -->

            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="shortdescription">Short Description:<span class="text-danger"></span></label>

                    <textarea id="shortdescription" tabindex="27" name="short_des" placeholder="Product Short Description" type="text" class="form-control"><?= $result->short_de ?></textarea>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="des">Description:<span class="text-danger"></span></label>

                    <textarea id="description" tabindex="28" name="long_des" placeholder="Product Long Description" type="text" class="form-control"><?= $result->long_de ?></textarea>
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
<script>
    var inputs = document.querySelectorAll("input,select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                //  alert(e.which)
                nextInput[0].focus();
            }
        })
    }
</script>
<script>
    jQuery.noConflict();

    function updateProduct(e) {
        e.preventDefault();
        var url = $("#updateProduct").attr("action");
        var form = $("#updateProduct")[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json', // data type
            success: function(customerList) {
                console.log(customerList);
                if (customerList.status == true) {
                    toastr.success("Product Update Successfully!", "");
                    setTimeout(function() {
                        window.location.href="<?= base_url() ?>invent/product"
                    }, 2000)

                } else {
                    $("#respnce_msg").show();
                    $("#respnce_msg").addClass("text");
                    $("#respnce_msg").addClass("text-danger");
                    $("#respnce_msg").html(customerList.msg);

                }
            },

        });
    }
</script>