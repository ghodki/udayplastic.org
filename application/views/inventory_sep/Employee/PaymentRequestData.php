<div class="modal-header">
    <h5 class="modal-title" id="title">Request Information</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>Request Date</th>
                        <td><?= date('d-m-Y', strtotime($PaymentRequest->date)); ?></td>
                        <th>Invoice Number</th>
                        <td>
                            <?php
                                $invoiceNumbers = json_decode($PaymentRequest->invoiceNumber ?? '', true);
                                if (!is_array($invoiceNumbers)) {
                                    $invoiceNumbers = [];
                                }
                                if (!empty($invoiceNumbers)) {
                                    $InvoiceNumber = "";
                                    foreach ($invoiceNumbers as $key => $InvoceData) {
                                        $InvoiceNumber .= $InvoceData . ", ";
                                    }
                                    echo rtrim($InvoiceNumber, ", ");
                                } else {
                                    echo $PaymentRequest->invoiceNumber ?? '';
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Party Name</th>
                        <td><?= get_title('parties', ['id' => $PaymentRequest->party_id], 'name'); ?></td>
                        <th>Payment Date</th>
                        <td><?= date('d-m-Y', strtotime($PaymentRequest->payment_date)); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Mode</th>
                        <td>
                            <?= get_title('payment_mode', ['id' => $PaymentRequest->payment_mode], 'payment_method'); ?>
                            <?= $PaymentRequest->ChequeDate ? " <small>(" . date('d-m-Y', strtotime($PaymentRequest->ChequeDate)) . ")</small>" : ""; ?>
                        </td>
                        <th>Payment Amount</th>
                        <td><i class="fa fa-inr"></i> <?= number_format($PaymentRequest->payment_amount, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($PaymentRequest->status == 0) { ?>
                            <label class="btn btn-warning btn-xs"><i class="fa fa-warning"></i> Pending</label>
                            <?php } elseif ($PaymentRequest->status == 1) { ?>
                            <label class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> Approved</label>
                            <?php } elseif ($PaymentRequest->status == 2) { ?>
                            <label class="btn btn-danger btn-xs"><i class="fa fa-ban"></i> Rejected</label>
                            <?php } ?>
                        </td>
                        <th>Created</th>
                        <td><?= $PaymentRequest->created; ?></td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td><?= $PaymentRequest->created_by; ?></td>
                        <th>Remark</th> 
                        <td style="white-space: unset;"><?= $PaymentRequest->remark; ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?= $PaymentRequest->updated; ?></td>
                        <th>Updated By</th>
                        <td><?= $PaymentRequest->updated_by; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>