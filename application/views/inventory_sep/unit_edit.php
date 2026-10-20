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
                    
                <?php if ($this->session->userdata('roles') == 1) { ?>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green p-2">
                        <div class="header-body">
                            <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Edit Unit</h2>
                            <form method="POST" action="<?= base_url(); ?>unit/update">
                                <input type="hidden" name="id" value="<?= set_value('id', $unit->id); ?>" />
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="unit_name" class="text-white">Unit Name <span class="text-danger">*</span></label>
                                            <input type="text" name="unit_name" value="<?= set_value('unit_name', $unit->name); ?>" id="unit_name" class="form-control" placeholder="Unit Name" required="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="uqc_code" class="text-white">UQC Code<span class="text-danger">*</span></label>
                                            <input type="text" name="uqc_code" value="<?= set_value('uqc_code', $unit->uqc_code); ?>" id="uqc_code" class="form-control" placeholder="UQC Code" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Update
                                        </button>
                                        <a href="<?= base_url(); ?>Unit" class="btn btn-danger" title="Cancel">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>