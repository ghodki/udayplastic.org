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
                                    Raw Goods Stock Summary
                                </h5>
                            </div>
                            <a href="<?= base_url(); ?>stocks_report/stockSummary" class="btn btn-sm bg-purple pull-right" title="Back">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <form method="GET">
                                        <div class="row">
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                                <div class="form-group">
                                                    <input type="date" name="from" value="<?= set_value('from', $this->input->get('from') ? $this->input->get('from') : ''); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                                <div class="form-group">
                                                    <input type="date" name="to" value="<?= set_value('to', $this->input->get('to') ? $this->input->get('to') : ''); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-lg-3">
                                                <div class="form-group">
                                                    <select name="productId" id="productId" class="form-control form-control-sm" title="Product">
                                                        <option value="All" <?= $this->input->get('productId') == '' || $this->input->get('productId') == 'All' ? 'selected' : ''; ?>>All Product</option>
                                                        <?php if (!empty($products)) { foreach ($products as $key => $product) { ?>
                                                        <option value="<?= $product->id; ?>" <?= $this->input->get('productId') == $product->id ? 'selected' : ''; ?>><?= $product->p_name; ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4">
                                                <button type="submit" class="btn bg-purple btn-sm">Search</button>
                                                <a href="<?= base_url(); ?>stocks_report/rawGoodsStockSummary" class="btn btn-danger btn-sm">Reset</a>
                                                <button class="btn bg-purple btn-sm" onclick="printTable();">Print</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                    <div class="table-responsive mt-2">
                                        <table class="table table-bordered table-sm" id="print">
                                            <thead class="bg-purple">
                                                <tr class="text-center">
                                                    <th>Sr</th>
                                                    <th>Particulars</th>
                                                    <th colspan="3">Opening Balance</th>
                                                    <th colspan="3">Inwards</th>
                                                    <th colspan="3">Outwards</th>
                                                    <th colspan="3">Closing Balance</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Quantity</th>
                                                    <th>Rate</th>
                                                    <th>Value</th>
                                                    <th>Quantity</th>
                                                    <th>Rate</th>
                                                    <th>Value</th>
                                                    <th>Quantity</th>
                                                    <th>Rate</th>
                                                    <th>Value</th>
                                                    <th>Quantity</th>
                                                    <th>Rate</th>
                                                    <th>Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sr = 0;
                                                    $totalOpeningQty = 0;
                                                    $totalInwardsQty = 0;
                                                    $totalOutwardsQty = 0;
                                                    $totalClosingQty = 0;
                                                    $totalOpeningValue = 0;
                                                    $totalInwardsValue = 0;
                                                    $totalOutwardsValue = 0;
                                                    $totalClosingValue = 0;
                                                    if (isset($products) && !empty($products)) {
                                                        $where = ['comp_id' => $compId, 'user_id' => $userId, 'status' => 1];
                                                        $outwardWhere = ['CompId' => $compId, 'UserId' => $userId, 'IsDeleted' => 1];
                                                        if ($this->input->get('from') && $this->input->get('to')) {
                                                            $where['invoiceDate >='] = $this->input->get('from');
                                                            $where['invoiceDate <='] = $this->input->get('to');
                                                            $outwardWhere['Date >='] = $this->input->get('from');
                                                            $outwardWhere['Date <='] = $this->input->get('to');
                                                        }
                                                                                                                
                                                        foreach ($products as $key => $product) {
                                                            $sr++;
                                                            
                                                            //Opening
                                                            $openingQty = (float) ($product->quantity ?? 0);
                                                            $openingPrice = (float) ($product->price ?? 0);
                                                            $totalOpeningQty += $openingQty;
                                                            $openingValue = ($openingQty * $openingPrice);
                                                            $totalOpeningValue += $openingValue;
                                                            
                                                            // Inward
                                                            $purchaseQuery = $this->db->select('SUM(IFNULL(qty, 0)) AS quantity, AVG(rate) AS price, SUM(IFNULL(netAmount, 0)) AS total')
                                                                ->where($where)
                                                                ->where('type', 'Purchase')
                                                                ->where('product_id', $product->id)
                                                                ->get('invoice_product')
                                                                ->row();
                                                            
                                                            $estimatePurchaseQuery = $this->db->select('SUM(IFNULL(qty, 0)) AS quantity, AVG(rate) AS price, SUM(IFNULL(netAmount, 0)) AS total')
                                                                ->where($where)
                                                                ->where('type', 'Purchase')
                                                                ->where('product_id', $product->id)
                                                                ->get('estimate_product')
                                                                ->row();
                                                            
                                                            $purchaseQty = (float) (is_object($purchaseQuery) ? ($purchaseQuery->quantity ?? 0) : 0);
                                                            $purchasePrice = (float) (is_object($purchaseQuery) ? ($purchaseQuery->price ?? 0) : 0);
                                                            $purchaseTotal = (float) (is_object($purchaseQuery) ? ($purchaseQuery->total ?? 0) : 0);
                                                            $estimateQty = (float) (is_object($estimatePurchaseQuery) ? ($estimatePurchaseQuery->quantity ?? 0) : 0);
                                                            $estimatePrice = (float) (is_object($estimatePurchaseQuery) ? ($estimatePurchaseQuery->price ?? 0) : 0);
                                                            $estimateTotal = (float) (is_object($estimatePurchaseQuery) ? ($estimatePurchaseQuery->total ?? 0) : 0);

                                                            $inwardQty = ($purchaseQty + $estimateQty);
                                                            if ($inwardQty > 0) {
                                                                $inwardAvgPrice = (($purchaseQty * $purchasePrice) + ($estimateQty * $estimatePrice)) / $inwardQty;
                                                            } else {
                                                                $inwardAvgPrice = 0;
                                                            }
                                                            $inwardValue = ($purchaseTotal + $estimateTotal);
                                                            
                                                            $totalInwardsQty += $inwardQty;
                                                            $totalInwardsValue += $inwardValue;                                                            
                                                            
                                                            // Outward
                                                            $rawMaterialQuery = $this->db->select('MainProductId, SUM(IFNULL(Quantity, 0)) AS quantity, AVG(Price) AS price')
                                                                ->where('CompId', $compId)
                                                                ->where('UserId', $userId)
                                                                ->where('IsDeleted', 1)
                                                                ->where('ProductId', $product->id)
                                                                ->get('raw_material_products')
                                                                ->row();
                                                            
                                                            $rawMainProductId = is_object($rawMaterialQuery) ? ($rawMaterialQuery->MainProductId ?? null) : null;
                                                            $rawQty = (float) (is_object($rawMaterialQuery) ? ($rawMaterialQuery->quantity ?? 0) : 0);
                                                            $rawPrice = (float) (is_object($rawMaterialQuery) ? ($rawMaterialQuery->price ?? 0) : 0);

                                                            $manufacturerQuery = null;
                                                            if (!empty($rawMainProductId)) {
                                                                $manufacturerQuery = $this->db->select('SUM(IFNULL(Quantity, 0)) AS quantity, AVG(Price) AS price, SUM(IFNULL(Total, 0)) AS total')
                                                                    ->where($outwardWhere)
                                                                    ->where('ProductId', $rawMainProductId)                                                                
                                                                    ->get('stock_manufacturer_products')
                                                                    ->row();
                                                            }
                                                            
                                                            $manuQty = (float) (is_object($manufacturerQuery) ? ($manufacturerQuery->quantity ?? 0) : 0);
                                                            $manuPrice = (float) (is_object($manufacturerQuery) ? ($manufacturerQuery->price ?? 0) : 0);
                                                            $manuTotal = (float) (is_object($manufacturerQuery) ? ($manufacturerQuery->total ?? 0) : 0);

                                                            $outwardQty = ($manuQty * $rawQty);
                                                            $outwardAvgPrice = ($outwardQty) ? (($manuQty * $manuPrice) + ($rawQty * $rawPrice)) / $outwardQty : 0;
                                                            $outwardValue = (($rawQty * $rawPrice) + $manuTotal);
                                                            
                                                            $totalOutwardsQty += $outwardQty;
                                                            $totalOutwardsValue += $outwardValue;                                                                                                                        
                                                            
                                                            // Closing
                                                            $closingQty = (($openingQty + $inwardQty) - $outwardQty);
                                                            $totalClosingQty += $closingQty;  
                                                            
                                                            $closingValue = (($openingValue + $inwardValue) - $outwardValue);
                                                            $totalClosingValue += $closingValue;
                                                            
                                                            $closingAvgPrice = ($closingQty != 0) ? ($closingValue / $closingQty) : 0;
                                                ?>
                                                <tr>
                                                    <td><?= $sr; ?></td>
                                                    <td><?= $product->p_name; ?></td>
                                                    <td><?= indianMoneyFormat($openingQty); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($openingPrice); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($openingValue); ?></td>
                                                    <td><?= indianMoneyFormat($inwardQty); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($inwardAvgPrice); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($inwardValue); ?></td>
                                                    <td><?= indianMoneyFormat($outwardQty); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($outwardAvgPrice); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($outwardValue); ?></td>
                                                    <td><?= indianMoneyFormat($closingQty); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($closingAvgPrice); ?></td>
                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($closingValue); ?></td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-right">Total</th>
                                                    <th><?= indianMoneyFormat($totalOpeningQty); ?></th>
                                                    <th></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalOpeningValue); ?></th>
                                                    <th><?= indianMoneyFormat($totalInwardsQty); ?></th>
                                                    <th></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalInwardsValue); ?></th>
                                                    <th><?= indianMoneyFormat($totalOutwardsQty); ?></th>
                                                    <th></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalOutwardsValue); ?></th>
                                                    <th><?= indianMoneyFormat($totalClosingQty); ?></th>
                                                    <th></th>
                                                    <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalClosingValue); ?></th>
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
    $(document).ready(function() {
        $("#productId").select2();
    });
    
    function printTable() {
        var originalContent = document.body.innerHTML;
        var tableContent = document.getElementById("print").outerHTML;

        document.body.innerHTML = tableContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>