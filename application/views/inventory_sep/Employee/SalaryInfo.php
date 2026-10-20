<section class="py-3">
    <div class="container-fluid">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="header-title text-primary">
                            Salary Information
                        </h5>
                    </div>
                    <a href="<?= base_url(); ?>Employee/SalaryReport" title="Back to Salary Report" class="btn btn-xs bg-primary pull-right">
                        <i class="fa fa-angle-double-left text-white"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <tr>
                                <th>Date</th>
                                <td><?= !empty($EmployeeSalary->Date) ? date('d-m-Y', strtotime($EmployeeSalary->Date)) : ""; ?></td>
                            </tr>
                            <tr>
                                <th>Employee Name</th>
                                <td>
                                    <?= get_title('employees', ['Id' => $EmployeeSalary->EmpId], 'Name'); ?>
                                    <small>(<?= get_title('position', ['Id', get_title('employees', ['Id' => $EmployeeSalary->EmpId], 'RoleId')], 'PostName'); ?>)</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Month</th>
                                <td><?= $EmployeeSalary->Month . "-" . $EmployeeSalary->Year; ?></td>
                            </tr>
                            <tr>
                                <th>Working Days</th>
                                <td><?= $EmployeeSalary->WorkingDays; ?></td>
                            </tr>
                            <tr>
                                <th>Present Days</th>
                                <td><?= $EmployeeSalary->PresentDays; ?></td>
                            </tr>
                            <tr>
                                <th>Leave Days</th>
                                <td><?= $EmployeeSalary->LeaveDays; ?></td>
                            </tr>
                            <tr>
                                <th>Holidays</th>
                                <td><?= $EmployeeSalary->Holidays; ?></td>
                            </tr>
                            <tr>
                                <th>Absent Days</th>
                                <td><?= $EmployeeSalary->AbsentDays; ?></td>
                            </tr>
                            <tr>
                                <th>Total Days</th>
                                <td><?= $EmployeeSalary->TotalDays; ?></td>
                            </tr>
                            <tr>
                                <th>Over Time Hours</th>
                                <td><?= $EmployeeSalary->OverTimeHours; ?></td>
                            </tr>
                            <tr>
                                <th>Basic</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->Basic; ?></td>
                            </tr>
                            <tr>
                                <th>House Rent Allowance</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->HRA; ?></td>
                            </tr>
                            <tr>
                                <th>Conveyance Allowance</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->ConveyanceAllowance; ?></td>
                            </tr>
                            <tr>
                                <th>Staff Special Allowance</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->StaffSpecialAllowance; ?></td>
                            </tr>
                            <tr>
                                <th>Gross Monthly Total</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->GrossMonthlyTotal; ?></td>
                            </tr>
                            <tr>
                                <th>Employee Provident Fund</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->EmployeePF; ?></td>
                            </tr>
                            <tr>
                                <th>Employee State Insurance</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->EmployeeESI; ?></td>
                            </tr>
                            <tr>
                                <th>Profession Tax</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->ProfessionTax; ?></td>
                            </tr>
                            <tr>
                                <th>Income Tax</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->IncomeTax; ?></td>
                            </tr>
                            <tr>
                                <th>TDS Amount</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->TDSAmount; ?></td>
                            </tr>
                            <tr>
                                <th>Monthly Deduction</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->MonthlyDeduction; ?></td>
                            </tr>
                            <tr>
                                <th>Monthly Net Take Home</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->MonthlyNetTakeHome; ?></td>
                            </tr>
                            <tr>
                                <th>Employer Provident Fund</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->EmployerPF; ?></td>
                            </tr>
                            <tr>
                                <th>Employer, Employee State Insurance</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->EmployerESI; ?></td>
                            </tr>
                            <tr>
                                <th>Annual Components</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->AnnualComponents; ?></td>
                            </tr>
                            <tr>
                                <th>Performance Bonus</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->PerformanceBonus; ?></td>
                            </tr>
                            <tr>
                                <th>Gratuity/Ex-Gratia</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->Gratuity; ?></td>
                            </tr>
                            <tr>
                                <th>Leave Travel Allowance</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->LeaveTravelAllowance; ?></td>
                            </tr>
                            <tr>
                                <th>Security Deduction</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->SecurityDeduction; ?></td>
                            </tr>
                            <tr>
                                <th>Over Time Amount</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->OverTimeAmount; ?></td>
                            </tr>
                            <tr>
                                <th>Net Take Home Salary</th>
                                <td><i class="fa fa-inr"></i> <?= $EmployeeSalary->NetTakeHomeSalary; ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if ($EmployeeSalary->Status == 0) { ?>
                                    <label class="btn btn-warning btn-sm"><i class="fa fa-spinner"></i> Pending</label>
                                    <?php } elseif ($EmployeeSalary->Status == 1) { ?>
                                    <label class="btn btn-success btn-sm"><i class="fa fa-check-circle-o"></i> Released</label>
                                    <?php } elseif ($EmployeeSalary->Status == 2) { ?>
                                    <label class="btn btn-da btn-sm"><i class="fa fa-exclamation"></i> Hold</label>
                                    <?php } elseif ($EmployeeSalary->Status == 3) { ?>
                                    <label class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Cancelled</label>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Company</th>
                                <td><?= get_title('inventory_billing', ['id' => $EmployeeSalary->CompId], 'company_name'); ?></td>
                            </tr>
                            <?php if ($this->session->userdata('BranchRole')) { ?>
                            <tr>
                                <th>Company Branch</th>
                                <td><?= get_title('companybranch', ['Id' => $EmployeeSalary->CompanyBranchId], 'BranchName'); ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th>Created</th>
                                <td><?= $EmployeeSalary->Created; ?></td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td><?= $EmployeeSalary->CreatedBy; ?></td>
                            </tr>
                            <tr>
                                <th>Updated</th>
                                <td><?= $EmployeeSalary->Updated; ?></td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td><?= $EmployeeSalary->UpdatedBy; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>