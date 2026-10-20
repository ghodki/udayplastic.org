<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <form action="<?= base_url(); ?>Employee/ConfigurationUpdate" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="EmployeeConfigId" value="<?= set_value('EmployeeConfigId', $EmployeeConfig->Id); ?>" id="EmployeeConfigId" />
                    <div class="card card-outline card-orange">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="card-title text-orange">
                                    Configuration
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <legend class="text-navy" style="font-size: 15px;">Employee Code</legend>
                                    <hr class="bg-navy" />
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="Prefix">Prefix</label>
                                            <input type="text" name="Prefix" value="<?= set_value('Prefix', $EmployeeConfig->Prefix); ?>" id="Prefix" class="form-control form-control-sm" placeholder="Prefix" />
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="Starting">Starting</label>
                                            <input type="text" name="Starting" value="<?= set_value('Starting', $EmployeeConfig->Starting); ?>" id="Starting" class="form-control form-control-sm" placeholder="Starting" minlength="3" maxlength="3" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <legend class="text-navy" style="font-size: 15px;">Employee Terms & Conditions</legend>
                                    <hr class="bg-navy" />
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="TermsAndConditions">Terms & Conditions</label>
                                            <textarea name="TermsAndConditions" id="TermsAndConditions" class="form-control form-control-sm" placeholder="Terms & Conditions" rows="3"><?= set_value('TermsAndConditions', $EmployeeConfig->TermsAndConditions); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <legend class="text-navy" style="font-size: 15px;">Employee Login IP Validation</legend>
                                    <hr class="bg-navy" />
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="LoginIPValidation">Login IP Validation</label>
                                            <br />
                                            <input type="radio" name="LoginIPValidation" value="Yes" <?= set_radio('LoginIPValidation', 'Yes'); ?> id="LoginIPValidation" <?= $EmployeeConfig->LoginIPValidation == "Yes" ? "checked" : ""; ?> title="Yes" />
                                            Yes
                                            <input type="radio" name="LoginIPValidation" value="No" <?= set_radio('LoginIPValidation', 'No'); ?> id="LoginIPValidation" <?= $EmployeeConfig->LoginIPValidation == "No" ? "checked" : ""; ?> title="No" style="margin-left: 100px;" />
                                            No
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Your IP Address</label>
                                            <br />
                                            <label class="text-navy"><?= $this->input->ip_address(); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-sm bg-orange">
                                        <i class="fa fa-save text-white"></i> Save Changes
                                    </button>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-refresh text-white"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>                            
            </div>
        </div>
    </div>
</section>