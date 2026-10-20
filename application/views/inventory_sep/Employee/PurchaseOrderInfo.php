<style type="text/css">
    th, td {
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="header-title text-dark">
                                    Purchase Order Info
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>Employee/PurchaseOrderReport" class="btn btn-xs bg-dark pull-right" title="Purchase Order Report">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <th>Order Date</th>
                                                <td><?= date('d-m-Y', strtotime($PurchaseOrder->PurchaseDate)); ?></td>
                                                <th>Order Number</th>
                                                <td><?= $PurchaseOrder->PurchaseNumber; ?></td>
                                            </tr>
                                            <tr>
                                                <?php if (!$this->session->userdata('CompBranchId')) { ?>
                                                <th colspan="2">Branch Name</th>
                                                <td colspan="2"><?= get_title('companybranch', ['Id' => $PurchaseOrder->CompanyBranchId], 'BranchName'); ?></td>
                                                <?php } else { ?>
                                                <th colspan="2">Parent Company</th>
                                                <td colspan="2"><?= get_title('inventory_billing', ['id' => $PurchaseOrder->CompanyId], 'company_name'); ?></td>
                                                <?php } ?>
                                                <th>Sale Invoice Number</th>
                                                <td><?= get_title('sales', ['PurchaseOrderId' => $PurchaseOrder->Id], 'invoiceNumber')?></td>
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
                                                                if (!empty($PurchaseOrderItems)) {
                                                                    $Sr = 0;
                                                                    $TotalQuantity = 0;
                                                                    foreach ($PurchaseOrderItems as $key => $Item) {
                                                                        $Sr++;
                                                                        $TotalQuantity += $Item->Quantity; 
                                                            ?>
                                                            <tr>
                                                                <td><?= $Sr; ?></td>
                                                                <td><?= get_title('product', ['id' => $Item->ProductId], 'p_name'); ?></td>
                                                                <td><i class="fa fa-inr"></i> <?= number_format($Item->Price, 2); ?></td>
                                                                <td><?= number_format($Item->Quantity, 2); ?></td>
                                                                <td><i class="fa fa-inr"></i> <?= number_format($Item->Total, 2); ?></td>
                                                            </tr>
                                                            <?php } } ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="3" style="text-align: right;">Total</th>
                                                                <th><?= number_format($TotalQuantity, 2); ?></th>
                                                                <th><i class="fa fa-inr"></i> <?= number_format($PurchaseOrder->SubTotal, 2); ?></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Remark</th>
                                                <td colspan="3" style="white-space: unset;">
                                                    <?= $PurchaseOrder->Remark; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created</th>
                                                <td><?= $PurchaseOrder->Created; ?></td>
                                                <th>Created By</th>
                                                <td><?= $PurchaseOrder->CreatedBy; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Updated</th>
                                                <td><?= $PurchaseOrder->Updated; ?></td>
                                                <th>Updated By</th>
                                                <td><?= $PurchaseOrder->UpdatedBy; ?></td>
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
    <!-- /.content -->
 </div>