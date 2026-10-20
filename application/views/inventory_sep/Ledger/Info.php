<div class="modal-header">
    <h5 class="modal-title">Ledger Entry Info</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-condensed">
                    <tr>
                        <th>Date</th>
                        <td><?= date('d-m-Y', strtotime($LedgerData->Date)); ?></td>
                    </tr>
                    <tr>
                        <th>Account Group</th>
                        <td><?= get_title('accounting_group', ['id' => $LedgerData->AccountGroupId], 'name'); ?></td>
                    </tr>
                    <tr>
                        <th>Ledger</th>
                        <td><?= get_title('ladger', ['id' => $LedgerData->LedgerId], 'name'); ?></td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td><i class="fa fa-inr"></i> <?= number_format($LedgerData->Amount, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td><?= $LedgerData->Type; ?></td>
                    </tr>
                    <tr>
                        <th>Payment Mode</th>
                        <td><?= get_title('payment_mode', ['id' => $LedgerData->PaymentMode], 'payment_method'); ?></td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td><?= $LedgerData->Remark; ?></td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= $LedgerData->Created; ?></td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td><?= $LedgerData->CreatedBy; ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?= $LedgerData->Updated; ?></td>
                    </tr>
                    <tr>
                        <th>Updated By</th>
                        <td><?= $LedgerData->UpdatedBy; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>