            <form action="<?= base_url('product/update'); ?>" method="POST" autocomplete="off" id="updateProduct" onsubmit="updateProduct(event);">
                <input type="hidden" name="id" value="<?= $result->id; ?>" />
                <div class="" id="respnce_msg" style="opacity: 500; display: none;"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="title">Update Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="name">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?= set_value('name', $result->p_name); ?>" id="product_name" class="form-control" placeholder="Product Name" required="true" autofocus="" />
                            </div>
                            <span><?= form_error('name'); ?></span>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="pcode">Product Code <span class="text-danger">*</span></label>
                                <input type="text" name="pcode" value="<?= set_value('pcode', $result->qrCode); ?>" id="pcode" class="form-control" placeholder="Product Code" required="" />
                                <span><?= form_error('p_code'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="price">Selling Price <span class="text-danger">*</span></label>
                                <input type="text" name="price" value="<?= set_value('price', $result->price); ?>" id="price" min="1" class="form-control" placeholder="Selling Price" required="" />
                            </div>
                            <span><?= form_error('price'); ?></span>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="purchase_rate">Purchase Price</label>
                                <input type="text" name="purchase_rate" value="<?= set_value('purchase_rate', $result->purchase_rate); ?>" id="purchase_rate" min="0" class="form-control" placeholder="Purchase Price" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="mrp">MRP Price</label>
                                <input type="text" name="mrp" value="<?= set_value('mrp', $result->mrp); ?>" id="mrp" min="0" class="form-control" placeholder="MRP Price" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="text" name="qty" value="<?= set_value('qty', $result->qty); ?>" id="qty" min="1" class="form-control" placeholder="Product Qunatity" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="segement">Segment</label>
                                <input type="text" name="segement" value="<?= set_value('segement', $result->segement); ?>" id="segement" class="form-control" placeholder="Product Segement" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="discount">Discount</label>
                                <input type="text" name="discount" value="<?= set_value('discout', $result->discount); ?>" id="discount" min="0" class="form-control" placeholder="Discount in %" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="hsn_code">HSN CODE</label>
                                <input type="text" name="hsn_code" value="<?= set_value('hsn_code', $result->hsn); ?>" id="hsn_code" class="form-control" placeholder="HSN Code" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="min_qty">Min Qty</label>
                                <input type="text" name="min_qty" value="<?= set_value('min_qty', $result->min_qty); ?>" id="min_qty" min="0" class="form-control" placeholder="Min Qty" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="weight">Weight</label>
                                <input type="text" name="weight" value="<?= set_value('weight', $result->weight); ?>" id="weight" min="0" class="form-control" placeholder="Weight" />
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <label for="unit">Unit <span class="text-danger">*</span></label>
                            <select id="unit" name="unit" class="form-control" required="">
                                <option value="">Select Unit</option>
                                <?php
                                    $unit = $this->user_model->getResult('unit');
                                    $role = $this->session->userdata('roles');
                                    if ($role == 1) {
                                        if (isset($unit) && !empty($unit)) {
                                            foreach ($unit as $units) {
                                ?>
                                <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                <?php } } } else {
                                    if (isset($unit) && !empty($unit)) {
                                        foreach ($unit as $units) {
                                            $user_id = $this->session->userdata('user_id');
                                            $comp_id = $this->session->userdata('comp_id');
                                            $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
                                            $resu = $this->user_model->getRow('unit_status', $where);
                                            if (isset($resu) && !empty($resu)) {
                                                if ($resu->status == 1) {
                                ?>
                                <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                <?php } } else { ?>
                                <option value="<?= $units->id ?>" <?= set_value('unit', $result->unit) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                <?php } } } } ?>
                            </select>
                            <span><?= form_error('unit'); ?></span>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="tax">TAX <span class="text-danger">*</span></label>
                                <select id="tax" name="tax" class="form-control" required="">
                                    <option value="">Select Tax</option>
                                    <?php
                                        $tax = $this->user_model->getResult('tax');
                                        if ($role == 1) {
                                            if (isset($tax) && !empty($tax)) {
                                                foreach ($tax as $units) {
                                    ?>
                                    <option value="<?= $units->id; ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } } else {
                                        if (isset($tax) && !empty($tax)) {
                                            foreach ($tax as $units) {
                                                $user_id = $this->session->userdata('user_id');
                                                $comp_id = $this->session->userdata('comp_id');
                                                $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
                                                $resu = $this->user_model->getRow('tax_status', $where);
                                                if (isset($resu) && !empty($resu)) {
                                                    if ($resu->status == 1) {
                                    ?>
                                    <option value="<?= $units->id; ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } else { ?>
                                    <option value="<?= $units->id; ?>" <?= set_value('tax', $result->tax) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                                    <?php } } } } ?>
                                </select>
                            </div>
                            <span><?= form_error("tax"); ?></span>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="shortdescription">Short Description</label>
                                <textarea name="short_des" id="shortdescription" class="form-control" placeholder="Product Short Description" rows="1"><?= $result->short_de; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-3">
                            <div class="form-group">
                                <label for="des">Description:<span class="text-danger"></span></label>
                                <textarea name="long_des" id="description" class="form-control" placeholder="Product Long Description" rows="1"><?= $result->long_de; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </form>