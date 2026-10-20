<div class="row text-dark">
    <div class="col-md-3 col-sm-6 col-lg-3">
        <div class="form-group">
            <label for="service">Service<span class="text-danger">*</span></label>
            <select class="form-control select2" disabled required name="service" id="service">
                <option value="">Select Service</option>
                <?php
                $this->db->select('*');
                $this->db->from('crm_service');
                $this->db->where('status', 1);
                $crm_service = $this->db->get()->result();

                if ($crm_service) {
                    foreach ($crm_service as $category) {
                ?>
                        <option value="<?= $category->id?>" <?= $result->service_id == $category->id ? 'selected' : '' ?>><?= $category->service_name ?></option>
                        

                <?php
                    }
                }
                ?>
            </select>

        </div>

    </div>
    <div class="col-md-3 col-sm-6 col-lg-3">
        <div class="form-group">
            <label for="remark">SUB-SERVICE NAME:<span class="text-danger">*</span></label>
            <input type="text" id="sub_service_name" disabled name="sub_service_name" placeholder="Sub-Service Name" required class="form-control" value="<?= set_value('sub_service_name',$result->sub_service_name) ?>">
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('sub_service_name'); ?>

        </span>
    </div>

    <div class="col-md-3 col-sm-6 col-lg-3">
            <div class="form-group">
                <label for="sub_name">SUB-NAME:<span class="text-danger">*</span></label>
                <input type="text" id="sub_name" readonly name="sub_name" placeholder="Sub Name" required class="form-control" value="<?= set_value('sub_name', $result->sub_name) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('sub_name'); ?>

            </span>
        </div>

    <div class="col-md-3 col-sm-6 col-lg-3">
        <div class="form-group">
            <label for="charge">CHARGE AMOUNT:<span class="text-danger">*</span></label>
            <input type="number" min="0" id="charge" disabled name="charge" placeholder="Charge Amount" required class="form-control" value="<?= set_value('charge',$result->service_charge) ?>">
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('charge'); ?>

        </span>
    </div>



    <div class="col-md-3">
        <div class="form-group">
            <label for="status">Status<span class="text-danger"></span></label>
            <select class="form-control" disabled name="status">
                <option value="1" <?= $result->status == 1 ? 'selected' : '' ?> class="text-success">Active</option>
                <option value="0" <?= $result->status == 0 ? 'selected' : ''?> class="text-danger">Deactivate</option>
            </select>
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('status'); ?>
        </span>
    </div>
</div>
<script>
    function closeModel() {
        $("#viewModel").modal("hide");
    }
</script>