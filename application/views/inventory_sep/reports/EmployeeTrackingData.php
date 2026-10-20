<div class="modal-header">
    <h5 class="modal-title">Information</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 col-xl-12 col-xxl-12">
            <div class="table-responsive">
                <?php if (!empty($EmployeeId) && !empty($EmployeeData)) { ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <td><?= get_title('employees', ['Id' => $EmployeeData->EmpId], 'Name'); ?></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?= date('d-m-Y', strtotime($EmployeeData->Date)); ?></td>
                        </tr>
                        <tr>
                            <th>In Time</th>
                            <td><?= $EmployeeData->InTime; ?></td>
                        </tr>
                        <tr>
                            <th>Out Time</th>
                            <td><?= $EmployeeData->OutTime; ?></td>
                        </tr>
                        <tr>
                            <th>Working Hours</th>
                            <td><?= !empty($EmployeeData->WorkingHours) ? $EmployeeData->WorkingHours : differenceInHours($EmployeeData->InTime, date('H:i')); ?></td>
                        </tr>
                        <tr>
                            <th>Traveling In KM</th>
                            <td>
                                <?php
                                    $EmployeeTravelQuery = $this->db->select('StartLatitude, StartLongitude, EndLatitude, EndLongitude')
                                        ->where(['EmployeeId' => $EmployeeId, 'Date >=' => $Date, 'Date <=' => $Date])
                                        ->order_by('Id', 'ASC')
                                        ->get('employee_tracking');

                                    if ($EmployeeTravelQuery->num_rows()) {
                                        $TravelData = $EmployeeTravelQuery->result();

                                        $totalDistance = 0;

                                        foreach ($TravelData as $travelInfo) {
                                            // Ensure all coordinates exist
                                            if (!empty($travelInfo->StartLatitude) && !empty($travelInfo->StartLongitude) &&
                                                !empty($travelInfo->EndLatitude) && !empty($travelInfo->EndLongitude)) {

                                                $distance = haversineDistance(
                                                    $travelInfo->StartLatitude, $travelInfo->StartLongitude,
                                                    $travelInfo->EndLatitude, $travelInfo->EndLongitude
                                                );

                                                $totalDistance += $distance;
                                            }
                                        }

                                        echo number_format($totalDistance, 2) . ' KM';
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Attendance</th>
                            <td><?= $EmployeeData->Attendance; ?></td>
                        </tr>
                        <tr>
                            <th>Selfie</th>
                            <td>
                                <?php if (!empty($EmployeeData->SelfieImage)) { ?>
                                <img src="<?= base_url() . 'uploads/Employee/Selfie/' . $EmployeeData->SelfieImage; ?>" alt="selfie" style="width: 100px; border-radius: 25px;" />
                                <?php } else { ?>
                                <img src="<?= base_url() . 'assets/images/avatar.png'; ?>" alt="avatar" style="width: 100px; border-radius: 25px;" />
                                <?php } ?>
                            </td>
                        </tr>
                    </thead>
                </table>
                <?php } else { ?>
                <p class="text-danger">No Record found.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>