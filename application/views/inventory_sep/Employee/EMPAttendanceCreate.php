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
                    <form method="POST" action="<?= base_url(); ?>Employee/EMPAttendanceSave">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-success">
                                        Mark Attendance
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>Employee/EMPAttendance" class="btn btn-sm btn-success pull-right" title="Attendance Report">
                                    <i class="fa fa-check-circle"></i> Attendance Report
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
                                                <option value="<?= $Employee->Id; ?>" <?= $EmployeeInfo->Id == $Employee->Id ? "selected" : ""; ?> <?= set_select('EmpId', $Employee->Id); ?>><?= $Employee->Name; ?> (<?= $RoleName; ?>)</option>
                                                <?php } } ?>
                                            </select>
                                            <span class="text-danger"><?= form_error('EmpId'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-3">
                                        <div class="form-group">
                                            <label for="Date">Date <span class="text-danger">*</span></label>
                                            <input type="Date" name="Date" id="Date" value="<?= set_value('Date', date('Y-m-d')); ?>" class="form-control form-control-sm" title="Date" readonly="" required="" />
                                            <span class="text-danger"><?= form_error('Date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="Attendance">Attendance <span class="text-danger">*</span></label>
                                            <select name="Attendance" id="Attendance" class="form-control form-control-sm" title="Attendance" required="">
                                                <option value="" selected="" disabled="">Attendance</option>
                                                <option value="Present" <?= set_select('Attendance', 'Present'); ?>>Present</option>
                                                <option value="Absent" <?= set_select('Attendance', 'Absent'); ?>>Absent</option>
                                                <option value="Half Day" <?= set_select('Attendance', 'Half Day'); ?>>Half Day</option>
                                                <option value="EL" <?= set_select('Attendance', 'EL'); ?>>EL</option>
                                                <option value="CL" <?= set_select('Attendance', 'CL'); ?>>CL</option>
                                                <option value="Holiday" <?= set_select('Attendance', 'Holiday'); ?>>Holiday</option>
                                                <option value="Leave Without Pay" <?= set_select('Attendance', 'Leave Without Pay'); ?>>Leave Without Pay</option>
                                                <option value="Leave With Pay" <?= set_select('Attendance', 'Leave With Pay'); ?>>Leave With Pay</option>
                                            </select>
                                            <span class="text-danger"><?= form_error('Attendance'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row PresentAndHalfDay">
                                    <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-3">
                                        <div class="form-group">
                                            <label for="InTime">In Time <span class="text-danger">*</span></label>
                                            <input type="time" name="InTime" value="<?= set_value('InTime', date('H:i')); ?>" id="InTime" class="form-control form-control-sm" min="<?= date('h:i'); ?>" readonly="" />
                                            <span class="text-danger"><?= form_error('InTime'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="ShiftType">Shift <span class="text-danger">*</span></label>
                                            <select name="ShiftType" id="ShiftType" class="form-control form-control-sm" title="Shift Type">
                                                <option value="" selected="" disabled="">Shift</option>
                                                <option value="A" <?= set_select('ShiftType', 'A'); ?>>A</option>
                                                <option value="B" <?= set_select('ShiftType', 'B'); ?>>B</option>
                                                <option value="C" <?= set_select('ShiftType', 'C'); ?>>C</option>
                                            </select>
                                            <span class="text-danger"><?= form_error('ShiftType'); ?></span>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="card-footer">
                                <div class="col-md-3 col-sm-3 col-lg-3 offset-sm-4">
                                    <button type="submit" class="btn btn-success btn-sm">Mark Attendance</button> 
                                    <button type="reset" class="btn btn-danger btn-sm">Reset</button>
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

<script type="text/javascript">
    $("#Attendance").on("change",function() {
        let Attendance = $("#Attendance").val(); 
        if ((Attendance == "Half Day") || (Attendance == "Present")) {
            $(".PresentAndHalfDay").show();
            $("#InTime").prop("required", true);
            $("#ShiftType").prop("required", true);
        } else {
            $(".PresentAndHalfDay").hide();
            $("#InTime").prop("required", false);
            $("#ShiftType").prop("required", false);
        }
    }); 
</script>