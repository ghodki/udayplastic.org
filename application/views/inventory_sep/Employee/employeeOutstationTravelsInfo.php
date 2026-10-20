<div class="modal-header">
    <h5 class="modal-title">Employees Outstation Travels Info</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Date</th>
                            <td><?= date('d-m-Y', strtotime($employeeTravelsInfo->date)); ?></td>
                            <th>Employee Name</th>
                            <td><?= get_title('employees', ['Id' => $employeeTravelsInfo->employeeId], 'Name'); ?></td>
                            <th>Designation</th>
                            <td><?= get_title('position', ['Id' => get_title('employees', ['Id' => $employeeTravelsInfo->employeeId], 'RoleId')], 'PostName'); ?></td>
                        </tr>
                        <tr>
                            <th>Location/Place of Posting</th>
                            <td><?= $employeeTravelsInfo->location; ?> - <?= get_title('state_list', ['id' => $employeeTravelsInfo->stateId], 'state'); ?></td>
                            <th>Month</th>
                            <td><?= $employeeTravelsInfo->month; ?></td>
                            <th>Band</th>
                            <td><?= $employeeTravelsInfo->band; ?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td><?= $employeeTravelsInfo->department; ?></td>
                            <th>Reporting Senior</th>
                            <td><?= $employeeTravelsInfo->reportingSenior; ?></td>
                            <th>Head Quarter</th>
                            <td><?= $employeeTravelsInfo->headQuarter; ?></td>                            
                        </tr>
                        <tr>
                            <td colspan="6">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="bg-fuchsia">
                                            <tr>
                                                <th rowspan="3" class="text-white">Sr.</th>
                                                <th rowspan="3" class="text-white">Date</th>
                                                <th colspan="2" class="text-white text-center">Places Visited</th>
                                                <th colspan="8" class="text-white text-center">Mode Of Travel</th>
                                                <th colspan="2" class="text-white text-center">Outstation</th>
                                                <th colspan="3" class="text-white text-center">Other Misc. Expenses</th>
                                                <th rowspan="3" class="text-white">Total Amount</th>
                                                <th rowspan="3" class="text-white">Remark</th>
                                                <th rowspan="3" class="text-white">Created</th>
                                                <th rowspan="3" class="text-white">Created By</th>
                                                <th rowspan="3" class="text-white">Updated</th>
                                                <th rowspan="3" class="text-white">Updated By</th>                                                
                                            </tr>
                                            <tr>
                                                <th rowspan="2" class="text-white">From</th>
                                                <th rowspan="2" class="text-white">To</th>
                                                <th rowspan="2" class="text-white">Bus</th>
                                                <th colspan="5" class="text-white text-center">Private Own Vehicle</th>
                                                <th rowspan="2" class="text-white">Railway</th>
                                                <th rowspan="2" class="text-white">Auto</th>
                                                <th rowspan="2" class="text-white">Hotel Accommodation</th>
                                                <th rowspan="2" class="text-white">Food</th>
                                                <th rowspan="2" class="text-white">Internet/Fax</th>
                                                <th rowspan="2" class="text-white">Xerox/Print</th>
                                                <th rowspan="2" class="text-white">Post/Courier</th>
                                            </tr>
                                            <tr>
                                                <th class="text-white">Start KM (A)</th>
                                                <th class="text-white">End KM (B)</th>
                                                <th class="text-white">Total KM (B - A)</th>
                                                <th class="text-white">Amount</th>
                                                <th class="text-white">Car/Bike Model Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($employeeTravelsData) && !empty($employeeTravelsData)) {
                                                    $sr = 0;
                                                    $totalBus = 0;
                                                    $totalStartKM = 0;
                                                    $totalEndKM = 0;
                                                    $totalTotalKM = 0;
                                                    $totalAmount = 0;
                                                    $totalRailway = 0;
                                                    $totalAuto = 0;
                                                    $totalHotel = 0;
                                                    $totalFood = 0;
                                                    $totalInternetFax = 0;
                                                    $totalXeroxPrint = 0;
                                                    $totalPostCourier = 0;
                                                    $grandTotalAmount = 0;
                                                    foreach ($employeeTravelsData as $key => $travelsData) {
                                                        $sr++;
                                                        $totalBus += $travelsData->bus;
                                                        $totalStartKM += $travelsData->startKM;
                                                        $totalEndKM += $travelsData->endKM;
                                                        $totalTotalKM += $travelsData->totalKM;
                                                        $totalAmount += $travelsData->amount;
                                                        $totalRailway += $travelsData->railway;
                                                        $totalAuto += $travelsData->auto;
                                                        $totalHotel += $travelsData->hotel;
                                                        $totalFood += $travelsData->food;
                                                        $totalInternetFax += $travelsData->internetFax;
                                                        $totalXeroxPrint += $travelsData->xeroxPrint;
                                                        $totalPostCourier += $travelsData->postCourier;
                                                        $grandTotalAmount += $travelsData->totalAmount;
                                                        
                                            ?>
                                            <tr>
                                                <td><?= $sr; ?></td>
                                                <td><?= date('d-m-Y', strtotime($travelsData->travelDate)); ?></td>
                                                <td><?= $travelsData->from; ?></td>
                                                <td><?= $travelsData->to; ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->bus); ?></td>
                                                <td><?= $travelsData->startKM; ?></td>
                                                <td><?= $travelsData->endKM; ?></td>
                                                <td><?= $travelsData->totalKM; ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->amount); ?></td>
                                                <td><?= $travelsData->modelType; ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->railway); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->auto); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->hotel); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->food); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->internetFax); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->xeroxPrint); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->postCourier); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->totalAmount); ?></td>
                                                <td><?= $travelsData->remark ?></td>
                                                <td><?= $travelsData->created; ?></td>
                                                <td><?= $travelsData->createdBy; ?></td>
                                                <td><?= $travelsData->updated; ?></td>
                                                <td><?= $travelsData->updatedBy; ?></td>                                                
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" style="text-align: right;">Total</th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalBus); ?></th>
                                                <th><?= $totalStartKM; ?></th>
                                                <th><?= $totalEndKM; ?></th>
                                                <th><?= $totalTotalKM; ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
                                                <th></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalRailway); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAuto); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalHotel); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalFood); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalInternetFax); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalXeroxPrint); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalPostCourier); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($grandTotalAmount); ?></th>
                                                <th colspan="5"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td><?= get_title('users', ['id' => $employeeTravelsInfo->userId], 'name'); ?></td>
                            <th>Company</th>
                            <td><?= get_title('inventory_billing', ['id' => $employeeTravelsInfo->compId], 'company_name'); ?></td>
                            <th>Company Branch</th>
                            <td><?= get_title('companybranch', ['Id' => $employeeTravelsInfo->compBranchId], 'BranchName'); ?></td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?= $employeeTravelsInfo->created; ?></td>
                            <th>Created By</th>
                            <td><?= $employeeTravelsInfo->createdBy; ?></td>
                            <th>Updated</th>
                            <td><?= $employeeTravelsInfo->updated; ?></td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td><?= $employeeTravelsInfo->updatedBy; ?></td>
                            <td colspan="4"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>