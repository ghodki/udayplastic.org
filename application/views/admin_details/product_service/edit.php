<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green pb-1 pt-1 pt-md-1">
                        <div class="container-fluid">
                            <div class="header-body">
                                <h2 style="color: white;padding:10px 0px 10px 0px" class="text-center">Add Product Service</h2>
                                <form action="<?= base_url() ?>product_service/update" method="POST" enctype="multipart/form-data">
                                    <?php if (isset($error) && $error != '') { echo $error; } ?>

                                    <input id="id" name="id" type="hidden" required="true" value="<?= $product_Service->id ?>">
                                    <div class="row">
                                        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input id="name" name="name" placeholder="Product Service Name" type="text" class="form-control" required="true" value="<?= $product_Service->name ?>">
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?= form_error('name'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                            <select class="form-control" name="unit" id="unit" required>
                                                <?php
                                                    $where = ['id' => $product_Service->unit];
                                                    $unit = $this->user_model->getRow('unit', $where);
                                                    if ($unit) {
                                                ?>
                                                <option value="<?= $unit->id; set_value('unit') == $unit->id ? 'selected' : '' ?>"><?= $unit->name; ?></option>
                                                <?php }
                                                    $acgroup = $this->user_model->getAry('unit');
                                                    foreach ($acgroup as $units) {
                                                ?>
                                                <option value="<?= $units->id; set_value('unit') == $units->id ? 'selected' : '' ?>"><?= $units->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="col-sm-4 control-label">
                                                <?= form_error('unit'); ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input id="price" name="price" min="1" placeholder="Product Service Charge" type="number" class="form-control" value="<?= $product_Service->amount ?>">
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?= form_error('price'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-md-12 col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <textarea id="description" name="long_des" placeholder="Description" class="form-control"><?= $product_Service->des ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-warning">Update</button>
                                                <a href="<?= base_url(); ?>product_service" class="btn btn-danger">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>