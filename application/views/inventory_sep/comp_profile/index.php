<style type="text/css">
        input[type=radio] {
                width: 20px;
                height: 20px;
        }

        .btn {
                margin-bottom: 20px;
        }
</style>
<?php
if ($this->session->userdata('RoleType')) {
        $EmployeeId = $this->session->userdata('EmployeeId');
        $UserId = $this->session->userdata('UserId');
        $CompId = $this->session->userdata('CompId');
} else {
        $UserId = $this->session->userdata('user_id');
        $CompId = $this->session->userdata('comp_id');
}
$CompanyBranchId = $this->session->userdata('CompBranchId');

$Where = ['comp_id' => $CompId, 'user_id' => $UserId];
if ($this->session->userdata('BranchRole')) {
        $Where['CompanyBranchId'] = $CompanyBranchId;
}
$CompProfile = $this->user_model->getRow('comp_profile', $Where);
$Type = $this->session->userdata('BranchRole') ? "Branch" : "Company";
?>
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
                <div class="header">
                        <div class="header-body">
                                <div class="container-fluid">
                                        <h2 style="color: #ffffff;" class="text-center pb-5"><?= $Type; ?> Profile</h2>
                                        <?php if ($this->session->flashdata('msg')) { ?>
                                                <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                                                        <?= $this->session->flashdata('msg'); ?>
                                                </div>
                                        <?php } ?>
                                        <div class="row">
                                                <!-- COMPNAY DETAILS START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <button type="button" class="btn btn-dark btn-block" data-toggle="modal" data-target="#myModal">
                                                                Update <?= $Type; ?>
                                                        </button>
                                                        <!-- The Modal -->
                                                        <div class="modal fade" id="myModal">
                                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                                <!-- Modal Header -->
                                                                                <div class="modal-header">
                                                                                        <h4 class="text-center">Update <?= $Type; ?></h4>
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                </div>
                                                                                <!-- Modal body -->
                                                                                <div class="modal-body">
                                                                                        <form id="signUpForm" action="<?= base_url(); ?>comp_profile/update_comp_info" method="POST" enctype="multipart/form-data">
                                                                                                <?php
                                                                                                if ($this->session->userdata('BranchRole')) {
                                                                                                        $CompanyBranch = $this->user_model->getRow('companybranch', ['Id' => $this->session->userdata('CompBranchId'), 'BranchUserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')]);
                                                                                                ?>
                                                                                                        <input type="hidden" name="id" value="<?= $CompanyBranch->Id; ?>" />
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="name" class="text-dark">Branch Name: <span class="text-danger">*</span></label>
                                                                                                                                <input id="name" name="name" placeholder="Company Name" type="text" class="form-control" required="true" value="<?= $CompanyBranch->BranchName; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="name" class="text-dark">Mobile Number:</label>
                                                                                                                                <input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= $CompanyBranch->Mobile; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="email" class="text-dark">Email:</label>
                                                                                                                                <input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= $CompanyBranch->Email; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="gst" class="text-dark">GST No:</label>
                                                                                                                                <input autocomplete="off" type="hidden" name="ledger_gst_details" id="ledger_gst_details_branch" value="<?= $CompanyBranch->gst_details; ?>" placeholder="GST No">
                                                                                                                                <input name="gst" id="gst" placeholder="GST Number" type="text" class="form-control" value="<?= $CompanyBranch->GST; ?>" onblur="checkGstDetails()">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="panNumber" class="text-dark">PAN No:</label>
                                                                                                                                <input name="panNumber" placeholder="PAN Number" type="text" class="form-control" value="<?= $comp_profile->panNumber; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="phoneNumber" class="text-dark">Phone No:</label>
                                                                                                                                <input name="phoneNumber" placeholder="Phone Number" type="text" class="form-control" value="<?= $comp_profile->phoneNumber; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="contact" class="text-dark">Contact Person:</label>
                                                                                                                                <input name="contact" placeholder="Contact person" type="text" class="form-control" value="<?= $CompanyBranch->ContactPerson; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="year" class="text-dark">Financial Year: <span class="text-danger">*</span></label>
                                                                                                                                <select class="form-control" required name="year" id="year">
                                                                                                                                        <option value="">Select Financial Year</option>
                                                                                                                                        <?php
                                                                                                                                        $financial_year = $this->user_model->getResult('year');
                                                                                                                                        if ($financial_year) {
                                                                                                                                                foreach ($financial_year as $years) {
                                                                                                                                        ?>
                                                                                                                                                        <option value="<?= $years->id; ?>" <?= $comp_profile->financial_year == $years->id ? "selected" : ""; ?> <?= set_select('year', $years->id); ?>><?= $years->year; ?></option>
                                                                                                                                        <?php }
                                                                                                                                        } ?>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="state" class="text-dark">State:<span class="text-danger">*</span></label>
                                                                                                                                <select name="state" id="state" class="form-control">
                                                                                                                                        <?php
                                                                                                                                        $where = ['id' => $CompanyBranch->State];
                                                                                                                                        $acgroup = $this->user_model->getRow('state_list', $where);
                                                                                                                                        if ($acgroup) {
                                                                                                                                                $name = $acgroup->state;
                                                                                                                                        } else {
                                                                                                                                                $name = "<span class='text-danger'>Deleted</span>";
                                                                                                                                        }
                                                                                                                                        ?>
                                                                                                                                        <option value="<?= $CompanyBranch->State; ?>" <?= $CompanyBranch->State == $acgroup->id ? "selected" : ""; ?> <?= set_select('state', $CompanyBranch->State); ?>><?= $name; ?></option>
                                                                                                                                        <?php
                                                                                                                                        $acgroup = $this->user_model->getResult('state_list');
                                                                                                                                        if ($acgroup) {
                                                                                                                                                foreach ($acgroup as $row) {
                                                                                                                                        ?>
                                                                                                                                                        <option value="<?= $row->id; ?>" <?= set_select('state', $row->id); ?>><?= $row->state; ?></option>
                                                                                                                                        <?php }
                                                                                                                                        } ?>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <?php
                                                                                                                        $where = ['id' => $CompanyBranch->city];
                                                                                                                        $districts = $this->user_model->getRow('districts', $where);
                                                                                                                        $city_name = "";
                                                                                                                        if (!empty($districts)) {
                                                                                                                                $city_name = $districts->name;
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="City" class="text-dark">City:<span class="text-danger">*</span></label>
                                                                                                                                <input type="text" name="city" value="<?= set_value('city') ? set_value('city') : $city_name; ?>" id="city" class="form-control" oninput="getPartyCity(this.value);" tabindex="10" autocomplete="off" placeholder="City" />
                                                                                                                                <div id="party_city_list"></div>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="pincode" class="text-dark">Pincode:<span class="text-danger"></span></label>
                                                                                                                                <input name="pincode" placeholder="Enter pincode" type="text" class="form-control" value="<?= $CompanyBranch->pincode; ?>">
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="ac" class="text-dark">Accounting Group:<span class="text-danger">*</span></label>
                                                                                                                                <select id="ac" name="ac" class="form-control" required>
                                                                                                                                        <?php if ($CompanyBranch->AccountingType == 1) { ?>
                                                                                                                                                <option selected value="1">Basic</option>
                                                                                                                                        <?php } else { ?>
                                                                                                                                                <option selected value="2">Advanced</option>
                                                                                                                                        <?php } ?>
                                                                                                                                        <option value="1">Basic</option>
                                                                                                                                        <option value="2">Advanced</option>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="category" class="text-dark">Category:<span class="text-danger">*</span></label>
                                                                                                                                <select id="category" name="category" class="form-control" required>
                                                                                                                                        <option value="Normal" <?= set_value('category', $comp_profile->category) == 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                                                                                                                        <option value="Glass" <?= set_value('category', $comp_profile->category) == 'Glass' ? 'selected' : ''; ?>>Glass</option>
                                                                                                                                        <option value="Glass" <?= set_value('category', $comp_profile->category) == 'General Store' ? 'selected' : ''; ?>>General Store</option>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="CashOpeningBalance" class="text-dark">Cash Opening Balance:<span class="text-danger"></span></label>
                                                                                                                                <input name="CashOpeningBalance" placeholder="Cash Opening Balance" type="text" class="form-control" value="<?= $comp_profile->CashOpeningBalance; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="BankOpeningBalance" class="text-dark">Bank Opening Balance:<span class="text-danger"></span></label>
                                                                                                                                <input name="BankOpeningBalance" placeholder="Bank Opening Balance" type="text" class="form-control" value="<?= $comp_profile->BankOpeningBalance; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <?php if ($CompProfile->eway == "yes") { ?>
                                                                                                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <?php $ewayBillApiMode = ['TEST' => 'TEST', 'LIVE' => 'LIVE']; ?>
                                                                                                                                        <label for="ewayBillApiMode" class="text-dark">Eway Bill Api Mode:<span class="text-danger"></span></label>
                                                                                                                                        <select name="ewayBillApiMode" class="form-control">
                                                                                                                                                <?php foreach ($ewayBillApiMode as $ewayValue) { ?>
                                                                                                                                                        <option value="<?= $ewayValue ?>" <?= $CompanyBranch->ewayBillApiMode == $ewayValue ? "selected" : ""; ?>><?= $ewayValue ?></option>
                                                                                                                                                <?php } ?>
                                                                                                                                        </select>
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                <?php } ?>

                                                                                                                <?php if ($CompProfile->eInvoice == "Y") { ?>

                                                                                                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="hidden" name="eInvoiceApiModeAccessTokne" id="eInvoiceApiModeAccessTokne" value="" />
                                                                                                                                        <?php $eInvoiceBillApiMode = ['TEST' => 'TEST', 'LIVE' => 'LIVE']; ?>
                                                                                                                                        <label for="eInvoiceApiMode" class="text-dark">E-Invoice Api Mode:<span class="text-danger"></span></label>
                                                                                                                                        <select name="eInvoiceApiMode" class="form-control" id="eInvoiceApiMode">
                                                                                                                                                <?php foreach ($eInvoiceBillApiMode as $eInvoiceValue) { ?>
                                                                                                                                                        <option value="<?= $eInvoiceValue ?>" <?= $CompanyBranch->eInvoiceApiMode == $eInvoiceValue ? "selected" : ""; ?>><?= $eInvoiceValue ?></option>
                                                                                                                                                <?php } ?>
                                                                                                                                        </select>
                                                                                                                                </div>
                                                                                                                        </div>

                                                                                                                <?php } ?>
                                                                                                                
                                                                                                                <?php if ($CompProfile->gstr1Status == "Y") { ?> 
                                                                                                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                <div class="form-group"> 
                                                                                                                                        <?php $gstr1StatusApiMode = ['TEST' => 'TEST', 'LIVE' => 'LIVE']; ?>
                                                                                                                                        <label for="gstr1Mode" class="text-dark">GSTR1 Api Mode:<span class="text-danger"></span></label>
                                                                                                                                        <select name="gstr1Mode" class="form-control" id="gstr1Mode">
                                                                                                                                                <?php foreach ($gstr1StatusApiMode as $gstr1StatusValue) { ?>
                                                                                                                                                        <option value="<?= $gstr1StatusValue ?>" <?= $CompanyBranch->gstr1Mode == $gstr1StatusValue ? "selected" : ""; ?>><?= $gstr1StatusValue ?></option>
                                                                                                                                                <?php } ?>
                                                                                                                                        </select>
                                                                                                                                </div>
                                                                                                                        </div> 
                                                                                                                <?php } ?>

                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="address" class="text-dark">Address:<span class="text-danger"></span></label>
                                                                                                                                <textarea name="address" placeholder="Address" class="form-control" rows="2"><?= $CompanyBranch->Address; ?></textarea>
                                                                                                                        </div>
                                                                                                                </div>


                                                                                                                <?php if (empty($this->session->userdata('RoleType'))) { ?>

                                                                                                                        <div class="col-md-12 col-sm-12 col-lg-12" id="eway_einvoice_details">
                                                                                                                        </div>

                                                                                                                        <div class="row" id="gst_eway_einvoice_details">
                                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                                        <h5 style="text-align:center;color:red;">EWay Bill Login Details</h5>
                                                                                                                                        <hr>
                                                                                                                                </div>
                                                                                                                                <?php $eWayUserDetails = json_decode($CompanyBranch->eWayUserDetails); ?>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eway_username" class="text-dark">E-way Bill Users:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[eway_username]" placeholder="E-way Users" type="text" class="form-control" value="<?= $eWayUserDetails->eway_username; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eway_password" class="text-dark">E-way Bill Password:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[eway_password]" placeholder="E-way Password" type="text" class="form-control" value="<?= $eWayUserDetails->eway_password; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eway_mobile" class="text-dark">Registered Mobile:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[eway_mobile]" placeholder="Enter Mobile" type="text" class="form-control" value="<?= $eWayUserDetails->eway_mobile!=""?$eWayUserDetails->eway_mobile:$CompanyBranch->Mobile; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>

                                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                                        <hr>
                                                                                                                                        <h5 style="text-align:center;color:red;">GSP Details</h5>
                                                                                                                                        <hr>
                                                                                                                                </div>

                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="gsp_username" class="text-dark">Username:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[gsp_username]" placeholder="GSP Users" type="text" class="form-control" value="<?= $eWayUserDetails->gsp_username; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="gsp_password" class="text-dark">Password:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[gsp_password]" placeholder="GSP Password" type="text" class="form-control" value="<?= $eWayUserDetails->gsp_password; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">

                                                                                                                                </div>

                                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                                        <hr>
                                                                                                                                        <h5 style="text-align:center;color:red;">EInvoice Login Details</h5>
                                                                                                                                        <hr>
                                                                                                                                </div>
                                                                                                                                <?php $eInvoiceUserDetails = json_decode($CompanyBranch->eInvoiceUserDetails);  ?>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eInvoice_username" class="text-dark">UserName:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[eInvoice_username]" placeholder="E-Invoice Users" type="text" class="form-control" value="<?= $eInvoiceUserDetails->eInvoice_username; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eInvoice_password" class="text-dark">Password:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[eInvoice_password]" placeholder="E-Invoice Password" type="text" class="form-control" value="<?= $eInvoiceUserDetails->eInvoice_password; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eInvoice_mobile" class="text-dark">Registered Mobile:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[eInvoice_mobile]" placeholder="Enter Mobile" type="text" class="form-control" value="<?= $eInvoiceUserDetails->eInvoice_mobile!=""?$eInvoiceUserDetails->eInvoice_mobile:$CompanyBranch->Mobile; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>

                                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                                        <hr>
                                                                                                                                        <h5 style="text-align:center;color:red;">EInvoice API Details</h5>
                                                                                                                                        <hr>
                                                                                                                                </div>

                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="api_username" class="text-dark">UserName:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[api_username]" placeholder="Api user" type="text" class="form-control" value="<?= $eInvoiceUserDetails->api_username; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="api_password" class="text-dark">Password:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[api_password]" placeholder="api Password" type="text" class="form-control" value="<?= $eInvoiceUserDetails->api_password; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                </div>
                                                                                                                                <hr>
                                                                                                                        </div>
                                                                                                                <?php } ?>

                                                                                                                <div class="col-md-12">
                                                                                                                        <div class="text-center">
                                                                                                                                <?php if ($CompProfile->eInvoice == "Y") { ?>
                                                                                                                                        <input type="button" id="refresh_gst" onclick="checkGstDetails()" class="btn btn-outline-primary" style="cursor: pointer;" class="btn btn-info" value="Refresh Gst" title="GST synchronize with GST Service Provider" />
                                                                                                                                <?php } ?>
                                                                                                                                <button type="submit" class="btn btn-primary btn-sm">
                                                                                                                                        Update
                                                                                                                                </button>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                <?php
                                                                                                } else {
                                                                                                        $inventory_billing = $this->user_model->getRow('inventory_billing', ['id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')]);
                                                                                                ?>
                                                                                                        <input type="hidden" name="id" value="<?= $inventory_billing->id; ?>">
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="name" class="text-dark">Company Name:<span class="text-danger">*</span></label>
                                                                                                                                <input id="name" name="name" placeholder="Company Name" type="text" class="form-control" required="true" value="<?= $inventory_billing->company_name; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="name" class="text-dark">Mobile Number:<span class="text-danger"></span></label>
                                                                                                                                <input name="number" placeholder="Mobile Number" type="text" class="form-control" value="<?= $inventory_billing->number; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="email" class="text-dark">Email:<span class="text-danger"></span></label>
                                                                                                                                <input id="email" name="email" placeholder=" Email" type="email" class="form-control" value="<?= $inventory_billing->email; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="gst" class="text-dark">GST No:<span class="text-danger"></span></label>
                                                                                                                                <input name="gst" id="gst" placeholder="GST Number" type="text" class="form-control" value="<?= $inventory_billing->gst_no; ?>">
                                                                                                                                <input type="hidden" name="ledger_gst_details" id="ledger_gst_details" placeholder="GST Number" type="text" class="form-control" value="<?= $inventory_billing->gst_no; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="panNumber" class="text-dark">PAN No:</label>
                                                                                                                                <input name="panNumber" placeholder="PAN Number" type="text" class="form-control" value="<?= $comp_profile->panNumber; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="phoneNumber" class="text-dark">Phone No:</label>
                                                                                                                                <input name="phoneNumber" placeholder="Phone Number" type="text" class="form-control" value="<?= $comp_profile->phoneNumber; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="contact" class="text-dark">Contact:<span class="text-danger"></span></label>
                                                                                                                                <input name="contact" placeholder="Contact person" type="text" class="form-control" value="<?= $inventory_billing->contact_person; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="year" class="text-dark">Financial Year:<span class="text-danger">*</span></label>
                                                                                                                                <select class="form-control" required name="year" id="year">
                                                                                                                                        <option value="">Select Financial Year</option>
                                                                                                                                        <?php
                                                                                                                                        $financial_year = $this->user_model->getResult('year');
                                                                                                                                        if ($financial_year) {
                                                                                                                                                foreach ($financial_year as $years) {
                                                                                                                                        ?>
                                                                                                                                                        <option value="<?= $years->id ?>" <?= set_value('year', $comp_profile->financial_year) == $years->id ? 'selected' : ''; ?>><?= $years->year; ?></option>
                                                                                                                                        <?php }
                                                                                                                                        } ?>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="state" class="text-dark">State:<span class="text-danger">*</span></label>
                                                                                                                                <select name="state" id="state" class="form-control">
                                                                                                                                        <?php
                                                                                                                                        $where = ['id' => $inventory_billing->state];
                                                                                                                                        $acgroup = $this->user_model->getRow('state_list', $where);
                                                                                                                                        if ($acgroup) {
                                                                                                                                                $name = $acgroup->state;
                                                                                                                                        } else {
                                                                                                                                                $name = "<span class='text-danger'>Deleted</span>";
                                                                                                                                        }
                                                                                                                                        ?>
                                                                                                                                        <option value="<?= $inventory_billing->state ?>" <?= set_value('state') == $inventory_billing->state ? 'selected' : ''; ?>><?= $name; ?></option>
                                                                                                                                        <?php
                                                                                                                                        $acgroup = $this->user_model->getResult('state_list');
                                                                                                                                        if ($acgroup) {
                                                                                                                                                foreach ($acgroup as $row) {
                                                                                                                                        ?>
                                                                                                                                                        <option value="<?= $row->id ?>" <?= set_value('state') == $row->id ? 'selected' : ''; ?>><?= $row->state; ?></option>
                                                                                                                                        <?php }
                                                                                                                                        } ?>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>


                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <?php
                                                                                                                        $where = ['id' => $inventory_billing->city];
                                                                                                                        $districts = $this->user_model->getRow('districts', $where);
                                                                                                                        $city_name = "";
                                                                                                                        if (!empty($districts)) {
                                                                                                                                $city_name = $districts->name;
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="City" class="text-dark">City:<span class="text-danger">*</span></label>
                                                                                                                                <input type="text" name="city" value="<?= set_value('city') ? set_value('city') : $city_name; ?>" id="city" class="form-control" oninput="getPartyCity(this.value);" tabindex="10" autocomplete="off" placeholder="City" />
                                                                                                                                <div id="party_city_list"></div>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="pincode" class="text-dark">Pincode:<span class="text-danger"></span></label>
                                                                                                                                <input name="pincode" placeholder="Enter pincode" type="text" class="form-control" value="<?= $inventory_billing->pincode; ?>">
                                                                                                                        </div>
                                                                                                                </div>



                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="ac" class="text-dark">Accounting Group:<span class="text-danger">*</span></label>
                                                                                                                                <select id="ac" name="ac" class="form-control" required>
                                                                                                                                        <?php if ($inventory_billing->accounting == 1) { ?>
                                                                                                                                                <option selected value="1">Basic</option>
                                                                                                                                        <?php } else { ?>
                                                                                                                                                <option selected value="2">Advanced</option>
                                                                                                                                        <?php } ?>
                                                                                                                                        <option value="1">Basic</option>
                                                                                                                                        <option value="2">Advanced</option>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="category" class="text-dark">Category:<span class="text-danger">*</span></label>
                                                                                                                                <select id="category" name="category" class="form-control" required>
                                                                                                                                        <option value="Normal" <?= set_value('category', $comp_profile->category) == 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                                                                                                                        <option value="Glass" <?= set_value('category', $comp_profile->category) == 'Glass' ? 'selected' : ''; ?>>Glass</option>
                                                                                                                                        <option value="Glass" <?= set_value('category', $comp_profile->category) == 'General Store' ? 'selected' : ''; ?>>General Store</option>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="CashOpeningBalance" class="text-dark">Cash Opening Balance:<span class="text-danger"></span></label>
                                                                                                                                <input name="CashOpeningBalance" placeholder="Cash Opening Balance" type="text" class="form-control" value="<?= $comp_profile->CashOpeningBalance; ?>">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="BankOpeningBalance" class="text-dark">Bank Opening Balance:<span class="text-danger"></span></label>
                                                                                                                                <input name="BankOpeningBalance" placeholder="Bank Opening Balance" type="text" class="form-control" value="<?= $comp_profile->BankOpeningBalance; ?>">
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <?php if ($CompProfile->eway == "yes") { ?>
                                                                                                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <?php $ewayBillApiMode = ['TEST' => 'TEST', 'LIVE' => 'LIVE']; ?>
                                                                                                                                        <label for="ewayBillApiMode" class="text-dark">Eway Bill Api Mode:<span class="text-danger"></span></label>
                                                                                                                                        <select name="ewayBillApiMode" class="form-control">
                                                                                                                                                <?php foreach ($ewayBillApiMode as $ewayValue) { ?>
                                                                                                                                                        <option value="<?= $ewayValue ?>" <?= $inventory_billing->ewayBillApiMode == $ewayValue ? "selected" : ""; ?>><?= $ewayValue ?></option>
                                                                                                                                                <?php } ?>
                                                                                                                                        </select>
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                <?php } ?>
                                                                                                                <?php if ($CompProfile->eInvoice == "Y") { ?>
                                                                                                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="hidden" name="eInvoiceApiModeAccessTokne" id="eInvoiceApiModeAccessTokne" value="" />
                                                                                                                                        <?php $eInvoiceBillApiMode = ['TEST' => 'TEST', 'LIVE' => 'LIVE']; ?>
                                                                                                                                        <label for="eInvoiceApiMode" class="text-dark">E-Invoice Api Mode:<span class="text-danger"></span></label>
                                                                                                                                        <select name="eInvoiceApiMode" class="form-control" id="eInvoiceApiMode">
                                                                                                                                                <?php foreach ($eInvoiceBillApiMode as $eInvoiceValue) { ?>
                                                                                                                                                        <option value="<?= $eInvoiceValue ?>" <?= $inventory_billing->eInvoiceApiMode == $eInvoiceValue ? "selected" : ""; ?>><?= $eInvoiceValue ?></option>
                                                                                                                                                <?php } ?>
                                                                                                                                        </select>
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                <?php } ?>
                                                                                                                
                                                                                                                <?php if ($CompProfile->gstr1Status == "Y") { ?> 
                                                                                                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                <div class="form-group"> 
                                                                                                                                        <?php $gstr1StatusApiMode = ['TEST' => 'TEST', 'LIVE' => 'LIVE']; ?>
                                                                                                                                        <label for="gstr1Mode" class="text-dark">GSTR1 Api Mode:<span class="text-danger"></span></label>
                                                                                                                                        <select name="gstr1Mode" class="form-control" id="gstr1Mode">
                                                                                                                                                <?php foreach ($gstr1StatusApiMode as $gstr1StatusValue) { ?>
                                                                                                                                                        <option value="<?= $gstr1StatusValue ?>" <?= $inventory_billing->gstr1Mode == $gstr1StatusValue ? "selected" : ""; ?>><?= $gstr1StatusValue ?></option>
                                                                                                                                                <?php } ?>
                                                                                                                                        </select>
                                                                                                                                </div>
                                                                                                                        </div> 
                                                                                                                <?php } ?>
                                                                                                                
                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="address" class="text-dark">Address:<span class="text-danger"></span></label>
                                                                                                                                <textarea name="address" placeholder="Address" class="form-control" rows="2"><?= $inventory_billing->address; ?></textarea>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <?php if (empty($this->session->userdata('RoleType'))) { ?>

                                                                                                                        <div class="col-md-12 col-sm-12 col-lg-12" id="eway_einvoice_details">
                                                                                                                        </div>

                                                                                                                        <div class="row" id="gst_eway_einvoice_details">
                                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                                        <h5 style="text-align:center;color:red;">EWay Bill Login Details</h5>
                                                                                                                                        <hr>
                                                                                                                                </div>
                                                                                                                                <?php $eWayUserDetails = json_decode($inventory_billing->eWayUserDetails); ?>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eway_username" class="text-dark">E-way Bill Users:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[eway_username]" placeholder="E-way Users" type="text" class="form-control" value="<?= $eWayUserDetails->eway_username; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eway_password" class="text-dark">E-way Bill Password:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[eway_password]" placeholder="E-way Password" type="text" class="form-control" value="<?= $eWayUserDetails->eway_password; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eway_mobile" class="text-dark">Registered Mobile:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[eway_mobile]" placeholder="Enter Mobile" type="text" class="form-control" value="<?= $eWayUserDetails->eway_mobile!=""?$eWayUserDetails->eway_mobile:$inventory_billing->number; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>

                                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                                        <hr>
                                                                                                                                        <h5 style="text-align:center;color:red;">GSP Details</h5>
                                                                                                                                        <hr>
                                                                                                                                </div>

                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="gsp_username" class="text-dark">Username:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[gsp_username]" placeholder="GSP Users" type="text" class="form-control" value="<?= $eWayUserDetails->gsp_username; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="gsp_password" class="text-dark">Password:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eWayUserDetails[gsp_password]" placeholder="GSP Password" type="text" class="form-control" value="<?= $eWayUserDetails->gsp_password; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">

                                                                                                                                </div>

                                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                                        <hr>
                                                                                                                                        <h5 style="text-align:center;color:red;">EInvoice Login Details</h5>
                                                                                                                                        <hr>
                                                                                                                                </div>
                                                                                                                                <?php $eInvoiceUserDetails = json_decode($inventory_billing->eInvoiceUserDetails);  ?>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eInvoice_username" class="text-dark">UserName:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[eInvoice_username]" placeholder="E-Invoice Users" type="text" class="form-control" value="<?= $eInvoiceUserDetails->eInvoice_username; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eInvoice_password" class="text-dark">Password:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[eInvoice_password]" placeholder="E-Invoice Password" type="text" class="form-control" value="<?= $eInvoiceUserDetails->eInvoice_password; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="eInvoice_mobile" class="text-dark">Registered Mobile:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[eInvoice_mobile]" placeholder="Enter Mobile" type="text" class="form-control" value="<?= $eInvoiceUserDetails->eInvoice_mobile!=""?$eInvoiceUserDetails->eInvoice_mobile:$inventory_billing->number; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>

                                                                                                                                <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                                                                        <hr>
                                                                                                                                        <h5 style="text-align:center;color:red;">EInvoice API Details</h5>
                                                                                                                                        <hr>
                                                                                                                                </div>

                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="api_username" class="text-dark">UserName:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[api_username]" placeholder="Api user" type="text" class="form-control" value="<?= $eInvoiceUserDetails->api_username; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                        <div class="form-group">
                                                                                                                                                <label for="api_password" class="text-dark">Password:<span class="text-danger"></span></label>
                                                                                                                                                <input name="eInvoiceUserDetails[api_password]" placeholder="api Password" type="text" class="form-control" value="<?= $eInvoiceUserDetails->api_password; ?>">
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                                <div class="col-md-4 col-sm-6 col-lg-4">
                                                                                                                                </div>
                                                                                                                                <hr>
                                                                                                                        </div>
                                                                                                                <?php } ?>


                                                                                                                <div class="col-md-12">
                                                                                                                        <div class="text-center">
                                                                                                                                <?php if ($CompProfile->eInvoice == "Y") { ?>
                                                                                                                                        <input type="button" id="refresh_gst" onclick="checkGstDetails()" class="btn btn-outline-primary" style="cursor: pointer;" class="btn btn-info" value="Refresh Gst" title="GST synchronize with GST Service Provider" />
                                                                                                                                <?php } ?>
                                                                                                                                <button type="submit" class="btn btn-primary btn-sm">
                                                                                                                                        Update
                                                                                                                                </button>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                <?php } ?>
                                                                                        </form>
                                                                                </div>
                                                                                <!-- Modal footer -->
                                                                                <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- COMPANY DETAILS END -->

                                                <!-- BARCODE START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#barcode">
                                                                Barcode Config
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="barcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl" role="document">
                                                                        <div class="modal-content">
                                                                                <?php if (isset($comp_profile) && !empty($comp_profile)) { ?>
                                                                                        <div id="message"></div>
                                                                                        <?php echo form_open('comp_profile/update_barcode_config', array('id' => 'contactForm')); ?>
                                                                                        <div class="modal-header">
                                                                                                <h5 class="modal-title text-dark" id="exampleModalLabel">Barcode Config</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                        </div>
                                                                                        <div class="modal-body">

                                                                                                <table class="table text-dark table-responsive">

                                                                                                        </style>
                                                                                                        <input type="hidden" name="comp_id" id="comp_id" value="<?= $comp_profile->comp_id ?>">
                                                                                                        <tr>
                                                                                                                <th>TITLE</th>
                                                                                                                <td><input class="form-control" type="text" tabindex="1" value="<?= $comp_profile->barcode_title; ?>" name="barcode_title" id="barcode_title"></td>
                                                                                                                <!-- </tr> -->
                                                                                                                <!-- <tr> -->
                                                                                                                <th>MRP PREFIX</th>
                                                                                                                <td><input type="text" class="form-control" tabindex="2" value="<?= $comp_profile->barcode_mrp_prefix ?>" name="barcode_mrp_prefix" id="barcode_mrp_prefix"></td>


                                                                                                                <!-- </tr>
                                                        <tr> -->
                                                                                                                <th>SELLING PREFIX</th>
                                                                                                                <td><input type="text" class="form-control" tabindex="3" value="<?= $comp_profile->barcode_sell_prefix ?>" name="barcode_sell_prefix" id="barcode_sell_prefix"></td>

                                                                                                                <th>PURCHASE PREFIX</th>
                                                                                                                <td><input type="text" class="form-control" tabindex="3" value="<?= $comp_profile->barcode_purchase_prefix ?>" name="barcode_purchase_prefix" id="barcode_purchase_prefix"></td>


                                                                                                                <!-- </tr>
                                                        <tr> -->

                                                                                                        </tr>
                                                                                                        <tr>

                                                                                                                <th>Price Code Field</th>
                                                                                                                <td>
                                                                                                                        <select class="form-control" style="width: auto;" name="price_code" tabindex="5" id="price_code">
                                                                                                                                <option value="purchase_price" <?= $comp_profile->price_code == 'purchase_price' ? 'selected' : '' ?>>Purchase Price</option>
                                                                                                                                <option value="sell_price" <?= $comp_profile->price_code == 'sell_price' ? 'selected' : '' ?>>Sell Price</option>
                                                                                                                                <option value="mrp_price" <?= $comp_profile->price_code == 'mrp_price' ? 'selected' : '' ?>>MRP Price</option>


                                                                                                                                <!-- <option>Product Name</option> -->

                                                                                                                        </select>
                                                                                                                </td>
                                                                                                                <th>
                                                                                                                        Field 1
                                                                                                                </th>
                                                                                                                <td>
                                                                                                                        <select class="form-control" style="width: auto;" name="item1" tabindex="6" id="item1">
                                                                                                                                <option value="product_name" <?= $comp_profile->item1 == 'product_name' ? 'selected' : '' ?>>Product Name</option>
                                                                                                                                <option value="product_code" <?= $comp_profile->item1 == 'product_code' ? 'selected' : '' ?>>Product Code</option>
                                                                                                                                <option value="mrp" <?= $comp_profile->item1 == 'mrp' ? 'selected' : '' ?>>MRP Price</option>
                                                                                                                                <option value="sell" <?= $comp_profile->item1 == 'sell' ? 'selected' : '' ?>>Sell Price</option>
                                                                                                                                <option value="purchase" <?= $comp_profile->item1 == 'purchase' ? 'selected' : '' ?>>Purchase Price</option>

                                                                                                                                <option value="hsn_code" <?= $comp_profile->item1 == 'hsn_code' ? 'selected' : '' ?>>HSN CODE</option>

                                                                                                                        </select>
                                                                                                                </td>
                                                                                                                <th>
                                                                                                                        Field 2
                                                                                                                </th>
                                                                                                                <td>
                                                                                                                        <select class="form-control" style="width: auto;" name="item2" tabindex="" id="item2">
                                                                                                                                <option value="product_name" <?= $comp_profile->item2 == 'product_name' ? 'selected' : '' ?>>Product Name</option>
                                                                                                                                <option value="product_code" <?= $comp_profile->item2 == 'product_code' ? 'selected' : '' ?>>Product Code</option>

                                                                                                                                <option value="mrp" <?= $comp_profile->item2 == 'mrp' ? 'selected' : '' ?>>MRP Price</option>
                                                                                                                                <option value="sell" <?= $comp_profile->item2 == 'sell' ? 'selected' : '' ?>>Sell Price</option>
                                                                                                                                <option value="purchase" <?= $comp_profile->item2 == 'purchase' ? 'selected' : '' ?>>Purchase Price</option>

                                                                                                                                <option value="hsn_code" <?= $comp_profile->item2 == 'hsn_code' ? 'selected' : '' ?>>HSN CODE</option>

                                                                                                                                <!-- <option value="purchase" <?= $comp_profile->item2 == 'purchase' ? 'selected' : '' ?>>Purchase Price</option> -->


                                                                                                                                <!-- <option>Product Name</option> -->

                                                                                                                        </select>
                                                                                                                </td>
                                                                                                                <th>
                                                                                                                        Field 3
                                                                                                                </th>
                                                                                                                <td>
                                                                                                                        <select class="form-control" style="width: auto;" name="item3" tabindex="" id="item3">
                                                                                                                                <option value="product_name" <?= $comp_profile->item3 == 'product_name' ? 'selected' : '' ?>>Product Name</option>
                                                                                                                                <option value="product_code" <?= $comp_profile->item3 == 'product_code' ? 'selected' : '' ?>>Product Code</option>
                                                                                                                                <option value="mrp" <?= $comp_profile->item3 == 'mrp' ? 'selected' : '' ?>>MRP Price</option>
                                                                                                                                <option value="sell" <?= $comp_profile->item3 == 'sell' ? 'selected' : '' ?>>Sell Price</option>
                                                                                                                                <option value="purchase" <?= $comp_profile->item3 == 'purchase' ? 'selected' : '' ?>>Purchase Price</option>

                                                                                                                                <!-- <option value="purchase" <?= $comp_profile->item3 == 'purchase' ? 'selected' : '' ?>>Purchase Price</option> -->
                                                                                                                                <option value="hsn_code" <?= $comp_profile->item3 == 'hsn_code' ? 'selected' : '' ?>>HSN CODE</option>

                                                                                                                                <!-- <option>Product Name</option> -->

                                                                                                                        </select>
                                                                                                                </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                                <th>BARCODE GENERATOR</th>
                                                                                                                <td>
                                                                                                                        <select class="form-control" name="barcode_generator" tabindex="4" id="barcode_generator">
                                                                                                                                <option value="productcode" <?= $comp_profile->barcode_generator == 'productcode' ? 'selected' : '' ?>>ProductCode</option>
                                                                                                                                <option value="productname" <?= $comp_profile->barcode_generator == 'productname' ? 'selected' : '' ?>>Product Name</option>
                                                                                                                                <!-- <option>Product Name</option> -->

                                                                                                                        </select>
                                                                                                                </td>
                                                                                                                <th>Barcode Pre Row</th>
                                                                                                                <td><input type="number" name="barcode_pre_row" id="barcode_pre_row" class="form-control" required min="1" max="4" placeholder="min 1 max 4" value="<?= $comp_profile->barcode_pre_row ?>"></td>
                                                                                                                <th>
                                                                                                                        Barcode Type
                                                                                                                </th>
                                                                                                                <td>
                                                                                                                        <select class="form-control" name="barcode_type" tabindex="10" id="barcode_type">
                                                                                                                                <option value="horzontal" <?= $comp_profile->barcode_type == 'horzontal' ? 'selected' : '' ?>>Horizontal</option>
                                                                                                                                <option value="vertical" <?= $comp_profile->barcode_type == 'vertical' ? 'selected' : '' ?>>Vertical</option>
                                                                                                                                <option value="virat" <?= $comp_profile->barcode_type == 'virat' ? 'selected' : '' ?>>VIRAT SILK</option>
                                                                                                                                <option value="sticker" <?= $comp_profile->barcode_type == 'sticker' ? 'selected' : '' ?>>Sticker</option>
                                                                                                                        </select>
                                                                                                                </td>
                                                                                                        </tr>
                                                                                                </table>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                        </div>
                                                                                        <?= form_close(); ?>
                                                                                <?php } ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- BARCODE END -->

                                                <!-- TERMS AND CODITION START -->
                                                <div class="col-md-2  col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#exampleModal">
                                                                T&C
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                ?>
                                                                                        <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_tc" method="post">
                                                                                                <input type="hidden" name="id" value="<?= $comp_profile->id ?>">
                                                                                                <div class="modal-header">
                                                                                                        <h5 class="modal-title" id="exampleModalLabel">Terms And Condtion</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                        <textarea id="YourEditor" class="form-control YourEditor" name="content"><?= $comp_profile->content; ?></textarea>
                                                                                                        <script type="text/javascript">
                                                                                                                ClassicEditor.create(document.querySelector('#YourEditor')).then(editor => {
                                                                                                                        console.log(editor);
                                                                                                                }).catch(error => {
                                                                                                                        console.error(error);
                                                                                                                });
                                                                                                        </script>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                                </div>
                                                                                        </form>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- TERMS AND CONDITIONS END -->

                                                <!-- BANK DETAILS START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-block btn-dark" data-toggle="modal" data-target="#bank">
                                                                Bank Details
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                ?>
                                                                                        <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_bank" method="post">
                                                                                                <input type="hidden" name="id" value="<?= $comp_profile->id ?>">
                                                                                                <div class="modal-header">
                                                                                                        <h5 class="modal-title" id="exampleModalLabel">Bank Information</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                        <textarea id="YourEditor3" class="form-control" required name="bank_details"><?= $comp_profile->bank_details; ?></textarea>
                                                                                                        <script>
                                                                                                                // CKEDITOR.replace('YourEditor3');
                                                                                                        </script>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                                </div>
                                                                                        </form>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                </div>
                                                <!-- BANK DETAILS END -->

                                                <!-- SUBJECT START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#subj">
                                                                Subject
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="subj" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                ?>
                                                                                        <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_subj" method="post">
                                                                                                <input type="hidden" name="id" value="<?= $comp_profile->id ?>">
                                                                                                <div class="modal-header">
                                                                                                        <h5 class="modal-title" id="exampleModalLabel">Subject To</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                        <textarea style="height: 148px;" class="form-control" required name="subject_to"><?= $comp_profile->subject_to; ?></textarea>
                                                                                                        <!-- <script>
                                                                                        CKEDITOR.replace('YourEditor4');
                                                                                </script> -->
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                                                                                </div>
                                                                                        </form>
                                                                                <?php

                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                </div>
                                                <!-- SUBJECT TO END -->

                                                <!-- INVOICE CONFIG START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-block btn-secondary" data-toggle="modal" data-target="#secondary">
                                                                Invoice Config
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="secondary" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                ?>
                                                                                        <form class="form" id="form" action="<?= base_url(); ?>comp_profile/update_total_invoice" enctype="multipart/form-data" method="POST">
                                                                                                <input type="hidden" name="id" value="<?= $comp_profile->id; ?>" />
                                                                                                <div class="modal-header">
                                                                                                        <h5 class="modal-title" id="exampleModalLabel" style="color: #000000;">Invoice Config</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-3">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="Number Of Copies" class="text-dark">Number Of Copies</label>
                                                                                                                                <input type="number" min="1" class="form-control" value="<?= $comp_profile->total_invoice ?>" name="total_invoice" id="total_invoice">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="format" class="text-dark">Bill Format</label>
                                                                                                                                <select id="cate" name="cate_id" required class="form-control">

                                                                                                                                        <option value="">--Select Option--</option>
                                                                                                                                        <?php
                                                                                                                                        $cate = $this->user_model->getResult('billing_format');
                                                                                                                                        if ($cate) {
                                                                                                                                                foreach ($cate as $key => $value) {
                                                                                                                                        ?>
                                                                                                                                                        <option value="<?= $value->id ?>" <?= $this->session->userdata('format_of_bill') == $value->id ? 'selected' : ''; ?>><?= $value->name; ?></option>


                                                                                                                                        <?php        }
                                                                                                                                        }
                                                                                                                                        ?>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="estimate" class="text-dark">Estimate Format</label>
                                                                                                                                <select id="estimate" name="estimate" required class="form-control">

                                                                                                                                        <option value="">--Select Option--</option>
                                                                                                                                        <?php
                                                                                                                                        $cate = $this->user_model->getResult('billing_format');
                                                                                                                                        if ($cate) {
                                                                                                                                                foreach ($cate as $key => $value) {
                                                                                                                                        ?>
                                                                                                                                                        <option value="<?= $value->id ?>" <?= $comp_profile->estimate == $value->id ? 'selected' : ''; ?>><?= $value->name; ?></option>


                                                                                                                                        <?php        }
                                                                                                                                        }
                                                                                                                                        ?>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="title" class="text-dark">Item Per Page</label>
                                                                                                                                <input type="number" class="form-control" value="<?= $comp_profile->item_per_page ?>" name="item_per_page" id="item_per_page">

                                                                                                                        </div>

                                                                                                                </div>
                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="title" class="text-dark">Invoice Title</label>
                                                                                                                                <input type="text" class="form-control" value="<?= $comp_profile->title_invoice ?>" name="title" id="title">

                                                                                                                        </div>

                                                                                                                </div>
                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="title" class="text-dark">Add Logo</label>
                                                                                                                                <input type="file" name="logo" class="form-control" accept="image/png, image/gif, image/jpeg">
                                                                                                                                <span class="text-dark" name="imagess"><?= $comp_profile->logo ?></span>

                                                                                                                        </div>

                                                                                                                </div>


                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="taxcal" class="text-dark">Default TaxCal</label>
                                                                                                                                <select id="taxcal" name="taxcal" required class="form-control">
                                                                                                                                        <option value="1" <?= set_value('taxcal', $comp_profile->taxcal) == '1' ? 'selected' : 'selected'; ?>>
                                                                                                                                                Inclusive</option>
                                                                                                                                        <option value="2" <?= set_value('taxcal', $comp_profile->taxcal) == '2' ? 'selected' : ''; ?>>
                                                                                                                                                Exclusive</option>


                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="voucherType" title="Voucher Type" class="text-dark">Payment Mode</label>
                                                                                                                                <select tabindex="3" name="voucherType" id="voucherType" required="true" class="form-control">
                                                                                                                                        <option value="1" <?= set_value('voucherType', $comp_profile->voucherType) == '1' ? 'selected' : ''; ?>>Cash</option>
                                                                                                                                        <option value="2" <?= set_value('voucherType', $comp_profile->voucherType) == '2' ? 'selected' : ''; ?>>Credit</option>
                                                                                                                                        <option value="Card" <?= set_value('voucherType', $comp_profile->voucherType) == 'Card' ? 'selected' : ''; ?>>Card</option>
                                                                                                                                        <option value="Phone Pay" <?= set_value('voucherType', $comp_profile->voucherType) == 'Phone Pay' ? 'selected' : ''; ?>>Phone Pay</option>
                                                                                                                                        <option value="Google Pay" <?= set_value('voucherType', $comp_profile->voucherType) == 'Google Pay' ? 'selected' : ''; ?>>Google Pay</option>
                                                                                                                                        <option value="Paytm" <?= set_value('voucherType', $comp_profile->voucherType) == 'Paytm' ? 'selected' : ''; ?>>Paytm</option>
                                                                                                                                        <option value="RTGS" <?= set_value('voucherType', $comp_profile->voucherType) == 'RTGS' ? 'selected' : ''; ?>>RTGS</option>
                                                                                                                                        <option value="NEFT" <?= set_value('voucherType', $comp_profile->voucherType) == 'NEFT' ? 'selected' : ''; ?>>NEFT</option>
                                                                                                                                        <option value="Cheque" <?= set_value('voucherType', $comp_profile->voucherType) == 'Cheque' ? 'selected' : ''; ?>>Cheque</option>
                                                                                                                                        <option value="IMPS" <?= set_value('voucherType', $comp_profile->voucherType) == 'IMPS' ? 'selected' : ''; ?>>IMPS</option>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="format" class="text-dark">Comp Details</label>
                                                                                                                                <select id="comp_details_position" name="comp_details_position" required class="form-control">

                                                                                                                                        <!-- <option value="left">Left</option> -->
                                                                                                                                        <option value="">--Select--</option>

                                                                                                                                        <option value="left" <?= set_value('comp_details_position', $comp_profile->comp_details_position) == 'left' ? 'selected' : ''; ?>>
                                                                                                                                                Left</option>
                                                                                                                                        <option value="center" <?= set_value('comp_details_position', $comp_profile->comp_details_position) == 'center' ? 'selected' : ''; ?>>
                                                                                                                                                Center</option>
                                                                                                                                        <option value="right" <?= set_value('comp_details_position', $comp_profile->comp_details_position) == 'right' ? 'selected' : ''; ?>>
                                                                                                                                                Right</option>


                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="title" class="text-dark">Scan Signature</label>
                                                                                                                                <input type="file" name="scan_signature" class="form-control" accept="image/png, image/gif, image/jpeg">
                                                                                                                                <span class="text-dark" id="scan_signature_name"><?= $comp_profile->scan_signature ?></span>

                                                                                                                        </div>

                                                                                                                </div>

                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="signature_display" class="text-dark">Signature</label>
                                                                                                                                <select id="signature_display" name="signature_display" required class="form-control">
                                                                                                                                        <option value="enable" <?= set_value('signature_display', $comp_profile->signature_display) == 'enable' ? 'selected' : 'selected'; ?>>
                                                                                                                                                Enable</option>
                                                                                                                                        <option value="disable" <?= set_value('signature_display', $comp_profile->signature_display) == 'disable' ? 'selected' : ''; ?>>
                                                                                                                                                Disable</option>


                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="signature_width" class="text-dark">Signature Width</label>
                                                                                                                                <input type="number" min="0" name="signature_width" id="signature_width" value="<?= $comp_profile->signature_width ?>" class="form-control">
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="signature_height" class="text-dark">Signature Height</label>
                                                                                                                                <input type="number" min="0" name="signature_height" id="signature_height" value="<?= $comp_profile->signature_height ?>" class="form-control">
                                                                                                                        </div>
                                                                                                                </div>


                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="logo_status" class="text-dark">Logo</label>
                                                                                                                                <select id="logo_status" onchange="ChangePosition();" name="logo_status" required class="form-control">
                                                                                                                                        <option value="enable" <?= set_value('logo_status', $comp_profile->logo_status) == 'enable' ? 'selected' : 'selected'; ?>>
                                                                                                                                                Enable</option>
                                                                                                                                        <option value="disable" <?= set_value('logo_status', $comp_profile->logo_status) == 'disable' ? 'selected' : ''; ?>>
                                                                                                                                                Disable</option>


                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>


                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="format" class="text-dark">Logo</label>
                                                                                                                                <?php
                                                                                                                                if ($comp_profile->logo_status == 'disable') {
                                                                                                                                ?>
                                                                                                                                        <select id="logo_position" name="logo_position" disabled class="form-control">
                                                                                                                                        <?php
                                                                                                                                } else {
                                                                                                                                        ?>
                                                                                                                                                <select id="logo_position" name="logo_position" class="form-control">

                                                                                                                                                <?php
                                                                                                                                        }
                                                                                                                                                ?>
                                                                                                                                                <option value="">--Select--</option>
                                                                                                                                                <option value="left" <?= set_value('logo_position', $comp_profile->logo_position) == 'left' ? 'selected' : ''; ?>>
                                                                                                                                                        Left</option>
                                                                                                                                                <option value="center" <?= set_value('logo_position', $comp_profile->logo_position) == 'center' ? 'selected' : ''; ?>>
                                                                                                                                                        Center</option>
                                                                                                                                                <option value="right" <?= set_value('logo_position', $comp_profile->logo_position) == 'right' ? 'selected' : ''; ?>>
                                                                                                                                                        Right</option>


                                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="logo_size_width" class="text-dark">Logo Size Width</label>
                                                                                                                                <input type="number" min="0" name="logo_size_width" id="logo_size_width" value="<?= $comp_profile->logo_width ?>" class="form-control">
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-md-3">

                                                                                                                        <div class="form-group">
                                                                                                                                <label for="logo_size_height" class="text-dark">Logo Size Height</label>
                                                                                                                                <input type="number" min="0" name="logo_size_height" id="logo_size_height" value="<?= $comp_profile->logo_height ?>" class="form-control">
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-2">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="payment_qr" class="text-dark">Payment QR</label>
                                                                                                                                <input type="file" name="payment_qr" class="form-control" accept="image/png, image/gif, image/jpeg" />
                                                                                                                                <span class="text-dark" id="payment_qr"><?= $comp_profile->payment_qr; ?></span>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-3">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="qr_status" class="text-dark">Payment QR</label>
                                                                                                                                <select id="qr_status" onchange="ChangePositionQR();" name="qr_status" required class="form-control">
                                                                                                                                        <option value="enable" <?= set_value('qr_status', $comp_profile->qr_status) == 'enable' ? 'selected' : 'selected'; ?>>Enable</option>
                                                                                                                                        <option value="disable" <?= set_value('qr_status', $comp_profile->qr_status) == 'disable' ? 'selected' : ''; ?>>Disable</option>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-3">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="qr_position" class="text-dark">Payment QR</label>
                                                                                                                                <?php if ($comp_profile->qr_status == 'disable') { ?>
                                                                                                                                        <select id="qr_position" name="qr_position" disabled="" class="form-control">
                                                                                                                                        <?php } else { ?>
                                                                                                                                                <select id="qr_position" name="qr_position" class="form-control">
                                                                                                                                                <?php } ?>
                                                                                                                                                <option value="">--Select--</option>
                                                                                                                                                <option value="left" <?= set_value('qr_position', $comp_profile->qr_position) == 'left' ? 'selected' : ''; ?>>Left</option>
                                                                                                                                                <option value="center" <?= set_value('qr_position', $comp_profile->qr_position) == 'center' ? 'selected' : ''; ?>>Center</option>
                                                                                                                                                <option value="right" <?= set_value('qr_position', $comp_profile->qr_position) == 'right' ? 'selected' : ''; ?>>Right</option>
                                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-2">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="qr_size_width" class="text-dark">QR Size Width</label>
                                                                                                                                <input type="number" min="0" name="qr_size_width" id="qr_size_width" value="<?= $comp_profile->qr_width; ?>" class="form-control" />
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-2">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="qr_size_height" class="text-dark">QR Size Height</label>
                                                                                                                                <input type="number" min="0" name="qr_size_height" id="qr_size_height" value="<?= $comp_profile->qr_height ?>" class="form-control" />
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="form-group col-md-3">
                                                                                                                        <label for="invoicePayment" class="text-dark">Invoice Payment</label>
                                                                                                                        <select class="form-control" name="invoicePayment" id="invoicePayment">
                                                                                                                                <option value="Enable" <?= $comp_profile->invoicePayment == 'Enable' ? 'selected' : ''; ?>>Enable</option>
                                                                                                                                <option value="Disable" <?= $comp_profile->invoicePayment == 'Disable' ? 'selected' : ''; ?>>Disable</option>
                                                                                                                        </select>
                                                                                                                </div>
                                                                                                                <div class="col-md-3">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="payment_due_period" class="text-dark">Payment Due Period</label>
                                                                                                                                <input type="number" name="payment_due_period" value="<?= $comp_profile->payment_due_period; ?>" id="payment_due_period" class="form-control" placeholder="Payment Due Period" min="0" />
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                                                                                </div>
                                                                                        </form>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                </div>
                                                <!-- INVOICE CONFIG END -->

                                                <!-- PERMISSION START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#permissi">
                                                                Permission
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="permissi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content text-dark">
                                                                                <?php if (isset($comp_profile) && !empty($comp_profile)) { ?>
                                                                                        <form class="form" id="form" action="<?= base_url(); ?>comp_profile/update_permiss" method="POST">
                                                                                                <input type="hidden" name="id" value="<?= $comp_profile->id ?>" />
                                                                                                <div class="modal-header">
                                                                                                        <h5 class="modal-title" id="exampleModalLabel">Permission</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                </div>
                                                                                                <div class="modal-body text-dark">
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">Product Code</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="barcode_con" value="yes" <?= set_value('barcode_con', $comp_profile->barcode_con) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="barcode_con" value="No" <?= set_value('barcode_con', $comp_profile->barcode_con) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="vahical_no">Vehicle Number</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="vahical_no" value="yes" <?= set_value('vahical_no', $comp_profile->vahical_no) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="vahical_no" value="No" <?= set_value('vahical_no', $comp_profile->vahical_no) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="SalesmanOnPrintOut">Salesman On Print Out</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="SalesmanOnPrintOut" value="Yes" <?= set_value('SalesmanOnPrintOut', $comp_profile->SalesmanOnPrintOut) == 'Yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="SalesmanOnPrintOut" value="No" <?= set_value('SalesmanOnPrintOut', $comp_profile->SalesmanOnPrintOut) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="lot_no">LUT</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="lot_no" value="Y" <?= set_value('lot_no', $comp_profile->lot_no) == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="lot_no" value="N" <?= set_value('lot_no', $comp_profile->lot_no) == 'N' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row" hidden>
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="lh">LH</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="lh" value="Y" <?= set_value('lh', $comp_profile->lh) == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="lh" value="N" <?= set_value('lh', $comp_profile->lh) == 'N' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="order_number">Order Number</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="order_number" value="yes" <?= set_value('order_number', $comp_profile->orderNumber) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="order_number" value="No" <?= set_value('order_number', $comp_profile->orderNumber) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="orderDate">Order Date</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="orderDate" value="yes" <?= set_value('orderDate', $comp_profile->orderDate) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="orderDate" value="No" <?= set_value('orderDate', $comp_profile->orderDate) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">Discount</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="discount" value="yes" <?= set_value('discount', $comp_profile->discount) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="discount" value="No" <?= set_value('discount', $comp_profile->discount) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">DC Date</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="dcdate" value="yes" <?= set_value('dcdate', $comp_profile->dcdate) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="dcdate" value="No" <?= set_value('dcdate', $comp_profile->dcdate) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">DC No</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="dcno" value="yes" <?= set_value('dcno', $comp_profile->dcno) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="dcno" value="No" <?= set_value('dcno', $comp_profile->dcno) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">Due Date</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="duedate" value="yes" <?= set_value('duedate', $comp_profile->duedate) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="duedate" value="No" <?= set_value('duedate', $comp_profile->duedate) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="due_date">Due Date Calculate</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="due_date" value="Yes" <?= set_value('due_date', $comp_profile->due_date) == 'Yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="due_date" value="No" <?= set_value('due_date', $comp_profile->due_date) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">PTD</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="ptd" value="yes" <?= set_value('ptd', $comp_profile->ptd) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="ptd" value="No" <?= set_value('ptd', $comp_profile->ptd) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">Dispatch By</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="dpb" value="yes" <?= set_value('dpb', $comp_profile->dpb) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="dpb" value="No" <?= set_value('dpb', $comp_profile->ptd) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <?php if ($_SESSION['IsAdmin'] == 'yes') {  ?>
                                                                                                                <div class="row">
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="eway">E-way Bill</label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="eway" value="yes" <?= set_value('eway', $comp_profile->eway) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="eway" value="No" <?= set_value('eway', $comp_profile->eway) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } else { ?>
                                                                                                                <div class="row">
                                                                                                                        <input type="hidden" name="eway" value="<?= $comp_profile->eway; ?>" />
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="eway">E-way Bill</label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="yes" <?= set_value('eway', $comp_profile->eway) == 'yes' ? "checked" : ""; ?> disabled /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="No" <?= set_value('eway', $comp_profile->eway) == 'No' ? "checked" : ""; ?> disabled /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } ?>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">LR Number</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="lrnumber" value="yes" <?= set_value('lrnumber', $comp_profile->lrnumber) == 'yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="lrnumber" value="No" <?= set_value('lrnumber', $comp_profile->lrnumber) == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="barcode">LR Date</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="lrdate" value="yes" <?= set_value('lrdate', $comp_profile->lrdate) == 'yes' ? "checked" : ""; ?> />Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="lrdate" value="No" <?= set_value('lrdate', $comp_profile->lrdate) == 'No' ? "checked" : ""; ?> />No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="estimate_format">Estimate Tax</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="estimate_format" value="Tax" <?= set_radio('estimate_format', $comp_profile->estimate_format); ?> <?= $comp_profile->estimate_format == 'Tax' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="estimate_format" value="Estimate" <?= set_radio('estimate_format', $comp_profile->estimate_format); ?> <?= $comp_profile->estimate_format == 'Estimate' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="estimate_format">Estimate Stock</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="estimate_stock" value="Yes" <?= set_radio('estimate_stock', $comp_profile->estimate_stock); ?> <?= $comp_profile->estimate_stock == 'Yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="estimate_stock" value="No" <?= set_radio('estimate_stock', $comp_profile->estimate_stock); ?> <?= $comp_profile->estimate_stock == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="estimate_format">Salesman Sale</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="salesman_sale" value="Yes" <?= set_radio('salesman_sale', $comp_profile->salesman_sale); ?> <?= $comp_profile->salesman_sale == 'Yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="salesman_sale" value="No" <?= set_radio('salesman_sale', $comp_profile->salesman_sale); ?> <?= $comp_profile->salesman_sale == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="estimate_format">Salesman Estimate</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="salesman_estimate" value="Yes" <?= set_radio('salesman_estimate', $comp_profile->salesman_estimate); ?> <?= $comp_profile->salesman_estimate == 'Yes' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="salesman_estimate" value="No" <?= set_radio('salesman_estimate', $comp_profile->salesman_estimate); ?> <?= $comp_profile->salesman_estimate == 'No' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="pos">POS</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="pos" value="Y" <?= set_radio('pos', $comp_profile->pos); ?> <?= $comp_profile->pos == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="pos" value="N" <?= set_radio('pos', $comp_profile->pos); ?> <?= $comp_profile->pos == 'N' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="party_wise_rate">
                                                                                                                                Party Wise Rate
                                                                                                                        </label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="party_wise_rate" value="Y" <?= set_radio('party_wise_rate', $comp_profile->party_wise_rate); ?> <?= $comp_profile->party_wise_rate == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="party_wise_rate" value="N" <?= set_radio('party_wise_rate', $comp_profile->party_wise_rate); ?> <?= $comp_profile->party_wise_rate == 'N' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="ProductStockCheck">
                                                                                                                                Product Stock Check
                                                                                                                        </label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="ProductStockCheck" value="Y" <?= set_radio('ProductStockCheck', $comp_profile->ProductStockCheck); ?> <?= $comp_profile->ProductStockCheck == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="ProductStockCheck" value="N" <?= set_radio('ProductStockCheck', $comp_profile->ProductStockCheck); ?> <?= $comp_profile->ProductStockCheck == 'N' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="formE">FORM - E</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="formE" value="Y" <?= set_radio('formE', $comp_profile->formE); ?> <?= $comp_profile->formE == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="formE" value="N" <?= set_radio('formE', $comp_profile->formE); ?> <?= $comp_profile->formE == 'N' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <?php if ($_SESSION['IsAdmin'] == 'yes') {  ?>
                                                                                                                <div class="row">
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="eInvoice">E Invoice</label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="eInvoice" value="Y" <?= set_radio('eInvoice', $comp_profile->eInvoice); ?> <?= $comp_profile->eInvoice == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="eInvoice" value="N" <?= set_radio('eInvoice', $comp_profile->eInvoice); ?> <?= $comp_profile->eInvoice == 'N' ? "checked" : ""; ?> /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } else { ?>
                                                                                                                <div class="row">
                                                                                                                        <input type="hidden" name="eInvoice" value="<?= $comp_profile->eInvoice; ?>" />
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="eInvoice">E Invoice</label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="Y" <?= set_radio('eInvoice', $comp_profile->eInvoice); ?> <?= $comp_profile->eInvoice == 'Y' ? "checked" : ""; ?> disabled /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="N" <?= set_radio('eInvoice', $comp_profile->eInvoice); ?> <?= $comp_profile->eInvoice == 'N' ? "checked" : ""; ?> disabled /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <?php } if ($_SESSION['IsAdmin'] == 'yes') {  ?>
                                                                                                                <div class="row">
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="eCRN_note">E-Credit Note</label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="eCRN_note" value="Y" <?= set_radio('eCRN_note', $comp_profile->eCRN_note); ?> <?= $comp_profile->eCRN_note == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="eCRN_note" value="N" <?= set_radio('eCRN_note', $comp_profile->eCRN_note); ?> <?= $comp_profile->eCRN_note == 'N' ? "checked" : ""; ?> /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } else { ?>
                                                                                                                <div class="row">
                                                                                                                        <input type="hidden" name="eCRN_note" value="<?= $comp_profile->eCRN_note; ?>" />
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="eCRN_note">E-Credit Note</label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="Y" <?= set_radio('eCRN_note', $comp_profile->eCRN_note); ?> <?= $comp_profile->eCRN_note == 'Y' ? "checked" : ""; ?> disabled /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="N" <?= set_radio('eCRN_note', $comp_profile->eCRN_note); ?> <?= $comp_profile->eCRN_note == 'N' ? "checked" : ""; ?> disabled /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } if ($_SESSION['IsAdmin'] == 'yes') {  ?>
                                                                                                                <div class="row">
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="eDBN_note">E-Debit Note</label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="eDBN_note" value="Y" <?= set_radio('eDBN_note', $comp_profile->eDBN_note); ?> <?= $comp_profile->eDBN_note == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="eDBN_note" value="N" <?= set_radio('eDBN_note', $comp_profile->eDBN_note); ?> <?= $comp_profile->eDBN_note == 'N' ? "checked" : ""; ?> /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } else { ?>
                                                                                                                <div class="row">
                                                                                                                        <input type="hidden" name="eDBN_note" value="<?= $comp_profile->eDBN_note; ?>" />
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="eDBN_note">E-Debit Note</label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="Y" <?= set_radio('eDBN_note', $comp_profile->eDBN_note); ?> <?= $comp_profile->eDBN_note == 'Y' ? "checked" : ""; ?> disabled /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="N" <?= set_radio('eDBN_note', $comp_profile->eDBN_note); ?> <?= $comp_profile->eDBN_note == 'N' ? "checked" : ""; ?> disabled /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } if ($_SESSION['IsAdmin'] == 'yes') {  ?>
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="gstVerifiedParty">GST Verified Party</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="gstVerifiedParty" value="Y" <?= set_radio('gstVerifiedParty', $comp_profile->gstVerifiedParty); ?> <?= $comp_profile->gstVerifiedParty == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="gstVerifiedParty" value="N" <?= set_radio('gstVerifiedParty', $comp_profile->gstVerifiedParty); ?> <?= $comp_profile->gstVerifiedParty == 'N' ? "checked" : ""; ?> /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>                                                                                                    
                                                                                                        <?php } else { ?> 
                                                                                                        <input type="hidden" name="gstVerifiedParty" value="<?= $comp_profile->gstVerifiedParty; ?>" />
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-4">
                                                                                                                        <label for="gstVerifiedParty">GST Verified Party</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" value="Y" <?= set_radio('gstVerifiedParty', $comp_profile->gstVerifiedParty); ?> <?= $comp_profile->gstVerifiedParty == 'Y' ? "checked" : ""; ?> disabled="" /> Yes
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-4">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" value="N" <?= set_radio('gstVerifiedParty', $comp_profile->gstVerifiedParty); ?> <?= $comp_profile->gstVerifiedParty == 'N' ? "checked" : ""; ?> disabled="" /> No
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                        <?php } ?>
                                                                                                        
                                                                                                         <?php if ($_SESSION['IsAdmin'] == 'yes') {  ?>
                                                                                                                <div class="row">
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="formE">
                                                                                                                                        Gstr1 Return
                                                                                                                                </label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="gstr1Status" value="Y" <?= set_radio('gstr1Status', $comp_profile->gstr1Status); ?> <?= $comp_profile->gstr1Status == 'Y' ? "checked" : ""; ?> /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" name="gstr1Status" value="N" <?= set_radio('gstr1Status', $comp_profile->gstr1Status); ?> <?= $comp_profile->gstr1Status == 'N' ? "checked" : ""; ?> /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } else { ?>
                                                                                                                <div class="row">
                                                                                                                        <input type="hidden" name="gstr1Status" value="<?= $comp_profile->gstr1Status == 'Y' ? "Y" : "No"; ?>" />
                                                                                                                        <div class="col-md-4">
                                                                                                                                <label for="formE">
                                                                                                                                         Gstr1 Return
                                                                                                                                </label>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="Y" <?= set_radio('gstr1Status', $comp_profile->gstr1Status); ?> <?= $comp_profile->gstr1Status == 'Y' ? "checked" : ""; ?> disabled /> Yes
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                        <div class="col-md-4">
                                                                                                                                <div class="form-group">
                                                                                                                                        <input type="radio" value="N" <?= set_radio('gstr1Status', $comp_profile->gstr1Status); ?> <?= $comp_profile->gstr1Status == 'N' ? "checked" : ""; ?> disabled /> No
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        <?php } ?>
                                                                                                        
                                                                                                        

                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                                </div>
                                                                                        </form>
                                                                                <?php } ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- PERMISSION END -->

                                                <!-- MRP CODE START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#mrp_code">
                                                                MRP CODE
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="mrp_code" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                        $first = '';
                                                                                        $second = '';
                                                                                        $third = '';
                                                                                        $fourth = '';
                                                                                        $fifth = '';
                                                                                        $sixth = '';
                                                                                        $seventh = '';
                                                                                        $eight = '';
                                                                                        $nine = '';
                                                                                        $zero = '';
                                                                                        $mrp_id = 0;
                                                                                ?>
                                                                                        <div class="text-dark" id="msgs"></div>
                                                                                        <!-- <div id="error_message"></div> -->

                                                                                        <?php echo form_open('comp_profile/mrp_barcode_config', array('id' => 'mrp')) ?>
                                                                                        <!-- <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_tc" method="post"> -->
                                                                                        <!-- <input type="hidden" name="comp_id" value="<?= $comp_profile->id ?>"> -->
                                                                                        <div class="modal-header">
                                                                                                <h5 class="modal-title text-dark" id="exampleModalLabel">MRP CODE GENERATOR</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                                <?php
                                                                                                $mrp_code = $this->user_model->getRow('mrp_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                                if ($mrp_code) {
                                                                                                        $first = $mrp_code->first;
                                                                                                        $second = $mrp_code->second;
                                                                                                        $third = $mrp_code->third;
                                                                                                        $fourth = $mrp_code->fourth;
                                                                                                        $fifth = $mrp_code->fifth;
                                                                                                        $seventh = $mrp_code->seventh;
                                                                                                        $sixth = $mrp_code->sixth;
                                                                                                        $eight = $mrp_code->eight;
                                                                                                        $nine = $mrp_code->nine;
                                                                                                        $zero = $mrp_code->zero;
                                                                                                        $mrp_id = $mrp_code->id;
                                                                                                }
                                                                                                ?>
                                                                                                <table class="table table-striped text-dark">


                                                                                                        <tr>
                                                                                                                <input type="hidden" name="mrp_id" id="mrp_id" value="<?= $mrp_id ?>">
                                                                                                                <th>0</th>
                                                                                                                <td><input class="form-control" type="text" value="<?= $zero ?>" tabindex="1" name="zero" id="zero" autofocus></td>
                                                                                                                <th>1</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $first ?>" tabindex="2" name="first" id="first"></td>

                                                                                                        </tr>


                                                                                                        <tr>
                                                                                                                <th>2</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $second ?>" tabindex="3" name="second" id="second"></td>
                                                                                                                <th>3</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $third ?>" tabindex="4" name="third" id="third"></td>


                                                                                                        </tr>

                                                                                                        <!-- <tr>


                                                                                </tr> -->

                                                                                                        <tr>
                                                                                                                <th>4</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $fourth ?>" tabindex="5" name="fourth" id="fourth"></td>



                                                                                                                <th>5</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $fifth ?>" tabindex="6" name="fifth" id="fifth"></td>


                                                                                                        </tr>

                                                                                                        <tr>
                                                                                                                <th>6</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $sixth ?>" tabindex="7" name="sixth" id="sixth"></td>



                                                                                                                <th>7</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $seventh ?>" tabindex="8" name="seventh" id="seventh"></td>


                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                                <th>8</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $eight ?>" tabindex="9" name="eight" id="eight"></td>



                                                                                                                <th>9</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $nine ?>" tabindex="10" name="nine" id="nine"></td>


                                                                                                        </tr>

                                                                                                </table>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary" tabindex="11">Update </button>
                                                                                        </div>
                                                                                        </form>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- MRP CODE END -->

                                                <!-- PURCHASE CODE START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-dark btn-block" data-toggle="modal" data-target="#purchase_code">
                                                                PURCHASE CODE
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="purchase_code" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                        $first = '';
                                                                                        $second = '';
                                                                                        $third = '';
                                                                                        $fourth = '';
                                                                                        $fifth = '';
                                                                                        $sixth = '';
                                                                                        $seventh = '';
                                                                                        $eight = '';
                                                                                        $nine = '';
                                                                                        $zero = '';
                                                                                        $pur_id = 0;
                                                                                ?>
                                                                                        <div id="purchase_msg"></div>
                                                                                        <!-- <div id="error_message"></div> -->

                                                                                        <?php echo form_open('comp_profile/purchase_barcode_config', array('id' => 'purchase', 'class' => 'purchase')) ?>
                                                                                        <!-- <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_tc" method="post"> -->
                                                                                        <!-- <input type="hidden" name="comp_id" value="<?= $comp_profile->id ?>"> -->
                                                                                        <div class="modal-header">
                                                                                                <h5 class="modal-title text-dark" id="exampleModalLabel">Purchase CODE GENERATOR</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                                <?php
                                                                                                $mrp_code = $this->user_model->getRow('purchase_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                                if ($mrp_code) {
                                                                                                        $first = $mrp_code->first;
                                                                                                        $second = $mrp_code->second;
                                                                                                        $third = $mrp_code->third;
                                                                                                        $fourth = $mrp_code->fourth;
                                                                                                        $fifth = $mrp_code->fifth;
                                                                                                        $seventh = $mrp_code->seventh;
                                                                                                        $sixth = $mrp_code->sixth;
                                                                                                        $eight = $mrp_code->eight;
                                                                                                        $nine = $mrp_code->nine;
                                                                                                        $zero = $mrp_code->zero;
                                                                                                        $pur_id = $mrp_code->id;
                                                                                                }
                                                                                                ?>
                                                                                                <table class="table table-striped text-dark">

                                                                                                        </style>
                                                                                                        <tr>
                                                                                                                <input type="hidden" value="<?= $pur_id ?>" name="purchase_id" id="purchase_id">
                                                                                                                <th>0</th>
                                                                                                                <td><input class="form-control" type="text" value="<?= $zero ?>" tabindex="1" name="zero" id="zero" autofocus></td>
                                                                                                                <th>1</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $first ?>" tabindex="2" name="first" id="first"></td>

                                                                                                        </tr>


                                                                                                        <tr>
                                                                                                                <th>2</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $second ?>" tabindex="3" name="second" id="second"></td>
                                                                                                                <th>3</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $third ?>" tabindex="4" name="third" id="third"></td>


                                                                                                        </tr>

                                                                                                        <!-- <tr>


                                                                                </tr> -->

                                                                                                        <tr>
                                                                                                                <th>4</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $fourth ?>" tabindex="5" name="fourth" id="fourth"></td>



                                                                                                                <th>5</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $fifth ?>" tabindex="6" name="fifth" id="fifth"></td>


                                                                                                        </tr>

                                                                                                        <tr>
                                                                                                                <th>6</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $sixth ?>" tabindex="7" name="sixth" id="sixth"></td>



                                                                                                                <th>7</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $seventh ?>" tabindex="8" name="seventh" id="seventh"></td>


                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                                <th>8</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $eight ?>" tabindex="9" name="eight" id="eight"></td>



                                                                                                                <th>9</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $nine ?>" tabindex="10" name="nine" id="nine"></td>


                                                                                                        </tr>

                                                                                                </table>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary" tabindex="11">Update </button>
                                                                                        </div>
                                                                                        </form>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- PURCHASE CODE END -->

                                                <!-- SALE CONFIGRATION -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#a5invoice">
                                                                Sale Config
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="a5invoice" tabindex="-1" role="dialog" aria-labelledby="a5invoice" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content text-dark">
                                                                                <?php $a5config = $this->user_model->getRow('a5config', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]); ?>
                                                                                <div id="a5permission_message"></div>

                                                                                <?= form_open('comp_profile/update_a5config', array('id' => 'a5permission')); ?>
                                                                                <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">SALE INVOICE CONFIG PERMISSION</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
                                                                                <div class="modal-body text-dark">
                                                                                        <div class="row card-header">
                                                                                                <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                                <label for="title">Invoice Title</label>
                                                                                                                <input type="text" name="title" value="<?= $a5config->title ?>" class="form-control">
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Company Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_name">Company Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="Y" <?= set_value('comp_name', $a5config->comp_name) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="N" <?= set_value('comp_name', $a5config->comp_name) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_mobile_number">Company Mobile Number</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="Y" <?= set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="N" <?= set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_gst">Company GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="Y" <?= set_value('comp_gst', $a5config->comp_gst) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="N" <?= set_value('comp_gst', $a5config->comp_gst) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_address">Company Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="Y" <?= set_value('comp_address', $a5config->comp_address) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="N" <?= set_value('comp_address', $a5config->comp_address) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_email">Company Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="Y" <?= set_value('comp_email', $a5config->comp_email) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="N" <?= set_value('comp_email', $a5config->comp_email) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border: 1px solid;">Customer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                        <label for="customer_name">Customer Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="Y" <?= set_value('customer_name', $a5config->customer_name) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="N" <?= set_value('customer_name', $a5config->customer_name) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="PartyContactPerson">Party Contact Person:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="PartyContactPerson" value="Y" <?= set_value('PartyContactPerson', $a5config->PartyContactPerson) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="PartyContactPerson" value="N" <?= set_value('PartyContactPerson', $a5config->PartyContactPerson) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="customer_address">Customer Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="Y" <?= set_value('customer_address', $a5config->customer_address) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="N" <?= set_value('customer_address', $a5config->customer_address) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="customer_gst">Customer GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="Y" <?= set_value('customer_gst', $a5config->customer_gst) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="N" <?= set_value('customer_gst', $a5config->customer_gst) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="shipping_address">Shipping Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="Y" <?= set_value('shipping_address', $a5config->shipping_address) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="N" <?= set_value('shipping_address', $a5config->shipping_address) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="customer_email">Customer Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="Y" <?= set_value('customer_email', $a5config->customer_email) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="N" <?= set_value('customer_email', $a5config->customer_email) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="customer_mobile_number">Customer Mobile Number:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="Y" <?= set_value('customer_mobile_number', $a5config->customer_mobile_number) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="N" <?= set_value('customer_mobile_number', $a5config->customer_mobile_number) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="CustomerLocationQR">Customer Location QR:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="CustomerLocationQR" value="Y" <?= set_value('CustomerLocationQR', $a5config->CustomerLocationQR) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="CustomerLocationQR" value="N" <?= set_value('CustomerLocationQR', $a5config->CustomerLocationQR) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border: 1px solid;">Product Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                        <label for="hsn">Product HSN</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="Y" <?= set_value('hsn', $a5config->hsn) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="N" <?= set_value('hsn', $a5config->hsn) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="qty">Product Qty</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="Y" <?= set_value('qty', $a5config->qty) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="N" <?= set_value('qty', $a5config->qty) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="sqrfoot">Square Foot</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sqrfoot" value="Y" <?= set_value('sqrfoot', $a5config->sqrfoot) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sqrfoot" value="N" <?= set_value('sqrfoot', $a5config->sqrfoot) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="sell_price">Product Selling Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="Y" <?= set_value('qty', $a5config->sell_price) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="N" <?= set_value('sell_price', $a5config->sell_price) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="mrp">Product MRP Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="Y" <?= set_value('mrp', $a5config->mrp) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="N" <?= set_value('mrp', $a5config->mrp) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="unit">Unit</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="unit" value="Y" <?= set_value('unit', $a5config->unit) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="unit" value="N" <?= set_value('unit', $a5config->unit) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="SubUnit">Sub Unit</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="SubUnit" value="Y" <?= set_value('SubUnit', $a5config->SubUnit) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="SubUnit" value="N" <?= set_value('SubUnit', $a5config->SubUnit) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">

                                                                                                        <label for="discount_invoice">Discount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="discount_invoice" value="Y" <?php
                                                                                                                                                                        echo set_value('discount_invoice', $a5config->discount_invoice) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="discount_invoice" value="N" <?php
                                                                                                                                                                                        echo set_value('discount_invoice', $a5config->discount_invoice) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">

                                                                                                        <label for="purchase">Product Purchase Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="purchase_price" value="Y" <?php
                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="purchase_price" value="N" <?php
                                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax%</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="tax" value="Y" <?php
                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="tax" value="N" <?php
                                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxable_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxable_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxamount" value="Y" <?php
                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxamount" value="N" <?php
                                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Net Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="net_amount" value="Y" <?php
                                                                                                                                                                echo set_value('net_amount', $a5config->net_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="net_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('net_amount', $a5config->net_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Total Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_taxable_amount" value="Y" <?php
                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_taxable_amount" value="N" <?php
                                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_tax_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_tax_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                    </div>

                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">GRAND TOTAL</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="grand_total" value="Y" <?php
                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="grand_total" value="N" <?php
                                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="round_off">ROUND OFF</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="Y" <?php
                                                                                                                                                                echo set_value('round_off', $a5config->round_off) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="round_off" value="N" <?php
                                                                                                                                                                                echo set_value('round_off', $a5config->round_off) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <div class="col-md-4">

                                                                                                        <label for="total_summary">TOTAL SUMMARY</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_summary" value="Y" <?php
                                                                                                                                                                        echo set_value('total_summary', $a5config->total_summary) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_summary" value="N" <?= set_value('total_summary', $a5config->total_summary) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="freight_charge">Freight Charge</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="Y" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="N" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="cash_discount">Cash Discount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="Y" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="N" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="total_summary">HSN/SAC SUMMARY</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsnsac_summary" value="Y" <?= set_radio('hsnsac_summary', $a5config->hsnsac_summary); ?> <?= $a5config->hsnsac_summary == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsnsac_summary" value="N" <?= set_radio('hsnsac_summary', $a5config->hsnsac_summary); ?> <?= $a5config->hsnsac_summary == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <div class="col-md-4">

                                                                                                        <label for="you_saved">YOU SAVED</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="you_saved" value="Y" <?php
                                                                                                                                                                echo set_value('you_saved', $a5config->you_saved) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="you_saved" value="N" <?php
                                                                                                                                                                                echo set_value('you_saved', $a5config->you_saved) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Bank && Footer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="bank_info">BANK INFO</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="bank_info" value="Y" <?php
                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="bank_info" value="N" <?php
                                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="remark">Remark</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="remark" value="Y" <?php
                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="remark" value="N" <?php
                                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">

                                                                                                        <label for="signature">Receiver Signatures</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="signature" value="Y" <?php
                                                                                                                                                                echo set_value('signature', $a5config->signature) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="signature" value="N" <?php
                                                                                                                                                                                echo set_value('signature', $a5config->signature) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <div class="col-md-4">

                                                                                                        <label for="footer">FOOTER</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="footer" value="Y" <?php
                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="footer" value="N" <?php
                                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                        </div>
                                                                                </div>
                                                                                <?= form_close(); ?>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                        <!-- A5 END -->
                                                </div>
                                                <!-- SALES CONFIGRATION END-->

                                                <!-- PURCHASE CONFIGRATION  START-->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->

                                                        <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#purchase_config">
                                                                Purchase Config
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="purchase_config" tabindex="-1" role="dialog" aria-labelledby="a5invoice" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content text-dark">
                                                                                <?php

                                                                                $a5config = $this->user_model->getRow('purchase_config', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                ?>
                                                                                <div id="purchase_config_message"></div>
                                                                                <!-- <div id="error_message"></div> -->

                                                                                <?php echo form_open('comp_profile/purchase_config', array('id' => 'purchase_configpermission')) ?>
                                                                                <!-- <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_a5config" method="post"> -->
                                                                                <!-- <input type="hidden" name="id" value="<?= $comp_profile->id ?>"> -->
                                                                                <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">PURCHASE INVOICE CONFIG PERMISSION</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
                                                                                <div class="modal-body text-dark">

                                                                                        <div class="row card-header">
                                                                                                <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                                <label for="title">Invoice Title</label>
                                                                                                                <input type="text" name="purchase_title" value="<?= $a5config->title ?>" class="form-control">
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                        </div>

                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Company Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_name">Company Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="Y" <?php
                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_name" value="N" <?php
                                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_mobile_number">Customer Mobile Number</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="N" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_gst">Company GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="Y" <?php
                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_gst" value="N" <?php
                                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_address">Company Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_address" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_email">Company Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="Y" <?php
                                                                                                                                                                echo set_value('comp_email', $a5config->comp_email) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_email" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_email', $a5config->comp_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div> <!-- </div> -->
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Customer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_name">Customer Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_name" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="N" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_gst" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="shipping_address">Shipping Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="Y" <?php
                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="shipping_address" value="N" <?php
                                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_email" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Mobile Number:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="Y" <?php
                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_mobile_number" value="N" <?php
                                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div> <!-- </div> -->
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Product Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">

                                                                                                <div class="col-md-4">

                                                                                                        <label for="hsn">Product HSN</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="Y" <?php
                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="hsn" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="qty">Product Qty</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->qty) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="qty" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->qty) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                                    </div>

                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="sell_price">Product Selling Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->sell_price) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="sell_price" value="N" <?php
                                                                                                                                                                                        echo set_value('sell_price', $a5config->sell_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="mrp">Product MRP Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="Y" <?php
                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="mrp" value="N" <?php
                                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="purchase">Product Purchase Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="purchase_price" value="Y" <?php
                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="purchase_price" value="N" <?php
                                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax%</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="tax" value="Y" <?php
                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="tax" value="N" <?php
                                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxable_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxable_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxamount" value="Y" <?php
                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxamount" value="N" <?php
                                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Net Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="net_amount" value="Y" <?php
                                                                                                                                                                echo set_value('net_amount', $a5config->net_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="net_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('net_amount', $a5config->net_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>


                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Total Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_taxable_amount" value="Y" <?php
                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_taxable_amount" value="N" <?php
                                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_tax_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_tax_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                            </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">GRAND TOTAL</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="grand_total" value="Y" <?php
                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="grand_total" value="N" <?php
                                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">
                                                                                                        <label for="round_off">ROUND OFF</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="Y" <?= set_value('round_off', $a5config->round_off) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="N" <?= set_value('round_off', $a5config->round_off) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="freight_charge">Freight Charge</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="Y" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="N" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="cash_discount">Cash Discount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="Y" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="N" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Bank && Footer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="bank_info">BANK INFO</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="bank_info" value="Y" <?php
                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="bank_info" value="N" <?php
                                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="remark">Remark</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="remark" value="Y" <?php
                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="remark" value="N" <?php
                                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <div class="col-md-4">

                                                                                                        <label for="signature">Receiver Signatures</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="signature" value="Y" <?php
                                                                                                                                                                echo set_value('signature', $a5config->signature) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="signature" value="N" <?php
                                                                                                                                                                                echo set_value('signature', $a5config->signature) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <div class="col-md-4">

                                                                                                        <label for="footer">FOOTER</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="footer" value="Y" <?php
                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="footer" value="N" <?php
                                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div>

                                                                                        <!-- </div> -->
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                        </div>
                                                                                        </form>
                                                                                </div>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                        <!-- A5 END -->
                                                </div>
                                                <!-- PURCHASE CONFIGRATION END -->

                                                <!--Credit Note Config-->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->

                                                        <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#credit_note_config">
                                                                Credit Note Config
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="credit_note_config" tabindex="-1" role="dialog" aria-labelledby="a5invoice" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content text-dark">
                                                                                <?php

                                                                                $a5config = $this->user_model->getRow('credit_note_config', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                ?>
                                                                                <div id="credit_note_config_message"></div>
                                                                                <!-- <div id="error_message"></div> -->

                                                                                <?php echo form_open('comp_profile/credit_note_config', array('id' => 'credit_note_configpermission')) ?>
                                                                                <!-- <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_a5config" method="post"> -->
                                                                                <!-- <input type="hidden" name="id" value="<?= $comp_profile->id ?>"> -->
                                                                                <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">CRIDITE NOTE INVOICE CONFIG</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
                                                                                <div class="modal-body text-dark">

                                                                                        <div class="row card-header">
                                                                                                <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                                <label for="title">Invoice Title</label>
                                                                                                                <input type="text" name="title" value="<?= $a5config->title ?>" class="form-control">
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                        </div>

                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Company Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_name">Company Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="Y" <?php
                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_name" value="N" <?php
                                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_mobile_number">Customer Mobile Number</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="N" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_gst">Company GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="Y" <?php
                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_gst" value="N" <?php
                                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_address">Company Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_address" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_email">Company Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="Y" <?php
                                                                                                                                                                echo set_value('comp_email', $a5config->comp_email) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_email" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_email', $a5config->comp_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div> <!-- </div> -->
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Customer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_name">Customer Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_name" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="N" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_gst" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="shipping_address">Shipping Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="Y" <?php
                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="shipping_address" value="N" <?php
                                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_email" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Mobile Number:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="Y" <?php
                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_mobile_number" value="N" <?php
                                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div> <!-- </div> -->
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Product Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">

                                                                                                <div class="col-md-4">

                                                                                                        <label for="hsn">Product HSN</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="Y" <?php
                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="hsn" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="qty">Product Qty</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->qty) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="qty" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->qty) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                                    </div>

                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="sell_price">Product Selling Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->sell_price) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="sell_price" value="N" <?php
                                                                                                                                                                                        echo set_value('sell_price', $a5config->sell_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="mrp">Product MRP Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="Y" <?php
                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="mrp" value="N" <?php
                                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="purchase">Product Purchase Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="purchase_price" value="Y" <?php
                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="purchase_price" value="N" <?php
                                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax%</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="tax" value="Y" <?php
                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="tax" value="N" <?php
                                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxable_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxable_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxamount" value="Y" <?php
                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxamount" value="N" <?php
                                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Net Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="net_amount" value="Y" <?php
                                                                                                                                                                echo set_value('net_amount', $a5config->net_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="net_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('net_amount', $a5config->net_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>


                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Total Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_taxable_amount" value="Y" <?php
                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_taxable_amount" value="N" <?php
                                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_tax_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_tax_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                            </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">GRAND TOTAL</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="grand_total" value="Y" <?php
                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="grand_total" value="N" <?php
                                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="round_off">ROUND OFF</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="Y" <?php
                                                                                                                                                                echo set_value('round_off', $a5config->round_off) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="round_off" value="N" <?php
                                                                                                                                                                                echo set_value('round_off', $a5config->round_off) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Bank && Footer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="bank_info">BANK INFO</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="bank_info" value="Y" <?php
                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="bank_info" value="N" <?php
                                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="remark">Remark</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="remark" value="Y" <?php
                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="remark" value="N" <?php
                                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">

                                                                                                        <label for="footer">FOOTER</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="footer" value="Y" <?php
                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="footer" value="N" <?php
                                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div>

                                                                                        <!-- </div> -->
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                        </div>
                                                                                        </form>
                                                                                </div>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                        <!-- A5 END -->
                                                </div>
                                                <!--Credit Note Config end -->

                                                <!-- Debit Note Config -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->

                                                        <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#debit_note_config">
                                                                Debit Note Config
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="debit_note_config" tabindex="-1" role="dialog" aria-labelledby="a5invoice" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content text-dark">
                                                                                <?php

                                                                                $a5config = $this->user_model->getRow('debit_note_config', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                ?>
                                                                                <div id="credit_note_config_message"></div>
                                                                                <!-- <div id="error_message"></div> -->

                                                                                <?php echo form_open('comp_profile/debit_note_config', array('id' => 'debit_note_configpermission')) ?>
                                                                                <!-- <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_a5config" method="post"> -->
                                                                                <!-- <input type="hidden" name="id" value="<?= $comp_profile->id ?>"> -->
                                                                                <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalLabel">DEBIT NOTE INVOICE CONFIG</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
                                                                                <div class="modal-body text-dark">

                                                                                        <div class="row card-header">
                                                                                                <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                                <label for="title">Invoice Title</label>
                                                                                                                <input type="text" name="title" value="<?= $a5config->title ?>" class="form-control">
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                        </div>

                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Company Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_name">Company Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="Y" <?php
                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_name" value="N" <?php
                                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_mobile_number">Customer Mobile Number</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="N" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_gst">Company GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="Y" <?php
                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_gst" value="N" <?php
                                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_address">Company Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_address" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_email">Company Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="Y" <?php
                                                                                                                                                                echo set_value('comp_email', $a5config->comp_email) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_email" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_email', $a5config->comp_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div> <!-- </div> -->
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Customer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_name">Customer Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_name" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="N" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_gst" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="shipping_address">Shipping Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="Y" <?php
                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="shipping_address" value="N" <?php
                                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_email" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Mobile Number:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="Y" <?php
                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_mobile_number" value="N" <?php
                                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div> <!-- </div> -->
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Product Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">

                                                                                                <div class="col-md-4">

                                                                                                        <label for="hsn">Product HSN</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="Y" <?php
                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="hsn" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="qty">Product Qty</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->qty) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="qty" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->qty) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                                    </div>

                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="sell_price">Product Selling Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->sell_price) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="sell_price" value="N" <?php
                                                                                                                                                                                        echo set_value('sell_price', $a5config->sell_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="mrp">Product MRP Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="Y" <?php
                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="mrp" value="N" <?php
                                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="purchase">Product Purchase Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="purchase_price" value="Y" <?php
                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="purchase_price" value="N" <?php
                                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax%</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="tax" value="Y" <?php
                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="tax" value="N" <?php
                                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxable_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxable_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxamount" value="Y" <?php
                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxamount" value="N" <?php
                                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Net Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="net_amount" value="Y" <?php
                                                                                                                                                                echo set_value('net_amount', $a5config->net_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="net_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('net_amount', $a5config->net_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>


                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Total Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_taxable_amount" value="Y" <?php
                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_taxable_amount" value="N" <?php
                                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_tax_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_tax_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                            </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">GRAND TOTAL</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="grand_total" value="Y" <?php
                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="grand_total" value="N" <?php
                                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="round_off">ROUND OFF</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="Y" <?php
                                                                                                                                                                echo set_value('round_off', $a5config->round_off) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="round_off" value="N" <?php
                                                                                                                                                                                echo set_value('round_off', $a5config->round_off) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Bank && Footer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="bank_info">BANK INFO</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="bank_info" value="Y" <?php
                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="bank_info" value="N" <?php
                                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="remark">Remark</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="remark" value="Y" <?php
                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="remark" value="N" <?php
                                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">

                                                                                                        <label for="footer">FOOTER</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="footer" value="Y" <?php
                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="footer" value="N" <?php
                                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div>

                                                                                        <!-- </div> -->
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                        </div>
                                                                                        </form>
                                                                                </div>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                        <!-- A5 END -->
                                                </div>
                                                <!-- Debit Note Config end -->

                                                <!-- Estimate CONFIGRATION -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->

                                                        <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#aman">
                                                                ESTIMATE CONFIG
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="aman" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel123" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content text-dark">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {

                                                                                        $a5config = $this->user_model->getRow('estimateconfig', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                }
                                                                                ?>
                                                                                <div id="estimateconfig_message"></div>
                                                                                <!-- <div id="error_message"></div> -->

                                                                                <?php echo form_open('comp_profile/estimateconfig', array('id' => 'estimateconfig')) ?>
                                                                                <!-- <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_a5config" method="post"> -->
                                                                                <!-- <input type="hidden" name="id" value="<?= $comp_profile->id ?>"> -->
                                                                                <div class="modal-header">
                                                                                        <h5 class="modal-title" id="exampleModalLabel123">ESTIMATE CONFIG</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
                                                                                <div class="modal-body text-dark">

                                                                                        <div class="row card-header">
                                                                                                <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                                <label for="title">Invoice Title</label>
                                                                                                                <input type="text" name="title" value="<?= $a5config->title ?>" class="form-control">
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Company Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_name">Company Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="Y" <?php
                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_name" value="N" <?php
                                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_mobile_number">Customer Mobile Number</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="N" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_gst">Company GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="Y" <?php
                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_gst" value="N" <?php
                                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_address">Company Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_address" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_email">Company Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="Y" <?php
                                                                                                                                                                echo set_value('comp_email', $a5config->comp_email) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_email" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_email', $a5config->comp_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Customer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_name">Customer Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_name" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="N" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_gst" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="shipping_address">Shipping Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="Y" <?php
                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="shipping_address" value="N" <?php
                                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_email" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Mobile Number:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="Y" <?php
                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_mobile_number" value="N" <?php
                                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div> <!-- </div> -->
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Product Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">

                                                                                                <div class="col-md-4">

                                                                                                        <label for="hsn">Product HSN</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="Y" <?php
                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="hsn" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="qty">Product Qty</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->qty) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="qty" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->qty) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                                        </div>

                                                                        <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="sell_price">Product Selling Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->sell_price) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="sell_price" value="N" <?php
                                                                                                                                                                                        echo set_value('sell_price', $a5config->sell_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="mrp">Product MRP Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="Y" <?php
                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="mrp" value="N" <?php
                                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                                        <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="purchase">Product Purchase Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="purchase_price" value="Y" <?php
                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="purchase_price" value="N" <?php
                                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax%</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="tax" value="Y" <?php
                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="tax" value="N" <?php
                                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                        <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxable_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxable_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                        <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxamount" value="Y" <?php
                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxamount" value="N" <?php
                                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Net Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="net_amount" value="Y" <?php
                                                                                                                                                                echo set_value('net_amount', $a5config->net_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="net_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('net_amount', $a5config->net_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>


                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Total Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_taxable_amount" value="Y" <?php
                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_taxable_amount" value="N" <?php
                                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                                <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_tax_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_tax_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                                </div>

                                                                <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">GRAND TOTAL</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="grand_total" value="Y" <?php
                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="grand_total" value="N" <?php
                                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                                <div class="row"> -->
                                                                                                <div class="col-md-4">
                                                                                                        <label for="round_off">ROUND OFF</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="Y" <?= set_value('round_off', $a5config->round_off) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="N" <?= set_value('round_off', $a5config->round_off) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="freight_charge">Freight Charge</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="Y" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="N" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="cash_discount">Cash Discount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="Y" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="N" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Bank && Footer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="bank_info">BANK INFO</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="bank_info" value="Y" <?php
                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="bank_info" value="N" <?php
                                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                                <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="remark">Remark</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="remark" value="Y" <?php
                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="remark" value="N" <?php
                                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">

                                                                                                        <label for="footer">FOOTER</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="footer" value="Y" <?php
                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="footer" value="N" <?php
                                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                        </div>

                                                                                        <!-- </div> -->
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                        </div>
                                                                                        </form>
                                                                                </div>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                        <!-- A5 END -->
                                                </div>
                                                <!-- Estimate CONFIGRATION -->

                                                <!-- Estimate Purchase Configuration -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-block btn-danger" data-toggle="modal" data-target="#estimate_purchase_config">
                                                                Estimate Purchase Config
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="estimate_purchase_config" tabindex="-1" role="dialog" aria-labelledby="estimate_purchase_config" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content text-dark">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {

                                                                                        $a5config = $this->user_model->getRow('estimate_purchase_config', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                }
                                                                                ?>
                                                                                <div id="estimate_purchase_config_message"></div>
                                                                                <!-- <div id="error_message"></div> -->

                                                                                <?php echo form_open('comp_profile/estimate_purchase_config', array('id' => 'estimate_purchase_config_form')) ?>
                                                                                <!-- <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_a5config" method="post"> -->
                                                                                <!-- <input type="hidden" name="id" value="<?= $comp_profile->id ?>"> -->
                                                                                <div class="modal-header">
                                                                                        <h5 class="modal-title" id="estimate_purchase_config">Estimate Purchase Config</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
                                                                                <div class="modal-body text-dark">

                                                                                        <div class="row card-header">
                                                                                                <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                                <label for="title">Invoice Title</label>
                                                                                                                <input type="text" name="title" value="<?= $a5config->title ?>" class="form-control">
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>

                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>

                                                                                                </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Company Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_name">Company Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="Y" <?php
                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_name" value="N" <?php
                                                                                                                                                                                echo set_value('comp_name', $a5config->comp_name) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_mobile_number">Customer Mobile Number</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="N" <?php
                                                                                                                                                                        echo set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_gst">Company GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="Y" <?php
                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="comp_gst" value="N" <?php
                                                                                                                                                                                echo set_value('comp_gst', $a5config->comp_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_address">Company Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="Y" <?php
                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_address" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_address', $a5config->comp_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="comp_email">Company Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="Y" <?php
                                                                                                                                                                echo set_value('comp_email', $a5config->comp_email) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="comp_email" value="N" <?php
                                                                                                                                                                                        echo set_value('comp_email', $a5config->comp_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>



                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Customer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_name">Customer Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_name" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_name', $a5config->customer_name) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">


                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="N" <?php
                                                                                                                                                                        echo set_value('customer_address', $a5config->customer_address) == 'N' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="barcode">Customer GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">

                                                                                                        <div class="form-group"> <input type="radio" name="customer_gst" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_gst', $a5config->customer_gst) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="shipping_address">Shipping Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="Y" <?php
                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="shipping_address" value="N" <?php
                                                                                                                                                                                        echo set_value('shipping_address', $a5config->shipping_address) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="Y" <?php
                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_email" value="N" <?php
                                                                                                                                                                                        echo set_value('customer_email', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="customer_email">Customer Mobile Number:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="Y" <?php
                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_mobile_number) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="customer_mobile_number" value="N" <?php
                                                                                                                                                                                                echo set_value('customer_mobile_number', $a5config->customer_email) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div> <!-- </div> -->
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Product Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">

                                                                                                <div class="col-md-4">

                                                                                                        <label for="hsn">Product HSN</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="Y" <?php
                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="hsn" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->hsn) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="qty">Product Qty</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->qty) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="qty" value="N" <?php
                                                                                                                                                                                echo set_value('hsn', $a5config->qty) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                                    </div>

                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="sell_price">Product Selling Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="Y" <?php
                                                                                                                                                                echo set_value('qty', $a5config->sell_price) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="sell_price" value="N" <?php
                                                                                                                                                                                        echo set_value('sell_price', $a5config->sell_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="mrp">Product MRP Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="Y" <?php
                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="mrp" value="N" <?php
                                                                                                                                                                                echo set_value('mrp', $a5config->mrp) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="purchase">Product Purchase Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="purchase_price" value="Y" <?php
                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="purchase_price" value="N" <?php
                                                                                                                                                                                        echo set_value('purchase_price', $a5config->purchase_price) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->
                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax%</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="tax" value="Y" <?php
                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="tax" value="N" <?php
                                                                                                                                                                                echo set_value('tax', $a5config->tax) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxable_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxable_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('taxable_amount', $a5config->taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>
                                                                    <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxamount" value="Y" <?php
                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="taxamount" value="N" <?php
                                                                                                                                                                                echo set_value('taxamount', $a5config->taxamount) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div> -->

                                                                                                <!-- <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Product Net Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="net_amount" value="Y" <?php
                                                                                                                                                                echo set_value('net_amount', $a5config->net_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="net_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('net_amount', $a5config->net_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>


                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Total Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_taxable_amount" value="Y" <?php
                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_taxable_amount" value="N" <?php
                                                                                                                                                                                                echo set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">Total Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_tax_amount" value="Y" <?php
                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="total_tax_amount" value="N" <?php
                                                                                                                                                                                        echo set_value('total_tax_amount', $a5config->total_tax_amount) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <!-- 
                                                            </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="tax">GRAND TOTAL</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="grand_total" value="Y" <?php
                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'Y' ? "checked" : "";
                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="grand_total" value="N" <?php
                                                                                                                                                                                        echo set_value('grand_total', $a5config->grand_total) == 'N' ? "checked" : "";
                                                                                                                                                                                        ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">
                                                                                                        <label for="round_off">ROUND OFF</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="Y" <?= set_value('round_off', $a5config->round_off) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="N" <?= set_value('round_off', $a5config->round_off) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="freight_charge">Freight Charge</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="Y" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="N" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="cash_discount">Cash Discount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="Y" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="N" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Bank && Footer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">

                                                                                                        <label for="bank_info">BANK INFO</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="bank_info" value="Y" <?php
                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="bank_info" value="N" <?php
                                                                                                                                                                                echo set_value('bank_info', $a5config->bank_info) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                                <!-- </div>

                                                            <div class="row"> -->
                                                                                                <div class="col-md-4">

                                                                                                        <label for="remark">Remark</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="remark" value="Y" <?php
                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="remark" value="N" <?php
                                                                                                                                                                                echo set_value('remark', $a5config->remark) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">

                                                                                                        <label for="footer">FOOTER</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="footer" value="Y" <?php
                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'Y' ? "checked" : "";
                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group"> <input type="radio" name="footer" value="N" <?php
                                                                                                                                                                                echo set_value('footer', $a5config->footer) == 'N' ? "checked" : "";
                                                                                                                                                                                ?> />
                                                                                                        </div>
                                                                                                </div>

                                                                                        </div>

                                                                                        <!-- </div> -->
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                        </div>
                                                                                        </form>
                                                                                </div>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                        <!-- A5 END -->
                                                </div>
                                                <!-- Estimate Purchase Configuration -->

                                                <!-- EMAIL TEMPLATE START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#email_template">
                                                                Email Config
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="email_template" tabindex="-1" role="dialog" aria-labelledby="emailTemplateModelLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                ?>
                                                                                        <form class="form" id="form" action="<?= base_url() ?>comp_profile/invoice_send_mail" method="post">
                                                                                                <input type="hidden" name="id" value="<?= $comp_profile->id ?>">
                                                                                                <div class="modal-header">
                                                                                                        <h5 class="modal-title text-dark" id="emailTemplateModelLabel">INVOICE SEND ON MAIL</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                        <div class="form-group">
                                                                                                                <label for="subject" class="text-dark">Subject</label>
                                                                                                                <input type="text" name="email_subject" class="form-control" required value="<?= $comp_profile->email_subject ?>">
                                                                                                        </div>
                                                                                                        <div class="form-group">
                                                                                                                <label for="body" class="text-dark">Body</label>
                                                                                                                <textarea id="YourEditor1" class="form-control YourEditor" required name="email_body"><?= $comp_profile->email_body ?></textarea>
                                                                                                                <script type="text/javascript">
                                                                                                                        ClassicEditor.create(document.querySelector('#YourEditor1')).then(editor => {
                                                                                                                                console.log(editor);
                                                                                                                        }).catch(error => {
                                                                                                                                console.error(error);
                                                                                                                        });
                                                                                                                </script>
                                                                                                        </div>
                                                                                                        <div class="row">


                                                                                                                <div class="col-sm-6 mainsmtpDIV" style="display: block;">
                                                                                                                        <div class="form-group ">
                                                                                                                                <div class="col-sm-12">
                                                                                                                                        <label for="smtp_username" class="text-dark">SMTP Username &nbsp;
                                                                                                                                        </label>
                                                                                                                                        <input type="text" class="form-control" id="smtp_username" name="smtp_username" autocomplete="off" value="<?= $comp_profile->smtp_username ?>">
                                                                                                                                        <span class="control-label"></span>
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-sm-6 mainsmtpDIV" style="display: block;">
                                                                                                                        <div class="form-group ">
                                                                                                                                <div class="col-sm-12">
                                                                                                                                        <label for="smtp_password" class="text-dark">SMTP Password &nbsp;
                                                                                                                                        </label>
                                                                                                                                        <input type="password" class="form-control" id="smtp_password" name="smtp_password" autocomplete="off" value="<?= $comp_profile->smtp_password ?>">
                                                                                                                                        <span class="control-label"></span>
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>

                                                                                                        <div class="row">
                                                                                                                <div class="col-sm-6 mainsmtpDIV" style="display: block;">
                                                                                                                        <div class="form-group ">
                                                                                                                                <div class="col-sm-12">
                                                                                                                                        <label for="smtp_server" class="text-dark">SMTP Server &nbsp;
                                                                                                                                        </label>
                                                                                                                                        <input type="text" class="form-control" id="smtp_server" name="smtp_server" value="<?= $comp_profile->smtp_server ?>" spellcheck="false" data-ms-editor="true">
                                                                                                                                        <span class="control-label"></span>
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>

                                                                                                                <div class="col-sm-6 mainsmtpDIV" style="display: block;">
                                                                                                                        <div class="form-group ">
                                                                                                                                <div class="col-sm-12">
                                                                                                                                        <label for="smtp_port" class="text-dark">SMTP Port &nbsp;
                                                                                                                                        </label>
                                                                                                                                        <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?= $comp_profile->smtp_port ?>" spellcheck="false" data-ms-editor="true">
                                                                                                                                        <span class="control-label"></span>
                                                                                                                                </div>
                                                                                                                        </div>
                                                                                                                </div>


                                                                                                        </div>

                                                                                                        <div class="row">

                                                                                                                <div class="col-md-12">

                                                                                                                        <label for="saleTemplate" class="text-dark">SALE SMS</label>
                                                                                                                </div>
                                                                                                                <div class="col-md-12">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="saleTemplate" value="1" <?= set_value('saleTemplate', $comp_profile->saleTemplate) == '1' ? "checked" : ""; ?> />
                                                                                                                                <label for="saleTemplate" class="text-dark">
                                                                                                                                        Dear User, Thank you for Visit with {#var#} Invoice Value Is {#var#} Powered By Umatic
                                                                                                                                </label>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-12">
                                                                                                                        <div class="form-group">
                                                                                                                                <input type="radio" name="saleTemplate" value="2" <?= set_value('saleTemplate', $comp_profile->saleTemplate) == '2' ? "checked" : ""; ?> />
                                                                                                                                <label for="saleTemplate" class="text-dark">
                                                                                                                                        Dear User, Thank you for Visit with {#var#} Invoice Value Is {#var#} Download Inv {#var#} Powered By Umatic
                                                                                                                                </label>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                                </div>
                                                                                        </form>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- EMAIL TEMPLATE END -->

                                                <!-- PAYMENT CONFIG START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#payment">
                                                                <b>Payment Config</b>
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document" style="max-width: 950px;">
                                                                        <div class="modal-content">
                                                                                <?php if (isset($comp_profile) && !empty($comp_profile)) { ?>
                                                                                        <div class="modal-header">
                                                                                                <h5 class="modal-title" id="exampleModalLabel" style="color: #000000;">Payment Config</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                        </div>
                                                                                        <form class="form" id="form" action="<?= base_url(); ?>comp_profile/update_payment" method="POST">
                                                                                                <input type="hidden" name="id" value="<?= $comp_profile->id; ?>" />
                                                                                                <div class="modal-body">
                                                                                                        <div class="row">
                                                                                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="sms_status" class="text-dark">SMS Status</label>
                                                                                                                                <select name="sms_status" id="sms_status" class="form-control">
                                                                                                                                        <option value="Enable" <?= set_value('sms_status', isset($comp_profile->sms_status) ? $comp_profile->sms_status : null) == 'Enable' ? 'selected' : 'selected'; ?>>Enable</option>
                                                                                                                                        <option value="Disable" <?= set_value('sms_status', isset($comp_profile->sms_status) ? $comp_profile->sms_status : null) == 'Disable' ? 'selected' : ''; ?>>Disable</option>
                                                                                                                                </select>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                                <div class="col-md-10 col-sm-12 col-lg-10">
                                                                                                                        <div class="form-group">
                                                                                                                                <label for="saleTemplate2" class="text-dark">Payment SMS</label>
                                                                                                                                <br />
                                                                                                                                <input type="radio" name="saleTemplate2" value="1" <?= set_value('saleTemplate2', isset($comp_profile->saleTemplate2) ? $comp_profile->saleTemplate2 : null) == '1' ? "checked" : ""; ?> />
                                                                                                                                <span class="text-dark" style="padding-left: 10px; position: relative; top: -10px;">
                                                                                                                                        Dear User, Thank you for payment {#var#} of Rs {#var#} Your Bal Is {#var#} Regards Umatic
                                                                                                                                </span>
                                                                                                                        </div>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                        <button type="submit" class="btn btn-primary" style="width: auto;">Update</button>
                                                                                                        <button type="button" class="btn btn-secondary" style="width: auto;" data-dismiss="modal">Close</button>
                                                                                                </div>
                                                                                        </form>
                                                                                <?php } ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- PAYMENT CONFIG END -->

                                                <!-- Order Book Config -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#proforma_config">
                                                                Proforma Config
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="proforma_config" tabindex="-1" role="dialog" aria-labelledby="proforma_config" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content text-dark">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                        $a5config = $this->user_model->getRow('proforma_config', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                }
                                                                                ?>
                                                                                <div id="proforma_config_message"></div>
                                                                                <?= form_open('comp_profile/proforma_config', array('id' => 'proforma_config_form')); ?>
                                                                                <div class="modal-header">
                                                                                        <h5 class="modal-title" id="proforma_config">
                                                                                                Proforma Config
                                                                                        </h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                </div>
                                                                                <div class="modal-body text-dark">
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                                <label for="title">Order Title</label>
                                                                                                                <input type="text" name="title" value="<?= $a5config->title; ?>" class="form-control" />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <div class="form-group">
                                                                                                                <label for="">Field</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="yes">Yes</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <label for="no">No</label>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Company Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_name">Company Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="Y" <?= set_value('comp_name', $a5config->comp_name) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_name" value="N" <?= set_value('comp_name', $a5config->comp_name) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_mobile_number">Customer Mobile Number</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="Y" <?= set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_mobile_number" value="N" <?= set_value('comp_mobile_number', $a5config->comp_mobile_number) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_gst">Company GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="Y" <?= set_value('comp_gst', $a5config->comp_gst) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_gst" value="N" <?= set_value('comp_gst', $a5config->comp_gst) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_address">Company Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="Y" <?= set_value('comp_address', $a5config->comp_address) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_address" value="N" <?= set_value('comp_address', $a5config->comp_address) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="comp_email">Company Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="Y" <?= set_value('comp_email', $a5config->comp_email) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="comp_email" value="N" <?= set_value('comp_email', $a5config->comp_email) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Customer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                        <label for="customer_name">Customer Name:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="Y" <?= set_value('customer_name', $a5config->customer_name) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_name" value="N" <?= set_value('customer_name', $a5config->customer_name) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="barcode">Customer Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="Y" <?= set_value('customer_address', $a5config->customer_address) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_address" value="N" <?= set_value('customer_address', $a5config->customer_address) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="barcode">Customer GST</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="Y" <?= set_value('customer_gst', $a5config->customer_gst) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_gst" value="N" <?= set_value('customer_gst', $a5config->customer_gst) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="shipping_address">Shipping Address</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="Y" <?= set_value('shipping_address', $a5config->shipping_address) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="shipping_address" value="N" <?= set_value('shipping_address', $a5config->shipping_address) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="customer_email">Customer Email</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="Y" <?= set_value('customer_email', $a5config->customer_email) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_email" value="N" <?= set_value('customer_email', $a5config->customer_email) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="customer_email">Customer Mobile Number:</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="Y" <?= set_value('customer_mobile_number', $a5config->customer_mobile_number) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="customer_mobile_number" value="N" <?= set_value('customer_mobile_number', $a5config->customer_email) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Product Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                        <label for="hsn">Product HSN</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="Y" <?= set_value('hsn', $a5config->hsn) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="hsn" value="N" <?= set_value('hsn', $a5config->hsn) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="qty">Product Qty</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="Y" <?= set_value('qty', $a5config->qty) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="qty" value="N" <?= set_value('hsn', $a5config->qty) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="sell_price">Product Selling Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="Y" <?= set_value('qty', $a5config->sell_price) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="sell_price" value="N" <?= set_value('sell_price', $a5config->sell_price) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="mrp">Product MRP Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="Y" <?= set_value('mrp', $a5config->mrp) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="mrp" value="N" <?= set_value('mrp', $a5config->mrp) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="purchase">Product Purchase Price</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="purchase_price" value="Y" <?= set_value('purchase_price', $a5config->purchase_price) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="purchase_price" value="N" <?= set_value('purchase_price', $a5config->purchase_price) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="tax">Product Tax%</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="tax" value="Y" <?= set_value('tax', $a5config->tax) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="tax" value="N" <?= set_value('tax', $a5config->tax) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="tax">Product Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxable_amount" value="Y" <?= set_value('taxable_amount', $a5config->taxable_amount) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxable_amount" value="N" <?= set_value('taxable_amount', $a5config->taxable_amount) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="tax">Product Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxamount" value="Y" <?= set_value('taxamount', $a5config->taxamount) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="taxamount" value="N" <?= set_value('taxamount', $a5config->taxamount) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="tax">Product Net Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="net_amount" value="Y" <?= set_value('net_amount', $a5config->net_amount) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="net_amount" value="N" <?= set_value('net_amount', $a5config->net_amount) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Total Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                        <label for="tax">Total Taxable Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_taxable_amount" value="Y" <?= set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_taxable_amount" value="N" <?= set_value('total_taxable_amount', $a5config->total_taxable_amount) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="tax">Total Tax Amount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_tax_amount" value="Y" <?= set_value('total_tax_amount', $a5config->total_tax_amount) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="total_tax_amount" value="N" <?= set_value('total_tax_amount', $a5config->total_tax_amount) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="tax">GRAND TOTAL</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="grand_total" value="Y" <?= set_value('grand_total', $a5config->grand_total) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="grand_total" value="N" <?= set_value('grand_total', $a5config->grand_total) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="round_off">ROUND OFF</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="Y" <?= set_value('round_off', $a5config->round_off) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="round_off" value="N" <?= set_value('round_off', $a5config->round_off) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="freight_charge">Freight Charge</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="Y" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="freight_charge" value="N" <?= set_radio('freight_charge', $a5config->freight_charge); ?> <?= $a5config->freight_charge == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="cash_discount">Cash Discount</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="Y" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="cash_discount" value="N" <?= set_radio('cash_discount', $a5config->cash_discount); ?> <?= $a5config->cash_discount == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                        <p class="text-center" style="border:1px solid;">Bank && Footer Permission</p>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                        <label for="bank_info">BANK INFO</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="bank_info" value="Y" <?= set_value('bank_info', $a5config->bank_info) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="bank_info" value="N" <?= set_value('bank_info', $a5config->bank_info) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="remark">Remark</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="remark" value="Y" <?= set_value('remark', $a5config->remark) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="remark" value="N" <?= set_value('remark', $a5config->remark) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                        <label for="footer">FOOTER</label>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="footer" value="Y" <?= set_value('footer', $a5config->footer) == 'Y' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                                <div class="col-md-1">
                                                                                                        <div class="form-group">
                                                                                                                <input type="radio" name="footer" value="N" <?= set_value('footer', $a5config->footer) == 'N' ? "checked" : ""; ?> />
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                        <button type="submit" class="btn btn-lg btn-primary">Update </button>
                                                                                </div>
                                                                                <?= form_close(); ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- Order Book Config -->

                                                <!-- SEll CODE START -->
                                                <div class="col-md-2 col-sm-12 col-lg-2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-dark btn-block" data-toggle="modal" data-target="#sell_codes">
                                                                SELL CODE
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="sell_codes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                                <?php
                                                                                if (isset($comp_profile) && !empty($comp_profile)) {
                                                                                        $first = '';
                                                                                        $second = '';
                                                                                        $third = '';
                                                                                        $fourth = '';
                                                                                        $fifth = '';
                                                                                        $sixth = '';
                                                                                        $seventh = '';
                                                                                        $eight = '';
                                                                                        $nine = '';
                                                                                        $zero = '';
                                                                                        $sel_id = 0;
                                                                                ?>
                                                                                        <div id="sell_msg"></div>
                                                                                        <!-- <div id="error_message"></div> -->

                                                                                        <?php echo form_open('comp_profile/sell_barcode_config', array('id' => 'sell_code', 'class' => 'sell_code')) ?>
                                                                                        <!-- <form class="form" id="form" action="<?= base_url() ?>comp_profile/update_tc" method="post"> -->
                                                                                        <!-- <input type="hidden" name="comp_id" value="<?= $comp_profile->id ?>"> -->
                                                                                        <div class="modal-header">
                                                                                                <h5 class="modal-title text-dark" id="exampleModalLabel">Sell CODE GENERATOR</h5>
                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                                <?php
                                                                                                $mrp_code = $this->user_model->getRow('sell_code', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                                                                if ($mrp_code) {
                                                                                                        $first = $mrp_code->first;
                                                                                                        $second = $mrp_code->second;
                                                                                                        $third = $mrp_code->third;
                                                                                                        $fourth = $mrp_code->fourth;
                                                                                                        $fifth = $mrp_code->fifth;
                                                                                                        $seventh = $mrp_code->seventh;
                                                                                                        $sixth = $mrp_code->sixth;
                                                                                                        $eight = $mrp_code->eight;
                                                                                                        $nine = $mrp_code->nine;
                                                                                                        $zero = $mrp_code->zero;
                                                                                                        $sel_id = $mrp_code->id;
                                                                                                }
                                                                                                ?>
                                                                                                <table class="table table-striped text-dark">

                                                                                                        </style>
                                                                                                        <tr>
                                                                                                                <input type="hidden" value="<?= $sel_id ?>" name="sell_id" id="sell_id">
                                                                                                                <th>0</th>
                                                                                                                <td><input class="form-control" type="text" value="<?= $zero ?>" tabindex="1" name="zero" id="zero" autofocus></td>
                                                                                                                <th>1</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $first ?>" tabindex="2" name="first" id="first"></td>

                                                                                                        </tr>


                                                                                                        <tr>
                                                                                                                <th>2</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $second ?>" tabindex="3" name="second" id="second"></td>
                                                                                                                <th>3</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $third ?>" tabindex="4" name="third" id="third"></td>


                                                                                                        </tr>

                                                                                                        <!-- <tr>


                                                                                                            </tr> -->

                                                                                                        <tr>
                                                                                                                <th>4</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $fourth ?>" tabindex="5" name="fourth" id="fourth"></td>



                                                                                                                <th>5</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $fifth ?>" tabindex="6" name="fifth" id="fifth"></td>


                                                                                                        </tr>

                                                                                                        <tr>
                                                                                                                <th>6</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $sixth ?>" tabindex="7" name="sixth" id="sixth"></td>



                                                                                                                <th>7</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $seventh ?>" tabindex="8" name="seventh" id="seventh"></td>


                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                                <th>8</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $eight ?>" tabindex="9" name="eight" id="eight"></td>



                                                                                                                <th>9</th>
                                                                                                                <td><input type="text" class="form-control" value="<?= $nine ?>" tabindex="10" name="nine" id="nine"></td>


                                                                                                        </tr>

                                                                                                </table>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-lg btn-primary" tabindex="11">Update </button>
                                                                                        </div>
                                                                                        </form>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <!-- SELL CODE END -->

                                                <!-- SHow Data -->
                                                <div class="col-md-12 col-xl-12 col-sm-12">
                                                        <div class="card card-outline card-maroon">
                                                                <div class="card-header">
                                                                        <div class="card-title">
                                                                                <h5 class="text-maroon">
                                                                                        <?= $Type; ?> Profile
                                                                                </h5>
                                                                        </div>
                                                                </div>
                                                                <div class="card-body">
                                                                        <div class="table-responsive">
                                                                                <table class="table table-bordered">
                                                                                        <thead class="bg-maroon">
                                                                                                <tr>
                                                                                                        <th>#</th>
                                                                                                        <th><?= $Type; ?> Name</th>
                                                                                                        <th>Bank Details</th>
                                                                                                        <th>Content</th>
                                                                                                </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                                <?php if (isset($comp_profile) && !empty($comp_profile)) { ?>
                                                                                                        <tr>
                                                                                                                <td><span><?= 1; ?></span></td>
                                                                                                                <td><?= $comp_profile->name; ?></td>
                                                                                                                <td><?= $comp_profile->bank_details; ?></td>
                                                                                                                <td><?= $comp_profile->content; ?></td>
                                                                                                        </tr>
                                                                                                <?php } ?>
                                                                                        </tbody>
                                                                                </table>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
</div>

<script>
        $(document).ready(function() {
                hideGstEwayEinvoiceDetails();
        });

        function hideGstEwayEinvoiceDetails() {
                let text_details = '<label for="address" class="text-dark" style="cursor:pointer" onclick="viewGstEwayEinvoiceDetails()" >Gst Details  <i class="fa fa-eye" style="font-size:20px"></i></label>';
                $("#eway_einvoice_details").html(text_details);
                $("#gst_eway_einvoice_details").hide();
        }

        function viewGstEwayEinvoiceDetails() {
                let text_details = '<label for="address" class="text-dark" style="cursor:pointer" onclick="hideGstEwayEinvoiceDetails()" >Gst Details  <i class="fa fa-eye-slash" style="font-size:20px"></i></label>';
                $("#eway_einvoice_details").html(text_details);
                $("#gst_eway_einvoice_details").show();
        }

        function checkGstDetails() {
                var gst = $("#gst").val();
                console.log(gst, 'gst func');
                if (gst != '') {
                        $(".loader").show();
                        company_gst_details(gst);
                        //alert(gst);  
                } else {

                        alert("Please Enter Gst Number");

                }
        }

        const getPartyCity = (PartyCity) => {
                if (PartyCity == "" || PartyCity == null) {
                        $('#party_city_list').html('');
                        return;
                }

                $.ajax({
                        method: "POST",
                        url: "<?= base_url(); ?>party/getPartyCity",
                        data: {
                                PartyCity: PartyCity
                        },
                        success: function(Response) {
                                if (Response) {
                                        var PartyData = JSON.parse(Response);
                                        var partyopt = `<ul class="search-ul">`;
                                        PartyData.forEach((element) => {
                                                partyopt += `<li class="search-list" onclick="onSelectgetPartyCity('${element.cname}');">${element.cname}</li>`;
                                        });
                                        partyopt += `</ul>`;
                                        $('#party_city_list').html(partyopt);
                                        $('#city').focusout(function() {
                                                window.setTimeout(function() {
                                                        $('#party_city_list').html('');
                                                }, 1000);
                                        });
                                } else {
                                        $('#party_city_list').html('');
                                }
                        }
                });
        };

        const onSelectgetPartyCity = (PartyCity) => {
                $('#city').val(PartyCity);
                window.setTimeout(function() {
                        $('#party_city_list').html('');
                }, 1000);
        };
</script>
<?php require_once('script.php'); ?>