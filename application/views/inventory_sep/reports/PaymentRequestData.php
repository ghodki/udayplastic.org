<div class="modal-header">
    <h5 class="modal-title" id="title">Request Information</h5>
    <button class="btn btn-success btn-sm" onclick="onPartyPaymentStatement();" style="margin-left: 100px;">
        payment Statement
    </button>
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
                        <th>Request Date</th>
                        <td><?= date('d-m-Y', strtotime($PaymentRequest->date)); ?></td>
                        <th>Invoice Number</th>
                        <td>
                            <?php 
                                if (is_array(json_decode($PaymentRequest->invoiceNumber))) {
                                    $InvoiceNumber = "";
                                    foreach (json_decode($PaymentRequest->invoiceNumber) as $key => $InvoceData) {
                                        $InvoiceNumber .= $InvoceData . ", ";
                                    }
                                    echo substr(trim($InvoiceNumber), 0, -1);
                                } else {
                                    echo $PaymentRequest->invoiceNumber;
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>
                            <?php
                                if (is_array(json_decode($PaymentRequest->Type))) {
                                    if (in_array('Sale', json_decode($PaymentRequest->Type))) {
                                        echo "Sale";
                                    }
                                    if (in_array('Estimate', json_decode($PaymentRequest->Type))) {
                                        echo "Estimate";
                                    }
                                } else {
                                    echo $PaymentRequest->Type;
                                }
                            ?>
                        </td>
                        <th>Invoice Amount</th>
                        <td>
                            <i class="fa fa-inr"></i> 
                            <?php
                                if (is_array(json_decode($PaymentRequest->Type))) {
                                    if (in_array("Sale", json_decode($PaymentRequest->Type)) || in_array("Estimate", json_decode($PaymentRequest->Type))) {
                                        $InvoicesRecord = json_decode($PaymentRequest->invoiceId);
                                        $SaleInvoiceTotal = "";
                                        foreach ($InvoicesRecord as $key => $data) {
                                            if (in_array("Sale", json_decode($PaymentRequest->Type))) {
                                                $SaleInvoiceTotal .= get_title('sales', ['id' => $data], 'total'). ", ";
                                            }
                                            if (in_array("Estimate", json_decode($PaymentRequest->Type))) {
                                                $SaleInvoiceTotal .= get_title('estimates', ['id' => $data], 'total') . ", ";
                                            }
                                        }
                                        echo indianMoneyFormat(substr(trim($SaleInvoiceTotal), 0, -1));
                                    }
                                } else {
                                    if ($PaymentRequest->Type == 'Sale') {
                                        echo indianMoneyFormat(get_title('sales', ['id' => $PaymentRequest->invoiceId], 'total'));
                                    } elseif ($PaymentRequest->Type == 'Estimate') {
                                        echo indianMoneyFormat(get_title('estimates', ['id' => $PaymentRequest->invoiceId], 'total'));
                                    }
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
                        <td><?= get_title('payment_mode', ['id' => $PaymentRequest->payment_mode], 'payment_method'); ?></td>
                        <th>Payment Amount</th>
                        <td><i class="fa fa-inr"></i> <?= number_format($PaymentRequest->payment_amount, 2); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($PaymentRequest->status == 0) { ?>
                            <label class="btn btn-warning btn-xs"><i class="fa fa-warning"></i> Pending</label>
                            <?php } elseif ($PaymentRequest->status == 1) { ?>
                            <label class="btn btn-success btn-xs"><i class="fa fa-check-circle text-white"></i> Approved</label>
                            <?php } elseif ($PaymentRequest->status == 2) { ?>
                            <label class="btn btn-danger btn-xs"><i class="fa fa-ban text-white"></i> Rejected</label>
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
            <hr />
            <?php if ($PaymentRequest->status == 0) { ?>
            <form method="POST" id="PaymentRequest" onsubmit="onPaymentRequestFromSubmitHandler(event);">
                <input type="hidden" name="RequestId" value="<?= set_value('RequestId', $PaymentRequest->id); ?>" />
                <input type="hidden" name="InvoiceId" value="<?= set_value('InvoiceId', $PaymentRequest->invoiceId); ?>" />
                <input type="hidden" name="Type" value="<?= set_value('Type', $PaymentRequest->Type); ?>" />
                <input type="hidden" name="PartyId" value="<?= set_value('PartyId', $PaymentRequest->party_id); ?>" id="PartyId" />
                <input type="hidden" name="InvoiceNumber" value="<?= set_value('InvoiceNumber', $PaymentRequest->invoiceNumber); ?>" />
                <input type="hidden" name="PaymentModeId" value="<?= set_value('PaymentModeId', $PaymentRequest->payment_mode); ?>" />
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="form-group">
                            <label for="PaymentDate">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="PaymentDate" value="<?= set_value('PaymentDate', $PaymentRequest->payment_date); ?>" id="PaymentDate" class="form-control form-control-sm" title="Payment Date" required="" />
                        </div>
                    </div>
                    <?php if (get_title('payment_mode', ['id' => $PaymentRequest->payment_mode], 'payment_method') == "Cheque") { ?>
                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="form-group">
                            <label for="ChequeDate">Cheque Date <span class="text-danger">*</span></label>
                            <input type="date" name="ChequeDate" value="<?= set_value('ChequeDate', $PaymentRequest->ChequeDate); ?>" id="ChequeDate" class="form-control form-control-sm" title="Cheque Date" required="" />
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                        <div class="form-group">
                            <label for="receivedPaidAmount">Received Paid Amount <span class="text-danger">*</span></label>
                            <input type="number" name="receivedPaidAmount" value="<?= set_value('receivedPaidAmount', $PaymentRequest->payment_amount); ?>" id="receivedPaidAmount" class="form-control form-control-sm" min="0" placeholder="Received Paid Amount" step="0.1" onchange="calculatePaidAmount();" oninput="calculatePaidAmount();" required="" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-lg-3">
                        <div class="form-group">
                            <label for="cashDiscountAmount">Cash Discount Amount</label>
                            <input type="number" name="cashDiscountAmount" value="<?= set_value('cashDiscountAmount'); ?>" id="cashDiscountAmount" class="form-control form-control-sm" min="0" placeholder="Cash Discount Amount" step="0.1" onchange="calculatePaidAmount();" oninput="calculatePaidAmount();" />
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="form-group">
                            <label for="Amount">Payment Amount <span class="text-danger">*</span></label>
                            <input type="text" name="Amount" value="<?= set_value('Amount', $PaymentRequest->payment_amount); ?>" id="Amount" class="form-control form-control-sm" placeholder="Payment Amount" required="" readonly="" />
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="form-group">
                            <label for="status">Payment Status <span class="text-danger">*</span></label>
                            <br />
                            <input type="radio" name="Status" value="1" <?= set_radio('status', 1); ?> id="Approved" class="Status" checked="" title="Approved" required="" />
                            <label>Approved</label>
                            <br />
                            <input type="radio" name="Status" value="2" <?= set_radio('status', 2); ?> id="Rejected" class="Status" title="Rejected" required="" />
                            <label>Rejected</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Remark">Payment Remark</label>
                            <textarea name="Remark" id="Remark" class="form-control" placeholder="Payment Remark" rows="1"><?= set_value('Remark'); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-3 mt-4">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Submit" id="submitButton" />
                        </div>
                    </div>
                </div>
            </form>
            <?php } elseif ($PaymentRequest->status == 1 || $PaymentRequest->status == 2) { ?>
            <label class="text-warning text-lg text-center">Request Status Updated.</label>
            <?php } ?>
        </div>
        <div class="col-md-12">
            <div class="table-responsive" id="PartyPaymentStatementData"></div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>