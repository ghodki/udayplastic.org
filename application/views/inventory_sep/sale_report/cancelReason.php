<form onsubmit="onCancelReasonFormSubmitHandler(event);" method="POST" id="cancelInvoiceForm" autocomplete="off">
    <input type="hidden" name="invoiceId" value="<?= set_value('invoiceId', $invoiceId); ?>" id="invoiceId" />
    <div class="modal-header">
        <h5 class="modal-title" id="title">Cancel Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="form-group">
                    <label for="remark" class="text-dark">Cancel Reason</label>
                    <textarea name="remark" id="remark" class="form-control" placeholder="Cancel Reason" rows="2" required=""><?= set_value('remark', $invoiceData->remark); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?php if ($invoiceData->status === "1") { ?>
        <button type="submit" class="btn btn-success btn-xs" id="cancelInvoiceButton">Yes, Cancel</button>
        <?php } ?>
        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
    </div>
</form>