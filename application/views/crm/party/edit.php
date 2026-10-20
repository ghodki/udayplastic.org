<script>

function chekedServices(service_id,party_id) {
        if (!isNaN(service_id)) {
            let checked = document.getElementById("amans" + service_id);
            $("#append_data_aman" + service_id).html("");
            if (checked.checked == true) {
                $.ajax({
                    url: "<?= base_url() ?>crm/service/chekedSubservice",
                    type: 'post',
                    data: {
                        service_id: service_id,
                        party_id:party_id
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
<form id="updateForm" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>crm/party/update" method="POST" enctype="multipart/form-data">

    <div class="row text-dark">
        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="name">Firm Name<span class="text-danger">*</span></label>
                <input id="name" name="name" placeholder="Party Name" required type="text" class="form-control" value="<?= set_value('name', $result->party_name) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('name'); ?>

            </span>
        </div>

        <input type="hidden" name="id" value="<?= $result->id ?>">

        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="authorised_name">Authorised Name<span class="text-danger"></span></label>
                <input id="authorised_name" name="authorised_name" placeholder="Authorised  Name" type="text" class="form-control" value="<?= set_value('authorised_name', $result->authorised_name) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('authorised_name'); ?>

            </span>
        </div>

        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="number">Mobile No<span class="text-danger"></span></label>

                <input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= set_value('number', $result->number) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('number'); ?>

            </span>
        </div>
        <div class="col-md-4  col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="email">Email<span class="text-danger"></span></label>

                <input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= set_value('email', $result->email) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('email'); ?>

            </span>
        </div>
        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="gst">GST No<span class="text-danger"></span></label>

                <input name="gst" placeholder="GST Number" type="text" class="form-control" value="<?= set_value('gst', $result->gst_no) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('gst'); ?>

            </span>
        </div>
        <div class="col-md-4 col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="address">Address<span class="text-danger"></span></label>

                <input name="address" placeholder="Address" type="text" class="form-control" value="<?= set_value('address', $result->address) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('address'); ?>

            </span>
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
                                        chekedServices(<?= $category->id ?>,<?= $result->id ?>);
                                    </script>
                            <?php
                                }else
                                {
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

        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="incometax_file_no">Income Tax File No<span class="text-danger"></span></label>

                <input name="incometax_file_no" placeholder="Income Tax File No" type="text" class="form-control" value="<?= set_value('incometax_file_no', $result->incometax_file_no) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('incometax_file_no'); ?>

            </span>
        </div>

        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="tds_file_no">TDS File No<span class="text-danger"></span></label>

                <input name="tds_file_no" placeholder="TDS File No" type="text" class="form-control" value="<?= set_value('tds_file_no', $result->tds_file_no) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('tds_file_no'); ?>

            </span>
        </div>

        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="gst_file_no">GST File No<span class="text-danger"></span></label>

                <input name="gst_file_no" placeholder="GST File No" type="text" class="form-control" value="<?= set_value('gst_file_no', $result->gst_file_no) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('gst_file_no'); ?>

            </span>
        </div>

        <div class="col-md-4 col-sm-6 col-lg-4">
            <div class="form-group">
                <label for="pt_file_no">PT File No<span class="text-danger"></span></label>

                <input name="pt_file_no" placeholder="PT File No" type="text" class="form-control" value="<?= set_value('pt_file_no', $result->pt_file_no) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('pt_file_no'); ?>

            </span>
        </div>

    </div>

    <div class="mt-3 row text-dark">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="gst_username">GST Username<span class="text-danger"></span></label>
                <input id="gst_username" name="gst_username" placeholder="GST Username" type="text" class="form-control" value="<?= set_value('gst_username', $result->gst_username) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('gst_username'); ?>

            </span>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="gst_password">GST Password<span class="text-danger"></span></label>
                <input id="gst_password" name="gst_password" placeholder="GST Password" type="text" class="form-control" value="<?= set_value('gst_password', $result->gst_password) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('gst_password'); ?>

            </span>
        </div>
    </div>

    <div class="row mt-2 text-dark">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="tds_username">TDS Username<span class="text-danger"></span></label>
                <input id="tds_username" name="tds_username" placeholder="TDS Username" type="text" class="form-control" value="<?= set_value('tds_username', $result->tds_username) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('tds_username'); ?>

            </span>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="tds_password">TDS Password<span class="text-danger"></span></label>
                <input id="tds_password" name="tds_password" placeholder="TDS Password" type="text" class="form-control" value="<?= set_value('tds_password', $result->tds_password) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('tds_password'); ?>

            </span>
        </div>
    </div>


    <div class="row mt-2 text-dark">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="incomeTax_username">IncomeTax Username<span class="text-danger"></span></label>
                <input id="incomeTax_username" name="incomeTax_username" placeholder="Income Tax Username" type="text" class="form-control" value="<?= set_value('incomeTax_username', $result->incomeTax_username) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('incomeTax_username'); ?>

            </span>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="incomeTax_password">Income Tax Password<span class="text-danger"></span></label>
                <input id="incomeTax_password" name="incomeTax_password" placeholder="Income Tax Password" type="text" class="form-control" value="<?= set_value('incomeTax_password', $result->incomeTax_password) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('incomeTax_password'); ?>

            </span>
        </div>
    </div>


    <div class="row mt-2 text-dark">
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="pt_username">PT Username<span class="text-danger"></span></label>
                <input id="pt_username" name="pt_username" placeholder="PT Username" type="text" class="form-control" value="<?= set_value('pt_username', $result->pt_username) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('pt_username'); ?>

            </span>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="pt_password">PT Password<span class="text-danger"></span></label>
                <input id="pt_password" name="pt_password" placeholder="PT Password" type="text" class="form-control" value="<?= set_value('pt_password', $result->pt_password) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('pt_password'); ?>

            </span>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="ewaybill_username">Eway-Bill Username<span class="text-danger"></span></label>
                <input id="ewaybill_username" name="ewaybill_username" placeholder="Eway-Bill Username" type="text" class="form-control" value="<?= set_value('ewaybill_username', $result->ewaybill_username) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('ewaybill_username'); ?>

            </span>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6">
            <div class="form-group">
                <label for="ewaybill_password">Eway-Bill Password<span class="text-danger"></span></label>
                <input id="ewaybill_password" name="ewaybill_password" placeholder="Eway-Bill Password" type="text" class="form-control" value="<?= set_value('ewaybill_password', $result->ewaybill_password) ?>">
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('tds_password'); ?>

            </span>
        </div>

        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="form-group">
                <label for="remark">Remark<span class="text-danger"></span></label>
                <textarea id="remark" name="remark" placeholder="Remark" type="text" class="form-control" value="<?= set_value('remark') ?>"><?= $result->remark ?></textarea>
            </div>
            <span class="col-sm-4 control-label">
                <?php echo form_error('remark'); ?>

            </span>
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
                    if (response.status == 'success') {
                        $("#messages").html(response.message);
                        toastr.success(response.message);
                        $("#exampleModals").modal("hide");
                        window.location.reload();

                    } else {
                        toastr.error(response.message);

                    }


                }
            });
        })
    })


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

 

    function closeModel() {
        $("#exampleModals").modal("hide");
    }
</script>