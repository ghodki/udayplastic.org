<html>

<head>
    <style data-merge-styles="true"></style>
    <title>Uday Plastic</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->

    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
   
        <style>
.main-footer
{
    display: none;
}
        </style>
        
</head>

<body onload="window.print()">
    <?php
    $comp_id = $this->session->userdata('comp_id');
    $user_id = $this->session->userdata('user_id');
    $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
    if ($this->session->userdata('BranchRole')){
        $where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
    }
    $comp_profile = $this->user_model->getRow('comp_profile', $where);
    $inv_where = ['user_id' => $user_id, 'id' => $comp_id];
    if ($this->session->userdata('BranchRole')){
        $inv_where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
    }
    $inventory_details = $this->user_model->getRow('inventory_billing', $inv_where);

    ?>
    <table style="background-color: #fff; border: 0; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 12px;width:100%;">
        <tr>
            <td style="text-align: center; padding: 5px 0;">
                <h1 style="font-size: 18px; margin: 0px;"><?= $comp_profile->name ?></h1>
                <h5 style="font-size: 14px; margin:  0 0 5px 0;; font-weight: normal;"><?= $inventory_details->address ?></h5>
                <p style="margin: 0 0 5px 0;">Email : <?= $inventory_details->email ?></p>
                <p style="margin: 0px; font-weight: bold;">GSTIN: <?= $inventory_details->gst_no ?></p>
            </td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: center; font-weight: bold; padding: 5px; font-size: 18px;">
                GST Invoice
            </td>
        </tr>
        <?php
        $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
        ?>

        <tr>
            <td>
                <table style="border: 0; border-collapse: collapse; width: 100%; font-size: 14px;">
                    <tr>
                        <td colspan="2" style="padding: 5px 0 5px 0;">Billing Address</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 0 0 5px 0;"><?= $party->name ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 0 0 5px 0;"><?= $result->customerAddress ?></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px;">State of Supply :</td>
                        <td style="padding-bottom: 5px;"><?= get_title('state_list', ['id' => $party->state], 'code'); ?>-<?= get_title('state_list', ['id' => $party->state], 'state'); ?></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px;">Invoice No. :</td>
                        <td style="padding-bottom: 5px;"><?=  $result->invoiceNumber ?></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px;">Payment Mode : </td>
                        <td style="padding-bottom: 5px;">
                            <?php
                            if ($result->voucherType == 1) {
                                echo "Cash";
                            } elseif ($result->voucherType == 2) {
                                echo "Credit";
                            } else {
                                echo $result->voucherType;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px;">Invoice Date</td>
                        <td style="padding-bottom: 5px;">
                            <strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border: 0; border-collapse: collapse; width: 100%; font-size: 14px;">
                    <tr>
                        <th style="border-top: 1px solid #000;">SL</th>
                        <th style="border-top: 1px solid #000;">ITEM NAME</th>
                        <th style="border-top: 1px solid #000;">QTY</th>
                        <th style="border-top: 1px solid #000;">Rate</th>
                        <th style="border-top: 1px solid #000; text-align: right;">Total</th>
                    </tr>
                    <?php
                    $nosItm = 0;
                    $lines = 6;
                    $counts = 0;
                    $totalDiscount = 0;
                    $totalRate = 0;
                    $totalCD = 0;
                    if ($result->product) {
                        foreach ($result->product as $key => $row) {
                            $qtty = $row->qty;
                            $nosItm += $qtty;
                            $taxIte =  get_title('product', ['id' => $row->tax], 'tax');
                            $rate = $row->rate;
                    ?>
                            <tr>
                                <td style="border-top: 1px solid #000;"><?= $key + 1 ?></td>
                                <td style="border-top: 1px solid #000;">
                                    <?php

                                    $product_name = get_title('product', ['id' => $row->product_id], 'p_name');
                                    if ($product_name) {
                                        echo $product_name;
                                    } else {
                                        echo $product_name;
                                    }

                                    ?>
                                </td>
                                <td style="border-top: 1px solid #000;"><?= $row->qty ?></td>
                                <td style="border-top: 1px solid #000;"><?= money_formats($row->purchase_price / $row->qty) ?></td>
                                <td style="border-top: 1px solid #000; text-align: right;"><?= money_formats($row->netAmount); ?></td>
                            </tr>
                    <?php
                        }
                    }

                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border: 0; border-collapse: collapse; width: 100%; font-size: 14px; font-weight: bold; border-top: 1px solid #000;">
                    <tr>
                        <td>Taxable</td>
                        <td style="text-align: right;"><?= money_formats($result->taxableAmount) ?></td>
                    </tr>
                    <tr>
                        <td>Round Off</td>
                        <td style="text-align: right;"><?= money_formats($result->roundOff) ?></td>
                    </tr>
                    <tr>
                        <td>Grand Total</td>
                        <td style="text-align: right;"><?= money_formats($result->total) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="border: 0; border-collapse: collapse; width: 100%; font-size: 14px; font-weight: bold; border-top: 1px solid #000;">
                    <tr>

                        <td>
                            <p style="margin-bottom: 5px;">SUMMERY</p>
                           
                        </td>
                        <td>
                            <p style="margin-bottom: 5px;">TAXABLE</p>
                            
                        </td>
                        <td>
                            <p style="margin-bottom: 5px;">CGST</p>
                            
                        </td>
                        <td>
                            <p style="margin-bottom: 5px;">SGST</p>
                           
                        </td>
                    </tr>
                        <?php
											$this->db->select('tax');
											$this->db->select_sum('price');
											$this->db->select_sum('sgst');
											$this->db->select_sum('cgst');
											$this->db->select_sum('igst');
											$this->db->select_sum('taxAmount');
											$this->db->from('estimate_product');
											$this->db->where('invoiceID', $result->id);
											$this->db->where('InvoiceNo', $result->invoiceNumber);
											$this->db->where('type', 'Sale');
											$this->db->group_by('tax');
											$tax = $this->db->get()->result();
											$tcount = 4;
											if (isset($tax) && !empty($tax)) {
												foreach ($tax as $row) { ?>
													<tr>
														<td class="tax_slab" style="text-align:left"><b>GST @<?= $row->tax; ?>%</b></td>
														<td><?= money_formats($row->price) ?></td>
														<!-- <td><?= money_formats($row->taxAmount, 2) ?></td> -->
														<td><?= money_formats($row->sgst, 2) ?></td>
														<td><?= money_formats($row->cgst, 2) ?></td>
														<!-- <td><?= money_formats($row->igst, 2) ?></td> -->
													</tr>
												<?php $tcount--;
												}
											}
                                            ?>

                    
                </table>
            </td>
        </tr>
        <tr>

            <td style="border-top: 1px solid #000; padding: 5px 0;">
                <h4 style="margin: 0 0 0px 0;">Terms & Conditions</h4>
                <?php
                $bank_where = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')){
                    $bank_where['CompanyBranchId'] =  $this->session->userdata('CompBranchId'); 
                }
                $bank = $this->db->get_where('comp_profile', $bank_where)->row();
                if (isset($bank) && !empty($bank)) {
                ?>
                    <p style="margin: 0 0 5px 0;"><?= $bank->content; ?></p>
                <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; border-top: 1px solid #000;">Thank you for shopping at <?= $comp_profile->name ?></td>
        </tr>
    </table>
</body>

</html>