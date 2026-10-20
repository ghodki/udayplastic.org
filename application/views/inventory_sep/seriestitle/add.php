<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-danger">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="text-danger">Add Series Title</h3>
                            </div>
                            <a href="<?= base_url(); ?>seriestitle" class="btn btn-danger pull-right">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?= base_url(); ?>seriestitle/save">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" required="" placeholder="Series Title" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                        <button type="submit" class="btn btn-default">
                                            Save
                                        </button>
                                        <?php } else { ?>
                                        <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                        <?php } } else { ?>
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                        <?php } ?>
                                        <input type="reset" class="btn btn-danger" value="Reset" />
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