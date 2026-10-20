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
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-success">
                                    Salesman Wise Due Customers
                                </h4>
                            </div>
                            <a href="<?= base_url(); ?>Reports/SalesmanWiseDueEstimateReport" class="btn btn-sm bg-success pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <h5 class="text-success">
                                    Salesman Name : <?= $SalesmanName; ?>
                                </h5>
                                <table class="table table-bordered">
                                    <thead class="bg-success">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Customer Name</th>
                                            <th>Area</th>
                                            <th>Invoices</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($SalesData) && !empty($SalesData)) {
                                                $Sr = 0;
                                                $Invoices = 0;
                                                $Total = 0;
                                                $Paid = 0;
                                                $Balance = 0;
                                                foreach ($SalesData as $key => $Sales) {
                                                    $Sr++;
                                                    $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                                                    $SalesQuery = $this->db->select('id')->where(['customerId' => $Sales->customerId, 'status' => 1])->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->get('estimates');
                                                    
                                                    $Invoices += $SalesQuery->num_rows();
                                                    $Total += $Sales->Total;
                                                    $Paid += $Sales->PaidAmount;
                                                    $Balance += ($Sales->Total - $Sales->PaidAmount);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>Reports/SalesmanWiseDueEstimateInvoices/<?= $Sales->customerId; ?>" title="Invoices Info">
                                                    <?= get_title('parties', ['id' => $Sales->customerId], 'name'); ?>
                                                </a>
                                            </td>
                                            <td><?= get_title('parties', ['id' => $Sales->customerId], 'area'); ?></td>
                                            <td><?= $SalesQuery->num_rows(); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sales->Total); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($Sales->PaidAmount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($Sales->Total - $Sales->PaidAmount)); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total</th>
                                            <th><?= $Invoices; ?></th>
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