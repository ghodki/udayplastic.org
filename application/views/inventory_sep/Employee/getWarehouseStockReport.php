<?php $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));"); ?>

<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .table thead tr th, .table tfoot tr th {
        font-size: 15px;
    }
    .table tbody tr td {
        font-size: 12px;
    }
    .table thead tr th, .table tbody tr td, .table tfoot tr th {
        padding: 3px 3px;
        border: 0.1px solid #000;
    }
    .card-header-sticky {
        position: sticky;
        top: 0;
        z-index: 99;
        background-color: #ffffff;
    }
    .tableFixHead {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        overflow: auto;
    }
    .tableFixHead tbody {
        width: 100%;
        overflow: auto;
        height: 58vh;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Stock</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Report</a></li>
                        <li class="breadcrumb-item active">Stock Report</li>
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
                <div class="col-md-12">
                    <div class="card card-purple card-outline">
                        <div class="card-header-sticky">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-title">
                                            <h5 class="header-title text-purple">
                                                Stock Report
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <form method="GET" action="<?= base_url(); ?>Employee/getWarehouseStockReport" onsubmit="onFormSubmitHandler(event);" id="reportForm" autocomplete="off" style="margin-top: 10px;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select name="warehouse" id="warehouse" class="form-control form-control-sm" title="Warehouse" required="">
                                                        <?php if (!empty($Warehouses)) { foreach ($Warehouses as $key => $Warehouse) { ?>
                                                        <option value="<?= $Warehouse->id; ?>" <?= set_select('warehouse', $Warehouse->id); ?>><?= $Warehouse->name; ?> - <?= $Warehouse->location; ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <select name="transfer_type" id="transfer_type" class="form-control form-control-sm" title="Transfer Type">
                                                        <option <?= $this->input->get('transfer_type') == "All" ? "selected" : ""; ?> value="All">All</option>
                                                        <option <?= $this->input->get('transfer_type') == "Estimate" ? "selected" : ""; ?> value="Estimate">Estimate</option>
                                                        <option <?= $this->input->get('transfer_type') == "Tax" ? "selected" : ""; ?> value="Tax">Tax</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="checkbox" name="details" value="true" id="details" <?= $this->input->get('details') == "true" ? "checked" : ""; ?> />
                                                    Details View
                                                </div>
                                                <div class="col-md-5">
                                                    <button type="submit" class="btn btn-outline-primary btn-xs">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>Employee/StockReport" class="btn btn-outline-danger btn-xs" title="Refresh">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                    &nbsp;
                                                    <button type="button" id="productRequestOrder" class="btn btn-outline-dark btn-xs">
                                                        <i class="fa fa-shopping-bag"></i> Create Purchase Order
                                                    </button>
                                                    &nbsp;
                                                    <a href="<?= base_url(); ?>Employee/PurchaseOrderReport" class="btn btn-outline-warning btn-xs" title="Purchase Order Report">
                                                        <i class="fa fa-table"></i> Purchase Order Report
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?= base_url(); ?>Employee/getProductDetails" id="tableform" autocomplete="off" style="margin-top: 10px;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tableFixHead">
                                            <?php
                                                $TotalOpeningStock = 0;
                                                $TotalPurchaseQuantity = 0;
                                                $TotalSaleQuantity = 0;
                                                $TotalBalanceQuantity = 0;
                                                $TotalPrice = 0;
                                                $TotalAmount = 0;
                                                $TotalTransferTo = 0;
                                                $TotalTransferFrom = 0;
                                            ?>
                                            <?php if ($this->input->get('details') == "") { ?>
                                            <table class="table table-bordered table-condensed">
                                                <thead class="bg-navy">
                                                    <tr>
                                                        <th class="text-white">Sr</th>
                                                        <th class="text-white">Product Name</th>
                                                        <th class="text-white">Opening Stock</th>
                                                        <th class="text-white">Purchase Quantity</th>
                                                        <th class="text-white">Sale Quantity</th>
                                                        <th class="text-white">Balance Quantity</th>
                                                        <th class="text-white">Price</th>
                                                        <th class="text-white">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if (isset($Products) && !empty($Products)) {
                                                            $UserId = $this->session->userdata('UserId');
                                                            $CompId = $this->session->userdata('CompId');
                                                            $CompBranchId = $this->session->userdata('CompBranchId');
                                                            $Warehouse = $this->input->get('warehouse');

                                                            $CommonWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $CommonWhere['CompanyBranchId'] = $CompBranchId;
                                                            }

                                                            $n = 0;
                                                            $OpeningStock = 0;
                                                            $TotalOpeningStock = 0;
                                                            $TotalPurchaseQuantity = 0;
                                                            $TotalSaleQuantity = 0;
                                                            $TotalBalanceQuantity = 0;
                                                            $Price = 0;
                                                            $TotalAmount = 0;
                                                            foreach ($Products as  $key => $value) {
                                                                $n++;

                                                                $estimate_stock = get_title('comp_profile', $CommonWhere, 'estimate_stock');

                                                                $this->db->select('SUM(Quantity) as smqty');
                                                                $this->db->where(['UserId' => $UserId, 'CompId' => $CompId]);
                                                                $this->db->where('ProductId', $value->id);
                                                                $this->db->where('IsDeleted', 1);
                                                                $this->db->where('WarehouseId', $Warehouse);
                                                                $stock_manu_qty_result = $this->db->get('stock_manufacturer_products')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                    $stock_manu_qty = (float) ($stock_manu_qty_result->smqty ?? 0);
                                                                } else {
                                                                    $stock_manu_qty = 0;
                                                                }
                                                                ////////////////////////////// Stock Manufacturer Quantity ///////////////////////////////////////////

                                                                $this->db->select('SUM(qty) as pqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('status', 1);
                                                                $this->db->where('type', "Purchase");
                                                                $this->db->where('warehouse', $Warehouse);
                                                                $purchase_qty_result = $this->db->get('invoice_product')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                    $purchase_qty = (float) ($purchase_qty_result->pqty ?? 0);
                                                                } else {
                                                                    $purchase_qty = 0;
                                                                }
                                                                ////////////////////////////// Purchase Quantity ///////////////////////////////////////////

                                                                $this->db->select('SUM(qty) as sqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('status', 1);
                                                                $this->db->where('type', "Sale");
                                                                $this->db->where('warehouse', $Warehouse);
                                                                $sale_qty_result = $this->db->get('invoice_product')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                    $sale_qty = (float) ($sale_qty_result->sqty ?? 0);
                                                                } else {
                                                                    $sale_qty = 0;
                                                                }
                                                                ////////////////////////////////// Sale Quantity ////////////////////////////////////////

                                                                $this->db->select('SUM(quantity) as ttqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('transaction_type', 'TO');
                                                                $this->db->where('warehouse_id', $Warehouse);
                                                                if ($this->input->get('transfer_type') == "Tax") {
                                                                    $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                                } elseif ($this->input->get('transfer_type') == "Estimate") {
                                                                    $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                                }
                                                                $transferTo = $this->db->get('transfer_products')->row();
                                                                //////////////////////////////////// Transfer To Quantity ////////////////////////////////////

                                                                $this->db->select('SUM(quantity) as tfqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('transaction_type', 'FROM');
                                                                $this->db->where('warehouse_id', $Warehouse);
                                                                if ($this->input->get('transfer_type') == "Tax") {
                                                                    $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                                } elseif ($this->input->get('transfer_type') == "Estimate") {
                                                                    $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                                }
                                                                $transferFrom = $this->db->get('transfer_products')->row();
                                                                $transferToQty = (float) ($transferTo->ttqty ?? 0);
                                                                $transferFromQty = (float) ($transferFrom->tfqty ?? 0);
                                                                ///////////////////////////////////// Transfer From Quantity ///////////////////////////////////

                                                                $this->db->select('SUM(qty) as esqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('status', 1);
                                                                $this->db->where('type', "Sale");
                                                                $this->db->where('warehouse', $Warehouse);
                                                                $estimate_sale_qty_result = $this->db->get('estimate_product')->row();
                                                                if ($estimate_stock == "Yes") {
                                                                    if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                        $estimate_sale_qty = (float) ($estimate_sale_qty_result->esqty ?? 0);
                                                                    } else {
                                                                        $estimate_sale_qty = 0;
                                                                    }
                                                                } else {
                                                                    $estimate_sale_qty = 0;
                                                                }
                                                                ///////////////////////////////// Estimate Sale Quantity ///////////////////////////////////////

                                                                $this->db->select('SUM(qty) as epqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('status', 1);
                                                                $this->db->where('type', "Purchase");
                                                                $this->db->where('warehouse', $Warehouse);
                                                                $estimate_purchase_qty_result = $this->db->get('estimate_product')->row();
                                                                if ($estimate_stock == "Yes") {
                                                                    if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                        $estimate_purchase_qty = (float) ($estimate_purchase_qty_result->epqty ?? 0);
                                                                    } else {
                                                                        $estimate_purchase_qty = 0;
                                                                    }
                                                                } else {
                                                                    $estimate_purchase_qty = 0;
                                                                }
                                                                //////////////////////////////////// Estimate Purchase Quantity ////////////////////////////////////
                                
                                                                $this->db->select('MainProductId, SUM(Quantity) as rmqty');
                                                                $this->db->where('UserId', $UserId);
                                                                $this->db->where('CompId', $CompId);
                                                                $this->db->where('ProductId', $value->id);
                                                                $this->db->where('IsDeleted', 1);
                                                                $raw_material_qty_result = $this->db->get('raw_material_products')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                    $raw_material_qty = (float) ($raw_material_qty_result->rmqty ?? 0);
                                                                } else {
                                                                    $raw_material_qty = 0;
                                                                }
                                                                //////////////////////////////////// Raw Material Quantity ////////////////////////////////////

                                                                $this->db->select('SUM(Quantity) as smqty_value');
                                                                $this->db->where('UserId', $UserId);
                                                                $this->db->where('CompId', $CompId);
                                                                $this->db->where('ProductId', (!empty($raw_material_qty_result) ? ($raw_material_qty_result->MainProductId ?? 0) : 0));
                                                                $this->db->where('IsDeleted', 1);
                                                                $this->db->where('WarehouseId', $Warehouse);
                                                                $stock_manu_data_qty_result = $this->db->get('stock_manufacturer_products')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                    $stock_manu_data_qty = (float) ($stock_manu_data_qty_result->smqty_value ?? 0);
                                                                } else {
                                                                    $stock_manu_data_qty = 0;
                                                                }
                                                                //////////////////////////////////// Stock Manufacturer Raw Material Quantity ////////////////////////////////////
                                                                
                                                                $TotalOpeningStock += ($value->warehouse == $Warehouse ? $value->qty : $OpeningStock);
                                                                $TotalPurchaseQuantity += ($purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty);
                                                                $TotalSaleQuantity += ($sale_qty + $transferFromQty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty));
                                                                $TotalBalanceQuantity += ((($value->warehouse == $Warehouse ? $value->qty : $OpeningStock) + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty) - ($sale_qty + $transferFromQty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty)));
                                                                $TotalPrice += $value->price;
                                                                $TotalAmount += $value->price * ((($value->warehouse == $Warehouse ? $value->qty : $OpeningStock) + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty) - ($sale_qty + $transferFromQty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty)));

                                                                $BalanceQuantity = ((($value->warehouse == $Warehouse ? $value->qty : $OpeningStock) + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty) - ($sale_qty + $transferFromQty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty)));
                                                    ?>
                                                    <tr>
                                                        <td><?= $n; ?> </td>
                                                        <td>
                                                            <input type="checkbox" id="product_out_stock_<?= $value->id ?>" name="product_out_stock[]" value="<?= $value->id ?>" /> 
                                                            &nbsp;&nbsp;<?= $value->p_name; ?>
                                                        </td>
                                                        <td><?= number_format(($value->warehouse == $Warehouse ? $value->qty : $OpeningStock)); ?></td>
                                                        <td><?= number_format(($purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty), 2); ?></td>
                                                        <td><?= number_format(($sale_qty + $transferFromQty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty)), 2); ?></td>
                                                        <td>
                                                            <?php if ($BalanceQuantity >= $value->min_qty) { ?>
                                                            <span class="text-success">
                                                                <?= number_format($BalanceQuantity, 2); ?>
                                                            </span>
                                                            <?php } elseif ($BalanceQuantity < $value->min_qty) { ?>
                                                            <span class="text-danger">
                                                                <?= number_format($BalanceQuantity, 2); ?>
                                                            </span>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?= !empty($value->price) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($value->price, 2) : "0.00"; ?>
                                                        </td>
                                                        <td>
                                                            <?= !empty($value->price) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($value->price * ((($value->warehouse == $Warehouse ? $value->qty : $OpeningStock) + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty) - ($sale_qty + $transferFromQty + $estimate_sale_qty)), 2) : "0.00"; ?>
                                                        </td>
                                                    </tr>
                                                    <?php } } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th style="text-align: right;" colspan="2">Total</th>
                                                        <th><?= number_format($TotalOpeningStock, 2); ?></th>
                                                        <th><?= number_format($TotalPurchaseQuantity, 2); ?></th>
                                                        <th><?= number_format($TotalSaleQuantity, 2); ?></th>
                                                        <th><?= number_format($TotalBalanceQuantity, 2); ?></th>
                                                        <th><?= !empty($TotalPrice) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($TotalPrice, 2) : "0.00"; ?></th>
                                                        <th><?= !empty($TotalAmount) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($TotalAmount, 2) : "0.00"; ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <?php } elseif ($this->input->get('details') == true) { ?>
                                            <table class="table table-bordered table-condensed">
                                                <thead class="bg-navy">
                                                    <tr>
                                                        <th class="text-white">Sr</th>
                                                        <th class="text-white">Product Name</th>
                                                        <th class="text-white">Opening Stock</th>
                                                        <th class="text-white">Manu. Quantity</th>
                                                        <th class="text-white">Purchase Quantity</th>
                                                        <th class="text-white">Transfer To</th>
                                                        <th class="text-white">Estimate Purchase</th>
                                                        <th class="text-white">Sale Quantity</th>
                                                        <th class="text-white">Transfer From</th>
                                                        <th class="text-white">Estimate Sale</th>
                                                        <th class="text-white">Raw Material</th>
                                                        <th class="text-white">Balance Quantity</th>
                                                        <th class="text-white">Price</th>
                                                        <th class="text-white">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if (isset($Products) && !empty($Products)) {
                                                            $UserId = $this->session->userdata('UserId');
                                                            $CompId = $this->session->userdata('CompId');
                                                            $CompBranchId = $this->session->userdata('CompBranchId');
                                                            $Warehouse = $this->input->get('warehouse');

                                                            $CommonWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $CommonWhere['CompanyBranchId'] = $CompBranchId;
                                                            }

                                                            $n = 0;
                                                            $OpeningStock = 0;
                                                            $TotalOpeningStock = 0;
                                                            $TotalStockManufacturer = 0;
                                                            $TotalPurchaseQuantity = 0;
                                                            $TotalTransferTo = 0;
                                                            $TotalEstimatePurchase = 0;
                                                            $TotalSaleQuantity = 0;
                                                            $TotalTransferFrom = 0;
                                                            $TotalEstimateSale = 0;
                                                            $TotalRawMaterial = 0;
                                                            $TotalBalanceQuantity = 0;
                                                            $Price = 0;
                                                            $TotalPrice = 0;
                                                            $TotalAmount = 0;
                                                            foreach ($Products as  $key => $value) {
                                                                $n++;

                                                                $estimate_stock = get_title('comp_profile', $CommonWhere, 'estimate_stock');

                                                                $this->db->select('SUM(Quantity) as smqty');
                                                                $this->db->where(['UserId' => $UserId, 'CompId' => $CompId]);
                                                                $this->db->where('ProductId', $value->id);
                                                                $this->db->where('IsDeleted', 1);
                                                                $this->db->where('WarehouseId', $Warehouse);
                                                                $stock_manu_qty_result = $this->db->get('stock_manufacturer_products')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                    $stock_manu_qty = (float) ($stock_manu_qty_result->smqty ?? 0);
                                                                } else {
                                                                    $stock_manu_qty = 0;
                                                                }
                                                                ///////////////////////////// Stock Manufacturer Quantity ///////////////////////////////////////////

                                                                $this->db->select('SUM(qty) as pqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('type', "Purchase");
                                                                $this->db->where('status', 1);
                                                                $this->db->where('warehouse', $Warehouse);
                                                                $purchase_qty_result = $this->db->get('invoice_product')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                    $purchase_qty = (float) ($purchase_qty_result->pqty ?? 0);
                                                                } else {
                                                                    $purchase_qty = 0;
                                                                }
                                                                ///////////////////////////// Purchase Quantity ///////////////////////////////////////////

                                                                $this->db->select('SUM(qty) as sqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('status', 1);
                                                                $this->db->where('type', "Sale");
                                                                $this->db->where('warehouse', $Warehouse);
                                                                $sale_qty_result = $this->db->get('invoice_product')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                    $sale_qty = (float) ($sale_qty_result->sqty ?? 0);
                                                                } else {
                                                                    $sale_qty = 0;
                                                                }
                                                                //////////////////////////////// Sale Quantity ////////////////////////////////////////

                                                                $this->db->select('SUM(quantity) as ttqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('transaction_type', 'TO');
                                                                $this->db->where('warehouse_id', $Warehouse);
                                                                if ($this->input->get('transfer_type') == "Tax") {
                                                                    $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                                } elseif ($this->input->get('transfer_type') == "Estimate") {
                                                                    $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                                }
                                                                $transferTo = $this->db->get('transfer_products')->row();
                                                                /////////////////////////////////// Transfer To Quantity //////////////////////////////////////////

                                                                $this->db->select('SUM(quantity) as tfqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('product_id', $value->id);
                                                                $this->db->where('transaction_type', 'FROM');
                                                                $this->db->where('warehouse_id', $Warehouse);
                                                                if ($this->input->get('transfer_type') == "Tax") {
                                                                    $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                                } elseif ($this->input->get('transfer_type') == "Estimate") {
                                                                    $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                                }
                                                                $transferFrom = $this->db->get('transfer_products')->row();
                                                                $transferToQty = (float) ($transferTo->ttqty ?? 0);
                                                                $transferFromQty = (float) ($transferFrom->tfqty ?? 0);
                                                                ///////////////////////////////// Transfer From Quantity //////////////////////////////////////////

                                                                $this->db->select('SUM(qty) as esqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('status', 1);
                                                                $this->db->where('type', "Sale");
                                                                $this->db->where('warehouse', $Warehouse);
                                                                $this->db->where('product_id', $value->id);
                                                                $estimate_sale_qty_result = $this->db->get('estimate_product')->row();
                                                                if ($estimate_stock == "Yes") {
                                                                    if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                        $estimate_sale_qty = (float) ($estimate_sale_qty_result->esqty ?? 0);
                                                                    } else {
                                                                        $estimate_sale_qty = 0;
                                                                    }
                                                                } else {
                                                                    $estimate_sale_qty = 0;
                                                                }
                                                                ///////////////////////////////// Estimate Sale Quantity //////////////////////////////////////////

                                                                $this->db->select('SUM(qty) as epqty');
                                                                $this->db->where($CommonWhere);
                                                                $this->db->where('status', 1);
                                                                $this->db->where('type', "Purchase");
                                                                $this->db->where('warehouse', $Warehouse);
                                                                $this->db->where('product_id', $value->id);
                                                                $estimate_purchase_qty_result = $this->db->get('estimate_product')->row();
                                                                if ($estimate_stock == "Yes") {
                                                                    if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                        $estimate_purchase_qty = (float) ($estimate_purchase_qty_result->epqty ?? 0);
                                                                    } else {
                                                                        $estimate_purchase_qty = 0;
                                                                    }
                                                                } else {
                                                                    $estimate_purchase_qty = 0;
                                                                }
                                                                //////////////////////////////////// Estimate Purchase Quantity /////////////////////////////////////////

                                                                $this->db->select('MainProductId, SUM(Quantity) as rmqty');
                                                                $this->db->where('UserId', $UserId);
                                                                $this->db->where('CompId', $CompId);
                                                                $this->db->where('ProductId', $value->id);
                                                                $this->db->where('IsDeleted', 1);
                                                                $raw_material_qty_result = $this->db->get('raw_material_products')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                    $raw_material_qty = (float) ($raw_material_qty_result->rmqty ?? 0);
                                                                } else {
                                                                    $raw_material_qty = 0;
                                                                }
                                                                //////////////////////////////////// Raw Material Quantity ////////////////////////////////////

                                                                $this->db->select('SUM(Quantity) as smqty_value');
                                                                $this->db->where('UserId', $UserId);
                                                                $this->db->where('CompId', $CompId);
                                                                $this->db->where('ProductId', (!empty($raw_material_qty_result) ? ($raw_material_qty_result->MainProductId ?? 0) : 0));
                                                                $this->db->where('IsDeleted', 1);
                                                                $this->db->where('WarehouseId', $Warehouse);
                                                                $stock_manu_data_qty_result = $this->db->get('stock_manufacturer_products')->row();
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                    $stock_manu_data_qty = (float) ($stock_manu_data_qty_result->smqty_value ?? 0);
                                                                } else {
                                                                    $stock_manu_data_qty = 0;
                                                                }
                                                                //////////////////////////////////// Stock Manufacturer Raw Material Quantity ////////////////////////////////////
                                                                
                                                                $TotalOpeningStock += ($value->warehouse == $Warehouse ? $value->qty : $OpeningStock);
                                                                $TotalStockManufacturer += $stock_manu_qty;
                                                                $TotalPurchaseQuantity += $purchase_qty;
                                                                $TotalTransferTo += $transferToQty;
                                                                $TotalEstimatePurchase += $estimate_purchase_qty;
                                                                $TotalSaleQuantity += $sale_qty;
                                                                $TotalTransferFrom += $transferFromQty;
                                                                $TotalEstimateSale += $estimate_sale_qty;
                                                                $TotalRawMaterial += ($stock_manu_data_qty * $raw_material_qty);
                                                                $TotalBalanceQuantity += ((($value->warehouse == $Warehouse ? $value->qty : $OpeningStock) + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty) - ($sale_qty + $transferFromQty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty)));
                                                                $TotalPrice += $value->price;
                                                                $TotalAmount += $value->price * ((($value->warehouse == $Warehouse ? $value->qty : $OpeningStock) + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty) - ($sale_qty + $transferFromQty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty)));

                                                                $BalanceQuantity = ((($value->warehouse == $Warehouse ? $value->qty : $OpeningStock) + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty) - ($sale_qty + $transferFromQty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty)));
                                                    ?>
                                                    <tr>
                                                        <td><?= $n; ?></td>
                                                        <td><?= $value->p_name; ?></td>
                                                        <td>
                                                            <?= number_format(($value->warehouse == $Warehouse ? $value->qty : $OpeningStock)); ?>
                                                        </td>
                                                        <td><?= number_format($stock_manu_qty, 2); ?></td>
                                                        <td><?= number_format($purchase_qty, 2); ?></td>
                                                        <td><?= number_format($transferToQty, 2); ?></td>
                                                        <td><?= number_format($estimate_purchase_qty, 2); ?></td>
                                                        <td><?= number_format($sale_qty, 2); ?></td>
                                                        <td><?= number_format($transferFromQty, 2); ?></td>
                                                        <td><?= number_format($estimate_sale_qty, 2); ?></td>
                                                        <td><?= number_format(($stock_manu_data_qty * $raw_material_qty), 2); ?></td>
                                                        <td>
                                                            <?php if ($BalanceQuantity >= $value->min_qty) { ?>
                                                            <span class="text-success">
                                                                <?= number_format($BalanceQuantity, 2); ?>
                                                            </span>
                                                            <?php } elseif ($BalanceQuantity < $value->min_qty) { ?>
                                                            <span class="text-danger">
                                                                <?= number_format($BalanceQuantity, 2); ?>
                                                            </span>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?= !empty($value->price) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($value->price, 2) : "0.00"; ?>
                                                        </td>
                                                        <td>
                                                            <?= !empty($value->price) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($value->price * ((($value->warehouse == $Warehouse ? $value->qty : $OpeningStock) + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty) - ($sale_qty + $transferFromQty + $estimate_sale_qty)), 2) : "0.00"; ?>
                                                        </td>
                                                    </tr>
                                                    <?php } } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th style="text-align: right;" colspan="2">Total</th>
                                                        <th><?= number_format($TotalOpeningStock, 2); ?></th>
                                                        <th><?= number_format($TotalStockManufacturer, 2); ?></th>
                                                        <th><?= number_format($TotalPurchaseQuantity, 2); ?></th>
                                                        <th><?= number_format($TotalTransferTo, 2); ?></th>
                                                        <th><?= number_format($TotalEstimatePurchase, 2); ?></th>
                                                        <th><?= number_format($TotalSaleQuantity, 2); ?></th>
                                                        <th><?= number_format($TotalTransferFrom, 2); ?></th>
                                                        <th><?= number_format($TotalEstimateSale, 2); ?></th>
                                                        <th><?= number_format($TotalRawMaterial, 2); ?></th>
                                                        <th><?= number_format($TotalBalanceQuantity, 2); ?></th>
                                                        <th><?= !empty($TotalPrice) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($TotalPrice, 2) : "0.00"; ?></th>
                                                        <th><?= !empty($TotalAmount) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($TotalAmount, 2) : "0.00"; ?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- Purchase Order modal -->
<div class="modal fade bd-example-modal-lg" id="purchaseOrderModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="<?= base_url(); ?>Employee/PurchaseOrderSave" method="POST">
                <div class="modal-header" style="display: block;">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                            <h5 class="modal-title">Create Purchase Order</h5>
                        </div>
                        <div class="col-md-8 col-lg-8 col-sm-8 col-xl-8 col-xs-8 col-xxl-8">
                            <div class="pull-right">
                                <a href="<?= base_url(); ?>Employee/PurchaseOrderReport" class="btn btn-xs bg-navy" title="Purchase Order Report">
                                    <i class="fa fa-list"></i> Purchase Order Report
                                </a>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xs-3 col-xxl-3">
                            <div class="form-group">
                                <label for="Date">Date <span class="text-danger">*</span></label>
                                <input type="date" name="Date" value="<?= set_value('Date', date('Y-m-d')); ?>" id="Date" class="form-control form-control-sm" title="Date" required="" />
                                <span class="text-danger"><?= form_error('Date'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                            <div class="form-group">
                                <label for="CompanyId">Parent Company <span class="text-danger">*</span></label>
                                <input type="text" name="comp" value="<?= ucfirst(get_title('inventory_billing', ['id' => $this->session->userdata('CompId')], 'company_name')) ?>" id="comp" class="form-control form-control-sm" title="Company Name" readonly />
                                <input type="hidden" name="CompanyId" id="CompanyId" value="<?= $this->session->userdata('CompId'); ?>" />
                            </div>
                        </div>
                    </div>

                    <hr class="bg-navy" />
                    <div class="row" style="min-height: 250px; height: auto;">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm ProductsDropDown">
                                    <thead class="bg-navy" style="position: sticky; top: 0; z-index: 99;">
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="out_of_stock"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Sub Total</th>
                                            <th colspan="1">
                                                <span id="TotalTxtQty">0</span>
                                            </th>
                                            <th colspan="1" style="text-align: right;">
                                                <i class="fa fa-inr"></i> <span id="SubTotalTxt"><?= set_value('SubTotal'); ?></span>
                                                <input type="hidden" name="SubTotal" value="<?= set_value('SubTotal'); ?>" id="SubTotal" />
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xs-4 col-xxl-4">
                            <div class="form-group">
                                <label for="Remark">Remark</label>
                                <textarea name="Remark" id="Remark" class="form-control form-control-sm" placeholder="Remark" rows="1"><?= set_value('Remark'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xs-12 col-xxl-12">
                            <button type="submit" class="btn btn-sm bg-navy">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-danger btn-sm">
                                Reset
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#productRequestOrder").click(function() {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Employee/getProductDetails",
            data    :   $("#tableform").serialize(),
            success :   function(Reponse) {
                            var JSONReponse = JSON.parse(Reponse);
                            if (JSONReponse.status == 0) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'error',
                                    title: "Please Select Product.",
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            } else {
                                let html = "";
                                let total_amount = 0;
                                let TotalTxtQty = 0;
                                
                                JSONReponse.result.forEach(async (value, index) => {
                                    TotalTxtQty += 1;
                                    total_amount += parseFloat(value.price);
                                    index++;
                                    html += `<tr>
                                                <td style="width: 20px">` + index + `
                                                    <input type="hidden" name="Products[`+ index +`][ProductId]" id="ProductId`+ index +`" value="` + value.id + `" />
                                                </td>
                                                <td style="width: 280px">
                                                    ` + value.p_name + `
                                                    <input type="hidden" name="Products[`+ index +`][ProductName]" id="ProductName`+ index +`" value="` + value.p_name + `" />
                                                </td> 
                                                <td style="width: 150px; text-align: center;">
                                                    ` + value.price + `
                                                    <input type="hidden" name="Products[`+ index +`][ProductPrice]" id="ProductPrice`+ index +`" value="` + value.price + `" />
                                                </td> 
                                                <td style="width: 150px;">
                                                    <input type="text" id="qty_` + value.id + `" name="Products[`+ index +`][Quantity]" value="1" class="form-control form-control-sm" placeholder="Qunatity" onchange="update_qty(` + value.id + `);" oninput="update_qty(` + value.id + `);" style="border: 0px;" required="" />
                                                </td> 
                                                <td style="width: 150px; text-align: right;" id="total_amt_` + value.id + `" >
                                                    ` + value.price + `
                                                </td> 
                                                <input type="hidden" name="Products[`+ index +`][Total]" id="Total`+ value.id +`" value="`+ value.price +`" />
                                            </tr>`;
                                });
                                
                                $("#TotalTxtQty").html(TotalTxtQty);
                                $("#SubTotalTxt").html(total_amount);
                                $("#SubTotal").val(total_amount);
                                $("#out_of_stock").html(html);
                                $("#purchaseOrderModal").modal('show');
                            }
                        }
        });
    });

    const PRODUCT_QTY = [];

    function update_qty(product_id) {
        PRODUCT_QTY.push(product_id);
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Employee/getProductDetails",
            data    :   $("#tableform").serialize(),
            success :   function(Reponse) {
                            var JSONReponse = JSON.parse(Reponse);
                            let total_amount = 0;
                            let TotalTxtQty = 0;
                            
                            JSONReponse.result.forEach(async (value) => {
                                let amount = parseFloat(value.price);
                                let qty1 = 1;
                                if (PRODUCT_QTY.includes(parseInt(value.id))) {
                                    let qty = $("#qty_" + value.id).val();
                                    amount = parseInt(qty) * parseFloat(value.price);
                                    $("#total_amt_" + value.id).html(amount);
                                    $("#Total" + value.id).val(amount);
                                    qty1 = qty;
                                }
                                TotalTxtQty += parseInt(qty1);
                                total_amount += amount;
                            });
                            
                            $("#TotalTxtQty").html(TotalTxtQty);
                            $("#SubTotalTxt").html(total_amount);
                            $("#SubTotal").val(total_amount);
                        }
        });
    }
</script>