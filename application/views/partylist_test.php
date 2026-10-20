<table class="table table-bordered table-responsive-sm dataTable" style="overflow:scroll" id="example12">
    <thead class="text-dark">
        <tr>
            <th style="color:black!important;">Name</th>
            <th style="color:black!important;">Contact</th>
            <th style="color:black!important;">Email</th>
            <th style="color:black!important;">Edit</th>
        </tr>
    </thead>
    <tbody id="tbody">
        <?php
        $role = $this->session->userdata('roles');
        //   $party = $this->db->select('party_id, name, city')->order_by('name', 'ASC')->get_where('parties', ['status' => 1])->result();
        $this->db->select('*');
        $this->db->from('parties');
        $this->db->where('status', 1);
        $this->db->where('comp_id', $this->session->userdata('comp_id'));
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where_in('group_head');
        $this->db->limit(20);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get();
        $partys = $query->result();

        if ($partys) {

            foreach ($partys as $key => $party) {
        ?>

                <tr style="margin-top:10px;" id="trp<?= $key ?>" tabindex="-1" onkeyup="keyUpForParty(event,<?= $key ?>)" onkeypress="getCustomerDetail(<?= $party->id ?>,<?= $key ?>)" onkeydown="keyDownForParty(event,<?= $key ?>)" onclick="getCustomerDetail(<?= $party->id ?>,<?= $key ?>)">

                    <td>
                        <?= $party->name ?></td>
                    <td><?= $party->contact ?></td>
                    <td><?= $party->email ?></td>
                    <td><a href="#" onmouseover="partyProfileEdit(<?= $key ?>,<?= $party->id ?>,event)">Edit</a>
                    </td>
                <tr id="hiddendata<?= $key ?>" style="display:none;width:144%">
                    <td>
                        <div class="" id="partyMsg<?= $key ?>" style="opacity: 500; display: none;"></div>

                        <!-- <div class="row"> -->
                        <!-- <h1 class="text-center">Party Update</h1> -->
                        <form action="#" method="post" onsubmit="orBanaoForm(<?= $key ?>,event)" id="orBanaoForm<?= $key ?>">
                            <div class="row">

                                <input type="hidden" name="id" value="<?= $party->id ?>" id="partyId<?= $key ?>">

                                <!-- <input type="hidden" name="id" value="<?= $party->id ?>"> -->
                                <div class="form-group col-sm-3">
                                    <?php
                                    $tax = $this->user_model->getResult('accounting_group');

                                    ?>
                                    <label class="control-label" for="group">Accounting Group:
                                        <span class="text-danger">*</span></label>

                                    <select id="group<?= $key ?>" name="group" class="form-control" required>
                                        <option value="">-Select-
                                        </option>
                                        <option value="<?= $party->group_head ?>" <?= set_value('group', $party->group_head) == $party->group_head ? 'selected' : ''; ?>>
                                            <?= get_title('accounting_group', ['id' => $party->group_head], 'name'); ?>
                                        </option>

                                        <?php

                                        if ($role == 1) {
                                            if (isset($tax) && !empty($tax)) {
                                                foreach ($tax as $units) {
                                        ?>
                                                    <option value="<?= $units->id ?>" <?= set_value('group', $party->group_head) == $units->id ? 'selected' : ''; ?>>
                                                        <?= $units->name; ?>
                                                    </option>

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
                                                            <option value="<?= $units->id ?>" <?= set_value('group', $units->id) == $units->id ? 'selected' : ''; ?>>
                                                                <?= get_title('accounting_group', ['id' => $units->id], 'name'); ?>
                                                            </option>

                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>>
                                                            <?= get_title('accounting_group', ['id' => $units->id], 'name'); ?>
                                                        </option>


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
                                    <input autocomplete="off" type="text" name="name" id="name<?= $key ?>" value="<?= set_value('name', $party->name) ?>" placeholder="Name" class="form-control textOnly" required="">
                                    <span class="col-sm-4 control-label">
                                        <?php echo form_error('name'); ?>

                                    </span>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="contact">Contact<span class="text-danger"></span></label>
                                    <input autocomplete="off" type="number" name="contact" id="contact<?= $key ?>" value="<?= set_value('contact', $party->contact) ?>" placeholder="Contact" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" class="form-control">
                                    <span class="col-sm-4 control-label">
                                        <?php echo form_error('contact'); ?>

                                    </span>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="email">Email<span class="text-danger"></span></label>
                                    <input autocomplete="off" type="email" name="email" id="email<?= $key ?>" value="<?= set_value('email', $party->email) ?>" placeholder="Email" class="form-control">
                                    <span class="col-sm-4 control-label">
                                        <?php echo form_error('email'); ?>

                                    </span>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="address_1">Address<span class="text-danger"></span></label>
                                    <input autocomplete="off" type="text" name="address_1" id="address_1<?= $key ?>" value="<?= set_value('address_1', $party->address_1) ?>" placeholder="Address" class="form-control">
                                    <span class="col-sm-4 control-label">
                                        <?php echo form_error('address_1'); ?>

                                    </span>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="state">State <span class="text-danger">*</span></label>
                                    <select name="state" onchange="stateChanger(event,<?= $key ?>)" id="state<?= $key ?>" class="form-control">
                                        <?php
                                        $where = ['id' => $party->state];
                                        $acgroup = $this->user_model->getRow('state_list', $where);
                                        if ($acgroup) {
                                            $name = $acgroup->state;
                                        }
                                        ?>
                                        <option value="<?= $party->state ?>" <?= set_value('state') == $party->state ? 'selected' : ''; ?>>
                                            <?= $name; ?></option>

                                        <?php
                                        $acgroup = $this->user_model->getResult('state_list');
                                        if ($acgroup) {
                                            foreach ($acgroup as $row) {

                                        ?>
                                                <option value="<?= $row->id ?>" data-id="<?= $row->code ?>" data-state="<?= $row->state ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>>
                                                    <?= $row->state; ?></option>

                                                <!-- <option value="<?= $ag->id; ?>"><?= $ag->state; ?></option> -->
                                        <?php
                                            }
                                        }

                                        ?>

                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="state_code">Code</label>
                                    <input autocomplete="off" type="text" readonly="" name="state_code" id="state_code<?= $key ?>" value="<?= set_value('code', $party->state_code) ?>" placeholder="Code" class="form-control" required="">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="state">City <span class="text-danger">*</span></label>
                                    <select name="city" id="city<?= $key ?>" class="form-control" data-live-search="true" required>
                                        <?php
                                        $where = ['id' => $party->city];
                                        $acgroup = $this->user_model->getRow('districts', $where);
                                        if ($acgroup) {
                                            $name = $acgroup->name;
                                        } else {
                                        ?>
                                            <option value="">Select City
                                            </option>
                                        <?php
                                        }
                                        ?>

                                        <option value="<?= $party->city ?>" <?= set_value('city') == $party->city ? 'selected' : ''; ?>>
                                            <?= $name; ?></option>

                                        <?php $states = $this->db->get('districts')->result();
                                        if ($states) {
                                            foreach ($states as $row) { ?>
                                                <option value="<?= $row->id ?>" <?= set_value('city') == $row->id ? 'selected' : ''; ?>>
                                                    <?= $row->name; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="pin">Pin Code</label>
                                    <input autocomplete="off" type="text" name="pin" id="pin<?= $key ?>" placeholder="Pin Code" value="<?= set_value('pin', $party->pin) ?>" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control">
                                    <span class="col-sm-4 control-label">
                                        <?php echo form_error('pin'); ?>

                                    </span>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="pan">Pan No</label>
                                    <input autocomplete="off" type="text" name="pan" id="pan<?= $key ?>" placeholder="Pan No" value="<?= set_value('pan', $party->pan) ?>" class="form-control toUpper" maxlength="10">
                                    <span class="col-sm-4 control-label">
                                        <?php echo form_error('pan'); ?>

                                    </span>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="credit_limit">Credit</label>
                                    <input autocomplete="off" type="text" name="credit_limit" id="credit_limit<?= $key ?>" placeholder="Credit Limit" value="<?= set_value('credit_limit', $party->credit_limit) ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="gstType">GST Type</label>
                                    <select name="gstType" id="gstType<?= $key ?>" class="form-control">
                                        <!-- <option value=""></option> -->
                                        <option value="<?= $party->gstType; ?>" <?= set_value('gstType') ?>>
                                            <?= $party->gstType; ?>
                                        </option>

                                        <option value="Regular" <?= set_value('gstType') == 'Regular' ? 'selected' : ''; ?>>
                                            Regular</option>
                                        <option value="Composite" <?= set_value('gstType') == 'Composite' ? 'selected' : ''; ?>>
                                            Composite</option>
                                        <option value="N/A" <?= set_value('gstType') == 'N/A' ? 'selected' : ''; ?>>
                                            N/A</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3" id="gstTypeVal">
                                    <label class="control-label" for="gst">GST No</label>
                                    <input autocomplete="off" type="text" name="gst" id="gst<?= $key ?>" placeholder="GST No" value="<?= $party->gst; ?>" class="form-control" maxlength="15">
                                </div>
                                <div class="form-group col-sm-3 ">
                                    <label class="control-label" for="opening_balance">Opening
                                        Balance</label>
                                    <input autocomplete="off" type="text" name="opening_balance" id="opening_balance<?= $key ?>" placeholder="Opening Balance" value="<?= $party->opening_balance ?>" class="form-control">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label class="control-label" for="opBalnceType">Type</label>
                                    <select name="opBalnceType" id="opBalnceType<?= $key ?>" class="form-control">
                                        <!-- <option value="">-Select-</option> -->
                                        <option value="<?= $party->opBalnceType ?>" <?= set_value('opBalnceType'); ?>>
                                            <?= $party->opBalnceType; ?>
                                        </option>
                                        <option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>
                                            CR</option>
                                        <option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>
                                            DR</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 mt-4">
                                    <div class="form-group">
                                        <button type="button" id="clik<?= $key ?>" onclick="partyProfileEdit(<?= $key ?>,<?= $party->id ?>,event)" class="btn btn-secondary">Close</button>
                                        <button class="btn btn-primary">Update
                                            Party</button>
                                    </div>
                                </div>
                            </div>
                    </td>
                </tr>
                </tr>
            <?php
            }
            ?>


        <?php

        }
        ?>
    </tbody>


</table>
<script>
    $('#closepartymodel').click(function() {
        $('#exampleModals').modal('hide');
    })


    function orBanaoForm(row, e) {
        e.preventDefault();
        // $(".searchselect").select2();
        var form = $("#orBanaoForm"+row);
        var data = new FormData(form);
        //(data);
        console.log(data);
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: '<?= base_url() ?>party/updateparty',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(customer) {
                // console.log(customer);
                var customer = JSON.parse(customer);
                if (customer.status == true) {
                    document.getElementById('clik' + row).click();
                    alert("Party Updated");
                } else {
                    $("#partyMsg" + row).show();
                    $("#partyMsg" + row).addClass("text");
                    $("#partyMsg" + row).addClass("text-danger");
                    $("#partyMsg" + row).html(customer.msg);
                }

            },

        });
    }
</script>