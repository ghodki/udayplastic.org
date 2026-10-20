<style type="text/css">
    .header label {
        color: #ffffff;
    }
</style>

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
                    <div class="header bg-gradient-green">
                        <div class="container-fluid">
                            <div class="header-body">
                                <h2 style="color: #fff; padding: 5px 0px 5px 0px;" class="text-center">
                                    Edit Holidays
                                </h2>
                                <form method="POST" action="<?= base_url(); ?>Holiday/update">
                                    <input type="hidden" name="id" id="id" value="<?= set_value('id', $holiday->id); ?>" />
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="holiday_name">Holiday Name <span class="text-danger">*</span></label>
                                                <input type="text" name="holiday_name" value="<?= set_value('holiday_name', $holiday->name); ?>" id="holiday_name" class="form-control" placeholder="Holiday Name" required="" />
                                                <span class="text-danger"><?= form_error('holiday_name'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-2">
                                            <button type="submit" class="btn btn-success">
                                                Save
                                            </button>
                                            <a href="<?= base_url(); ?>Holiday" class="btn btn-danger" title="Cancel">
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