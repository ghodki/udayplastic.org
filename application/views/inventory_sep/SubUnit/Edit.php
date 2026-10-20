<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12">
                    <form action="<?= base_url(); ?>SubUnit/Update/<?= $SubUnitId; ?>" method="POST">
                        <input type="hidden" name="SubUnitId" value="<?= set_value('SubUnitId', $SubUnitData->Id); ?>" id="SubUnitId" />
                        <div class="card card-outline card-maroon">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-maroon">
                                        Sub Unit Edit
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>SubUnit" class="btn btn-primary btn-sm pull-right" title="Back">
                                    <i class="fa fa-angle-double-left text-white"></i> Back
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="SubUnitName">Sub Unit Name <span class="text-danger">*</span></label>
                                            <input type="text" name="SubUnitName" value="<?= set_value('SubUnitName', $SubUnitData->SubUnitName); ?>" id="SubUnitName" class="form-control" placeholder="Sub Unit Name" required="" />
                                            <span class="text-danger"><?= form_error('SubUnitName'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                                        <div class="form-group">
                                            <label for="Value">Value</label>
                                            <input type="text" name="Value" value="<?= set_value('Value', $SubUnitData->Value); ?>" id="Value" class="form-control" placeholder="Value" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="submit" class="btn btn-primary btn-sm" value="Save Changes" />
                                <a href="<?= base_url(); ?>SubUnit" class="btn btn-danger btn-sm">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>