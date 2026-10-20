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
                                    Purchase Payment Summary
                                </h4>
                            </div>
                            <a href="<?= base_url(); ?>Reports/AccountSummary" class="btn btn-sm bg-navy pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Customer Name</th>
                                            <th>Mobile</th>
                                            <th>GST</th>
                                            <th>Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $Sr = 0;
                                            $TotalPaid = 0;
                                            if (isset($PurchasesPayments) && !empty($PurchasesPayments)) {
                                                foreach ($PurchasesPayments as $key => $Data) {
                                                    $Sr++;
                                                    $rowPaid = (float) ($Data->PaidAmount ?? 0);
                                                    $TotalPaid += $rowPaid;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>Reports/PurchasePaymentByCustomer/<?= $Data->party_id; ?>" class="text-primary" title="Payment Info">
                                                    <?= get_title('parties', ['id' => $Data->party_id], 'name'); ?>
                                                </a>
                                            </td>
                                            <td><?= get_title('parties', ['id' => $Data->party_id], 'contact'); ?></td>
                                            <td><?= get_title('parties', ['id' => $Data->party_id], 'gst'); ?></td>
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