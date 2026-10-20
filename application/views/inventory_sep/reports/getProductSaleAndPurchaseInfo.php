<div class="modal-header">
        <h5 class="modal-title">Product Purchase And Sale Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
        </button>
</div>
<div class="modal-body">
        <div class="row">
                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                        <h6 class="text-center"><?= $productName; ?></h6>
                </div>
                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                        <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                        <thead class="bg-dark">
                                                <tr>
                                                        <th>Sr</th>
                                                        <th>Invoice No</th>
                                                        <th>Invoice Date</th>
                                                        <th>Type</th>
                                                        <th>Price</th>
                                                        <th>Qty</th>
                                                        <th>Total</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <?php
                                                        $sr = 0;
                                                        $productTotalQty = 0;
                                                        $productTotalAmount = 0;
                                                        if (!empty($productSaleAndPurchaseData) && isset($productSaleAndPurchaseData)) {
                                                                foreach ($productSaleAndPurchaseData as $key => $productData) {
                                                                        $sr++;
                                                                        $rowQty = (float) ($productData->qty ?? 0);
                                                                        $rowAmount = (float) ($productData->netAmount ?? 0);
                                                                        $productTotalQty += $rowQty;
                                                                        $productTotalAmount += $rowAmount;
                                                ?>
                                                <tr>
                                                        <td><?= $sr; ?></td>
                                                        <td><?= $productData->invoiceNo; ?></td>
                                                        <td><?= !empty($productData->invoiceDate) ? date('d-m-Y', strtotime($productData->invoiceDate)) : ''; ?></td>
                                                        <td><?= $productData->type; ?></td>
                                                        <td><?= $productData->rate; ?></td>
                                                        <td><?= indianMoneyFormat($rowQty); ?></td>
                                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($rowAmount); ?></td>
                                                </tr>
                                                <?php } } ?>
                                        </tbody>
                                        <tfoot>
                                                <tr>
                                                        <th colspan="5" style="text-align: right;">Total</th>
                                                        <th><?= indianMoneyFormat($productTotalQty); ?></th>
                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($productTotalAmount); ?></th>
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