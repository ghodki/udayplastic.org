<form id="addUserForm" method="post" action="<?= base_url() ?>add-customer" onsubmit="onUserFormSubmitHandler(event)">
    <div class="" id="partyMsg" style="opacity: 500; display: none;"></div>
    <div class="row">
        <!-- <div class="col-md-4 mb-2">
            <label for="type" class="">Type <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-control searchselect" required>
                <option value="">-Select-</option>
         
            </select>
        </div> -->

        <div class="col-md-4 mb-2">
            <label for="name" class="">Name <span class="text-danger">*</span></label>
            <input autocomplete="nope" name="name" id="name" class="form-control textOnly" type="text" required="" placeholder="Enter Name">
        </div>

        <div class="col-md-4 mb-2">
            <label for="contact" class="">Mobile <span class="text-danger">*</span></label>
            <input autocomplete="nope" name="contact" id="contact" class="form-control " type="text" onkeypress="return IsNumeric(event);" maxlength="10" minlength="10" placeholder="Enter Mobile" required>
        </div>

        <div class="col-md-4 mb-2">
            <label for="email" class="">Email <span class="text-danger">*</span></label>
            <input autocomplete="nope" name="email" id="email" class="form-control " type="email" placeholder="Enter Email" required>
        </div>
        <div class="col-md-4 mb-2">
            <label for="pan" class="">Pan No</label>
            <input autocomplete="nope" name="pan" id="pan" class="form-control toUpper" type="text" placeholder="Enter Pan No" maxlength="10">
        </div>
        <div class="col-md-4 mb-2">
            <label for="address_1" class="">Address <span class="text-danger">*</span></label>
            <input autocomplete="nope" name="address_1" id="address_1" class="form-control " type="text" placeholder="Enter Address" required>
        </div>
        <div class="col-md-4 mb-2">
            <label for="address_2" class="">Address Line 2</label>
            <input autocomplete="nope" name="address_2" id="address_2" class="form-control " type="text" placeholder="Enter Address Line 2">
        </div>
        <div class="col-md-4 mb-2">
            <label for="city" class="">City</label>
            <input autocomplete="nope" name="city" id="city" class="form-control textOnly" type="text" placeholder="Enter City" required>
        </div>

        <div class="col-md-4 mb-2">
            <label for="pin" class="">Pin <span class="text-danger">*</span></label>
            <input autocomplete="nope" type="text" name="pin" id="pin" placeholder="Pin Code" onkeypress="return IsNumeric(event);" maxlength="6" minlength="6" class="form-control" required>
        </div>
        <div class="col-md-4 mb-2">
            <label for="state" class="">State <span class="text-danger">*</span></label><br>
            <select name="state" id="state" class="searchselect" style="width:100%" required>
                <option value="">Select State</option>
                <?php $states = $this->db->get('state_list')->result();
                if ($states) {
                    foreach ($states as $row) { ?>
                        <option value="<?= $row->id ?>" data-id="<?= $row->code ?>"> <?= $row->state; ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label for="state_code" class="">State</label><br>
            <input autocomplete="nope" type="text" readonly name="state_code" id="state_code" placeholder="Code" class="form-control" required>
        </div>
        <div class="col-md-4 mb-2">
            <label for="state_code" class="">Credit Limit</label><br>
            <input autocomplete="nope" type="text" name="credit_limit" id="credit_limit" placeholder="Credit Limit" value="<?= set_value('credit_limit') ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
        </div>
        <div class="col-md-4 mb-2">
            <label for="gstTypeParty" class="">gst Type <span class="text-danger">*</span></label><br>
            <select name="gstType" id="gstTypeParty" class="form-control" required>
                <option value="">-Select-</option>
                <option value="Regular">Regular</option>
                <option value="Composite">Composite</option>
                <option value="N/A">N/A</option>
            </select>
        </div>

        <div class="col-md-4 mb-2" id="gstTypeVal">
            <label for="gst" class="">GSTIN No <span class="text-danger">*</span></label>
            <input autocomplete="nope" name="gst" id="gst" class="form-control toUpper" type="text" placeholder="Enter GSTIN No">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="custClose" onclick="closeModel()" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="custSave">Save</button>
    </div>
</form>


<script>
    $(document).ready(function() {
        $('#gstTypeVal').hide()
        $('.searchselect').select2({
            dropdownParent: "#exampleModal"
        });

        $('#state').change(function() {
            var code = $(this).find(':selected').data('id');
            $('#state_code').val(code);
        });
        $('#gstTypeParty').change(function() {
            if ($(this).find(':selected').val() == 'Regular' || $(this).find(':selected').val() == 'Composite') {
                $('#gstTypeVal').show();
                $('#gst').attr('required', true);
            } else {
                $('#gstTypeVal').hide();
                $('#gst').attr('required', false);
            }
        });
    });

    $('#gst').blur(function() {
        $("#custClose").focus()
    });
    $("#custClose").on("keypress", function(e) {
        if (e.keyCode == 13) {
            closeModel();
            $("#exampleModal").modal("hide");
        }
    });
    $("#custSave").on("keypress", function(e) {
        if (e.keyCode == 13) {
            $("#addUserForm").submit();
        }
    });
    $("#custClose").on("focus", function(e) {
        $("#custClose").css({
            "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)"
        });
    });
    $("#custSave").on("focus", function(e) {
        $("#custSave").css({
            "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)"
        });
    });
    $("#custClose").on("blur", function(e) {
        $("#custClose").css({
            "box-shadow": "none"
        });
    });
    $("#custSave").on("blur", function(e) {
        $("#custSave").css({
            "box-shadow": "none"
        });
    });
</script>
