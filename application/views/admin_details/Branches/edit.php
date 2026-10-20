<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                    <div class="header bg-gradient-green">
                        <div class="container-fluid">
                            <div class="header-body">
                                <h4 style="color: #fff; padding: 5px 0px 5px 0px;" class="text-center">
                                    Add Branch Address
                                </h4>
                                <form method="POST" action="<?= base_url(); ?>Branches/Update/<?= $BranchId; ?>">
                                    <input type="hidden" name="BranchId" value="<?= set_value('BranchId', $BranchData->Id); ?>" id="BranchId" />
                                    <div class="row">
                                        <div class="col-md-2 col-sm-2 col-lg-2">
                                            <div class="form-group">
                                                <label for="Title">Title <span class="text-danger">*</span></label>
                                                <input type="text" name="Title" value="<?= set_value('Title', $BranchData->Title); ?>" id="Title" class="form-control form-control-sm" placeholder="Title" required="" />
                                                <span class="text-danger"><?= form_error('Title'); ?></span>
                                            </div>                                        
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-lg-2">
                                            <div class="form-group">
                                                <label for="Phone">Phone <span class="text-danger">*</span></label>
                                                <input type="text" name="Phone" value="<?= set_value('Phone', $BranchData->Phone); ?>" id="Phone" class="form-control form-control-sm" placeholder="Phone" required="" maxlength="10" />
                                                <span class="text-danger"><?= form_error('Phone'); ?></span>
                                            </div>                                        
                                        </div>
                                        <div class="col-md-5 col-sm-5 col-lg-5">
                                            <div class="form-group">
                                                <label for="Address">Address <span class="text-danger">*</span></label>
                                                <textarea name="Address" id="Address" class="form-control form-control-sm" placeholder="Address" required=""><?= set_value('Address', $BranchData->Address); ?></textarea>
                                                <span class="text-danger"><?= form_error('Address'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-sm-5 col-lg-5">
                                            <div class="form-group">
                                                <label for="Location">Location</label>
                                                <textarea name="Location" id="Location" class="form-control form-control-sm" placeholder="Location"><?= set_value('Location', $BranchData->Location); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-2">
                                            <button type="submit" class="btn btn-success">
                                                Save Changes
                                            </button>
                                            <a href="<?= base_url(); ?>Branches" class="btn btn-danger" title="Cancel">
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
</div>