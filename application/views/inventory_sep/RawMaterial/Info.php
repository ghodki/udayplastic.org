<div class="modal-header">
    <h5 class="modal-title">Raw Material Info</h5>
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
                        <th>Product</th>
                        <td colspan="3"><?= get_title('product', ['id' => $RawMaterialData->ProductId], 'p_name'); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-dark">
                                    <tr>
                                        <th class="text-white">Sr.</th>
                                        <th class="text-white">Product Name</th>
                                        <th class="text-white">Quantity</th>
                                        <th class="text-white">Price</th>
                                        <th class="text-white">Weight</th>
                                        <th class="text-white">Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        if (!empty($RawMaterialProducts)) {
                                            $Sr = 0;
                                            $TotalQuantity = 0;
                                            foreach ($RawMaterialProducts as $key => $Item) {
                                                $Sr++;
                                                $TotalQuantity += $Item->Quantity; 
                                    ?>
                                    <tr>
                                        <td><?= $Sr; ?></td>
                                        <td><?= $Item->ProductName; ?></td>
                                        <td><?= $Item->Quantity; ?></td>
                                        <td><i class="fa fa-inr"></i> <?= $Item->Price; ?></td>
                                        <td><?= $Item->Weight; ?></td>
                                        <td><?= $Item->Unit; ?></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align: right;">Total</th>
                                        <th><?= $TotalQuantity; ?></th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td colspan="3" style="white-space: unset;">
                            <?= $RawMaterialData->Remark; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= $RawMaterialData->Created; ?></td>
                        <th>Created By</th>
                        <td><?= $RawMaterialData->CreatedBy; ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?= $RawMaterialData->Updated; ?></td>
                        <th>Updated By</th>
                        <td><?= $RawMaterialData->UpdatedBy; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
</div>