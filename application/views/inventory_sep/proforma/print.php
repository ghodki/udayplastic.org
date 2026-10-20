<html>
        <head>
                <title>Uday Plastic - Invoice</title>
                <meta charset="utf-8" />
               <meta name="viewport" content="width=device-width, initial-scale=1" />
               <meta http-equiv="x-ua-compatible" content="ie=edge" />
                <link type="image/png" rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.png" />
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
                <style type="text/css">
                    body {
                        font-family: sans-serif;
                        margin: 0px;
                        font-size: 12px;
                    }
                    .main_container {
                        page-break-after: always;
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
                    .address {
                        white-space: pre-wrap;
                        font-size: 12px;
                    }
                    .gstin, .pan, .cin {
                        font-size: 15px;
                        font-weight: bold;
                        display: block;
                    }
                    .invoice_title {
                        text-align: center;
                        padding: 3px;
                        font-size: 15px;
                        font-weight: bold;
                        border-top: 1px solid;
                        border-bottom: 1px solid;
                    }
                    .invoice_details {
                        font-size: 13px;
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
                        font-weight: bold;
                    }
                    .invoice_details .gstin, .invoice_details .pan, .invoice_details .cin {
                        font-size: 16px;
                        font-weight: bold;
                        display: block;
                    }
                    .gstin:before {
                        content: 'GSTIN : ';
                    }
                    .pan:before {
                        content: 'PAN : ';
                    }
                    .contact_no:before, .mobile:before {
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
                        font-weight: bold;
                        width: 150px;
                    }
                    .inv_rows th:after {
                        content: ' : ';
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
                        font-size: 13px;
                    }
                    .item_table th {
                        border-top: 1px solid;
                        border-bottom: 1px solid;
                        font-weight: bold;
                    }
                    .item_table th:last-child, .item_table td:last-child {
                        border-right: 0px;
                    }
                    .item_table th, .item_table td {
                        border-right: 1px solid;
                        page-break-inside: avoid;
                        padding: 0px 2px;
                    }
                    .item_table td {
                        vertical-align: top;
                    }
                    .item_table .item_no {
                        text-align: left !important;
                        vertical-align: top;
                    }
                    .item_table .item_code {
                        text-align: left !important;
                    }
                    .item_table .item_hsn {
                        text-align: left !important;
                    }
                    .item_table .item_desc {
                        font-size: 18px;
                        white-space: pre-wrap;
                    }
                    .item_table .descc {
                        font-size: 10px;
                        white-space: pre-wrap;
                    }
                    .item_table .item_unit {
                        text-align: center;
                    }
                    .item_table .tax {
                        text-align: right;
                    }
                    .item_table .item_mrp {
                        text-align: right;
                    }
                    .item_table .item_discount {
                        text-align: right;
                    }
                    .item_table .item_tax_amount {
                        text-align: right;
                    }
                    .item_table .item_tax_perc {
                        text-align: center;
                    }
                    .item_table .item_tax_perc_only {
                        text-align: center;
                    }
                    .item_table .item_tax_type {
                        text-align: center;
                    }
                    .item_table .item_taxable {
                        text-align: right;
                    }
                    .item_table .item_total {
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
                        width: 60%;
                    }
                    .tax_summary {
                        width: 100%;
                    }
                    .tax_summary th {
                        font-weight: bold;
                        font-size: 13px;
                    }
                    .tax_summary_details {
                        text-align: right;
                    }
                    .tax_summary td {
                        font-size: 13px;
                        text-align: right;
                    }
                    .tax_slab {
                        text-align: left;
                    }
                    .tax_summary .slap_perc {
                        font-size: 14px;
                        padding-left: 30px;
                        text-align: left;
                        float: left;
                    }
                    .total_summary {
                        width: 40%;
                        vertical-align: top;
                    }
                    .total_summary .summary_label {
                        display: inline-block;
                        width: 100%;
                        font-weight: bold;
                        font-size: 13px;
                        padding-left: 0px;
                        border-bottom: 1px solid;
                        line-height: 22px;
                    }
                    .total_summary .summary_value {
                        display: inline-block;
                        width: 46%;
                        font-weight: bold;
                        font-size: 18px;
                        text-align: right;
                        padding-right: 2px;
                        border-bottom: 1px solid;
                        line-height: 23px;
                    }
                    .total_summary .summary_label:nth-last-child(2), .total_summary .summary_value:last-child {
                        border-bottom: 0px;
                    }
                    .amount_word {
                        border-top: 1px solid;
                    }
                    .amount_word {
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
                        vertical-align: top;
                        width: 68%;
                    }
                    .inv_details .terms .label,
                    .inv_details .remarks .label {
                        font-weight: bold;
                    }
                    .inv_details .customer_signature, .inv_details .author {
                        vertical-align: bottom;
                        text-align: center;
                        width: 20%;
                    }
                    .inv_details .receiver_signature, .inv_details .author {
                        vertical-align: bottom;
                        text-align: center;
                        width: 12%;
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
        <body>
                <?php
                        $product_field = 0;
                        $comp_id = $this->session->userdata('comp_id');
                        $user_id = $this->session->userdata('user_id');
                        $compBranchId = $this->session->userdata("CompBranchId");


                        $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
                        if ($this->session->userdata("BranchRole")) {
                                $where['CompanyBranchId'] = $compBranchId;
                        }
                        $comp_profile = $this->user_model->getRow('comp_profile', $where);

                        $counter = 0;
                        if ($comp_profile->dcdate == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->dcno == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->lrnumber == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->eway == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->dpb == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->ptd == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->duedate == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->lrdate == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->vahical_no == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->orderDate == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->orderNumber == 'yes') {
                            $counter = $counter + 1;
                        }
                        if ($comp_profile->lot_no == 'Y') {
                            $counter = $counter + 1;
                        }

                        $a5config = $this->user_model->getRow('proforma_config', $where);
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

                        if ($comp_profile) {
                            $jklmn = $comp_profile->total_invoice;
                            for ($jkl = 0; $jkl < $jklmn; $jkl++) {
                ?>
                <div class="main_container">
                        <?php if ($jkl == 0) { ?>
                        <div class="copy_title text-primary" style="margin-right:34px;">ORIGINAL</div>
                        <?php } elseif ($jkl == 1) { ?>
                        <div class="copy_title text-dark" style="margin-right:34px;">DUPLICATE</div>
                        <?php } else { ?>
                        <div class="copy_title text-dark" style="margin-right:34px;">TRIPLICATE</div>
                        <?php } ?>
                        <div class="container">
                        <table cellspacing="0" class="item_table" id="item_table_1">
                                <thead>
                                        <tr>
                                                <td style="padding:0px;" colspan="12">
                                                        <div class="header_content">
                                                                <?php
                                                                        $log_postion = $comp_profile->logo_position;
                                                                        $logo_status = $comp_profile->logo_status;
                                                                        $comp_details_position = $comp_profile->comp_details_position;
                                                                        if ($this->session->userdata('BranchRole')) {
                                                                                $where = ['Id' => $compBranchId, 'CompId' => $comp_id, 'BranchUserId' => $user_id];
                                                                                $compBranch = $this->user_model->getRow('companybranch', $where);
                                                                        } else {                                            
                                                                                $inventory_billing = $this->user_model->getRow('inventory_billing', ['id' => $comp_id, 'user_id' => $user_id]);
                                                                        }
                                                                    ?>
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <?php 
                                                    if ($logo_status == 'enable' && $log_postion) { 
                                                        $logourl = 'uploads/complogo/' . $comp_profile->logo; 
                                                        $ext = pathinfo($logourl, PATHINFO_EXTENSION);
                                                        $logoimg = file_get_contents($logourl);
                                                        $logo = 'data:image/' . $ext . ';base64,' . base64_encode($logoimg);
                                                        if ($log_postion == 'left') {
                                                ?>
                                                <td style="text-align:<?= $log_postion; ?>; border-right: none;">
                                                    <img src="<?= $logo; ?>" style="width: <?= $comp_profile->logo_width; ?>px;height: <?= $comp_profile->logo_height; ?>px;float:<?= $log_postion; ?>;text-align:<?= $log_postion; ?>; padding: 2px;" />      
                                                </td>
                                                <?php } } ?> 
                                                        <td>
                                                                <div style="text-align:<?= $comp_details_position; ?>;margin-top:-13px; width: 100%;">
                                                                        <?php if ($a5config->comp_name == 'Y') { ?>
                                                                        <div class="name" style="font-size:16px;margin-bottom: -20px;">
                                                                                <h2 style="margin-top: 23px;"><strong><?= $comp_profile->name; ?></strong></h2>
                                                                        </div>
                                                                        <?php } if ($a5config->comp_address == 'Y') { ?>
                                                                        <div class="address" style="font-size: 16px;"><?= $this->session->userdata('BranchRole') ? $compBranch->Address : $inventory_billing->address; ?></div>
                                                                        <?php } if ($a5config->comp_mobile_number == 'Y') { ?>
                                                                        <div class="contact_no" style="font-size: 16px;"><?= $this->session->userdata('BranchRole') ? $compBranch->Mobile : $inventory_billing->number ; ?></div>
                                                                        <?php } if ($a5config->comp_email == 'Y') { ?>
                                                                        <div class="email" style="font-size: 16px;"><?= $this->session->userdata('BranchRole') ? $compBranch->Email : $inventory_billing->email; ?></div>
                                                                        <?php } if ($a5config->comp_gst == 'Y') { ?>
                                                                        <div class="gst_no" style="font-size: 16px;color:red;">
                                                                            <b style="color:black;">GSTIN: <?= $this->session->userdata('BranchRole') ? $compBranch->GST : $inventory_billing->gst_no; ?></b>
                                                                        </div>
                                                                        <?php } ?>
                                                                </div>
                                                        </td>
                                                <?php 
                                                    if ($logo_status == 'enable' && $log_postion) { 
                                                        $logourl = 'uploads/complogo/' . $comp_profile->logo; 
                                                        $ext = pathinfo($logourl, PATHINFO_EXTENSION);
                                                        $logoimg = file_get_contents($logourl);
                                                        $logo = 'data:image/' . $ext . ';base64,' . base64_encode($logoimg);
                                                        if ($log_postion == 'right') {
                                                ?>
                                                <td style="text-align:<?= $log_postion; ?>; border-right: none;">
                                                    <img src="<?= $logo; ?>" style="width: <?= $comp_profile->logo_width; ?>px;height: <?= $comp_profile->logo_height; ?>px;float:<?= $log_postion; ?>;text-align:<?= $log_postion; ?>; padding: 2px;" />      
                                                </td>
                                                <?php } } ?> 
                                            </tr>
                                        </table>

                                                <?php 
                                                        if ($result->customerId != '' && $result->customerId != 0) {
                                                                $party = $this->db->get_where('parties', ['id' => $result->customerId])->row(); 
                                                        }
                                                ?>
                                        <table style="width: 100%; border-collapse: collapse;font-size: 16px;">
                                            <?php if ($a5config->title) { ?>
                                            <tr>
                                                <td colspan="2" style="text-align: center; border-bottom: 1px solid #000; border-top: 1px solid #000;">
                                                    <h3 style="margin: 0px;"><?= $a5config->title; ?></h3>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td style="width: 50%; padding: 10px;border-bottom: 1px solid #000;line-height:15px;font-size: 12px;">
                                                    <h6 style="margin-bottom: auto; margin-top: 0px;"><strong>Billing Address,</strong></h6>
                                                    <?php if ($a5config->customer_name == 'Y') { ?>
                                                    <h6 style="text-transform: uppercase; margin: 5px 0 5px 0;;font-size:15px;">
                                                        <strong><?= $party->name; ?></strong>
                                                    </h6>
                                                    <?php } if ($a5config->customer_address == 'Y') { ?>
                                                    <p style="margin: 5px 0 5px 0;font-size:12px;">
                                                        <?= $party->address_1; ?>
                                                        <br />
                                                        <?php $city = $this->db->get_where('districts', ['id' => $party->city])->row(); echo $city->name; ?>
                                                        <br />
                                                        <?php $state = $this->db->get_where('state_list', ['id' => $party->state])->row(); echo $state->state; ?>&nbsp;(<?= $party->pin; ?>)
                                                    </p>
                                                    <?php } if ($a5config->customer_mobile_number == 'Y') { ?>
                                                    <p style="margin: 5px 0 5px 0;">Contact: <?= $party->contact; ?></p>
                                                    <?php } if ($a5config->customer_email == 'Y') { ?>
                                                    <p style="margin: 5px 0 5px 0;">Email: <?= $party->email; ?></p>
                                                    <?php } if ($a5config->customer_gst == 'Y') { ?>
                                                    <p style="margin: 0 0 5px 0;color:red;"><strong>GSTIN: <?= $party->gst; ?></strong> </p>
                                                    <?php } ?>
                                                </td>
                                               <?php if ($a5config->shipping_address == 'Y') { ?>
                                               <td style="width: 50%; padding: 10px; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                                                       <h5 style="margin: 0 0 5px 0;">
                                                               <strong>Shipping Address,</strong>
                                                       </h5>
                                                       <?php $shippingInfo = json_decode($result->shippingInfo); ?>
                                                       <p style="text-transform: uppercase; margin: 0 0 5px 0;">
                                                               <strong><?= $shippingInfo->shippingName; ?></strong>
                                                       </p>
                                                       <p style="text-transform: uppercase; margin: 0 0 5px 0;">
                                                               <?= $shippingInfo->shippingAddress; ?>, 
                                                               <br />
                                                               <?= $shippingInfo->shippingCity; ?> (<?= $shippingInfo->shippingZip; ?>), <?= get_title('state_list', ['id' => $shippingInfo->shippingState], 'state'); ?>
                                                       </p>                                                      
                                                       <p style="text-transform: uppercase; margin: 0 0 5px 0;">Email: <?= $shippingInfo->shippingEmail; ?></p>
                                                       <p style="text-transform: uppercase; margin: 0 0 5px 0;">Mob.: <?= $shippingInfo->shippingMobile; ?></p>
                                                       <p style="text-transform: uppercase; margin: 0 0 5px 0;">GST: <?= $shippingInfo->shippingGST; ?></p>
                                                </td>
                                                <?php } else { ?>
                                                <td style="width: 50%; padding: 10px;">
                                                    <table style="width: 100%; border-collapse: collapse;font-size: 13px;">
                                                        <?php if ($a5config->invoice_number == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Invoice No :</strong></td>
                                                            <td style="width: 50%; text-align: right;color:red;"><strong><?= $result->invoiceNumber ?></strong></td>
                                                        </tr>
                                                        <?php } if ($a5config->invoice_date == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Invoice Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->due_date == 'Yes') { if ($result->voucherType != 1) { ?>
                                                        <tr>
                                                            <td style="width: 50%; border-right: none;"><strong>Due Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;">
                                                                <strong>
                                                                    <?php
                                                                        if (!empty($comp_profile->payment_due_period)) {
                                                                            echo date('d/m/Y', strtotime($result->invoiceDate . '+ ' . $comp_profile->payment_due_period . 'days')); 
                                                                        }
                                                                    ?>
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php } } if ($a5config->state == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>State of Supply :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= get_title('state_list', ['id' => $party->state], 'code'); ?>-<?= get_title('state_list', ['id' => $party->state], 'state'); ?></strong></td>
                                                        </tr>
                                                        <?php } if ($a5config->payment_mode == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Payment Mode :</strong></td>
                                                            <td style="width: 50%; text-align: right;">
                                                                <strong>
                                                                    <?php
                                                                        if ($result->voucherType == 1) {
                                                                            echo "Cash";
                                                                        } elseif ($result->voucherType == 2) {
                                                                            echo "Credit";
                                                                        } else {
                                                                            echo $result->voucherType;
                                                                        }
                                                                    ?>
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php } if ($counter > 4 || $a5config->shipping_address == 'N') { ?>
                                                        <?php $dispatch_by = $this->session->userdata('dpb'); if ($dispatch_by == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Dispatch By :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= ucwords($result->transport); ?></strong></td>
                                                        </tr>
                                                        <?php } $lr = $this->session->userdata('lrnumber'); if ($lr == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>LR No :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->lrNumber; ?></strong></td>
                                                        </tr>
                                                        <?php } $ewayBill = $this->session->userdata('eway'); if ($ewayBill == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Eway Bill :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->ewayBill; ?></strong></td>
                                                        </tr>
                                                        <?php } $duedate = $this->session->userdata('duedate'); if ($duedate == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Due Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->dueDate; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->ptd == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Receipt Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->recieptDate; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->lrdate == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>LR Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->lrDate ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->vahical_no == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Vehicle No :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->vahical_no ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->orderDate == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Order Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->orderDate; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->orderNumber == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Order Number :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->orderNumber; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->dcdate == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>DC Date. :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->dcDate ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->dcno == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>DC No. :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->dcNumber ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->lot_no == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Lot No :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->lot_no; ?></strong></td>
                                                        </tr>
                                                        <?php } } ?>
                                                    </table>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <?php if ($a5config->shipping_address == 'Y') { ?>
                                                <td style="width: 50%; padding: 10px;">
                                                    <table style="width: 100%; border-collapse: collapse;font-size: 12px;">
                                                        <?php $dispatch_by = $this->session->userdata('dpb'); if ($dispatch_by == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Dispatch By :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= ucwords($result->transport); ?></strong></td>
                                                        </tr>
                                                        <?php } $lr = $this->session->userdata('lrnumber'); if ($lr == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>LR No :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->lrNumber; ?></strong></td>
                                                        </tr>
                                                        <?php } $ewayBill = $this->session->userdata('eway'); if ($ewayBill == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Eway Bill :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->ewayBill; ?></strong></td>
                                                        </tr>
                                                        <?php } $duedate = $this->session->userdata('duedate'); if ($duedate == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Due Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->dueDate; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->ptd == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Receipt Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->recieptDate; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->lrdate == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>LR Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->lrDate; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->vahical_no == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Vehicle No :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->vahical_no; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->orderDate == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Order Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->orderDate; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->orderNumber == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Order Number :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->orderNumber; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->dcdate == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>DC Date. :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->dcDate ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->dcno == 'yes') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>DC No. :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->dcNumber ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->lot_no == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Lot No :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= $result->lot_no ?></strong></td>
                                                        </tr>
                                                        <?php } ?>
                                                    </table>
                                                </td>
                                                <?php } else { if ($a5config->shipping_address == 'Y') { ?>
                                                <td></td>
                                                <?php } } if ($a5config->shipping_address == 'Y') { ?>
                                                <td style="width: 50%; padding: 10px;">
                                                    <table style="width: 100%; border-collapse: collapse;font-size: 12px;">
                                                        <?php if ($a5config->invoice_number == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Invoice No :</strong></td>
                                                            <td style="width: 50%; text-align: right;color:red;"><strong><?= $result->invoiceNumber; ?></strong></td>
                                                        </tr>
                                                        <?php } if ($a5config->invoice_date == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Invoice Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong></td>
                                                        </tr>
                                                        <?php } if ($comp_profile->due_date == 'Yes') { if ($result->voucherType != 1) { ?>
                                                        <tr>
                                                            <td style="width: 50%; border-right: none;"><strong>Due Date :</strong></td>
                                                            <td style="width: 50%; text-align: right;">
                                                                <strong>
                                                                    <?php
                                                                        if (!empty($comp_profile->payment_due_period)) {
                                                                            echo date('d/m/Y', strtotime($result->invoiceDate . '+ ' . $comp_profile->payment_due_period . 'days')); 
                                                                        }
                                                                    ?>
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php } } if ($a5config->state == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>State of Supply :</strong></td>
                                                            <td style="width: 50%; text-align: right;"><strong><?= get_title('state_list', ['id' => $party->state], 'code'); ?>-<?= get_title('state_list', ['id' => $party->state], 'state'); ?></strong></td>
                                                        </tr>
                                                        <?php } if ($a5config->payment_mode == 'Y') { ?>
                                                        <tr>
                                                            <td style="width: 50%;border-right:none;"><strong>Payment Mode :</strong></td>
                                                            <td style="width: 50%; text-align: right;">
                                                                <strong>
                                                                    <?php
                                                                        if ($result->voucherType == 1) {
                                                                            echo "Cash";
                                                                        } elseif ($result->voucherType == 2) {
                                                                            echo "Credit";
                                                                        } else {
                                                                            echo $result->voucherType;
                                                                        }
                                                                    ?>
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                    </table>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                        </table>
                                    </div>
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
                                        $width = 200;
                                    }
                                ?>
                                <th class="item_name" style="white-space:nowrap;">DESCRIPTION OF GOODS/SERVICE</th>
                                <?php
                                    if ($a5config->hsn == 'Y') {
                                        echo '<th class="item_hsn">HSN/SAC</th>';
                                    }
                                    if ($a5config->qty == 'Y') {
                                        echo '<th style="class="item_qty">QTY</th>';
                                    }
                                    if ($a5config->sqrfoot == 'Y') {
                                        echo '<th class="item_qty">Unit Price</th>';
                                    }
                                    if ($a5config->discount_invoice == 'Y') {
                                        echo '<th class="discount">Discount</th>';
                                    }
                                    if ($a5config->unit == 'Y') {
                                        echo '<th class="unit">UNIT</th>';
                                    }
                                    if ($a5config->mrp == 'Y') {
                                        echo '<th class="item_mrp">MRP</th>';
                                    }
                                    if ($a5config->sell_price == 'Y') {
                                        echo '<th class="item_rate">RATE</th>';
                                    }
                                    if ($a5config->tax == 'Y') {
                                        echo '<th class="tax">Tax%</th>';
                                    }
                                    if ($a5config->taxable_amount == 'Y') {
                                        echo '<th class="taxable_amount">Taxble Amt</th>';
                                    }
                                    if ($a5config->taxamount == 'Y') {
                                        echo '<th class="tax_amount">Tax Amount</th>';
                                    }
                                    if ($a5config->net_amount == 'Y') {
                                        echo '<th class="netAmount">Net Amount</th>';
                                    }
                                ?>
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
                                        $totalDiscount += get_title('product', ['id' => $row->product_id], 'mrp') * $row->qty;
                            ?>
                            <tr class="text-center">
                                <td><?= $key + 1; ?></td>
                                <td class="text-left" style="white-space:nowrap;">
                                    <?php
                                        $product_name = get_title('product', ['id' => $row->product_id], 'p_name');
                                        if ($product_name) {
                                            echo $product_name;
                                        } else {
                                            echo $row->productname;
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
                                <td><?= $row->qty ?></td>
                                <?php } if ($a5config->sqrfoot == 'Y') { ?>
                                <td><?= $row->sqft; ?></td>
                                <?php } if ($a5config->discount_invoice == 'Y') { ?>
                                <td><?= $row->discount; ?>%</td>
                                <?php } if ($a5config->unit == 'Y') { ?>
                                <td><?= get_title('product', ['id' => $row->product_id], 'unit_name'); ?></td>
                                <?php }  if ($a5config->mrp == 'Y') { ?>
                                <td><?= get_title('product', ['id' => $row->product_id], 'mrp'); ?></td>
                                <?php } if ($a5config->sell_price == 'Y') { ?>
                                <td><?= number_format($row->purchase_price / $row->qty, 3); ?></td>
                                <?php } if ($a5config->tax == 'Y') { ?>
                                <td><?= $row->tax; ?>%</td>
                                <?php } if ($a5config->taxable_amount == 'Y') { ?>
                                <td><?= $row->purchase_price; ?></td>
                                <?php } if ($a5config->taxamount == 'Y') { ?>
                                <td><?= number_format($row->amount, 3); ?></td>
                                <?php } if ($a5config->net_amount == 'Y') { ?>
                                <td style="text-align:right;"><?= number_format($row->netAmount, 2); ?></td>
                                <?php } ?>
                            </tr>
                            <?php $counts = $key; } } ?>
                            <?php
                                if (isset($counts) && !empty($counts)) {
                                    $lines = $lines - $counts;
                                    $lines = $lines * 1;
                                } else {
                                    $lines = $lines - $counts;
                                }
                                if (isset($counts) && !empty($counts)) {
                                    $lines = $lines - $counts;
                                } else {
                                    $lines = $lines - $counts;
                                }
                                if ($lines > 0) {
                                    for ($i = 0; $i < $lines; $i++) { 
                            ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <?php if ($a5config->hsn == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->qty == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->sqrfoot == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->discount_invoice == 'Y') {  ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->unit == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->mrp == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->sell_price == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->tax == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->taxable_amount == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->taxamount == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->net_amount == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } ?>
                            </tr>
                            <?php } } ?>
                            <tr id="page-main">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <?php if ($a5config->hsn == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->qty == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->sqrfoot == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->discount_invoice == 'Y') {  ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->unit == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->mrp == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->sell_price == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->tax == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->taxable_amount == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->taxamount == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } if ($a5config->net_amount == 'Y') { ?>
                                <td>&nbsp;</td>
                                <?php } ?>
                            </tr>
                            <tr style="text-align: center;">
                                <th></th>
                                <th></th>
                                <?php if ($a5config->hsn == 'Y') { ?>
                                <th></th>
                                <?php } if ($a5config->qty == 'Y') { ?>
                                <th class="item_qty" style="text-align: left;"><?= $nosItm; ?></th>
                                <?php } if ($a5config->sqrfoot == 'Y') { ?>
                                <th class="item_qty"></th>
                                <?php } if ($a5config->discount_invoice == 'Y') { ?>
                                <th class="discount"></th>
                                <?php } if ($a5config->unit == 'Y') { ?>
                                <th class="unit"></th>
                                <?php } if ($a5config->mrp == 'Y') { ?>
                                <th></th>
                                <?php } if ($a5config->sell_price == 'Y') { ?>
                                <th></th>
                                <?php } if ($a5config->tax == 'Y') { ?>
                                <th></th>
                                <?php } if ($a5config->taxable_amount == 'Y') { ?>
                                <th style="text-align: left;"><?= number_format($result->taxableAmount, 3); ?></th>
                                <?php } if ($a5config->taxamount == 'Y') { ?>
                                <th style="text-align: left;"><?= number_format($result->totalTax, 3); ?></th>
                                <?php } if ($a5config->net_amount == 'Y') { ?>
                                <th style="text-align: right;"><?= number_format($result->totalValue, 3); ?></th>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                    <table class="item_table">
                        <tbody>
                            <tr>
                                <?php if ($a5config->total_summary == 'Y') { ?>
                                <td style="padding: 0px;">
                                    <table class="item_table" cellspacing="2">
                                        <thead>
                                            <tr>
                                                <th style="border-top: none;">SUMMARY</th>
                                                <th style="border-top: none;">Taxable</th>
                                                <th style="border-top: none;">CGST</th>
                                                <th style="border-top: none;">SGST</th>
                                                <th style="border-top: none;">IGST</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $this->db->select('tax');
                                                $this->db->select_sum('purchase_price');
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
                                                $tcount = 2;
                                                if (isset($tax) && !empty($tax)) {
                                                    foreach ($tax as $row) { 
                                            ?>
                                            <tr style="font-size:14px;">
                                                <td class="tax_slab" style="text-align:left;"><b>GST @<?= $row->tax; ?>% </b></td>
                                                <td><?= number_format($row->purchase_price, 3); ?></td>
                                                <td><?= number_format($row->sgst, 3); ?></td>
                                                <td><?= number_format($row->cgst, 3); ?></td>
                                                <td><?= number_format($row->igst, 3); ?></td>
                                            </tr>
                                            <?php $tcount--; } } ?>
                                            <?php 
                                                if ($tcount > 0) {
                                                    for ($i = 0; $i < $tcount; $i++) { 
                                            ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <?php } } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr style="height: 34px;">
                                                <td colspan="1" style="border-top: 0.5px solid;">
                                                    No of Items : <?= $counts + 1; ?>
                                                </td>
                                                <td colspan="4" style="border-top: 0.5px solid;">
                                                    Total Qty : <?= $nosItm; ?>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                                <?php } ?>
                                <td style="padding: 0px;">
                                    <table class="item_table" cellspacing="2">
                                        <tbody>
                                            <?php if ($a5config->total_taxable_amount == 'Y') { ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid; padding: 2px 2px;"><b>TAXABLE</b></td>
                                                <td style="border-bottom: 1px solid;padding: 2px 2px;">
                                                    <b style="float:right;">
                                                        <i class="fa fa-inr"></i> <?= number_format($result->taxableAmount, 3); ?>
                                                    </b>
                                                </td>
                                            </tr>
                                            <?php } if ($a5config->total_tax_amount == 'Y') { ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid;padding: 2px 2px;"><b>TAX</b></td>
                                                <td style="border-bottom: 1px solid;padding: 2px 2px;">
                                                    <b style="float:right;">
                                                        <i class="fa fa-inr"></i> <?= number_format($result->totalTax, 3); ?>
                                                    </b>
                                                </td>
                                            </tr>
                                            <?php } if ($a5config->round_off == 'Y') { ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid;padding: 2px 2px;"><b>ROUND OFF</b></td>
                                                <td style="border-bottom: 1px solid;padding: 2px 2px;">
                                                    <b style="float:right;">
                                                        <i class="fa fa-inr"></i> <?= number_format($result->roundOff, 2); ?>
                                                    </b>
                                                </td>
                                            </tr>
                                            <?php } if ($a5config->grand_total == 'Y') { ?>
                                            <tr>
                                                <td style="padding: 3px 2px;"><b>GRAND TOTAL</b></td>
                                                <td style="padding: 3px 2px;">
                                                    <b style="float:right;">
                                                        <i class="fa fa-inr"></i> <?= number_format($result->total, 2); ?>
                                                    </b>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="amount_word"><?= ucwords($result->amountInWords); ?>/-</div>
                    <?php if ($a5config->hsnsac_summary == 'Y') { ?>
                    <table class="item_table">
                        <thead>
                            <tr>
                                <th style="border-bottom: none; border-top: none;">HSN/SAC</th>
                                <th style="border-bottom: none; border-top: none;">Taxable</th>
                                <th colspan="2" style="border-top: none;">CGST</th>
                                <th colspan="2" style="border-top: none;">SGCT</th>
                                <th colspan="2" style="border-top: none;">IGST</th>
                                <th style="border-bottom: none; border-top: none;">Total</th>
                            </tr>
                            <tr>
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
                        <tbody>
                            <?php
                                $this->db->select('tax');
                                $this->db->select('hsn');
                                $this->db->select_sum('purchase_price');
                                $this->db->select_sum('sgst');
                                $this->db->select_sum('cgst');
                                $this->db->select_sum('igst');
                                $this->db->select_sum('amount');
                                $this->db->select_sum('taxAmount');
                                $this->db->from('proforma_product');
                                $this->db->where('invoiceID', $result->id);
                                $this->db->where('InvoiceNo', $result->invoiceNumber);
                                $this->db->where('type', 'Proforma');
                                $this->db->group_by(array('tax', 'hsn'));
                                $taxdata = $this->db->get()->result();
                                if ($taxdata) { 
                                    foreach ($taxdata as $key => $row) { 
                            ?>
                            <tr>
                                <td><?= $row->hsn; ?></td>
                                <td style="text-align:right;"><i class="fa fa-inr"></i> <?= number_format($row->purchase_price, 3); ?></td>
                                <?php if ($row->cgst == "0.00" && $row->sgst == "0.00") { ?>
                                <td style="text-align:right;">-</td>
                                <td style="text-align:right;">-</td>
                                <td style="text-align:right;">-</td>
                                <td style="text-align:right;">-</td>
                                <?php } else { ?>
                                <td style="text-align:right;"><?= $row->tax / 2; ?> <i class="fa fa-percent"></i></td>
                                <td style="text-align:right;"><i class="fa fa-inr"></i> <?= number_format($row->cgst, 3); ?></td>
                                <td style="text-align:right;"><?= $row->tax / 2; ?> <i class="fa fa-percent"></i></td>
                                <td style="text-align:right;"><i class="fa fa-inr"></i> <?= number_format($row->sgst, 3); ?></td>
                                <?php } if ($row->igst == "0.00") { ?>
                                <td style="text-align:right;">-</td>
                                <?php } else { ?>
                                <td style="text-align:right;"><?= $row->tax; ?> <i class="fa fa-percent"></i></td>
                                <?php } if ($row->igst == "0.00") { ?>
                                <td style="text-align:right;">-</td>
                                <?php } else { ?>
                                <td style="text-align:right;"><i class="fa fa-inr"></i> <?= number_format($row->igst, 3); ?></td>
                                <?php } ?>
                                <td style="text-align:right;"><i class="fa fa-inr"></i> <?= number_format($row->amount, 3); ?></td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align: right;">Total</th>
                                <th style="text-align: right;"><i class="fa fa-inr"></i> <?= number_format($result->taxableAmount, 3); ?></th>
                                <?php if ($result->totalcgst == "0.00" && $result->totalsgst == "0.00") { ?>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <?php } else { ?>
                                <th></th>
                                <th style="text-align: right;"><i class="fa fa-inr"></i> <?= number_format($result->totalcgst, 3); ?></th>
                                <th></th>
                                <th style="text-align: right;"><i class="fa fa-inr"></i> <?= number_format($result->totalsgst, 3); ?></th>
                                <?php } if ($result->totaligst == "0.00") { ?>
                                <th></th>
                                <th></th>
                                <?php } else { ?>
                                <th></th>
                                <th style="text-align: right;"><i class="fa fa-inr"></i> <?= number_format($result->totaligst, 3); ?></th>
                                <?php } ?>
                                <th style="text-align:right;"><i class="fa fa-inr"></i> <?= number_format($result->totalTax, 3); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <?php } ?>
                    <div class="bank_info" style=" font-size: 11px;">
                        <?php if ($a5config->bank_info == 'Y') { ?>
                        <div style="font-weight:bold;font-family:'ArialMt Bold';">Bank Account Information :</div>
                        <?php 
                            if (!empty($comp_profile->bank)) {
                        ?>
                        <div class="bank_account_name"><strong><?= $bank->bank_details; ?></strong></div>
                        <?php } } ?>
                    </div>
                    <?php if ($a5config->remark == 'Y') { ?>
                    <div class="remarks" style="border-bottom: 1px solid;">
                        <div style="font-weight:bold;font-family:'ArialMt Bold';">
                            Remarks : <?= $result->remark; ?>
                        </div>
                    </div>
                    <?php } ?>
                        <table class="inv_details" cellspacing="0" cellpadding="0">
                                <tbody>
                                        <tr>
                                               <td class="terms">
                                                       <div class="label" style="font-size:15px;">Terms and Conditions</div>
                                                       <span style="font-size:12px;line-height: 1.1;">
                                                               <?php 
                                                                       if (!empty($comp_profile->content)) {
                                                                               echo $bank->content;
                                                                       } 
                                                               ?>
                                                       </span>
                                               </td>
                                               <?php if ($a5config->signature == 'Y') { ?>
                                               <td class="receiver_signature" style="font-size:14px;">
                                                       <div>
                                                               <p>Receiver Signatures</p>
                                                       </div>
                                               </td>
                                               <?php } if ($comp_profile->qr_status == 'enable') { ?>
                                               <td class="customer_signature" style="font-size:16px;">
                                                       <table style="width: 100%; border-collapse: collapse;">
                                                               <tr>
                                                                       <td style="height: auto; text-align: center;">
                                                                               <b>Payment QR</b>
                                                                       </td>
                                                               </tr>
                                                               <tr>
                                                                       <td style="height: auto; text-align: center;">
                                                                              <?php
                                                                                       if ($comp_profile->qr_height) {
                                                                                            $qr_height = $comp_profile->qr_height;
                                                                                       } else {
                                                                                            $qr_height = "137";
                                                                                       }
                                                                                       if ($comp_profile->qr_width) {
                                                                                            $qr_width = $comp_profile->qr_width;
                                                                                       } else {
                                                                                            $qr_width = "260";
                                                                                       }
                                                                                       if ($comp_profile->qr_status == 'enable') {
                                                                                              if ($bank->payment_qr) {
                                                                                                      $paymentqrurl = 'uploads/payment_qr/' . $bank->payment_qr; 
                                                                                                      $ext3 = pathinfo($paymentqrurl, PATHINFO_EXTENSION);
                                                                                                      $paymentqrimg = file_get_contents($paymentqrurl);
                                                                                                      $paymentqr = 'data:image/' . $ext2 . ';base64,' . base64_encode($paymentqrimg);
                                                                               ?>
                                                                               <img src="<?= $paymentqr; ?>" style="width: <?= $qr_width; ?>px; height: <?= $qr_height; ?>px;" />
                                                                               <?php } else { echo '<div style="height:100px;"></div>'; } } else { echo '<div style="height:100px;"></div>'; } ?>
                                                                       </td>
                                                               </tr>
                                                       </table>
                                               </td>                                               
                                               <?php } ?>
                                               <td class="customer_signature" style="font-size:16px;">
                                                        <table style="width: 100%; border-collapse: collapse;">
                                                               <tr>
                                                                       <td style="height: auto; text-align: center;">
                                                                                <b style="white-space: nowrap;">For: <?= $comp_profile->name; ?></b>
                                                                       </td>
                                                               </tr>
                                                               <tr>
                                                                       <td style="height: auto; text-align: center;">
                                                                              <?php
                                                                                      if ($comp_profile->signature_height) {
                                                                                              $signature_height = $comp_profile->signature_height;
                                                                                      } else {
                                                                                              $signature_height = "137";
                                                                                      }
                                                                                      if ($comp_profile->signature_width) {
                                                                                              $signature_width = $comp_profile->signature_width;
                                                                                      } else {
                                                                                               $signature_width = "260";
                                                                                      }
                                                                                      if ($comp_profile->signature_display == 'enable') {
                                                                                              if ($bank->scan_signature) {
                                                                                                      $signatureurl = 'uploads/signature/' . $bank->scan_signature; 
                                                                                                      $ext2 = pathinfo($signatureurl, PATHINFO_EXTENSION);
                                                                                                      $signatureimg = file_get_contents($signatureurl);
                                                                                                       $signature = 'data:image/' . $ext2 . ';base64,' . base64_encode($signatureimg);
                                                                               ?>
                                                                              <img src="<?= $signature; ?>" style="width: <?= $signature_width; ?>px; height: <?= $signature_height; ?>px;" />
                                                                              <?php } else { echo '<div style="height:100px;"></div>'; } } else { echo '<div style="height:100px;"></div>'; } ?>
                                                                       </td>
                                                               </tr>
                                                               <tr>
                                                                       <td style="height: auto; text-align: center;">
                                                                               Authorized Signature
                                                                       </td>
                                                               </tr>
                                                       </table>
                                               </td>
                                       </tr>
                               </tbody>
                        </table>
                </div>
                <?php if ($a5config->footer == 'Y') { ?>
                <div class=" footer" style="font-size:12px;">
                        This is a computer generated copy by Uday Plastic
                </div>
                <?php } ?>
            </div>
            <?php } } ?>

            <script type="text/javascript">
                $(document).ready(function() {
                    <?php if ($a5config->hsnsac_summary == 'N') { ?>
                    if (document.body.scrollHeight < 1000) {
                        document.getElementById("page-main").style.height = document.getElementById("page-main").clientHeight + 150 + 'px';
                    }
                    <?php } ?>
                    window.print();
                });

                function closePrintView() {
                    window.open('', '_self');
                    window.close();
                }

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