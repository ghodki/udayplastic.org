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
                    <div class="card card-outline card-navy">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="text-navy">Edit Series</h3>
                            </div>
                            <a href="<?= base_url('series/series_list') ?>" class="btn bg-navy pull-right">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>series/update" method="POST">
                                <input autocomplete="off" type="hidden" name="series_id" value="<?= set_value('series_id', $result->series_id); ?>" />
                                <input autocomplete="off" type="hidden" name="series_typeid" value="<?= set_value('series_typeid', $result->series_type); ?>" />
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="series_type">Voucher Type <span class="text-danger">*</span></label>
                                    <div class="col-sm-4 form-group">
                                        <select name="series_type" id="series_type" class="form-control" required="">
                                            <option value="">-Select-</option>
                                            <?php
                                                $seriesTitle = $this->db->get('seriestitle')->result();
                                                if ($seriesTitle) {
                                                    foreach ($seriesTitle as $row) {
                                                        if ($row->id == $result->series_type) {
                                            ?>
                                            <option value="<?= $row->id ?>" <?= $row->id == $result->series_type ? 'selected' : '' ?>><?= $row->series; ?></option>
                                            <?php } else { ?>
                                            <option value="<?= $row->id ?>" <?= $row->id == $result->series_type ? 'selected' : '' ?>><?= $row->series; ?></option>
                                            <?php } } } ?>
                                        </select>
                                    </div>
                                    
                                    <label class="col-sm-2 control-label" for="year">Financial Year<span class="text-danger">*</span></label>
                                    <div class="col-md-4 form-group">
                                        <select name="year" id="year" data-select2-id="15" class="form-control">
                                            <option value="">Select Financial Year</option>
                                            <?php
                                                $this->db->select('*');
                                                $this->db->from('year');
                                                $query = $this->db->get();
                                                $year = $query->result();
                                                if ($year) {
                                                    foreach ($year as $row) {
                                            ?>
                                            <option value="<?= $row->id ?>" <?= $row->id == $result->financial_year ? "selected" : '' ?>><?= $row->year ?></option>
                                            <?php } } ?>
                                        </select>
                                        
                                        <select class="form-control" name="fincial_position" style="width: 50px;" id="fincial_position">
                                            <option value="1" <?= 1 == $result->financial_year_position ? "selected" : '' ?>>1</option>
                                            <option value="2" <?= 2 == $result->financial_year_position ? "selected" : '' ?>>2</option>
                                            <option value="3" <?= 3 == $result->financial_year_position ? "selected" : '' ?>>3</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="prefix">Prefix Invoice No. <span class="text-danger">*</span></label>
                                    <div class="col-sm-4 form-group">
                                        <input autocomplete="off" type="text" name="prefix" id="prefix" value="<?= set_value('prefix', $result->prefix); ?>" placeholder="Prefix" class="form-control" onchange="myfunction();" />
                                        <select class="form-control" name="prefixposition" style="width: 50px;" id="prefixposition">
                                            <option value="1" <?= 1 == $result->prefix_position ? "selected" : '' ?>>1</option>
                                            <option value="2" <?= 2 == $result->prefix_position ? "selected" : '' ?>>2</option>
                                            <option value="3" <?= 3 == $result->prefix_position ? "selected" : '' ?>>3</option>
                                        </select>
                                    </div>

                                    <label class="col-sm-2 control-label" for="start_point">Series Start From <span class="text-danger">*</span></label>
                                    <div class="col-sm-4 form-group">
                                        <input autocomplete="off" type="text" name="start_point" id="start_point" value="<?= set_value('start_point', $result->start_point); ?>" placeholder="Series Start From" class="form-control" />
                                        <select class="form-control" name="start_point_position" style="width: 50px;" id="start_point_position">
                                            <option value="1" <?= 1 == $result->start_point_position ? "selected" : '' ?>>1</option>
                                            <option value="2" <?= 2 == $result->start_point_position ? "selected" : '' ?>>2</option>
                                            <option value="3" <?= 3 == $result->start_point_position ? "selected" : '' ?>>3</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="start_point">Capitalization</label>
                                    <div class="col-sm-4 form-group">
                                        <input autocomplete="off" type="text" name="symbl" id="symbl" value="<?= set_value('symbl', $result->symbl); ?>" placeholder="Example / : # $" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <input type="submit" class="btn btn-primary fr" value="Save">
                                        <a href="<?= base_url(); ?>series/series_list" class="btn btn-danger" title="Cancel">
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