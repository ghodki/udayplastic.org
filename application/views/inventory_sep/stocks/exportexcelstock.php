<?php $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));"); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=TIS-620" />
        <title>Uday Plastic</title>
        <style type="text/css">
            td {
                white-space: nowrap;
            }
        </style>
    </head>
    <body style="font-size: 13px; padding: 3em;">
        <br />
        <div style="margin: 3em;">
            <h1 style="text-align: center; margin-top: 1em;">Stock Report</h1>
            <table style="border: 1px solid black; border-collapse: collapse; width: 100%;" border="1">
                <?php if ($details == "") { ?>
                <thead>
                    <tr style="background-color: #ccc;">
                        <th style="padding: 3px; font-size:15px;">Sr.</th>
                        <th style="padding: 3px; font-size:15px;">Product Name</th>
                        <th style="padding: 3px; font-size:15px;">Opening Stock</th>
                        <th style="padding: 3px; font-size:15px;">Purchase Quantity</th>
                        <th style="padding: 3px; font-size:15px;">Sale Quantity</th>
                        <th style="padding: 3px; font-size:15px;">Balance Quantity</th>
                        <th style="padding: 3px; font-size:15px;">Price</th>
                        <th style="padding: 3px; font-size:15px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ($result) { 
                            $n = 0;
                            $OpeningStock = 0;
                            $TotalOpeningStock = 0;
                            $TotalPurchaseQuantity = 0;
                            $TotalSaleQuantity = 0;
                            $TotalBalanceQuantity = 0;
                            $Price = 0;
                            $TotalAmount = 0;
                            foreach ($result as $key => $row) {
                                $n++;
                                
                                $EstimateStockWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                if ($this->session->userdata('BranchRole')) {
                                    $EstimateStockWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $estimate_stock = get_title('comp_profile', $EstimateStockWhere, 'estimate_stock');
                                
                                $warehouse = [];
                                foreach ($this->input->get('warehouse') as $key => $wdata) {
                                    $warehouse[] = $wdata;
                                }
                                                            
                                $this->db->select('SUM(Quantity) as smqty');
                                $this->db->where('UserId', $this->session->userdata('user_id'));
                                $this->db->where('CompId', $this->session->userdata('comp_id'));
                                $this->db->where('ProductId', $row->id);
                                $this->db->where('IsDeleted', 1);
                                if (!empty($this->input->get('warehouse'))) {
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
                                ///////////////////////////////// Stock Manufacturer Quantity ///////////////////////////////////////
                                                            
                                $this->db->select('SUM(qty) as pqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                $this->db->where('type', "Purchase");
                                $this->db->where('status', 1);
                                if (!empty($this->input->get('warehouse'))) {
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
                                ///////////////////////////////// Purchase Quantity ///////////////////////////////////////
                                
                                $this->db->select('SUM(qty) as sqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                $this->db->where('type', "Sale");
                                $this->db->where('status', 1);
                                if (!empty($this->input->get('warehouse'))) {
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
                                ///////////////////////////////// Sale Quantity ///////////////////////////////////////
                                
                                $this->db->select('SUM(quantity) as ttqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                if ($transfer_type == "Tax") {
                                    $this->db->where('transfer_type', $transfer_type);
                                } elseif ($transfer_type == "Estimate") {
                                    $this->db->where('transfer_type', $transfer_type);
                                }
                                $transferTo = $this->db->get('transfer_products')->row();
                                /////////////////////////////////// Transfer To Quantity /////////////////////////////////////

                                $this->db->select('SUM(quantity) as tfqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                if ($transfer_type == "Tax") {
                                    $this->db->where('transfer_type', $transfer_type);
                                } elseif ($transfer_type == "Estimate") {
                                    $this->db->where('transfer_type', $transfer_type);
                                }
                                $transferFrom = $this->db->get('transfer_products')->row();
                                /////////////////////////////// Transfer From Quantity ////////////////////////////////////////

                                $this->db->select('SUM(qty) as esqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                $this->db->where('type', "Sale");
                                $this->db->where('status', 1);
                                if (!empty($this->input->get('warehouse'))) {
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
                                ////////////////////////////// Estimate Sale Quantity //////////////////////////////////////////

                                $this->db->select('SUM(qty) as epqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                $this->db->where('type', "Purchase");
                                $this->db->where('status', 1);
                                if (!empty($this->input->get('warehouse'))) {
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
                                //////////////////////////////// Estimate Purchase Quantity /////////////////////////////////////////
                                
                                $this->db->select('MainProductId, SUM(Quantity) as rmqty');
                                $this->db->where('UserId', $this->session->userdata('user_id'));
                                $this->db->where('CompId', $this->session->userdata('comp_id'));
                                $this->db->where('ProductId', $row->id);
                                $this->db->where('IsDeleted', 1);
                                $raw_material_qty_result = $this->db->get('raw_material_products')->row();
                                if ($transfer_type == "All" || $transfer_type == "Estimate") {
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
                                if ($transfer_type == "All" || $transfer_type == "Estimate") {
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
                                $this->db->where('product_id', $row->id);
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
                                $this->db->where('product_id', $row->id);
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
                                
                                $TotalOpeningStock += $row->qty;
                                $TotalPurchaseQuantity += ($purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty);
                                $TotalSaleQuantity += ($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty);
                                $TotalBalanceQuantity += (($row->qty + $purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty) - ($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty));
                                $TotalPrice += $row->price;
                                $TotalAmount += $row->price * (($row->qty + $purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty) - ($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty));
                                
                                $BalanceQuantity = (($row->qty + $purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty) - ($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty));
                    ?>
                    <tr>
                        <td style="padding: 3px;"><?= $n; ?></td>
                        <td style="padding: 3px;"><?= $row->p_name; ?></td>
                        <td style="padding: 3px;"><?= number_format($row->qty); ?></td>
                        <td style="padding: 3px;"><?= number_format(($purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty), 2); ?></td>
                        <td style="padding: 3px;"><?= number_format(($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty), 2); ?></td>
                        <td style="padding: 3px;">
                            <?php if ($BalanceQuantity >= $row->min_qty) { ?>
                            <span style="color: #009104;">
                                <?= number_format($BalanceQuantity, 2); ?>
                            </span>
                            <?php } elseif ($BalanceQuantity < $row->min_qty) { ?>
                            <span style="color: #f50505;">
                                <?= number_format($BalanceQuantity, 2); ?>
                            </span>
                            <?php } ?>
                        </td>
                        <td style="padding: 3px;"><?= number_format($row->price); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->price * (($row->qty + $purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty) - ($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty)), 2); ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #ccc;">
                        <th style="padding: 3px; font-size: 15px; text-align: right;" colspan="2">Total</th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalOpeningStock, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalPurchaseQuantity, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalSaleQuantity, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalBalanceQuantity, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalPrice, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalAmount, 2); ?></th>
                    </tr>
                </tfoot>
                <?php } elseif ($details == true) { ?>
                <thead>
                    <tr style="background-color: #ccc;">
                        <th style="padding: 3px; font-size:15px;">Sr.</th>
                        <th style="padding: 3px; font-size:15px;">Product Name</th>
                        <th style="padding: 3px; font-size:15px;">Opening Stock</th>
                        <th style="padding: 3px; font-size:15px;">Manu. Quantity</th>
                        <th style="padding: 3px; font-size:15px;">Purchase Quantity</th>
                        <th style="padding: 3px; font-size:15px;">Transfer To</th>
                        <th style="padding: 3px; font-size:15px;">Estimate Purchase</th>
                        <th style="padding: 3px; font-size:15px;">Debit Note</th>
                        <th style="padding: 3px; font-size:15px;">Sale Quantity</th>
                        <th style="padding: 3px; font-size:15px;">Transfer From</th>
                        <th style="padding: 3px; font-size:15px;">Estimate Sale</th>
                        <th style="padding: 3px; font-size:15px;">Raw Material</th>
                        <th style="padding: 3px; font-size:15px;">Credit Note</th>
                        <th style="padding: 3px; font-size:15px;">Balance Quantity</th>
                        <th style="padding: 3px; font-size:15px;">Price</th>
                        <th style="padding: 3px; font-size:15px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ($result) { 
                            $n = 0;
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
                            $Price = 0;
                            $TotalAmount = 0;
                            foreach ($result as $key => $row) {
                                $n++;
                                
                                $warehouse = [];
                                foreach ($this->input->get('warehouse') as $key => $wdata) {
                                    $warehouse[] = $wdata;
                                }
                                
                                $EstimateStockWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                if ($this->session->userdata('BranchRole')) {
                                    $EstimateStockWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                }
                                $estimate_stock = get_title('comp_profile', $EstimateStockWhere, 'estimate_stock');
                                
                                $this->db->select('SUM(Quantity) as smqty');
                                $this->db->where('UserId', $this->session->userdata('user_id'));
                                $this->db->where('CompId', $this->session->userdata('comp_id'));
                                $this->db->where('ProductId', $row->id);
                                $this->db->where('IsDeleted', 1);
                                if (!empty($this->input->get('warehouse'))) {
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
                                /////////////////////////////  Stock Manufacturer Quantity //////////////////////////////////////////
                                
                                $this->db->select('SUM(qty) as pqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                $this->db->where('type', "Purchase");
                                $this->db->where('status', 1);
                                if (!empty($this->input->get('warehouse'))) {
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
                                /////////////////////////////  Purchase Quantity //////////////////////////////////////////
                                
                                $this->db->select('SUM(qty) as sqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                $this->db->where('type', "Sale");
                                $this->db->where('status', 1);
                                if (!empty($this->input->get('warehouse'))) {
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
                                $this->db->where('product_id', $row->id);
                                if ($transfer_type == "Tax") {
                                    $this->db->where('transfer_type', $transfer_type);
                                } elseif ($transfer_type == "Estimate") {
                                    $this->db->where('transfer_type', $transfer_type);
                                }
                                $transferTo = $this->db->get('transfer_products')->row();
                                /////////////////////////////////// Transfer To Quantity ////////////////////////////////////

                                $this->db->select('SUM(quantity) as tfqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                if ($transfer_type == "Tax") {
                                    $this->db->where('transfer_type', $transfer_type);
                                } elseif ($transfer_type == "Estimate") {
                                    $this->db->where('transfer_type', $transfer_type);
                                }
                                $transferFrom = $this->db->get('transfer_products')->row();
                                //////////////////////////////////// Transfer From Quantity ///////////////////////////////////

                                $this->db->select('SUM(qty) as esqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                $this->db->where('status', 1);
                                $this->db->where('type', "Sale");
                                if (!empty($this->input->get('warehouse'))) {
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
                                ////////////////////////////////// Estimate Sale Quantity //////////////////////////////////////
                                
                                $this->db->select('SUM(qty) as epqty');
                                $this->db->where('user_id', $this->session->userdata('user_id'));
                                $this->db->where('comp_id', $this->session->userdata('comp_id'));
                                if ($this->session->userdata('BranchRole')) {
                                    $this->db->where('CompanyBranchId', $this->session->userdata('CompBranchId'));
                                }
                                $this->db->where('product_id', $row->id);
                                $this->db->where('status', 1);
                                $this->db->where('type', "Purchase");
                                if (!empty($this->input->get('warehouse'))) {
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
                                ////////////////////////////////// Estimate Purchase Quantity //////////////////////////////////////
                                
                                $this->db->select('MainProductId, SUM(Quantity) as rmqty');
                                $this->db->where('UserId', $this->session->userdata('user_id'));
                                $this->db->where('CompId', $this->session->userdata('comp_id'));
                                $this->db->where('ProductId', $row->id);
                                $this->db->where('IsDeleted', 1);
                                $raw_material_qty_result = $this->db->get('raw_material_products')->row();
                                if ($transfer_type == "All" || $transfer_type == "Estimate") {
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
                                if ($transfer_type == "All" || $transfer_type == "Estimate") {
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
                                $this->db->where('product_id', $row->id);
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
                                $this->db->where('product_id', $row->id);
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
                    
                                $TotalOpeningStock += $row->qty;
                                $TotalStockManufacturer += $stock_manu_qty;
                                $TotalPurchaseQuantity += $purchase_qty;
                                $TotalTransferTo += $transferTo->ttqty;
                                $TotalEstimatePurchase += $estimate_purchase_qty;
                                $TotalDebitNote += $debit_note_qty;
                                $TotalSaleQuantity += $sale_qty;
                                $TotalEstimateSale += $estimate_sale_qty;                                
                                $TotalTransferFrom += $transferFrom->tfqty;
                                $TotalRawMaterial += ($stock_manu_data_qty * $raw_material_qty);
                                $TotalCreditNote += $credit_note_qty;
                                $TotalBalanceQuantity += (($row->qty + $purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty) - ($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty));
                                $TotalPrice += $row->price;
                                $TotalAmount += $row->price * (($row->qty + $purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty) - ($sale_qty + $transferFrom->tfqty+ $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty));
                                
                                $BalanceQuantity = (($row->qty + $purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty) - ($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty));
                    ?>
                    <tr>
                        <td style="padding: 3px;"><?= $n; ?></td>
                        <td style="padding: 3px;"><?= $row->p_name; ?></td>
                        <td style="padding: 3px;"><?= number_format($row->qty); ?></td>
                        <td style="padding: 3px;"><?= number_format($stock_manu_qty, 2); ?></td>
                        <td style="padding: 3px;"><?= number_format($purchase_qty, 2); ?></td>
                        <td style="padding: 3px;"><?= number_format($transferTo->ttqty, 2); ?></td>
                        <td style="padding: 3px;"><?= number_format($estimate_purchase_qty, 2); ?></td>
                        <td style="padding: 3px;"><?= number_format($debit_note_qty, 2); ?></td>
                        <td style="padding: 3px;"><?= number_format($sale_qty, 2); ?></td>
                        <td style="padding: 3px;"><?= number_format($transferFrom->tfqty, 2); ?></td>
                        <td style="padding: 3px;"><?= number_format($estimate_sale_qty, 2); ?></td>
                        <td style="padding: 3px;"><?= number_format(($stock_manu_data_qty * $raw_material_qty), 2); ?></td>
                        <td style="padding: 3px;"><?= number_format($credit_note_qty, 2); ?></td>
                        <td style="padding: 3px;">
                            <?php if ($BalanceQuantity >= $row->min_qty) { ?>
                            <span style="color: #009104;">
                                <?= number_format($BalanceQuantity, 2); ?>
                            </span>
                            <?php } elseif ($BalanceQuantity < $row->min_qty) { ?>
                            <span style="color: #f50505;">
                                <?= number_format($BalanceQuantity, 2); ?>
                            </span>
                            <?php } ?>
                        </td>
                        <td style="padding: 3px;"><?= number_format($row->price); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->price * (($row->qty + $purchase_qty + $transferTo->ttqty + $estimate_purchase_qty + $stock_manu_qty + $debit_note_qty) - ($sale_qty + $transferFrom->tfqty + $estimate_sale_qty + ($stock_manu_data_qty * $raw_material_qty) + $credit_note_qty)), 2); ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #ccc;">
                        <th style="padding: 3px; font-size: 15px; text-align: right;" colspan="2">Total</th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalOpeningStock, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalStockManufacturer, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalPurchaseQuantity, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalTransferTo, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalEstimatePurchase, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalDebitNote, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalSaleQuantity, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalTransferFrom, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalEstimateSale, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalRawMaterial, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalCreditNote, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalBalanceQuantity, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalPrice, 2); ?></th>
                        <th style="padding: 3px; font-size: 15px;"><?= number_format($TotalAmount, 2); ?></th>
                    </tr>
                </tfoot>
                <?php } ?>
            </table>
        </div>
    </body>
</html>