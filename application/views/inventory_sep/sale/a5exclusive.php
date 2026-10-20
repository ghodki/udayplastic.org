<html>

<head>
    <style data-merge-styles="true"></style>
    <title>Uday Plastic</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <style>
        /* @font-face {
            font-family: 'ArialMt';
            src: url('../assets/fonts/Arial MT.ttf') format("truetype");
        }

        @font-face {
            font-family: 'ArialMt Bold';
            src: url('../assets/fonts/arial-mt-bold.ttf') format("truetype");
        } */

        body {
            font-family: ArialMt;
            margin: 0px;
            font-size: 15px;
        }

        .main_container {
            page-break-after: always;
            /* width: 19.1; */
            margin: auto;
        }

        table {
            border-spacing: 0;
        }

        thead {
            display: table-header-group !important;
        }

        tfoot {
            display: table-row-group !important;
        }

        tr {
            page-break-inside: avoid !important;
        }

        .copy_title {
            font-size: 14px;
            text-align: right;
        }

        .container {
            border: 1px solid;
            /*border-top: 0px;*/
        }

        .hidden_head th {
            border: 0px !important;
            border-top: 1px solid !important;
        }

        .header {
            padding: 5px 10px;
            font-size: 14px;
            clear: both;
            overflow: auto;
        }

        /* .name {
			font-size: 20px;
			font-family: 'ArialMt Bold';
			font-weight: bold;
		} */

        .address {
            white-space: pre-wrap;
            font-size: 16px;

        }

        .gstin,
        .pan,
        .cin {
            font-size: 15px;
            font-family: 'ArialMt Bold';
            font-weight: bold;
            display: block;
        }

        .invoice_title {
            text-align: center;
            padding: 3px;
            font-size: 15px;
            font-family: 'ArialMt Bold';
            font-weight: bold;
            border-top: 1px solid;
            border-bottom: 1px solid;
        }

        .invoice_details {
            font-size: 16px;
            padding: 5px 0px;
            word-break: break-word;
        }

        .billing_address {
            display: inline-block;
            padding-left: 7px;
            width: 100%;

        }

        .shipping_address {
            display: inline-block;
            padding-left: 7px;
            width: 200px;
        }

        .invoice_details .name {
            font-size: 16px;
            font-family: 'ArialMt Bold';
            font-weight: bold;
        }

        .invoice_details .gstin,
        .invoice_details .pan,
        .invoice_details .cin {
            font-size: 16px;
            font-family: 'ArialMt Bold';
            font-weight: bold;
            display: block;
        }

        .gstin:before {
            content: 'GSTIN : ';
        }

        .pan:before {
            content: 'PAN : ';
        }

        .contact_no:before,
        .mobile:before {
            content: 'Contact : ';
        }

        .email:before {
            content: 'E-mail : ';
        }

        .cin:before {
            content: 'CIN : ';
        }

        .inv_rows {
            font-size: 15px;
            float: right;
            width: 450px;
        }

        .inv_rows th {
            border: 0px !important;
            text-align: left !important;
            font-family: 'ArialMt Bold';
            font-weight: bold;
            width: 150px;
        }

        .inv_rows td.ad {}

        .inv_rows th:after {
            content: ' : ';
            font-family: 'ArialMt Bold';
            font-weight: bold;
            float: right;

        }

        .inv_rows td {
            border: 0px !important;
            text-align: right;
            padding-right: 10px !important;
        }

        .item_table thead th {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .inv_rows tbody th {
            text-align: left !important;
        }

        .item_table th {
            vertical-align: middle !important;
        }

        .item_table {
            width: 100%;
            font-size: 18px;
            /* table-layout: fixed; */
            word-break: break-all;
        }

        .item_table th {
            border-top: 1px solid;
            border-bottom: 1px solid;
            font-family: 'ArialMt Bold';
            font-weight: bold;
        }

        .item_table th:last-child,
        .item_table td:last-child {
            border-right: 0px;
        }

        .item_table th,
        .item_table td {
            border-right: 1px solid;
            page-break-inside: avoid;
            padding: 1px 2px;
        }

        .item_table td {
            vertical-align: top;
        }

        .item_table .item_no {
            width: 40px;
            text-align: center;
            vertical-align: top;
        }

        .item_table .item_code {
            width: 75px;
            text-align: center;
        }

        .item_table .item_hsn {
            width: 84px;
            text-align: center;
        }

        .item_table .item_desc {
            font-size: 18px;
            white-space: pre-wrap;
        }

        .item_table .item_unit {
            width: 40px;
            text-align: center;
        }

        .item_table .item_qty {
            width: 104px;

        }

        .item_table .item_rate {
            width: 75px;
            text-align: right;
        }

        .item_table .tax {
            width: 75px;
            text-align: right;
        }

        .item_table .item_mrp {
            width: 75px;
            text-align: right;
        }

        .item_table .item_discount {
            width: 70px;
            text-align: right;
        }

        .item_table .item_tax_amount {
            width: 75px;
            text-align: right;
        }

        .item_table .item_tax_perc {
            width: 25px;
            text-align: center;
        }

        .item_table .item_tax_perc_only {
            width: 40px;
            text-align: center;
        }

        .item_table .item_tax_type {
            width: 45px;
            text-align: center;
        }

        .item_table .item_taxable {
            width: 75px;
            text-align: right;
        }

        .item_table .item_total {
            width: 75px;
            text-align: right;
        }

        .header2 th:last-child {
            border-right: 1px solid;
        }

        .header2 th {
            border-top: 0px;
        }

        .summary_table_div {
            padding: 0px 10px;
            vertical-align: top;
            border-right: 1px solid;
            width: 530px;
        }

        .tax_summary {
            width: 100%;
        }

        .tax_summary th {
            font-family: 'ArialMt Bold';
            font-weight: bold;
            font-size: 18px;
        }

        .tax_summary_details {
            text-align: right;
        }

        .tax_summary td {
            font-size: 16px;
            text-align: right;
        }

        .tax_slab {
            text-align: left;
        }

        .tax_summary .slap_perc {
            font-size: 16px;
            padding-left: 30px;
            text-align: left;
            float: left;
        }

        .total_summary {
            width: 493px;
            vertical-align: top;
        }

        .total_summary .summary_label {
            display: inline-block;
            width: 47%;
            font-family: 'ArialMt Bold';
            font-weight: bold;
            font-size: 16px;
            padding-left: 10px;
            border-bottom: 1px solid;
            line-height: 22px;
        }

        .total_summary .summary_value {
            display: inline-block;
            width: 49%;
            font-family: 'ArialMt Bold';
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            padding-right: 2px;
            border-bottom: 1px solid;
            line-height: 23px;
        }

        .total_summary .summary_label:nth-last-child(2),
        .total_summary .summary_value:last-child {
            border-bottom: 0px;
        }

        .amount_word {
            border-top: 1px solid;
        }

        .amount_word {
            font-family: 'ArialMt Bold';
            font-weight: bold;
            border-bottom: 1px solid;
            padding: 5px 10px;
        }

        .bank_info {
            border-bottom: 1px solid;
            padding: 5px 10px;
        }

        .bank_account_name {
            width: 100%;
        }

        .inv_details {
            font-size: 12px;
            width: 100%;
        }

        .inv_details td {
            border-right: 1px solid;
            padding: 5px;
        }

        .inv_details td:last-child {
            border-right: 0px solid;
            height: 100px;
        }

        .inv_details .terms,
        .inv_details .remarks {
            /*white-space: pre-wrap;*/
            vertical-align: top;
            width: 200px;
        }

        .inv_details .terms .label,
        .inv_details .remarks .label {
            font-family: 'ArialMt Bold';
            font-weight: bold;
        }

        .inv_details .customer_signature,
        .inv_details .author {
            vertical-align: bottom;
            text-align: center;
            width: 200px;
        }

        .footer {
            font-size: 11px;
            text-align: center;
            margin-top: 5px;
        }

        .main-footer {
            display: none;
        }
    </style>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
</head>

<body onload="window.print()">

    <!--<body>-->

    <?php
    $product_field = 0;
    $comp_id = $this->session->userdata('comp_id');
    $user_id = $this->session->userdata('user_id');
    $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
    $comp_profile = $this->user_model->getRow('comp_profile', $where);
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



    if ($comp_profile) {
        $jklmn = $comp_profile->total_invoice;
        for ($jkl = 0; $jkl < $jklmn; $jkl++) {
    ?>
            <div class="main_container">

                <?php

                if (!$jkl) {
                ?>
                    <div class="copy_title text-primary" style="margin-right:34px;">ORIGINAL</div>

                <?php
                } else {
                ?>
                    <div class="copy_title text-dark" style="margin-right:34px;">DUPLICATE</div>

                <?php
                }
                ?>
                <div class="container">
                    <table border="0" cellspacing="0" class="item_table" id="item_table_1">
                        <thead>

                            <tr>
                                <td style="padding:0px;" colspan="10">
                                    <div class="header_content">
                                        <?php
                                        $comp_id = $this->session->userdata('comp_id');
                                        $user_id = $this->session->userdata('user_id');
                                        $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
                                        $comp_profile = $this->user_model->getRow('comp_profile', $where);
                                        $log_postion = $comp_profile->logo_position;
                                        $logo_status = $comp_profile->logo_status;
                                        $comp_details_position = $comp_profile->comp_details_position;

                                        ?>
                                        <div class="header" style="text-align:<?= $log_postion ?>">
                                            <?php
                                            if ($logo_status == 'enable' && $log_postion) {
                                            ?>
                                                <img src="<?= base_url() ?>uploads/complogo/<?= $comp_profile->logo ?>" style="width: 400px;height: 200px;float:<?= $log_postion ?>;text-align:<?= $log_postion ?>;">
                                            <?php
                                            }
                                            ?>
                                            <div style="float:<?= $comp_details_position ?>;text-align:<?= $comp_details_position ?>">
                                                <?php
                                                if ($a5config->comp_name == 'Y') {
                                                ?>
                                                    <div class="name" style="font-size: 18px;">
                                                        <h1 style="color:black;"><b><?= $comp_profile->name ?></b></h1>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <?php
                                                if ($a5config->comp_address == 'Y') {
                                                ?>
                                                    <div class="address" style="font-size: 18px;"><?= $this->session->userdata('address') ?></div>
                                                <?php
                                                }
                                                ?>
                                                <?php
                                                if ($a5config->comp_mobile_number == 'Y') {
                                                ?>
                                                    <div class="contact_no" style="font-size: 18px;"><?= $this->session->userdata('numbers') ?></div>
                                                <?php
                                                }
                                                ?>
                                                <?php
                                                if ($a5config->comp_email == 'Y') {
                                                ?>
                                                    <div class="email" style="font-size: 18px;"><?= $this->session->userdata('email') ?></div>
                                                <?php
                                                }
                                                ?>
                                                <?php
                                                if ($a5config->comp_gst == 'Y') {
                                                ?>
                                                    <!--<div class="website" style="font-size: 18px;">www.umatic.in</div>-->
                                                    <div class="gst_no" style="font-size: 18px;"><b style="color:black;">GSTIN:<?= $this->session->userdata('gst_nos') ?></b></div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php if ($result->customerId != '' && $result->customerId != 0) {
                                            $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
                                        }
                                        ?>
                                        <table style="width: 100%; border-collapse: collapse;font-size: 16px;">
                                            <tr>
                                                <td colspan="2" style="text-align: center; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 10px;">
                                                    <h1 style="margin: 0px;"><?= $a5config->title ?></h1>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td style="width: 50%; padding: 10px;border-bottom: 1px solid #000;">
                                                    <h6><strong>Billing Address,</strong></h6>
                                                    <?php
                                                    if ($a5config->customer_name == 'Y') {
                                                    ?>
                                                        <h6 style="text-transform: uppercase; margin: 5px 0 5px 0;"><strong><?= $party->name ?></strong></h6>


                                                    <?php
                                                    }
                                                    if ($a5config->customer_address == 'Y') {
                                                    ?>
                                                    <p style="margin: 5px 0 5px 0;font-size:12px;">
                                                        <?= $result->customerAddress ?>
                                                        <br />
                                                        <?php $city = $this->db->get_where('districts', ['id' => $party->city])->row(); echo $city->name; ?>
                                                        <br />
                                                        <?php $state = $this->db->get_where('state_list', ['id' => $party->state])->row(); echo $state->state; ?>&nbsp;(<?= $party->pin; ?>)
                                                    </p>


                                                    <?php
                                                    }
                                                    if ($a5config->customer_mobile_number == 'Y') {
                                                    ?>
                                                        <p style="margin: 5px 0 5px 0;">Contact: <?= $result->customerMobile ?></p>

                                                    <?php
                                                    }
                                                    if ($a5config->customer_email == 'Y') {
                                                    ?>
                                                        <p style="margin: 5px 0 5px 0;">Email: <?= $result->customerEmail ?></p>


                                                    <?php
                                                    }


                                                    if ($a5config->customer_gst == 'Y') {
                                                    ?>
                                                        <p style="margin: 0 0 5px 0;"><strong>GSTN: <?= $result->gstNo ?></strong> </p>

                                                    <?php
                                                    }

                                                    ?>



                                                </td>
                                                <?php
                                                if ($a5config->shipping_address == 'Y') {

                                                ?>
                                                    <td style="width: 50%; padding: 10px; border-left: 1px solid #000; border-bottom: 1px solid #000;">

                                                        <h5 style="margin: 0 0 5px 0;"><strong>Shipping Address,</strong></h5>
                                                        <p style="text-transform: uppercase; margin: 0 0 5px 0;"><?= $result->shipping_address ?></p>

                                                        <!-- <h4 style="text-transform: uppercase; margin: 0 0 5px 0;">Brigade panorama apartment owners association</h4>
													<p style="text-transform: uppercase; margin: 0 0 5px 0;">43/34/453, Club house, ground floor, brigade
														panorama cluchouse</p>
													<p style="margin: 0 0 5px 0;">Contact: </p>
													<p style="margin: 0 0 5px 0;">Email: </p>
													<p style="margin: 0 0 5px 0;"><strong>GSTN: 29768A678</strong> </p> -->
                                                    </td>
                                                <?php
                                                } else {
                                                ?>
                                                    <td style="width: 50%; padding: 10px;">
                                                        <table style="width: 100%; border-collapse: collapse;">
                                                            <?php
                                                            if ($a5config->invoice_number == 'Y') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>Invoice No :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= $result->invoiceNumber ?></strong></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($a5config->invoice_date == 'Y') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>Invoice Date :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong></td>
                                                                </tr>

                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($a5config->state == 'Y') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>State of Supply :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= get_title('state_list', ['id' => $party->state], 'code'); ?>-<?= get_title('state_list', ['id' => $party->state], 'state'); ?></strong></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($a5config->payment_mode == 'Y') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>Payment Mode :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong>

                                                                            <?php
                                                                            if ($result->voucherType == 1) {
                                                                                echo "Cash";
                                                                            } elseif ($result->voucherType == 2) {
                                                                                echo "Credit";
                                                                            } else {
                                                                                echo $result->voucherType;
                                                                            }
                                                                            ?>
                                                                        </strong></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </table>
                                                    </td>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                            <tr>
                                                <?php
                                                $company_ka_data = $this->user_model->getRow('comp_profile', ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')]);
                                                $counter = 0;
                                                if ($company_ka_data->dcdate == 'yes') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->dcno == 'yes') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->lrnumber == 'yes') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->eway == 'yes') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->dpb == 'yes') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->ptd == 'yes') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->duedate == 'yes') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->lrdate == 'yes') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->vahical_no == 'yes') {
                                                    $counter + 1;
                                                }

                                                if ($company_ka_data->orderDate == 'Y') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->orderNumber == 'Y') {
                                                    $counter + 1;
                                                }
                                                if ($company_ka_data->lot_no == 'Y') {
                                                    $counter + 1;
                                                }


                                                if ($counter > 4) {

                                                ?>
                                                    <td style="width: 50%; border-right: 1px solid #000; padding: 10px;">
                                                        <table style="width: 100%; border-collapse: collapse;">


                                                            <?php
                                                            $dispatch_by = $this->session->userdata('dpb');
                                                            if ($dispatch_by == 'yes') {
                                                            ?>

                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>Dispatch By :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= ucwords($result->transport) ?></strong></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            $lr = $this->session->userdata('lrnumber');
                                                            if ($lr == 'yes') {
                                                            ?>

                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>LR No :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= $result->lrNumber ?></strong></td>
                                                                </tr>

                                                            <?php
                                                            }


                                                            $ewayBill = $this->session->userdata('eway');
                                                            if ($ewayBill == 'yes') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>Eway Bill :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= $result->ewayBill ?></strong></td>
                                                                </tr>

                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            $duedate = $this->session->userdata('duedate');
                                                            if ($duedate == 'yes') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>DueDate :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= $result->dueDate ?></strong></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>


                                                        </table>
                                                    </td>
                                                    <?php
                                                } else {
                                                    if ($a5config->shipping_address == 'Y') {

                                                    ?>
                                                        <td></td>
                                                    <?php
                                                    }
                                                }

                                                if ($a5config->shipping_address == 'Y') {


                                                    ?>
                                                    <td style="width: 50%; padding: 10px;">
                                                        <table style="width: 100%; border-collapse: collapse;">
                                                            <?php
                                                            if ($a5config->invoice_number == 'Y') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>Invoice No :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= $result->invoiceNumber ?></strong></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($a5config->invoice_date == 'Y') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>Invoice Date :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong></td>
                                                                </tr>

                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($a5config->state == 'Y') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>State of Supply :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong><?= get_title('state_list', ['id' => $party->state], 'code'); ?>-<?= get_title('state_list', ['id' => $party->state], 'state'); ?></strong></td>

                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($a5config->payment_mode == 'Y') {
                                                            ?>
                                                                <tr>
                                                                    <td style="width: 50%;border-right:none;"><strong>Payment Mode :</strong></td>
                                                                    <td style="width: 50%; text-align: right;"><strong>

                                                                            <?php
                                                                            if ($result->voucherType ==1) {
                                                                                echo "Cash";
                                                                            } elseif ($result->voucherType == 2) {
                                                                                echo "Credit";
                                                                            } else {
                                                                                echo $result->voucherType;
                                                                            }
                                                                            ?>
                                                                        </strong></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </table>
                                                    </td>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                </td>
                            </tr>

                            <tr>
                                <th class="item_no">SL</th>
                                <?php
                                if ($product_field == 10) {
                                    $width = 275;
                                } elseif ($product_field == 9) {
                                    $width = 300;
                                } elseif ($product_field == 8) {
                                    $width = 325;
                                } elseif ($product_field == 7) {
                                    $width = 340;
                                } elseif ($product_field == 6) {
                                    $width = 360;
                                } elseif ($product_field == 5) {
                                    $width = 390;
                                } elseif ($product_field == 4) {
                                    $width = 450;
                                } elseif ($product_field == 3) {
                                    $width = 500;
                                } elseif ($product_field == 2) {
                                    $width = 550;
                                } else {
                                    $width = 650;
                                }
                                ?>
                                <th class="item_name" style="white-space:nowrap;">DESCRIPTION OF GOODS/SERVICE</th>
                                <?php
                                if ($a5config->hsn == 'Y') {
                                    echo '<th class="item_hsn">HSN/SAC</th>';
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                if ($a5config->qty == 'Y') {
                                    echo '<th class="item_qty">QTY</th>';
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                if ($a5config->mrp == 'Y') {
                                    echo '<th class="item_mrp">MRP</th>';
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }


                                if ($a5config->sell_price == 'Y') {
                                    echo '<th class="item_rate">RATE</th>';
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                 if ($a5config->tax == 'Y') {
                                    echo '<th class="tax">Tax%</th>';
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                if ($a5config->taxable_amount == 'Y') {
                                    echo '<th class="taxable_amount">Taxble Amt</th>';
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                               
                                if ($a5config->taxamount == 'Y') {
                                    echo '<th class="tax_amount">Tax Amount</th>';
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                if ($a5config->net_amount == 'Y') {
                                    echo '<th class="netAmount">NetAmount</th>';
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                ?>
                                <!-- <th></th> -->




                            </tr>
                        </thead>
                        <tbody class="tbody">
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
                                    // $trheight=40;

                            ?>
                                    <tr class="text-center">
                                        <td><?= $key + 1 ?></td>
                                        <td class="text-left" style="white-space:nowrap;"><?php

                                                                $product_name = get_title('product', ['id' => $row->product_id], 'p_name');
                                                                if ($product_name) {
                                                                    echo $product_name;
                                                                } else {
                                                                    echo $product_name;
                                                                }

                                                                ?>
                                            <br>
                                            <span><?= $row->product_dec ?></span>

                                            <?php if ($row->serials) {

                                                foreach ($row->serials as $key => $value) {
                                                    if (sizeof($row->serials) - 1 > $key) {
                                                        echo $value->serial_no . ',';
                                                    } else {
                                                        echo $value->serial_no;
                                                    }
                                                }
                                            } ?>

                                        </td>

                                        <?php
                                        if ($a5config->hsn == 'Y') {
                                        ?>
                                            <td><?= $row->hsn ?></td>
                                        <?php
                                        } else {
                                            echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                        }
                                        ?>
                                        <?php
                                        if ($a5config->qty == 'Y') {
                                        ?>
                                            <td><?= $row->qty ?></td>
                                        <?php
                                        } else {
                                            echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                        }
                                        ?>
                                        <?php
                                        if ($a5config->mrp == 'Y') {
                                        ?>
                                            <td><?= get_title('product', ['id' => $row->product_id], 'mrp') ?></td>
                                        <?php
                                        } else {
                                            echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                        }

                                        if ($a5config->sell_price == 'Y') {
                                        ?>
                                            <td><?= money_formats($rate) ?></td>
                                        <?php

                                        } else {
                                            echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                        }
                                         if ($a5config->tax == 'Y') {
                                        ?>
                                            <td><?= $row->tax ?>%</td>
                                        <?php
                                        } else {
                                            echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                        }
                                        if ($a5config->taxable_amount == 'Y') {
                                        ?>
                                            <td>
                                                <?php
                                                echo $row->purchase_price;
                                                ?>
                                            </td>
                                        <?php
                                        } else {
                                            echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                        }
                                       
                                        if ($a5config->taxamount == 'Y') {
                                        ?>
                                            <td>
                                                <?= money_formats($row->amount) ?>
                                            </td>
                                        <?php
                                        } else {
                                            echo '<td style="border-right:0px;border-bottom:0px;"></td>';
                                        }
                                        if ($a5config->net_amount == 'Y') {
                                        ?>
                                            <td>
                                                <?= money_formats($row->netAmount); ?>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                        <!-- <td></td> -->
                                    </tr>

                                <?php
                                    $counts = $key;
                                }
                            }
                            if (isset($counts) && !empty($counts)) {
                                $lines = $lines - $counts;
                                $lines = $lines * 1;
                            } else {
                                $lines = $lines - $counts;
                            }


                            if ($lines > 0) {
                                for ($i = 0; $i < $lines; $i++) { ?>
                                    <tr>

                                        <td>&nbsp;</td>
                                        <td>&nbsp;
                                            <p></p>
                                        </td>

                                        <?php
                                        if ($a5config->hsn == 'Y') {
                                        ?>
                                            <td>&nbsp;</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="border:none;"></td>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if ($a5config->qty == 'Y') {
                                        ?>
                                            <td>&nbsp;</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="border:none;"></td>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if ($a5config->mrp == 'Y') {
                                        ?>
                                            <td>&nbsp;</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="border:none;"></td>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if ($a5config->sell_price == 'Y') {
                                        ?>
                                            <td>&nbsp;</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="border:none;"></td>
                                        <?php
                                        }


                                        if ($a5config->taxamount == 'Y') {
                                        ?>
                                            <td>&nbsp;</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="border:none;"></td>
                                        <?php
                                        }
                                        if ($a5config->tax == 'Y') {
                                        ?>
                                            <td>&nbsp;</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="border:none;"></td>
                                        <?php
                                        }
                                        if ($a5config->taxable_amount == 'Y') {
                                        ?>
                                            <td>&nbsp;</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="border:none;"></td>
                                        <?php
                                        }
                                        if ($a5config->net_amount == 'Y') {
                                        ?>
                                            <td>&nbsp;</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="border:none;"></td>
                                        <?php
                                        }
                                        ?>
                                        <!-- <td>&nbsp;</td> -->




                                    </tr>
                            <?php }
                            } ?>
                            <tr style="text-align: center;">
                                <!-- <th></th> -->
                                <th></th>
                                <th></th>
                                <?php
                                if ($a5config->hsn == 'Y') {
                                ?>
                                    <th></th>
                                <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }

                                if ($a5config->qty == 'Y') {
                                ?>
                                    <th class="item_qty"><?= $nosItm ?></th>
                                <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                ?>
                                <?php
                                if ($a5config->mrp == 'Y') {
                                ?>
                                    <th></th>
                                <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }

                                if ($a5config->sell_price == 'Y') {
                                ?>
                                    <th></th>
                                <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }

                                if ($a5config->taxable_amount == 'Y') {
                                ?>


                                    <th><?= money_formats($result->taxableAmount) ?></th>
                                <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                if ($a5config->tax == 'Y') {
                                ?>
                                    <th></th>
                                <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                ?>
                                <?php
                                if ($a5config->taxamount == 'Y') {
                                ?>
                                    <th>
                                        <?= money_formats($result->totalTax) ?>
                                    </th>
                                <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                if ($a5config->net_amount == 'Y') {
                                ?>

                                    <th><?= money_formats($result->total) ?></th>
                                <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                                ?>
                                <!-- <th></th> -->
                            </tr>
                        </tbody>
                    </table>
                    <table border="0" width="100%">
                        <tbody>
                            <tr>
                                <td class="summary_table_div">
                                    <table class="tax_summary" border="0" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="tax_slab">SUMMARY</th>
                                                <th class="tax_summary_details">Taxable</th>
                                                <th class="tax_summary_details">CGST</th>
                                                <th class="tax_summary_details">SGST</th>
                                                <th class="tax_summary_details">IGST</th>

                                            </tr>
                                        </thead>
                                        <tbody>
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
                                                    <tr style="font-size:20px;">
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
                                            if ($tcount > 0) {
                                                for ($i = 0; $i < $tcount; $i++) { ?>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <!-- <td>&nbsp;</td> -->
                                                    </tr>
                                            <?php   }
                                            } ?>

                                        </tbody>
                                    </table>
                                    <span style="margin-right: 1em;"><span>No of ltems : </span><?= $counts + 1 ?> </span>
                                    <span><span>Total Qty : </span><?= $nosItm; ?></span>
                                    <br>
                                </td>
                                <td class="total_summary">
                                    <?php
                                    if ($a5config->total_taxable_amount == 'Y') {
                                    ?>
                                        <div class="summary_label">TAXABLE</div>
                                        <div class="summary_value"><i class="fa fa-inr"></i><?= money_formats($result->taxableAmount) ?></div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($a5config->total_tax_amount == 'Y') {
                                    ?>
                                        <div class="summary_label">TAX</div>
                                        <div class="summary_value"><i class="fa fa-inr"></i><?= money_formats($result->totalTax) ?></div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($a5config->round_off == 'Y') {
                                    ?>
                                        <div class="summary_label">ROUND OFF</div>
                                        <div class="summary_value"><i class="fa fa-inr"></i><?= money_formats($result->roundOff) ?></div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($a5config->grand_total == 'Y') {
                                    ?>
                                        <div class="summary_label">GRAND TOTAL</div>
                                        <div class="summary_value"><i class="fa fa-inr"></i> <?= money_formats($result->total) ?></div>
                                    <?php
                                    }
                                    ?>
                                </td>

                            </tr>

                        </tbody>
                    </table>
                    <div class="amount_word"><?= ucwords($result->amountInWords) ?>/-</div>
                    <div class="bank_info">
                        <?php
                        if ($a5config->bank_info == 'Y') {
                        ?>
                            <div style="font-weight:bold;font-family:'ArialMt Bold'">Bank Account Information :</div>

                            <?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
                            if (isset($bank) && !empty($bank)) {
                            ?>
                                <div class="bank_account_name"><?= $bank->bank_details; ?> </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <?php

                    if ($a5config->remark == 'Y') {
                    ?>

                        <div class="remarks" style="border-bottom: 1px solid;">

                            <div style="font-weight:bold;font-family:'ArialMt Bold'">Remarks :</div>

                            <div class="bank_account_name"><?= $result->remark; ?> </div>

                        </div>
                    <?php
                    }
                    ?>
                    <table class="inv_details" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td class="terms">
                                    <div class="label" style="font-size: 20px;">Terms and Conditions</div>
                                    <?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
                                    if (isset($bank) && !empty($bank)) {
                                    ?>
                                        <span style="font-size:14px;"><?= $bank->content; ?></span>
                                    <?php
                                    }
                                    ?>

                                </td>
                                <?php
                                if ($comp_profile->signature_display == 'enable') {
                                ?>
                                    <td class="customer_signature" style="font-size:20px;">
                                        <div class="label"><b>For:<?= $comp_profile->name ?></b></div>
                                        <?php
                                        if ($bank->scan_signature) {
                                        ?>
                                            <img src="<?= base_url() ?>uploads/signature/<?= $bank->scan_signature ?>" style=" width: 260px; height: 137px;">
                                        <?php
                                        }
                                        ?>
                                        <br>
                                        Authorised Signature
                                    </td>
                                <?php
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php
                if ($a5config->footer == 'Y') {
                ?>
                    <div class=" footer" style="font-size:20px;">This is a Computer Generated Copy Generated by Uday Plastic
                    </div>
                <?php
                }
                ?>
            </div>
    <?php
        }
    }
    ?>



    <script>
        $(document).ready(function() {
            for (z = 1; z <= 2; z++) {
                var height = document.getElementById('item_table_' + z).offsetHeight;
                if (height < 600) {
                    var tableis = document.getElementById('tbody_' + z);
                    var last = tableis.rows.length - 1;
                    var Tr = tableis.insertRow(last);
                    var size = 600 - height;
                    Tr.style.height = size + "px";
                    var i;
                    for (i = 0; i < 8; i++) {
                        var Td1 = Tr.insertCell(i);
                        Td1.innerHTML = "";
                        Tr.appendChild(Td1);
                    }
                }
            }
            window.print();
        });
        jQuery(document).bind("keyup keydown", function(e) {
            if (e.ctrlKey && e.keyCode == 80) {
                window.print();
                return false;
            }
        });
    </script>
</body>


</html>