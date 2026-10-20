<section class="py-3">
    <div class="container-fluid">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card card-outline card-fuchsia">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="header-title text-fuchsia">
                            <?= get_title('position', ['Id' => $Employee->RoleId ?? null], 'PostName'); ?> Information
                        </h5>
                    </div>
                    <a href="<?= base_url(); ?>Employee" title="Back to Report" class="btn btn-xs bg-fuchsia pull-right">
                        <i class="fa fa-angle-double-left text-white"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <tr>
                                <th colspan="2" class="text-fuchsia text-center">Personal</th>
                            </tr>
                            <tr>
                                <th>Date Of Registration</th>
                                <td><?= !empty($Employee->Date) ? date('d-m-Y', strtotime($Employee->Date)) : ""; ?></td>
                            </tr>
                            <tr>
                                <th>Employee Id</th>
                                <td><?= $Employee->EmployeeId; ?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?= $Employee->Name; ?></td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td><?= $Employee->Mobile; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $Employee->Email; ?></td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td><?= $Employee->Gender; ?></td>
                            </tr>
                            <tr>
                                <th>Date Of Birth</th>
                                <td><?= !empty($Employee->DateOfBirth) ? date('d-m-Y', strtotime($Employee->DateOfBirth)) : ""; ?></td>
                            </tr>
                            <tr>
                                <th>Blood Group</th>
                                <td><?= $Employee->BloodGroup; ?></td>
                            </tr>
                            <tr>
                                <th>Emergency Number</th>
                                <td><?= $Employee->EmergencyNumber; ?></td>
                            </tr>
                            <tr>
                                <th>Temporary Address</th>
                                <td><?= $Employee->TemporaryAddress; ?></td>
                            </tr>
                            <tr>
                                <th>Permanent Address</th>
                                <td><?= $Employee->PermanentAddress; ?></td>
                            </tr>
                            <tr>
                                <th>Father Name</th>
                                <td><?= $Employee->FatherName; ?></td>
                            </tr>
                            <tr>
                                <th>Father Mobile</th>
                                <td><?= $Employee->FatherMobile; ?></td>
                            </tr>
                            <tr>
                                <th>Mother Name</th>
                                <td><?= $Employee->MotherName; ?></td>
                            </tr>
                            <tr>
                                <th>Mother Mobile</th>
                                <td><?= $Employee->MotherMobile; ?></td>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-fuchsia text-center">Official</th>
                            </tr>
                            <tr>
                                <th>Post</th>
                                <td><?= get_title('position', ['Id' => $Employee->RoleId], 'PostName'); ?></td>
                            </tr>
                            <tr>
                                <th>Warehouse</th>
                                <td>
                                    <?= get_title('warehouse', ['id' => $Employee->WarehouseId], 'name'); ?>
                                    -
                                    <?= get_title('warehouse', ['id' => $Employee->WarehouseId], 'location'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th style="white-space: unset;">Warehouse Salesman</th>
                                <td style="white-space: unset;">
                                    <?php
                                        $SalesmanName = "";
                                        $warehouseSalesmen = !empty($Employee->WarehouseSalesman) ? json_decode($Employee->WarehouseSalesman, true) : [];
                                        if (is_array($warehouseSalesmen)) {
                                            foreach ($warehouseSalesmen as $key => $data) {
                                                $SalesmanName .= get_title('employees', ['Id' => $data], 'Name') . ", ";
                                            }
                                        }
                                        echo $SalesmanName !== "" ? substr(trim($SalesmanName), 0, -1) : "";
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Aadhaar Number</th>
                                <td><?= $Employee->AadhaarNumber; ?></td>
                            </tr>
                            <tr>
                                <th>PAN Number</th>
                                <td><?= $Employee->PANNumber; ?></td>
                            </tr>
                            <tr>
                                <th>UAN Number</th>
                                <td><?= $Employee->UANNumber; ?></td>
                            </tr>
                            <tr>
                                <th>ESI Number</th>
                                <td><?= $Employee->ESINumber; ?></td>
                            </tr>
                            <tr>
                                <th>Joining Date</th>
                                <td><?= !empty($Employee->JoiningDate) ? date('d-m-Y', strtotime($Employee->JoiningDate)) : ""; ?></td>
                            </tr>
                            <tr>
                                <th>Expiry Date</th>
                                <td><?= !empty($Employee->ExpiryDate) ? date('d-m-Y', strtotime($Employee->ExpiryDate)) : ""; ?></td>
                            </tr>
                            <tr>
                                <th>Password</th>
                                <td><?= $Employee->Password; ?></td>
                            </tr>
                            <tr>
                                <th>IP Address</th>
                                <td><?= $Employee->IPAddress; ?></td>
                            </tr>
                            <tr>
                                <th>Sales Target</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->SalesTarget; ?></td>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-fuchsia text-center">Documents</th>
                            </tr>
                            <tr>
                                <th>Photograph</th>
                                <td>
                                    <?php 
                                        $photographfile = $Employee->Photograph;
                                        $photograph = 'uploads/Employee/Photograph/' . $photographfile;
                                        if (file_exists($photograph) && !empty($photographfile)) {
                                    ?>
                                    <a href="<?= base_url() . '/' . $photograph; ?>" title="View in large" target="_blank">
                                        <img src="<?= base_url() . '/' . $photograph; ?>" alt="Photograph" style="width: 50px; border-radius: 25px;" />
                                    </a>
                                    <?php } else { ?>
                                    <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-availabl" style="width: 50px; border-radius: 25px;" />
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Identity Proof</th>
                                <td>
                                    <?php 
                                        $identity_prooffile = $Employee->IdentityProof;
                                        $identity_proof = 'uploads/Employee/IdentityProof/' . $identity_prooffile;
                                        if (file_exists($identity_proof) && !empty($identity_prooffile)) {
                                    ?>
                                    <a href="<?= base_url() . '/' . $identity_proof; ?>" title="View in large" target="_blank">
                                        <img src="<?= base_url() . '/' . $identity_proof; ?>" alt="IdentityProof" style="width: 50px; border-radius: 25px;" />
                                    </a>
                                    <?php } else { ?>
                                    <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-availabl" style="width: 50px; border-radius: 25px;" />
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Address Proof</th>
                                <td>
                                    <?php 
                                        $AddressProofFile = $Employee->AddressProof;
                                        $AddressProof = 'uploads/Employee/AddressProof/' . $AddressProofFile;
                                        if (file_exists($AddressProof) && !empty($AddressProofFile)) {
                                    ?>
                                    <a href="<?= base_url() . '/' . $AddressProof; ?>" title="View in large" target="_blank">
                                        <img src="<?= base_url() . '/' . $AddressProof; ?>" alt="AddressProof" style="width: 50px; border-radius: 25px;" />
                                    </a>
                                    <?php } else { ?>
                                    <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-availabl" style="width: 50px; border-radius: 25px;" />
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Bank Passbook</th>
                                <td>
                                    <?php 
                                        $BankPassbookFile = $Employee->BankPassbook;
                                        $BankPassbook = 'uploads/Employee/BankPassbook/' . $BankPassbookFile;
                                        if (file_exists($BankPassbook) && !empty($BankPassbookFile)) {
                                    ?>
                                    <a href="<?= base_url() . '/' . $BankPassbook; ?>" title="View in large" target="_blank">
                                        <img src="<?= base_url() . '/' . $BankPassbook; ?>" alt="BankPassbook" style="width: 50px; border-radius: 25px;" />
                                    </a>
                                    <?php } else { ?>
                                    <img src="<?= base_url(); ?>assets/images/no-image-available.jpg" alt="no-image-availabl" style="width: 50px; border-radius: 25px;" />
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-fuchsia text-center">Bank Account</th>
                            </tr>
                            <tr>
                                <th>Bank Name</th>
                                <td><?= $Employee->BankName; ?></td>
                            </tr>
                            <tr>
                                <th>Branch Name</th>
                                <td><?= $Employee->BranchName; ?></td>
                            </tr>
                            <tr>
                                <th>IFSC Code</th>
                                <td><?= $Employee->IFSCCode; ?></td>
                            </tr>
                            <tr>
                                <th>Account Number</th>
                                <td><?= $Employee->AccountNumber; ?></td>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-fuchsia text-center">Salary</th>
                            </tr>
                            <tr>
                                <th>Basic</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->Basic; ?></td>
                            </tr>
                            <tr>
                                <th>House Rent Allowance</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->HRA; ?></td>
                            </tr>
                            <tr>
                                <th>Conveyance Allowance</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->ConveyanceAllowance; ?></td>
                            </tr>
                            <tr>
                                <th>Staff Special Allowance</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->StaffSpecialAllowance; ?></td>
                            </tr>
                            <tr>
                                <th>Gross Monthly Total</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->GrossMonthlyTotal; ?></td>
                            </tr>
                            <tr>
                                <th>Employee Provident Fund</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->EmployeePF; ?></td>
                            </tr>
                            <tr>
                                <th>Employee State Insurance</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->EmployeeESI; ?></td>
                            </tr>
                            <tr>
                                <th>Profession Tax</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->ProfessionTax; ?></td>
                            </tr>
                            <tr>
                                <th>Income Tax</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->IncomeTax; ?></td>
                            </tr>
                            <tr>
                                <th>Tax Deducted at Source</th>
                                <td><?= $Employee->TDS; ?> <i class="fa fa-percent"></i></td>
                            </tr>
                            <tr>
                                <th>TDS Amount</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->TDSAmount; ?></td>
                            </tr>
                            <tr>
                                <th>Monthly Deduction</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->MonthlyDeduction; ?></td>
                            </tr>
                            <tr>
                                <th>Monthly Net Take Home</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->MonthlyNetTakeHome; ?></td>
                            </tr>
                            <tr>
                                <th>Employer Provident Fund</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->EmployerPF; ?></td>
                            </tr>
                            <tr>
                                <th>Employer, Employee State Insurance</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->EmployerESI; ?></td>
                            </tr>
                            <tr>
                                <th>Annual Components</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->AnnualComponents; ?></td>
                            </tr>
                            <tr>
                                <th>Performance Bonus</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->PerformanceBonus; ?></td>
                            </tr>
                            <tr>
                                <th>Gratuity/Ex-Gratia</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->Gratuity; ?></td>
                            </tr>
                            <tr>
                                <th>Leave Travel Allowance</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->LeaveTravelAllowance; ?></td>
                            </tr>
                            <tr>
                                <th>Cost To Company</th>
                                <td><i class="fa fa-inr"></i> <?= $Employee->CostToCompany; ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if ($Employee->Status) { ?>
                                    <label class="btn btn-success btn-xs">
                                        <i class="fa fa-check-circle text-white"></i> Active
                                    </label>
                                    <?php } else { ?>
                                    <label class="btn btn-danger btn-xs">
                                        <i class="fa fa-ban text-white"></i> Inactive
                                    </label>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Company</th>
                                <td><?= get_title('inventory_billing', ['id' => $Employee->CompId], 'company_name'); ?></td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td><?= $Employee->Created; ?></td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td><?= $Employee->CreatedBy; ?></td>
                            </tr>
                            <tr>
                                <th>Updated</th>
                                <td><?= $Employee->Updated; ?></td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td><?= $Employee->UpdatedBy; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>