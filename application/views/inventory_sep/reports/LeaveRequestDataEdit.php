<div class="modal-header">
    <h5 class="modal-title">Edit Leave Request</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <form action="<?= base_url(); ?>Reports/UpdateLeaveRequestData" method="POST">
        <input type="hidden" name="LeaveId" value="<?= set_value('LeaveId', $LeaveRequestData->Id); ?>" id="LeaveId" />
        <div class="row">
            <div class="col-md-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <label for="StartDate">Start Date <span class="text-danger">*</span></label>
                    <input type="Date" name="StartDate" id="StartDate" value="<?= set_value('StartDate', $LeaveRequestData->StartDate); ?>" class="form-control form-control-sm" title="Start Date" required="" />
                    <span class="text-danger"><?= form_error('StartDate'); ?></span>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <label for="EndDate">End Date <span class="text-danger">*</span></label>
                    <input type="Date" name="EndDate" id="EndDate" value="<?= set_value('EndDate', $LeaveRequestData->EndDate); ?>" class="form-control form-control-sm" title="End Date" required="" />
                    <span class="text-danger"><?= form_error('EndDate'); ?></span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label for="Remark">Remark</label>
                    <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="2"><?= set_value('Remark', $LeaveRequestData->Remark); ?></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 offset-4">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Update" />
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>