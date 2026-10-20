<?php $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));"); ?>

<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/bootstrap-multiselect.min.css" />
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
        height: auto;
    }
    thead {
        background: #004a7a;
        color: #ffffff;
    }
    .custom-select {
        height: calc(2rem + 2px);
        font-size: 0.9rem;
    }
    .pagination {
        display: block;
    }
    .pagination a {
        color: black;
        float: left;
        padding: .25rem .5rem;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
        font-size: .875rem;
    }
    .pagination a.active {
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
    }
    a.page-link {
        padding: 6px 8px 6px 8px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow">
                        <div class="card-header-sticky">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="card-title">
                                            STOCK REPORT
                                        </h4>
                                    </div>
                                    <div class="col-md-12">
                                        <form method="GET" action="<?= base_url(); ?>stocks_report/getWarehouseStockReport" onsubmit="onFormSubmitHandler(event);" id="reportForm" autocomplete="off" style="margin-top: 10px;">
                                            <input type="hidden" name="details" id="details" value="<?= $this->input->get('details'); ?>" />
                                            <input type="hidden" name="transfer_type" id="transfer_type" value="<?= $this->input->get('transfer_type'); ?>" />
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <?php
                                                        $warehouse_selected = [];
                                                        foreach ($this->input->get('warehouse') as $key => $wids) {
                                                            $warehouse_selected[] = $wids;
                                                        }
                                                    ?>
                                                    <select name="warehouse[]" id="warehouse" class="form-control form-control-sm" title="Warehouse" multiple="multiple">
                                                        <?php
                                                            $this->db->select('id, name, location');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $warehouses = $this->db->get('warehouse')->result();
                                                            if (!empty($warehouses)) {
                                                                foreach ($warehouses as $key => $warehouse) {
                                                        ?>
                                                        <option <?= in_array($warehouse->id, $warehouse_selected) ? 'selected' : ''; ?> value="<?= $warehouse->id; ?>" <?= set_select('warehouse', $warehouse->id); ?>><?= $warehouse->name; ?> - <?= $warehouse->location; ?></option>
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
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-outline-primary btn-xs">
                                                        Search
                                                    </button>
                                                    <a href="<?= base_url(); ?>stocks_report" class="btn btn-info btn-xs" title="Refresh">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" id="excelExport" title="Export Excel">
                                                        <i class="fa fa-file-excel"></i>
                                                    </a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="PrintStock('printarea');" title="Print">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" id="keywords" class="form-control form-control-sm" placeholder="Search" onkeyup="searchFilter();" />
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <select id="sortBy" class="form-control form-control-sm" onchange="searchFilter();">
                                        <option value="ASC">ASC</option>
                                        <option selected="" value="DESC">DESC</option>
                                    </select>        
                                </div>
                                <div class="col-md-1">
                                    <select id="pagelength" class="form-control form-control-sm pagelength" onchange="searchFilter();">
                                        <option value="15">15</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="tableFixHead StockList" id="printarea" style="padding-top: 10px;">
                                        <?php if ($this->input->get('details') == "") { ?>
                                        <table class="table table-bordered table-condensed">
                                            <thead>
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
                                                    $n = 0;
                                                    $OpeningStock = 0;
                                                    $TotalOpeningStock = 0;
                                                    $TotalPurchaseQuantity = 0;
                                                    $TotalSaleQuantity = 0;
                                                    $TotalBalanceQuantity = 0;
                                                    $TotalPrice = 0;
                                                    $TotalAmount = 0;
                                                    if (isset($products) && !empty($products)) {
                                                        foreach ($products as $key => $value) {
                                                            $n++;
                                                            $EstimateStockWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $EstimateStockWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                            }
                                                            $estimate_stock = get_title('comp_profile', $EstimateStockWhere, 'estimate_stock');
                                                            
                                                            $warehouse = function_exists('input_get_array')
                                                                ? input_get_array('warehouse')
                                                                : (array) ($this->input->get('warehouse') ?: []);
                                                            
                                                            $this->db->select('SUM(Quantity) as smqty');
                                                            $this->db->where('UserId', $this->session->userdata('user_id'));
                                                            $this->db->where('CompId', $this->session->userdata('comp_id'));
                                                            $this->db->where('ProductId', $value['id']);
                                                            $this->db->where('IsDeleted', 1);
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('WarehouseId', $warehouse);
                                                                }
                                                            }
                                                            $stock_manu_qty_result = $this->db->get('stock_manufacturer_products')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $stock_manu_qty = $stock_manu_qty_result->smqty;
                                                            } else {
                                                                $stock_manu_qty = 0;
                                                            }
                                                            ////////////////////////////// Stock Manufacturer Quantity ///////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as pqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "Purchase");
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse', $warehouse);
                                                                }
                                                            }
                                                            $purchase_qty_result = $this->db->get('invoice_product')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $purchase_qty = $purchase_qty_result->pqty;
                                                            } else {
                                                                $purchase_qty = 0;
                                                            }
                                                            ////////////////////////////// Purchase Quantity ///////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as sqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "Sale");
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse', $warehouse);
                                                                }
                                                            }                                                                
                                                            $sale_qty_result = $this->db->get('invoice_product')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $sale_qty = $sale_qty_result->sqty;
                                                            } else {
                                                                $sale_qty = 0;
                                                            }
                                                            ////////////////////////////////// Sale Quantity ////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(quantity) as ttqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('transaction_type', 'TO');
                                                            if ($this->input->get('transfer_type') == "Tax") {
                                                                $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                            } elseif ($this->input->get('transfer_type') == "Estimate") {
                                                                $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                            }
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse_id', $warehouse);
                                                                }
                                                            }
                                                            $transferTo = $this->db->get('transfer_products')->row();
                                                            //////////////////////////////////// Transfer To Quantity ////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(quantity) as tfqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('transaction_type', 'FROM');
                                                            if ($this->input->get('transfer_type') == "Tax") {
                                                                $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                            } elseif ($this->input->get('transfer_type') == "Estimate") {
                                                                $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                            }
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse_id', $warehouse);
                                                                }
                                                            }
                                                            $transferFrom = $this->db->get('transfer_products')->row();
                                                            ///////////////////////////////////// Transfer From Quantity ///////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as esqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "Sale");
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse', $warehouse);
                                                                }
                                                            }
                                                            $estimate_sale_qty_result = $this->db->get('estimate_product')->row();
                                                            if ($estimate_stock == "Yes") {
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                    $estimate_sale_qty = $estimate_sale_qty_result->esqty;
                                                                } else {
                                                                    $estimate_sale_qty = 0;
                                                                }
                                                            } else {
                                                                $estimate_sale_qty = 0;
                                                            }
                                                            ///////////////////////////////// Estimate Sale Quantity ///////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as epqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            $this->db->where('product_id', $value['id']);
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "Purchase");
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse', $warehouse);
                                                                }
                                                            }
                                                            $estimate_purchase_qty_result = $this->db->get('estimate_product')->row();
                                                            if ($estimate_stock == "Yes") {
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                    $estimate_purchase_qty = $estimate_purchase_qty_result->epqty;
                                                                } else {
                                                                    $estimate_purchase_qty = 0;
                                                                }
                                                            } else {
                                                                $estimate_purchase_qty = 0;
                                                            }
                                                            //////////////////////////////////// Estimate Purchase Quantity ////////////////////////////////////
                                                            
                                                            $this->db->select('MainProductId, SUM(Quantity) as rmqty');
                                                            $this->db->where('UserId', $this->session->userdata('user_id'));
                                                            $this->db->where('CompId', $this->session->userdata('comp_id'));
                                                            $this->db->where('ProductId', $value['id']);
                                                            $this->db->where('IsDeleted', 1);
                                                            $raw_material_qty_result = $this->db->get('raw_material_products')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                $raw_material_qty = $raw_material_qty_result->rmqty;
                                                            } else {
                                                                $raw_material_qty = 0;
                                                            }
                                                            //////////////////////////////////// Raw Material Quantity ////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(Quantity) as smqty_value');
                                                            $this->db->where('UserId', $this->session->userdata('user_id'));
                                                            $this->db->where('CompId', $this->session->userdata('comp_id'));
                                                            $this->db->where('ProductId', $raw_material_qty_result->MainProductId);
                                                            $this->db->where('IsDeleted', 1);
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('WarehouseId', $warehouse);
                                                                }
                                                            }
                                                            $stock_manu_data_qty_result = $this->db->get('stock_manufacturer_products')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                $stock_manu_data_qty = $stock_manu_data_qty_result->smqty_value;
                                                            } else {
                                                                $stock_manu_data_qty = 0;
                                                            }
                                                            //////////////////////////////////// Stock Manufacturer Raw Material Quantity ////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as cnqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "CreditNote");
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('WarehouseId', $warehouse);
                                                                }
                                                            }                                                                
                                                            $credit_note_qty_result = $this->db->get('credit_product')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $credit_note_qty = $credit_note_qty_result->cnqty;
                                                            } else {
                                                                $credit_note_qty = 0;
                                                            }
                                                            ////////////////////////////////// Credit Note Quantity ////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as dnqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "DebitNote");
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('WarehouseId', $warehouse);
                                                                }
                                                            }                                                                
                                                            $debit_note_qty_result = $this->db->get('credit_product')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $debit_note_qty = $debit_note_qty_result->dnqty;
                                                            } else {
                                                                $debit_note_qty = 0;
                                                            }
                                                            ////////////////////////////////// Debit Note Quantity ////////////////////////////////////////
                                                            
                                                            $rowPrice = (float) ($value['price'] ?? 0);
                                                            $rowOpening = (float) (in_array($value['warehouse'], $warehouse) ? ($value['qty'] ?? 0) : $OpeningStock);
                                                            $transferToQty = (float) (is_object($transferTo) ? ($transferTo->ttqty ?? 0) : 0);
                                                            $transferFromQty = (float) (is_object($transferFrom) ? ($transferFrom->tfqty ?? 0) : 0);
                                                            $rowPurchase = (float) (($purchase_qty ?? 0) + $transferToQty + ($estimate_purchase_qty ?? 0) + ($stock_manu_qty ?? 0) + ($debit_note_qty ?? 0));
                                                            $rowSale = (float) (($sale_qty ?? 0) + $transferFromQty + ($estimate_sale_qty ?? 0) + (($stock_manu_data_qty ?? 0) * ($raw_material_qty ?? 0)) + ($credit_note_qty ?? 0));
                                                            $BalanceQuantity = $rowOpening + $rowPurchase - $rowSale;
                                                            $TotalOpeningStock += $rowOpening;
                                                            $TotalPurchaseQuantity += $rowPurchase;
                                                            $TotalSaleQuantity += $rowSale;
                                                            $TotalBalanceQuantity += $BalanceQuantity;
                                                            $TotalPrice += $rowPrice;
                                                            $TotalAmount += $rowPrice * $BalanceQuantity;
                                                ?>
                                                <tr>
                                                    <td><?= $n; ?></td>
                                                    <td><?= $value['p_name']; ?></td>
                                                    <td><?= number_format($rowOpening, 2); ?></td>
                                                    <td><?= number_format($rowPurchase, 2); ?></td>
                                                    <td><?= number_format($rowSale, 2); ?></td>
                                                    <td>
                                                       <?php if ($BalanceQuantity >= $value['min_qty']) { ?>
                                                        <span class="text-success">
                                                            <?= number_format($BalanceQuantity, 2); ?>
                                                        </span>
                                                        <?php } elseif ($BalanceQuantity < $value['min_qty']) { ?>
                                                        <a href="<?= base_url(); ?>stocks_report/purchase_order_report" class="text-danger" title="Purchase Order Report" target="_blank">
                                                            <?= number_format($BalanceQuantity, 2); ?>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?= $rowPrice ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($rowPrice, 2) : "0.00"; ?></td>
                                                    <td><?= $rowPrice ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($rowPrice * $BalanceQuantity, 2) : "0.00"; ?></td>
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
                                                <tr>
                                                    <td colspan="7">
                                                        <?= $this->ajax_pagination_dashboard->create_links(); ?>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <?php } elseif ($this->input->get('details') == true) { ?>
                                        <table class="table table-bordered table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="text-white">Sr</th>
                                                    <th class="text-white">Product Name</th>
                                                    <th class="text-white">Opening Stock</th>
                                                    <th class="text-white">Manu. Quantity</th>
                                                    <th class="text-white">Purchase Quantity</th>
                                                    <th class="text-white">Transfer To</th>
                                                    <th class="text-white">Estimate Purchase</th>
                                                    <th class="text-white">Debit Note</th>
                                                    <th class="text-white">Sale Quantity</th>
                                                    <th class="text-white">Transfer From</th>
                                                    <th class="text-white">Estimate Sale</th>
                                                    <th class="text-white">Raw Material</th>
                                                    <th class="text-white">Credit Note</th>
                                                    <th class="text-white">Balance Quantity</th>
                                                    <th class="text-white">Price</th>
                                                    <th class="text-white">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $n = 0;
                                                    $OpeningStock = 0;
                                                    $TotalOpeningStock = 0;
                                                    $TotalStockManufacturer = 0;
                                                    $TotalPurchaseQuantity = 0;
                                                    $TotalTransferTo = 0;
                                                    $TotalEstimatePurchase = 0;
                                                    $TotalDebitNote = 0;
                                                    $TotalSaleQuantity = 0;
                                                    $TotalTransferFrom = 0;
                                                    $TotalEstimateSale = 0;
                                                    $TotalRawMaterial = 0;
                                                    $TotalCreditNote = 0;
                                                    $TotalBalanceQuantity = 0;
                                                    $TotalPrice = 0;
                                                    $TotalAmount = 0;
                                                    if (isset($products) && !empty($products)) {
                                                        foreach ($products as  $key => $value) {
                                                            $n++;
                                                            
                                                            $warehouse = function_exists('input_get_array')
                                                                ? input_get_array('warehouse')
                                                                : (array) ($this->input->get('warehouse') ?: []);
                                                            $EstimateStockWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $EstimateStockWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                            }
                                                            $estimate_stock = get_title('comp_profile', $EstimateStockWhere, 'estimate_stock');
                                                            
                                                            $this->db->select('SUM(Quantity) as smqty');
                                                            $this->db->where('UserId', $this->session->userdata('user_id'));
                                                            $this->db->where('CompId', $this->session->userdata('comp_id'));
                                                            $this->db->where('ProductId', $value['id']);
                                                            $this->db->where('IsDeleted', 1);
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('WarehouseId', $warehouse);
                                                                }
                                                            }
                                                            $stock_manu_qty_result = $this->db->get('stock_manufacturer_products')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $stock_manu_qty = $stock_manu_qty_result->smqty;
                                                            } else {
                                                                $stock_manu_qty = 0;
                                                            }
                                                            ///////////////////////////// Stock Manufacturer Quantity ///////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as pqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('type', "Purchase");
                                                            $this->db->where('status', 1);
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse', $warehouse);
                                                                }
                                                            }
                                                            $purchase_qty_result = $this->db->get('invoice_product')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $purchase_qty = $purchase_qty_result->pqty;
                                                            } else {
                                                                $purchase_qty = 0;
                                                            }
                                                            ///////////////////////////// Purchase Quantity ///////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as sqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "Sale");
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse', $warehouse);
                                                                }
                                                            }
                                                            $sale_qty_result = $this->db->get('invoice_product')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $sale_qty = $sale_qty_result->sqty;
                                                            } else {
                                                                $sale_qty = 0;
                                                            }
                                                            //////////////////////////////// Sale Quantity ////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(quantity) as ttqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }                                                            
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('transaction_type', 'TO');
                                                            if ($this->input->get('transfer_type') == "Tax") {
                                                                $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                            } elseif ($this->input->get('transfer_type') == "Estimate") {
                                                                $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                            }
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse_id', $warehouse);
                                                                }
                                                            }
                                                            $transferTo = $this->db->get('transfer_products')->row();
                                                            /////////////////////////////////// Transfer To Quantity //////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(quantity) as tfqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('product_id', $value['id']);
                                                            $this->db->where('transaction_type', 'FROM');
                                                            if ($this->input->get('transfer_type') == "Tax") {
                                                                $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                            } elseif ($this->input->get('transfer_type') == "Estimate") {
                                                                $this->db->where('transfer_type', $this->input->get('transfer_type'));
                                                            }
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse_id', $warehouse);
                                                                }
                                                            }
                                                            $transferFrom = $this->db->get('transfer_products')->row();
                                                            ///////////////////////////////// Transfer From Quantity //////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as esqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "Sale");
                                                            $this->db->where('product_id', $value['id']);
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse', $warehouse);
                                                                }
                                                            }
                                                            $estimate_sale_qty_result = $this->db->get('estimate_product')->row();
                                                            if ($estimate_stock == "Yes") {
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                    $estimate_sale_qty = $estimate_sale_qty_result->esqty;
                                                                } else {
                                                                    $estimate_sale_qty = 0;
                                                                }
                                                            } else {
                                                                $estimate_sale_qty = 0;
                                                            }
                                                            ///////////////////////////////// Estimate Sale Quantity //////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as epqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "Purchase");
                                                            $this->db->where('product_id', $value['id']);                         
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('warehouse', $warehouse);
                                                                }
                                                            }
                                                            $estimate_purchase_qty_result = $this->db->get('estimate_product')->row();
                                                            if ($estimate_stock == "Yes") {
                                                                if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                    $estimate_purchase_qty = $estimate_purchase_qty_result->epqty;
                                                                } else {
                                                                    $estimate_purchase_qty = 0;
                                                                }
                                                            } else {
                                                                $estimate_purchase_qty = 0;
                                                            }
                                                            //////////////////////////////////// Estimate Purchase Quantity /////////////////////////////////////////
                                                            
                                                            $this->db->select('MainProductId, SUM(Quantity) as rmqty');
                                                            $this->db->where('UserId', $this->session->userdata('user_id'));
                                                            $this->db->where('CompId', $this->session->userdata('comp_id'));
                                                            $this->db->where('ProductId', $value['id']);
                                                            $this->db->where('IsDeleted', 1);
                                                            $raw_material_qty_result = $this->db->get('raw_material_products')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                $raw_material_qty = $raw_material_qty_result->rmqty;
                                                            } else {
                                                                $raw_material_qty = 0;
                                                            }
                                                            //////////////////////////////////// Raw Material Quantity ////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(Quantity) as smqty_value');
                                                            $this->db->where('UserId', $this->session->userdata('user_id'));
                                                            $this->db->where('CompId', $this->session->userdata('comp_id'));
                                                            $this->db->where('ProductId', $raw_material_qty_result->MainProductId);
                                                            $this->db->where('IsDeleted', 1);
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('WarehouseId', $warehouse);
                                                                }
                                                            }
                                                            $stock_manu_data_qty_result = $this->db->get('stock_manufacturer_products')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Estimate") {
                                                                $stock_manu_data_qty = $stock_manu_data_qty_result->smqty_value;
                                                            } else {
                                                                $stock_manu_data_qty = 0;
                                                            }
                                                            //////////////////////////////////// Stock Manufacturer Raw Material Quantity ////////////////////////////////////
                                                                                                                        
                                                            $this->db->select('SUM(qty) as cnqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "CreditNote");
                                                            $this->db->where('product_id', $value['id']);                         
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('WarehouseId', $warehouse);
                                                                }
                                                            }
                                                            $credit_note_qty_result = $this->db->get('credit_product')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $credit_note_qty = $credit_note_qty_result->cnqty;
                                                            } else {
                                                                $credit_note_qty = 0;
                                                            }
                                                            //////////////////////////////////// Credit Note Quantity /////////////////////////////////////////
                                                            
                                                            $this->db->select('SUM(qty) as dnqty');
                                                            $this->db->where('user_id', $this->session->userdata('user_id'));
                                                            $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                                            }
                                                            $this->db->where('status', 1);
                                                            $this->db->where('type', "DebitNote");
                                                            $this->db->where('product_id', $value['id']);                         
                                                            if (!empty($this->input->get('warehouse'))) {
                                                                if (!in_array('All', $warehouse)) {
                                                                    $this->db->where_in('WarehouseId', $warehouse);
                                                                }
                                                            }
                                                            $debit_note_qty_result = $this->db->get('credit_product')->row();
                                                            if ($this->input->get('transfer_type') == "All" || $this->input->get('transfer_type') == "Tax") {
                                                                $debit_note_qty = $debit_note_qty_result->dnqty;
                                                            } else {
                                                                $debit_note_qty = 0;
                                                            }
                                                            //////////////////////////////////// Debit Note Quantity /////////////////////////////////////////
                                                            
                                                            $rowPrice = (float) ($value['price'] ?? 0);
                                                            $rowOpening = (float) (in_array($value['warehouse'], $warehouse) ? ($value['qty'] ?? 0) : $OpeningStock);
                                                            $stock_manu_qty = (float) ($stock_manu_qty ?? 0);
                                                            $purchase_qty = (float) ($purchase_qty ?? 0);
                                                            $estimate_purchase_qty = (float) ($estimate_purchase_qty ?? 0);
                                                            $debit_note_qty = (float) ($debit_note_qty ?? 0);
                                                            $sale_qty = (float) ($sale_qty ?? 0);
                                                            $estimate_sale_qty = (float) ($estimate_sale_qty ?? 0);
                                                            $credit_note_qty = (float) ($credit_note_qty ?? 0);
                                                            $transferToQty = (float) (is_object($transferTo) ? ($transferTo->ttqty ?? 0) : 0);
                                                            $transferFromQty = (float) (is_object($transferFrom) ? ($transferFrom->tfqty ?? 0) : 0);
                                                            $rawMaterialUsed = (float) (($stock_manu_data_qty ?? 0) * ($raw_material_qty ?? 0));
                                                            $BalanceQuantity = $rowOpening + $purchase_qty + $transferToQty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty - ($sale_qty + $transferFromQty + $estimate_sale_qty + $rawMaterialUsed + $credit_note_qty);
                                                            $TotalOpeningStock += $rowOpening;
                                                            $TotalStockManufacturer += $stock_manu_qty;
                                                            $TotalPurchaseQuantity += $purchase_qty;
                                                            $TotalTransferTo += $transferToQty;
                                                            $TotalEstimatePurchase += $estimate_purchase_qty;
                                                            $TotalDebitNote += $debit_note_qty;
                                                            $TotalSaleQuantity += $sale_qty;
                                                            $TotalTransferFrom += $transferFromQty;
                                                            $TotalEstimateSale += $estimate_sale_qty;
                                                            $TotalRawMaterial += $rawMaterialUsed;
                                                            $TotalCreditNote += $credit_note_qty;
                                                            $TotalBalanceQuantity += $BalanceQuantity;
                                                            $TotalPrice += $rowPrice;
                                                            $TotalAmount += $rowPrice * $BalanceQuantity;
                                                ?>
                                                <tr>
                                                    <td><?= $n; ?></td>
                                                    <td><?= $value['p_name']; ?></td>
                                                    <td><?= number_format($rowOpening, 2); ?></td>
                                                    <td><?= number_format($stock_manu_qty, 2); ?></td>
                                                    <td><?= number_format($purchase_qty, 2); ?></td>
                                                    <td><?= number_format($transferToQty, 2); ?></td>
                                                    <td><?= number_format($estimate_purchase_qty, 2); ?></td>
                                                    <td><?= number_format($debit_note_qty, 2); ?></td>
                                                    <td><?= number_format($sale_qty, 2); ?></td>
                                                    <td><?= number_format($transferFromQty, 2); ?></td>
                                                    <td><?= number_format($estimate_sale_qty, 2); ?></td>
                                                    <td><?= number_format($rawMaterialUsed, 2); ?></td>
                                                    <td><?= number_format($credit_note_qty, 2); ?></td>
                                                    <td>
                                                       <?php if ($BalanceQuantity >= $value['min_qty']) { ?>
                                                        <span class="text-success">
                                                            <?= number_format($BalanceQuantity, 2); ?>
                                                        </span>
                                                        <?php } elseif ($BalanceQuantity < $value['min_qty']) { ?>
                                                        <a href="<?= base_url(); ?>stocks_report/purchase_order_report" class="text-danger" title="Purchase Order Report" target="_blank">
                                                            <?= number_format($BalanceQuantity, 2); ?>
                                                        </a>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?= $rowPrice ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($rowPrice, 2) : "0.00"; ?></td>
                                                    <td><?= $rowPrice ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($rowPrice * $BalanceQuantity, 2) : "0.00"; ?></td>
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
                                                    <th><?= number_format($TotalDebitNote, 2); ?></th>
                                                    <th><?= number_format($TotalSaleQuantity, 2); ?></th>
                                                    <th><?= number_format($TotalTransferFrom, 2); ?></th>
                                                    <th><?= number_format($TotalEstimateSale, 2); ?></th>
                                                    <th><?= number_format($TotalRawMaterial, 2); ?></th>
                                                    <th><?= number_format($TotalCreditNote, 2); ?></th>
                                                    <th><?= number_format($TotalBalanceQuantity, 2); ?></th>
                                                    <th><?= !empty($TotalPrice) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($TotalPrice, 2) : "0.00"; ?></th>
                                                    <th><?= !empty($TotalAmount) ? "<i class='fa fa-inr'></i>&nbsp;" . number_format($TotalAmount, 2) : "0.00"; ?></th>
                                                </tr>
                                                <tr>
                                                    <td colspan="12">
                                                        <?= $this->ajax_pagination_dashboard->create_links(); ?>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <?php } ?>
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

<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('#warehouse').multiselect({
        buttonWidth: 'auto'
    });
    
    $('#excelExport').click(function(e) {
        e.preventDefault();
        var form = $('#reportForm').serialize();
        var href = "<?= base_url(); ?>stocks_report/ExportExcelStock?" + form;
        var $a = $("<a>");
        $a.attr("href", href);
        $("body").append($a);
        $a.attr("download", "stock_report.xls");
        $a[0].click();
        $a.remove();
    });
    
    function PrintStock(PrintArea) {
        var printContents = document.getElementById(PrintArea).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
    
    function searchFilter(page_num){
        page_num = page_num ? page_num : 0;
        var keywords = $('#keywords').val();
        var sortBy = $('#sortBy').val();
        var pagelength = $('#pagelength').val();
        var WarehouseData = $('#warehouse').val();
        var details = $('#details').val();
        var transfer_type = $('#transfer_type').val();
        
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url('stocks_report/AjaxStockData/'); ?>"+page_num,
            data    :   "page="+page_num+"&keywords="+keywords+"&sortBy="+sortBy+"&pagelength="+pagelength+"&WarehouseData="+WarehouseData+"&details="+details+"&transfer_type="+transfer_type,
            success :   function(html){
                            $('.StockList').html(html);
                        }
        });
    }
</script>