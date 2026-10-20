<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <?php if (!empty($EmpId)) { ?>
                <form action="<?= base_url(); ?>Employee/Update/<?= $EmpId; ?>" enctype="multipart/form-data" method="POST" autocomplete="off">
                    <input type="hidden" name="EmpId" value="<?= set_value('EmpId', $Employee->Id); ?>" />
                    <div class="card card-outline card-maroon">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="card-title text-maroon">
                                    Edit Employee
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee" class="btn btn-xs bg-maroon pull-right" title="Employee Report">
                                <i class="fa fa-angle-double-left text-white"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <legend class="text-maroon" style="font-size: 15px;">Personal</legend>
                                <hr class="bg-maroon" />
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="EmployeeId">Employee Id <span class="text-danger">*</span></label>
                                        <input type="text" name="EmployeeId" value="<?= set_value('EmployeeId', $Employee->EmployeeId); ?>" id="EmployeeId" class="form-control form-control-sm" placeholder="Employee Id" required="" readonly="" />
                                        <span class="text-danger"><?= form_error('EmployeeId'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="Name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="Name" value="<?= set_value('Name', $Employee->Name); ?>" id="Name" class="form-control form-control-sm" placeholder="Employee Name" required="" />
                                        <span class="text-danger"><?= form_error('Name'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="Mobile">Mobile <span class="text-danger">*</span></label>
                                        <input type="tel" name="Mobile" value="<?= set_value('Mobile', $Employee->Mobile); ?>" id="Mobile" class="form-control form-control-sm" placeholder="Mobile" minlength="10" maxlength="10" required="" />
                                        <span class="text-danger"><?= form_error('Mobile'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="Email">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="Email" value="<?= set_value('Email', $Employee->Email); ?>" id="Email" class="form-control form-control-sm" placeholder="Email" required="" />
                                        <span class="text-danger"><?= form_error('Email'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="Gender">Gender <span class="text-danger">*</span></label>
                                        <select name="Gender" id="Gender" class="form-control form-control-sm" title="Gender">
                                            <option value="" <?= $Employee->Gender == "" ? "selected" : ""; ?> disabled="">Select Gender</option>
                                            <option value="Male" <?= $Employee->Gender == "Male" ? "selected" : ""; ?> <?= set_select("Gender", "Male"); ?>>Male</option>
                                            <option value="Female" <?= $Employee->Gender == "Female" ? "selected" : ""; ?> <?= set_select("Gender", "Female"); ?>>Female</option>
                                            <option value="Other" <?= $Employee->Gender == "Other" ? "selected" : ""; ?> <?= set_select("Gender", "Other"); ?>>Other</option>
                                        </select>
                                        <span class="text-danger"><?= form_error('Gender'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="DateOfBirth">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" name="DateOfBirth" value="<?= set_value('DateOfBirth', $Employee->DateOfBirth); ?>" id="DateOfBirth" class="form-control form-control-sm" title="Date of Birth" />
                                        <span class="text-danger"><?= form_error('DateOfBirth'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="BloodGroup">Blood Group</label>
                                        <input type="text" name="BloodGroup" value="<?= set_value('BloodGroup', $Employee->BloodGroup); ?>" id="BloodGroup" class="form-control form-control-sm" placeholder="Blood Group" />
                                        <span class="text-danger"><?= form_error('BloodGroup'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="EmergencyNumber">Emergency Number</label>
                                        <input type="tel" name="EmergencyNumber" value="<?= set_value('EmergencyNumber', $Employee->EmergencyNumber); ?>" id="EmergencyNumber" class="form-control form-control-sm" placeholder="Emergency Number" minlength="10" maxlength="10" />
                                        <span class="text-danger"><?= form_error('EmergencyNumber'); ?></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="TemporaryAddress">Temporary Address</label>
                                        <textarea name="TemporaryAddress" id="TemporaryAddress" class="form-control form-control-sm" placeholder="Temporary Address" rows="1"><?= set_value('TemporaryAddress', $Employee->TemporaryAddress); ?></textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="PermanentAddress">Permanent Address</label>
                                        <textarea name="PermanentAddress" id="PermanentAddress" class="form-control form-control-sm" placeholder="Permanent Address" rows="1"><?= set_value('PermanentAddress', $Employee->PermanentAddress); ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="FatherName">Father Name</label>
                                        <input type="text" name="FatherName" value="<?= set_value('FatherName', $Employee->FatherName); ?>" id="FatherName" class="form-control form-control-sm" placeholder="Father Name" />
                                        <span class="text-danger"><?= form_error('FatherName'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="FatherMobile">Father Mobile</label>
                                        <input type="tel" name="FatherMobile" value="<?= set_value('FatherMobile', $Employee->FatherMobile); ?>" id="FatherMobile" class="form-control form-control-sm" placeholder="Father Mobile" minlength="10" maxlength="10" />
                                        <span class="text-danger"><?= form_error('FatherMobile'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="MotherName">Mother Name</label>
                                        <input type="text" name="MotherName" value="<?= set_value('MotherName', $Employee->MotherName); ?>" id="MotherName" class="form-control form-control-sm" placeholder="Mother Name" />
                                        <span class="text-danger"><?= form_error('MotherName'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="MotherMobile">Mother Mobile</label>
                                        <input type="tel" name="MotherMobile" value="<?= set_value('MotherMobile', $Employee->MotherMobile); ?>" id="MotherMobile" class="form-control form-control-sm" placeholder="Mother Mobile" minlength="10" maxlength="10" />
                                        <span class="text-danger"><?= form_error('MotherMobile'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <legend class="text-maroon mt-3" style="font-size: 15px;">Official</legend>
                            <hr class="bg-maroon" />
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="RoleId">Employee Post <span class="text-danger">*</span></label>
                                        <select name="RoleId" id="RoleId" class="form-control form-control-sm" title="Employee Post" required="">
                                            <option value="" <?= $Employee->RoleId == "" ? "selected" : ""; ?> disabled="">Employee Post</option>
                                            <?php if ($Positions) { foreach ($Positions as $key => $Post) { ?>
                                            <option value="<?= $Post->Id; ?>" <?= $Employee->RoleId == $Post->Id ? "selected" : ""; ?> <?= set_select("RoleId", $Post->Id); ?>><?= $Post->PostName; ?></option>
                                            <?php } } ?>
                                        </select>
                                        <span class="text-danger"><?= form_error('RoleId'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="WarehouseId">Warehouse <span class="text-danger">*</span></label>
                                        <select name="WarehouseId" id="WarehouseId" class="form-control form-control-sm" title="Warehouse" required="" onchange="getWarehouseSalesman(this.value);">
                                            <option <?= $Employee->WarehouseId == "" ? "selected" : ""; ?> disabled="" value="">Select Warehouse</option>
                                            <?php if (!empty($Warehouses)) { foreach ($Warehouses as $key => $Warehouse) { ?>
                                            <option value="<?= $Warehouse->id; ?>" <?= $Employee->WarehouseId == $Warehouse->id ? "selected" : ""; ?> <?= set_select('WarehouseId', $Warehouse->id); ?>><?= $Warehouse->name . " - " . $Warehouse->location; ?></option>
                                            <?php } } ?>
                                        </select>
                                        <span class="text-danger"><?= form_error('WarehouseId'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="WarehouseSalesman">Warehouse Salesman</label>
                                        <select name="WarehouseSalesman[]" id="WarehouseSalesman" class="form-control form-control-sm" title="Warehouse Salesman" placeholder="Selected Warehouse Salesman" multiple="">
                                            <?php 
                                                $WarehouseSalesman = json_decode($Employee->WarehouseSalesman ?? '', true) ?: [];
                                                if (!empty($WarehouseSalesman)) {
                                                    foreach ($WarehouseSalesman as $key => $Salesman) {
                                                        $SalesmanName = get_title('employees', ['Id' => $Salesman], 'Name');
                                            ?>
                                            <option selected="" value="<?= $Salesman; ?>"><?= $SalesmanName; ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="AadhaarNumber">Aadhaar Number</label>
                                        <input type="tel" name="AadhaarNumber" value="<?= set_value('AadhaarNumber', $Employee->AadhaarNumber); ?>" id="AadhaarNumber" class="form-control form-control-sm" placeholder="Aadhaar Number" minlength="12" maxlength="12" />
                                        <span class="text-danger"><?= form_error('AadhaarNumber'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="PANNumber">PAN Number</label>
                                        <input type="tel" name="PANNumber" value="<?= set_value('PANNumber', $Employee->PANNumber); ?>" id="PANNumber" class="form-control form-control-sm" placeholder="PAN Number" minlength="10" maxlength="10" />
                                        <span class="text-danger"><?= form_error('PANNumber'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="UANNumber">UAN Number</label>
                                        <input type="tel" name="UANNumber" value="<?= set_value('UANNumber', $Employee->UANNumber); ?>" id="UANNumber" class="form-control form-control-sm" placeholder="UAN Number" minlength="12" maxlength="12" />
                                        <span class="text-danger"><?= form_error('UANNumber'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="ESINumber">ESI Number</label>
                                        <input type="tel" name="ESINumber" value="<?= set_value('ESINumber', $Employee->ESINumber); ?>" id="ESINumber" class="form-control form-control-sm" placeholder="ESI Number" minlength="17" maxlength="17" />
                                        <span class="text-danger"><?= form_error('ESINumber'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="JoiningDate">Joining Date</label>
                                        <input type="date" name="JoiningDate" value="<?= set_value('JoiningDate', $Employee->JoiningDate); ?>" id="JoiningDate" class="form-control form-control-sm" title="Joining Date" />
                                        <span class="text-danger"><?= form_error('JoiningDate'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="ExpiryDate">Expiry Date</label>
                                        <input type="date" name="ExpiryDate" value="<?= set_value('ExpiryDate', $Employee->ExpiryDate); ?>" id="ExpiryDate" class="form-control form-control-sm" title="Expiry Date" />
                                        <span class="text-danger"><?= form_error('ExpiryDate'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="Password">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="Password" value="<?= set_value('Password', $Employee->Password); ?>" id="Password" class="form-control form-control-sm" placeholder="Password" minlength="6" maxlength="20" />
                                        <div class="password-container">
                                            <i class="fa fa-eye password-toggle-btn" onclick="togglePasswordVisibility()"></i>
                                        </div>
                                        <span class="text-danger"><?= form_error('Password'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="IPAddress">IP Address</label>
                                        <input type="text" name="IPAddress" value="<?= set_value('IPAddress', $Employee->IPAddress); ?>" id="IPAddress" class="form-control form-control-sm" placeholder="IP Address" />
                                        <span class="text-danger"><?= form_error('IPAddress'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="SalesTarget">Sales Target</label>
                                        <input type="text" name="SalesTarget" value="<?= set_value('SalesTarget', $Employee->SalesTarget); ?>" id="SalesTarget" class="form-control form-control-sm" placeholder="Sales Target" />
                                    </div>
                                </div>
                                
                                <legend class="text-maroon mt-3" style="font-size: 15px;">Bank Account</legend>
                                <hr class="bg-maroon" />
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="BankName">Bank Name</label>
                                        <input type="text" name="BankName" value="<?= set_value('BankName', $Employee->BankName); ?>" id="BankName" class="form-control form-control-sm" placeholder="Bank Name" />
                                        <span class="text-danger"><?= form_error('BankName'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="BranchName">Branch Name</label>
                                        <input type="text" name="BranchName" value="<?= set_value('BranchName', $Employee->BranchName); ?>" id="BranchName" class="form-control form-control-sm" placeholder="Branch Name" />
                                        <span class="text-danger"><?= form_error('BranchName'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="IFSCCode">IFSC Code</label>
                                        <input type="text" name="IFSCCode" value="<?= set_value('IFSCCode', $Employee->IFSCCode); ?>" id="IFSCCode" class="form-control form-control-sm text-uppercase" placeholder="IFSC Code" minlength="11" maxlength="11" />
                                        <span class="text-danger"><?= form_error('IFSCCode'); ?></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="AccountNumber">Account Number</label>
                                        <input type="text" name="AccountNumber" value="<?= set_value('AccountNumber', $Employee->AccountNumber); ?>" id="AccountNumber" class="form-control form-control-sm" placeholder="Account Number" minlength="1" maxlength="20" />
                                        <span class="text-danger"><?= form_error('AccountNumber'); ?></span>
                                    </div>
                                </div>
                                
                                <legend class="text-maroon mt-3" style="font-size: 15px;">Documents</legend>
                                <hr class="bg-maroon" />
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="Photograph">Photograph</label>
                                        <input type="file" name="Photograph" id="Photograph" class="form-control form-control-sm" title="Photograph" accept="image/*" />
                                        <input type="hidden" name="OldPhotograph" value="<?= set_value('OldPhotograph', $Employee->Photograph); ?>" id="OldPhotograph" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="IdentityProof">Identity Proof</label>
                                        <input type="file" name="IdentityProof" id="IdentityProof" class="form-control form-control-sm" title="Identity Proof" accept="image/*" />
                                        <input type="hidden" name="OldIdentityProof" value="<?= set_value('OldIdentityProof', $Employee->IdentityProof); ?>" id="OldIdentityProof" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="AddressProof">Address Proof</label>
                                        <input type="file" name="AddressProof" id="AddressProof" class="form-control form-control-sm" title="Address Proof" accept="image/*" />
                                        <input type="hidden" name="OldAddressProof" value="<?= set_value('OldAddressProof', $Employee->AddressProof); ?>" id="OldAddressProof" />
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="BankPassbook">Bank Passbook</label>
                                        <input type="file" name="BankPassbook" id="BankPassbook" class="form-control form-control-sm" title="Bank Passbook" accept="image/*" />
                                        <input type="hidden" name="OldBankPassbook" value="<?= set_value('OldBankPassbook', $Employee->BankPassbook); ?>" id="OldBankPassbook" />
                                    </div>
                                </div>
                                    
                                <legend class="text-maroon mt-3" style="font-size: 15px;">Salary</legend>
                                <hr class="bg-maroon" />
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xxl-12">
                                        <div class="table table-responsive">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead class="bg-maroon">
                                                    <tr>
                                                        <th>Categories</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <label for="Basic">Basic <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="Basic" value="<?= set_value('Basic', $Employee->Basic ? $Employee->Basic : 0); ?>" id="Basic" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Basic" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('Basic'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="HRA">HRA <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="HRA" value="<?= set_value('HRA', $Employee->HRA ? $Employee->HRA : 0); ?>" id="HRA" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="House Rent Allowance" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('HRA'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="ConveyanceAllowance">Conveyance Allowance <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="text" name="ConveyanceAllowance" value="<?= set_value('ConveyanceAllowance', $Employee->ConveyanceAllowance ? $Employee->ConveyanceAllowance : 0); ?>" id="ConveyanceAllowance" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Conveyance Allowance" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('ConveyanceAllowance'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="StaffSpecialAllowance">Staff Special Allowance <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="StaffSpecialAllowance" value="<?= set_value('StaffSpecialAllowance', $Employee->StaffSpecialAllowance ? $Employee->StaffSpecialAllowance : 0); ?>" id="StaffSpecialAllowance" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Staff Special Allowance" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('ConveyanceAllowance'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="GrossMonthlyTotal">Gross Monthly Total <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="GrossMonthlyTotal" value="<?= set_value('GrossMonthlyTotal', $Employee->GrossMonthlyTotal ? $Employee->GrossMonthlyTotal : 0); ?>" id="GrossMonthlyTotal" class="form-control form-control-sm" placeholder="Gross Monthly Total" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('GrossMonthlyTotal'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="EmployeePF">Employee PF <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="EmployeePF" value="<?= set_value('EmployeePF', $Employee->EmployeePF ? $Employee->EmployeePF : 0); ?>" id="EmployeePF" class="form-control form-control-sm" placeholder="Employee Provident Fund" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('EmployeePF'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="EmployeeESI">Employee ESI <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="EmployeeESI" value="<?= set_value('EmployeeESI', $Employee->EmployeeESI ? $Employee->EmployeeESI : 0); ?>" id="EmployeeESI" class="form-control form-control-sm" placeholder="Employee State Insurance" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('EmployeeESI'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="ProfessionTax">Profession Tax <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="ProfessionTax" value="<?= set_value('ProfessionTax', $Employee->ProfessionTax ? $Employee->ProfessionTax : 0); ?>" id="ProfessionTax" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Profession Tax" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('ProfessionTax'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="IncomeTax">Income Tax <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="IncomeTax" value="<?= set_value('IncomeTax', $Employee->IncomeTax ? $Employee->IncomeTax : 0); ?>" id="IncomeTax" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Income Tax" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('IncomeTax'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="TDS">
                                                                Tax Deducted at Source <span class="text-danger">*</span>
                                                                <small class="text-maroon">in <i class="fa fa-percent"></i></small>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <select name="TDS" id="TDS" class="form-control form-control-sm" onchange="SalaryCalculate();" title="Tax Deducted at Source" required="">
                                                                    <option value="0" <?= $Employee->TDS == 0 ? "selected" : ""; ?> <?= set_select('TDS', 0); ?>>0</option>
                                                                    <option value="1" <?= $Employee->TDS == 1 ? "selected" : ""; ?> <?= set_select('TDS', 1); ?>>1</option>
                                                                    <option value="2" <?= $Employee->TDS == 2 ? "selected" : ""; ?> <?= set_select('TDS', 2); ?>>2</option>
                                                                    <option value="3" <?= $Employee->TDS == 3 ? "selected" : ""; ?> <?= set_select('TDS', 3); ?>>3</option>
                                                                    <option value="4" <?= $Employee->TDS == 4 ? "selected" : ""; ?> <?= set_select('TDS', 4); ?>>4</option>
                                                                    <option value="5" <?= $Employee->TDS == 5 ? "selected" : ""; ?> <?= set_select('TDS', 5); ?>>5</option>
                                                                    <option value="6" <?= $Employee->TDS == 6 ? "selected" : ""; ?> <?= set_select('TDS', 6); ?>>6</option>
                                                                    <option value="7" <?= $Employee->TDS == 7 ? "selected" : ""; ?> <?= set_select('TDS', 7); ?>>7</option>
                                                                    <option value="8" <?= $Employee->TDS == 8 ? "selected" : ""; ?> <?= set_select('TDS', 8); ?>>8</option>
                                                                    <option value="9" <?= $Employee->TDS == 9 ? "selected" : ""; ?> <?= set_select('TDS', 9); ?>>9</option>
                                                                    <option value="10" <?= $Employee->TDS == 10 ? "selected" : ""; ?> <?= set_select('TDS', 10); ?>>10</option>
                                                                </select>
                                                                <span class="text-danger"><?= form_error('TDS'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="TDSAmount">TDS Amount <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="TDSAmount" value="<?= set_value('TDSAmount', $Employee->TDSAmount ? $Employee->TDSAmount : 0); ?>" id="TDSAmount" class="form-control form-control-sm" placeholder="TDS Amount" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('TDSAmount'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="MonthlyDeduction" class="text-danger">Monthly Deduction <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="MonthlyDeduction" value="<?= set_value('MonthlyDeduction', $Employee->MonthlyDeduction ? $Employee->MonthlyDeduction : 0); ?>" id="MonthlyDeduction" class="form-control form-control-sm" placeholder="Monthly Deduction" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('MonthlyDeduction'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="MonthlyNetTakeHome" class="text-success">Monthly Net Take Home <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="MonthlyNetTakeHome" value="<?= set_value('MonthlyNetTakeHome', $Employee->MonthlyNetTakeHome ? $Employee->MonthlyNetTakeHome : 0); ?>" id="MonthlyNetTakeHome" class="form-control form-control-sm" placeholder="Monthly Net Take Home" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('MonthlyNetTakeHome'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="EmployerPF">Employer PF <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="EmployerPF" value="<?= set_value('EmployerPF', $Employee->EmployerPF ? $Employee->EmployerPF : 0); ?>" id="EmployerPF" class="form-control form-control-sm" placeholder="Employer Provident Fund" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('EmployerPF'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="EmployerESI">Employer ESI <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="EmployerESI" value="<?= set_value('EmployerESI', $Employee->EmployerESI ? $Employee->EmployerESI : 0); ?>" id="EmployerESI" class="form-control form-control-sm" placeholder="Employer, Employee State Insurance" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('EmployerESI'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="AnnualComponents">Annual Components <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="AnnualComponents" value="<?= set_value('AnnualComponents', $Employee->AnnualComponents ? $Employee->AnnualComponents : 0); ?>" id="AnnualComponents" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Annual Components" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('AnnualComponents'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="PerformanceBonus">Performance Bonus <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="PerformanceBonus" value="<?= set_value('PerformanceBonus', $Employee->PerformanceBonus ? $Employee->PerformanceBonus : 0); ?>" id="PerformanceBonus" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Performance Bonus" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('PerformanceBonus'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="Gratuity">Gratuity/Ex-Gratia <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="Gratuity" value="<?= set_value('Gratuity', $Employee->Gratuity ? $Employee->Gratuity : 0); ?>" id="Gratuity" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Gratuity/Ex-Gratia" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('Gratuity'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="LeaveTravelAllowance">Leave Travel Allowance <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="LeaveTravelAllowance" value="<?= set_value('LeaveTravelAllowance', $Employee->LeaveTravelAllowance ? $Employee->LeaveTravelAllowance : 0); ?>" id="LeaveTravelAllowance" class="form-control form-control-sm" oninput="SalaryCalculate();" onchange="SalaryCalculate();" placeholder="Leave Travel Allowance" required="" minlength="1" maxlength="10" />
                                                                <span class="text-danger"><?= form_error('LeaveTravelAllowance'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="CostToCompany" style="font-size: 20px;">Cost To Company <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-4 form-group">
                                                                <input type="tel" name="CostToCompany" value="<?= set_value('CostToCompany', $Employee->CostToCompany ? $Employee->CostToCompany : 0); ?>" id="CostToCompany" class="form-control form-control-sm text-bold" placeholder="Cost To Company" required="" minlength="1" maxlength="10" readonly="" />
                                                                <span class="text-danger"><?= form_error('CostToCompany'); ?></span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-sm bg-maroon">
                                        <i class="fa fa-save text-white"></i> Make Changes
                                    </button>
                                    <a href="<?= base_url(); ?>Employee" class="btn btn-danger btn-sm">
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
</section>