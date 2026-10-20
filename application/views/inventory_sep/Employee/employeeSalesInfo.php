<div class="modal-header">
    <h5 class="modal-title">Employees Sales Info</h5>
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
                            <td><?= date('d-m-Y', strtotime($employeeSalesInfo->date)); ?></td>
                            <th>Employee Name</th>
                            <td><?= get_title('employees', ['Id' => $employeeSalesInfo->employeeId], 'Name'); ?></td>
                            <th>Designation</th>
                            <td><?= get_title('position', ['Id' => get_title('employees', ['Id' => $employeeSalesInfo->employeeId], 'RoleId')], 'PostName'); ?></td>
                        </tr>
                        <tr>
                            <th>Base Location</th>
                            <td><?= $employeeSalesInfo->baseLocation; ?></td>
                            <th>Month</th>
                            <td><?= $employeeSalesInfo->month; ?></td>
                            <th>Band</th>
                            <td><?= $employeeSalesInfo->band; ?></td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td><?= get_title('state_list', ['id' => $employeeSalesInfo->stateId], 'code'); ?>-<?= get_title('state_list', ['id' => $employeeSalesInfo->stateId], 'state'); ?></td>
                            <th>Department</th>
                            <td><?= $employeeSalesInfo->department; ?></td>
                            <th>Reporting Senior</th>
                            <td><?= $employeeSalesInfo->reportingSenior; ?></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th class="text-white">Sr.</th>
                                                <th class="text-white">Billing Date</th>
                                                <th class="text-white">Distributor Name</th>
                                                <th class="text-white">Retailer Name</th>
                                                <th class="text-white">Location</th>
                                                <th class="text-white">Contact Person</th>
                                                <th class="text-white">Mobile No</th>
                                                <th class="text-white">Bill Value</th>
                                                <th class="text-white">Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($employeeSalesParties) && !empty($employeeSalesParties)) {
                                                    $sr = 0;
                                                    $totalAmount = 0;
                                                    foreach ($employeeSalesParties as $key => $salesData) {
                                                        $sr++;
                                                        $totalAmount += $salesData->netSale;
                                            ?>
                                            <tr>
                                                <td><?= $sr; ?></td>
                                                <td><?= date('d-m-Y', strtotime($salesData->billingDate)); ?></td>
                                                <td><?= $salesData->distributorName; ?></td>
                                                <td><?= $salesData->retailerName; ?></td>
                                                <td><?= $salesData->location; ?></td>
                                                <td><?= $salesData->contactPerson; ?></td>
                                                <td><?= $salesData->mobileNumber; ?></td>
                                                <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($salesData->netSale); ?></td>
                                                <td><?= $salesData->remark; ?></td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="7" style="text-align: right;">Total</th>
                                                <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td><?= get_title('users', ['id' => $employeeSalesInfo->userId], 'name'); ?></td>
                            <th>Company</th>
                            <td><?= get_title('inventory_billing', ['id' => $employeeSalesInfo->compId], 'company_name'); ?></td>
                            <th>Company Branch</th>
                            <td><?= get_title('companybranch', ['Id' => $employeeSalesInfo->compBranchId], 'BranchName'); ?></td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?= $employeeSalesInfo->created; ?></td>
                            <th>Created By</th>
                            <td><?= $employeeSalesInfo->createdBy; ?></td>
                            <th>Updated</th>
                            <td><?= $employeeSalesInfo->updated; ?></td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td><?= $employeeSalesInfo->updatedBy; ?></td>
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