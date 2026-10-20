<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-maroon">
                                    Stock Summary
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xl-6 col-lg-6 col-xxl-6 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-purple mb-0 mt-2 mt-md-0">
                                        <div class="inner">
                                            <h3>Raw Goods</h3>
                                            <p>Stock Summary</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-file" style="opacity: 0.20;"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>stocks_report/rawGoodsStockSummary" class="small-box-footer" title="Raw Goods">
                                            More Info <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered">
                                            <thead class="bg-purple">
                                                <tr>
                                                    <th>Quantity</th>
                                                    <th>Rate</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $stockWhere = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1];
                                                    if (isset($rawProducts) && !empty($rawProducts)) {
                                                        $totalRawGoodsQty = 0;
                                                        $totalRawGoodsValue = 0;
                                                        foreach ($rawProducts as $key => $product) {
                                                            $purchaseQuery = $this->db->select('SUM(IFNULL(qty, 0)) AS quantity, AVG(rate) AS price, SUM(IFNULL(netAmount, 0)) AS total')
                                                                ->where($stockWhere)
                                                                ->where('product_id', $product->id)
                                                                ->where('type', 'Purchase')
                                                                ->get('invoice_product')
                                                                ->row();

                                                            $estimatePurchaseQuery = $this->db->select('SUM(IFNULL(qty, 0)) AS quantity, AVG(rate) AS price, SUM(IFNULL(netAmount, 0)) AS total')
                                                                ->where($stockWhere)
                                                                ->where('product_id', $product->id)
                                                                ->where('type', 'Purchase')
                                                                ->get('estimate_product')
                                                                ->row();
                                                            
                                                            $rawMaterialQuery = $this->db->select('MainProductId, SUM(IFNULL(Quantity, 0)) AS quantity, AVG(Price) AS price')
                                                                ->where('CompId', $compId)
                                                                ->where('UserId', $userId)
                                                                ->where('ProductId', $product->id)
                                                                ->where('IsDeleted', 1)
                                                                ->get('raw_material_products')
                                                                ->row();

                                                            $manufacturerQuery = $this->db->select('SUM(IFNULL(Quantity, 0)) AS quantity, AVG(Price) AS price, SUM(IFNULL(Total, 0)) AS total')
                                                                ->where('ProductId', $rawMaterialQuery->MainProductId)
                                                                ->where('UserId', $userId)
                                                                ->where('CompId', $compId)
                                                                ->where('IsDeleted', 1)
                                                                ->get('stock_manufacturer_products')
                                                                ->row();
                                                            
                                                            $rawGoodsInwardQty = ($product->quantity + $purchaseQuery->quantity + $estimatePurchaseQuery->quantity);
                                                            $rawGoodsOutwardQty = ($rawMaterialQuery->quantity * $manufacturerQuery->quantity);
                                                            $closingRawGoodsQty = ($rawGoodsInwardQty - $rawGoodsOutwardQty);
                                                            $totalRawGoodsQty += $closingRawGoodsQty;
                                                            
                                                            $rawGoodsInwardValue = (($product->quantity * $product->price) + $purchaseQuery->total + $estimatePurchaseQuery->total);
                                                            $rawGoodsOutwardValue = (($rawMaterialQuery->quantity * $rawMaterialQuery->price) + $manufacturerQuery->total);
                                                            $closingRawGoodsValue = ($rawGoodsInwardValue - $rawGoodsOutwardValue);
                                                            $totalRawGoodsValue += $closingRawGoodsValue;
                                                            
                                                            $rawGoodsAvgPrice = ($totalRawGoodsQty != 0) ? $totalRawGoodsValue / $totalRawGoodsQty: 0;
                                                        }
                                                ?>
                                                <tr>
                                                    <td><?= indianMoneyFormat($totalRawGoodsQty); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($rawGoodsAvgPrice); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalRawGoodsValue); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xl-6 col-lg-6 col-xxl-6 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-success mb-0 mt-2 mt-md-0">
                                        <div class="inner">
                                            <h3>Finish Goods</h3>
                                            <p>Stock Summary</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-file-signature" style="opacity: 0.20;"></i>
                                        </div>
                                        <a href="<?= base_url(); ?>stocks_report/finishGoodsStockSummary" class="small-box-footer" title="Finish Goods">
                                            More Info <i class="fa fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered">
                                            <thead class="bg-success">
                                                <tr>
                                                    <th>Quantity</th>
                                                    <th>Rate</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($finishProducts) && !empty($finishProducts)) {
                                                        $totalFinishGoodsQty = 0;
                                                        $totalFinishGoodsValue = 0;
                                                        foreach ($finishProducts as $key => $product) {
                                                            $inwardQuery = $this->db->select('SUM(IFNULL(Quantity, 0)) AS quantity, AVG(Price) AS price, SUM(IFNULL(Total, 0)) AS total')
                                                                ->where('UserId', $userId)
                                                                ->where('CompId', $compId)
                                                                ->where('ProductId', $product->id)
                                                                ->where('IsDeleted', 1)
                                                                ->get('stock_manufacturer_products')
                                                                ->row();

                                                            $saleQuery = $this->db->select('SUM(IFNULL(qty, 0)) AS quantity, AVG(rate) AS price, SUM(IFNULL(netAmount, 0)) AS total')
                                                                ->where($stockWhere)
                                                                ->where('product_id', $product->id)
                                                                ->where('type', 'Sale')
                                                                ->get('invoice_product')
                                                                ->row();

                                                            $estimateSaleQuery = $this->db->select('SUM(IFNULL(qty, 0)) AS quantity, AVG(rate) AS price, SUM(IFNULL(netAmount, 0)) AS total')
                                                                ->where($stockWhere)
                                                                ->where('product_id', $product->id)
                                                                ->where('type', 'Sale')
                                                                ->get('estimate_product')
                                                                ->row();
                                                            
                                                            $finishGoodsInwardQty = ($product->quantity + $inwardQuery->quantity);
                                                            $finishGoodsOutwardQty = ($saleQuery->quantity + $estimateSaleQuery->quantity);
                                                            $closingFinishGoodsQty = ($finishGoodsInwardQty - $finishGoodsOutwardQty);
                                                            $totalFinishGoodsQty += $closingFinishGoodsQty;
                                                            
                                                            $finishGoodsInwardValue = (($product->quantity * $product->price) + $inwardQuery->total);
                                                            $finishGoodsOutwardValue = ($saleQuery->total + $estimateSaleQuery->total);
                                                            $closingFinishGoodsValue = ($finishGoodsInwardValue - $finishGoodsOutwardValue);
                                                            $totalFinishGoodsValue += $closingFinishGoodsValue;
                                                            
                                                            $finishGoodsAvgPrice = ($totalFinishGoodsQty != 0) ? $totalFinishGoodsValue / $totalFinishGoodsQty: 0;
                                                        }                                                
                                                ?>
                                                <tr>
                                                    <td><?= indianMoneyFormat($totalFinishGoodsQty); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($finishGoodsAvgPrice); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalFinishGoodsValue); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
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