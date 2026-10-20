<div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div> 
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <form method="POST" action="<?= base_url(); ?>Employee/EMPAttendanceUpdate/<?= $EmployeeAttendance->Id; ?>">
                        <input type="hidden" name="AttendanceId" value="<?= set_value('AttendanceId', $EmployeeAttendance->Id); ?>" id="AttendanceId" />
                        <div class="card card-outline card-danger">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-danger">
                                        Add Out Time
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>Employee/EMPAttendance" class="btn btn-sm btn-danger pull-right" title="Attendance Report">
                                    <i class="fa fa-angle-double-left"></i> Back
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-3">
                                        <div class="form-group">
                                            <label for="EmpId">Employees <span class="text-danger">*</span></label>
                                            <select name="EmpId" id="EmpId" class="form-control form-control-sm" title="Employees" required="">
                                                <?php
                                                    if (isset($Employees) && !empty($Employees)) {
                                                        foreach ($Employees as $key => $Employee) {
                                                            $RoleName = get_title('position', ['Id' => $Employee->RoleId], 'PostName');
                                                ?>
                                                <option value="<?= $Employee->Id; ?>" <?= $EmployeeAttendance->EmpId == $Employee->Id ? "selected" : ""; ?> <?= set_select('EmpId', $Employee->Id); ?>><?= $Employee->Name; ?> (<?= $RoleName; ?>)</option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger"><?= form_error('EmpId'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="date" name="Date" value="<?= set_value('Date', $EmployeeAttendance->Date); ?>" id="Date" class="form-control form-control-sm" title="Date" readonly="" required="" />
                                            <span class="text-danger"><?= form_error('Date'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-3">
                                        <div class="form-group">
                                            <label for="Attendance">Attendance <span class="text-danger">*</span></label>
                                            <input type="text" name="Attendance" value="<?= set_value('Attendance', $EmployeeAttendance->Attendance); ?>" id="Attendance" class="form-control form-control-sm" placeholder="Attendance" readonly="" required="" />
                                            <span class="text-danger"><?= form_error('Attendance'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="InTime">In Time <span class="text-danger">*</span></label>
                                            <input type="text" name="InTime" value="<?= set_value('InTime', $EmployeeAttendance->InTime); ?>"  id="InTime" class="form-control form-control-sm" title="In Time" readonly="" />
                                            <span class="text-danger"><?= form_error('InTime'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-3">
                                        <div class="form-group">
                                            <label for="OutTime">Out Time <span class="text-danger">*</span></label>
                                            <input type="time" name="OutTime" value="<?= set_value('OutTime', $EmployeeAttendance->OutTime ? $EmployeeAttendance->OutTime : date('H:i')); ?>" id="OutTime" class="form-control form-control-sm" title="Out Time" readonly="" />
                                            <span class="text-danger"><?= form_error('OutTime'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="ShiftType">Shift <span class="text-danger">*</span></label>
                                            <input type="text" name="ShiftType" value="<?= set_value('ShiftType', $EmployeeAttendance->ShiftType); ?>" id="ShiftType" class="form-control form-control-sm" placeholder="Shift Type" readonly="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-4">
                                    <button type="submit" class="btn btn-sm bg-navy">
                                        Out Time Save
                                    </button> 
                                    <button type="reset" class="btn btn-sm btn-danger">
                                        Reset
                                    </button>
                                    <a href="<?= base_url(); ?>Employee/EMPAttendance" class="btn btn-info btn-sm" title="Attendance Report">
                                        View Attendance
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>