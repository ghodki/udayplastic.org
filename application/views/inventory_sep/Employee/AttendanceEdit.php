<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-navy">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="card-title">
                                    <h5 class="header-title text-navy">
                                        Attendance Edit
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>Employee/AttendanceReport" class="btn btn-sm bg-navy pull-right" title="Attendance Report">
                                    <i class="fa fa-file text-white"></i> Attendance Report
                                </a>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <form action="<?= base_url(); ?>Employee/AttendanceEdit" method="GET">
                                    <div class="row">
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
                            </div>
                        </div>
                    </div>
                    <form action="<?= base_url(); ?>Employee/UpdateAttendance" method="POST" name="frmactive">
                        <input type="hidden" name="From" value="<?= set_value('From', $this->input->get('From')); ?>" id="From" />
                        <input type="hidden" name="To" value="<?= set_value('To', $this->input->get('To')); ?>" id="To" />
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm DataTable">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th>
                                                <input type="checkbox" name="allbox" id="allbox" title="Select OR De-Select ALL" onclick="un_check();" />
                                            </th>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>In</th>
                                            <th>Out</th>
                                            <th>Shift</th>
                                            <th>Attendance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!empty($EmployeeAttendance)) {
                                                $Sr = 0;
                                                foreach ($EmployeeAttendance as $key => $Employee) {
                                                    $Sr++;

                                        ?>
                                        <tr>
                                            <input type="hidden" name="EmpAttendanceId[]" value="<?= $Employee->Id; ?>" id="EmpAttendanceId" />
                                            <td>
                                                <input type="checkbox" name="AttendanceId[]" value="<?= $Employee->Id; ?>" id="AttendanceId" />
                                            </td>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <?= get_title('employees', ['Id' => $Employee->EmpId], 'Name'); ?>
                                                <br />
                                                <small class="text-purple">
                                                    (<?= get_title('position', ['Id' => get_title('employees', ['Id' => $Employee->EmpId], 'RoleId')], 'PostName'); ?>)
                                                </small>
                                            </td>
                                            <td>
                                                <input type="date" name="Date[]" value="<?= set_value('Date[]', $Employee->Date); ?>" id="Date<?= $Sr; ?>" class="form-control form-control-sm" title="Date" />
                                            </td>
                                            <td>
                                                <input type="time" name="InTime[]" value="<?= set_value('InTime[]', $Employee->InTime); ?>" id="InTime<?= $Sr; ?>" class="form-control form-control-sm" title="In Time" />
                                            </td>
                                            <td>
                                                <input type="time" name="OutTime[]" value="<?= set_value('OutTime[]', $Employee->OutTime ? $Employee->OutTime : date('H:i')); ?>" id="OutTime<?= $Sr; ?>" class="form-control form-control-sm" title="Out Time" />
                                            </td>
                                            <td>
                                                <select name="ShiftType[]" id="ShiftType<?= $Sr; ?>" class="form-control form-control-sm" title="Shift Type">
                                                    <option value="A" <?= $Employee->ShiftType == "A" ? "selected" : ""; ?> <?= set_select('ShiftType[]', 'A'); ?>>A</option>
                                                    <option value="B" <?= $Employee->ShiftType == "B" ? "selected" : ""; ?> <?= set_select('ShiftType[]', 'B'); ?>>B</option>
                                                    <option value="C" <?= $Employee->ShiftType == "C" ? "selected" : ""; ?> <?= set_select('ShiftType[]', 'C'); ?>>C</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="Attendance[]" id="Attendance<?= $Sr; ?>" class="form-control form-control-sm" title="Attendance">
                                                    <option value="Present" <?= $Employee->Attendance == "Present" ? "selected" : ""; ?> <?= set_select('Attendance[]', 'Present'); ?>>Present</option>
                                                    <option value="Absent" <?= $Employee->Attendance == "Absent" ? "selected" : ""; ?> <?= set_select('Attendance[]', 'Absent'); ?>>Absent</option>
                                                    <option value="Half Day" <?= $Employee->Attendance == "Half Day" ? "selected" : ""; ?> <?= set_select('Attendance[]', 'Half Day'); ?>>Half Day</option>
                                                    <option value="EL" <?= $Employee->Attendance == "EL" ? "selected" : ""; ?> <?= set_select('Attendance[]', 'EL'); ?>>EL</option>
                                                    <option value="CL" <?= $Employee->Attendance == "CL" ? "selected" : ""; ?> <?= set_select('Attendance[]', 'CL'); ?>>CL</option>
                                                    <option value="Holiday" <?= $Employee->Attendance == "Holiday" ? "selected" : ""; ?> <?= set_select('Attendance[]', 'Holiday'); ?>>Holiday</option>
                                                    <option value="Leave Without Pay" <?= $Employee->Attendance == "Leave Without Pay" ? "selected" : ""; ?> <?= set_select('Attendance[]', 'Leave Without Pay'); ?>>Leave Without Pay</option>
                                                    <option value="Leave With Pay" <?= $Employee->Attendance == "Leave With Pay" ? "selected" : ""; ?> <?= set_select('Attendance[]', 'Leave With Pay'); ?>>Leave With Pay</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-sm bg-navy">Make Changes</button>
                                <a href="<?= base_url(); ?>Employee/AttendanceReport" class="btn btn-danger btn-sm" title="Cancel">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>