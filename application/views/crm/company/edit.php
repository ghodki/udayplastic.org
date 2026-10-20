<div id="message" class="text-center"></div>
<form id="signUpForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/company/save" method="POST" enctype="multipart/form-data">
    <div class="row text-dark">
        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <lable for="name">Company Name<span class="text-danger">*</span></lable>
                <input id="name" name="name" placeholder="Company Name*" type="text" class="form-control" required value="<?= set_value('name') ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('name'); ?>

            </span>
        </div>
        <!-- <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"> -->
        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
            <lable for="state">Financial Year<span class="text-danger">*</span></lable>

            <select name="financial_year" id="financial_year" class="form-control" required>
                <option value="">-Select-</option>
                <?php $financial_year = $this->db->get('year')->result();
                if ($financial_year) {
                    foreach ($financial_year as $row) { ?>
                        <option value="<?= $row->id ?>" <?= set_value('financial_year') == $row->id ? 'selected' : ''; ?>><?= $row->year; ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <lable for="number">Mobile No<span class="text-danger"></span></lable>

                <input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= set_value('number') ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('number'); ?>

            </span>
        </div>
        <div class="col-md-4  col-sm-6 col-lg-4">
            <div class="form-group">
                <lable for="email">Email<span class="text-danger"></span></lable>

                <input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= set_value('email') ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('email'); ?>

            </span>
        </div>
        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <lable for="gst">GST No<span class="text-danger"></span></lable>

                <input name="gst" placeholder="GST Number" type="text" class="form-control" value="<?= set_value('gst') ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('gst'); ?>

            </span>
        </div>
        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <lable for="contact">Contact<span class="text-danger"></span></lable>

                <input name="contact" placeholder="Contact person" type="text" class="form-control" value="<?= set_value('contact') ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('contact'); ?>

            </span>
        </div>
        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
            <lable for="state">State Name<span class="text-danger">*</span></lable>

            <select name="state" id="state" class="form-control" required>
                <option value="">-Select-</option>
                <?php $states = $this->db->get('state_list')->result();
                if ($states) {
                    foreach ($states as $row) { ?>
                        <option value="<?= $row->id ?>" data-id="<?= $row->code ?>" data-state="<?= $row->state ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-4">
            <div class="form-group">
                <lable for="adddress">Address<span class="text-danger"></span></lable>

                <input name="address" placeholder="Address" type="text" class="form-control" value="<?= set_value('address') ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('address'); ?>

            </span>
        </div>

        <div class="col-md-4 col-sm-6 col-lg-4">
            <lable for="frmt">Billing Format <span class="text-danger"></span></lable>

            <select id="cate" name="cate_id" class="form-control">

                <option value="">Billing Format</option>
                <?php
                $cate = $this->user_model->getResult('billing_format');
                if ($cate) {
                    foreach ($cate as $key => $value) {
                ?>
                        <option value="<?= $value->id ?>" <?= set_value('cate_id') == $value->id ? 'selected' : ''; ?>><?= $value->name; ?></option>

                <?php    }
                }
                ?>
            </select>
            <span class="col-sm-4 control-label">
                <?php echo form_error('cate_id'); ?>

            </span>
        </div>

        <div class="col-md-4 col-sm-6 col-lg-4">
            <lable for="at">Accounting Type<span class="text-danger">*</span></lable>

            <select id="ac" name="ac" class="form-control">
                <option value=''>Accounting Type</option>
                <option value="1">Basic</option>
                <option value="2">Advanced</option>
            </select>
            <span class="col-sm-4 control-label">
                <?php echo form_error('ac'); ?>

            </span>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="submit">Update changes</button>
    </div>
</form>