<div class="row text-dark">

    <input type="hidden" name="id" value="<?= $result->id ?>">
    <div class="col-md-6 col-sm-6 col-lg-6">
        <div class="form-group">
            <label for="Category">Party<span class="text-danger">*</span></label>
            <span class="partyDataUpdate"></span>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6">
        <div class="form-group">
            <?php
            $last_month = date('Y-m-d', strtotime('-1 month', time()));
            ?>
            <label for="date">Date<span class="text-danger">*</span></label>
            <input readonly id="date" name="date" placeholder="Date" type="date" required class="form-control" value="<?= set_value('date', $result->date) ?>">
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('remark'); ?>
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
    function closeModel() {
        $("#viewModel").modal("hide");
    }

    partyData(<?= $result->party_id ?>);

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
                // $('select').selectize({
                //     sortField: 'text',
                // });


            }
        });
    }
</script>