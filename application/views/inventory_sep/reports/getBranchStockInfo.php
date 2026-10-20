<div class="modal-header">
    <h5 class="modal-title" id="infoModalLabel">Branch Stock Info</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="bg-dark">
                        <tr>
                            <th>Sr.</th>
                            <th>Product Name</th>
                            <th>Available Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sr = 0;
                            $totalQty = 0;
                            if (isset($branchStockData) && !empty($branchStockData)) {
                                foreach ($branchStockData as $key => $availStock) {
                                    $balanceQty = (float) productBalanceQuanityVendor($availStock->id, $availStock->user_id, $availStock->comp_id, $availStock->CompanyBranchId);
                                    if ($balanceQty > 0) {
                                        $sr++;
                                        $totalQty += $balanceQty;
                        ?>
                        <tr>
                            <td><?= $sr; ?></td>
                            <td><?= $availStock->p_name; ?></td>
                            <td><?= $balanceQty; ?></td>
                        </tr>
                        <?php } } } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="2">Total</th>
                            <th><?= indianMoneyFormat($totalQty); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>