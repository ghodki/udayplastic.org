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
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="title text-purple">
                                    Earn Report
                                </h3>
                            </div>
                            <a href="<?= base_url(); ?>electrician/insight/<?= $userId; ?>" class="btn bg-purple btn-sm pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="pb-2"><?= $electricianName; ?></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-purple">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Date</th>
                                            <th>Credit</th>
                                            <th>Product Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($earningData) && !empty($earningData)) {
                                                $Sr = 0;
                                                $totalCredit = 0;
                                                foreach ($earningData as $key => $data) {
                                                    $Sr++;
                                                    $totalCredit += $data->credit;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($data->date)); ?></td>
                                            <td><?= $data->credit; ?></td>
                                            <td><?= get_title('product', ['id' => $data->productId], 'p_name'); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align: right;">Total</th>
                                            <th colspan="2"><?= $totalCredit; ?></th>
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