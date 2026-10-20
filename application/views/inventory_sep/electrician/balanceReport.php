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
                    <div class="card card-fuchsia card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="title text-fuchsia">
                                    Balance Report
                                </h3>
                            </div>
                            <a href="<?= base_url(); ?>electrician/insight/<?= $userId; ?>" class="btn bg-fuchsia btn-sm pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="pb-2"><?= $electricianName; ?></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-fuchsia">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if (isset($balanceData) && !empty($balanceData)) {
                                                $Sr = 0;
                                                $totalCredit = 0;
                                                $totalDebit = 0;
                                                $totalBalance = 0;
                                                foreach ($balanceData as $key => $data) {
                                                    $Sr++;
                                                    $totalCredit += $data->credit;
                                                    $totalDebit += $data->debit;
                                                    $totalBalance += ($data->credit - $data->debit);
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= $data->created; ?></td>
                                            <td><?= $data->credit; ?></td>
                                            <td><?= $data->debit; ?></td>
                                            <td><?= $data->balance; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align: right;">Total</th>
                                            <th><?= $totalCredit; ?></th>
                                            <th><?= $totalDebit; ?></th>
                                            <th><?= $totalBalance; ?></th>
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