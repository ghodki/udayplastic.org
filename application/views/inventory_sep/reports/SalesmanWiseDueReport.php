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
                                    Salesman Wise Due Report
                                </h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Salesman Name</th>
                                            <th>Invoices</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($salesmans) && !empty($salesmans)) {
                                                $sr = 0;
                                                $invoices = 0;
                                                $total = 0;
                                                $paid = 0;
                                                $balance = 0;
                                                foreach ($salesmans as $key => $salesman) {
                                                    $sr++;
                                                    
                                                    $salesQuery = $this->db->select('COUNT(id) AS totalInvoices, SUM(IFNULL(total, 0)) AS total, SUM(IFNULL(paid_amount, 0)) AS paidAmount')->where(['SalesmanId' => $salesman->Id, 'status' => 1])->get('sales')->row();
                                                    $estimateSalesQuery = $this->db->select('COUNT(id) AS totalInvoices, SUM(IFNULL(total, 0)) AS total, SUM(IFNULL(paid_amount, 0)) AS paidAmount')->where(['SalesmanId' => $salesman->Id, 'status' => 1])->get('estimates')->row();
                                                    
                                                    $invoices += ($salesQuery->totalInvoices + $estimateSalesQuery->totalInvoices);
                                                    $total += ($salesQuery->total + $estimateSalesQuery->total);
                                                    $paid += ($salesQuery->paidAmount + $estimateSalesQuery->paidAmount);
                                                    $balance += (($salesQuery->total + $estimateSalesQuery->total) - ($salesQuery->paidAmount + $estimateSalesQuery->paidAmount));
                                                    
                                                    if ($salesQuery->totalInvoices > 0) {
                                        ?>
                                        <tr>
                                            <td><?= $sr; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>reports/SalesmanWiseDueCustomers/<?= $salesman->Id; ?>" title="Salesman Wise Due Customers">
                                                    <?= $salesman->Name; ?>
                                                </a>
                                            </td>
                                            <td><?= ($salesQuery->totalInvoices + $estimateSalesQuery->totalInvoices); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($salesQuery->total + $estimateSalesQuery->total)); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($salesQuery->paidAmount + $estimateSalesQuery->paidAmount)); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat((($salesQuery->total + $estimateSalesQuery->total) - ($salesQuery->paidAmount + $estimateSalesQuery->paidAmount))); ?></td>
                                        </tr>
                                        <?php } } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align: right;">Total</th>
                                            <th><?= $invoices; ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($total); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($paid); ?></th>
                                            <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($balance); ?></th>
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