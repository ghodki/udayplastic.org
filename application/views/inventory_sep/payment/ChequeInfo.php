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
                        <td><?= date('d-m-Y', strtotime($purchaseInfo->invoiceDate)); ?></td>
                        <th>Invoice Number</th>
                        <td><?= $purchaseInfo->invoiceNumber; ?></td>
                    </tr>
                    <tr>
                        <th>Invoice Type</th>
                        <td><?= $purchaseInfo->type; ?></td>
                        <th>Customer Name</th>
                        <td><?= get_title('parties', ['id' => $purchaseInfo->customerId], 'name'); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Mode</th>
                        <td><?= get_title('payment_mode', ['id' => $paymentInfo->paymentmode], 'payment_method'); ?></td>
                        <th></th> 
                        <td></td>
                    </tr>
                </table>
            </div>
            
            <hr class="bg-navy" />
            <form id="chequeForm" method="POST" onsubmit="onPaymentChequeFormSubmitHandler(event);">
                <input type="hidden" name="invoiceId" value="<?= set_value('invoiceId', $purchaseInfo->id); ?>" id="invoiceId" />
                <input type="hidden" name="invoiceType" value="<?= set_value('invoiceType', $purchaseInfo->type); ?>" id="invoiceType" />
                <input type="hidden" name="paymentId" value="<?= set_value('paymentId', $paymentInfo->id); ?>" id="paymentId" />
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="form-group">
                            <label for="PaymentDate" class="text-dark">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="PaymentDate" value="<?= set_value('PaymentDate', $paymentInfo->date); ?>" id="PaymentDate" class="form-control form-control-sm" title="Payment Date" required="" />
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="form-group">
                            <label for="ChequeDate" class="text-dark">Cheque Date <span class="text-danger">*</span></label>
                            <input type="date" name="ChequeDate" value="<?= set_value('ChequeDate', $paymentInfo->ChequeDate); ?>" id="ChequeDate2" class="form-control form-control-sm" title="Cheque Date" required="" />
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="form-group">
                            <label for="Amount" class="text-dark">Payment Amount <span class="text-danger">*</span></label>
                            <input type="text" name="Amount" value="<?= set_value('Amount', $paymentInfo->credit); ?>" id="Amount" class="form-control form-control-sm" placeholder="Payment Amount" required="" readonly="" />
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
                            <textarea name="Remark" id="Remark" class="form-control" placeholder="Payment Remark" rows="1"><?= set_value('Remark', $paymentInfo->remark); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 offset-4 mt-2">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Submit" id="chequeSubmitButton" />
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

<script type="text/javascript">
    const onPaymentChequeFormSubmitHandler = (event) => {
        $("#chequeSubmitButton").prop("disabled", true);
        event.preventDefault();
        var invoiceId = $("#invoiceId").val();
        var invoiceType = $("#invoiceType").val();
        var paymentId = $("#paymentId").val();
        var paymentDate = $("#PaymentDate").val();
        var ChequeDate2 = $("#ChequeDate2").val();
        var amount = $("#Amount").val();
        var chequeStatus = $('input[name="ChequeStatus"]:checked').val();

        if (invoiceId && invoiceType && paymentId && paymentDate && ChequeDate2 && amount && chequeStatus) {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>payment/chequeStatusUpdate",
                data    :   $("#chequeForm").serialize(),
                success :   function(response) {
                                var responseData = JSON.parse(response);
                                if (responseData.status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    window.setTimeout(function() {
                                        $("#ChequeInfo").modal("hide");
                                        getPartyBalance();
                                        getPandingInvoices($("#party_id").val());
                                        getPaymentStatement($("#party_id").val());
                                        getVoucherNumber($("#voucher_type").val());
                                        $("#chequeSubmitButton").prop("disabled", false);
                                    }, 1000);
                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: responseData.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $("#chequeSubmitButton").prop("disabled", false);
                                }
                            }
            });            
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#chequeSubmitButton").prop("disabled", false);            
        }
    };
</script>