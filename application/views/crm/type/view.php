<div class="row text-dark">

    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="remark">Type NAME:<span class="text-danger"></span></label>
            <textarea id="type_name" name="type_name" placeholder="type Name" required type="text" class="form-control" readonly value="<?= set_value('type_name') ?>"><?= $result->name ?></textarea>
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('type_name'); ?>

        </span>
    </div>
    <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="short_name">SHORT NAME:<span class="text-danger">*</span></label>
                <textarea id="short_name" name="short_name" placeholder="Short Name" required type="text" class="form-control" readonly value="<?= set_value('short_name') ?>"><?= $result->short_name ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('short_name'); ?>

            </span>
        </div>
    <div class="col-md-12">
        <div class="form-group">

            <label for="status">Status<span class="text-danger"></span></label>
            <select class="form-control" disabled name="type">
                <option value="0" <?= $result->status == 0 ? 'selected' : '' ?> class="text-danger">Pending</option>
                <option value="1" <?= $result->status == 1 ? 'selected' : '' ?> class="text-success">Approved</option>

            </select>
        </div>
    </div>
</div>
<script>
    function closeModel() {
        $("#viewModel").modal("hide");
    }
</script>