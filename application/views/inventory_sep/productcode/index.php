<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>                    
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green p-2">
                        <div class="header-body">
                            <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Start Product Code</h2>
                            <?php if (isset($product) && !empty($product)) { ?>
                            <form id="form" action="<?= base_url(); ?>productcode/update" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="id" value="<?= set_value('id', $product->id); ?>" />
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="prefix_product_code" class="text-white">Prefix Product Code</label>
                                            <input type="text" name="prefix_product_code" value="<?= set_value('prefix_product_code', $product->prefix_product_code); ?>" id="prefix_product_code" class="form-control" placeholder="Prefix Product Code" required="" />
                                            <span class="control-label"><?= form_error('prefix_product_code'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="productcode" class="text-white">Product Code</label>
                                            <input type="number" name="productcode" value="<?= set_value('productcode', $product->productcode); ?>" id="productcode" min="1" class="form-control" placeholder="Product Code" required="" />
                                            <span class="control-label"><?= form_error('productcode'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Update
                                        </button>
                                        <a href="<?= base_url(); ?>Productcode" class="btn btn-danger" title="Cancel">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <?php } else { ?>
                            <form id="form" action="<?= base_url(); ?>productcode/save" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="prefix_product_code" class="text-white">Prefix Product Code</label>
                                            <input type="text" name="prefix_product_code" value="<?= set_value('prefix_product_code'); ?>" id="prefix_product_code" class="form-control" placeholder="Prefix Product Code" required="" />
                                            <span class="control-label"><?= form_error('prefix_product_code'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="productcode" class="text-white">Product Code</label>
                                            <input type="number" name="productcode" value="<?= set_value('productcode'); ?>" id="productcode" min="1" class="form-control" placeholder="Product Code" required="" />
                                            <span class="control-label"><?= form_error('productcode'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                        <button type="submit" class="btn btn-dark">
                                            Save
                                        </button>
                                        <?php } else { ?>
                                        <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                        <?php } } else { ?>
                                        <button type="submit" class="btn btn-dark">
                                            Save
                                        </button>
                                        <?php } ?>
                                        <a href="#" type="reset" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-orange mt-2">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-orange">Start Product Code</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-orange">
                                        <tr>
                                            <th class="text-white">Sr</th>
                                            <th class="text-white">Date</th>
                                            <th class="text-white">Prefix Product Code</th>
                                            <th class="text-white">Product Code</th>
                                            <th class="text-white">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($product) && !empty($product)) { ?>
                                        <tr>
                                            <td>1</td>
                                            <td><?= date("d-m-Y", strtotime($product->created_at)); ?></td>
                                            <td><?= $product->prefix_product_code ?></td>
                                            <td><?= $product->productcode ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>productCode/edit/<?= $product->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a> 
                                                <a href="<?= base_url(); ?>productCode/delete/<?= $product->id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>