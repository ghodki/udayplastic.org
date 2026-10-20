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
    <body style="font-size:13px; padding:3em">
        <br />
        <?php if ($estimate == "") { if ($sale_register == "general") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">Address</th>
                        <th style="padding:3px; font-size:15px;">Mobile</th>
                        <th style="padding:3px; font-size:15px;">Email</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Taxable Amount</th>
                        <th style="padding:3px; font-size:15px;">Tax</th>
                        <th style="padding:3px; font-size:15px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result) { foreach ($result as $key => $row) { ?>
                    <tr>
                        <td style="padding:3px;"><?= $key + 1 ?></td>
                        <td style="padding:3px;"><?= $row->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= $row->invoiceDate; ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                        <td style="padding:3px;" width="400"><?= $row->customerAddress; ?></td>
                        <td style="padding:3px;"><?= $row->customerMobile; ?></td>
                        <td style="padding:3px;"><?= $row->customerEmail; ?></td>
                        <td style="padding:3px;"><?= $row->gstNo ?></td>
                        <td style="padding:3px;"><i class="fa fa-inr" aria-hidden="true"></i><?= $row->taxableAmount; ?></td>
                        <td style="padding:3px;"><i class="fa fa-inr" aria-hidden="true"></i><?= $row->totalTax; ?></td>
                        <td style="padding:3px;"><i class="fa fa-inr" aria-hidden="true"></i><?= $row->total; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
	<?php } elseif ($sale_register == "columnar") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">POS</th>
                        <th style="padding:3px; font-size:15px;">Voucher Type</th>
                        <th style="padding:3px; font-size:15px;">Taxable Amt</th>
                        <?php
                            $this->db->select('tax');
                            $this->db->from('invoice_product');
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $this->db->order_by('tax', 'asc');
                            $tax = $this->db->get()->result();
                            if (isset($tax) && !empty($tax)) {
                                foreach ($tax as $row) {
                                    $total_gsttype[$row->tax] = $row->tax;
                        ?>
                        <td style="padding:3px; font-size:15px;"><span>Taxable Amt @ </span><?= $row->tax; ?>%</td>
                        <td style="padding:3px; font-size:15px;">SGST</td>
                        <td style="padding:3px; font-size:15px;">CGST</td>
                        <td style="padding:3px; font-size:15px;">IGST</td>
                        <?php } } ?>
                        <th>Invoice Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result) { foreach ($result as $key => $row) { ?>
                    <tr>
                        <td style="padding:3px"><?= $key + 1 ?></td>
                        <td style="padding:3px"><?= $row->invoiceNumber; ?></td>
                        <td style="padding:3px"><?= $row->invoiceDate; ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                        <td style="padding:3px;"><?= $row->gstNo ?></td>
                        <td style="padding:3px;"><?= $row->state_name; ?></td>
                        <td style="padding:3px"><?= "Sale" ?></td>
                        <td style="padding:3px"><i class="fa fa-inr" aria-hidden="true"></i><?= $row->taxableAmount; ?></td>
                        <?php
                            $this->db->select('tax');
                            $this->db->select_sum('price');
                            $this->db->select_sum('sgst');
                            $this->db->select_sum('cgst');
                            $this->db->select_sum('igst');
                            $this->db->select_sum('taxAmount');
                            $this->db->from('invoice_product');
                            $this->db->where('invoiceID', $row->id);
                            $this->db->where('InvoiceNo', $row->invoiceNumber);
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $tax = $this->db->get()->result();
                            foreach ($total_gsttype as $total_tax) {
                                $this->db->select('tax');
                                $this->db->select_sum('price');
                                $this->db->select_sum('sgst');
                                $this->db->select_sum('cgst');
                                $this->db->select_sum('igst');
                                $this->db->select_sum('taxAmount');
                                $this->db->from('invoice_product');
                                $this->db->where('invoiceID', $row->id);
                                $this->db->where('InvoiceNo', $row->invoiceNumber);
                                $this->db->where('tax', $total_tax);
                                $this->db->where('type', 'Sale');
                                $this->db->where('status', 1);
                                $this->db->group_by('tax');
                                $this->db->order_by('tax', 'asc');
                                $tax = $this->db->get()->result();
                                if (!empty($tax)) {
                        ?>
                        <td style="text-align:center;"><?= money_formats($tax[0]->price) ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->sgst, 2) ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->cgst, 2) ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->igst, 2) ?></td>
                        <?php } else { ?>
                        <td style="text-align:center;">--</td>
                        <td style="text-align:center;">--</td>
                        <td style="text-align:center;">--</td>
                        <td style="text-align:center;">--</td>
                        <?php } } ?>
                        <td style="padding:3px"><?= $row->totalValue; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
	<?php } elseif ($sale_register == "hsn") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">HSN No</th>
                        <th style="padding:3px; font-size:15px;">Description</th>
                        <th style="padding:3px; font-size:15px;">Total Qty</th>
                        <th style="padding:3px; font-size:15px;">Tax</th>
                        <th style="padding:3px; font-size:15px;">Taxable Total</th>
                        <th style="padding:3px; font-size:15px;">CGST</th>
                        <th style="padding:3px; font-size:15px;">SGST</th>
                        <th style="padding:3px; font-size:15px;">IGST</th>
                        <th style="padding:3px; font-size:15px;">Cess Amount</th>
                        <th style="padding:3px; font-size:15px;">Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $this->db->select('tax, hsn, productname');
                        $this->db->select_sum('qty');
                        $this->db->select_sum('price');
                        $this->db->select_sum('cgst');
                        $this->db->select_sum('sgst');
                        $this->db->select_sum('igst');
                        $this->db->select_sum('netAmount');
                        $this->db->from('invoice_product');
                        $this->db->where('comp_id', $this->session->userdata('comp_id'));
                        $this->db->where('user_id', $this->session->userdata('user_id'));
                        $this->db->where('status', 1);
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('type', 'Sale');
                        $this->db->group_by('hsn', 'tax');
                        $hsn_result = $this->db->get()->result();
                        if (!empty($hsn_result)) {
                            $n = 0;
                            foreach ($hsn_result as $key => $row) {
                                $n++;
                    ?>
                    <tr>
                        <td style="padding:3px"><?= $n; ?></td>
                        <td style="padding:3px"><?= $row->hsn; ?></td>
                        <td style="padding:3px"><?= $row->productname; ?></td>
                        <td style="padding:3px"><?= number_format($row->qty); ?></td>
                        <td style="padding:3px"><?= $row->tax; ?></td>
                        <td style="padding:3px"><?= money_formats($row->price, 2); ?></td>
                        <td style="padding:3px"><?= money_formats($row->cgst, 2); ?></td>
                        <td style="padding:3px"><?= money_formats($row->sgst, 2); ?></td>
                        <td style="padding:3px"><?= money_formats($row->igst, 2); ?></td>
                        <td style="padding:3px"><?= 0.00; ?></td>
                        <td style="padding:3px"><?= money_formats($row->netAmount, 2); ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>        
        <?php } elseif ($sale_register == "detailview") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">City</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Round Off</th>
                        <th style="padding:3px; font-size:15px;">Taxable Amt</th>
                        <?php
                            $this->db->select('tax');
                            $this->db->from('invoice_product');
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $this->db->order_by('tax', 'ASC');
                            $tax = $this->db->get()->result();
                            if (isset($tax) && !empty($tax)) {
                                foreach ($tax as $row) {
                                    if (is_numeric($row->tax)) {
                                        $total_gsttype[$row->tax] = $row->tax;
                        ?>
                        <th style="padding:3px; font-size:15px;"><span>Taxable Amt @ </span><?= $row->tax; ?></th>
                        <th style="padding:3px; font-size:15px;">SGST</th>
                        <th style="padding:3px; font-size:15px;">CGST</th>
                        <th style="padding:3px; font-size:15px;">IGST</th>
                        <?php } } } ?>
                        <th style="padding:3px; font-size:15px;">Invoice Total</th>
                    </tr>
                <thead>
                <tbody>
                    <?php
                        if ($result) {
                            $n = 0;
                            $totalTaxable_amount = 0;
                            $total_tax = 0;
                            $total_amount = 0;
                            $round_off = 0;
                            foreach ($result as $key => $row) {
                                $n++;
                                $totalTaxable_amount += $row->taxableAmount;
                                $total_tax += $row->totalTax;
                                $total_amount += $row->total;
                                $round_off += $row->roundOff;
                    ?>
                    <tr>
                        <td style="padding:3px;"><?= $n; ?></td>
                        <td style="padding:3px;"><?= $row->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                        <td style="padding:3px;"><?= get_title('districts', ['id' => $row->customerCity], 'name'); ?></td>
                        <td style="padding:3px;"><?= $row->gstNo; ?></td>
                        <td style="padding:3px;"><?= number_format($row->roundOff, 2); ?></td>
                        <td style="padding:3px;"><?= $row->taxableAmount; ?></td>
                        <?php
                            $this->db->select('tax');
                            $this->db->select_sum('price');
                            $this->db->select_sum('sgst');
                            $this->db->select_sum('cgst');
                            $this->db->select_sum('igst');
                            $this->db->select_sum('taxAmount');
                            $this->db->from('invoice_product');
                            $this->db->where('invoiceID', $row->id);
                            $this->db->where('InvoiceNo', $row->invoiceNumber);
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $tax = $this->db->get()->result();
                            foreach ($total_gsttype as $total_tax) {
                                $this->db->select('tax');
                                $this->db->select_sum('price');
                                $this->db->select_sum('sgst');
                                $this->db->select_sum('cgst');
                                $this->db->select_sum('igst');
                                $this->db->select_sum('taxAmount');
                                $this->db->from('invoice_product');
                                $this->db->where('invoiceID', $row->id);
                                $this->db->where('InvoiceNo', $row->invoiceNumber);
                                $this->db->where('tax', $total_tax);
                                $this->db->where('type', 'Sale');
                                $this->db->where('status', 1);
                                $this->db->group_by('tax');
                                $this->db->order_by('tax', 'ASC');
                                $tax = $this->db->get()->result();
                                if (!empty($tax)) {
                        ?>
                        <td style="padding:3px;"><?= money_formats($tax[0]->price); ?></td>
                        <td style="padding:3px;"><?= money_formats($tax[0]->sgst, 2); ?></td>
                        <td style="padding:3px;"><?= money_formats($tax[0]->cgst, 2); ?></td>
                        <td style="padding:3px;"><?= money_formats($tax[0]->igst, 2); ?></td>
                        <?php } else { ?>
                        <td style="padding:3px;">--</td>
                        <td style="padding:3px;">--</td>
                        <td style="padding:3px;">--</td>
                        <td style="padding:3px;">--</td>
                        <?php } } ?>
                        <td style="padding:3px;"><?= $row->totalValue; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px; text-align: right;" colspan="6">Total</th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($round_off, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($totalTaxable_amount, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($total_tax, 2); ?></th>
                        <th style="padding:3px; font-size:15px; text-align: right;" colspan="12"><?= number_format($total_amount, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } elseif ($sale_register == "updateview") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">Mobile</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Payment Mode</th> 
                        <th style="padding:3px; font-size:15px;">Taxable Amount</th>
                        <th style="padding:3px; font-size:15px;">Tax</th>
                        <th style="padding:3px; font-size:15px;">Total</th>
                        <th style="padding:3px; font-size:15px;">Created</th>
                        <th style="padding:3px; font-size:15px;">Created By</th>
                        <th style="padding:3px; font-size:15px;">Updated</th>
                        <th style="padding:3px; font-size:15px;">Updated By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($result) {
                            $n = 0;
                            $totalTaxable_amount = 0;
                            $total_tax = 0;
                            $total_amount = 0;
                            $round_off = 0;
                            foreach ($result as $key => $row) {
                                $n++;
                                $totalTaxable_amount += $row->taxableAmount;
                                $total_tax += $row->totalTax;
                                $total_amount += $row->total;
                                $round_off = $round_off + $row->roundOff;
                    ?>
                    <tr>
                        <td style="padding:3px;"><?= $n; ?></td>
                        <td style="padding:3px;"><?= $row->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= date("d-m-Y", strtotime($row->invoiceDate)); ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                        <td style="padding:3px;"><?= $row->customerMobile; ?></td>
                        <td style="padding:3px;"><?= $row->gstNo; ?></td>
                        <td style="padding:3px;">
                            <?php
                                if ($row->voucherType == 1) {
                                    $payment_mode = "Cash";
                                } else if ($row->voucherType == 2) {
                                    $payment_mode = "Credit";
                                } else {
                                    $payment_mode = $row->voucherType;
                                }
                                echo $payment_mode;
                            ?>
                        </td> 
                        <td style="padding:3px;"><?= number_format($row->taxableAmount); ?></td>
                        <td style="padding:3px;"><?= number_format($row->totalTax); ?></td>
                        <td style="padding:3px;"><?= number_format($row->total); ?></td>
                        <td style="padding:3px;"><?= $row->created; ?></td>
                        <td style="padding:3px;"><?= $row->created_by; ?></td>
                        <td style="padding:3px;"><?= $row->updated; ?></td>
                        <td style="padding:3px;"><?= $row->updated_by; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px; text-align: right;" colspan="7">Total</th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($totalTaxable_amount); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($total_tax); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($total_amount); ?></th>
                        <th style="padding:3px;" colspan="4"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } elseif ($sale_register == "description") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Receipt Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">Mobile</th>
                        <th style="padding:3px; font-size:15px;">Email</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Cust. Address</th>
                        <th style="padding:3px; font-size:15px;">Ship. Address</th>
                        <th style="padding:3px; font-size:15px;">Cust. City</th>
                        <th style="padding:3px; font-size:15px;">Cust. State</th>
                        <th style="padding:3px; font-size:15px;">Payment Mode</th> 
                        <th style="padding:3px; font-size:15px;">Taxable Amount</th>
                        <th style="padding:3px; font-size:15px;">Tax</th>
                        <th style="padding:3px; font-size:15px;">CGST</th>
                        <th style="padding:3px; font-size:15px;">SGST</th>
                        <th style="padding:3px; font-size:15px;">IGST</th>
                        <th style="padding:3px; font-size:15px;">Round Off</th>
                        <th style="padding:3px; font-size:15px;">Freight Charge</th>
                        <th style="padding:3px; font-size:15px;">Cash Discount</th>
                        <th style="padding:3px; font-size:15px;">CESS</th>
                        <th style="padding:3px; font-size:15px;">Total</th>
                        <th style="padding:3px; font-size:15px;">Expenses</th>
                        <th style="padding:3px; font-size:15px;">Amount In Word</th>
                        <th style="padding:3px; font-size:15px;">Vehicle No</th>
                        <th style="padding:3px; font-size:15px;">LR Date</th>
                        <th style="padding:3px; font-size:15px;">LR Num.</th>
                        <th style="padding:3px; font-size:15px;">Eway Bill</th>
                        <th style="padding:3px; font-size:15px;">Transport</th>
                        <th style="padding:3px; font-size:15px;">Pay Terms Day</th>
                        <th style="padding:3px; font-size:15px;">Due Date</th>
                        <th style="padding:3px; font-size:15px;">DC Date</th>
                        <th style="padding:3px; font-size:15px;">Cust. Balance</th>
                        <th style="padding:3px; font-size:15px;">DC Num.</th>
                        <th style="padding:3px; font-size:15px;">Old Balance</th>
                        <th style="padding:3px; font-size:15px;">Remark</th>
                        <th style="padding:3px; font-size:15px;">Order Date</th>
                        <th style="padding:3px; font-size:15px;">Order Num.</th>
                        <th style="padding:3px; font-size:15px;">Qty.</th>
                        <th style="padding:3px; font-size:15px;">Challan Num.</th>
                        <th style="padding:3px; font-size:15px;">Consignee</th>
                        <th style="padding:3px; font-size:15px;">Quotation Num.</th>
                        <th style="padding:3px; font-size:15px;">Docket No</th>
                        <th style="padding:3px; font-size:15px;">Reached Date</th>
                        <th style="padding:3px; font-size:15px;">Order Doc</th>
                        <th style="padding:3px; font-size:15px;">Docket Doc</th>
                        <th style="padding:3px; font-size:15px;">Tax Cal</th>
                        <th style="padding:3px; font-size:15px;">Company</th>
                        <th style="padding:3px; font-size:15px;">Format Invoice</th>
                        <th style="padding:3px; font-size:15px;">Financial Year</th>
                        <th style="padding:3px; font-size:15px;">L H</th>
                        <th style="padding:3px; font-size:15px;">Lot No</th>
                        <th style="padding:3px; font-size:15px;">Payment Amount</th>
                        <th style="padding:3px; font-size:15px;">Created</th>
                        <th style="padding:3px; font-size:15px;">Created By</th>
                        <th style="padding:3px; font-size:15px;">Updated</th>
                        <th style="padding:3px; font-size:15px;">Updated By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($result) {
                            $n = 0;
                            $totalTaxable_amount = 0;
                            $total_tax = 0;
                            $total_amount = 0;
                            $round_off = 0;
                            foreach ($result as $key => $row) {
                                $n++;
                                $totalTaxable_amount += $row->taxableAmount;
                                $total_tax += $row->totalTax;
                                $total_amount += $row->total;
                                $round_off = $round_off + $row->roundOff;
                    ?>
                    <tr>
                        <td style="padding: 3px;"><?= $n; ?></td>
                        <td style="padding: 3px;"><?= $row->invoiceNumber; ?></td>
                        <td style="padding: 3px;"><?php if (!empty($row->invoiceDate)) { echo date("d-m-Y", strtotime($row->invoiceDate)); } ?></td>
                        <td style="padding: 3px;"><?php if (!empty($row->recieptDate)) { echo date("d-m-Y", strtotime($row->recieptDate)); } ?></td>
                        <td style="padding: 3px;" width="300"><?= get_title('parties', ['id' => $row->customerId], 'name'); ?></td>
                        <td style="padding: 3px;"><?= $row->customerMobile; ?></td>
                        <td style="padding: 3px;"><?= $row->customerEmail; ?></td>
                        <td style="padding: 3px;"><?= $row->gstNo; ?></td>
                        <td style="padding: 3px;" width="500"><?= $row->customerAddress; ?></td>
                        <td style="padding: 3px;" width="400"><?= $row->shipping_address; ?></td>
                        <td style="padding: 3px;"><?= get_title('districts', ['id' => $row->customerCity], 'name'); ?></td>
                        <td style="padding: 3px;"><?= get_title('state_list', ['id' => $row->customerState], 'state'); ?></td>
                        <td style="padding: 3px;">
                            <?php
                                if ($row->voucherType == 1) {
                                    $payment_mode = "Cash";
                                } else if ($row->voucherType == 2) {
                                    $payment_mode = "Credit";
                                } else {
                                    $payment_mode = $row->voucherType;
                                }
                                echo $payment_mode;
                            ?>
                        </td> 
                        <td style="padding: 3px;"><?= number_format($row->taxableAmount); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->totalTax); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->totalcgst); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->totalsgst); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->totaligst); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->roundOff); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->freight_charge); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->cash_discount); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->cess); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->total); ?></td>
                        <td style="padding: 3px;"><?= number_format($row->expenses); ?></td>
                        <td style="padding: 3px;"><?= $row->amountInWords; ?></td>
                        <td style="padding: 3px;"><?= $row->vahical_no; ?></td>
                        <td style="padding: 3px;"><?php if (!empty($row->lrDate)) { echo date('d-m-Y', strtotime($row->lrDate)); } ?></td>
                        <td style="padding: 3px;"><?= $row->lrNumber; ?></td>
                        <td style="padding: 3px;"><?= $row->ewayBill; ?></td>
                        <td style="padding: 3px;"><?= $row->transport; ?></td>
                        <td style="padding: 3px;"><?= $row->payTermsDay; ?></td>
                        <td style="padding: 3px;"><?php if (!empty($row->dueDate)) { echo date('d-m-Y', strtotime($row->dueDate)); } ?></td>
                        <td style="padding: 3px;"><?php if (!empty($row->dcDate)) { echo date('d-m-Y', strtotime($row->dcDate)); } ?></td>
                        <td style="padding: 3px;"><?= number_format($row->customerBalance); ?></td>
                        <td style="padding: 3px;"><?= $row->dcNumber; ?></td>
                        <td style="padding: 3px;"><?= number_format($row->oldBalance); ?></td>
                        <td style="padding: 3px;"><?= $row->remark; ?></td>
                        <td style="padding: 3px;"><?php if (!empty($row->orderDate)) { echo date('d-m-Y', strtotime($row->orderDate)); } ?></td>
                        <td style="padding: 3px;"><?= $row->orderNumber; ?></td>
                        <td style="padding: 3px;"><?= $row->qty; ?></td>
                        <td style="padding: 3px;"><?= $row->challanNumber; ?></td>
                        <td style="padding: 3px;"><?= $row->consignee; ?></td>
                        <td style="padding: 3px;"><?= $row->quotationNumber; ?></td>
                        <td style="padding: 3px;"><?= $row->docketNo; ?></td>
                        <td style="padding: 3px;"><?php if (!empty($row->reachedDate)) { echo date('d-m-Y', strtotime($row->reachedDate)); } ?></td>
                        <td style="padding: 3px;"><?= $row->orderDoc; ?></td>
                        <td style="padding: 3px;"><?= $row->docketDoc; ?></td>
                        <td style="padding: 3px;"><?php if (($row->taxCal == 1)) { echo "Inclusive"; } elseif ($row->taxCal == 2) { echo "Exclusive"; } ?></td>
                        <td style="padding: 3px;" width="300"><?= get_title('inventory_billing', ['id' => $row->comp_id], 'company_name'); ?></td>
                        <td style="padding: 3px;"><?= $row->format_invoice; ?></td>
                        <td style="padding: 3px;"><?= get_title('year', ['id' => $row->financial_year], 'year'); ?></td>
                        <td style="padding: 3px;"><?= $row->lh; ?></td>
                        <td style="padding: 3px;"><?= $row->lot_no; ?></td>
                        <td style="padding: 3px;"><?= number_format($row->paymentAmount); ?></td>
                        <td style="padding: 3px;"><?= $row->created; ?></td>
                        <td style="padding: 3px;"><?= $row->created_by; ?></td>
                        <td style="padding: 3px;"><?= $row->updated; ?></td>
                        <td style="padding: 3px;"><?= $row->updated_by; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px; text-align: right;" colspan="13">Total</th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($totalTaxable_amount); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($total_tax); ?></th>
                        <th style="padding:3px; font-size:15px; text-align: right;" colspan="8"><?= number_format($total_amount); ?></th>
                        <th style="padding:3px;" colspan="35"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } } elseif ($estimate == true) { if ($sale_register == "general") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Type</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">Mobile</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Payment Mode</th>
                        <th style="padding:3px; font-size:15px;">Taxable Amount</th>
                        <th style="padding:3px; font-size:15px;">Tax</th>
                        <th style="padding:3px; font-size:15px;">Total</th>
                        <th style="padding:3px; font-size:15px;">Salesman</th>
                        <th style="padding:3px; font-size:15px;">Created</th>
                        <th style="padding:3px; font-size:15px;">Created By</th>
                        <th style="padding:3px; font-size:15px;">Updated</th>
                        <th style="padding:3px; font-size:15px;">Updated By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, user_id, taxableAmount, totalTax, total, type, added_at, salesman, created, created_by, updated, updated_by');
                        $this->db->from('sales');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $query1 = $this->db->get_compiled_select();

                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, user_id, taxableAmount, totalTax, total, type, added_at, salesman, created, created_by, updated, updated_by');
                        $this->db->from('estimates');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $query2 = $this->db->get_compiled_select();

                        $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC")->result();
                        
                        if (!empty($allsales)) { 
                            $n = 0;
                            $TotalTaxableAmount = 0;
                            $TotalTax = 0;        
                            $TotalAmount = 0;
                            foreach ($allsales as $key => $data) { 
                                $n++;
                                $TotalTaxableAmount += $data->taxableAmount;
                                $TotalTax += $data->totalTax;
                                $TotalAmount += $data->total;
                    ?>
                    <tr>
                        <td style="padding: 3px;"><?= $n; ?></td>
                        <td style="padding: 3px;"><?= $data->type; ?></td>
                        <td style="padding: 3px;"><?= $data->invoiceNumber; ?></td>
                        <td style="padding: 3px;"><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                        <td style="padding: 3px;"><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                        <td style="padding: 3px;"><?= $data->customerMobile; ?></td>
                        <td style="padding: 3px;"><?= $data->gstNo; ?></td>
                        <td style="padding: 3px;">
                            <?php
                                if ($data->voucherType == 1) {
                                    $payment_mode = "Cash";
                                } elseif ($data->voucherType == 2) {
                                    $payment_mode = "Credit";
                                } else {
                                    $payment_mode = $data->voucherType;
                                }
                                echo $payment_mode;
                            ?>
                        </td>
                        <td style="padding: 3px;">
                            <?= !empty($data->taxableAmount) ? number_format($data->taxableAmount) : ""; ?>
                        </td>
                        <td style="padding: 3px;">
                            <?= !empty($data->totalTax) ? number_format($data->totalTax) : ""; ?>
                        </td>
                        <td style="padding: 3px;"><?= number_format($data->total); ?></td>
                        <td style="padding: 3px;">
                            <?php
                                if (!empty($data->salesman)) {
                                    echo get_title('salesman', ['id' => $data->salesman], 'name');
                                }
                            ?>
                        </td>
                        <td style="padding: 3px;">
                            <?php 
                                if (!empty($data->created)) { 
                                    echo $data->created; 
                                } else { 
                                    echo date('d-m-Y h:i:s A', strtotime($data->added_at)); 
                                } 
                            ?>
                        </td>
                        <td style="padding: 3px;">
                            <?php
                                if (!empty($data->created_by)) {
                                    echo $data->created_by;
                                } else {
                                    echo get_title('users', ['id' => $data->user_id], 'name');
                                }
                            ?>
                        </td>
                        <td style="padding: 3px;"><?= $data->updated; ?></td>
                        <td style="padding: 3px;"><?= $data->updated_by; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px;" colspan="8">Total</th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTaxableAmount); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTax); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalAmount); ?></th>
                        <th style="padding:3px;" colspan="6"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } elseif ($sale_register == "columnar") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Type</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Customer</th>
                        <th style="padding:3px; font-size:15px;">GSTIN</th>
                        <th style="padding:3px; font-size:15px;">POS</th>
                        <th style="padding:3px; font-size:15px;">Payment Mode</th>
                        <th style="padding:3px; font-size:15px;">Round Off</th>
                        <th style="padding:3px; font-size:15px;">Taxable Amount</th>
                        <?php
                            $this->db->select('tax');
                            $this->db->from('invoice_product');
                            $this->db->where('user_id', $user_id);
                            $this->db->where('comp_id', $comp_id);
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->where('tax !=', NULL);
                            $this->db->group_by('tax');
                            $query1 = $this->db->get_compiled_select();

                            $this->db->select('tax');
                            $this->db->from('estimate_product');
                            $this->db->where('user_id', $user_id);
                            $this->db->where('comp_id', $comp_id);
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->where('tax !=', NULL);
                            $this->db->group_by('tax');
                            $query2 = $this->db->get_compiled_select();

                            $alltax = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY tax ASC")->result();
                            if (isset($alltax) && !empty($alltax)) {
                                foreach ($alltax as $key => $row) {
                                    if (is_numeric($row->tax) && !empty($row->tax)) {
                                        $total_gsttype[$row->tax] = $row->tax;
                        ?>
                        <th style="padding:3px; font-size:15px;"><span>Taxable Amt @ </span><?= $row->tax; ?></th>
                        <?php } } } ?>
                        <th style="padding:3px; font-size:15px;">CGST</th>
                        <th style="padding:3px; font-size:15px;">SGST</th>
                        <th style="padding:3px; font-size:15px;">IGST</th>
                        <th style="padding:3px; font-size:15px;">CESS</th>
                        <th style="padding:3px; font-size:15px;">Invoice Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, gstNo, state_name, voucherType, taxableAmount, roundOff, total, type');
                        $this->db->from('sales');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $query3 = $this->db->get_compiled_select();

                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, gstNo, state_name, voucherType, taxableAmount, roundOff, total, type');
                        $this->db->from('estimates');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $query4 = $this->db->get_compiled_select();

                        $allsales = $this->db->query($query3 . " UNION " . $query4 . " ORDER BY invoiceDate DESC")->result();
                        if (!empty($allsales)) {
                            $n = 0;
                            $TotalTaxableAmount = 0;
                            $TotalAmount = 0;
                            $TotalRoundOff = 0;
                            foreach ($allsales as $key => $data) {
                                $n++;
                                $TotalTaxableAmount += $data->taxableAmount;
                                $TotalRoundOff += $data->roundOff;
                                $TotalAmount += $data->total;
                    ?>
                    <tr>
                        <td style="padding:3px;"><?= $n; ?></td>
                        <td style="padding:3px;"><?= $data->type; ?></td>
                        <td style="padding:3px;"><?= $data->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= date("d-m-Y", strtotime($data->invoiceDate)); ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                        <td style="padding:3px;"><?= $data->gstNo; ?></td>
                        <td style="padding:3px;"><?= $data->state_name; ?></td>
                        <?php
                            if ($data->voucherType == 1) {
                                $payment_mode = "Cash";
                            } elseif ($data->voucherType == 2) {
                                $payment_mode = "Credit";
                            } else {
                                $payment_mode = $data->voucherType;
                            }
                        ?>
                        <td style="padding:3px;"><?= $payment_mode; ?></td>
                        <td style="padding:3px;"><?= $data->roundOff; ?></td>
                        <td style="padding:3px;"><?= $data->taxableAmount; ?></td>
                        <?php
                            $overall_cgst = 0;
                            $overall_sgst = 0;
                            $overall_igst = 0;

                            foreach ($total_gsttype as $key => $total_tax) {
                                $this->db->select('tax');
                                $this->db->select_sum('price');
                                $this->db->select_sum('cgst');
                                $this->db->select_sum('sgst');
                                $this->db->select_sum('igst');
                                $this->db->from('invoice_product');
                                $this->db->where('invoiceID', $data->id);
                                $this->db->where('InvoiceNo', $data->invoiceNumber);
                                $this->db->where('tax', $total_tax);
                                $this->db->where('type', 'Sale');
                                $this->db->where('status', 1);
                                $this->db->group_by('tax');
                                $tax1 = $this->db->get_compiled_select();

                                $this->db->select('tax');
                                $this->db->select_sum('price');
                                $this->db->select_sum('cgst');
                                $this->db->select_sum('sgst');
                                $this->db->select_sum('igst');
                                $this->db->from('estimate_product');
                                $this->db->where('invoiceID', $data->id);
                                $this->db->where('InvoiceNo', $data->invoiceNumber);
                                $this->db->where('tax', $total_tax);
                                $this->db->where('type', 'Sale');
                                $this->db->where('status', 1);
                                $this->db->group_by('tax');
                                $tax2 = $this->db->get_compiled_select();

                                $alltaxes = $this->db->query($tax1 . " UNION " . $tax2 . " ORDER BY tax ASC")->result();
                                if (!empty($alltaxes)) {
                                    foreach ($alltaxes as $key => $taxvalue) {
                                        $overall_cgst += $taxvalue->cgst;
                                        $overall_sgst += $taxvalue->sgst;
                                        $overall_igst += $taxvalue->igst;
                        ?>
                        <td style="padding:3px;"><?= !empty($taxvalue->price) ? money_formats($taxvalue->price) : ""; ?></td>
                        <?php } } else { ?>
                        <td style="padding:3px;">0.00</td>
                        <?php } } ?>
                        <td style="padding:3px;">
                            <?= !empty($overall_cgst) ? $overall_cgst : "0.00"; ?>
                        </td>
                        <td style="padding:3px;">
                            <?= !empty($overall_sgst) ? $overall_sgst : "0.00"; ?>
                        </td>
                        <td style="padding:3px;">
                            <?= !empty($overall_igst) ? $overall_igst : "0.00"; ?>
                        </td>
                        <td style="padding:3px;"><?= 0; ?></td>
                        <td style="padding:3px;"><?= number_format($data->total, 2); ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;" colspan="8">Total</th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalRoundOff, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTaxableAmount, 2); ?></th>
                        <?php
                            $this->db->select('tax');
                            $this->db->from('invoice_product');
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $taxquery1 = $this->db->get_compiled_select();

                            $this->db->select('tax');
                            $this->db->from('estimate_product');
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $taxquery2 = $this->db->get_compiled_select();

                            $alltaxdata = $this->db->query($taxquery1 . " UNION " . $taxquery2 . " ORDER BY tax ASC")->result();
                            if (isset($alltaxdata) && !empty($alltaxdata)) {
                                foreach ($alltaxdata as $taxrow) {
                        ?>
                        <th style="padding:3px;"></th>
                        <?php } } ?>
                        <th style="padding:3px;"></th>
                        <th style="padding:3px;"></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalAmount, 2); ?></th>
                        <th style="padding:3px;"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
	<?php } elseif ($sale_register == "hsn") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">HSN No</th>
                        <th style="padding:3px; font-size:15px;">Description</th>
                        <th style="padding:3px; font-size:15px;">Total Qty</th>
                        <th style="padding:3px; font-size:15px;">Tax</th>
                        <th style="padding:3px; font-size:15px;">Taxable Total</th>
                        <th style="padding:3px; font-size:15px;">CGST</th>
                        <th style="padding:3px; font-size:15px;">SGST</th>
                        <th style="padding:3px; font-size:15px;">IGST</th>
                        <th style="padding:3px; font-size:15px;">Cess Amount</th>
                        <th style="padding:3px; font-size:15px;">Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $this->db->select('hsn, productname, tax');
                        $this->db->select_sum('price');
                        $this->db->select_sum('qty');
                        $this->db->select_sum('cgst');
                        $this->db->select_sum('sgst');
                        $this->db->select_sum('igst');
                        $this->db->select_sum('netAmount');
                        $this->db->from('invoice_product');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('type', 'Sale');
                        $query1 = $this->db->get_compiled_select();

                        $this->db->select('hsn, productname, tax');
                        $this->db->select_sum('price');
                        $this->db->select_sum('qty');
                        $this->db->select_sum('cgst');
                        $this->db->select_sum('sgst');
                        $this->db->select_sum('igst');
                        $this->db->select_sum('netAmount');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->from('estimate_product');
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('type', 'Sale');
                        $query2 = $this->db->get_compiled_select();

                        $allsaleproducts = $this->db->query($query1 . " UNION " . $query2 . " GROUP BY hsn, tax")->result();
                        if (!empty($allsaleproducts)) {
                            $n = 0;
                            foreach ($allsaleproducts as $key => $data) {
                                $n++;
                    ?>
                    <tr>
                        <td style="padding:3px;"><?= $n; ?></td>
                        <td style="padding:3px;"><?= $data->hsn; ?></td>
                        <td style="padding:3px;"><?= $data->productname; ?></td>
                        <td style="padding:3px;"><?= number_format($data->qty); ?></td>
                        <td style="padding:3px;"><?= $data->tax; ?></td>
                        <td style="padding:3px;"><?= !empty($data->price) ? money_formats($data->price, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= $data->cgst != 0 ? money_formats($data->cgst, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= $data->sgst != 0 ? money_formats($data->sgst, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= $data->igst != 0 ? money_formats($data->igst, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= 0.00; ?></td>
                        <td style="padding:3px;"><?= money_formats($data->netAmount, 2); ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
        <?php } elseif ($sale_register == "detailview") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Type</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">City</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Round Off</th>
                        <th style="padding:3px; font-size:15px;">Taxable Amt</th>
                        <?php
                            $this->db->select('tax');
                            $this->db->from('invoice_product');
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $tax1 = $this->db->get_compiled_select();

                            $this->db->select('tax');
                            $this->db->from('estimate_product');
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $tax2 = $this->db->get_compiled_select();

                            $alltax = $this->db->query($tax1 . " UNION " . $tax2 . " ORDER BY tax ASC")->result();
                            if (isset($alltax) && !empty($alltax)) {
                                foreach ($alltax as $key => $row) {
                                    if (is_numeric($row->tax)) {
                                        $total_gsttype[$row->tax] = $row->tax;
                        ?>
                        <th style="padding:3px; font-size:15px;"><span>Taxable Amt @ </span><?= number_format($row->tax); ?></th>
                        <th style="padding:3px; font-size:15px;">CGST</th>
                        <th style="padding:3px; font-size:15px;">SGST</th>
                        <th style="padding:3px; font-size:15px;">IGST</th>
                        <?php } } } ?>
                        <th style="padding:3px; font-size:15px;">Tax Total</th>
                        <th style="padding:3px; font-size:15px;">Invoice Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerCity, gstNo, taxableAmount, totalTax, roundOff, totalValue, type');
                        $this->db->from('sales');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $query1 = $this->db->get_compiled_select();

                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerCity, gstNo, taxableAmount, totalTax, roundOff, totalValue, type');
                        $this->db->from('estimates');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $query2 = $this->db->get_compiled_select();

                        $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC")->result();
                        if (!empty($allsales)) {
                            $n = 0;
                            $TotalRoundOff = 0;
                            $TotalTaxableAmount = 0;
                            $TotalTax = 0;
                            $TotalAmount = 0;
                            foreach ($allsales as $key => $data) {
                                $n++;
                                $TotalRoundOff += $data->roundOff;
                                $TotalTaxableAmount += $data->taxableAmount;
                                $TotalTax += $data->totalTax;
                                $TotalAmount += $data->totalValue;
                    ?>
                    <tr>
                        <td style="padding:3px;"><?= $n; ?></td>
                        <td style="padding:3px;"><?= $data->type; ?></td>
                        <td style="padding:3px;"><?= $data->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                        <td style="padding:3px;"><?= get_title('districts', ['id' => $data->customerCity], 'name'); ?></td>
                        <td style="padding:3px;"><?= $data->gstNo; ?></td>
                        <td style="padding:3px;"><?= number_format($data->roundOff, 2); ?></td>
                        <td style="padding:3px;"><?= number_format($data->taxableAmount, 2); ?></td>
                        <?php
                            $this->db->select('tax');
                            $this->db->select_sum('price');
                            $this->db->select_sum('cgst');
                            $this->db->select_sum('sgst');
                            $this->db->select_sum('igst');
                            $this->db->from('invoice_product');
                            $this->db->where('invoiceID', $data->id);
                            $this->db->where('InvoiceNo', $data->invoiceNumber);
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $taxvalue1 = $this->db->get_compiled_select();

                            $this->db->select('tax');
                            $this->db->select_sum('price');
                            $this->db->select_sum('cgst');
                            $this->db->select_sum('sgst');
                            $this->db->select_sum('igst');
                            $this->db->from('estimate_product');
                            $this->db->where('invoiceID', $data->id);
                            $this->db->where('InvoiceNo', $data->invoiceNumber);
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $taxvalue2 = $this->db->get_compiled_select();

                            $alltaxes = $this->db->query($taxvalue1 . " UNION " . $taxvalue2 . " ORDER BY tax ASC")->result();

                            foreach ($total_gsttype as $key => $total_tax) {
                                $this->db->select('tax');
                                $this->db->select_sum('price');
                                $this->db->select_sum('cgst');
                                $this->db->select_sum('sgst');
                                $this->db->select_sum('igst');
                                $this->db->from('invoice_product');
                                $this->db->where('invoiceID', $data->id);
                                $this->db->where('InvoiceNo', $data->invoiceNumber);
                                $this->db->where('tax', $total_tax);
                                $this->db->where('type', 'Sale');
                                $this->db->where('status', 1);
                                $this->db->group_by('tax');
                                $taxvalue3 = $this->db->get_compiled_select();

                                $this->db->select('tax');
                                $this->db->select_sum('price');
                                $this->db->select_sum('cgst');
                                $this->db->select_sum('sgst');
                                $this->db->select_sum('igst');
                                $this->db->from('estimate_product');
                                $this->db->where('invoiceID', $data->id);
                                $this->db->where('InvoiceNo', $data->invoiceNumber);
                                $this->db->where('tax', $total_tax);
                                $this->db->where('type', 'Sale');
                                $this->db->where('status', 1);
                                $this->db->group_by('tax');
                                $taxvalue4 = $this->db->get_compiled_select();

                                $alltaxdata = $this->db->query($taxvalue3 . " UNION " . $taxvalue4 . " ORDER BY tax ASC")->result();
                                if (!empty($alltaxdata)) {
                        ?>
                        <td style="padding:3px;"><?= money_formats($alltaxdata[0]->price); ?></td>
                        <td style="padding:3px;"><?= $alltaxdata[0]->cgst != 0 ? money_formats($alltaxdata[0]->cgst, 2) : "--"; ?></td>
                        <td style="padding:3px;"><?= $alltaxdata[0]->sgst != 0 ? money_formats($alltaxdata[0]->sgst, 2) : "--"; ?></td>
                        <td style="padding:3px;"><?= $alltaxdata[0]->igst != 0 ? money_formats($alltaxdata[0]->igst, 2) : "--"; ?></td>
                        <?php } else { ?>
                        <td style="padding:3px;">--</td>
                        <td style="padding:3px;">--</td>
                        <td style="padding:3px;">--</td>
                        <td style="padding:3px;">--</td>
                        <?php } } ?>
                        <td style="padding:3px;"><?= number_format($data->totalTax, 2); ?></td>
                        <td style="padding:3px;"><?= number_format($data->totalValue, 2); ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;" colspan="7">Total</th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalRoundOff, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTaxableAmount, 2); ?></th>
                        <?php
                            $this->db->select('tax');
                            $this->db->from('invoice_product');
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $taxquery1 = $this->db->get_compiled_select();

                            $this->db->select('tax');
                            $this->db->from('estimate_product');
                            $this->db->where('type', 'Sale');
                            $this->db->where('status', 1);
                            $this->db->group_by('tax');
                            $taxquery2 = $this->db->get_compiled_select();

                            $alltaxes = $this->db->query($taxquery1 . " UNION " . $taxquery2 . " ORDER BY tax ASC")->result();
                            if (isset($alltaxes) && !empty($alltaxes)) {
                                foreach ($alltaxes as $key => $taxrow) {
                        ?>
                        <th style="padding:3px;"></th>
                        <th style="padding:3px;"></th>
                        <th style="padding:3px;"></th>
                        <th style="padding:3px;"></th>
                        <?php } } ?>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTax, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalAmount, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } elseif ($sale_register == "updateview") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Type</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">Mobile</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Payment Mode</th> 
                        <th style="padding:3px; font-size:15px;">Taxable Amount</th>
                        <th style="padding:3px; font-size:15px;">Tax</th>
                        <th style="padding:3px; font-size:15px;">Total</th>
                        <th style="padding:3px; font-size:15px;">Created</th>
                        <th style="padding:3px; font-size:15px;">Created By</th>
                        <th style="padding:3px; font-size:15px;">Updated</th>
                        <th style="padding:3px; font-size:15px;">Updated By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, user_id, taxableAmount, totalTax, total, type, added_at, created, created_by, updated, updated_by');
                        $this->db->from('sales');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $this->db->where('updated !=', '');
                        $this->db->where('updated_by !=', '');
                        $query1 = $this->db->get_compiled_select();

                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, user_id, taxableAmount, totalTax, total, type, added_at, created, created_by, updated, updated_by');
                        $this->db->from('estimates');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $this->db->where('updated !=', '');
                        $this->db->where('updated_by !=', '');
                        $query2 = $this->db->get_compiled_select();

                        $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC")->result();
                        if (!empty($allsales)) {
                            $n = 0;
                            $TotalTaxableAmount = 0;
                            $TotalTax = 0;        
                            $TotalAmount = 0;
                            foreach ($allsales as $key => $data) {
                                $n++;
                                $TotalTaxableAmount += $data->taxableAmount;
                                $TotalTax += $data->totalTax;
                                $TotalAmount += $data->total;
                    ?>
                    <tr>
                        <td style="padding:3px;"><?= $n; ?></td>
                        <td style="padding:3px;"><?= $data->type; ?></td>
                        <td style="padding:3px;"><?= $data->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= date('d-m-Y', strtotime($data->invoiceDate)); ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                        <td style="padding:3px;"><?= $data->customerMobile; ?></td>
                        <td style="padding:3px;"><?= $data->gstNo; ?></td>
                        <td style="padding:3px;">
                            <?php
                                if ($data->voucherType == 1) {
                                    $payment_mode = "Cash";
                                } elseif ($data->voucherType == 2) {
                                    $payment_mode = "Credit";
                                } else {
                                    $payment_mode = $data->voucherType;
                                }
                                echo $payment_mode;
                            ?>
                        </td>
                        <td style="padding:3px;">
                            <?= !empty($data->taxableAmount) ? number_format($data->taxableAmount) : ""; ?>
                        </td>
                        <td style="padding:3px;">
                            <?= !empty($data->totalTax) ? number_format($data->totalTax) : ""; ?>
                        </td>
                        <td style="padding:3px;"><?= number_format($data->total); ?></td>
                        <td style="padding:3px;">
                            <?php 
                                if (!empty($data->created)) { 
                                    echo $data->created; 
                                } else { 
                                    echo date('d-m-Y h:i:s A', strtotime($data->added_at)); 
                                } 
                            ?>
                        </td>
                        <td style="padding:3px;">
                            <?php
                                if (!empty($data->created_by)) {
                                    echo $data->created_by;
                                } else {
                                    echo get_title('users', ['id' => $data->user_id], 'name');
                                }
                            ?>
                        </td>
                        <td style="padding:3px;"><?= $data->updated; ?></td>
                        <td style="padding:3px;"><?= $data->updated_by; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;" colspan="8">Total</th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTaxableAmount); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTax); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalAmount); ?></th>
                        <th style="padding:3px;" colspan="5"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } elseif ($sale_register == "description") { ?>
        <div style="margin:3em">
            <h1 style="text-align: center; margin-top:1em">Sale Report</h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Type</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Receipt Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">Mobile</th>
                        <th style="padding:3px; font-size:15px;">Email</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Cust. Address</th>
                        <th style="padding:3px; font-size:15px;">Ship. Address</th>
                        <th style="padding:3px; font-size:15px;">Cust. City</th>
                        <th style="padding:3px; font-size:15px;">Cust. State</th>
                        <th style="padding:3px; font-size:15px;">Payment Mode</th> 
                        <th style="padding:3px; font-size:15px;">Taxable Amount</th>
                        <th style="padding:3px; font-size:15px;">Tax</th>
                        <th style="padding:3px; font-size:15px;">CGST</th>
                        <th style="padding:3px; font-size:15px;">SGST</th>
                        <th style="padding:3px; font-size:15px;">IGST</th>
                        <th style="padding:3px; font-size:15px;">Round Off</th>
                        <th style="padding:3px; font-size:15px;">Freight Charge</th>
                        <th style="padding:3px; font-size:15px;">Cash Discount</th>
                        <th style="padding:3px; font-size:15px;">CESS</th>
                        <th style="padding:3px; font-size:15px;">Total</th>
                        <th style="padding:3px; font-size:15px;">Expenses</th>
                        <th style="padding:3px; font-size:15px;">Amount In Word</th>
                        <th style="padding:3px; font-size:15px;">Vehicle No</th>
                        <th style="padding:3px; font-size:15px;">LR Date</th>
                        <th style="padding:3px; font-size:15px;">LR Num.</th>
                        <th style="padding:3px; font-size:15px;">Eway Bill</th>
                        <th style="padding:3px; font-size:15px;">Transport</th>
                        <th style="padding:3px; font-size:15px;">Pay Terms Day</th>
                        <th style="padding:3px; font-size:15px;">Due Date</th>
                        <th style="padding:3px; font-size:15px;">DC Date</th>
                        <th style="padding:3px; font-size:15px;">Cust. Balance</th>
                        <th style="padding:3px; font-size:15px;">DC Num.</th>
                        <th style="padding:3px; font-size:15px;">Old Balance</th>
                        <th style="padding:3px; font-size:15px;">Remark</th>
                        <th style="padding:3px; font-size:15px;">Order Date</th>
                        <th style="padding:3px; font-size:15px;">Order Num.</th>
                        <th style="padding:3px; font-size:15px;">Qty.</th>
                        <th style="padding:3px; font-size:15px;">Challan Num.</th>
                        <th style="padding:3px; font-size:15px;">Consignee</th>
                        <th style="padding:3px; font-size:15px;">Quotation Num.</th>
                        <th style="padding:3px; font-size:15px;">Docket No</th>
                        <th style="padding:3px; font-size:15px;">Reached Date</th>
                        <th style="padding:3px; font-size:15px;">Order Doc</th>
                        <th style="padding:3px; font-size:15px;">Docket Doc</th>
                        <th style="padding:3px; font-size:15px;">Tax Cal</th>
                        <th style="padding:3px; font-size:15px;">Company</th>
                        <th style="padding:3px; font-size:15px;">Format Invoice</th>
                        <th style="padding:3px; font-size:15px;">Financial Year</th>
                        <th style="padding:3px; font-size:15px;">L H</th>
                        <th style="padding:3px; font-size:15px;">Lot No</th>
                        <th style="padding:3px; font-size:15px;">Payment Amount</th>
                        <th style="padding:3px; font-size:15px;">Created</th>
                        <th style="padding:3px; font-size:15px;">Created By</th>
                        <th style="padding:3px; font-size:15px;">Updated</th>
                        <th style="padding:3px; font-size:15px;">Updated By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $this->db->select('id, financial_year, invoiceNumber, invoiceDate, recieptDate, customerId, customerMobile, customerEmail, gstNo, customerAddress, shipping_address, customerBalance, customerCity, customerState, format_invoice, voucherType, taxCal, user_id, comp_id, taxableAmount, totalTax, totalcgst, totalsgst, totaligst, roundOff, freight_charge, cash_discount, cess, total, expenses, amountInWords, type, vahical_no, lrDate, lrNumber, ewayBill, transport, payTermsDay, dueDate, dcDate, dcNumber, added_at, remark, orderDate, orderNumber, qty, challanNumber, consignee, quotationNumber, docketNo, reachedDate, orderDoc, docketDoc, lot_no, lh, paymentAmount, created, created_by, updated, updated_by');
                        $this->db->from('sales');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $query1 = $this->db->get_compiled_select();

                        $this->db->select('id, financial_year, invoiceNumber, invoiceDate, recieptDate, customerId, customerMobile, customerEmail, gstNo, customerAddress, shipping_address, customerBalance, customerCity, customerState, format_invoice, voucherType, taxCal, user_id, comp_id, taxableAmount, totalTax, totalcgst, totalsgst, totaligst, roundOff, freight_charge, cash_discount, cess, total, expenses, amountInWords, type, vahical_no, lrDate, lrNumber, ewayBill, transport, payTermsDay, dueDate, dcDate, dcNumber, added_at, remark, orderDate, orderNumber, qty, challanNumber, consignee, quotationNumber, docketNo, reachedDate, orderDoc, docketDoc, lot_no, lh, paymentAmount, created, created_by, updated, updated_by');
                        $this->db->from('estimates');
                        if (!empty($invoicenumber)) {
                            $this->db->where($invoicenumber);
                        }
                        $this->db->where($FromDate);
                        $this->db->where($ToDate);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('comp_id', $comp_id);
                        $this->db->where('status', $status);
                        $this->db->where('financial_year', $financial_year);
                        $query2 = $this->db->get_compiled_select();

                        $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC")->result();
                        if (!empty($allsales)) {
                            $n = 0;
                            $TotalTaxableAmount = 0;
                            $TotalTax = 0;
                            $TotalCGST = 0;
                            $TotalSGST = 0;
                            $TotalIGST = 0;
                            $TotalAmount = 0;
                            $TotalRoundOff = 0;
                            $TotalFreightCharge = 0;
                            $TotalCashDiscount = 0;
                            $TotalCustomerBalance = 0;
                            $TotalOldBalance = 0;
                            foreach ($allsales as $key => $data) {
                                $n++;
                                $TotalTaxableAmount += $data->taxableAmount;
                                $TotalTax += $data->totalTax;
                                $TotalCGST += $data->totalcgst;
                                $TotalSGST += $data->totalsgst;
                                $TotalIGST += $data->totaligst;
                                $TotalAmount += $data->total;
                                $TotalRoundOff += $data->roundOff;
                                $TotalFreightCharge += $data->freight_charge;
                                $TotalCashDiscount += $data->cash_discount;
                                $TotalCustomerBalance += $data->customerBalance;
                                $TotalOldBalance += $data->oldBalance;
                    ?>
                    <tr>
                        <td style="padding:3px;"><?= $n; ?></td>
                        <td style="padding:3px;"><?= $data->type; ?></td>
                        <td style="padding:3px;"><?= $data->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= !empty($data->invoiceDate) ? date("d-m-Y", strtotime($data->invoiceDate)) : ""; ?></td>
                        <td style="padding:3px;"><?= !empty($data->recieptDate) ? date("d-m-Y", strtotime($data->recieptDate)) : ""; ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('parties', ['id' => $data->customerId], 'name'); ?></td>
                        <td style="padding:3px;"><?= $data->customerMobile; ?></td>
                        <td style="padding:3px;"><?= $data->customerEmail; ?></td>
                        <td style="padding:3px;"><?= $data->gstNo; ?></td>
                        <td style="padding:3px;" width="500"><?= $data->customerAddress; ?></td>
                        <td style="padding:3px;" width="400"><?= $data->shipping_address; ?></td>
                        <td style="padding:3px;"><?= get_title('districts', ['id' => $data->customerCity], 'name'); ?></td>
                        <td style="padding:3px;"><?= get_title('state_list', ['id' => $data->customerState], 'state'); ?></td>
                        <td style="padding:3px;">
                            <?php
                                if ($data->voucherType == 1) {
                                    $payment_mode = "Cash";
                                } elseif ($data->voucherType == 2) {
                                    $payment_mode = "Credit";
                                } else {
                                    $payment_mode = $data->voucherType;
                                }
                                echo $payment_mode;
                            ?>
                        </td> 
                        <td style="padding:3px;"><?= ($data->taxableAmount != 0) ? number_format($data->taxableAmount, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->totalTax != 0) ? number_format($data->totalTax, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->totalcgst != 0) ? number_format($data->totalcgst, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->totalsgst != 0) ? number_format($data->totalsgst, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->totaligst != 0) ? number_format($data->totaligst, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->roundOff != 0) ? number_format($data->roundOff, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->freight_charge != 0) ? number_format($data->freight_charge, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->cash_discount != 0) ? number_format($data->cash_discount, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->cess != 0) ? number_format($data->cess) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->total != 0) ? number_format($data->total, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= ($data->expenses != 0) ? number_format($data->expenses) : 0; ?></td>
                        <td style="padding:3px;" width="300"><?= $data->amountInWords; ?></td>
                        <td style="padding:3px;"><?= $data->vahical_no; ?></td>
                        <td style="padding:3px;"><?= !empty($data->lrDate) ? date('d-m-Y', strtotime($data->lrDate)) : ""; ?></td>
                        <td style="padding:3px;"><?= $data->lrNumber; ?></td>
                        <td style="padding:3px;"><?= $data->ewayBill; ?></td>
                        <td style="padding:3px;"><?= $data->transport; ?></td>
                        <td style="padding:3px;"><?= $data->payTermsDay; ?></td>
                        <td style="padding:3px;"><?= !empty($data->dueDate) ? date('d-m-Y', strtotime($data->dueDate)) : ""; ?></td>
                        <td style="padding:3px;"><?= !empty($data->dcDate) ? date('d-m-Y', strtotime($data->dcDate)) : ""; ?></td>
                        <td style="padding:3px;"><?= ($data->customerBalance != 0) ? number_format($data->customerBalance, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= $data->dcNumber; ?></td>
                        <td style="padding:3px;"><?= ($data->oldBalance != 0) ? number_format($data->oldBalance, 2) : 0; ?></td>
                        <td style="padding:3px;"><?= $data->remark; ?></td>
                        <td style="padding:3px;"><?= !empty($row->orderDate) ? date('d-m-Y', strtotime($data->orderDate)) : ""; ?></td>
                        <td style="padding:3px;"><?= $data->orderNumber; ?></td>
                        <td style="padding:3px;"><?= number_format($data->qty); ?></td>
                        <td style="padding:3px;"><?= $data->challanNumber; ?></td>
                        <td style="padding:3px;"><?= $data->consignee; ?></td>
                        <td style="padding:3px;"><?= $data->quotationNumber; ?></td>
                        <td style="padding:3px;"><?= $data->docketNo; ?></td>
                        <td style="padding:3px;"><?= !empty($data->reachedDate) ? date('d-m-Y', strtotime($data->reachedDate)) : ""; ?></td>
                        <td style="padding:3px;"><?= $data->orderDoc; ?></td>
                        <td style="padding:3px;"><?= $data->docketDoc; ?></td>
                        <td style="padding:3px;"><?php if (($data->taxCal == 1)) { echo "Inclusive"; } elseif ($data->taxCal == 2) { echo "Exclusive"; } ?></td>
                        <td style="padding:3px;" width="300"><?= get_title('inventory_billing', ['id' => $data->comp_id], 'company_name'); ?></td>
                        <td style="padding:3px;"><?= $data->format_invoice; ?></td>
                        <td style="padding:3px;"><?= get_title('year', ['id' => $data->financial_year], 'year'); ?></td>
                        <td style="padding:3px;"><?= $data->lh; ?></td>
                        <td style="padding:3px;"><?= $data->lot_no; ?></td>
                        <td style="padding:3px;"><?= ($data->paymentAmount != 0) ? number_format($data->paymentAmount) : 0; ?></td>
                        <td style="padding:3px;"><?= $data->created; ?></td>
                        <td style="padding:3px;"><?= $data->created_by; ?></td>
                        <td style="padding:3px;"><?= $data->updated; ?></td>
                        <td style="padding:3px;"><?= $data->updated_by; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
                <tfoot>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;" colspan="14">Total</th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTaxableAmount, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalTax, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalCGST, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalSGST, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalIGST, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalRoundOff, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalFreightCharge, 2); ?></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalCashDiscount, 2); ?></th>
                        <th style="padding:3px;"></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalAmount, 2); ?></th>
                        <th style="padding:3px;" colspan="10"></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalCustomerBalance, 2); ?></th>
                        <th style="padding:3px;"></th>
                        <th style="padding:3px; font-size:15px;"><?= number_format($TotalOldBalance, 2); ?></th>
                        <th style="padding:3px;" colspan="22"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } } ?>
    </body>
</html>