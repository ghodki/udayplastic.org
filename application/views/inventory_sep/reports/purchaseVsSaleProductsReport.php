<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .btn-group {
        display: block;
    }
    .custom-select {
        height: calc(2rem + 1px);
        line-height: 1;
    }
    .multiselect-container {
        transform: translate3d(0px, 32px, 0px) !important;
    }
    .multiselect-container .multiselect-option {
        padding: 0px 5px 0px 5px;
    }
    .dropdown-menu.show {
        height: auto;
        max-height: 200px;
        overflow-y: scroll;
    }
    .dropdown-menu {
        min-width: 24.6rem;
    }
</style>

<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
                <div class="container-fluid">
                        <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                                        <div class="card card-dark card-outline">
                                                <div class="card-header">
                                                        <div class="card-title">
                                                                <h5 class="text-dark">
                                                                        Purchase Vs Sale Invoices Products Report
                                                                </h5>
                                                        </div>
                                                </div>
                                                <div class="card-body">
                                                        <div class="row">
                                                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                                                        <form id="reportForm" action="<?= base_url(); ?>Reports/purchaseVsSaleProductsReport" method="GET">
                                                                                <?php
                                                                                    $selectedProductId = function_exists('input_get_array') ? input_get_array('productId') : (array) ($this->input->get('productId') ?: []);
                                                                                    $selectedCategoryId = function_exists('input_get_array') ? input_get_array('categoryId') : (array) ($this->input->get('categoryId') ?: []);
                                                                                    $selectedBrandId = function_exists('input_get_array') ? input_get_array('brandId') : (array) ($this->input->get('brandId') ?: []);
                                                                                ?>
                                                                                <div class="row">
                                                                                        <div class="col-md-3 col-sm-4 form-group">
                                                                                            <label for="productId">
                                                                                                    Products
                                                                                                    <a href="javascript:void(0);" id="productRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                    </a>
                                                                                            </label>
                                                                                            <select name="productId[]" id="productId" class="form-control form-control-sm" title="Products" multiple="">
                                                                                                <option <?= in_array('All', $selectedProductId, true) ? "selected" : ""; ?> value="All" <?= set_select('productId', 'All'); ?>>All</option>
                                                                                                <?php if (!empty($products)) { foreach ($products as $key => $product) { ?>
                                                                                                <option <?= in_array($product->id, $selectedProductId) ? "selected" : ""; ?> value="<?= $product->id; ?>" <?= set_select('productId', $product->id); ?>><?= $product->p_name; ?></option>
                                                                                                <?php } } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-sm-3 form-group">
                                                                                            <label for="categoryId">
                                                                                                    Categories
                                                                                                    <a href="javascript:void(0);" id="categoryRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                    </a>
                                                                                            </label>
                                                                                            <select name="categoryId[]" id="categoryId" class="form-control form-control-sm" title="Categories" multiple="">
                                                                                                <option <?= in_array('All', $selectedCategoryId, true) ? "selected" : ""; ?> value="All" <?= set_select('categoryId', 'All'); ?>>All</option>
                                                                                                <?php if (!empty($categories)) { foreach ($categories as $key => $category) { ?>
                                                                                                <option <?= in_array($category->id, $selectedCategoryId) ? "selected" : ""; ?> value="<?= $category->id; ?>" <?= set_select('categoryId', $category->id); ?>><?= $category->name; ?></option>
                                                                                                <?php } } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3 col-sm-3 form-group">
                                                                                            <label for="brandId">
                                                                                                    Brands
                                                                                                    <a href="javascript:void(0);" id="brandRefresh" class="text-danger ml-1" title="Refresh" style="font-size: 12px;">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                    </a>
                                                                                            </label>
                                                                                            <select name="brandId[]" id="brandId" class="form-control form-control-sm" title="Brands" multiple="">
                                                                                                <option <?= in_array('All', $selectedBrandId, true) ? "selected" : ""; ?> value="All" <?= set_select('brandId', 'All'); ?>>All</option>
                                                                                                <?php if (!empty($brands)) { foreach ($brands as $key => $brand) { ?>
                                                                                                <option <?= in_array($brand->id, $selectedBrandId) ? "selected" : ""; ?> value="<?= $brand->id; ?>" <?= set_select('brandId', $brand->id); ?>><?= $brand->name; ?></option>
                                                                                                <?php } } ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-1 col-sm-1 form-group">
                                                                                            <label for="estimate">Estimate</label>
                                                                                            <br />
                                                                                            <input type="checkbox" name="estimate" value="true" id="estimate" <?= $this->input->get('estimate') == true ? "checked" : "unchecked"; ?> />
                                                                                            Estimate
                                                                                        </div>                                                                                        
                                                                                        <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2" style="margin-top: 28px;">
                                                                                                <button type="submit" class="btn btn-sm bg-olive">
                                                                                                        <i class="fa fa-search text-white"></i> Search
                                                                                                </button>
                                                                                                <a href="<?= base_url(); ?>reports/purchaseVsSaleProductsReport" class="btn btn-danger btn-sm" title="Refresh">
                                                                                                        <i class="fa fa-refresh"></i>
                                                                                                </a>
                                                                                        </div>
                                                                                </div>
                                                                        </form>
                                                                </div>
                                                                <div class="col-md-12 col-sm-12 col-xl-12 col-lg-12 col-xxl-12 col-xs-12">
                                                                        <div class="table-responsive mt-2">
                                                                                <table class="table table-sm table-bordered">
                                                                                        <?php if ($this->input->get('estimate') == "") { ?>
                                                                                        <thead class="bg-dark">
                                                                                                <tr>
                                                                                                        <th class="text-white">Sr</th>
                                                                                                        <th class="text-white">Product Name</th>
                                                                                                        <th class="text-white">Purchase Qty</th>
                                                                                                        <th class="text-white">Purchase Avg Price</th>
                                                                                                        <th class="text-white">Purchase Total</th>
                                                                                                        <th class="text-white">Sale Qty</th>
                                                                                                        <th class="text-white">Sale Avg Price</th>
                                                                                                        <th class="text-white">Sale Total</th>
                                                                                                        <th class="text-white">Profit/Loss</th>
                                                                                                </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                                <?php 
                                                                                                        $totalPurchaseQty = 0; 
                                                                                                        $totalPurchaseAmount = 0; 
                                                                                                        $totalSaleQty = 0; 
                                                                                                        $totalSaleAmount = 0; 
                                                                                                        $srNo = 1; // Serial number

                                                                                                        $aggregatedData = [];
                                                                                                        foreach ($purchaseVsSaleInvoicesProducts as $product) {
                                                                                                                $isPurchase = ($product->type === 'Purchase');
                                                                                                                $isSale = ($product->type === 'Sale');

                                                                                                                // Initialize the product data if not already done
                                                                                                                if (!isset($aggregatedData[$product->product_id])) {
                                                                                                                        $aggregatedData[$product->product_id] = [
                                                                                                                                'product_id' => $product->product_id,
                                                                                                                                'name' => $product->p_name,
                                                                                                                                'purchaseQty' => 0,
                                                                                                                                'purchaseAmount' => 0,
                                                                                                                                'saleQty' => 0,
                                                                                                                                'saleAmount' => 0,
                                                                                                                        ];
                                                                                                                }

                                                                                                                // Aggregate purchase or sale data
                                                                                                                if ($isPurchase) {
                                                                                                                        $aggregatedData[$product->product_id]['purchaseQty'] += $product->qty;
                                                                                                                        $aggregatedData[$product->product_id]['purchaseAmount'] += bcdiv(($product->qty * $product->rate), '1', 2);
                                                                                                                } elseif ($isSale) {
                                                                                                                        $aggregatedData[$product->product_id]['saleQty'] += $product->qty;
                                                                                                                        $aggregatedData[$product->product_id]['saleAmount'] += bcdiv(($product->qty * $product->rate), '1', 2);
                                                                                                                }
                                                                                                        }

                                                                                                        // Calculate totals for the footer
                                                                                                        foreach ($aggregatedData as $data) {
                                                                                                                $totalPurchaseQty += $data['purchaseQty'];
                                                                                                                $totalPurchaseAmount += $data['purchaseAmount'];
                                                                                                                $totalSaleQty += $data['saleQty'];
                                                                                                                $totalSaleAmount += $data['saleAmount'];
                                                                                                        }

                                                                                                        // Calculate average purchase and sale rates for footer profit/loss calculation
                                                                                                        $averagePurchaseRate = ($totalPurchaseQty > 0) ? bcdiv(($totalPurchaseAmount / $totalPurchaseQty), '1', 2) : 0;
                                                                                                        $averageSaleRate = ($totalSaleQty > 0) ? bcdiv(($totalSaleAmount / $totalSaleQty), '1', 2) : 0;

                                                                                                        // Calculate total profit/loss in footer
                                                                                                        $totalProfitLoss = bcdiv((($averageSaleRate - $averagePurchaseRate) * $totalSaleQty), '1', 2);


                                                                                                        // Display aggregated data
                                                                                                        foreach ($aggregatedData as $productId => $data) {
                                                                                                                $averagePurchaseRate = ($data['purchaseQty'] > 0) ? bcdiv(($data['purchaseAmount'] / $data['purchaseQty']), '1', 2) : 0;
                                                                                                                $averageSaleRate = ($data['saleQty'] > 0) ? bcdiv(($data['saleAmount'] / $data['saleQty']), '1', 2) : 0;

                                                                                                                // Calculate profit/loss based on average rates
                                                                                                                $profitLoss = bcdiv((($averageSaleRate - $averagePurchaseRate) * $data['saleQty']), '1', 2);
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td><?= $srNo++; ?></td>
                                                                                                    <td>
                                                                                                            <a href="javascript:void(0);" onclick="getProductSaleAndPurchaseInfo(<?= $data['product_id']; ?>);" title="More Info">
                                                                                                                    <?= htmlspecialchars($data['name']); ?>
                                                                                                            </a>
                                                                                                    </td>
                                                                                                    <td><?= indianMoneyFormat($data['purchaseQty']); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($averagePurchaseRate); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data['purchaseAmount']); ?></td>
                                                                                                    <td><?= indianMoneyFormat($data['saleQty']); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($averageSaleRate); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data['saleAmount']); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($profitLoss); ?></td>
                                                                                                </tr>
                                                                                                <?php } ?>
                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                                <tr>
                                                                                                        <th colspan="2" style="text-align: right;">Total</th>
                                                                                                       <th><?= indianMoneyFormat($totalPurchaseQty); ?></th>
                                                                                                        <th></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalPurchaseAmount); ?></th>
                                                                                                        <th><?= indianMoneyFormat($totalSaleQty); ?></th>
                                                                                                        <th></th>
                                                                                                        <th><i class="fa fa-inr"></i > <?= indianMoneyFormat($totalSaleAmount); ?></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalProfitLoss); ?></th> 
                                                                                                </tr>
                                                                                        </tfoot>
                                                                                        <?php } elseif ($this->input->get('estimate') == true) { ?>
                                                                                        <thead class="bg-dark">
                                                                                                <tr>
                                                                                                        <th class="text-white">Sr</th>
                                                                                                        <th class="text-white">Product Name</th>
                                                                                                        <th class="text-white">Purchase Qty</th>
                                                                                                        <th class="text-white">Purchase Avg Price</th>
                                                                                                        <th class="text-white">Purchase Total</th>
                                                                                                        <th class="text-white">Sale Qty</th>
                                                                                                        <th class="text-white">Sale Avg Price</th>
                                                                                                        <th class="text-white">Sale Total</th>
                                                                                                        <th class="text-white">Profit/Loss</th>
                                                                                                </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                                <?php 
                                                                                                        $totalPurchaseQty = 0; 
                                                                                                        $totalPurchaseAmount = 0; 
                                                                                                        $totalSaleQty = 0; 
                                                                                                        $totalSaleAmount = 0; 
                                                                                                        $srNo = 1; // Serial number

                                                                                                        $aggregatedData = [];
                                                                                                        foreach ($purchaseVsSaleWithEstimateInvoicesProducts as $product) {
                                                                                                                $isPurchase = ($product->type === 'Purchase');
                                                                                                                $isSale = ($product->type === 'Sale');

                                                                                                                // Initialize the product data if not already done
                                                                                                                if (!isset($aggregatedData[$product->product_id])) {
                                                                                                                        $aggregatedData[$product->product_id] = [
                                                                                                                                'product_id' => $product->product_id,
                                                                                                                                'name' => $product->p_name,
                                                                                                                                'purchaseQty' => 0,
                                                                                                                                'purchaseAmount' => 0,
                                                                                                                                'saleQty' => 0,
                                                                                                                                'saleAmount' => 0,
                                                                                                                        ];
                                                                                                                }

                                                                                                                // Aggregate purchase or sale data
                                                                                                                if ($isPurchase) {
                                                                                                                        $aggregatedData[$product->product_id]['purchaseQty'] += $product->qty;
                                                                                                                        $aggregatedData[$product->product_id]['purchaseAmount'] += bcdiv(($product->qty * $product->rate), '1', 2);
                                                                                                                } elseif ($isSale) {
                                                                                                                        $aggregatedData[$product->product_id]['saleQty'] += $product->qty;
                                                                                                                        $aggregatedData[$product->product_id]['saleAmount'] += bcdiv(($product->qty * $product->rate), '1', 2);
                                                                                                                }
                                                                                                        }

                                                                                                        // Calculate totals for the footer
                                                                                                        foreach ($aggregatedData as $data) {
                                                                                                                $totalPurchaseQty += $data['purchaseQty'];
                                                                                                                $totalPurchaseAmount += $data['purchaseAmount'];
                                                                                                                $totalSaleQty += $data['saleQty'];
                                                                                                                $totalSaleAmount += $data['saleAmount'];
                                                                                                        }

                                                                                                        // Calculate average purchase and sale rates for footer profit/loss calculation
                                                                                                        $averagePurchaseRate = ($totalPurchaseQty > 0) ? bcdiv(($totalPurchaseAmount / $totalPurchaseQty), '1', 2) : 0;
                                                                                                        $averageSaleRate = ($totalSaleQty > 0) ? bcdiv(($totalSaleAmount / $totalSaleQty), '1', 2) : 0;

                                                                                                        // Calculate total profit/loss in footer
                                                                                                        $totalProfitLoss = bcdiv((($averageSaleRate - $averagePurchaseRate) * $totalSaleQty), '1', 2);


                                                                                                        // Display aggregated data
                                                                                                        foreach ($aggregatedData as $productId => $data) {
                                                                                                                $averagePurchaseRate = ($data['purchaseQty'] > 0) ? bcdiv(($data['purchaseAmount'] / $data['purchaseQty']), '1', 2) : 0;
                                                                                                                $averageSaleRate = ($data['saleQty'] > 0) ? bcdiv(($data['saleAmount'] / $data['saleQty']), '1', 2) : 0;

                                                                                                                // Calculate profit/loss based on average rates
                                                                                                                $profitLoss = bcdiv((($averageSaleRate - $averagePurchaseRate) * $data['saleQty']), '1', 2);
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td><?= $srNo++; ?></td>
                                                                                                    <td>
                                                                                                            <a href="javascript:void(0);" onclick="getProductSaleAndPurchaseInfo(<?= $data['product_id']; ?>);" title="More Info">
                                                                                                                    <?= htmlspecialchars($data['name']); ?>
                                                                                                            </a>
                                                                                                    </td>
                                                                                                    <td><?= indianMoneyFormat($data['purchaseQty']); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($averagePurchaseRate); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data['purchaseAmount']); ?></td>
                                                                                                    <td><?= indianMoneyFormat($data['saleQty']); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($averageSaleRate); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($data['saleAmount']); ?></td>
                                                                                                    <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($profitLoss); ?></td>
                                                                                                </tr>
                                                                                                <?php } ?>
                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                                <tr>
                                                                                                        <th colspan="2" style="text-align: right;">Total</th>
                                                                                                       <th><?= indianMoneyFormat($totalPurchaseQty); ?></th>
                                                                                                        <th></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalPurchaseAmount); ?></th>
                                                                                                        <th><?= indianMoneyFormat($totalSaleQty); ?></th>
                                                                                                        <th></th>
                                                                                                        <th><i class="fa fa-inr"></i > <?= indianMoneyFormat($totalSaleAmount); ?></th>
                                                                                                        <th><i class="fa fa-inr"></i> <?= indianMoneyFormat($totalProfitLoss); ?></th> 
                                                                                                </tr>
                                                                                        </tfoot>
                                                                                        <?php } ?>
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

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content"></div>
        </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
        $('#productId').multiselect({
            buttonWidth: 'auto',
            enableFiltering: true,
            includeFilterClearBtn: true,
            enableCaseInsensitiveFiltering: true
        });

        $('#categoryId').multiselect({
            buttonWidth: 'auto',
            enableFiltering: true,
            includeFilterClearBtn: true,
            enableCaseInsensitiveFiltering: true
        });

        $('#brandId').multiselect({
            buttonWidth: 'auto',
            enableFiltering: true,
            includeFilterClearBtn: true,
            enableCaseInsensitiveFiltering: true
        });

        $("#productRefresh").click(function () {
            $("#productId option:selected").each(function() {
                $(this).prop("selected", false);
            });
            $("#productId").multiselect("refresh");
        });

        $("#categoryRefresh").click(function () {
            $("#categoryId option:selected").each(function() {
                $(this).prop("selected", false);
            });
            $("#categoryId").multiselect("refresh");
        });

        $("#brandRefresh").click(function () {
            $("#brandId option:selected").each(function() {
                $(this).prop("selected", false);
            });
            $("#brandId").multiselect("refresh");
        });
        
        $(document).ready(function() {
                $('.table').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                               {
                                       extend: 'copy',
                                       footer: true
                               },
                               {
                                       extend: 'excel',
                                       footer: true
                               },
                               {
                                       extend: 'pdf',
                                       footer: true
                               },
                               {
                                       extend: 'print',
                                       footer: true
                               },
                               "pageLength"
                        ],
                       "lengthMenu": [[20, 100, 250, -1], [20, 100, 250, "All"]]
                });
        });
        
        const getProductSaleAndPurchaseInfo = (productId) => {
                if ($("#estimate").is(":checked")) {
                    var estimateValue = $("#estimate").val();
                } else {
                    var estimateValue = "";
                }            

                $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>reports/getProductSaleAndPurchaseInfo?estimate=" + estimateValue + "&productId=" + productId);
                $('#Info').modal('show');
        };        
</script>