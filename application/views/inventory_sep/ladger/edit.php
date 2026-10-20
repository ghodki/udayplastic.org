<style type="text/css">
    .header label {
        color: #fff;
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
                    <div class="header">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h3 style="color: #fff; padding: 5px 0px 5px 0px;" class="text-center">
                                    Update Ledger
                                </h3>
                                <form method="POST" action="<?= base_url(); ?>ladger/updateledger">
                                    <input type="hidden" name="id" value="<?= set_value('id', $result->id); ?>" />
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="accounting_group">Accounting Group <span class="text-danger">*</span></label>
                                                <select id="accounting_group" name="accounting_group" class="form-control form-control-sm" required="" title="Accounting Group">
                                                    <?php
                                                        $accounting_group = $this->user_model->getResult('accounting_group');
                                                        if (isset($accounting_group) && !empty($accounting_group)) {
                                                            foreach ($accounting_group as $data) {
                                                    ?>
                                                    <option <?= $result->accounting_group == $data->id ? "selected" : ""; ?> value="<?= $data->id ?>" <?= set_select('accounting_group', $data->id); ?>><?= $data->name; ?></option>
                                                    <?php } } ?>
                                                </select>
                                                <span class="text-danger">
                                                    <?= form_error('accounting_group'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="name">Ledger Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="<?= set_value('name', $result->name); ?>" class="form-control form-control-sm" placeholder="Ledger Name" required="" />
                                                <span class="text-danger">
                                                    <?= form_error('name'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="ContactPerson">Contact Person <span class="text-danger">*</span></label>
                                                <input type="text" name="ContactPerson" value="<?= set_value('ContactPerson', $result->ContactPerson); ?>" class="form-control form-control-sm" placeholder="Contact Person" required="" />
                                                <span class="text-danger"><?= form_error('ContactPerson'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="Mobile">Mobile <span class="text-danger">*</span></label>
                                                <input type="tel" name="Mobile" value="<?= set_value('Mobile', $result->Mobile); ?>" class="form-control form-control-sm" placeholder="Mobile" required="" minlength="10" maxlength="10" />
                                                <span class="text-danger"><?= form_error('Mobile'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="Email">Email</label>
                                                <input type="text" name="Email" value="<?= set_value('Email', $result->Email); ?>" class="form-control form-control-sm" placeholder="Email" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="Address">Address</label>
                                                <textarea name="Address" class="form-control form-control-sm" placeholder="Address" rows="1"><?= set_value('Address', $result->Address); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="Area">Area</label>
                                                <input type="text" name="Area" value="<?= set_value('Area', $result->Area); ?>" class="form-control form-control-sm" placeholder="Area" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="opening_balance">Opening Balance</label>
                                                <input type="text" name="opening_balance" value="<?= set_value('opening_balance', $result->opening_balance); ?>" class="form-control form-control-sm" min="0" placeholder="Opening Balance" required="" />
                                                <span class="control-label text-danger">
                                                    <?= form_error('opening_balance'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="type">Ledger Type <span class="text-danger">*</span></label>
                                                <select name="type" id="type" class="form-control form-control-sm" title="Ledger Type" required="">
                                                    <option <?= $result->type == "CR" ? "selected" : ""; ?> <?= set_select('type', 'CR'); ?> value="CR">CR</option>
                                                    <option <?= $result->type == "DR" ? "selected" : ""; ?> <?= set_select('type', 'DR'); ?> value="DR">DR</option>
                                                </select>
                                                <span class="text-danger">
                                                    <?= form_error('type'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row pb-1">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-warning btn-xs">
                                                Save Changes
                                            </button>
                                            <?php if (!empty($this->session->userdata('RoleType'))) { ?>
                                            <a href="<?= base_url(); ?>Employee/Ledger" class="btn btn-danger btn-xs">
                                                Cancel
                                            </a>
                                            <?php } else { ?>
                                            <a href="<?= base_url(); ?>ladger" class="btn btn-danger btn-xs">
                                                Cancel
                                            </a>
                                            <?php } ?>
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