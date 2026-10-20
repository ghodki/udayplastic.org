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
                            <h4 style="color: white;" class="text-center">Update Certificate</h4>
                            <form method="POST" action="<?= base_url('aboutus/certificate-update/') . $certificate->id ; ?>" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $certificate->id; ?>" />
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-lg-5">
                                        <div class="form-group">
                                            <label for="certificate">Certificate</label>
                                            <input type="file" name="certificate" id="certificate" class="form-control form-control-sm" accept="image/*" title="Certificate" required="" />
                                            <input type="hidden" name="oldimage" value="<?= $certificate->certificate; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-lg-5">
                                        <div class="form-group">
                                            <label for="certificate">Certificate</label>
                                            <img src="<?= base_url(); ?>uploads/about/<?= $certificate->certificate; ?>" alt="certificate" style="width: 100px; height: 50px;" />
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