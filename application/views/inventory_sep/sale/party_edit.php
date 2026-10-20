<!-- Card stats -->
<style type="text/css">
    .modal-backdrop.fade {
        opacity: 0;
    }
</style>
<div class="row">
    <div class="" id="ResponseMassage" style="opacity: 500; display: none;"></div>
    <form id="updateParty" action="<?= base_url(); ?>party/update_party" method="POST" enctype="multipart/form-data" onsubmit="updateParty(event);">
        <div class="row">
            <input type="hidden" name="id" value="<?= $party->id ?>">
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="group">Accounting Group <span class="text-danger">*</span></label>
                <select id="group" name="group" class="form-control select2DropdownFilterEdit" tabindex="1" required="">
                    <option value="">-Select-</option>
                    <option value="<?= $party->group_head ?>" <?= set_value('group', $party->group_head) == $party->group_head ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $party->group_head], 'name'); ?></option>
                    <?php
                        if ($role == 1) {
                            $tax = $this->user_model->getResult('accounting_group'); if (isset($tax) && !empty($tax)) {
                                foreach ($tax as $units) {
                    ?>
                    <option value="<?= $units->id ?>" <?= set_value('group', $party->group_head) == $units->id ? 'selected' : ''; ?>><?= $units->name; ?></option>
                    <?php } } } else {
                        if (isset($tax) && !empty($tax)) {
                            foreach ($tax as $units) {
                                $TaxWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'bill_id' => $units->id];
                                if ($this->session->userdata('BranchRole')){
                                    $TaxWhere['CompanyBranchId'] = $CompanyBranchId; 
                                }
                                $resu = $this->user_model->getRow('accountant_status', $TaxWhere);
                                if (isset($resu) && !empty($resu)) {
                                    if ($resu->status == 1) {
                    ?>
                    <option value="<?= $units->id ?>" <?= set_value('group', $units->id) == $units->id ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $units->id], 'name'); ?></option>
                    <?php } } else { ?>
                    <option value="<?= $units->id ?>" <?= set_value('group') == $units->id ? 'selected' : ''; ?>><?= get_title('accounting_group', ['id' => $units->id], 'name'); ?></option>
                    <?php } } } } ?>
                </select>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="<?= set_value('name', $party->name); ?>" id="name_for_edit" class="form-control textOnly" oninput="getPartyNameForEdit(this.value);" tabindex="2" autocomplete="off" placeholder="Name" required="" />
                <div id="party_name_list_for_edit"></div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('name'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="gstType">GST Type</label>
                <select name="gstType" id="gstType" class="form-control" tabindex="3">
                  <option value="Regular" <?= $party->gstType == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                    <option value="Composite" <?= $party->gstType == 'Composite' ? 'selected' : ''; ?>>Composite</option>
                    <option value="Tax Deductor" <?= $party->gstType == 'Tax Deductor' ? 'selected' : ''; ?>>Tax Deductor</option>
                    <option value="N/A" <?= $party->gstType == 'N/A' ? 'selected' : ''; ?>>N/A</option>
                </select>
            </div>
            <div class="form-group col-sm-3 mb-0" id="gstTypeVal">
                <label class="control-label" for="gst">
                    <a href="https://services.gst.gov.in/services/searchtp" target="”_blank”">Check GST No &gt;&gt;</a>
                </label>
                <input autocomplete="off" type="text" name="gst" id="gst" onkeyup="checkStateCode()" placeholder="GST No" value="<?= $party->gst; ?>" tabindex="4" class="form-control" maxlength="15">
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="contact">Contact</label>
                <input autocomplete="off" type="tel" name="contact" id="contact" value="<?= set_value('contact', $party->contact) ?>" tabindex="5" placeholder="Contact" class="form-control" required="" minlength="10" maxlength="10" />
                <span class="col-sm-4 control-label">
                    <?php echo form_error('contact'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="email">Email</label>
                <input autocomplete="off" type="email" name="email" id="email" value="<?= set_value('email', $party->email) ?>" tabindex="6" placeholder="Email" class="form-control">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('email'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="address_1">Address</label>
                <textarea name="address_1" id="address_1" class="form-control form-control-sm" tabindex="7" rows="1" autocomplete="off" placeholder="Address"><?= set_value('address_1', $party->address_1); ?></textarea>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('address_1'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="area">Area</label>
                <textarea name="area" id="area_for_edit" class="form-control form-control-sm" oninput="getPartyAreaForEdit(this.value);" tabindex="8" rows="1" autocomplete="off" placeholder="Area"><?= set_value('area', $party->area); ?></textarea>
                <div id="party_area_list_for_edit"></div>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="state">State <span class="text-danger">*</span></label>
                <select name="state" id="state_id" class="form-control select2DropdownFilterEdit" tabindex="9">
                    <?php
                        $acgroup = $this->user_model->getResult('state_list');
                        if ($acgroup) {
                            foreach ($acgroup as $row) {
                    ?>
                    <option value="<?= $row->id ?>" <?= $party->state == $row->id ? 'selected' : ''; ?>><?= $row->state; ?>(<?= $row->code ?>)</option>
                    <?php } } ?>
                </select>
            </div>
            <div class="form-group col-sm-3 mb-0" hidden>
                <label class="control-label" for="state_code">Code</label>
                <input autocomplete="off" type="text" readonly="" name="state_code" id="state_code" value="<?= set_value('code', $party->state_code) ?>" placeholder="Code" class="form-control" required="">
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="state">City</label>
                <input type="text" name="city" value="<?= get_title('districts', ['id' => $party->city], 'name'); ?>" id="city_for_edit" class="form-control" oninput="getPartyCityForEdit(this.value);" tabindex="10" autocomplete="off" placeholder="City" required="">
                <div id="party_city_list_for_edit"></div>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="pin">Pin Code</label>
                <input autocomplete="off" type="text" name="pin" id="pin" placeholder="Pin Code" value="<?= set_value('pin', $party->pin) ?>" onkeypress="return IsNumeric(event);" tabindex="11" maxlength="6" minlength="6" class="form-control">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('pin'); ?>
                </span>
            </div>            
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="segement">Segment</label>
                <textarea name="segement" id="segment" class="form-control form-control-sm" tabindex="12" rows="1" autocomplete="off" placeholder="Segment"><?= set_value('pin', $party->segement); ?></textarea>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="pan">Pan No</label>
                <input autocomplete="off" type="text" name="pan" id="pan" tabindex="13" placeholder="Pan No" value="<?= set_value('pan', $party->pan) ?>" class="form-control toUpper" maxlength="10">
                <span class="col-sm-4 control-label">
                    <?php echo form_error('pan'); ?>
                </span>
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="credit_limit">Credit Amount Limit</label>
                <input autocomplete="off" type="text" name="credit_limit" id="credit_limit" tabindex="14" placeholder="Credit Amount Limit" value="<?= set_value('credit_limit', $party->credit_limit); ?>" onkeypress="return IsNumeric(event);" class="form-control" maxlength="20">
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label" for="credit_limit_day">Credit Day Limit</label>
                <input type="text" name="credit_limit_day" value="<?= set_value('credit_limit_day', $party->credit_limit_day); ?>" id="credit_limit_day" class="form-control form-control-sm" tabindex="15" autocomplete="off" placeholder="Credit Day Limit" onkeypress="return IsNumeric(event);" />
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="opening_balance">Opening Balance</label>
                <input autocomplete="off" type="text" name="opening_balance" id="opening_balance" tabindex="16" placeholder="Opening Balance" value="<?= $party->opening_balance ?>" class="form-control">
            </div>
            <div class="form-group col-sm-3 mb-0">
                <label class="control-label" for="opBalnceType">Type</label>
                <select name="opBalnceType" id="opBalnceType" class="form-control" tabindex="17">
                    <option value="CR" <?= $party->opBalnceType == 'CR' ? 'selected' : ''; ?>>CR</option>
                    <option value="DR" <?= $party->opBalnceType == 'DR' ? 'selected' : ''; ?>>DR</option>
                </select>
            </div>
            <?php if ($this->session->userdata('salesman_sale') == 'Yes' || $this->session->userdata('salesman_estimate') == 'Yes') { if ($this->session->userdata('RoleType') != "Salesman") { ?>
            <div class="col-sm-3">
                <div class="form-group mb-0">
                    <label for="SalesmanId">Salesman</label>
                    <select name="SalesmanId" id="SalesmanId" class="form-control select2DropdownFilterEdit" title="Salesman" tabindex="18" required="">
                        <option <?= $party->SalesmanId == "" ? "selected" : ""; ?> value="" disabled="">Select Salesman</option>
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
                        <option <?= $party->SalesmanId == $Salesman->Id ? "selected" : ""; ?> value="<?= $Salesman->Id; ?>" <?= set_select('SalesmanId', $Salesman->Id); ?>><?= $Salesman->Name; ?></option>
                        <?php } } ?>
                    </select>
                </div>
            </div>
            <?php } else { ?>
            <input type="hidden" name="SalesmanId" value="<?= set_value('SalesmanId', $party->SalesmanId); ?>" id="SalesmanId" />
            <?php } } ?>
            <div class="form-group col-sm-3">
                <label class="control-label" for="partyCategoryId">Party Category</label>
                <select name="partyCategoryId" id="partyCategoryId" class="form-control select2DropdownFilterEdit" title="Party Category" tabindex="18">
                    <option value="" disabled="" <?= $party->categoryId == "" ? "selected" : ""; ?>>Select Party Category</option>
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
                        if (!empty($partyCategories)) {
                            foreach($partyCategories as $key => $categoryData) {
                    ?>
                    <option value="<?= $categoryData->id; ?>" <?= $party->categoryId == $categoryData->id ? "selected" : ""; ?> <?= set_select('partyCategoryId', $categoryData->id); ?>><?= $categoryData->name; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label" for="LocationQR">Location QR</label>
                <input type="file" name="LocationQR" id="LocationQR" class="form-control" tabindex="19" title="Location QR" accept="image/*" style="line-height: 1.2;" />
                <input type="hidden" name="OldLocationQR" value="<?= $party->LocationQR; ?>" id="OldLocationQR" />
            </div>
            <div class="form-group col-sm-3 col-md-3 mb-0">
                <label class="control-label" for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control" tabindex="20" rows="1" autocomplete="off" placeholder="Remark"><?= set_value('remark', $party->remark); ?></textarea>
            </div>
        </div>
        <hr />
        <h6>Shipping Info</h6>
        <div class="row">
            <div class="form-group col-sm-3 col-md-3">
                <label class="control-label" for="shipName">Name</label>
                <input type="text" name="shipName" value="<?= set_value('shipName', $party->shipName); ?>" id="shipName" class="form-control form-control-sm" autocomplete="off" placeholder="Name" />
            </div>
            <div class="form-group col-sm-3 col-md-3">
                <label class="control-label" for="shipGstNo">GST Number</label>
                <input type="text" name="shipGstNo" value="<?= set_value('shipGstNo', $party->shipGstNo); ?>" id="shipGstNo" class="form-control form-control-sm" autocomplete="off" placeholder="GST Number" />
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label class="control-label" for="shipAddress">Address</label>
                <textarea name="shipAddress" id="shipAddress" class="form-control form-control-sm" rows="2" autocomplete="off" placeholder="Shipping Address"><?= set_value('shipAddress', $party->shipAddress); ?></textarea>
            </div>
            <div class="form-group col-sm-3 col-md-3">
                <label class="control-label" for="shipStateId">State</label>
                <select name="shipStateId" id="shipStateId" class="form-control form-control-sm select2DropdownFilterEdit">
                    <option value="" disabled="" selected="">Select State</option>
                    <?php
                        $stateList = $this->db->get('state_list')->result();
                        if (isset($stateList) && !empty($stateList)) {
                            foreach ($stateList as $key => $stateInfo) {
                    ?>
                    <option value="<?= $stateInfo->id; ?>" <?= $stateInfo->id == $party->shipStateId ? 'selected' : ''; ?>><?= $stateInfo->state; ?> (<?= $stateInfo->code; ?>)</option>
                    <?php } } ?>
                </select>
            </div>
            <div class="form-group col-sm-3 col-md-3">
                <label class="control-label" for="shipCity">City</label>
                <input type="text" name="shipCity" value="<?= set_value('shipCity', get_title('districts', ['id' => $party->shipCityId], 'name')); ?>" id="shipCity" class="form-control form-control-sm" autocomplete="off" placeholder="City" />
            </div>
            <div class="form-group col-sm-3 col-md-3">
                <label class="control-label" for="shipPincode">Pin Code</label>
                <input type="text" name="shipPincode" value="<?= set_value('shipPincode', $party->shipPincode); ?>" id="shipPincode" class="form-control form-control-sm" autocomplete="off" placeholder="Pin Code" />
            </div>
        </div>
        <div class="row">
            <div class="form-group ml-auto pr-5 pt-2">
                <input autocomplete="off" type="submit" class="btn btn-primary" value="Update" />
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

    function updateParty(e) {
        e.preventDefault();
        var url = $("#updateParty").attr("action");
        var form = $("#updateParty")[0];
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
                                    getCustomerDetail(Response.id);
                                } else {
                                    $("#ResponseMassage").show();
                                    $("#ResponseMassage").addClass("text");
                                    $("#ResponseMassage").addClass("text-danger");
                                    $("#ResponseMassage").html(Response.msg);
                                }
                            },
        });
    }

    $(document).ready(function() {
        $('.select2DropdownFilterEdit').each(function() { 
            $(this).select2({dropdownParent: $(this).parent()});
        });

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
    
    function checkStateCode() {
        let chotiBachchiHoKya = $("#gst").val();
        if (chotiBachchiHoKya.length == 2) {
            $.ajax({
                url: '<?= base_url('state/getStateByGst/'); ?>' + chotiBachchiHoKya,
                success: function(data) {
                    data1 = JSON.parse(data);
                    $("#state_id").val(data1.data.id);
                    $("#state_code").val(data1.data.code);

                }
            });

        }
    }
  
    const getPartyNameForEdit = (PartyName) => {
        if (PartyName == "" || PartyName == null) {
            $('#party_name_list_for_edit').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyName",
            data    :   {PartyName: PartyName},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list-party-name" onclick="onSelectgetPartyNameForEdit('${element.name}');">${element.name}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_name_list_for_edit').html(partyopt);
                                $('#name_for_edit').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_name_list_for_edit').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_name_list_for_edit').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyNameForEdit = (PartyName) => {
        $('#name_for_edit').val(PartyName);
        window.setTimeout(function() {
            $('#party_name_list_for_edit').html('');
        });
    };
        
    const getPartyAreaForEdit = (PartyArea) => {
        if (PartyArea == "" || PartyArea == null) {
            $('#party_area_list_for_edit').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyArea",
            data    :   {PartyArea: PartyArea},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list" onclick="onSelectgetPartyAreaForEdit('${element.area}');">${element.area}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_area_list_for_edit').html(partyopt);
                                $('#area_for_edit').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_area_list_for_edit').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_area_list_for_edit').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyAreaForEdit = (PartyArea) => {
        $('#area_for_edit').val(PartyArea);
        window.setTimeout(function() {
            $('#party_area_list_for_edit').html('');
        }, 1000);
    };
        
    const getPartyCityForEdit = (PartyCity) => {
        if (PartyCity == "" || PartyCity == null) {
            $('#party_city_list_for_edit').html('');
            return;
        }
        
        $.ajax({
            method  :   "POST",
            url     :   "<?= base_url(); ?>party/getPartyCity",
            data    :   {PartyCity: PartyCity},
            success :   function (Response) {                                                      
                            if (Response) {
                                var PartyData = JSON.parse(Response);  
                                var partyopt = `<ul class="search-ul">`;
                                PartyData.forEach((element) => {
                                    partyopt += `<li class="search-list" onclick="onSelectgetPartyCityForEdit('${element.cname}');">${element.cname}</li>`;
                                });
                                partyopt += `</ul>`;
                                $('#party_city_list_for_edit').html(partyopt);
                                $('#city_for_edit').focusout(function(){
                                    window.setTimeout(function() {
                                        $('#party_city_list_for_edit').html('');
                                    }, 1000);
                                });
                            } else {
                                $('#party_city_list_for_edit').html('');
                            }
                        }
        });
    };
    
    const onSelectgetPartyCityForEdit = (PartyCity) => {
        $('#city_for_edit').val(PartyCity);
        window.setTimeout(function() {
            $('#party_city_list_for_edit').html('');
        }, 1000);
    };
</script>