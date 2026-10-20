<!-- Card stats -->
<style>
    .modal-backdrop.fade {
        opacity: 0;
    }
</style>
<div class="row">
    <div class="" id="respnce_msg_add" style="opacity: 500; display: none;"></div>
    <form id="add_Wali_parti" action="<?= base_url(); ?>party/save_party" method="POST" enctype="multipart/form-data" onsubmit="saveParty(event);">
        <div class="row">
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="group">Accounting Group <span class="text-danger">*</span></label>
                <select id="group" name="group" class="form-control select2DropdownFilter" tabindex="1" required="">
                    <option value="">-Select-</option>
                    <?php $accounting_group = $this->user_model->getResult('accounting_group'); if (isset($accounting_group) && !empty($accounting_group)) { foreach ($accounting_group as $units) { ?>
                    <option value="<?= $units->id ?>" <?= 5 == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control textOnly" tabindex="2" oninput="suggestPartyName(this.value);" autocomplete="off" placeholder="Name" required="" />
                <div id="party_name_list"></div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('name'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="gstType">GST Type</label>
                <select name="gstType" id="gstType" class="form-control gstType" tabindex="3">
                    <option value="">GST Type</option>
                    <option value="Regular" <?= set_value('gstType') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                    <option value="Composite" <?= set_value('gstType') == 'Composite' ? 'selected' : ''; ?>>Composite</option>
                    <option value="Tax Deductor" <?= set_value('gstType') == 'Tax Deductor' ? 'selected' : ''; ?>>Tax Deductor</option>
                    <option value="N/A" <?= set_value('gstType') == 'N/A' ? 'selected' : ''; ?>>N/A</option>
                </select>
            </div>
            <div class="form-group col-sm-3 mb-0" id="gstTypeVal" class="gstTypeVal">
                <label class="control-label" for="gst">
                    <a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No &gt;&gt;</a>
                </label>
                <input autocomplete="off" type="text" onkeyup="checkStateCode()" tabindex="4" name="gst" id="gst" placeholder="GST No" value="" class="form-control" maxlength="15">
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="contact">Contact</label>
                <input autocomplete="off" type="tel" name="contact" id="contact" tabindex="5" value="<?= set_value('contact') ?>" placeholder="Contact" class="form-control" required="" minlength="10" maxlength="10" />
                <span class="col-sm-4 control-label">
                    <?php echo form_error('contact'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="email">Email</label>
                <input autocomplete="off" type="email" name="email" id="email" tabindex="6" value="<?= set_value('email') ?>" placeholder="Email" class="form-control">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('email'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="address_1">Address</label>
                <textarea name="address_1" id="address_1" class="form-control form-control-sm" tabindex="7" rows="1" autocomplete="off" placeholder="Address"><?= set_value('address_1'); ?></textarea>
                <span class="col-sm-4 control-label">
                    <?= form_error('address_1'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="area">Area</label>
                <textarea name="area" id="area" class="form-control form-control-sm" tabindex="8" rows="1" oninput="getPartyArea(this.value);" autocomplete="off" placeholder="Area"><?= set_value('area'); ?></textarea>
                <div id="party_area_list"></div>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="state">State <span class="text-danger">*</span></label>
                <select name="state" id="state_id" class="form-control select2DropdownFilter" tabindex="9" required="">
                    <option value="0">Select State</option>
                    <?php $acgroup = $this->user_model->getResult('state_list'); if ($acgroup) { foreach ($acgroup as $row) { ?>
                    <option value="<?= $row->id ?>" <?= $this->session->userdata('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?>(<?= $row->code ?>)</option>
                    <?php } } ?>
                </select>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="city">City <span class="text-danger"></span></label>
                <input type="text" name="city" value="<?= set_value('city'); ?>" id="city" class="form-control" oninput="getPartyCity(this.value);" tabindex="10" autocomplete="off" placeholder="City" />
                <div id="party_city_list"></div>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="pin">Pin Code</label>
                <input autocomplete="off" type="text" name="pin" id="pin" placeholder="Pin Code" value="<?= set_value('pin') ?>" onkeypress="return IsNumeric(event);" tabindex="11" maxlength="6" minlength="6" class="form-control">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('pin'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="segment">Segment</label>
                <textarea name="segement" id="segment" class="form-control form-control-sm" rows="1" autocomplete="off" placeholder="Segment" tabindex="12"><?= set_value('segement'); ?></textarea>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="pan">Pan No</label>
                <input autocomplete="off" type="text" name="pan" id="pan" placeholder="Pan No" value="<?= set_value('pan') ?>" tabindex="13" class="form-control toUpper" maxlength="10">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('pan'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label" for="credit_limit">Credit Amount Limit</label>
                <input type="text" name="credit_limit" value="<?= set_value('credit_limit'); ?>" id="credit_limit" class="form-control form-control-sm" tabindex="14" autocomplete="off" placeholder="Credit Amount Limit" onkeypress="return IsNumeric(event);" maxlength="20" />
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label" for="credit_limit_day">Credit Day Limit</label>
                <input type="text" name="credit_limit_day" value="<?= set_value('credit_limit_day'); ?>" id="credit_limit_day" class="form-control form-control-sm" tabindex="15" autocomplete="off" placeholder="Credit Day Limit" onkeypress="return IsNumeric(event);" />
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="opening_balance">Opening Balance</label>
                <input autocomplete="off" type="text" name="opening_balance" id="opening_balance" placeholder="Opening Balance" value="<?= set_value('opening_balance'); ?>" tabindex="16" class="form-control">
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="opBalnceType">Type</label>
                <select name="opBalnceType" id="opBalnceType" class="form-control" tabindex="17">
                    <!-- <option value="">-Select-</option> -->
                    <option value="DR" <?= set_value('opBalnceType') == 'DR' ? 'selected' : ''; ?>>DR</option>
                    <option value="CR" <?= set_value('opBalnceType') == 'CR' ? 'selected' : ''; ?>>CR</option>
                </select>
            </div>
            <?php if ($this->session->userdata('salesman_sale') == 'Yes' || $this->session->userdata('salesman_estimate') == 'Yes') { if ($this->session->userdata('RoleType') != "Salesman") { ?>
            <div class="col-sm-3">
                <div class="form-group mb-0">
                    <label for="SalesmanId">Salesman</label>
                    <select name="SalesmanId" id="SalesmanId" class="form-control select2DropdownFilter" title="Salesman" tabindex="18" required="">
                        <option selected="" value="" disabled="">Select Salesman</option>
                        <?php
                            if ($this->session->userdata('RoleType')) {
                                $UserId = $this->session->userdata('UserId');
                                $CompId = $this->session->userdata('CompId');
                            } else {
                                $UserId = $this->session->userdata('user_id');
                                $CompId = $this->session->userdata('comp_id');
                            }
                            $SalesmanListWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'Status' => 1];
                            $RoleId = get_title('position', ['Status' => 1, 'IsDeleted' => 1, 'PostName' => 'Salesman'], 'Id');
                            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") { 
                                $SalesmanWhere = ['Id' => $this->session->userdata('EmployeeId'), 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'Status' => 1];
                                if ($this->session->userdata('BranchRole')) {
                                    $SalesmanWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                    $SalesmanListWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $EmployeeId = json_decode(get_title('employees', $SalesmanWhere, 'WarehouseSalesman'));
                                $RoleId = get_title('position', ['Status' => 1, 'IsDeleted' => 1, 'PostName' => 'Salesman'], 'Id');
                                $Salesmans = $this->db->select('Id, Name')->where_in('Id', $EmployeeId)->where('RoleId', $RoleId)->where($SalesmanListWhere)->get('employees')->result();
                            } else {
                                if ($this->session->userdata('BranchRole')) {
                                    $SalesmanListWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $Salesmans = $this->db->select('Id, Name')->where($SalesmanListWhere)->where('RoleId', $RoleId)->get('employees')->result();
                            }
                            if (!empty($Salesmans)) {
                                foreach($Salesmans as $key => $Salesman) {
                        ?>
                        <option value="<?= $Salesman->Id; ?>" <?= set_select('SalesmanId', $Salesman->Id); ?>><?= $Salesman->Name; ?></option>
                        <?php } } ?>
                    </select>
                </div>
            </div>
            <?php } else { ?>
            <input type="hidden" name="SalesmanId" value="<?= set_value('SalesmanId', $this->session->userdata('EmployeeId')); ?>" id="SalesmanId" />
            <?php } } ?>
            <div class="form-group col-sm-3">
                <label class="control-label" for="partyCategoryId">Party Category</label>
                <select name="partyCategoryId" id="partyCategoryId" class="form-control select2DropdownFilter" title="Party Category" tabindex="18">
                    <option value="" disabled="" selected="">Select Party Category</option>
                    <?php
                        if ($this->session->userdata('RoleType')) {
                            $UserId = $this->session->userdata('UserId');
                            $CompId = $this->session->userdata('CompId');
                        } else {
                            $UserId = $this->session->userdata('user_id');
                            $CompId = $this->session->userdata('comp_id');
                        }                    
                        $partyCategoryWhere = ['isDeleted' => 1, 'userId' => $UserId, 'compId' => $CompId];
                        if ($this->session->userdata('BranchRole')) {
                            $partyCategoryWhere['compBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        $partyCategories = $this->db->select('id, name')->where($partyCategoryWhere)->get('party_category')->result();
                        echo $this->db->last_query();
                        if (!empty($partyCategories)) {
                            foreach($partyCategories as $key => $categoryData) {
                    ?>
                    <option value="<?= $categoryData->id; ?>" <?= set_select('partyCategoryId', $categoryData->id); ?>><?= $categoryData->name; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label" for="LocationQR">Location QR</label>
                <input type="file" name="LocationQR" id="LocationQR" class="form-control" tabindex="19" title="Location QR" accept="image/*" style="line-height: 1.2;" />
            </div>
            <div class="form-group col-sm-3 col-md-3 mb-0">
                <label class="control-label" for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control" tabindex="20" rows="1" autocomplete="off" placeholder="Remark"><?= set_value('remark'); ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group ml-auto pr-5 pt-2">
                <?php if ($this->session->userdata('roles') == 3) { if (IsLicensed()) { ?>
                <input autocomplete="off" type="submit" class="btn btn-primary" value="Add Party" />
                <?php } else { ?>
                <span class="text-danger">Your license has been expired, Please contact your administrator.</span>
                <?php } } else { ?>
                <input autocomplete="off" type="submit" class="btn btn-primary" value="Add Party" />
                <?php } ?>
                <button type="button" class="btn btn-danger" id="close_modal" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    var inputs = document.querySelectorAll("input,select");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener("keypress", function(e) {
            if (e.which == 13) {
                e.preventDefault();
                var nextInput = document.querySelectorAll('[tabIndex="' + (this.tabIndex + 1) + '"]');
                if (nextInput.length === 0) {
                    nextInput = document.querySelectorAll('[tabIndex="1"]');
                }
                nextInput[0].focus();
            }
        });
    }
    
    jQuery.noConflict();

    function saveParty(e) {
        e.preventDefault();
        var url = $("#add_Wali_parti").attr("action");
        var form = $("#add_Wali_parti")[0];
        var data = new FormData(form);
        $.ajax({
            type        :   "POST",
            enctype     :   "multipart/form-data",
            url         :   url,
            data        :   data,
            processData :   false,
            contentType :   false,
            cache       :   false,
            dataType    :   "JSON",
            success     :   function(Response) {
                                if (Response.status == true) {
                                    toastr.success(Response.msg);
                                    $("#close_modal").click();
                                    <?php if (end(explode("/", current_url())) == 'CreateParty') { ?>
                                    window.location.reload();    
                                    <?php } else { ?>
                                    setTimeout(function() {
                                        getCustomerDetail(Response.id);
                                    }, 100);
                                    <?php } ?>
                                } else {
                                    $("#respnce_msg_add").show();
                                    $("#respnce_msg_add").addClass("text");
                                    $("#respnce_msg_add").addClass("text-danger");
                                    $("#respnce_msg_add").html(Response.msg);
                                }
                            }
        });
    }

    $(document).ready(function() {
        $('.select2DropdownFilter').each(function() { 
            $(this).select2({dropdownParent: $(this).parent()});
        });
    
        $('.gstTypeVal').hide();

        $('.gstType').change(function() {
            if ($(this).find(':selected').val() == 'Regular' || $(this).find(':selected').val() == 'Composite') {
                $('.gstTypeVal').show();
                $('#gst').attr('required', true);
            } else {
                $('.gstTypeVal').hide();
                $('#gst').attr('required', false);
            }
        });
       
    });
    
     function checkStateCode() {
        let chotiBachchiHoKya = $("#gst").val();
        if (chotiBachchiHoKya.length == 2) {
            $.ajax({
                url     :   "<?= base_url(); ?>state/getStateByGst/" + chotiBachchiHoKya,
                success :   function(Response) {
                                var JSONResponse = JSON.parse(Response);
                                $("#state_id").val(JSONResponse.data.id);
                            }
            });
        }
    }
</script>