<div class="modal-header">
    <h5 class="modal-title" id="title">Cheque Information</h5>
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
                        <th>Invoice Date</th>
                        <td><?= date('d-m-Y', strtotime($SaleInfo->invoiceDate)); ?></td>
                        <th>Invoice Number</th>
                        <td><?= $SaleInfo->invoiceNumber; ?></td>
                    </tr>
                    <tr>
                        <th>Invoice Type</th>
                        <td><?= $SaleInfo->type; ?></td>
                        <th>Customer Name</th>
                        <td><?= get_title('parties', ['id' => $SaleInfo->customerId], 'name'); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Mode</th>
                        <td><?= get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method'); ?></td>
                        <th>Salesman</th> 
                        <td><?= get_title('employees', ['Id' => $SaleInfo->SalesmanId], 'Name'); ?></td>
                    </tr>
                </table>
            </div>
            
            <hr class="bg-navy" />
            <form action="<?= base_url(); ?>Sale/ChequeStateUpdate" method="POST">
                <input type="hidden" name="SaleId" value="<?= set_value('SaleId', $SaleInfo->id); ?>" />
                <input type="hidden" name="Type" value="<?= set_value('Type', $SaleInfo->type); ?>" />
                <input type="hidden" name="PaymentId" value="<?= set_value('PaymentId', $PaymentInfo->id); ?>" />
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="form-group">
                            <label for="PaymentDate" class="text-dark">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="PaymentDate" value="<?= set_value('PaymentDate', $PaymentInfo->date); ?>" id="PaymentDate" class="form-control form-control-sm" title="Payment Date" required="" />
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="form-group">
                            <label for="ChequeDate" class="text-dark">Cheque Date <span class="text-danger">*</span></label>
                            <input type="date" name="ChequeDate" value="<?= set_value('ChequeDate', $PaymentInfo->ChequeDate); ?>" id="ChequeDate" class="form-control form-control-sm" title="Cheque Date" required="" />
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="form-group">
                            <label for="Amount" class="text-dark">Payment Amount <span class="text-danger">*</span></label>
                            <input type="text" name="Amount" value="<?= set_value('Amount', $PaymentInfo->debit); ?>" id="Amount" class="form-control form-control-sm" placeholder="Payment Amount" required="" readonly="" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                        <div class="form-group">
                            <label for="ChequeStatus" class="text-dark">Cheque Status <span class="text-danger">*</span></label>
                            <br />
                            <input type="radio" name="ChequeStatus" value="1" <?= set_radio('ChequeStatus', 1); ?> id="Clear" checked="" title="Clear" required="" />
                            <label class="text-dark">Cheque Clear</label>
                            <input type="radio" name="ChequeStatus" value="0" <?= set_radio('ChequeStatus', 0); ?> id="Rejected" class="ml-2" title="Rejected" required="" />
                            <label class="text-dark">Cheque Rejected</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6">
                        <div class="form-group">
                            <label for="Remark" class="text-dark">Payment Remark</label>
                            <textarea name="Remark" id="Remark" class="form-control" placeholder="Payment Remark" rows="1"><?= set_value('Remark', $PaymentInfo->remark); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 offset-4 mt-2">
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