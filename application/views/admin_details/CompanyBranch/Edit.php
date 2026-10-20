<style type="text/css">
        .form-group {
                margin-bottom: 0rem;
        }
</style>

<div class="content-wrapper">
        <div class="content-header mt-5">
                <div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if ($this->session->flashdata('msg')) { ?>
                                        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                                            <?= $this->session->flashdata('msg'); ?>
                                        </div>
                                        <?php } ?>                        
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if (!empty($BranchId)) { ?>
                                        <form action="<?= base_url(); ?>CompanyBranch/Update/<?= $BranchId; ?>" method="POST" autocomplete="off">
                                                <input type="hidden" name="BranchId" value="<?= set_value('BranchId', $CompanyBranch->Id); ?>" />
                                                <div class="card card-outline card-maroon">
                                                        <div class="card-header">
                                                                <div class="card-title">
                                                                        <h5 class="card-title text-maroon">
                                                                                Edit Company Branch
                                                                        </h5>
                                                                </div>
                                                                <a href="<?= base_url(); ?>CompanyBranch" class="btn btn-xs bg-maroon pull-right" title="CompanyBranch Report">
                                                                        <i class="fa fa-angle-double-left text-white"></i> Back
                                                                </a>
                                                        </div>
                                                        <div class="card-body">
                                                                <div class="row">
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="CompId">Companies <span class="text-danger">*</span></label>
                                                                                <select name="CompId" id="CompId" class="form-control form-control-sm" required="" title="Select Company">
                                                                                        <option value="" <?= $CompanyBranch->CompId == "" ? "selected" : ""; ?> disabled="">Company</option>
                                                                                        <?php if ($Companies) { foreach ($Companies as $key => $Data) { ?>
                                                                                        <option value="<?= $Data->id; ?>" <?= $CompanyBranch->CompId == $Data->id ? "selected" : ""; ?> <?= set_select('CompId', $Data->id); ?>><?= $Data->company_name; ?></option>
                                                                                        <?php } } ?>
                                                                                </select>
                                                                                <span class="text-danger"><?= form_error('CompId'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="BranchUserId">Branch User <span class="text-danger">*</span></label>
                                                                                <select name="BranchUserId" id="BranchUserId" class="form-control form-control-sm" required="" title="Select Branch User">
                                                                                        <option value="" <?= $CompanyBranch->BranchUserId == "" ? "selected" : ""; ?> disabled="">Branch User</option>
                                                                                        <?php if ($Users) { foreach ($Users as $key => $User) { ?>
                                                                                        <option value="<?= $User->id; ?>" <?= $CompanyBranch->BranchUserId == $User->id ? "selected" : ""; ?> <?= set_select('UserId', $User->id); ?>><?= $User->name; ?></option>
                                                                                        <?php } } ?>
                                                                                </select>
                                                                                <span class="text-danger"><?= form_error('UserId'); ?></span>
                                                                        </div>
                                                                </div>
                                
                                                                <hr class="bg-maroon" />
                                                                <div class="row">
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="BranchName">Branch Name <span class="text-danger">*</span></label>
                                                                                <input type="text" name="BranchName" value="<?= set_value('BranchName', $CompanyBranch->BranchName); ?>" id="BranchName" class="form-control form-control-sm" placeholder="Branch Name" required="" />
                                                                                <span class="text-danger"><?= form_error('BranchName'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="Mobile">Mobile <span class="text-danger">*</span></label>
                                                                                <input type="tel" name="Mobile" value="<?= set_value('Mobile', $CompanyBranch->Mobile); ?>" id="Mobile" class="form-control form-control-sm" placeholder="Mobile" minlength="10" maxlength="10" required="" />
                                                                                <span class="text-danger"><?= form_error('Mobile'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="Email">Email <span class="text-danger">*</span></label>
                                                                                <input type="email" name="Email" value="<?= set_value('Email', $CompanyBranch->Email); ?>" id="Email" class="form-control form-control-sm" placeholder="Email" required="" />
                                                                                <span class="text-danger"><?= form_error('Email'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="GST">GST</label>
                                                                                <input type="text" name="GST" value="<?= set_value('GST', $CompanyBranch->GST); ?>" id="GST" class="form-control form-control-sm" placeholder="GST Number" minlength="15" maxlength="15" />
                                                                                <span class="text-danger"><?= form_error('GST'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="ContactPerson">Contact Person</label>
                                                                                <input type="text" name="ContactPerson" value="<?= set_value('ContactPerson', $CompanyBranch->ContactPerson); ?>" id="ContactPerson" class="form-control form-control-sm" placeholder="Contact Person" />
                                                                                <span class="text-danger"><?= form_error('ContactPerson'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="State">State Name <span class="text-danger">*</span></label>
                                                                                <select name="State" id="State" class="form-control form-control-sm" required="" title="Select State">
                                                                                        <option value="" <?= $CompanyBranch->State == "" ? "selected" : ""; ?> disabled="">State</option>
                                                                                        <?php if ($States) { foreach ($States as $key => $Row) { ?>
                                                                                        <option value="<?= $Row->id; ?>" <?= $CompanyBranch->State == $Row->id ? "selected" : ""; ?> <?= set_select('State', $Row->id); ?>><?= $Row->state; ?></option>
                                                                                        <?php } } ?>
                                                                                </select>
                                                                                <span class="text-danger"><?= form_error('State'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-6 form-group">
                                                                                <label for="Address">Address</label>
                                                                                <textarea name="Address" id="Address" class="form-control form-control-sm" placeholder="Address" rows="1"><?= set_value('Address', $CompanyBranch->Address); ?></textarea>
                                                                        </div>
                                                                </div>
                                                                
                                                                <hr class="bg-maroon" />
                                                                <div class="row">
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="FinancialYear">Financial Year <span class="text-danger">*</span></label>
                                                                                <select name="FinancialYear" id="FinancialYear" class="form-control form-control-sm" required="" title="Select Financial Year">
                                                                                        <option value="" <?= $CompanyBranch->FinancialYear == "" ? "selected" : ""; ?> disabled="">Financial Year</option>
                                                                                        <?php if ($FinancialYears) { foreach ($FinancialYears as $key => $Year) { ?>
                                                                                        <option value="<?= $Year->id; ?>" <?= $CompanyBranch->FinancialYear == $Year->id ? "selected" : ""; ?> <?= set_select('FinancialYear', $Year->id); ?>><?= $Year->year; ?></option>
                                                                                        <?php } } ?>
                                                                                </select>
                                                                                <span class="text-danger"><?= form_error('FinancialYear'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="BillingFormat">Billing Format <span class="text-danger">*</span></label>
                                                                                <select name="BillingFormat" id="BillingFormat" class="form-control form-control-sm" required="" title="Select Billing Format">
                                                                                        <option value="" <?= $CompanyBranch->BillingFormat == "" ? "selected" : ""; ?> disabled="">Billing Format</option>
                                                                                        <?php if ($BillingFormat) { foreach ($BillingFormat as $key => $Value) { ?>
                                                                                        <option value="<?= $Value->id; ?>" <?= $CompanyBranch->BillingFormat == $Value->id ? "selected" : ""; ?> <?= set_select('BillingFormat', $Value->id); ?>><?= $Value->name; ?></option>
                                                                                        <?php } } ?>
                                                                                </select>
                                                                                <span class="text-danger"><?= form_error('BillingFormat'); ?></span>
                                                                        </div>
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="AccountingType">Accounting Type <span class="text-danger">*</span></label>
                                                                                <select name="AccountingType" id="AccountingType" class="form-control form-control-sm" required="" title="Select Accounting Type">
                                                                                        <option value="" <?= $CompanyBranch->AccountingType == "" ? "selected" : ""; ?> disabled="">Accounting Type</option>
                                                                                        <option value="1" <?= $CompanyBranch->AccountingType == 1 ? "selected" : ""; ?> <?= set_select('AccountingType', 1); ?>>Basic</option>
                                                                                        <option value="2" <?= $CompanyBranch->AccountingType == 2 ? "selected" : ""; ?> <?= set_select('AccountingType', 2); ?>>Advanced</option>
                                                                                </select>
                                                                                <span class="text-danger"><?= form_error('AccountingType'); ?></span>
                                                                        </div>
                                                                </div>
                                                                
                                                                <hr class="bg-maroon" />
                                                                <div class="row">
                                                                        <div class="col-md-3 form-group">
                                                                                <label for="branchDivisionYes">Branch Division</label>
                                                                                <br />
                                                                                <input type="radio" name="branchDivision" value="Y" id="branchDivisionYes" <?= set_radio('branchDivision', 'Y'); ?> onclick="getCompanyBranches();" <?= $CompanyBranch->branchDivision == 'Y' ? "checked" : ""; ?> required="" />
                                                                                Yes
                                                                                <input type="radio" name="branchDivision" value="N" id="branchDivisionNo" class="ml-5" <?= set_radio('branchDivision', 'N'); ?> onclick="getCompanyBranches();" <?= $CompanyBranch->branchDivision == 'N' ? "checked" : ""; ?> required="" />
                                                                                No
                                                                        </div>
                                                                        <div class="col-md-3 form-group" id="branchDivision" style="display: <?= $CompanyBranch->branchDivision == 'Y' ? "block" : "none"; ?>;">
                                                                                <label for="mainBranchId">Select Branch (Main Branch)</label>
                                                                                <select name="mainBranchId" id="mainBranchId" class="form-control form-control-sm" title="Select Branch" style="width: 100%;">
                                                                                </select>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <div class="card-footer">
                                                                <div class="row">
                                                                        <div class="col-md-3 form-group">
                                                                                <button type="submit" class="btn btn-sm bg-maroon">
                                                                                        <i class="fa fa-save text-white"></i> Make Changes
                                                                                </button>
                                                                                <a href="<?= base_url(); ?>CompanyBranch" class="btn btn-danger btn-sm">
                                                                                        <i class="fa fa-times text-white"></i> Cancel
                                                                                </a>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </form>                            
                                        <?php } ?>
                                </div>
                        </div>
                </div>
        </div>
</div>

<script type="text/javascript">
        $(document).ready(function() {
                $('#BranchUserId').select2();
                
                $('#State').select2();
                
                getCompanyBranches();
                
                $('#mainBranchId').select2();
        });
        
        const getCompanyBranches = () => {
                if ($("#branchDivisionYes").is(':checked')) {
                        var compId = $("#CompId").val();
                        var mainBranchId = "<?= $CompanyBranch->mainBranchId; ?>"
                        if (compId != "" && compId != null) {
                                $.ajax({
                                        type    :   "POST",
                                        url     :   "<?= base_url(); ?>companyBranch/getCompanyBranches",
                                        data    :   {compId: compId},
                                        success :   function (Response) {
                                                                if (Response) {
                                                                        $("#branchDivision").css('display', 'block');
                                                                        $("#mainBranchId").html(Response);
                                                                        $("#mainBranchId option[value='" + mainBranchId + "']").prop('selected', true);
                                                                        $("#mainBranchId").prop('required', true);
                                                                } else {
                                                                        alert('No branch available in company');
                                                                        $("#mainBranchId").prop('required', false);
                                                                        $("#branchDivision").css('display', 'none');
                                                                }
                                                        }
                                });
                        } else {
                                $("#branchDivisionYes").prop('checked', false);
                                alert('Please select frist company');
                        }
                } else {
                        $("#branchDivision").css('display', 'none');
                        $("#mainBranchId").prop('required', false);
                }
        };
</script>