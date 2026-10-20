<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
</style>

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
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <form method="POST" action="<?= base_url(); ?>MiniSlider/Update" enctype="multipart/form-data">
                        <input type="hidden" name="MiniSliderId" value="<?= set_value('MiniSliderId', $MiniSliderData->Id); ?>" id="MiniSliderId" />
                        <div class="card card-outline card-dark">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4 class="text-dark">
                                        Edit
                                    </h4>
                                </div>
                                <a href="<?= base_url(); ?>MiniSlider" class="btn btn-info btn-sm pull-right" title="Back">
                                    <i class="fa fa-angle-double-left"></i> Back
                                </a>
                            </div>
                            <div class="card-body">                                
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Image">Image <span class="text-danger">*</span></label>
                                            <input type="file" name="Image" id="Image" class="form-control form-control-sm" title="Image" accept="image/*" required="" />
                                            <input type="hidden" name="OldImage" value="<?= set_value('OldImage', $MiniSliderData->Image); ?>" id="OldImage" />
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-sm">
                                    Save Changes
                                </button>
                                <a href="<?= base_url(); ?>MiniSlider" class="btn btn-danger btn-sm">
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