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
                            <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Update Product Code</h2>
                            <form id="form" action="<?= base_url(); ?>productCode/update" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= set_value('id', $result->id); ?>" />
                                <div class="row">
                                    <div class="col-md-3 col-xd-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <input type="text" name="productcode" value="<?= set_value('productcode', $result->productcode); ?>" id="productcode" placeholder="Product Code" class="form-control" required="" />
                                            <span class="control-label"><?= form_error('productcode'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Update
                                        </button>
                                        <a href="<?= base_url(); ?>productCode" class="btn btn-danger" title="Cancel">
                                            Cancel
                                        </a>
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