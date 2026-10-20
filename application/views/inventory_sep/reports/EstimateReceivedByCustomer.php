<style type="text/css">
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-navy card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-navy">
                                    Received Summary
                                </h4>
                            </div>
                            <a href="<?= base_url(); ?>Reports/EstimateReceivedReport" class="btn btn-sm bg-navy pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <h5 class="text-maroon">
                                    Customer Name : <?= $CustomerName; ?>
                                </h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Invoice Number</th>
                                            <th>Mode</th>
                                            <th>Received</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $TotalPaid = 0;
                                            if (isset($EstimatesReceiveds) && !empty($EstimatesReceiveds)) {
                                                foreach ($EstimatesReceiveds as $key => $Data) {
                                                    $Sr++;
                                                    $rowPaid = (float) ($Data->PaidAmount ?? 0);
                                                    $TotalPaid += $rowPaid;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= !empty($Data->date) ? date('d-m-Y', strtotime($Data->date)) : ''; ?></td>
                                            <td>
                                                <?php
                                                    $InvoiceId = !empty($Data->invoice_id) ? json_decode($Data->invoice_id, true) : null;
                                                    if (is_array($InvoiceId)) {
                                                        $SaleInvoiceNumber = "";
                                                        foreach ($InvoiceId as $SaleInvoiceId) {
                                                            $SaleInvoiceNumber .= get_title('estimates', ['id' => $SaleInvoiceId], 'invoiceNumber') . ", ";
                                                        }
                                                        echo substr(trim($SaleInvoiceNumber), 0, -1);
                                                    } else {
                                                        echo get_title('estimates', ['id' => $Data->invoice_id], 'invoiceNumber'); 
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if (get_title('payment_mode', ['id' => $Data->paymentmode], 'payment_method') == "Cheque") {
                                                        echo get_title('payment_mode', ['id' => $Data->paymentmode], 'payment_method');
                                                        echo !empty($Data->ChequeDate) ? " <small>(" . date('d-m-Y', strtotime($Data->ChequeDate)) . ")</small>" : ""; 
                                                    } else {
                                                        echo get_title('payment_mode', ['id' => $Data->paymentmode], 'payment_method');
                                                    }
                                                ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($rowPaid, 2); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalPaid, 2); ?></th>
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
    $(document).ready(function() {
        $('.table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                        {
                                extend: 'copy',
                                footer: true
                        },
                        {
                                extend: 'excel',
                                footer: true
                        },
                        {
                                extend: 'pdf',
                                footer: true
                        },
                        {
                                extend: 'print',
                                footer: true
                        },
                        "pageLength"
                ],
                "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });
</script>