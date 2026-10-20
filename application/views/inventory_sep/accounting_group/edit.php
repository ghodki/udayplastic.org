<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green">
                        <div class="header-body">
                            <h5 style="color: white; padding:10px 0px 10px 0px" class="text-center">
                                Edit Accounting Group
                            </h5>
                            <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </div>
                            <?php } ?>
                            <form method="POST" action="<?= base_url(); ?>Accounting_group/update">
                                <input type="hidden" name="id" value="<?= set_value('id', $accounting_group->id); ?>" id="id" />
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="name" class="text-white">Accounting Group <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="<?= set_value('name', $accounting_group->name); ?>" id="name" class="form-control" placeholder="Accounting Group" required="" />
                                                <span class="text-danger"><?= form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-lg-2">
                                            <div class="form-group">
                                                <label for="name" class="text-white">Type <span class="text-danger">*</span></label>
                                                <select name="Type" id="Type" class="form-control" required="" title="Select Type">
                                                    <option value="" <?= $accounting_group->Type == "" ? 'selected' : ""; ?> disabled="">Select Type</option>
                                                    <option value="DR" <?= set_select('Type', 'DR'); ?><?= $accounting_group->Type == "DR" ? 'selected' : ""; ?>>DR</option>
                                                    <option value="CR" <?= set_select('Type', 'CR'); ?><?= $accounting_group->Type == "CR" ? 'selected' : ""; ?>>CR</option>
                                                </select>
                                                <span class="text-danger"><?= form_error('Type'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 pb-2">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-success">
                                                Update
                                            </button>
                                            <a href="<?= base_url(); ?>Accounting_group" class="btn btn-danger">
                                                Cancel
                                            </a>
                                        </div>
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