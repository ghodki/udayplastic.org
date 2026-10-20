<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-pink">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="header-title text-pink">
                                Salary Create
                            </h5>
                        </div>
                        <a href="<?= base_url(); ?>Employee/SalaryReport" class="btn btn-sm bg-pink pull-right" title="Salary Report">
                            <i class="fa fa-file text-white"></i> Salary Report
                        </a>
                    </div>
                    <form action="<?= base_url(); ?>Employee/SaveSalary" method="POST">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xxl-4">
                                    <div class="form-group">
                                        <label for="EmpId">Employee <span class="text-danger">*</span></label>
                                        <select name="EmpId" id="EmpId" class="form-control form-control-sm" title="Select Employee" required="" onchange="GetEmployeeAttendance();">
                                            <option value="" selected="" disabled="">Select Employee</option>
                                            <?php if (!empty($Employees)) { foreach($Employees as $key => $Emp) { ?>
                                            <option value="<?= $Emp->Id; ?>" <?= set_select('EmpId', $Emp->Id); ?>><?= $Emp->Name; ?> (<?= get_title('position', ['Id' => $Emp->RoleId], 'PostName'); ?>)</option>
                                            <?php } } ?>
                                        </select>
                                        <span class="text-danger"><?= form_error('EmpId'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xxl-4">
                                    <div class="form-group">
                                        <label for="Month">Month <span class="text-danger">*</span></label>
                                        <select name="Month" id="Month" class="form-control form-control-sm" title="Select Month" required="" onchange="GetEmployeeAttendance();">
                                            <option value="" selected="" disabled="">Select Month</option>
                                            <option value="January" <?= set_select('Month', 'January'); ?>>January</option>
                                            <option value="February" <?= set_select('Month', 'February'); ?>>February</option>
                                            <option value="March" <?= set_select('Month', 'March'); ?>>March</option>
                                            <option value="April" <?= set_select('Month', 'April'); ?>>April</option>
                                            <option value="May" <?= set_select('Month', 'May'); ?>>May</option>
                                            <option value="June" <?= set_select('Month', 'June'); ?>>June</option>
                                            <option value="July" <?= set_select('Month', 'July'); ?>>July</option>
                                            <option value="August" <?= set_select('Month', 'August'); ?>>August</option>
                                            <option value="September" <?= set_select('Month', 'September'); ?>>September</option>
                                            <option value="October" <?= set_select('Month', 'October'); ?>>October</option>
                                            <option value="November" <?= set_select('Month', 'November'); ?>>November</option>
                                            <option value="December" <?= set_select('Month', 'December'); ?>>December</option>
                                        </select>
                                        <span class="text-danger"><?= form_error('Month'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <hr />
                            <legend class="text-pink" style="font-size: 15px;">Salary Generation</legend>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="bg-pink">
                                                <tr>
                                                    <th>Working Days</th>
                                                    <th>Present Days</th>
                                                    <th>Leave Days</th>
                                                    <th>Holidays</th>
                                                    <th>Absent Days</th>
                                                    <th>Total Days</th>
                                                    <th>Over Time Hrs</th>
                                                    <th colspan="11"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="WorkingDays" value="<?= set_value('WorkingDays'); ?>" id="WorkingDays" class="form-control form-control-sm" placeholder="Working Days" required="" minlength="1" maxlength="2" oninput="EmployeeSalary();" />
                                                            <span class="text-danger"><?= form_error('WorkingDays'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="PresentDays" value="<?= set_value('PresentDays'); ?>" id="PresentDays" class="form-control form-control-sm" placeholder="Present Days" required="" minlength="1" maxlength="2" readonly="" />
                                                            <span class="text-danger"><?= form_error('PresentDays'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="LeaveDays" value="<?= set_value('LeaveDays'); ?>" id="LeaveDays" class="form-control form-control-sm" placeholder="Leave Days" required="" minlength="1" maxlength="2" readonly="" />
                                                            <span class="text-danger"><?= form_error('LeaveDays'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="Holidays" value="<?= set_value('Holidays'); ?>" id="Holidays" class="form-control form-control-sm" placeholder="Holidays" required="" minlength="1" maxlength="2" readonly="" />
                                                            <span class="text-danger"><?= form_error('Holidays'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="AbsentDays" value="<?= set_value('AbsentDays'); ?>" id="AbsentDays" class="form-control form-control-sm" placeholder="Absent Days" required="" minlength="1" maxlength="2" readonly="" />
                                                            <span class="text-danger"><?= form_error('AbsentDays'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="TotalDays" value="<?= set_value('TotalDays'); ?>" id="TotalDays" class="form-control form-control-sm" placeholder="Total Days" required="" minlength="1" maxlength="2" readonly="" />
                                                            <span class="text-danger"><?= form_error('TotalDays'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="OverTimeHours" value="<?= set_value('OverTimeHours'); ?>" id="OverTimeHours" class="form-control form-control-sm" placeholder="Over Time Hours" required="" minlength="1" maxlength="5" readonly="" />
                                                            <span class="text-danger"><?= form_error('OverTimeHours'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td colspan="11"></td>
                                                </tr>
                                                <tr class="bg-pink">
                                                    <th>Basic</th>
                                                    <th>HRA</th>
                                                    <th>Conveyance Allowance</th>
                                                    <th>Staff Special Allowance</th>
                                                    <th>Gross Monthly Total</th>
                                                    <th>Employee PF</th>
                                                    <th>Employee ESI</th>
                                                    <th>Profession Tax</th>
                                                    <th>Income Tax</th>
                                                    <th>TDS Amount</th>
                                                    <th>Monthly Deduction</th>
                                                    <th>Monthly Net Take Home</th>
                                                    <th>Employer PF</th>
                                                    <th>Employer ESI</th>
                                                    <th>Annual Components</th>
                                                    <th>Performance Bonus</th>
                                                    <th>Gratuity</th>
                                                    <th>Leave Travel Allowance</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="Basic" value="<?= set_value('Basic'); ?>" id="Basic" class="form-control form-control-sm" placeholder="Basic" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('Basic'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="HRA" value="<?= set_value('HRA'); ?>" id="HRA" class="form-control form-control-sm" placeholder="HRA" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('HRA'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="ConveyanceAllowance" value="<?= set_value('ConveyanceAllowance'); ?>" id="ConveyanceAllowance" class="form-control form-control-sm" placeholder="Conveyance Allowance" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('ConveyanceAllowance'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="StaffSpecialAllowance" value="<?= set_value('StaffSpecialAllowance'); ?>" id="StaffSpecialAllowance" class="form-control form-control-sm" placeholder="Staff Special Allowance" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('StaffSpecialAllowance'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="GrossMonthlyTotal" value="<?= set_value('GrossMonthlyTotal'); ?>" id="GrossMonthlyTotal" class="form-control form-control-sm" placeholder="Gross Monthly Total" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('GrossMonthlyTotal'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="EmployeePF" value="<?= set_value('EmployeePF'); ?>" id="EmployeePF" class="form-control form-control-sm" placeholder="Employee PF" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('EmployeePF'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="EmployeeESI" value="<?= set_value('EmployeeESI'); ?>" id="EmployeeESI" class="form-control form-control-sm" placeholder="Employee ESI" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('EmployeeESI'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="ProfessionTax" value="<?= set_value('ProfessionTax'); ?>" id="ProfessionTax" class="form-control form-control-sm" placeholder="Profession Tax" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('ProfessionTax'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="IncomeTax" value="<?= set_value('IncomeTax'); ?>" id="IncomeTax" class="form-control form-control-sm" placeholder="Income Tax" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('IncomeTax'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="TDSAmount" value="<?= set_value('TDSAmount'); ?>" id="TDSAmount" class="form-control form-control-sm" placeholder="TDS Amount" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('TDSAmount'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="MonthlyDeduction" value="<?= set_value('MonthlyDeduction'); ?>" id="MonthlyDeduction" class="form-control form-control-sm" placeholder="Monthly Deduction" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('MonthlyDeduction'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="MonthlyNetTakeHome" value="<?= set_value('MonthlyNetTakeHome'); ?>" id="MonthlyNetTakeHome" class="form-control form-control-sm" placeholder="Monthly Net Take Home" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('MonthlyNetTakeHome'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="EmployerPF" value="<?= set_value('EmployerPF'); ?>" id="EmployerPF" class="form-control form-control-sm" placeholder="Employer PF" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('EmployerPF'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="EmployerESI" value="<?= set_value('EmployerESI'); ?>" id="EmployerESI" class="form-control form-control-sm" placeholder="Employer ESI" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('EmployerESI'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="AnnualComponents" value="<?= set_value('AnnualComponents'); ?>" id="AnnualComponents" class="form-control form-control-sm" placeholder="Annual Components" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('AnnualComponents'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="PerformanceBonus" value="<?= set_value('PerformanceBonus'); ?>" id="PerformanceBonus" class="form-control form-control-sm" placeholder="Performance Bonus" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('PerformanceBonus'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="Gratuity" value="<?= set_value('Gratuity'); ?>" id="Gratuity" class="form-control form-control-sm" placeholder="Gratuity" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('Gratuity'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="LeaveTravelAllowance" value="<?= set_value('LeaveTravelAllowance'); ?>" id="LeaveTravelAllowance" class="form-control form-control-sm" placeholder="Leave Travel Allowance" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('LeaveTravelAllowance'); ?></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="bg-pink">
                                                    <th>Security Deduction</th>
                                                    <th>Over Time Amount</th>
                                                    <th>Net Take Home Salary</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="SecurityDeduction" value="<?= set_value('SecurityDeduction', 0); ?>" id="SecurityDeduction" class="form-control form-control-sm" placeholder="Security Deduction" required="" minlength="1" maxlength="10" oninput="EmployeeSalary();" />
                                                            <span class="text-danger"><?= form_error('SecurityDeduction'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="OverTimeAmount" value="<?= set_value('OverTimeAmount', 0); ?>" id="OverTimeAmount" class="form-control form-control-sm" placeholder="Over Time Amount" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('OverTimeAmount'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="tel" name="NetTakeHomeSalary" value="<?= set_value('NetTakeHomeSalary'); ?>" id="NetTakeHomeSalary" class="form-control form-control-sm" placeholder="Net Take Home Salary" required="" minlength="1" maxlength="10" readonly="" />
                                                            <span class="text-danger"><?= form_error('NetTakeHomeSalary'); ?></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-sm bg-pink">
                                    Make Record
                                </button>
                                <button type="reset" class="btn btn-sm bg-danger">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>