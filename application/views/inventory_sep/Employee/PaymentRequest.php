<form action="<?= base_url(); ?>Employee/PaymentRequest" method="POST">
    <input type="hidden" name="InvoiceId" value="<?= set_value('InvoiceId', $InvoiceData->id); ?>" id="InvoiceId" />
    <input type="hidden" name="PartyId" value="<?= set_value('PartyId', $InvoiceData->customerId); ?>" id="PartyId" />
    <div class="modal-header">
        <h5 class="modal-title" id="PaymentRequest">
            Payment Request
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                <div class="form-group">
                    <label for="invoiceDate">Invoice Date</label>
                    <input type="text" name="invoiceDate" value="<?= set_value('invoiceDate', date('d-m-Y', strtotime($InvoiceData->invoiceDate))); ?>" id="invoiceDate" class="form-control form-control-sm" placeholder="Invoice Date" readonly="" />
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                <div class="form-group">
                    <label for="Type">Type</label>
                    <input type="text" name="Type" value="<?= set_value('Type', $InvoiceData->type); ?>" id="Type" class="form-control form-control-sm" placeholder="Type" readonly="" />
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                <div class="form-group">
                    <label for="invoiceNumber">Invoice Number</label>
                    <input type="text" name="invoiceNumber" value="<?= set_value('invoiceNumber', $InvoiceData->invoiceNumber); ?>" id="invoiceNumber" class="form-control form-control-sm" placeholder="Invoice Number" readonly="" />
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                <div class="form-group">
                    <label for="total">Invoice Total</label>
                    <input type="text" name="total" value="<?= set_value('total', $InvoiceData->total); ?>" id="total" class="form-control form-control-sm" placeholder="Invoice Total" readonly="" />
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                <div class="form-group">
                    <label for="paidAmount">Paid Amount</label>
                    <input type="text" name="paidAmount" value="<?= set_value('paidAmount', $InvoiceData->paid_amount); ?>" id="paidAmount" class="form-control form-control-sm" placeholder="Paid Amount" readonly="" />
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                <div class="form-group">
                    <label for="balanceAmount">Balance Amount</label>
                    <input type="text" name="balanceAmount" value="<?= set_value('balanceAmount', round(($InvoiceData->total - $InvoiceData->paid_amount), 2)); ?>" id="balanceAmount" class="form-control form-control-sm" placeholder="Balance Amount" readonly="" />
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-6 col-xs-6 col-xxl-6">
                <div class="form-group">
                    <label for="party_name">Party Name</label>
                    <input type="text" name="party_name" value="<?= set_value('party_name', get_title('parties', ['id' => $InvoiceData->customerId], 'name')); ?>" id="party_name" class="form-control form-control-sm" placeholder="Party Name" readonly="" />
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                <div class="form-group">
                    <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" value="<?= set_value('payment_date', date('Y-m-d')); ?>" id="payment_date" class="form-control form-control-sm" title="Payment Date" required="" />
                </div>
            </div>
           <div class="col-md-2 col-sm-2 col-lg-2 col-xl-2 col-xs-2 col-xxl-2">
                <div class="form-group">
                    <label for="payment_mode">Payment Mode <span class="text-danger">*</span></label>
                    <select name="payment_mode" id="payment_mode" class="form-control form-control-sm" title="Payment Mode" onchange="ifCheque();" required="">
                        <option value="" selected="" disabled="">Select Payment Mode</option>
                        <?php if ($PaymentMode) { foreach ($PaymentMode as $key => $Data) { ?>
                        <option value="<?= $Data->id; ?>" <?= set_select('payment_mode', $Data->id); ?>><?= $Data->payment_method; ?></option>
                        <?php } } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-lg-2 col-xl-2 col-xs-2 col-xxl-2 Cheque d-none">
                <div class="form-group">
                    <label for="ChequeDate">Cheque Date</label>
                    <input type="date" name="ChequeDate" value="<?= set_value('ChequeDate'); ?>" id="ChequeDate" class="form-control form-control-sm" title="Cheque Date" />
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-lg-2 col-xl-2 col-xs-2 col-xxl-2">
                <div class="form-group">
                    <label for="payment_amount">Payment Amount <span class="text-danger">*</span></label>
                    <input type="number" name="payment_amount" value="<?= set_value('payment_amount'); ?>" id="payment_amount" class="form-control form-control-sm" placeholder="Payment Amount" required="" />
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
                <div class="form-group">
                    <label for="remark">Remark</label>
                    <textarea name="remark" id="remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('remark'); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success" type="submit">Submit Request</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
    </div>    
</form>