<?php 
    $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    if ($details == "") { 
?>
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
            $warehouse = is_array($warehouse ?? null) ? $warehouse : [];
            if (isset($products) && !empty($products)) {
                foreach ($products as $key => $value) {
                    $n++;

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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('WarehouseId', $warehouse);
                        }
                    }
                    $stock_manu_qty_result = $this->db->get('stock_manufacturer_products')->row();
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
                        $stock_manu_qty = is_object($stock_manu_qty_result) ? ($stock_manu_qty_result->smqty ?? 0) : 0;
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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('warehouse', $warehouse);
                        }
                    }
                    $purchase_qty_result = $this->db->get('invoice_product')->row();
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('warehouse', $warehouse);
                        }
                    }                                                                
                    $sale_qty_result = $this->db->get('invoice_product')->row();
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
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
                    if ($transfer_type == "Tax") {
                        $this->db->where('transfer_type', $transfer_type);
                    } elseif ($transfer_type == "Estimate") {
                        $this->db->where('transfer_type', $transfer_type);
                    }
                    if (!empty($warehouse)) {
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
                    if ($transfer_type == "Tax") {
                        $this->db->where('transfer_type', $transfer_type);
                    } elseif ($transfer_type == "Estimate") {
                        $this->db->where('transfer_type', $transfer_type);
                    }
                    if (!empty($warehouse)) {
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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('warehouse', $warehouse);
                        }
                    }
                    $estimate_sale_qty_result = $this->db->get('estimate_product')->row();
                    if ($estimate_stock == "Yes") {
                        if ($transfer_type == "All" || $transfer_type == "Estimate") {
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
                    if ($this->session->userdata('BranchRole')) {
                        $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                    }
                    $this->db->where('product_id', $value['id']);
                    $this->db->where('status', 1);
                    $this->db->where('type', "Purchase");
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('warehouse', $warehouse);
                        }
                    }
                    $estimate_purchase_qty_result = $this->db->get('estimate_product')->row();
                    if ($estimate_stock == "Yes") {
                        if ($transfer_type == "All" || $transfer_type == "Estimate") {
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
                    if ($transfer_type == "All") {
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
                    if ($transfer_type == "All") {
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
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
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
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
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
<?php } elseif ($details == true) { ?>
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
            $warehouse = is_array($warehouse ?? null) ? $warehouse : [];
            if (isset($products) && !empty($products)) {
                foreach ($products as  $key => $value) {
                    $n++;

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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('WarehouseId', $warehouse);
                        }
                    }
                    $stock_manu_qty_result = $this->db->get('stock_manufacturer_products')->row();
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('warehouse', $warehouse);
                        }
                    }
                    $purchase_qty_result = $this->db->get('invoice_product')->row();
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('warehouse', $warehouse);
                        }
                    }
                    $sale_qty_result = $this->db->get('invoice_product')->row();
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
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
                    if ($transfer_type == "Tax") {
                        $this->db->where('transfer_type', $transfer_type);
                    } elseif ($transfer_type == "Estimate") {
                        $this->db->where('transfer_type', $transfer_type);
                    }
                    if (!empty($warehouse)) {
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
                    if ($transfer_type == "Tax") {
                        $this->db->where('transfer_type', $transfer_type);
                    } elseif ($transfer_type == "Estimate") {
                        $this->db->where('transfer_type', $transfer_type);
                    }
                    if (!empty($warehouse)) {
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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('warehouse', $warehouse);
                        }
                    }
                    $estimate_sale_qty_result = $this->db->get('estimate_product')->row();
                    if ($estimate_stock == "Yes") {
                        if ($transfer_type == "All" || $transfer_type == "Estimate") {
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
                    if (!empty($warehouse)) {
                        if (!in_array('All', $warehouse)) {
                            $this->db->where_in('warehouse', $warehouse);
                        }
                    }
                    $estimate_purchase_qty_result = $this->db->get('estimate_product')->row();
                    if ($estimate_stock == "Yes") {
                        if ($transfer_type == "All" || $transfer_type == "Estimate") {
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
                    if ($transfer_type == "All") {
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
                    if ($transfer_type == "All") {
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
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
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
                    if ($transfer_type == "All" || $transfer_type == "Tax") {
                        $debit_note_qty = $debit_note_qty_result->dnqty;
                    } else {
                        $debit_note_qty = 0;
                    }
                    ////////////////////////////////// Debit Note Quantity ////////////////////////////////////////
                    
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