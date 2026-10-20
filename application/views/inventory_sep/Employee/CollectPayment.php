<style type="text/css">
    .table td, .table th {
        padding: 0.25rem;
        font-size: 12px;
    }
    #swal2-content {
        display: flex !important;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <form id="PaymentForm" method="POST" onsubmit="onLedgerFormSubmitHandler(event);">
                        <input type="hidden" name="PartyId" value="<?= set_value('PartyId', $CustomerId); ?>" id="PartyId" />
                        <input type="hidden" name="Action" value="<?= set_value('Action', 1); ?>" id="Action" />
                        <div class="card card-purple card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="text-purple">
                                        Collect Payment
                                    </h5>
                                </div>
                                <a href="<?= base_url(); ?>Employee/PartiesBalanceReport" class="btn bg-purple btn-xs pull-right" title="Back">
                                    <i class="fa fa-angle-double-left text-white"></i> Back
                                </a>
                            </div>
                            <div class="card-body">
                                <h4 class="text-purple text-center">
                                    <?= $PartyName; ?>
                                </h4>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xxl-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class="bg-purple">
                                                    <tr>
                                                        <th class="text-white"></th>
                                                        <th class="text-white">Invoice No.</th>
                                                        <th class="text-white">Total</th>
                                                        <th class="text-white">Paid</th>
                                                        <th class="text-white">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if (isset($PartiesPayments) && !empty($PartiesPayments)) {
                                                            $Total = 0;
                                                            $Paid = 0;
                                                            $Balance = 0;
                                                            foreach ($PartiesPayments as $key => $Payment) {
                                                                $Total += $Payment->total;
                                                                $Paid += $Payment->paid_amount;
                                                                $Balance += ($Payment->total - $Payment->paid_amount);
                                                    ?>
                                                    <input type="hidden" name="InvoiceType_<?= $Payment->id; ?>" value="<?= set_value('InvoiceType_' . $Payment->id, $Payment->type); ?>" id="InvoiceType_<?= $Payment->id; ?>" />
                                                    <input type="hidden" name="InvoiceNumber_<?= $Payment->id; ?>" value="<?= set_value('invoiceNumber_' . $Payment->id, $Payment->invoiceNumber); ?>" id="invoiceNumber_<?= $Payment->id; ?>" />
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" name="InvoiceId[]" value="<?= $Payment->id; ?>" id="InvoiceId<?= $Payment->id; ?>" class="checkbox" />
                                                        </th>
                                                        <td><?= $Payment->invoiceNumber; ?></td>
                                                        <td><i class="fa fa-inr"></i> <?= number_format($Payment->total, 2); ?></td>
                                                        <td><i class="fa fa-inr"></i> <?= number_format($Payment->paid_amount, 2); ?></td>
                                                        <td>
                                                            <i class="fa fa-inr"></i> 
                                                            <span id="InvoiceBalance_<?= $Payment->id; ?>">
                                                                <?= number_format(($Payment->total - $Payment->paid_amount), 2); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <?php } } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2" style="text-align: right;">Total</th>
                                                        <th><i class="fa fa-inr"></i> <?= number_format($Total, 2); ?></th>
                                                        <th><i class="fa fa-inr"></i> <?= number_format($Paid, 2); ?></th>
                                                        <th><i class="fa fa-inr"></i> <?= number_format($Balance, 2); ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xxl-3 col-xs-3">
                                        <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" value="<?= set_value('payment_date', date('Y-m-d')); ?>" id="payment_date" class="form-control form-control-sm" title="Payment Date" required="" />
                                        <span class="text-danger"><?= form_error('payment_date'); ?></span>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
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
                                    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3 Cheque d-none">
                                        <div class="form-group">
                                            <label for="ChequeDate">Cheque Date</label>
                                            <input type="date" name="ChequeDate" value="<?= set_value('ChequeDate'); ?>" id="ChequeDate" class="form-control form-control-sm" title="Cheque Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xs-3 col-xxl-3">
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
                            <div class="card-footer">
                                <button type="submit" class="btn bg-purple btn-sm">
                                    Submit Request
                                </button>
                                <button type="reset" class="btn btn-danger btn-sm">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const onLedgerFormSubmitHandler = (event) => {
        $("#submitButton").prop("disabled", true);
        event.preventDefault();
        var PaymentDate = $("#payment_date").val();
        var PaymentMode = $("#payment_mode").val();
        var PaymentAmount = $("#payment_amount").val();

        if (PaymentDate && PaymentMode && PaymentAmount) {
            let Button = '<button class="btn btn-dark btn-sm mr-3" onclick="PaymentRequestSave(3);">Save & Download Receipt</button>';
            Button += '<button class="btn btn-primary btn-sm mr-3" onclick="PaymentRequestSave(2);">Save & Send Receipt</button>';
            Button += '<button class="btn btn-success btn-sm" onclick="PaymentRequestSave(1);">Save Only</button>';
            
            Swal.fire({
                title: 'Do you want to save payment request ?',
                text: 'This will create payment request ?',
                html: Button,
                icon: 'question',
                showConfirmButton: false,
                showCancelButton: false,
                confirmButtonText: 'Yes, Save it!'
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please enter required fields.',
                showConfirmButton: false,
                timer: 2000
            });
            $("#submitButton").prop("disabled", false);
        }
    };

    function PaymentRequestSave(Action) {
        var OnclickAction = $("#Action").val();
        
        var PaymentRequestEntry = JSON.parse(onCheckPaymentRequestEntry()).Data;
        if (PaymentRequestEntry == 'Unique') {
            $.ajax({
                type    :   "POST",
                url     :   "<?= base_url(); ?>Employee/PaymentRequestSave",
                data    :   $("#PaymentForm").serialize(),
                success :   function(Response) {
                                var ResponseData = JSON.parse(Response);
                                if (ResponseData.Status === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });

                                    if (OnclickAction == Action) {
                                        window.setTimeout(function() {
                                            window.location.href = '<?= base_url(); ?>Employee/PartiesBalanceReport';
                                        }, 1000);
                                    } else if (Action == 2) {
                                        PaymentRequestSendReceiptAndSave(ResponseData.RequestId);
                                    } else if (Action == 3) {
                                        PaymentRequestDownloadReceiptAndSave(ResponseData.RequestId);
                                    }

                                } else {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: ResponseData.Message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                                $("#submitButton").prop("disabled", false);
                            },
                error   :   function() {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Error occurred. Please try again.',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                $("#submitButton").prop("disabled", false);
                            }
            });
        } else {
            Swal.fire({
                title: 'Duplicate Data Detected',
                text: 'The data you are about to submit already exists. Do you want to proceed?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type    :   "POST",
                        url     :   "<?= base_url(); ?>Employee/PaymentRequestSave",
                        data    :   $("#PaymentForm").serialize(),
                        success :   function(Response) {
                                        var ResponseData = JSON.parse(Response);
                                        if (ResponseData.Status === true) {
                                            Swal.fire({
                                                position: 'top-end',
                                                icon: 'success',
                                                title: ResponseData.Message,
                                                showConfirmButton: false,
                                                timer: 2000
                                            });

                                            if (OnclickAction == Action) {
                                                window.setTimeout(function() {
                                                    window.location.href = '<?= base_url(); ?>Employee/PartiesBalanceReport';
                                                }, 1000);
                                            } else if (Action == 2) {
                                                PaymentRequestSendReceiptAndSave(ResponseData.RequestId);
                                            } else if (Action == 3) {
                                                PaymentRequestDownloadReceiptAndSave(ResponseData.RequestId);
                                            }

                                        } else {
                                            Swal.fire({
                                                position: 'top-end',
                                                icon: 'error',
                                                title: ResponseData.Message,
                                                showConfirmButton: false,
                                                timer: 2000
                                            });
                                        }
                                        $("#submitButton").prop("disabled", false);
                                    },
                        error   :   function() {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'error',
                                            title: 'Error occurred. Please try again.',
                                            showConfirmButton: false,
                                            timer: 2000
                                        });
                                        $("#submitButton").prop("disabled", false);
                                    }
                    });
                } else {
                    $("#submitButton").prop("disabled", false);
                }
            });
        }
    }

    function PaymentRequestDownloadReceiptAndSave(RequestId) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Employee/DownloadReceiptVoucher",
            data    :   {RequestId: RequestId},
            success :   function(Response) {
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData.Status === true) {
                                DownloadFile(ResponseData.FileName, ResponseData.URL);
                                window.setTimeout(function() {
                                    window.location.href = '<?= base_url(); ?>Employee/PartiesBalanceReport';
                                }, 1000);
                            } else {
                                $("#submitButton").prop("disabled", false);
                            }
                        }
        });
    }
    
    function PaymentRequestSendReceiptAndSave(RequestId) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Employee/SendReceiptVoucher",
            data    :   {RequestId: RequestId},
            success :   function(Response) {
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData.Status === true) {
                                window.setTimeout(function() {
                                    window.location.href = '<?= base_url(); ?>Employee/PartiesBalanceReport';
                                    window.open(ResponseData.URL, '_blank');
                                }, 1000);
                            } else {
                                $("#submitButton").prop("disabled", false);
                            }
                        }
        });
    }

    const ifCheque = () => {
        if ($('#payment_mode').val() == 6) {
            $('.Cheque').removeClass('d-none');
        } else {
            $('.Cheque').addClass('d-none');
        }
    };
    
    $('.checkbox').click(function() {
        var checkbox = $('.checkbox');
        var anyCheckboxChecked = false;

        checkbox.each(function(index, element) {
            if ($(element).prop('checked')) {
                anyCheckboxChecked = true;
                return false;
            }
        });

        checkbox.attr('required', !anyCheckboxChecked);
        CalcultePaymentAmount();
    });

    const CalcultePaymentAmount = () => {
        var checkbox = $('.checkbox');
        var PaymentAmount = 0;
        
        checkbox.each(function(index, element) {  
            if ($(element).prop('checked')) {
                var BalanceInvoiceId = $(this).val();  
                var InvoiceBalanceAmount = $('#InvoiceBalance_' + BalanceInvoiceId).text();
                // Step 1: Convert string to integer
                let integerValue = parseFloat(InvoiceBalanceAmount.replace(/,/g, ''), 10); 
                PaymentAmount = parseFloat(PaymentAmount + integerValue);   
            } 
        });  
        $('#payment_amount').val(PaymentAmount);
    };
    
    const DownloadFile = async (FileName, FileUrl) => {
        try {
            const response = await fetch(FileUrl);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
        
            const blob = await response.blob();
            const objectUrl = window.URL.createObjectURL(blob);
            const anchor = document.createElement('a');
            anchor.style.display = 'none';
            anchor.href = objectUrl;
            anchor.download = FileName;
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
            window.URL.revokeObjectURL(objectUrl);
        } catch (error) {
            console.error('Error downloading the file:', error);
        }
    };
    
    const onCheckPaymentRequestEntry = () => {
        var PaymentDate = $("#payment_date").val();
        var PartyId = $("#PartyId").val();
        var Amount = $("#payment_amount").val();

        if (PaymentDate && PartyId && Amount) {
            return $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>Employee/CheckPaymentRequestEntry",
                data    :   $("#PaymentForm").serialize(),
                async   :   false,
                success :   function(Response) {
                                if (JSON.parse(Response).Status == true) {
                                    JSON.parse(Response);
                                }
                            }
            }).responseText;
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Required field should not be empty.',
                showConfirmButton: false,
                timer: 2000
            });
        }
    };
</script>