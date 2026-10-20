<!DOCTYPE html>
<html>
    <head>
        <title>Uday Plastic - Invoice</title>
        <meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1" />
    	<meta http-equiv="x-ua-compatible" content="ie=edge" />
        <link type="image/png" rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <link rel="stylesheet" href="<?= base_url(); ?>plugins/fontawesome-free/css/all.min.css" />
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
	<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    </head>
    <body>
	<?php
            $product_field = 0;
            $comp_id = $this->session->userdata('comp_id');
            $user_id = $this->session->userdata('user_id');
            $where = ['comp_id' => $comp_id, 'user_id' => $user_id];
            if ($this->session->userdata('BranchRole')){
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            }
            $comp_profile = $this->user_model->getRow('comp_profile', $where);

            $company_ka_data = $this->user_model->getRow('comp_profile', $where);
            $counter = 0;
            if ($company_ka_data->dcdate == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->dcno == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->lrnumber == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->eway == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->dpb == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->ptd == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->duedate == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->lrdate == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->vahical_no == 'yes') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->orderDate == 'Y') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->orderNumber == 'Y') {
		$counter = $counter + 1;
            }
            if ($company_ka_data->lot_no == 'Y') {
		$counter = $counter + 1;
            }

            $a5config = $this->user_model->getRow('a5config', $where);
            if ($a5config->hsn == 'Y') {
		++$product_field;
            }
            if ($a5config->qty == 'Y') {
		++$product_field;
            }
            if ($a5config->mrp == 'Y') {
		++$product_field;
            }
            if ($a5config->sell_price == 'Y') {
		++$product_field;
            }
            if ($a5config->purchase_price == 'Y') {
		++$product_field;
            }
            if ($a5config->tax == 'Y') {
		++$product_field;
            }
            if ($a5config->tax == 'Y') {
		++$product_field;
            }
            if ($a5config->taxable_amount == 'Y') {
		++$product_field;
            }
            if ($a5config->net_amount == 'Y') {
		++$product_field;
            }
            if ($a5config->taxamount == 'Y') {
		++$product_field;
            }

            if ($comp_profile) {
		$jklmn = count($result);
                $jklmn2 = $NumberOfInvoice;
                foreach ($result as $keys => $result) {
                    for ($jkl = 0; $jkl < $jklmn2; $jkl++) {    
	?>
        <div class="main_container">
            <?php if ($jkl == 0) { ?>
            <div class="copy_title text-primary" style="margin-right:34px;">ORIGINAL</div>
            <?php } elseif ($jkl == 1) { ?>
            <div class="copy_title text-dark" style="margin-right:34px;">DUPLICATE</div>
            <?php } elseif ($jkl == 2) { ?>
            <div class="copy_title text-dark" style="margin-right:34px;">TRIPLICATE</div>
            <?php } else { ?>
            <div class="copy_title text-dark" style="margin-right:34px;">ONLY DUPLICATE</div>
            <?php } ?>
            <div class="container">
                <table border="0" cellspacing="0" class="item_table" id="item_table_1" style="border: 1px solid #000;">
                    <thead>
                        <tr>
                            <td style="padding: 0px;" colspan="10">
                                <div class="header_content">
                                    <?php
                                        $log_postion = $comp_profile->logo_position;
                                        $logo_status = $comp_profile->logo_status;
                                        $comp_details_position = $comp_profile->comp_details_position;
                                        $get_row = ['id' => $comp_id, 'user_id' => $user_id];
                                        if ($this->session->userdata('BranchRole')){
                                            $get_row['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                                            $BranchWhere = ['Id' => $this->session->userdata('CompBranchId'), 'CompId' => $comp_id, 'BranchUserId' => $user_id];
                                            $CompanyBranch = $this->user_model->getRow('companybranch', $BranchWhere);
                                        } else {
                                            $inventory_billing = $this->user_model->getRow('inventory_billing', $get_row);
                                        }
                                    ?>
                                    <div class="header" style="text-align: <?= $log_postion; ?>;">
                                        <?php if ($logo_status == 'enable' && $log_postion) { ?>
                                        <img src="<?= base_url(); ?>uploads/complogo/<?= $comp_profile->logo; ?>" style="width: <?= $comp_profile->logo_width; ?>px;height: <?= $comp_profile->logo_height; ?>px; float: <?= $log_postion; ?>; text-align: <?= $log_postion; ?>;" />
                                        <?php } ?>
                                        <div style="float: <?= $comp_details_position; ?>; text-align: <?= $comp_details_position; ?>;">
                                            <?php if ($a5config->comp_name == 'Y') { ?>
                                            <div class="name" style="font-size: 16px;">
                                                <h2 style="color: black; margin-top: auto;">
                                                    <b><?= $comp_profile->name; ?></b>
                                                </h2>
                                            </div>
                                            <?php } if ($a5config->comp_address == 'Y') { ?>
                                            <div class="address" style="font-size: 16px; margin-top: -35px;">
                                                <?= $this->session->userdata('BranchRole') ? $CompanyBranch->Address : $inventory_billing->address; ?>
                                            </div>
                                            <?php } if ($a5config->comp_mobile_number == 'Y') { ?>
                                            <div class="contact_no" style="font-size: 16px;">
                                                <?= $this->session->userdata('BranchRole') ? $CompanyBranch->Mobile : $inventory_billing->number; ?>
                                            </div>
                                            <?php } if ($a5config->comp_email == 'Y') { ?>
                                            <div class="email" style="font-size: 16px;">
                                                <?= $this->session->userdata('BranchRole') ? $CompanyBranch->Email : $inventory_billing->email; ?>
                                            </div>
                                            <?php } if ($a5config->comp_gst == 'Y') { ?>
                                            <div class="gst_no" style="font-size: 16px;">
                                                <b style="color: black;">GSTIN: <?= $this->session->userdata('gst_nos') ?></b>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php 
                                        if ($result->customerId != '' && $result->customerId != 0) {
                                            $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
                                        }
                                    ?>
                                    <table style="width: 100%; border-collapse: collapse;font-size: 13px;">
                                        <?php if ($a5config->title) { ?>
                                        <tr>
                                            <td colspan="2" style="text-align: center; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 0px;">
                                                <h3 style="margin: 0px;"><?= $a5config->title; ?></h3>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td style="width: 50%; padding: 10px; border-bottom: 1px solid #000;line-height:15px;font-size: 12px;">
                                                <h4 style="margin-bottom: auto; margin-top: 0px;"><strong>Billing Address,</strong></h4>
                                                <?php if ($a5config->customer_name == 'Y') { ?>
                                                <h6 style="text-transform: uppercase; margin: 5px 0 5px 0; font-size:15px;">
                                                    <strong><?= $party->name; ?></strong>
                                                </h6>
                                                <?php } if ($a5config->customer_address == 'Y') { ?>
                                                <p style="margin: 5px 0 0px 0; font-size: 12px;">
                                                    <?= $party->address_1; ?>
                                                    <br />
                                                    <?php $city = $this->db->get_where('districts', ['id' => $party->city])->row(); echo $city->name; ?>
                                                    <br />
                                                    <?php $state = $this->db->get_where('state_list', ['id' => $party->state])->row(); echo $state->state; ?>&nbsp;(<?= $party->pin; ?>)
                                                </p>
                                                <?php } ?>
                                                <p style="margin: 5px 0 5px 0;">
                                                    <?php if ($a5config->PartyContactPerson == 'Y') { ?>
                                                    Contact Person: <?= $party->ContactPerson; ?>
                                                    <br />
                                                    <?php } if ($a5config->customer_mobile_number == 'Y') { ?>
                                                    Contact Number: <?= $party->contact; ?>
                                                    <br />
                                                    <?php } if ($a5config->customer_email == 'Y') { ?>
                                                    Email: <?= $party->email; ?>
                                                    <br />
                                                    <?php } if ($a5config->customer_gst == 'Y') { ?>
                                                    <strong style="margin: 0 0 5px 0; color: red;">GSTN: <?= $party->gst; ?></strong>
                                                    <?php } ?>
                                                </p>
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
                                                <table style="width: 100%; border-collapse: collapse;">
                                                    <?php if ($a5config->invoice_number == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Invoice No :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right; color: red;">
                                                            <strong><?= $result->invoiceNumber; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($a5config->invoice_date == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Invoice Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($a5config->state == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>State of Supply :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong>
                                                                <?= get_title('state_list', ['id' => $party->state], 'code'); ?>
                                                                -
                                                                <?= get_title('state_list', ['id' => $party->state], 'state'); ?>
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($a5config->payment_mode == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Payment Mode :</strong>
                                                        </td>
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
                                                    <?php } 
                                                        if ($counter > 4 || $a5config->shipping_address == 'N') { 
                                                            $dispatch_by = $this->session->userdata('dpb');
                                                            if ($dispatch_by == 'yes') {
                                                    ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Dispatch By :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= ucwords($result->transport); ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } $lr = $this->session->userdata('lrnumber'); if ($lr == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>LR No :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->lrNumber; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } $ewayBill = $this->session->userdata('eway'); if ($ewayBill == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Eway Bill :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->ewayBill; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } $duedate = $this->session->userdata('duedate'); if ($duedate == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Due Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->dueDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->ptd == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Receipt Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->recieptDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->lrdate == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Lr Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->lrDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->vahical_no == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Vehicle No :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->vahical_no; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->SalesmanOnPrintOut == 'Yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%;border-right:none;">
                                                            <strong>Salesman Name :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= get_title('employees', ['Id' => $party->SalesmanId], 'Name'); ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->orderDate == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Order Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->orderDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->orderNumber == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Order Number :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->orderNumber; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->lot_no == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Lot No :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->lot_no; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } } ?>
                                                </table>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <?php if ($a5config->shipping_address == 'Y' && $counter < 4) { ?>
                                            <td style="width: 50%; padding: 10px;">
                                                <table style="width: 100%; border-collapse: collapse;">
                                                    <?php $dispatch_by = $this->session->userdata('dpb'); if ($dispatch_by == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Dispatch By :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= ucwords($result->transport); ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } $lr = $this->session->userdata('lrnumber'); if ($lr == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>LR No :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->lrNumber; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } $ewayBill = $this->session->userdata('eway'); if ($ewayBill == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Eway Bill :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->ewayBill; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } $duedate = $this->session->userdata('duedate'); if ($duedate == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Due Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->dueDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->ptd == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Receipt Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->recieptDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->lrdate == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Lr Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->lrDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->vahical_no == 'yes') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Vehicle No :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->vahical_no; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->orderDate == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Order Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->orderDate; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->orderNumber == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Order Number :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->orderNumber; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($company_ka_data->lot_no == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Lot No :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->lot_no; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>
                                            </td>
                                            <?php } else { if ($a5config->shipping_address == 'Y') { ?>
                                            <td></td>
                                            <?php } } if ($a5config->shipping_address == 'Y') { ?>
                                            <td style="width: 50%; padding: 10px;">
                                                <table style="width: 100%; border-collapse: collapse;">
                                                    <?php if ($a5config->invoice_number == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Invoice No :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= $result->invoiceNumber; ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($a5config->invoice_date == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Invoice Date :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($a5config->state == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>State of Supply :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong>
                                                                <?= get_title('state_list', ['id' => $party->state], 'code'); ?>
                                                                -
                                                                <?= get_title('state_list', ['id' => $party->state], 'state'); ?>
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                    <?php } if ($a5config->payment_mode == 'Y') { ?>
                                                    <tr>
                                                        <td style="width: 50%; border-right: none;">
                                                            <strong>Payment Mode :</strong>
                                                        </td>
                                                        <td style="width: 50%; text-align: right;">
                                                            <strong>
                                                                <?php
                                                                    if ($result->voucherType == 2) {
                                                                        echo "Cash";
                                                                    } elseif ($result->voucherType == 1) {
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
                            <th class="item_no" style="white-space: nowrap;">Sr.</th>
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
                            <th class="item_name" style="white-space: nowrap;">DESCRIPTION OF GOODS</th>
                            <?php
                                if ($a5config->hsn == 'Y') {
                                    echo '<th class="item_hsn" style="white-space: nowrap;">HSN/SAC</th>';
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->qty == 'Y') {
                                    echo '<th class="item_qty" style="white-space: nowrap;">QTY</th>';
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->mrp == 'Y') {
                                    echo '<th class="item_mrp" style="white-space: nowrap;">MRP</th>';
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->sell_price == 'Y') {
                                    echo '<th class="item_rate" style="white-space: nowrap;">RATE</th>';
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->tax == 'Y') {
                                    echo '<th class="tax" style="white-space: nowrap;">Tax%</th>';
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->taxable_amount == 'Y') {
                                    echo '<th class="taxable_amount" style="white-space: nowrap;">Taxble Amt</th>';
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->taxamount == 'Y') {
                                    echo '<th class="tax_amount" style="white-space: nowrap;">Tax Amount</th>';
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->net_amount == 'Y') {
                                    echo '<th class="netAmount" style="white-space: nowrap;">NetAmount</th>';
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
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
                            $pwhare = ['invoiceID' => $result->id, 'type' => 'Sale'];
                            $serials = [];
                            
                            $allProducts = $this->user_model->getAry('invoice_product', '', '', '', $pwhare);
                            foreach ($allProducts as $row) {
                                $serialsAry = ['invoice_id' => $row->invoiceID, 'item_id' => $row->id];
                                $serials[] = $this->user_model->getAry('item_serials', '', '', '', $serialsAry, 'serial_no');
                                $row->serials = $serials;
                            }
                            if ($allProducts) {
                                foreach ($allProducts as $key => $row) {
                                    $qtty = $row->qty;
                                    $nosItm += $qtty;
                                    $taxIte = get_title('product', ['id' => $row->tax], 'tax');
                                    $rate = $row->rate;
                        ?>
                        <tr class="text-center">
                            <td><?= $key + 1; ?></td>
                            <td class="text-left">
                                <?php
                                    $product_name = get_title('product', ['id' => $row->product_id], 'p_name');
                                    if ($product_name) {
                                        echo $product_name;
                                    } else {
                                        echo $product_name;
                                    }
                                ?>
                                <br />
                                <span><?= $row->product_dec; ?></span>
                            </td>
                            <?php if ($a5config->hsn == 'Y') { ?>
                            <td><?= $row->hsn; ?></td>
                            <?php 
                                } else {
                                    echo '<td style="border-right: 0px; border-bottom: 0px;"></td>';
                                } if ($a5config->qty == 'Y') { 
                            ?>
                            <td><?= $row->qty; ?></td>
                            <?php
                                } else {
                                    echo '<td style="border-right: 0px; border-bottom: 0px;"></td>';
                                } if ($a5config->mrp == 'Y') {
                            ?>
                            <td><?= get_title('product', ['id' => $row->product_id], 'mrp'); ?></td>
                            <?php
                                } else {
                                    echo '<td style="border-right: 0px; border-bottom: 0px;"></td>';
                                } if ($a5config->sell_price == 'Y') {
                            ?>
                            <td style="white-space: nowrap;"><?= money_formats($row->purchase_price / $row->qty); ?></td>
                            <?php
                                } else {
                                    echo '<td style="border-right: 0px; border-bottom: 0px;"></td>';
                                } if ($a5config->tax == 'Y') {
                            ?>
                            <td style="white-space: nowrap;"><?= $row->tax; ?>%</td>
                            <?php
                                } else {
                                    echo '<td style="border-right: 0px; border-bottom: 0px;"></td>';
                                } if ($a5config->taxable_amount == 'Y') {
                            ?>
                            <td><?= $row->purchase_price; ?></td>
                            <?php
                                } else {
                                    echo '<td style="border-right: 0px; border-bottom: 0px;"></td>';
                                } if ($a5config->taxamount == 'Y') {
                            ?>
                            <td><?= money_formats($row->amount); ?></td>
                            <?php
                                } else {
                                    echo '<td style="border-right: 0px; border-bottom: 0px;"></td>';
                                } if ($a5config->net_amount == 'Y') {
                            ?>
                            <td><?= money_formats($row->netAmount); ?></td>
                            <?php } ?>
                        </tr>
                        <?php
                            $counts = $key;
                            } }
                            if (isset($counts) && !empty($counts)) {
                                $lines = $lines - $counts;
                                $lines = $lines * 1;
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
                            <?php } else { ?>
                            <td style="border: none;"></td>
                            <?php } if ($a5config->qty == 'Y') { ?>
                            <td>&nbsp;</td>
                            <?php } else { ?>
                            <td style="border: none;"></td>
                            <?php } if ($a5config->mrp == 'Y') { ?>
                            <td>&nbsp;</td>
                            <?php } else { ?>
                            <td style="border: none;"></td>
                            <?php } if ($a5config->sell_price == 'Y') { ?>
                            <td>&nbsp;</td>
                            <?php } else { ?>
                            <td style="border: none;"></td>
                            <?php } if ($a5config->taxable_amount == 'Y') { ?>
                            <td>&nbsp;</td>
                            <?php } else { ?>
                            <td style="border: none;"></td>
                            <?php } if ($a5config->tax == 'Y') { ?>
                            <td>&nbsp;</td>
                            <?php } else { ?>
                            <td style="border: none;"></td>
                            <?php } if ($a5config->taxamount == 'Y') { ?>
                            <td>&nbsp;</td>
                            <?php } else { ?>
                            <td style="border: none;"></td>
                            <?php } if ($a5config->net_amount == 'Y') { ?>
                            <td>&nbsp;</td>
                            <?php } else { ?>
                            <td style="border: none;"></td>
                            <?php } ?>
                        </tr>
                        <?php } } ?>
                        <tr style="text-align: center;">
                            <th></th>
                            <th></th>
                            <?php if ($a5config->hsn == 'Y') { ?>
                            <th></th>
                            <?php
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->qty == 'Y') {
                            ?>
                            <th class="item_qty"><?= $nosItm; ?></th>
                            <?php
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->mrp == 'Y') {
                            ?>
                            <th></th>
                            <?php
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->sell_price == 'Y') {
                            ?>
                            <th></th>
                            <?php
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->taxable_amount == 'Y') {
                            ?>
                            <th><?= money_formats($result->taxableAmount); ?></th>
                            <?php
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->tax == 'Y') {
                            ?>
                            <th></th>
                            <?php
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->taxamount == 'Y') {
                            ?>
                            <th><?= money_formats($result->totalTax); ?></th>
                            <?php
                                } else {
                                    echo '<th style="border-right: 0px;"></th>';
                                } if ($a5config->net_amount == 'Y') {
                            ?>
                            <th><?= money_formats($result->total); ?></th>
                            <?php
                                } else {
                                    echo '<th style="border-right:0px;"></th>';
                                }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table class="item_table" style="border: 1px solid #000;">
                    <tbody>
                        <tr>
                            <?php if ($a5config->total_summary == 'Y') { ?>
                            <td style="padding: 0px; min-width: 358px;">
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
                                        <tr style="font-size: 14px;">
                                            <td class="tax_slab" style="text-align: left; white-space: nowrap;">
                                                <b>GST @<?= $row->tax; ?>%</b>
                                            </td>
                                            <td><?= money_formats($row->purchase_price, 2); ?></td>
                                            <td><?= money_formats($row->sgst, 2); ?></td>
                                            <td><?= money_formats($row->cgst, 2); ?></td>
                                            <td><?= money_formats($row->igst, 2); ?></td>
                                        </tr>
                                        <?php $tcount--; } }
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
                                            <td colspan="1" style="border-top: 0.5px solid; white-space: nowrap;">
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
                            <td style="padding: 0px; min-width: 273px;">
                                <table class="item_table" cellspacing="2">
                                    <tbody>
                                        <?php if ($a5config->total_taxable_amount == 'Y') { ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid; padding: 2px 2px;"><b>TAXABLE</b></td>
                                            <td style="border-bottom: 1px solid;padding: 2px 2px;">
                                                <b style="float:right;">
                                                    <i class="fa fa-inr"></i> <?= money_formats($result->taxableAmount); ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <?php } if ($a5config->total_tax_amount == 'Y') { ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid;padding: 2px 2px;"><b>TAX</b></td>
                                            <td style="border-bottom: 1px solid;padding: 2px 2px;">
                                                <b style="float:right;">
                                                    <i class="fa fa-inr"></i> <?= money_formats($result->totalTax); ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <?php } if ($a5config->round_off == 'Y') { ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid;padding: 2px 2px; white-space: nowrap;"><b>ROUND OFF</b></td>
                                            <td style="border-bottom: 1px solid;padding: 2px 2px; white-space: nowrap;">
                                                <b style="float:right;">
                                                    <i class="fa fa-inr"></i> <?= money_formats($result->roundOff); ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <?php } if ($a5config->freight_charge == 'Y') { ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid; padding: 3px 2px;"><b>FREIGHT CHARGE</b></td>
                                            <td style="border-bottom: 1px solid; padding: 3px 2px;">
                                                <b style="float:right;">
                                                    <i class="fa fa-inr"></i> <?= money_formats($result->freight_charge); ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <?php } if ($a5config->cash_discount == 'Y') { ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid; padding: 3px 2px;"><b>CASH DISCOUNT</b></td>
                                            <td style="border-bottom: 1px solid; padding: 3px 2px;">
                                                <b style="float:right;">
                                                    <i class="fa fa-inr"></i> <?= money_formats($result->cash_discount); ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <?php } if ($a5config->grand_total == 'Y') { ?>
                                        <tr>
                                            <td style="padding: 3px 2px; white-space: nowrap;"><b>GRAND TOTAL</b></td>
                                            <td style="padding: 3px 2px; white-space: nowrap;">
                                                <b style="float:right;">
                                                    <i class="fa fa-inr"></i> <?= money_formats($result->total); ?>
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
                <div class="amount_word" style="border: 1px solid #000; min-width: 612px;">
                    <?= ucwords($result->amountInWords); ?>/-
                </div>
                <?php if ($a5config->hsnsac_summary == 'Y') { ?>
                <table class="item_table" style="border: 1px solid #000; min-width: 634px;">
                    <tr>
                        <th style="border-bottom: none; border-top: none;">HSN/SAC</th>
                        <th style="border-bottom: none; border-top: none;">Taxable</th>
                        <th colspan="2" style="border-top: none;">CGST</th>
                        <th colspan="2" style="border-top: none;">SGST</th>
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
                        <th style="border-top: none; white-space: nowrap;">Tax Amount</th>
                    </tr>
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
                        <td><?= $row->hsn; ?></td>
                        <td style="text-align:right; white-space: nowrap;"><i class="fa fa-inr"></i> <?= money_formats($row->purchase_price, 2); ?></td>
                        <?php if ($row->cgst == "0.00" && $row->sgst == "0.00") { ?>
                        <td style="text-align:right;">-</td>
                        <td style="text-align:right;">-</td>
                        <td style="text-align:right;">-</td>
                        <td style="text-align:right;">-</td>
                        <?php } else { ?>
                        <td style="text-align:right;"><?= $row->tax / 2; ?> <i class="fa fa-percent"></i></td>
                        <td style="text-align:right;white-space: nowrap;"><i class="fa fa-inr"></i> <?= money_formats($row->cgst, 2); ?></td>
                        <td style="text-align:right;"><?= $row->tax / 2; ?> <i class="fa fa-percent"></i></td>
                        <td style="text-align:right;white-space: nowrap;"><i class="fa fa-inr"></i> <?= money_formats($row->sgst, 2); ?></td>
                        <?php } if ($row->igst == "0.00") { ?>
                        <td style="text-align:right;">-</td>
                        <?php } else { ?>
                        <td style="text-align:right;"><?= $row->tax; ?> <i class="fa fa-percent"></i></td>
                        <?php } if ($row->igst == "0.00") { ?>
                        <td style="text-align:right;">-</td>
                        <?php } else { ?>
                        <td style="text-align:right;"><i class="fa fa-inr"></i> <?= money_formats($row->igst, 2); ?></td>
                        <?php } ?>
                        <td style="text-align:right;"><i class="fa fa-inr"></i> <?= money_formats($row->amount, 2); ?></td>
                    </tr>
                    <?php } } ?>
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
                        <th style="text-align: right;"><i class="fa fa-inr"></i> <?= money_formats($result->totalcgst, 2); ?></th>
                        <th></th>
                        <th style="text-align: right;"><i class="fa fa-inr"></i> <?= money_formats($result->totalsgst, 2); ?></th>
                        <?php } if ($result->totaligst == "0.00") { ?>
                        <th></th>
                        <th></th>
                        <?php } else { ?>
                        <th></th>
                        <th style="text-align: right;"><i class="fa fa-inr"></i> <?= money_formats($result->totaligst, 2); ?></th>
                        <?php } ?>
                        <th style="text-align:right;"><i class="fa fa-inr"></i> <?= money_formats($result->totalTax, 2); ?></th>
                    </tr>
                </table>
                <?php } ?>                
                <div class="bank_info" style="font-size: 11px; border: 1px solid #000; min-width: 612px;">
                    <?php if ($a5config->bank_info == 'Y') { ?>
                    <div style="font-weight: bold; font-family: 'ArialMt Bold';">
                        <?php 
                            $get_where = ['status' => 1, 'user_id' => $user_id, 'comp_id' => $comp_id];
                            if ($this->session->userdata('BranchRole')){
                                $get_where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                            }
                            $bank = $this->db->get_where('comp_profile', $get_where)->row();
                            if (isset($bank) && !empty($bank)) {
                        ?>
                        <div class="bank_account_name">
                            Bank Account Information : <strong><?= $bank->bank_details; ?></strong>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
                <?php if ($a5config->remark == 'Y') { ?>
                <div class="remarks" style="border: 1px solid #000; min-width: 634px;">
                    <div style="font-weight:bold;font-family:'ArialMt Bold';">
                        Remarks : <?= $result->remark; ?>
                    </div>
                </div>
                <?php } ?>
                <table class="inv_details" cellspacing="0" cellpadding="0" style="border: 1px solid #000; min-width: 634px;">
                    <tbody>
                        <tr>
                            <td class="terms">
                                <div class="label" style="font-size: 15px;">Terms and Conditions</div>
                                <span style="font-size: 12px; line-height: 1.1;">
                                    <?php 
                                        $where = ['status' => 1, 'user_id' => $user_id, 'comp_id' => $comp_id];
                                        if ($this->session->userdata('BranchRole')) {
                                            $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                                        }
                                        $bank = $this->db->get_where('comp_profile', $where)->row();
                                        if (!empty($bank->content)) {
                                            echo $bank->content;
                                        }
                                    ?>
                                </span>
                            </td>
                            <?php if ($comp_profile->qr_status == 'enable') { ?>
                            <td style="width: 50px; height: 50px; text-align: <?= $bank->qr_position; ?>;">
                                <div class="label" style="vertical-align: top;"><b>Payment QR</b></div>
                                <?php if ($bank->payment_qr) { ?>
                                <img src="<?= base_url(); ?>uploads/payment_qr/<?= $bank->payment_qr; ?>" style="width: <?= $comp_profile->qr_width; ?>px; height: <?= $comp_profile->qr_height; ?>px;" />
                                <?php } ?>
                            </td>
                            <?php } if ($comp_profile->signature_display == 'enable') { ?>
                            <td class="customer_signature" style="font-size: 13px;">
                                <div class="label"><b>For: <?= $comp_profile->name; ?></b></div>
                                <?php if ($bank->scan_signature) { ?>
                                <img src="<?= base_url(); ?>uploads/signature/<?= $bank->scan_signature; ?>" style="width: <?= $comp_profile->signature_width; ?>px; height: <?= $comp_profile->signature_height; ?>px;" />
                                <?php } ?>
                                <br />
                                Authorized Signature
                            </td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php if ($a5config->footer == 'Y') { ?>
            <div class="footer" style="font-size: 12px;">
                This is a computer generated copy by Uday Plastic
            </div>
            <?php } ?>
        </div>
        <?php } } } ?>

        <script type="text/javascript">
            $(document).ready(function() {
                setTimeout("window.print();", 2000);
            });
	</script>
    </body>
</html>