<style type="text/css">
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-1">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green pb-8 pt-5 pt-md-8">
                        <div class="header-body">
                            <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Update Company</h2>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <form action="<?= base_url(); ?>paymentReceipt/edit/<?= $result->id; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" name="company_name" value="<?= set_value('company_name', $result->company_name); ?>" id="company_name" class="form-control" placeholder="Company Name" required="true" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('company_name'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="number">Tel Number</label>
                                                <input type="tel" name="number" value="<?= set_value('number', $result->number); ?>" id="number" class="form-control" placeholder="Tel Number" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('number'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" value="<?= set_value('email', $result->email); ?>" id="email" class="form-control" placeholder="Email" required="true" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('email'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="pan">PAN</label>
                                                <input type="text" name="pan" value="<?= set_value('pan', $result->pan); ?>" id="pan" class="form-control" placeholder="PAN Number" minlength="10" maxlength="10" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="address">Address <span class="text-danger">*</span></label>
                                                <textarea name="address" id="address" class="form-control" placeholder="Address" required="true"><?= set_value('address', $result->address); ?></textarea>
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('address'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="pin">Pin <span class="text-danger">*</span></label>
                                                <input type="tel" name="pin" value="<?= set_value('pin', $result->pin); ?>" id="pin" class="form-control" placeholder="Pin" required="true" minlength="6" maxlength="6" />
                                                <span class="col-sm-4 control-label text-danger">
                                                    <?= form_error('pin'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 pb-3">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-default">Update</button>
                                            <a href="<?= base_url(); ?>paymentreceipt" class="btn btn-danger">Cancel</a>
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