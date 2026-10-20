<style type="text/css">
    input[type="file"] {
        line-height: 1;
    }
</style>
    
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="header-body pl-3 pr-3">
                            <a href="<?= base_url(); ?>aboutus/certificate-gst" class="btn btn-danger btn-xs pull-right mt-2" title="Back to About Us">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                            <h4 style="color: white;" class="text-center">Update GST</h4>
                            <form method="POST" action="<?= base_url('aboutus/GSTUpdate/') . $gstdata->id; ?>" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $gstdata->id; ?>" />
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="cname">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" name="cname" value="<?= set_value('cname', $gstdata->cname); ?>" id="cname" class="form-control form-control-sm" placeholder="Company Name" required="" />
                                            <span class="text-danger"><?= form_error('cname'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="state">State <span class="text-danger">*</span></label>
                                            <input type="text" name="state" value="<?= set_value('state', $gstdata->state); ?>" id="state" class="form-control form-control-sm" placeholder="State" required="" />
                                            <span class="text-danger"><?= form_error('state'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="location">Location <span class="text-danger">*</span></label>
                                            <input type="text" name="location" value="<?= set_value('location', $gstdata->location); ?>" id="location" class="form-control form-control-sm" placeholder="Location" required="" />
                                            <span class="text-danger"><?= form_error('location'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="offices">Offices/Plant <span class="text-danger">*</span></label>
                                            <input type="text" name="offices" value="<?= set_value('offices', $gstdata->offices); ?>" id="offices" class="form-control form-control-sm" placeholder="Offices/Plant" required="" />
                                            <span class="text-danger"><?= form_error('offices'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="provisional_id">Provisional ID <span class="text-danger">*</span></label>
                                            <input type="text" name="provisional_id" value="<?= set_value('provisional_id', $gstdata->provisional_id); ?>" id="provisional_id" class="form-control form-control-sm" placeholder="Provisional ID" required="" />
                                            <span class="text-danger"><?= form_error('provisional_id'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="address">Address <span class="text-danger">*</span></label>
                                            <textarea name="address" id="address" class="form-control form-control-sm" rows="1" placeholder="Address" required=""><?= set_value('address', $gstdata->address); ?></textarea>
                                            <span class="text-danger"><?= form_error('address'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="arn_copy">ARN Copy</label>
                                            <input type="file" name="arn_copy" id="arn_copy" class="form-control form-control-sm" title="ARN Copy" />
                                            <input type="hidden" name="oldcopy" value="<?= $gstdata->arn_copy; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1 pb-1">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-warning btn-xs">
                                            Save Changes
                                        </button>
                                        <a href="<?= base_url(); ?>aboutus/certificate-gst" class="btn btn-danger btn-xs">
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