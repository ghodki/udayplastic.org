<div class="row text-dark">
    <script>
    function chekedServices(service_id, party_id) {
        if (!isNaN(service_id)) {
            let checked = document.getElementById("amans" + service_id);
            $("#append_data_aman" + service_id).html("");
            if (checked.checked == true) {
                $.ajax({
                    url: "<?= base_url() ?>crm/status/chekedSubservice",
                    type: 'post',
                    data: {
                        service_id: service_id,
                        status_id: party_id
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            $("#append_data_aman" + service_id).append(response.data);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        } else {
            alert("please provide valid info");
        }

    }
    </script>
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="remark">Status NAME:<span class="text-danger"></span></label>
            <textarea id="Status_name" name="status_name" placeholder="Status Name" required type="text"
                class="form-control" readonly value="<?= set_value('Status_name') ?>"><?= $result->name ?></textarea>
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('Status_name'); ?>

        </span>
    </div>
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="short_name">SHORT NAME:<span class="text-danger">*</span></label>
            <textarea id="short_name" name="short_name" placeholder="Short Name" required type="text"
                class="form-control" readonly
                value="<?= set_value('short_name') ?>"><?= $result->short_name ?></textarea>
        </div>
        <span class="col-sm-4 control-label">
            <?php echo form_error('short_name'); ?>

        </span>
    </div>
    <div class="col-md-12">
        <div class="form-group">

            <label for="status">Status<span class="text-danger"></span></label>
            <select class="form-control" disabled name="status">
                <option value="0" <?= $result->status == 0 ? 'selected' : '' ?> class="text-danger">Pending</option>
                <option value="1" <?= $result->status == 1 ? 'selected' : '' ?> class="text-success">Approved</option>

            </select>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12 row">
            <?php
    $getResu = $this->user_model->getResult("crm_service");
    if ($getResu) {
        $getArrya = [];
        foreach ($getResu as $category) {
    ?>
            <div class="col-md-2 col-sm-2 col-lg-2">
                <div class="form-group">
                    <label for="service"><?php echo $category->service_name  ?><span class="text-danger"></span></label>
                    <?php
                    $services_ids = json_decode($result->services_id);
                    if (!empty($services_ids)) {
                        if (in_array($category->id, $services_ids)) {
                    ?>
                    <input name="service[]" checked type="checkbox" value="<?= $category->id ?>"
                        id="amans<?= $category->id ?>" onchange="checkService(<?= $category->id ?>)"
                        class="form-check-input form-control">
                    <script>
                    chekedServices(<?= $category->id ?>, <?= $result->id ?>);
                    </script>
                    <?php
                        }else
                        {
                            ?>
                    <input name="service[]" type="checkbox" value="<?= $category->id ?>" id="amans<?= $category->id ?>"
                        onchange="checkService(<?= $category->id ?>)" class="form-check-input form-control">

                    <?php
                        }
                    }
                    ?>


                    <?php

                    if (empty($services_ids)) {
                    ?>
                    <input name="service[]" type="checkbox"  value="<?= $category->id ?>" id="amans<?= $category->id ?>"
                        onchange="checkService(<?= $category->id ?>)" class="form-check-input form-control">
                    <?php
                    }
                    ?>

                </div>
            </div>
            <?php
        }
    }
    ?>
        </div>
        <?php
$getResu = $this->user_model->getResult("crm_service");
if ($getResu) {
    foreach ($getResu as $category) {
?>
        <div class="col-md-12 row" id="append_data_aman<?= $category->id ?>"></div>
        <?php
    }
}
?>


    </div>
</div>
<script>
function closeModel() {
    $("#viewModel").modal("hide");
}
</script>