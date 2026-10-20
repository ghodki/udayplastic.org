<div id="message" class="text-center"></div>
<form id="signUpForm" autocomplete="off" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/master/office_expence_save" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row text-dark">

            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="applicant_name">Applicant name<span class="text-danger">*</span></label>
                    <input disabled id="applicant_name" name="applicant_name" placeholder="Applicant Name" value="<?= $result->applicant_name ?>" type="text" required class="form-control">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('applicant_name'); ?>

                </span>
            </div>

            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="amount">Amount<span class="text-danger">*</span></label>
                    <input disabled id="amount" name="amount" min="0" placeholder="Amount" type="number" value="<?= $result->amount ?>" required class="form-control">
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('amount'); ?>

                </span>
            </div>

            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="type">Type<span class="text-danger">*</span></label>
                    <select disabled class="form-control select2" required name="type" id="type">
                        <option value="">Select Type</option>

                        <?php
                        $this->db->select('*');
                        $this->db->from('crm_type');
                        $this->db->where('status', 1);
                        $getResu = $this->db->get()->result();
                        if ($getResu) {
                            foreach ($getResu as $category) {
                        ?>
                                <option value="<?= $category->id ?>" <?= $result->type == $category->id ? 'selected' : '' ?>><?= $category->name ?></option>


                        <?php
                            }
                        }
                        ?>
                    </select>

                </div>

            </div>
            <!-- <style>
                                #refByList {
                                    position: absolute;
                                    z-index: 999;
                                    background: #fff;
                                }
                            </style> -->





            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="status">Status<span class="text-danger">*</span></label>
                    <select disabled class="form-control select2" required name="status" id="status">
                        <option value="">Select Status</option>

                        <?php
                        $this->db->select('*');
                        $this->db->from('crm_status');
                        $this->db->where('status', 1);
                        $getResu = $this->db->get()->result();
                        if ($getResu) {
                            foreach ($getResu as $category) {
                        ?>
                                <option value="<?= $category->id ?>" <?= $result->status == $category->id ? 'selected' : '' ?>><?= $category->name ?></option>


                        <?php
                            }
                        }
                        ?>
                    </select>

                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="form-group">
                    <label for="remark">Remark<span class="text-danger"></span></label>
                    <textarea id="remark" name="remark" disabled placeholder="Remark" type="text" class="form-control"><?= $result->reamrk ?></textarea>
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('remark'); ?>

                </span>
            </div>
        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </div>
</form>