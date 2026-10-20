<html>

<head>
    <style data-merge-styles="true"></style>
    <title hidden>Uday Plastic</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->

    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>

    <style>
        .main-footer {
            display: none;
        }
    </style>

</head>

<!--<body onload="window.print()">-->

<body style="height: auto;margin-left: 16px;margin-right: 16px;">
    <?php
    $comp_id = $this->session->userdata('comp_id');
    $user_id = $this->session->userdata('user_id');
    $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
    $comp_profile = $this->user_model->getRow('comp_profile', $where);
    $inventory_details = $this->user_model->getRow('inventory_billing', ['user_id' => $user_id, 'id' => $comp_id]);
    // $company_mobile_number=$inventory_details->
    $a5config = $this->user_model->getRow('a5config', $where);


    ?>
    <h2 class="text-center" style="text-align:center;"><?= $a5config->title ?></h2>
    <table style="background-color: #fff; border: 0; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 12px;width:100%;margin-top:-21px;">
        <tr>
            <td style="text-align: center; padding: 5px 0;">
                <?php
                if ($a5config->comp_name == 'Y' && $comp_profile->name) {
                ?>
                    <h1 style="font-size:25px; margin: 0px;"><strong><?= $comp_profile->name ?></strong></h1>
                <?php
                }
                ?>
                <?php
                if ($a5config->comp_address == 'Y' && $inventory_details->address) {
                ?>
                    <h5 style="font-size: 15px; margin:  0 0 5px 0;; font-weight: normal;">Address:<?= $inventory_details->address ?></h5>
                <?php
                }
                ?>
                <?php
                if ($a5config->comp_email == 'Y' && $inventory_details->email) {
                ?>
                    <p style="margin: 0 0 5px 0;font-size: 15px">Email : <?= $inventory_details->email ?></p>
                <?php
                }
                ?>
                <?php
                if ($a5config->comp_mobile_number == 'Y' && $inventory_details->number) {
                ?>
                    <p style="margin: 0 0 5px 0;font-size: 15px">Mobile Number:<?= $inventory_details->number ?></p>
                <?php
                }
                ?>
                <?php
                if ($a5config->comp_gst == 'Y' && $inventory_details->gst_no) {
                ?>
                    <p style="margin: 0px; font-weight: bold;font-size: 15px">GSTIN: <?= $inventory_details->gst_no ?></p>
                <?php
                }
                ?>
            </td>
        </tr>

        <?php
        $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
        ?>

        <tr>
            <td>
                <table style="border: 0; border-collapse: collapse; width: 100%; font-size: 16px;">


                    <tr>
                        <td></td>
                        <td style="padding-bottom: 5px;">Invoice No. :<?= $result->invoiceNumber ?></td>
                        <td></td>
                         <td></td>
                        <td style="padding-bottom: 5px;">Invoice Date:<strong><?= date('d-m-Y', strtotime($result->invoiceDate)); ?></strong>
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
                        <?php
                        if ($a5config->hsn == 'Y') {
                            echo '<th style="border-top: 1px solid #000;">HSN/SAC</th>';
                        }
                        if ($a5config->qty == 'Y') {
                        ?>
                            <th style="border-top: 1px solid #000;">QTY</th>
                        <?php
                        }
                        ?>
                        <?php
                        if ($a5config->discount_invoice == 'Y') {
                        ?>
                            <th style="border-top: 1px solid #000;">DISCOUNT</th>
                        <?php
                        }
                        ?>
                        <?php
                        if ($a5config->unit == 'Y') {
                        ?>
                            <th style="border-top: 1px solid #000;">UNIT</th>
                        <?php
                        }
                        ?>
                        <?php
                        if ($a5config->mrp == 'Y') {
                        ?>
                            <th style="border-top: 1px solid #000;">MRP</th>
                        <?php
                        }
                        ?>
                        <?php
                        if ($a5config->sell_price == 'Y') {
                        ?>
                            <th style="border-top: 1px solid #000;">RATE</th>
                        <?php
                        }
                        ?>
                        <?php
                        if ($a5config->tax == 'Y') {
                        ?>
                            <th style="border-top: 1px solid #000;">Tax%</th>
                        <?php
                        }
                        ?>
                        <?php
                        if ($a5config->taxable_amount == 'Y') {
                        ?>
                            <th style="border-top: 1px solid #000;">Taxble Amt</th>
                        <?php
                        }
                        ?>
                        <?php
                        if ($a5config->taxamount == 'Y') {
                        ?>
                            <th style="border-top: 1px solid #000;">Tax Amount</th>
                        <?php
                        }
                        ?>
                        <?php
                        if ($a5config->net_amount == 'Y') {

                        ?>
                            <th style="border-top: 1px solid #000; text-align: right;">Total</th>
                        <?php
                        }
                        ?>
                    </tr>
                    <?php
                    $nosItm = 0;
                    $lines = 6;
                    $counts = 0;
                    $totalDiscount = 0;
                    $totalRate = 0;
                    $totalCD = 0;
                    $total_mrp = 0;
                    if ($result->product) {
                        foreach ($result->product as $key => $row) {
                            $qtty = $row->qty;
                            $nosItm += $qtty;
                            $taxIte =  get_title('product', ['id' => $row->tax], 'tax');
                            $rate = $row->rate;
                    ?>
                            <tr style="font-size: 15px;text-align:center;">
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
                                <?php
                                if ($a5config->hsn == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;"><?= $row->hsn ?></td>
                                <?php
                                }
                                ?>
                                <!-- <td style="border-top: 1px solid #000;"><?= $row->qty ?></td>
                                <td style="border-top: 1px solid #000;"><?= money_formats($row->purchase_price / $row->qty) ?></td>
                                <td style="border-top: 1px solid #000; text-align: right;"><?= money_formats($row->netAmount); ?></td> -->

                                <?php
                                if ($a5config->qty == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;"><?= $row->qty ?></td>
                                <?php
                                }
                                ?>


                                <?php
                                if ($a5config->discount_invoice == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;"><?= $row->discount ?>%</td>
                                <?php
                                }
                                ?>
                                <?php
                                if ($a5config->unit == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;"><?= get_title('product', ['id' => $row->product_id], 'unit_name') ?></td>

                                <?php
                                }
                                ?>

                                <?php
                                if ($a5config->mrp == 'Y') {
                                    $total_mrp += get_title('product', ['id' => $row->product_id], 'mrp') * $row->qty;
                                ?>
                                    <td style="border-top: 1px solid #000;"><?= get_title('product', ['id' => $row->product_id], 'mrp') ?></td>
                                <?php
                                }

                                if ($a5config->sell_price == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;"><?= money_formats($row->netAmount / $row->qty)?></td>
                                <?php
                                }
                                if ($a5config->tax == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;"><?= $row->tax ?>%</td>
                                <?php
                                }
                                if ($a5config->taxable_amount == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;">
                                        <?php
                                        echo $row->purchase_price;
                                        ?>
                                    </td>
                                <?php
                                }

                                if ($a5config->taxamount == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;">
                                        <?= money_formats($row->amount) ?>
                                    </td>
                                <?php
                                }
                                if ($a5config->net_amount == 'Y') {
                                ?>
                                    <td style="border-top: 1px solid #000;text-align: right;">
                                        <?= money_formats($row->netAmount); ?>
                                    </td>
                                <?php
                                    // }else
                                    // {
                                    // 	echo '<td style="border-right:0px;border-bottom:0px;"></td>';

                                }
                                ?>
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
                   	<?php
									if ($a5config->round_off == 'Y') {
									?>
                    <tr>
                        <td>Taxable</td>
                        <td style="text-align: right;"><?= money_formats($result->taxableAmount) ?></td>
                    </tr>
                    <?php
									}
									?>
                    	<?php
									if ($a5config->round_off == 'Y') {
									?>
                    <tr>
                        <td>Round Off</td>
                        <td style="text-align: right;"><?= money_formats($result->roundOff) ?></td>
                    </tr>
                    <?php
									}
									?>
										<?php
									if ($a5config->grand_total == 'Y') {
									?>
                    <tr>
                        <td>Grand Total</td>
                        <td style="text-align: right;"><?= money_formats($result->total) ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if ($a5config->you_saved == 'Y') {
                    ?>
                        <tr>
                            <td>You Saved</td>
                            <td style="text-align: right;"><?php

                                                            echo money_formats($total_mrp - $result->total);
                                                            ?></td>

                        </tr>
                    <?php
                    }
                    ?>
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
                        <td>
                            <p style="margin-bottom: 5px;">IGST</p>

                        </td>
                    </tr>
                    <?php
                    $this->db->select('tax');
                    $this->db->select_sum('price');
                    $this->db->select_sum('sgst');
                    $this->db->select_sum('cgst');
                    $this->db->select_sum('igst');
                    $this->db->select_sum('taxAmount');
                    $this->db->from('invoice_product');
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
                                <td><?= money_formats($row->igst, 2) ?></td>
                            </tr>
                    <?php $tcount--;
                        }
                    }
                    ?>


                </table>
            </td>
        </tr>
        
      
        <tr>

            <td style="border-top: 1px solid #000; padding: 5px 0;font-size: 15px;">
                <h4 style="margin: 0 0 0px 0;margin: 0 0 0px 0;margin-bottom: -13px;">Terms & Conditions</h4>
                <?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
                if (isset($bank) && !empty($bank)) {
                ?>
                    <p style="margin: 0 0 5px 0;"><?= $bank->content; ?></p>
                <?php
                }
                ?>
            </td>
            <td>
             <tr>
            <td style="text-align:right;font-size: 15px;">For:<b><?= $comp_profile->name ?></b></td>

        </tr>
        </td>
        </tr>
       
    </table>
    
    <script>
        $(document).ready(function() {
            window.print();
            // setTimeout("closePrintView()", 1000);
        });

        function closePrintView() {
            // 			document.location.href = "<?= base_url('sale/sale_report') ?>";
            window.open('', '_self')
            window.close()
        }
    </script>
</body>

</html>