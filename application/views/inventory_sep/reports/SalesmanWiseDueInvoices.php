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
                            <a href="<?= base_url(); ?>reports/SalesmanWiseDueCustomers/<?= $salesmanId; ?>" class="btn btn-sm bg-maroon pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <h5 class="text-maroon">
                                    Customer Name : <?= $customerName; ?>
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
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($salesData) && !empty($salesData)) {
                                                $sr = 0;
                                                $total = 0;
                                                $paid = 0;
                                                $balance = 0;
                                                foreach ($salesData as $key => $sales) {
                                                    $sr++;
                                                    $total += $sales->total;
                                                    $paid += $sales->paidAmount;
                                                    $balance += ($sales->total - $sales->paidAmount);
                                        ?>
                                        <tr>
                                            <td><?= $sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($sales->invoiceDate)); ?></td>
                                            <td>
                                                <?php if ($sales->type == 'Sale') { ?>
                                                <a href="<?= base_url(); ?>sale/printSale/<?= $sales->id; ?>" title="Invoices Info" target="_blank">
                                                    <?= $sales->invoiceNumber; ?>
                                                </a>
                                                <?php } elseif ($sales->type == 'Estimate') { ?>
                                                <a href="<?= base_url(); ?>estimate/printEstimate/<?= $sales->id; ?>" title="Invoices Info" target="_blank">
                                                    <?= $sales->invoiceNumber; ?>
                                                </a>                                                
                                                <?php } ?>
                                            </td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sales->total); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sales->paidAmount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($sales->total - $sales->paidAmount)); ?></td>
                                            <td><?= $sales->type; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total</th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($paid); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($balance); ?></th>
                                            <th></th>
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