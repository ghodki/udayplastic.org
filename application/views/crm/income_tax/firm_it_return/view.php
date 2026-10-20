<div class="row text-dark">

    <input type="hidden" name="id" value="<?= $result->id ?>">
    <div class="col-md-4">
        <div class="form-group">
            <label for="Category">Party<span class="text-danger">*</span></label>
            <span class="partyDataUpdate"></span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <?php
            $last_month = date('Y-m-d');
            ?>
            <label for="date">Date<span class="text-danger">*</span></label>
            <input readonly id="date" name="date" placeholder="Date" type="date" required class="form-control" value="<?= set_value('date', $result->date) ?>">
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('remark'); ?>
        </span>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="status">Status<span class="text-danger"></span></label>
            <select class="form-control" disabled name="status">
                <?php
                $res = $this->user_model->getStatusForService();
                if ($res) {
                    foreach ($res as $row) {
                        if ($result->status == $row->id) {
                            $selected = "selected";
                        } else {
                            $selected = "";
                        }
                ?>
                        <option value="<?= $row->id ?>" <?= $selected ?>><?= $row->name ?></option>

                <?php
                    }
                }
                ?>
                <option value="0" <?= $result->status == 0 ? 'selected' : '' ?> class="text-danger">Pending</option>
                <option value="1" <?= $result->status == 1 ? 'selected' : '' ?> class="text-success">Approved</option>
            </select>
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('status'); ?>
        </span>
    </div>

    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="remark">Remark<span class="text-danger"></span></label>
            <textarea id="remark" readonly name="remark" placeholder="Remark" type="text" class="form-control" value="<?= set_value('remark') ?>"><?= $result->remark ?></textarea>
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('remark'); ?>
        </span>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" onclick="closeModel()">Close</button>
</div>

<script>
    partyData(<?= $result->party_id ?>);

    function closeModel() {
        $("#viewModel").modal("hide");
    }

    function partyData(party_id) {
        $.ajax({
            method: "POST",
            url: "<?= base_url('crm/master/getPartyFromSubServiceSelected'); ?>",
            cache: false,
            data: {
                party_id: party_id,
            },
            success: function(value) {
                $('.partyDataUpdate').html(value);
            }
        });
    }
</script>