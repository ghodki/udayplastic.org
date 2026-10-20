<style type="text/css">
    .table td, .table th {
        padding: 0.25rem;
    }
    @media (min-width: 1024px) {
        .downloadMobile {
            display: none;
        }
        .downloadWeb {
            display: inline;
        }
    }
    @media (max-width: 380px) {
        .downloadWeb {
            display: none;
        }
        .downloadMobile {
            display: inline;
        }
    }
    @media (max-width: 320px) {
        .downloadWeb {
            display: none;
        }
        .downloadMobile {
            display: inline;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-success">
                                    Parties Paid
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/PartiesBalanceReport" class="btn btn-success btn-sm pull-right" title="Back">
                                <i class="fa fa-angle-double-left text-white"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <h4 class="text-success text-center">
                                <?= $PartyName; ?>
                            </h4>
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead class="bg-success">
                                        <tr>
                                            <th class="text-white">Invoice Number</th>
                                            <th class="text-white">Paid</th>
                                            <th class="text-white">Mode</th>
                                            <th class="text-white"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $Sr = 0;
                                            $Total = 0;
                                            if (isset($PartiesPaid) && !empty($PartiesPaid)) {
                                                foreach ($PartiesPaid as $key => $Paid) {
                                                    $Sr++;
                                                    $Total += (float) ($Paid->debit ?? 0);
                                                    $invoiceTypes = json_decode($Paid->invoice_type ?? '', true);
                                                    if (!is_array($invoiceTypes)) {
                                                        $invoiceTypes = [];
                                                    }
                                                    $InvoicesRecord = json_decode($Paid->invoice_id ?? '', true);
                                                    if (!is_array($InvoicesRecord)) {
                                                        $InvoicesRecord = [];
                                                    }
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                    if (in_array("Sale", $invoiceTypes) || in_array("Estimate", $invoiceTypes)) {
                                                        $SaleInvoiceNumber = "";
                                                        foreach ($InvoicesRecord as $key => $data) {
                                                            if (in_array("Sale", $invoiceTypes)) {
                                                                $SaleInvoiceNumber .= get_title('sales', ['id' => $data], 'invoiceNumber'). ", ";
                                                            }
                                                            if (in_array("Estimate", $invoiceTypes)) {
                                                                $SaleInvoiceNumber .= get_title('estimates', ['id' => $data], 'invoiceNumber') . ", ";
                                                            }
                                                        }
                                                        echo substr(trim($SaleInvoiceNumber), 0, -1);
                                                    } elseif ($Paid->invoice_type == 'Sale' || $Paid->invoice_type == 'Estimate') {
                                                        if ($Paid->invoice_type == 'Sale') {
                                                            echo get_title('sales', ['id' => $Paid->invoice_id], 'invoiceNumber');
                                                        }
                                                        if ($Paid->invoice_type == 'Estimate') {
                                                            echo get_title('estimates', ['id' => $Paid->invoice_id], 'invoiceNumber');
                                                        } 
                                                    }
                                                ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($Paid->debit, 2); ?></td>
                                            <td>
                                                <small>
                                                    <?= get_title('payment_mode', ['id' => $Paid->paymentmode], 'payment_method'); ?>
                                                    <br />
                                                    <?= date('d-m-Y', strtotime($Paid->date));?>
                                                </small>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="DownloadReceiptOrSendWhatsApp(event, <?= $Paid->id; ?>);" title="Download OR Send WhatsApp">
                                                    <i class="fa fa-file text-success"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align: right;">Total</th>
                                            <th colspan="3"><i class="fa fa-inr"> <?= number_format((float) $Total, 2); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const DownloadReceiptOrSendWhatsApp = (event, ReceiptId) => {
        event.preventDefault();
        let Button = '<a href="<?= base_url(); ?>Employee/ViewReceipt/'+ ReceiptId +'" class="btn btn-info btn-xs mr-2" target="_blank" style="font-size: 0.60rem;">View Receipt</a>';
        Button += '<button class="btn btn-dark btn-xs mr-2 downloadWeb" onclick="DownloadPaymentReceipt('+ ReceiptId + ');" style="font-size: 0.60rem;" target="_blank">Download Receipt</button>';
        Button += '<a href="<?= base_url(); ?>Employee/DownloadReceiptViaReceiptId/'+ ReceiptId + '" class="btn btn-dark btn-xs mr-2 downloadMobile" style="font-size: 0.60rem;">Download Receipt</a>';
        Button += '<button class="btn btn-primary btn-xs" onclick="SendPaymentReceipt(' + ReceiptId + ');" style="font-size: 0.60rem;">Send Receipt</button>';
        
        Swal.fire({
            title: 'Do you want payment Receipt View, Download or Send ?',
            text: 'This will create payment Receipt ?',
            html: Button,
            icon: 'question',
            showConfirmButton: false,
            showCancelButton: false
        });
    };
    
    function DownloadPaymentReceipt(ReceiptId) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Employee/DownloadReceiptVoucherViaReceiptId",
            data    :   {ReceiptId: ReceiptId},
            success :   function(Response) {
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData.Status === true) {
                                DownloadFile(ResponseData.FileName, ResponseData.URL);
                                window.setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        }
        });
    }
    
    function SendPaymentReceipt(ReceiptId) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Employee/SendReceiptVoucherViaReceiptId",
            data    :   {ReceiptId: ReceiptId},
            success :   function(Response) {
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData.Status === true) {
                                window.setTimeout(function() {
                                    window.open(ResponseData.URL, '_blank');
                                    window.location.reload();
                                }, 1000);
                            }
                        }
        });
    }
    
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
</script>