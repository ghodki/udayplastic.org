<!-- Card stats -->
<div class="row">
    <div class="" id="respnce_msg" style="opacity: 500; display: none;"></div>

    <form action="<?= base_url() ?>party/update_party" method="post">
        <form id="updateParty" class="col-sm-12 col-md-12 col-lg-12" action="<?= base_url() ?>party/update_party" method="POST" enctype="multipart/form-data" onsubmit="updateParty(event)">

            <div class="row">
                <input type="hidden" name="id" value="<?= $party->id ?>">
                <div class="form-group col-sm-3">
                    <?php
                    $tax = $this->user_model->getResult('accounting_group');
                    // echo '<pre>';
                    // print_r($tax);
                    // die;
                    ?>
                    <label class="control-label" for="group">Accounting Group: <span class="text-danger">*</span></label>
                    <select id="group" name="group" class="form-control" required>
                        <option value="">-Select-</option>
                        <option value="<?= $party->group_head ?>" <?= set_value('group', $party->group_head) == $party->group_head ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $party->group_head], 'name'); ?></option>

                        <?php

                        if ($role == 1) {
                            if (isset($tax) && !empty($tax)) {
                                foreach ($tax as $units) {
                        ?>
                                    <option value="<?= $units->id ?>" <?= set_value('group', $party->group_head) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>

                                    <?php
                                }
                            }
                        } else {
                            if (isset($tax) && !empty($tax)) {
                                foreach ($tax as $units) {

                                    $user_id = $this->session->userdata('user_id');
                                    $comp_id = $this->session->userdata('comp_id');

                                    $where = ['user_id' => $user_id, 'comp_id' => $comp_id, 'bill_id' => $units->id];
                                    $resu = $this->user_model->getRow('accountant_status', $where);
                                    // $this->session->userdata('user_id');
                                    if (isset($resu) && !empty($resu)) {
                                        if ($resu->status == 1) {
                                    ?>
                                            <option value="<?= $units->id ?>" <?= set_value('group', $units->id) == $units->id ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $units->id], 'name'); ?></option>

                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $units->id], 'name'); ?></option>


                        <?php
                                    }
                                }
                            }
                        }

                        ?>

                    </select>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" for="name">Name<span class="text-danger">*</span></label>
                    <input autocomplete="off" type="text" name="name" id="name" value="<?= set_value('name', $party->name) ?>" placeholder="Name" class="form-control textOnly" required="">
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('name'); ?>

                    </span>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" for="contact">Contact<span class="text-danger"></span></label>
                    <input autocomplete="off" type="number" name="contact" id="contact" value="<?= set_value('contact', $party->contact) ?>" placeholder="Contact" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" class="form-control">
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('contact'); ?>

                    </span>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" for="email">Email<span class="text-danger"></span></label>
                    <input autocomplete="off" type="email" name="email" id="email" value="<?= set_value('email', $party->email) ?>" placeholder="Email" class="form-control">
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('email'); ?>

                    </span>
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label" for="address_1">Address<span class="text-danger"></span></label>
                    <input autocomplete="off" type="text" name="address_1" id="address_1" value="<?= set_value('address_1', $party->address_1) ?>" placeholder="Address" class="form-control">
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('address_1'); ?>

                    </span>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" for="state">state <span class="text-danger">*</span></label>
                    <select name="state" id="state" class="form-control">
                        <?php
                        $where = ['id' => $party->state];
                        $acgroup = $this->user_model->getRow('state_list', $where);
                        if ($acgroup) {
                            $name = $acgroup->state;
                        }
                        ?>
                        <option value="<?= $party->state ?>" <?= set_value('state') == $party->state ? 'selected' : ''; ?>><?= $name; ?></option>

                        <?php
                        $acgroup = $this->user_model->getResult('state_list');
                        if ($acgroup) {
                            foreach ($acgroup as $row) {

                        ?>
                                <option value="<?= $row->id ?>" data-id="<?= $row->code ?>" data-state="<?= $row->state ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>

                                <!-- <option value="<?= $ag->id; ?>"><?= $ag->state; ?></option> -->
                        <?php
                            }
                        }

                        ?>

                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label" for="state_code">Code</label>
                    <input autocomplete="off" type="text" readonly="" name="state_code" id="state_code" value="<?= set_value('code', $party->state_code) ?>" placeholder="Code" class="form-control" required="">
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" for="state">City <span class="text-danger">*</span></label>
                    <select name="city" id="city" class="form-control searchselect" data-live-search="true" required>
                        <?php
                        $where = ['id' => $party->city];
                        $acgroup = $this->user_model->getRow('districts', $where);
                        if ($acgroup) {
                            $name = $acgroup->name;
                        } else {
                        ?>
                            <option value="">Select City</option>
                        <?php
                        }
                        ?>

                        <option value="<?= $party->city ?>" <?= set_value('city') == $party->city ? 'selected' : ''; ?>><?= $name; ?></option>

                        <?php $states = $this->db->get('districts')->result();
                        if ($states) {
                            foreach ($states as $row) { ?>
                                <option value="<?= $row->id ?>" <?= set_value('city') == $row->id ? 'selected' : ''; ?>><?= $row->name; ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>



                <div class="form-group col-sm-3">
                    <label class="control-label" for="pin">Pin Code</label>
                    <input autocomplete="off" type="text" name="pin" id="pin" placeholder="Pin Code" value="<?= set_value('pin', $party->pin) ?>" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('pin'); ?>

                    </span>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" for="pan">Pan No</label>
                    <input autocomplete="off" type="text" name="pan" id="pan" placeholder="Pan No" value="<?= set_value('pan', $party->pan) ?>" class="form-control toUpper" maxlength="10">
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('pan'); ?>

                    </span>
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label" for="credit_limit">Credit</label>
                    <input autocomplete="off" type="text" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="<?= set_value('credit_limit', $party->credit_limit) ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" for="gstType">GST Type</label>
                    <select name="gstType" id="gstType" class="form-control">
                        <!-- <option value=""></option> -->
                        <option value="<?= $party->gstType; ?>" <?= set_value('gstType') ?>><?= $party->gstType; ?></option>

                        <option value="Regular" <?= set_value('gstType') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                        <option value="Composite" <?= set_value('gstType') == 'Composite' ? 'selected' : ''; ?>>Composite</option>
                        <option value="N/A" <?= set_value('gstType') == 'N/A' ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>
                <div class="form-group col-sm-3" id="gstTypeVal">
                    <label class="control-label" for="gst">GST No</label>
                    <input autocomplete="off" type="text" name="gst" id="gst" placeholder="GST No" value="<?= $party->gst; ?>" class="form-control" maxlength="15">
                </div>
                <div class="form-group col-sm-3 ">
                    <label class="control-label" for="opening_balance">Opening Balance</label>
                    <input autocomplete="off" type="text" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="<?= $party->opening_balance ?>" class="form-control">
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label" for="opBalnceType">Type</label>
                    <select name="opBalnceType" id="opBalnceType" class="form-control">
                        <!-- <option value="">-Select-</option> -->
                        <option value="<?= $party->opBalnceType ?>" <?= set_value('opBalnceType'); ?>><?= $party->opBalnceType; ?></option>
                        <option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR</option>
                        <option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR</option>
                    </select>
                </div>
            </div>



            <div class="row">
                <div class="form-group ml-auto pr-5">
                    <input autocomplete="off" type="submit" class="btn btn-primary fr" value="Update">
                </div>
            </div>

        </form>
</div>
<script>
    var inputs = document.querySelectorAll("input,select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                //  alert(e.which)
                nextInput[0].focus();
            }
        })
    }
</script>
<script>
    jQuery.noConflict();

    function updateParty(e) {
        e.preventDefault();
        var url = $("#updateParty").attr("action");
        var form = $("#updateParty")[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json', // data type
            success: function(customerList) {
                console.log(customerList);
                if (customerList.status == true) {
                    toastr.success(customerList.msg);
                    setTimeout(function() {
                        window.location.href = "<?= base_url() ?>invent/party"
                    }, 2000)

                } else {
                    $("#respnce_msg").show();
                    $("#respnce_msg").addClass("text");
                    $("#respnce_msg").addClass("text-danger");
                    $("#respnce_msg").html(customerList.msg);

                }
            },

        });
    }
</script>

<script>
    $(document).ready(function() {
        $('#gstTypeVal').hide()
        $('.searchselect select2').select2();

        //   $('#state').change(function() {
        //      var code = $(this).find(':selected').data('id');
        //      state = $(this).find(':selected').data('state');
        //      $('#state_code').val(code);

        //   });

        $('#gstType').change(function() {
            if ($(this).find(':selected').val() == 'Regular' || $(this).find(':selected').val() == 'Composite') {
                $('#gstTypeVal').show();
                $('#gst').attr('required', true);
            } else {
                $('#gstTypeVal').hide();
                $('#gst').attr('required', false);
            }
        });
        <?php if (isset($error) && !empty($error)) { ?>
            $('#gstTypeVal').show();
        <?php } ?>
    });
    $('#state').change(function() {
        var code = $(this).find(':selected').data('id');
        $('#code').val(code);
    });
</script>