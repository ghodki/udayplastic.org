<div class="modal-header">
    <h5 class="modal-title">Leave Request Information</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed request-data">
                    <tr>
                        <th>Date</th>
                        <td><?= date('d-m-Y', strtotime($LeaveRequestData->Date)); ?></td>
                        <th>Employee Name</th>
                        <td><?= get_title('employees', ['Id' => $LeaveRequestData->EmployeeId], 'Name'); ?></td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td><?= date('d-m-Y', strtotime($LeaveRequestData->StartDate)); ?></td>
                        <th>End Date</th>
                        <td><?= date('d-m-Y', strtotime($LeaveRequestData->EndDate)); ?></td>
                    </tr>
                    <tr>
                        <th>Days</th>
                        <td><?= DifferenceDays($LeaveRequestData->StartDate, $LeaveRequestData->EndDate); ?></td>
                        <th>Remark</th>
                        <td><?= $LeaveRequestData->Remark ?></td>
                    </tr>
                </table>
            </div>
            <hr />
            <form action="<?= base_url(); ?>Reports/UpdateLeaveRequest" method="POST">
                <input type="hidden" name="LeaveId" value="<?= set_value('LeaveId', $LeaveRequestData->Id); ?>" id="LeaveId" />
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-lg-3">
                        <div class="form-group">
                            <label for="Status">Status <span class="text-danger">*</span></label>
                            <br />
                            <input type="radio" name="Status" value="2" <?= set_radio('Status', 2); ?> id="Approved" <?= $LeaveRequestData->Status == 2 ? "checked" : ""; ?> title="Approved" required="" />
                            <label>Approved</label>
                            <input type="radio" name="Status" value="0" <?= set_radio('Status', 0); ?> id="Rejected" <?= $LeaveRequestData->Status == 0 ? "checked" : ""; ?> class="ml-2" title="Rejected" required="" />
                            <label>Rejected</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="AdminRemark">Remark</label>
                            <textarea name="AdminRemark" id="AdminRemark" class="form-control" placeholder="Remark" rows="1"><?= set_value('AdminRemark'); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 offset-4">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Submit" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>