<script>
    function chekedServices(service_id, party_id) {
        if (!isNaN(service_id)) {
            let checked = document.getElementById("amans" + service_id);
            $("#append_data_aman" + service_id).html("");
            if (checked.checked == true) {
                $.ajax({
                    url: "<?= base_url() ?>crm/type/chekedSubservice",
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
<div id="messages" class="text-center"></div>
<form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/type/update_type" method="POST" enctype="multipart/form-data">

    <div class="row text-dark">

        <input type="hidden" name="id" value="<?= $result->id ?>">
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="form-group">
                <label for="remark">Type NAME:<span class="text-danger"></span></label>
                <textarea id="type_name" name="type_name" placeholder="type Name" required type="text" class="form-control" value="<?= set_value('type_name') ?>"><?= $result->name ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('type_name'); ?>

            </span>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4">
            <div class="form-group">
                <label for="short_name">SHORT NAME:<span class="text-danger">*</span></label>
                <textarea id="short_name" name="short_name" placeholder="Short Name" required type="text" class="form-control" value="<?= set_value('short_name') ?>"><?= $result->short_name ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('short_name'); ?>

            </span>
        </div>
        <div class="col-md-4">
            <div class="form-group">

                <label for="status">Status<span class="text-danger"></span></label>
                <select class="form-control" name="status">
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
                                        <input name="service[]" checked type="checkbox" value="<?= $category->id ?>" id="amans<?= $category->id ?>" onchange="checkService(<?= $category->id ?>)" class="form-check-input form-control">
                                        <script>
                                            chekedServices(<?= $category->id ?>, <?= $result->id ?>);
                                        </script>
                                    <?php
                                    } else {
                                    ?>
                                        <input name="service[]" type="checkbox" value="<?= $category->id ?>" id="amans<?= $category->id ?>" onchange="checkService(<?= $category->id ?>)" class="form-check-input form-control">

                                <?php
                                    }
                                }
                                ?>


                                <?php

                                if (empty($services_ids)) {
                                ?>
                                    <input name="service[]" type="checkbox" value="<?= $category->id ?>" id="amans<?= $category->id ?>" onchange="checkService(<?= $category->id ?>)" class="form-check-input form-control">
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
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModel()">Close</button>
        <button class="btn btn-primary" type="submit">Update</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#updateForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url: contactForm.attr('action'),
                type: 'post',
                data: contactForm.serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.type == 'success') {
                        $("#messages").html(response.message);
                        toastr.success(response.message);
                        $("#exampleModals").modal("hide");
                        myStopFunction();

                    } else {
                        toastr.error(response.message);

                    }


                }
            });
        })
    })

    function closeModel() {
        $("#exampleModals").modal("hide");
    }

    function myStopFunction() {
        clearTimeout(1000);
        window.location.reload();

    }


    function checkService(service_id) {
        if (!isNaN(service_id)) {
            let checked = document.getElementById("amans" + service_id);
            $("#append_data_aman" + service_id).html("");
            if (checked.checked == true) {
                $.ajax({
                    url: "<?= base_url() ?>crm/service/subservices",
                    type: 'post',
                    data: {
                        service_id: service_id,
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