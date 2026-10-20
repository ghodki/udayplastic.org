<style type="text/css">
    .card-body {
        padding: 0.25rem 0.25rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-outline card-fuchsia">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-fuchsia">OrderForm Info</h5>
                            </div>
                            <a href="<?= base_url(); ?>Orderform" class="btn bg-fuchsia btn-xs pull-right" title="Orderform Report">
                                <i class="fa fa-list"></i> OrderForm Report
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <th>Order Date</th>
                                                <td><?= date('d-m-Y', strtotime($OrderForm->OrderDate)); ?></td>
                                                <th>Order Number</th>
                                                <td><?= $OrderForm->OrderNumber; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Customer Name</th>
                                                <td><?= get_title('parties', ['id' => $OrderForm->PartyId], 'name'); ?></td>
                                                <th>Voucher Type</th>
                                                <td><?= $OrderForm->VoucherType; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?= $OrderForm->VoucherType; ?> Invoice Number</th>
                                                <td colspan="3">
                                                    <?php
                                                        if ($OrderForm->VoucherType == "Sale") {
                                                            echo get_title('sales', ['order_book_Id' => $OrderForm->Id], 'invoiceNumber'); 
                                                        } elseif ($OrderForm->VoucherType == "Estimate") {
                                                            echo get_title('estimates', ['order_book_Id' => $OrderForm->Id], 'invoiceNumber'); 
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="bg-fuchsia">
                                                            <tr>
                                                                <th class="text-white">Sr.</th>
                                                                <th class="text-white">Product Name</th>
                                                                <th class="text-white">Price</th>
                                                                <th class="text-white">Quantity</th>
                                                                <th class="text-white">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if (!empty($OrderFormItems)) {
                                                                    $Sr = 0;
                                                                    $TotalQuantity = 0;
                                                                    foreach ($OrderFormItems as $key => $Item) {
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
                                                                <th><i class="fa fa-inr"></i> <?= $OrderForm->SubTotal; ?></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Salesman</th>
                                                <td>
                                                    <?= get_title('employees', ['Id' => $OrderForm->SalesmanId], 'Name'); ?>
                                                </td>
                                                <th>Remark</th>
                                                <td style="white-space: unset;">
                                                    <?= $OrderForm->Remark; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created</th>
                                                <td><?= $OrderForm->Created; ?></td>
                                                <th>Created By</th>
                                                <td><?= $OrderForm->CreatedBy; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Updated</th>
                                                <td><?= $OrderForm->Updated; ?></td>
                                                <th>Updated By</th>
                                                <td><?= $OrderForm->UpdatedBy; ?></td>
                                            </tr>
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