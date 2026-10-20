<div class="modal-header">
    <h5 class="modal-title">Stock Manufacturer Info</h5>
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
                        <td><?= date('d-m-Y', strtotime($StockManufacturerData->Date)); ?></td>
                        <th>Voucher Number</th>
                        <td><?= $StockManufacturerData->VoucherNumber; ?></td>
                    </tr>
                    <tr>
                        <th>Company</th>
                        <td><?= get_title('inventory_billing', ['id' => $StockManufacturerData->CompId], 'company_name'); ?></td>
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
                                        if (!empty($StockManufacturerProducts)) {
                                            $Sr = 0;
                                            $TotalQuantity = 0;
                                            foreach ($StockManufacturerProducts as $key => $Item) {
                                                $Sr++;
                                                $TotalQuantity += $Item->Quantity; 
                                    ?>
                                    <tr>
                                        <td><?= $Sr; ?></td>
                                        <td><?= get_title('product', ['id' => $Item->ProductId], 'p_name'); ?></td>
                                        <td><i class="fa fa-inr"></i> <?= $Item->Price; ?></td>
                                        <td><?= $Item->Quantity; ?></td>
                                        <td><i class="fa fa-inr"></i> <?= number_format($Item->Total, 2); ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align: right;">Total</th>
                                        <th><?= $TotalQuantity; ?></th>
                                        <th><i class="fa fa-inr"></i> <?= $StockManufacturerData->SubTotal; ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td colspan="3" style="white-space: unset;">
                            <?= $StockManufacturerData->Remark; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= $StockManufacturerData->Created; ?></td>
                        <th>Created By</th>
                        <td><?= $StockManufacturerData->CreatedBy; ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?= $StockManufacturerData->Updated; ?></td>
                        <th>Updated By</th>
                        <td><?= $StockManufacturerData->UpdatedBy; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>