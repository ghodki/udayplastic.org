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
                                <h2 style="color: #ffffff; padding: 5px 0px 5px 0px;" class="text-center">
                                    Edit Set Holiday
                                </h2>
                                <form method="POST" action="<?= base_url(); ?>Holiday/updateHoliday">
                                    <input type="hidden" class="form-control" name="id" id="id" value="<?= set_value('id', $holiday->id); ?>" />
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="holiday_id">Holiday Name <span class="text-danger">*</span></label> 
                                                <select name="holiday_id" <?= $holiday->holiday_id == "selected" ? "" : ""; ?> id="holiday_id" class="form-control" title="Holiday Name" required="">
                                                    <option value="" disabled="">Select Holiday</option>
                                                    <?php foreach($result as $value){ ?>
                                                    <option value="<?= $value->id; ?>" <?= $value->id == $holiday->holiday_id ? 'selected' : ''; ?> <?= set_select('holiday_id', $value->id); ?>><?= $value->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?= form_error('holiday_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                                <input type="date" name="start_date" value="<?= set_value('start_date', $holiday->start_date); ?>" id="start_date" class="form-control" title="Start Date" required="" />
                                                <span class="text-danger"><?= form_error('start_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="end_date">End Date <span class="text-danger">*</span></label>
                                                <input type="date" name="end_date" value="<?= set_value('end_date', $holiday->end_date); ?>" id="end_date" class="form-control" title="End Date" required="" />
                                                <span class="text-danger"><?= form_error('end_date'); ?></span>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-2">
                                            <button type="submit" class="btn btn-success">
                                                Save
                                            </button>
                                            <a href="<?= base_url(); ?>Holiday/setHoliday" class="btn btn-danger" title="Cancel">
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