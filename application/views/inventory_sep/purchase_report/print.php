<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </head>
    <body style="font-size:13px; padding:3em;" onload="window.print();">
	<br />
	<?php if ($sale_register == "general") { ?>
        <div style="margin:3em;">
            <h1 style="text-align: center; margin-top:1em;">
                Purchase Report
            </h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc">
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
                    <?php 
                        if ($result) {
                            foreach ($result as $key => $row) {
                                $PartyInfo = $this->db->select('name, address_1, contact, email, gst')->where('id', $row->customerId)->get('parties')->row();
                    ?>
                    <tr>
                        <td style="padding:3px"><?= $key + 1 ?></td>
                        <td style="padding:3px"><?= $row->invoiceNumber; ?></td>
                        <td style="padding:3px"><?= $row->invoiceDate; ?></td>
                        <td style="padding:3px"><?= $PartyInfo->name; ?></td>
                        <td style="padding:3px"><?= $PartyInfo->address_1; ?></td>
                        <td style="padding:3px"><?= $PartyInfo->contact; ?></td>
                        <td style="padding:3px"><?= $PartyInfo->email; ?></td>
                        <td style="padding:3px"><?= $PartyInfo->gst; ?></td>
                        <td style="padding:3px"><i class="fa fa-inr" aria-hidden="true"></i> <?= $row->taxableAmount; ?></td>
                        <td style="padding:3px"><i class="fa fa-inr" aria-hidden="true"></i> <?= $row->totalTax; ?></td>
                        <td style="padding:3px"><i class="fa fa-inr" aria-hidden="true"></i> <?= $row->total; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
	<?php } else if ($sale_register == 'columnar' || $sale_register == "hsn") { ?>
        <div style="margin:3em;">
            <h1 style="text-align: center; margin-top:1em;">
                Purchase Report
            </h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Voucher Type</th>
                        <th style="padding:3px; font-size:15px;">Taxable Amt</th>
                        <?php
                            $this->db->select('tax');
                            $this->db->from('invoice_product');
                            $this->db->where('type', 'Purchase');
                            $this->db->group_by('tax');
                            $this->db->order_by('tax', 'ASC');
                            $tax = $this->db->get()->result();
                            if (isset($tax) && !empty($tax)) {
                                foreach ($tax as $row) {
                                    $total_gsttype[$row->tax] = $row->tax;

                        ?>
                        <th style="text-align:center;"><span>Taxable Amt @ </span><?= $row->tax; ?>%</th>
                        <th style="text-align:center;">SGST</td>
                        <th style="text-align:center;">CGST</td>
                        <th style="text-align:center;">IGST</td>
                        <?php }  } ?>
                        <th>Invoice Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result) { foreach ($result as $key => $row) { ?>
                    <tr>
                        <td style="padding:3px;"><?= $key + 1; ?></td>
                        <td style="padding:3px;"><?= $row->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= $row->invoiceDate; ?></td>
                        <td style="padding:3px;"><?= "Purchase"; ?></td>
                        <td style="padding:3px;"><i class="fa fa-inr" aria-hidden="true"></i> <?= $row->taxableAmount; ?></td>
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
                            $this->db->where('type', 'Purchase');
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
                                $this->db->where('type', 'Purchase');
                                $this->db->group_by('tax');
                                $this->db->order_by('tax', 'ASC');
                                $tax = $this->db->get()->result();
                                if (!empty($tax)) {
                        ?>
                        <td style="text-align:center;"><?= money_formats($tax[0]->price); ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->sgst, 2); ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->cgst, 2); ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->igst, 2); ?></td>
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
	<?php } else { ?>
        <div style="margin:3em;">
            <h1 style="text-align: center; margin-top:1em;">
                Purchase Report
            </h1>
            <table style="border:1px solid black; border-collapse:collapse; width:100%;" border="1">
                <thead>
                    <tr style="background-color:#ccc;">
                        <th style="padding:3px; font-size:15px;">Sr.</th>
                        <th style="padding:3px; font-size:15px;">Invoice No</th>
                        <th style="padding:3px; font-size:15px;">Invoice Date</th>
                        <th style="padding:3px; font-size:15px;">Party Name</th>
                        <th style="padding:3px; font-size:15px;">City</th>
                        <th style="padding:3px; font-size:15px;">GST No</th>
                        <th style="padding:3px; font-size:15px;">Voucher Type</th>
                        <th style="padding:3px; font-size:15px;">Taxable Amt</th>
                        <?php
                            $this->db->select('tax');
                            $this->db->from('invoice_product');
                            $this->db->where('type', 'Purchase');
                            $this->db->group_by('tax');
                            $this->db->order_by('tax', 'asc');
                            $tax = $this->db->get()->result();
                            if (isset($tax) && !empty($tax)) {
                                foreach ($tax as $row) {
                                    $total_gsttype[$row->tax] = $row->tax;
                        ?>
                        <th style="text-align:center;"><span>Taxable Amt @ </span><?= $row->tax; ?>%</th>
                        <th style="text-align:center;">SGST</th>
                        <th style="text-align:center;">CGST</th>
                        <th style="text-align:center;">IGST</th>
                        <?php } } ?>
                        <th>Invoice Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ($result) {
                            foreach ($result as $key => $row) {
                                $PartyInfo = $this->db->select('name, gst, city')->where('id', $row->customerId)->get('parties')->row();
                    ?>
                    <tr>
                        <td style="padding:3px;"><?= $key + 1; ?></td>
                        <td style="padding:3px;"><?= $row->invoiceNumber; ?></td>
                        <td style="padding:3px;"><?= $row->invoiceDate; ?></td>
                        <td style="padding:3px;"><?= $PartyInfo->name; ?></td>
                        <td style="padding:3px;"><?= get_title('districts', ['id' => $PartyInfo->city], 'name'); ?></td>
                        <td style="padding:3px;"><?= $PartyInfo->gst; ?></td>
                        <td style="padding:3px;"><?= "Purchase"; ?></td>
                        <td style="padding:3px;"><i class="fa fa-inr" aria-hidden="true"></i> <?= $row->taxableAmount; ?></td>
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
                            $this->db->where('type', 'Purchase');
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
                                $this->db->where('type', 'Purchase');
                                $this->db->group_by('tax');
                                $this->db->order_by('tax', 'ASC');
                                $tax = $this->db->get()->result();
                                if (!empty($tax)) {
                        ?>
                        <td style="text-align:center;"><?= money_formats($tax[0]->price); ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->sgst, 2); ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->cgst, 2); ?></td>
                        <td style="text-align:center;"><?= money_formats($tax[0]->igst, 2); ?></td>
                        <?php } else { ?>
                        <td style="text-align:center;">--</td>
                        <td style="text-align:center;">--</td>
                        <td style="text-align:center;">--</td>
                        <td style="text-align:center;">--</td>
                        <?php } } ?>
                        <td style="padding:3px;"><?= $row->totalValue; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
	<?php } ?>
    </body>
</html>