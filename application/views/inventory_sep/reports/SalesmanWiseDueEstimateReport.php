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
                            <div class="table-responsive">
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
                                            if (isset($Salesmans) && !empty($Salesmans)) {
                                                $Sr = 0;
                                                $Invoices = 0;
                                                $Total = 0;
                                                $Paid = 0;
                                                $Balance = 0;
                                                foreach ($Salesmans as $key => $Salesman) {
                                                    $Sr++;
                                                    $SalesQuery = $this->db->select('id')->where(['SalesmanId' => $Salesman->Id, 'status' => 1])->get('estimates');
                                                    $SalesQuery2 = $this->db->select('SUM(IFNULL(total, 0)) AS Total, SUM(IFNULL(paid_amount, 0)) AS PaidAmount')->where(['SalesmanId' => $Salesman->Id, 'status' => 1])->get('estimates')->row();
                                                    
                                                    $Invoices += $SalesQuery->num_rows();
                                                    $Total += $SalesQuery2->Total;
                                                    $Paid += $SalesQuery2->PaidAmount;
                                                    $Balance += ($SalesQuery2->Total - $SalesQuery2->PaidAmount);
                                                    if ($SalesQuery->num_rows()) {
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>Reports/SalesmanWiseDueEstimateCustomers/<?= $Salesman->Id; ?>" title="Salesman Wise Due Customers">
                                                    <?= $Salesman->Name; ?>
                                                </a>
                                            </td>
                                            <td><?= $SalesQuery->num_rows(); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($SalesQuery2->Total); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($SalesQuery2->PaidAmount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($SalesQuery2->Total - $SalesQuery2->PaidAmount)); ?></td>
                                        </tr>
                                        <?php } } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align: right;">Total</th>
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