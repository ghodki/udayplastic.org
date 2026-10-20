<style type="text/css">
    .error {
        color: red;
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
                    <div class="card card-outline card-red">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="text-red">Add Series</h3>
                            </div>
                            <a href="<?= base_url(); ?>series/series_list" class="btn bg-red float-right">
                                <i class="fa fa-list-alt"></i> List
                            </a>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?= base_url(); ?>series/save_series">
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="series_type">Series Type <span class="text-danger">*</span></label>
                                    <div class="col-sm-4 form-group">
                                        <select name="series_type" id="series_type" class="form-control" required="">
                                            <option value="">-Select-</option>
                                            <?php 
                                                $seriesTitle = $this->db->get('seriestitle')->result();
                                                foreach ($seriesTitle as $row) {
                                            ?>
                                            <option value="<?= $row->id; ?> <?= set_select('series_type', $row->id); ?>"><?= $row->series; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span><?= form_error('series_type'); ?></span>
                                    </div>
                                    <label for="year" class="col-sm-2">Financial Year</label>
                                    <div class="col-md-4 form-group">
                                        <select name="year" id="year" class="form-control">
                                            <option value="">Select Financial Year</option>
                                            <?php
                                                $year = $this->user_model->getAry('year');
                                                if ($year) {
                                                    foreach ($year as $row) {
                                            ?>
                                            <option value="<?= $row->id; ?> <?= set_select('year', $row->id); ?>"><?= $row->year; ?></option>
                                            <?php } } ?>
                                        </select>
                                        <span><?= form_error('year'); ?></span>

                                        <select class="form-control" name="fincial_position" id="fincial_position" style="width: 50px;">
                                            <option value="1" <?= set_select('fincial_position', 1); ?>>1</option>
                                            <option value="2" <?= set_select('fincial_position', 2); ?>>2</option>
                                            <option value="3" <?= set_select('fincial_position', 3); ?>>3</option>
                                        </select>
                                        <span><?= form_error('fincial_position'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="prefix">Prefix Invoice No. <span class="text-danger">*</span></label>
                                    <div class="col-sm-4 form-group">
                                        <input autocomplete="off" type="text" name="prefix" id="prefix" placeholder="Prefix" value="<?= set_value('prefix') ?>" class="form-control" />
                                        <span><?= form_error('prefix'); ?></span>

                                        <select class="form-control" name="prefixposition" id="prefixposition" style="width: 50px;">
                                            <option value="1" <?= set_select('prefixposition', 1); ?>>1</option>
                                            <option value="2" <?= set_select('prefixposition', 2); ?>>2</option>
                                            <option value="3" <?= set_select('prefixposition', 3); ?>>3</option>
                                        </select>
                                        <span><?= form_error('prefixposition'); ?></span>
                                    </div>

                                    <label class="col-sm-2 control-label" for="start_point">Series Start From <span class="text-danger">*</span></label>
                                    <div class="col-sm-4 form-group">
                                        <input autocomplete="off" type="number" name="start_point" id="start_point" value="<?= set_value('start_point') ?>" placeholder="Series Start From" class="form-control">
                                        <span><?= form_error('start_point'); ?></span>

                                        <select class="form-control" name="start_point_position" id="start_point_position" style="width: 50px;">
                                            <option value="1" <?= set_select('start_point_position', 1); ?>>1</option>
                                            <option value="2" <?= set_select('start_point_position', 2); ?>>2</option>
                                            <option value="3" <?= set_select('start_point_position', 3); ?>>3</option>
                                        </select>
                                        <span><?= form_error('start_point_position'); ?></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-2 control-label" for="start_point">Capitalization <span class="text-danger"></span></label>
                                    <div class="col-sm-4 form-group">
                                        <input autocomplete="off" type="text" name="symbl" id="symbl" placeholder="Example / : # $" value="<?= set_value('symbl') ?>" class="form-control" />
                                        <span><?= form_error('symbl'); ?></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                                        <input type="submit" class="btn btn-primary" value="Save">
                                        <?php } else { ?>
                                        <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                                        <?php } } else { ?>
                                        <input type="submit" class="btn btn-primary" value="Save">
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