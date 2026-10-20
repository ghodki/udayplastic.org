<style type="text/css">
    .choices__inner {
        background-color: #ffffff;
        min-height: 40px;
        padding: 7.5px 7.5px 0px
    }
    .choices__input {
        background-color: #ffffff;
        padding: 0px 0 4px 2px;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Payment Mode Information
                    </h3>
                    <a href="<?= base_url(); ?>Paymentmode" class="btn btn-info btn-xs pull-right" title="Report Payment Mode">
                        <i class="fa fa-file-alt"></i> Report
                    </a>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <?php if (empty($Id)) { ?>
                        <form action="<?= base_url(); ?>Paymentmode/Create" method="POST" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="PaymentMethod">Payment Method <span class="text-danger">*</span></label>
                                    <input type="text" name="PaymentMethod" value="<?= set_value('PaymentMethod'); ?>" id="PaymentMethod" class="form-control" placeholder="Payment Method" required="" />
                                    <span class="text-danger"><?= form_error('PaymentMethod'); ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                    <?php } else { ?>
                                    <span class="text-danger">Your license has been expired,<br />Please contact your administrator.</span>
                                    <?php } } else { ?>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                    <?php } ?>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-refresh"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php } else { ?>
                        <form action="<?= base_url(); ?>Paymentmode/Update/<?= $PaymentModeData->id; ?>" method="POST" autocomplete="off">
                            <input type="hidden" name="PaymentModeId" value="<?= $PaymentModeData->id; ?>" id="PaymentModeId" />
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="PaymentMethod">Payment Method <span class="text-danger">*</span></label>
                                    <input type="text" name="PaymentMethod" value="<?= set_value('PaymentMethod', $PaymentModeData->payment_method); ?>" id="PaymentMethod" class="form-control" placeholder="Payment Method" required="" />
                                    <span class="text-danger"><?= form_error('PaymentMethod'); ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fa fa-save"></i> Update
                                    </button>
                                    <a href="<?= base_url(); ?>Paymentmode" class="btn btn-danger btn-sm">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </form>                            
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>