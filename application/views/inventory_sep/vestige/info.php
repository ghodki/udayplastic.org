<div class="modal-header">
    <h5 class="modal-title">Vestige Info</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <tr>
                        <th>Date</th>
                        <td><?= date('d-m-Y', strtotime($vestigeData->date)); ?></td>
                        <th>Voucher Number</th>
                        <td><?= $vestigeData->voucherNumber; ?></td>
                    </tr>
                    <tr>
                        <th>Company</th>
                        <td><?= get_title('inventory_billing', ['id' => $vestigeData->compId], 'company_name'); ?></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-dark">
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        if (!empty($vestigeProducts)) {
                                            $sr = 0;
                                            $totalQuantity = 0;
                                            foreach ($vestigeProducts as $key => $item) {
                                                $sr++;
                                                $totalQuantity += $item->quantity; 
                                    ?>
                                    <tr>
                                        <td><?= $sr; ?></td>
                                        <td><?= get_title('product', ['id' => $item->productId], 'p_name'); ?></td>
                                        <td><i class="fa fa-inr"></i> <?= $item->price; ?></td>
                                        <td><?= $item->quantity; ?></td>
                                        <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($item->total); ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align: right;">Total</th>
                                        <th><?= $totalQuantity; ?></th>
                                        <th><i class="fa fa-inr"></i> <?= $vestigeData->subTotal; ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td colspan="3" style="white-space: unset;">
                            <?= $vestigeData->remark; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= $vestigeData->created; ?></td>
                        <th>Created By</th>
                        <td><?= $vestigeData->createdBy; ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?= $vestigeData->updated; ?></td>
                        <th>Updated By</th>
                        <td><?= $vestigeData->updatedBy; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>