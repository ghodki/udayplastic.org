<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .dt-buttons {
        left: 0%;
    }
</style>

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
            </div>
            <div class="col-md-12 col-xl-12 col-lg-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="text-info">
                                Attendance Report
                            </h5>
                        </div>
                        <a href="<?= base_url(); ?>Employee/EMPAttendanceCreate" class="btn btn-sm btn-info pull-right" title="Set Attendance">
                            <i class="fa fa-check-circle"></i> Set Attendance
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead class="bg-info">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Shift</th>
                                        <th>Attendance</th>
                                        <th>Working Hours</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if (isset($EmployeesAttendance) && $EmployeesAttendance != '') {
                                            $Sr = 0;
                                            foreach ($EmployeesAttendance as  $key => $Attendance) {
                                                $Sr++;
                                                $InTime = '';
                                                $OutTime = '';
                                                if ($Attendance->InTime) {
                                                    $InTime = date("H:i", strtotime($Attendance->InTime));
                                                }
                                                if ($Attendance->OutTime) {
                                                    $OutTime = date("H:i", strtotime($Attendance->OutTime));
                                                }
                                    ?>
                                    <tr>
                                        <td><?= $Sr; ?></td>
                                        <td><?= date('d-m-Y', strtotime($Attendance->Date)); ?></td>
                                        <td><?= $InTime ? $InTime : '<i style="color: #ff0000;" class="fa fa-ban"></i>'; ?></td>
                                        <td><?= $OutTime ? $OutTime : '<i style="color: #ff0000;" class="fa fa-ban"></i>'; ?></td>
                                        <td><?= $Attendance->ShiftType ? $Attendance->ShiftType : '<i style="color: #ff0000;" class="fa fa-ban"></i>'; ?></td>
                                        <td><?= $Attendance->Attendance; ?></td>
                                        <td><?= differenceInHours($Attendance->InTime, $Attendance->OutTime); ?></td>
                                        <td>
                                            <?php if (($Attendance->Attendance == 'Half Day') || ($Attendance->Attendance == 'Present')) { if (empty($Attendance->OutTime)) { ?>
                                            <a href="<?= base_url(); ?>Employee/EMPAttendanceEdit/<?= $Attendance->Id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                Out Time
                                            </a>
                                            <?php } } ?>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">    
    $(function() {
        $("#dataTable").DataTable({
            dom: 'Bfrtip',
            buttons: [
                'pageLength'
            ]
        });
    });
</script>