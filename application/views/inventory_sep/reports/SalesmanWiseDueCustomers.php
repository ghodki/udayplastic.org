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
                            <a href="<?= base_url(); ?>reports/SalesmanWiseDueReport" class="btn btn-sm bg-success pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <h5 class="text-success">
                                    Salesman Name : <?= $salesmanName; ?>
                                </h5>
                                <table class="table table-bordered">
                                    <thead class="bg-success">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Customer Name</th>
                                            <th>Area</th>
                                            <th>Invoices</th>
                                            <th>Invoice Dates</th>
                                            <th>Type</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($salesData) && !empty($salesData)) {
                                                $sr = 0;
                                                $invoices = 0;
                                                $total = 0;
                                                $paid = 0;
                                                $balance = 0;
                                                foreach ($salesData as $key => $sales) {
                                                    $sr++;
                                                    $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

                                                    
                                                    $invoices += $sales->totalInvoice;
                                                    $total += $sales->total;
                                                    $paid += $sales->paidAmount;
                                                    $balance += ($sales->total - $sales->paidAmount);
                                        ?>
                                        <tr>
                                            <td><?= $sr; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>reports/SalesmanWiseDueInvoices/<?= $sales->customerId; ?>" title="Invoices Info">
                                                    <?= get_title('parties', ['id' => $sales->customerId], 'name'); ?>
                                                </a>
                                            </td>
                                            <td><?= get_title('parties', ['id' => $sales->customerId], 'area'); ?></td>
                                            <td><?= "<b>" . $sales->totalInvoice . "</b>"; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="getSalesmanWiseDueInvoiceDates(<?= $sales->customerId; ?>, '<?= $sales->type; ?>');" class="btn btn-success btn-sm" title="Invoice Dates Info">
                                                    <i class="fa fa-calendar"></i>
                                                </a>
                                            </td>
                                            <td><?= $sales->type; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sales->total); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($sales->paidAmount); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat(($sales->total - $sales->paidAmount)); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total</th>
                                            <th><?= $invoices; ?></th>
                                            <th></th>
                                            <th></th>
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

<div class="modal fade" id="invoice-dates-info" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
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
        
    const getSalesmanWiseDueInvoiceDates = (customerId, invoiceType) => {
        $("#invoice-dates-info .modal-dialog .modal-content").load("<?= base_url(); ?>reports/getSalesmanWiseDueInvoiceDates?customerId=" + customerId + "&invoiceType=" + invoiceType);
        $('#invoice-dates-info').modal('show');
    };
</script>