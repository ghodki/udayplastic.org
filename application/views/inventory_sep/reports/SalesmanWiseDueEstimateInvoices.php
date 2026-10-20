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
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-maroon">
                                    Salesman Wise Due Invoices
                                </h4>
                            </div>
                            <a href="<?= base_url(); ?>Reports/SalesmanWiseDueEstimateCustomers/<?= $SalesmanId; ?>" class="btn btn-sm bg-maroon pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <h5 class="text-maroon">
                                    Customer Name : <?= $CustomerName; ?>
                                </h5>
                                <table class="table table-bordered">
                                    <thead class="bg-maroon">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Invoice Number</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($SalesData) && !empty($SalesData)) {
                                                $Sr = 0;
                                                $Total = 0;
                                                $Paid = 0;
                                                $Balance = 0;
                                                foreach ($SalesData as $key => $Sales) {
                                                    $Sr++;
                                                    $Total += $Sales->Total;
                                                    $Paid += $Sales->PaidAmount;
                                                    $Balance += ($Sales->Total - $Sales->PaidAmount);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Sales->invoiceDate)); ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>Estimate/printEstimate/<?= $Sales->id; ?>" title="Invoices Info" target="_blank">
                                                    <?= $Sales->invoiceNumber; ?>
                                                </a>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sales->Total); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sales->PaidAmount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($Sales->Total - $Sales->PaidAmount)); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Total); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Paid); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($Balance); ?></th>
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
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength'],
            "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
        });
    });
</script>