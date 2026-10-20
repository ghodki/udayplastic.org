<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-maroon">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="header-title text-maroon">
                                Attendance Report
                            </h5>
                        </div>
                        <a href="<?= base_url(); ?>Employee/AttendanceEdit?From=<?= date('Y-m-d'); ?>&To=<?= date('Y-m-d'); ?>" class="btn btn-sm bg-maroon pull-right" title="Attendance Edit">
                            <i class="fa fa-edit text-white"></i> Attendance Edit
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url(); ?>Employee/AttendanceReport" method="GET">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <select name="EmpId" id="EmpId" class="form-control form-control-sm" title="Employee">
                                            <option value="All" <?= $this->input->get('EmpId') == "All" ? "selected" : ""; ?>>All Employee</option>
                                            <?php 
                                                if (isset($Employees) && !empty($Employees)) {
                                                    foreach ($Employees as $key => $Employee) {
                                                        $RoleName = get_title('position', ['Id' => $Employee->RoleId], 'PostName');
                                            ?>
                                            <option value="<?= $Employee->Id; ?>" <?= $this->input->get('EmpId') == $Employee->Id ? "selected" : ""; ?> <?= set_select('EmpId', $Employee->Id); ?>><?= $Employee->Name; ?> (<?= $RoleName; ?>)</option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <input type="date" name="From" value="<?= set_value('From', !empty($this->input->get('From')) ? $this->input->get('From') : date('Y-m-d')); ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <input type="date" name="To" value="<?= set_value('To', !empty($this->input->get('From')) ? $this->input->get('To') : date('Y-m-d')); ?>" id="From" class="form-control form-control-sm" title="From Date" />
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm bg-maroon">
                                            <i class="fa fa-search text-white"></i> Search
                                        </button>
                                        <a href="<?= base_url(); ?>Employee/AttendanceReport" class="btn btn-danger btn-sm" title="Refresh">
                                            <i class="fa fa-refresh text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm DataTable">
                                <thead class="bg-maroon">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Name</th>
                                        <th class="text-white">Date</th>
                                        <th class="text-white">In</th>
                                        <th class="text-white">Out</th>
                                        <th class="text-white">Shift</th>
                                        <th class="text-white">Attendance</th>
                                        <th class="text-white">Month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $Sr = 0;
                                        $Present = 0;
                                        $Absent = 0;
                                        $HalfDay = 0;
                                        $EL = 0;
                                        $CL = 0;
                                        $Holiday = 0;
                                        $LeaveWithoutPay = 0;
                                        $LeaveWithPay = 0;
                                        if (!empty($Attendances)) {
                                            foreach ($Attendances as $key => $Report) {
                                                $Sr++;
                                                
                                                if ($this->input->get('From') && $this->input->get('To')) {
                                                    $From = $this->input->get('From');
                                                    $To = $this->input->get('To');
                                                } else {
                                                    $From = date('Y-m-d');
                                                    $To = date('Y-m-d');
                                                }
                                                
                                                $Where = ['Date >= ' => $From, 'Date <= ' => $To];
                                                if ($this->input->get('EmpId') != 'All') {
                                                    $Where['EmpId'] = $Report->EmpId;
                                                }
                                                
                                                $Present = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Present')->get('employee_attendance')->num_rows();
                                                $Absent = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Absent')->get('employee_attendance')->num_rows();
                                                $HalfDay = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Half Day')->get('employee_attendance')->num_rows();
                                                $EL = $this->db->select('Attendance')->where($Where)->where('Attendance', 'EL')->get('employee_attendance')->num_rows();
                                                $CL = $this->db->select('Attendance')->where($Where)->where('Attendance', 'CL')->get('employee_attendance')->num_rows();
                                                $Holiday = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Holiday')->get('employee_attendance')->num_rows();
                                                $LeaveWithoutPay = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Leave Without Pay')->get('employee_attendance')->num_rows();
                                                $LeaveWithPay = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Leave With Pay')->get('employee_attendance')->num_rows();
                                    ?>
                                    <tr>
                                        <td><?= $Sr; ?></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-2 col-xs-2 col-lg-2 col-xx-2 col-sm-2 col-xxl-2">
                                                    <?php 
                                                        $SelfieFile = $Report->SelfieImage;
                                                        $Selfie = 'uploads/Employee/Selfie/' . $SelfieFile;
                                                        if (file_exists($Selfie) && !empty($SelfieFile)) {
                                                    ?>
                                                    <img src="<?= base_url() . '/' . $Selfie; ?>" alt="Selfie" style="width: 30px; border-radius: 25px;" />
                                                    <?php } else { ?>
                                                    <img src="<?= base_url(); ?>assets/images/avatar.png" alt="avatar" style="width: 30px; border-radius: 25px;" />
                                                    <?php } ?>
                                                </div>
                                                <div class="col-md-8 col-xs-8 col-lg-8 col-xx-8 col-sm-8 col-xxl-8">
                                                    <span style="margin: 5px 0px 0px 5px;">
                                                        <?= get_title('employees', ['Id' => $Report->EmpId], 'Name'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= date('d-m-Y', strtotime($Report->Date));?></td>
                                        <td><?= $Report->InTime; ?></td>
                                        <td><?= $Report->OutTime; ?></td>
                                        <td><?= $Report->ShiftType; ?></td>
                                        <td>
                                            <?= $Report->Attendance == "Present" ? "<span class='text-success'>Present</span>" : ""; ?>
                                            <?= $Report->Attendance == "Absent" ? "<span class='text-danger'>Absent</span>" : ""; ?>
                                            <?= $Report->Attendance == "Half Day" ? "<span class='text-primary'>Half Day</span>" : ""; ?>
                                            <?= $Report->Attendance == "EL" ? "<span class='text-info'>EL</span>" : ""; ?>
                                            <?= $Report->Attendance == "CL" ? "<span class='text-dark'>CL</span>" : ""; ?>
                                            <?= $Report->Attendance == "Holiday" ? "<span class='text-warning'>Holiday</span>" : ""; ?>
                                            <?= $Report->Attendance == "Leave Without Pay" ? "<span class='text-purple'>Leave Without Pay</span>" : ""; ?>
                                            <?= $Report->Attendance == "Leave With Pay" ? "<span class='text-marron'>Leave With Pay</span>" : ""; ?>
                                        </td>
                                        <td><?= $Report->Month; ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="text-success mr-5">Present : <?= $Present; ?></span>
                        <span class="text-danger mr-5">Absent : <?= $Absent; ?></span>
                        <span class="text-primary mr-5">Half Day : <?= $HalfDay; ?></span>
                        <span class="text-info mr-5">EL : <?= $EL; ?></span>
                        <span class="text-dark mr-5">CL : <?= $CL; ?></span>
                        <span class="text-warning mr-5">Holiday : <?= $Holiday; ?></span>
                        <span class="text-purple mr-5">Leave Without Pay : <?= $LeaveWithoutPay; ?></span>
                        <span class="text-marron mr-5">Leave With Pay : <?= $LeaveWithPay; ?></span>
                        <span class="text-navy font-weight-bolder">
                            Total Salary Days : <?= ($Present + ($HalfDay /2 ) + $EL + $CL + $Holiday + $LeaveWithPay); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>