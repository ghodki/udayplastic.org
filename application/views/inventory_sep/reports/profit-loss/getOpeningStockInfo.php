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
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-purple">
                                    Opening Stock Info
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>reports/profitAndLossReport" class="btn btn-sm bg-purple pull-right" title="Back">
                               <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-purple">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Product Name</th>
                                                    <th>Qty</th>
                                                    <th>Rate</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($openingStockData) && !empty($openingStockData)) {
                                                        $sr = 0;
                                                        $total = 0;
                                                        $totalAmount = 0;
                                                        foreach ($openingStockData as $key => $openingStock) {
                                                            $sr++;
                                                            $total = ($openingStock->qty * $openingStock->rate);
                                                            $totalAmount += $total;
                                                ?>
                                                <tr>
                                                    <td><?= $sr; ?></td>
                                                    <td><?= $openingStock->p_name; ?></td>
                                                    <td><?= $openingStock->qty; ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= $openingStock->rate; ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($total); ?></td>                            
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-right" colspan="4">Total</th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalAmount); ?></th>
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
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
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