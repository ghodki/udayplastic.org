<section class="py-3">
    <div class="container-fluid">
        <?php if ($this->session->flashdata('msg')) { ?>
        <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
            <?= $this->session->flashdata('msg'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <form action="<?= base_url(); ?>Employee/TakeAttendance" method="POST" name="frmactive">
                    <div class="card card-outline card-purple">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-purple">
                                    Attendance
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/AttendanceReport" class="btn btn-sm bg-purple pull-right" title="Attendance Report">
                                <i class="fa fa-file text-white"></i> Attendance Report
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm DataTable">
                                    <thead class="bg-purple">
                                        <tr>
                                            <th class="text-white">
                                                <input type="checkbox" name="allbox" title="Select OR De-Select ALL" onclick="un_check();" />
                                            </th>
                                            <th class="text-white">Sr.</th>
                                            <th class="text-white">Name</th>
                                            <th class="text-white">Date</th>
                                            <th class="text-white">In</th>
                                            <th class="text-white">Out</th>
                                            <th class="text-white">Shift</th>
                                            <th class="text-white">Attendance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (!empty($Employees)) {
                                                $Sr = 0;
                                                foreach ($Employees as $key => $Employee) {
                                                    $Sr++;

                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="EmpId[]" value="<?= $Employee->Id; ?>" />
                                            </td>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <?= $Employee->Name; ?>
                                                <br />
                                                <small class="text-purple">(<?= get_title('position', ['Id' => $Employee->RoleId], 'PostName'); ?>)</small>
                                            </td>
                                            <td>
                                                <input type="date" name="Date[]" value="<?= set_value('Date[]', date('Y-m-d')); ?>" id="Date<?= $Sr; ?>" class="form-control form-control-sm" title="Date" />
                                            </td>
                                            <td>
                                                <input type="time" name="InTime[]" value="<?= set_value('InTime[]', date('H:i')); ?>" id="InTime<?= $Sr; ?>" class="form-control form-control-sm" title="In Time" />
                                            </td>
                                            <td>
                                                <input type="time" name="OutTime[]" value="<?= set_value('OutTime[]'); ?>" id="OutTime<?= $Sr; ?>" class="form-control form-control-sm" title="Out Time" />
                                            </td>
                                            <td>
                                                <select name="ShiftType[]" id="ShiftType<?= $Sr; ?>" class="form-control form-control-sm" title="Shift Type">
                                                    <option value="" selected="" disabled="">Shift</option>
                                                    <option value="A" <?= set_select('ShiftType[]', 'A'); ?>>A</option>
                                                    <option value="B" <?= set_select('ShiftType[]', 'B'); ?>>B</option>
                                                    <option value="C" <?= set_select('ShiftType[]', 'C'); ?>>C</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="Attendance[]" id="Attendance<?= $Sr; ?>" class="form-control form-control-sm" title="Attendance">
                                                    <option value="" selected="" disabled="">Attendance</option>
                                                    <option value="Present" <?= set_select('Attendance[]', 'Present'); ?>>Present</option>
                                                    <option value="Absent" <?= set_select('Attendance[]', 'Absent'); ?>>Absent</option>
                                                    <option value="Half Day" <?= set_select('Attendance[]', 'Half Day'); ?>>Half Day</option>
                                                    <option value="EL" <?= set_select('Attendance[]', 'EL'); ?>>EL</option>
                                                    <option value="CL" <?= set_select('Attendance[]', 'CL'); ?>>CL</option>
                                                    <option value="Holiday" <?= set_select('Attendance[]', 'Holiday'); ?>>Holiday</option>
                                                    <option value="Leave Without Pay" <?= set_select('Attendance[]', 'Leave Without Pay'); ?>>Leave Without Pay</option>
                                                    <option value="Leave With Pay" <?= set_select('Attendance[]', 'Leave With Pay'); ?>>Leave With Pay</option>
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
                                <button type="submit" class="btn btn-sm bg-purple">Take Attendance</button>
                                <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>