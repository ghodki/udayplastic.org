<style type="text/css">
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <h3 class="m-0">Orderbook</h3>
                </div><!-- /.col -->
                <div class="col-md-6 col-lg-6 col-sm-6 col-xl-6 col-xs-6 col-xxl-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Reports</a></li>
                        <li class="breadcrumb-item active">Orderbook Info</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-dark">
                                    Orderbook Info
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/OrderbookReport" class="btn btn-xs bg-dark pull-right" title="Orderbook Report">
                                <i class="fa fa-angle-double-left text-white"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <th>Order Date</th>
                                                <td><?= date('d-m-Y', strtotime($OrderBook->OrderDate)); ?></td>
                                                <th>Order Number</th>
                                                <td><?= $OrderBook->OrderNumber; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Customer Name</th>
                                                <td><?= get_title('parties', ['id' => $OrderBook->PartyId], 'name'); ?></td>
                                                <th>Voucher Type</th>
                                                <td><?= $OrderBook->VoucherType; ?></td>
                                            </tr>
                                            <tr>
                                                <th><?= $OrderBook->VoucherType; ?> Invoice Number</th>
                                                <td colspan="3">
                                                    <?php
                                                        if ($OrderBook->VoucherType == "Sale") {
                                                            echo get_title('sales', ['order_book_Id' => $OrderBook->Id], 'invoiceNumber'); 
                                                        } elseif ($OrderBook->VoucherType == "Estimate") {
                                                            echo get_title('estimates', ['order_book_Id' => $OrderBook->Id], 'invoiceNumber'); 
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="bg-dark">
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
                                                                if (!empty($OrderBookItems)) {
                                                                    $Sr = 0;
                                                                    $TotalQuantity = 0;
                                                                    foreach ($OrderBookItems as $key => $Item) {
                                                                        $Sr++;
                                                                        $TotalQuantity += $Item->Quantity; 
                                                            ?>
                                                            <tr>
                                                                <td><?= $Sr; ?></td>
                                                                <td><?= $Item->ProductName; ?></td>
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
                                                                <th><i class="fa fa-inr"></i> <?= $OrderBook->SubTotal; ?></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Salesman</th>
                                                <td>
                                                    <?= get_title('employees', ['Id' => $OrderBook->SalesmanId], 'Name'); ?>
                                                </td>
                                                <th>Remark</th>
                                                <td style="white-space: unset;">
                                                    <?= $OrderBook->Remark; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created</th>
                                                <td><?= $OrderBook->Created; ?></td>
                                                <th>Created By</th>
                                                <td><?= $OrderBook->CreatedBy; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Updated</th>
                                                <td><?= $OrderBook->Updated; ?></td>
                                                <th>Updated By</th>
                                                <td><?= $OrderBook->UpdatedBy; ?></td>
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
    </section>
    <!-- /.content -->
 </div>