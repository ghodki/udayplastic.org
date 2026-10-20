<div class="modal-header">
    <h5 class="modal-title">Employees Travels Info</h5>
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
                            <th>Location/Place of Posting</th>
                            <td><?= $employeeTravelsInfo->location; ?> - <?= get_title('state_list', ['id' => $employeeTravelsInfo->stateId], 'state'); ?></td>
                            <th>Month</th>
                            <td><?= $employeeTravelsInfo->month; ?></td>
                        </tr>
                        <tr>
                            <th>Band</th>
                            <td><?= $employeeTravelsInfo->band; ?></td>
                            <th>Department</th>
                            <td><?= $employeeTravelsInfo->department; ?></td>
                            <th>Head Quarter</th>
                            <td><?= $employeeTravelsInfo->headQuarter; ?></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="bg-danger">
                                            <tr>
                                                <th rowspan="2" class="text-white">Sr.</th>
                                                <th rowspan="2" class="text-white">Date</th>
                                                <th rowspan="2" class="text-white">Senior Employee</th>
                                                <th rowspan="2" class="text-white">Salesman</th>
                                                <th rowspan="2" class="text-white">Places Visited</th>
                                                <th rowspan="2" class="text-white">Daily Allowance Claimed</th>
                                                <th colspan="3" class="text-white text-center">Other Misc. Expenses</th>
                                                <th rowspan="2" class="text-white">Total</th>
                                                <th rowspan="2" class="text-white">Remark</th>
                                                <th rowspan="2" class="text-white">Created</th>
                                                <th rowspan="2" class="text-white">Created By</th>
                                                <th rowspan="2" class="text-white">Updated</th>
                                                <th rowspan="2" class="text-white">Updated By</th>
                                            </tr>
                                            <tr>
                                                 <th class="text-white">Internet/Fax</th>
                                                 <th class="text-white">Xerox/Print</th>
                                                 <th class="text-white">Post/Courier</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sr = 0;
                                                $totalDailyAllowanceClaimed = 0;
                                                $totalInternetFax = 0;
                                                $totalXeroxPrint = 0;
                                                $totalPostCourier = 0;
                                                $totalAmount = 0;
                                                if (isset($employeeTravelsData) && !empty($employeeTravelsData)) {
                                                    foreach ($employeeTravelsData as $key => $travelsData) {
                                                        $sr++;
                                                        $totalDailyAllowanceClaimed += (float) ($travelsData->dailyAllowanceClaimed ?? 0);
                                                        $totalInternetFax += (float) ($travelsData->internetFax ?? 0);
                                                        $totalXeroxPrint += (float) ($travelsData->xeroxPrint ?? 0);
                                                        $totalPostCourier += (float) ($travelsData->postCourier ?? 0);
                                                        $totalAmount += (float) ($travelsData->total ?? 0);
                                            ?>
                                            <tr>
                                                <td><?= $sr; ?></td>
                                                <td><?= date('d-m-Y', strtotime($travelsData->travelDate)); ?></td>
                                                <td><?= get_title('employees', ['Id' => $travelsData->seniorEmployeeId], 'Name'); ?></td>
                                                <td><?= get_title('employees', ['Id' => $travelsData->employeeId], 'Name'); ?></td>
                                                <td><?= $travelsData->placesVisited; ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->dailyAllowanceClaimed); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->internetFax); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->xeroxPrint); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->postCourier); ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($travelsData->total); ?></td>
                                                <td><?= $travelsData->remark; ?></td>
                                                <td><?= $travelsData->created; ?></td>
                                                <td><?= $travelsData->createdBy; ?></td>
                                                <td><?= $travelsData->updated; ?></td>
                                                <td><?= $travelsData->updatedBy; ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" style="text-align: right;">Total</th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalDailyAllowanceClaimed); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalInternetFax); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalXeroxPrint); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalPostCourier); ?></th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
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