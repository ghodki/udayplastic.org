<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic - Invoice</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Uday Plastic - Invoice" />
        <meta name="keywords" content="Uday Plastic - Invoice" />
        <!-- Favicons -->
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=PT+Serif&family=Playfair+Display&family=Roboto+Slab&display=swap" rel="stylesheet">
        <!-- Vendor CSS Files -->
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/vendor/bootstrap/css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/css/style.css" />
        <style type="text/css">
            @media print {
                @page {
                    size: auto;
                }
            }
            body {
                background: #ffffff;
                font-family: 'Noto Serif', serif;
                font-family: 'Playfair Display', serif;
                font-family: 'PT Serif', serif;
                font-family: 'Roboto Slab', serif;
                font-size: 14px;
            }
            .table-bordered, .table-bordered tr, .table-bordered th, .table-bordered td {
                border: 0.5px solid #000000;
            }
            .table td, .table th {
                padding: 0.1rem;
            }
            .total-summary td, .total-summary th {
                padding: 0.2rem;
            }
            .table {
                margin-bottom: 0rem;
            }
            p {
                margin-bottom: 0rem;
            }
        </style>
    </head>
    <body onload="Print('print');">
        <?php
            $product_field = 0;
            $comp_id = $this->session->userdata('comp_id');
            $user_id = $this->session->userdata('user_id');
            $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
            $comp_profile = $this->user_model->getRow('comp_profile', $where);

            $company_ka_data = $this->user_model->getRow('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
            $counter = 0;

            if ($company_ka_data->vahical_no == 'yes') {
                $counter = $counter + 1;
            }

            $a5config = $this->user_model->getRow('a5config', $where);

            if ($a5config->hsn == 'Y')
                ++$product_field;
            if ($a5config->qty == 'Y')
                ++$product_field;
            if ($a5config->mrp == 'Y')
                ++$product_field;
            if ($a5config->sell_price == 'Y')
                ++$product_field;
            if ($a5config->purchase_price == 'Y')
                ++$product_field;
            if ($a5config->tax == 'Y')
                ++$product_field;
            if ($a5config->tax == 'Y')
                ++$product_field;
            if ($a5config->taxable_amount == 'Y')
                ++$product_field;
            if ($a5config->net_amount == 'Y')
                ++$product_field;
            if ($a5config->taxamount == 'Y')
                ++$product_field;
            if ($a5config->sqrfoot == 'Y')
                ++$product_field;
        ?>
        <section>
            <div class="container" id="print">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" style="border-bottom: none;">
                            <tr style="border-bottom: none;">
                                <?php 
                                    if ($result->customerId != '' && $result->customerId != 0) {
                                        $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
                                    }
                                ?>
                                <td style="border: none; width: 55%; text-align: right;">
                                    <?php if ($a5config->title) { ?>
                                    <h5><?= $a5config->title; ?></h5>
                                    <?php } ?>
                                </td>
                                <?php
                                    if ($comp_profile) {
                                        $jklmn = $comp_profile->total_invoice;
                                        for ($jkl = 0; $jkl < $jklmn; $jkl++) {
                                            if ($jkl == 0) {
                                ?>
                                <td style="border: none; width: 45%;  text-align: right;"><small>ORIGINAL</small></td>
                                <?php } elseif ($jkl == 1) { ?>
                                <td style="border: none; width: 45%;  text-align: right;"><small>DUPLICATE</small></td>
                                <?php } else { ?>
                                <td style="border: none; width: 45%;  text-align: right;"><small>TRIPLICATE</small></td>
                                <?php } ?>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered" style="border-bottom: none;">
                            <tr style="border-bottom: none;">
                                <td class="col-md-4 text-center" style="border-bottom: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php
                                                $comp_id = $this->session->userdata('comp_id');
                                                $user_id = $this->session->userdata('user_id');
                                                $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
                                                $comp_profile = $this->user_model->getRow('comp_profile', $where);
                                                $log_postion = $comp_profile->logo_position;
                                                $logo_status = $comp_profile->logo_status;
                                                $signature_display = $comp_profile->signature_display;
                                                $comp_details_position = $comp_profile->comp_details_position;

                                                // Company Details
                                                $inventory_billing = $this->user_model->getRow('inventory_billing', ['id' => $comp_id, 'user_id' => $user_id]);

                                                $company_name = $inventory_billing->company_name;
                                                $company_address = $inventory_billing->address;
                                                $company_number = $inventory_billing->number;
                                                $company_email = $inventory_billing->email;
                                                $company_state_code = $inventory_billing->state;

                                                // Party Details
                                                if ($result->customerId != '' && $result->customerId != 0) {
                                                    $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
                                                }
                                                
                                                if ($logo_status == 'enable' && $log_postion) { 
                                            ?>
                                            <img src="<?= base_url(); ?>uploads/complogo/<?= $comp_profile->logo; ?>" style="width: <?= $comp_profile->logo_width ?>px;height: <?= $comp_profile->logo_height ?>px;float:<?= $log_postion ?>;text-align:<?= $log_postion ?>;">
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-12">
                                            <?php if ($a5config->comp_gst == 'Y') { ?>
                                            <p><b>GST NO : <?= $inventory_billing->gst_no; ?></b></p>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </td>
                                <td class="col-md-8" style="text-align: <?= $comp_details_position; ?>; border-bottom: none;">
                                    <?php if ($a5config->comp_name == 'Y') { ?>
                                    <h3><b><?= $company_name; ?></b></h3>
                                    <?php } if ($a5config->comp_address == 'Y') { ?>
                                    <p><?= $inventory_billing->address; ?>
                                    <?php } if ($a5config->comp_mobile_number == 'Y') { ?>
                                    <p><b>Mob : <?= $inventory_billing->number; ?></b></p>
                                    <?php } if ($a5config->comp_email == 'Y') { ?>
                                    <small><b>Email ID : <?= $inventory_billing->email; ?></b></small>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered" style="border-bottom: none;">
                            <tr style="border-bottom: none;">
                                <td style="width: 60%; padding: 5px; border-bottom: none;">
                                    <div class="row">
                                        <?php if ($a5config->invoice_number == 'Y') { ?>
                                        <div class="col-md-12">
                                            <b>Invoice No :</b>
                                            <span style="padding-left: 30px;"><?= $result->invoiceNumber; ?></span>
                                        </div>
                                        <?php } if ($a5config->invoice_date == 'Y') { ?>
                                        <div class="col-md-12">
                                            <span>Invoice Date :</span>
                                            <?php
                                                $Date = $result->invoiceDate;
                                                $newDate = date("d-m-Y", strtotime($Date));
                                            ?>
                                            <span style="padding-left: 30px;"><?= $newDate; ?></span>
                                        </div>
                                        <?php }  if ($a5config->state == 'Y') { ?>
                                        <div style="width: 60%;">
                                            <span>State :</span>
                                            <span style="padding-left: 30px;"><?= get_title('state_list', ['id' => $party->state], 'state'); ?></span>
                                        </div>
                                        <div style="width: 40%; text-align:center;">
                                            <span>State Code :</span>
                                            <span style="padding-left: 30px;"><?= get_title('state_list', ['id' => $party->state], 'code'); ?></span>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </td>
                                <?php if ($a5config->shipping_address == 'Y') { ?>
                                <td style="width: 40%; padding-top: 20px;">
                                    <div class="col-md-8">
                                        <span>Transportation Mode :</span>
                                        <span style="padding-left: 30px;"><?= $result->transport; ?></span>
                                    </div>
                                    <?php if ($company_ka_data->vahical_no == 'yes') { ?>
                                    <div class="col-md-8">
                                        <span>Vehicle Number :</span>
                                        <span style="padding-left: 30px;"><?= $result->vahical_no; ?></span>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-8">
                                        <span>Date of Supply :</span>
                                        <span style="padding-left: 30px;"></span>
                                    </div>
                                    <?php if ($a5config->state == 'Y') { ?>
                                    <div class="col-md-8">
                                        <span>Place of Supply :</span>
                                        <span style="padding-left: 30px;"><?= get_title('state_list', ['id' => $party->state], 'state'); ?></span>
                                    </div>
                                    <?php } ?>
                                </td>
                                <?php } ?>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr class="text-center">
                                <th class="col-md-7">Details of Receiver | Billed to:</th>
                                <th class="col-md-5">Details of Consignee | Shipped to:</th>
                            </tr>
                            <tr>
                                <td style="width: 60%;">
                                    <div class="col-md-8">
                                        <?php if ($a5config->customer_name == 'Y') { ?>
                                        <b> <?= $result->customerName ?></b>
                                        <?php } if ($a5config->customer_address == 'Y') { ?>
                                        <p>
                                            <?= $result->customerAddress; ?>
                                            <br />
                                            <?php $city = $this->db->get_where('districts', ['id' => $party->city])->row(); echo $city->name; ?>
                                            <br />
                                            <?php $state = $this->db->get_where('state_list', ['id' => $party->state])->row(); echo $state->state; ?>
                                            &nbsp;
                                            (<?= $party->pin; ?>)
                                        </p>
                                        <?php } ?>
                                    </div>
                                    <?php if ($a5config->customer_mobile_number == 'Y') { ?>
                                    <div class="col-md-8">
                                        <span>Contact No :</span>
                                        <span style="padding-left: 30px;"><?= $result->customerMobile; ?></span>
                                    </div>
                                    <?php } if ($a5config->customer_gst == 'Y') { ?>
                                    <div class="col-md-8">
                                        <span>GST NO :</span>
                                        <span style="padding-left: 30px;"><?= $result->gstNo; ?></span>
                                    </div>
                                    <?php } if ($a5config->state == 'Y') { ?>
                                    <div class="col-md-8">
                                        <span>STATE :</span>
                                        <span style="padding-left: 30px;"><?= $state->state; ?></span>
                                    </div>
                                    <?php } ?>
                                </td>
                                <?php if ($a5config->shipping_address == 'Y') { ?>
                                <td style="width: 40%;">
                                    <p><?= $result->shipping_address; ?></p>
                                </td>
                                <?php } ?>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered text-center" style="border-top: none;">
                            <tr style="border-top: none;">
                                <th style="border-top: none;">S.N.</th>
                                <th style="border-top: none;">DESCRIPTION</th>
                                <?php if ($a5config->hsn == 'Y') { ?>
                                <th style="border-top: none;">HSN CODE</th>
                                <?php } if ($a5config->qty == 'Y') { ?>
                                <th style="border-top: none;">QTY</th>
                                <?php }  if ($a5config->unit == 'Y') { ?>
                                <th style="border-top: none;">UNIT</th>
                                <?php } if ($a5config->discount_invoice == 'Y') { ?>
                                <th style="border-top: none;">Discount %</th>
                                <?php } if ($a5config->mrp == 'Y') { ?>
                                <th style="border-top: none;">MRP</th>
                                <?php } if ($a5config->sell_price == 'Y') { ?>
                                <th style="border-top: none;">RATE</th>
                                <?php } if ($a5config->tax == 'Y') { ?>
                                <th style="border-top: none;">GST %</th>
                                <?php } if ($a5config->net_amount == 'Y') { ?>
                                <th style="border-top: none;">TOTAL</th>
                                <?php } ?>
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
                                <td><?= $key + 1; ?></td>
                                <td style="text-align: left;">
                                    <?php
                                        $product_name = get_title('product', ['id' => $row->product_id], 'p_name');
                                        if ($product_name) {
                                            echo $product_name;
                                        } else {
                                            echo $product_name;
                                        }
                                        if ($row->product_dec) {
                                    ?>
                                    <br />
                                    <div class="descc"><?= $row->product_dec; ?></div>
                                    <?php } ?>
                                </td>
                                <?php if ($a5config->hsn == 'Y') { ?>
                                <td><?= $row->hsn; ?></td>
                                <?php } if ($a5config->qty == 'Y') { ?>
                                <td><?= $row->qty; ?></td>
                                <?php } if ($a5config->unit == 'Y') { ?>
                                <td><?= get_title('product', ['id' => $row->product_id], 'unit_name') ?></td>
                                <?php } if ($a5config->discount_invoice == 'Y') { ?>
                                <td><?= $row->discount; ?></td>
                                <?php } if ($a5config->mrp == 'Y') { ?>
                                <td><?= get_title('product', ['id' => $row->product_id], 'mrp') ?></td>
                                <?php } if ($a5config->sell_price == 'Y') { ?>
                                <td><?= $row->rate ?></td>
                                <?php } if ($a5config->tax == 'Y') { ?>
                                <td><?php $str = explode(".", $row->tax); echo $str[0]; ?></td>
                                <?php } ?>
                                <td><?= money_formats($row->netAmount, 2); ?></td>
                            </tr>
                            <?php } } ?>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered" style="border: none;">
                            <tr style="border-top: none; border-bottom: none;">
                                <td style="padding: 0.75rem; border-top: none; border-bottom: none;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <?php if ($a5config->bank_info == 'Y') { ?>
                                <td style="width: 65%;">
                                    <div class="row">
                                        <div class="col-md-10 mb-3">
                                            <h5 style="text-decoration: underline; text-align: center; padding-bottom: 10px;">
                                                <b>Bank Detail</b>
                                            </h5>
                                            <?php
                                                $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
                                                if (isset($bank) && !empty($bank)) {
                                            ?>
                                            <p style=" text-align: left; padding-left: 100px">
                                                <b>
                                                    <?= $bank->bank_details; ?>
                                                </b>
                                            </p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </td>
                                <?php } ?>
                                <td style="width: 35%; padding: 0px;">
                                    <table class="table table-bordered total-summary" style="width: 100%; border: none;">
                                        <?php if ($a5config->total_taxable_amount == 'Y') { ?>
                                        <tr style="border-top: none; border-left: none; border-right: none;">
                                            <th class="col-md-7" style="border-top: none; border-left: none;">Total Taxable Value</th>
                                            <td class="text-center" style="border-top: none; border-right: none;"><?= money_formats($result->taxableAmount, 2); ?></td>
                                        </tr>
                                        <?php } if ($a5config->total_summary == 'Y') { ?>
                                        <tr style="border-left: none; border-right: none;">
                                            <th style="border-left: none;">Add: SGST@ %</th>
                                            <td class="text-center" style="border-right: none;"><?= money_formats($result->totalsgst, 2); ?></td>
                                        </tr>
                                        <tr style="border-left: none; border-right: none;">
                                            <th style="border-left: none;">Add: CGST@ %</th>
                                            <td class="text-center" style="border-right: none;"><?= money_formats($result->totalcgst, 2); ?></td>
                                        </tr>
                                        <tr style="border-left: none; border-right: none;">
                                            <th style="border-left: none;">Add: IGST@ %</th>
                                            <td class="text-center" style="border-right: none;"><?= money_formats($result->totaligst, 2); ?></td>
                                        </tr>
                                        <?php } if ($a5config->total_tax_amount == 'Y') { ?>
                                        <tr style="border-left: none; border-right: none;">
                                            <th style="border-left: none;">Total Tax Amount : GST</th>
                                            <td class="text-center" style="border-right: none;"><?= money_formats($result->totalTax); ?></td>
                                        </tr>
                                        <?php } if ($a5config->grand_total == 'Y') { ?>
                                        <tr style="border-left: none; border-right: none; border-bottom: none;">
                                            <th style="border-left: none; border-bottom: none;">Total Amount After Tax</th>
                                            <td class="text-center" style="border-right: none; border-bottom: none;"><?= money_formats($result->totalValue); ?></td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered" style="border-top: none;">
                            <tr style="border-top: none;">
                                <td style="width: 65%; padding:5px; border-top: none;">
                                    <p>
                                        <small>
                                            <b>
                                                Bill Amount In Words : 
                                                <span style="padding-left: 20px;">
                                                    <?= ucwords($result->amountInWords); ?> Only
                                                </span>
                                            </b>
                                        </small>
                                    </p>
                                </td>
                                <th style="width: 20.44%; border-top: none;">TOTAL INVOICE VALUE</th>
                                <td class="text-center" style="border-top: none;">
                                    <b><?= money_formats($result->total); ?></b>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <?php if ($a5config->hsnsac_summary == 'Y') { ?>
                        <table class="table table-bordered text-center" style="border: none;">
                            <thead style="border-top: none;">
                                <tr style="border-top: none; border-bottom: none;">
                                    <th style="border-bottom: none; border-top: none;">HSN/SAC</th>
                                    <th style="border-bottom: none; border-top: none;">Taxable</th>
                                    <th colspan="2" style="border-top: none;">CGST</th>
                                    <th colspan="2" style="border-top: none;">SGCT</th>
                                    <th colspan="2" style="border-top: none;">IGST</th>
                                    <th style="border-bottom: none; border-top: none;">Total</th>
                                </tr>
                                <tr style="border-top: none;">
                                    <th style="border-top: none;"></th>
                                    <th style="border-top: none;">Value</th>
                                    <th style="border-top: none;">Rate</th>
                                    <th style="border-top: none;">Amount</th>
                                    <th style="border-top: none;">Rate</th>
                                    <th style="border-top: none;">Amount</th>
                                    <th style="border-top: none;">Rate</th>
                                    <th style="border-top: none;">Amount</th>
                                    <th style="border-top: none;">Tax Amount</th>
                                </tr>
                            </thead>
                            <tbody style="border-top: none;">
                                <?php
                                    $this->db->select('tax');
                                    $this->db->select('hsn');
                                    $this->db->select_sum('purchase_price');
                                    $this->db->select_sum('sgst');
                                    $this->db->select_sum('cgst');
                                    $this->db->select_sum('igst');
                                    $this->db->select_sum('amount');
                                    $this->db->select_sum('taxAmount');
                                    $this->db->from('invoice_product');
                                    $this->db->where('invoiceID', $result->id);
                                    $this->db->where('InvoiceNo', $result->invoiceNumber);
                                    $this->db->where('type', 'Sale');
                                    $this->db->group_by(array('tax', 'hsn'));
                                    $taxdata = $this->db->get()->result();
                                    if ($taxdata) { 
                                        foreach ($taxdata as $key => $row) { 
                                ?>
                                <tr>
                                    <td style="text-align:left;"><?= $row->hsn; ?></td>
                                    <td style="text-align:right;"><?= money_formats($row->purchase_price, 2); ?></td>
                                    <?php if ($row->cgst == "0.00" && $row->sgst == "0.00") { ?>
                                    <td style="text-align:right;">-</td>
                                    <td style="text-align:right;">-</td>
                                    <td style="text-align:right;">-</td>
                                    <td style="text-align:right;">-</td>
                                    <?php } else { ?>
                                    <td style="text-align:right;"><?= $row->tax / 2; ?></td>
                                    <td style="text-align:right;"><?= money_formats($row->cgst, 2); ?></td>
                                    <td style="text-align:right;"><?= $row->tax / 2; ?></td>
                                    <td style="text-align:right;"><?= money_formats($row->sgst, 2); ?></td>
                                    <?php } if ($row->igst == "0.00") { ?>
                                    <td style="text-align:right;">-</td>
                                    <?php } else { ?>
                                    <td style="text-align:right;"><?= $row->tax; ?></td>
                                    <?php } if ($row->igst == "0.00") { ?>
                                    <td style="text-align:right;">-</td>
                                    <?php } else { ?>
                                    <td style="text-align:right;"><?= money_formats($row->igst, 2); ?></td>
                                    <?php } ?>
                                    <td style="text-align:right;"><?= money_formats($row->amount, 2); ?></td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                            <tfoot style="border: none;">
                                <tr>
                                    <th style="text-align: right;">Total</th>
                                    <th style="text-align: right;"><i class="fa fa-inr"></i> <?= money_formats($result->taxableAmount, 2); ?></th>
                                    <?php if ($result->totalcgst == "0.00" && $result->totalsgst == "0.00") { ?>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <?php } else { ?>
                                    <th></th>
                                    <th style="text-align: right;"><?= money_formats($result->totalcgst, 2); ?></th>
                                    <th></th>
                                    <th style="text-align: right;"><?= money_formats($result->totalsgst, 2); ?></th>
                                    <?php } if ($result->totaligst == "0.00") { ?>
                                    <th></th>
                                    <th></th>
                                    <?php } else { ?>
                                    <th></th>
                                    <th style="text-align: right;"><?= money_formats($result->totaligst, 2); ?></th>
                                    <?php } ?>
                                    <th style="text-align:right;"><?= money_formats($result->totalTax, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <?php } ?>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered" style="border: none;">
                            <tr style="border: none;">
                                <td style="width: 65%; border-top: none;">
                                    <h5 style="text-decoration: underline;">
                                        <b>
                                            Declaration:
                                        </b>
                                    </h5>
                                    <?php 
                                        $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
                                        if (isset($bank) && !empty($bank)) { 
                                    ?>
                                    <p><?= $bank->content; ?></p>
                                    <?php } ?>
                                </td>
                                <td style="width: 35%; border-top: none;" class="text-center">
                                    <h5>
                                        <pre style="margin-bottom: 0rem;">For : <?= $comp_profile->name; ?></pre>
                                    </h5>
                                    <?php 
                                        if ($comp_profile->signature_height) {
                                            $signature_height = $comp_profile->signature_height;
                                        } else {
                                            $signature_height = "100";
                                        }

                                        if ($comp_profile->signature_width) {
                                            $signature_width = $comp_profile->signature_width;
                                        } else {
                                            $signature_width = "50";
                                        }
                                        if ($comp_profile->signature_display == 'enable') {
                                            if ($bank->scan_signature) {
                                    ?>
                                    <img src="<?= base_url(); ?>uploads/signature/<?= $bank->scan_signature; ?>" style=" width: <?= $signature_width ?>px; height: <?= $signature_height ?>px;">
                                    <?php } } ?>
                                    <p>Auth. Signatory</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php if ($a5config->footer == 'Y') { ?>
                <div class="col-md-12 text-center mt-3">
                    This Is a Computer Generated Invoice by Umatic
                </div>
                <?php } ?>
            </div>
        </section>
        <?php } } ?>

        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            function Print(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
    </body>
</html>