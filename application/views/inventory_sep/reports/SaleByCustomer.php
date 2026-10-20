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
                                    Sale Summary
                                </h4>
                            </div>
                            <a href="<?= base_url(); ?>Reports/SaleReport" class="btn btn-sm bg-navy pull-right" title="Back">
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
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $Sr = 0;
                                            $TotalSale = 0;
                                            $TotalPaid = 0;
                                            $TotalBalance = 0;
                                            if (isset($SalesInvoices) && !empty($SalesInvoices)) {
                                                foreach ($SalesInvoices as $key => $Data) {
                                                    $Sr++;
                                                    $rowTotal = (float) ($Data->total ?? 0);
                                                    $rowPaid = (float) ($Data->paid_amount ?? 0);
                                                    $rowBalance = $rowTotal - $rowPaid;
                                                    $TotalSale += $rowTotal;
                                                    $TotalPaid += $rowPaid;
                                                    $TotalBalance += $rowBalance;
                                                    
                                                    if ($Data->voucherType == 1) {
                                                        $PaymentMode = "Cash";
                                                    } else if ($Data->voucherType == 2) {
                                                        $PaymentMode = "Credit";
                                                    } else {
                                                        $PaymentMode = $Data->voucherType;
                                                    }
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Data->invoiceDate)); ?></td>
                                            <td><?= $Data->invoiceNumber; ?></td>
                                            <td><?= $PaymentMode; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($rowTotal, 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($rowPaid, 2); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= number_format($rowBalance, 2); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalSale, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalPaid, 2); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= number_format($TotalBalance, 2); ?></th>
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