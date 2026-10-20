<!DOCTYPE html>
<html>
    <head>
        <title>Invoice</title>
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
                width: 60%;
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
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');
                    
            $where = ['comp_id' => $CompId, 'user_id' => $UserId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
            }
            $comp_profile = $this->user_model->getRow('comp_profile', $where);

            $company_ka_data = $this->user_model->getRow('comp_profile', $where);
            if (!$company_ka_data) {
                $company_ka_data = new stdClass();
            }
            foreach ([
                'dcdate', 'dcno', 'lrnumber', 'eway', 'dpb', 'ptd', 'duedate', 'lrdate',
                'vahical_no', 'orderDate', 'orderNumber', 'lot_no', 'SalesmanOnPrintOut',
            ] as $_ck) {
                if (!isset($company_ka_data->{$_ck})) {
                    $company_ka_data->{$_ck} = 'no';
                }
            }
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
            if ($company_ka_data->orderDate == 'yes') {
                $counter = $counter + 1;
            }
            if ($company_ka_data->orderNumber == 'yes') {
                $counter = $counter + 1;
            }
            if ($company_ka_data->lot_no == 'Y') {
                $counter = $counter + 1;
            }

            $estimateConfig = $this->user_model->getRow('estimateconfig', $where);
            if (!$estimateConfig) {
                $estimateConfig = new stdClass();
            }
            // Fill missing estimateconfig columns so PHP 8.3 does not warn
            foreach ([
                'hsn', 'qty', 'mrp', 'sell_price', 'purchase_price', 'tax', 'taxable_amount',
                'taxamount', 'net_amount', 'sqrfoot', 'unit', 'SubUnit', 'discount_invoice',
                'CustomerLocationQR', 'PartyContactPerson', 'customer_name', 'customer_address',
                'customer_mobile_number', 'customer_email', 'customer_gst',
                'comp_name', 'comp_address', 'comp_mobile_number', 'comp_email', 'comp_gst',
                'shipping_address', 'invoice_number', 'invoice_date', 'state', 'payment_mode',
                'total_summary', 'total_taxable_amount', 'total_tax_amount', 'round_off',
                'freight_charge', 'cash_discount', 'grand_total', 'hsnsac_summary',
                'bank_info', 'remark', 'signature', 'footer',
            ] as $_cfgKey) {
                if (!isset($estimateConfig->{$_cfgKey})) {
                    $estimateConfig->{$_cfgKey} = 'N';
                }
            }
            if (!isset($estimateConfig->title)) {
                $estimateConfig->title = '';
            }
            if ($estimateConfig->hsn == 'Y')
                ++$product_field;
            if ($estimateConfig->qty == 'Y')
                ++$product_field;
            if ($estimateConfig->mrp == 'Y')
                ++$product_field;
            if ($estimateConfig->sell_price == 'Y')
                ++$product_field;
            if ($estimateConfig->purchase_price == 'Y')
                ++$product_field;
            if ($estimateConfig->tax == 'Y')
                ++$product_field;
            if ($estimateConfig->tax == 'Y')
                ++$product_field;
            if ($estimateConfig->taxable_amount == 'Y')
                ++$product_field;
            if ($estimateConfig->net_amount == 'Y')
                ++$product_field;
            if ($estimateConfig->sqrfoot == 'Y')
                ++$product_field;
            if ($estimateConfig->unit == 'Y')
                ++$product_field;
            if ($estimateConfig->SubUnit == 'Y')
                ++$product_field;
            if ($estimateConfig->discount_invoice == 'Y')
                ++$product_field;

            if ($comp_profile) {
                $jklmn = $comp_profile->total_invoice;
                for ($jkl = 0; $jkl < $jklmn; $jkl++) {
        ?>
        <?php if ($jkl == 0) { ?>
        <div class="copy_title text-primary" style="margin-right:34px;">ORIGINAL</div>
        <?php } elseif ($jkl == 1) { ?>
        <div class="copy_title text-dark" style="margin-right:34px;">DUPLICATE</div>
        <?php } elseif ($jkl == 2) { ?>
        <div class="copy_title text-dark" style="margin-right:34px;">TRIPLICATE</div>
        <?php } else { ?>
        <div class="copy_title text-dark" style="margin-right:34px;">ONLY DUPLICATE</div>
        <?php } ?>
        <table cellspacing="0" class="item_table" id="item_table_1" style="border: 1px solid #000; width: 100%;">
            <thead>
                <tr>
                    <td style="padding:0px;" colspan="<?= $product_field + 2; ?>">
                        <div class="header_content">
                            <?php
                                $log_postion = $comp_profile->logo_position;
                                $logo_status = $comp_profile->logo_status;
                                $comp_details_position = $comp_profile->comp_details_position;
                                if ($this->session->userdata('BranchRole')) {
                                    $where = ['Id' => $CompanyBranchId, 'CompId' => $CompId, 'BranchUserId' => $UserId];
                                    $CompanyBranch = $this->user_model->getRow('companybranch', $where);
                                } else {
                                    $where = ['id' => $CompId, 'user_id' => $UserId];
                                    $inventory_billing = $this->user_model->getRow('inventory_billing', $where);
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
                                        <div style="text-align:<?= $comp_details_position; ?>;margin-top: -20px; width: 100%;">
                                            <?php if ($estimateConfig->comp_name == 'Y') { ?>
                                            <div class="name" style="margin-bottom: -20px;">
                                                <h2 style="margin-top: 20px; white-space: nowrap;">
                                                    <strong style="font-size: 16px;"><?= $comp_profile->name; ?></strong>
                                                </h2>
                                            </div>
                                            <?php } if ($estimateConfig->comp_address == 'Y') { ?>
                                            <div class="address" style="font-size: 12px;"><?= $this->session->userdata('BranchRole') ? $CompanyBranch->Address : $inventory_billing->address; ?></div>
                                            <?php } if ($estimateConfig->comp_mobile_number == 'Y') { ?>
                                            <div class="contact_no" style="font-size: 12px;"><?= $this->session->userdata('BranchRole') ? $CompanyBranch->Mobile : $inventory_billing->number ; ?></div>
                                            <?php } if ($estimateConfig->comp_email == 'Y') { ?>
                                            <div class="email" style="font-size: 12px;"><?= $this->session->userdata('BranchRole') ? $CompanyBranch->Email : $inventory_billing->email; ?></div>
                                            <?php } if ($estimateConfig->comp_gst == 'Y') { ?>
                                            <div class="gst_no" style="font-size: 12px;color:red;">
                                                <b style="color:black;">GSTIN: <?= $this->session->userdata('BranchRole') ? $CompanyBranch->GST : $inventory_billing->gst_no; ?></b>
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
                                $party = null;
                                if (!empty($result->customerId)) {
                                    $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
                                }
                                if (!$party) {
                                    $party = new stdClass();
                                }
                                foreach (['name', 'address_1', 'area', 'city', 'state', 'pin', 'LocationQR', 'ContactPerson', 'contact', 'email', 'gst', 'SalesmanId'] as $_pKey) {
                                    if (!isset($party->{$_pKey})) {
                                        $party->{$_pKey} = '';
                                    }
                                }
                            ?>
                            <table style="width: 100%; border-collapse: collapse;font-size: 13px;">
                                <?php if (!empty($estimateConfig->title)) { ?>
                                <tr>
                                    <td colspan="2" style="text-align: center; border-bottom: 1px solid #000; border-top: 1px solid #000;">
                                        <h3 style="margin: 0px;"><?= $estimateConfig->title; ?></h3>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td style="width: 50%; padding: 10px; border-bottom: 1px solid #000; line-height:15px; font-size: 12px;">
                                        <h4 style="margin-bottom: auto; margin-top: 0px;"><strong>Billing Address,</strong></h4>
                                        <?php if ($estimateConfig->customer_name == 'Y') { ?>
                                        <h6 style="text-transform: uppercase; margin: 5px 0 5px 0; font-size:15px;">
                                            <strong><?= $party->name; ?></strong>
                                        </h6>
                                        <?php } ?>
                                        <div style="display: flex;">
                                            <?php if ($estimateConfig->customer_address == 'Y') { ?>
                                            <div style="width: <?= $estimateConfig->CustomerLocationQR == 'Y' ? '80' : '100'; ?>%;">
                                                <p style="margin: 5px 0 0px 0; font-size: 12px;">
                                                    <?= $party->address_1; ?>&nbsp;<?= $party->area; ?>
                                                    <br />
                                                    <?php
                                                        $city = !empty($party->city) ? $this->db->get_where('districts', ['id' => $party->city])->row() : null;
                                                        echo (!empty($city) && isset($city->name)) ? $city->name : '';
                                                    ?>
                                                    <br />
                                                    <?php
                                                        $state = !empty($party->state) ? $this->db->get_where('state_list', ['id' => $party->state])->row() : null;
                                                        echo (!empty($state) && isset($state->state)) ? $state->state : '';
                                                    ?>&nbsp;(<?= $party->pin; ?>)
                                                </p>
                                            </div>
                                            <?php } if ($estimateConfig->CustomerLocationQR == 'Y' && $party->LocationQR !== '') { ?>
                                            <div style="width: <?= $estimateConfig->CustomerLocationQR == 'Y' ? '20' : '0'; ?>%;">
                                                <img src="<?= base_url(); ?>uploads/Parties/<?= $party->LocationQR; ?>" alt="LocationQR" style="width: 70px;" />
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <p style="margin: 0px 0 5px 0;">
                                            <?php if ($estimateConfig->PartyContactPerson == 'Y') { ?>
                                            Contact Person: <?= $party->ContactPerson; ?>
                                            <br />
                                            <?php } if ($estimateConfig->customer_mobile_number == 'Y') { ?>
                                            Contact Number: <?= $party->contact ?>
                                            <br />
                                            <?php } if ($estimateConfig->customer_email == 'Y') { ?>
                                            Email: <?= $party->email ?>
                                            <br />
                                            <?php } if ($estimateConfig->customer_gst == 'Y') { ?>
                                            <strong style="margin: 0 0 5px 0;color:red;">GSTIN: <?= $party->gst; ?></strong>
                                            <?php } ?>
                                        </p>
                                    </td>
                                       <?php if ($estimateConfig->shipping_address == 'Y') { ?>
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
                                            <?php if ($estimateConfig->invoice_number == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%; border-right: none;">
                                                    <strong>Invoice No :</strong>
                                                </td>
                                                <td style="width: 50%; text-align: right; color: red; border-right: none;">
                                                    <strong><?= $result->invoiceNumber; ?></strong>
                                                </td>
                                            </tr>
                                            <?php } if ($estimateConfig->invoice_date == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Invoice Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong></td>
                                            </tr>
                                            <?php } if ($comp_profile->due_date == 'Yes') { ?>
                                            <tr>
                                                <td style="width: 50%; border-right: none;"><strong>Due Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;">
                                                    <strong>
                                                        <?php
                                                            if (!empty($comp_profile->payment_due_period)) {
                                                                echo date('d/m/Y', strtotime($result->invoiceDate . '+ ' . $comp_profile->payment_due_period . 'days')); 
                                                            }
                                                        ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                            <?php } if ($estimateConfig->state == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%; border-right: none;">
                                                    <strong>State of Supply :</strong>
                                                </td>
                                                <td style="width: 50%; text-align: right; border-right: none;">
                                                    <strong>
                                                        <?= get_title('state_list', ['id' => $party->state], 'code'); ?>
                                                        -
                                                        <?= get_title('state_list', ['id' => $party->state], 'state'); ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                            <?php } if ($estimateConfig->payment_mode == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Payment Mode :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;">
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
                                            <?php } if ($counter > 4 || $estimateConfig->shipping_address == 'N') { ?>
                                            <?php $dispatch_by = $this->session->userdata('dpb'); if ($dispatch_by == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Dispatch By :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= ucwords($result->transport); ?></strong></td>
                                            </tr>
                                            <?php } $lr = $this->session->userdata('lrnumber'); if ($lr == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>LR No :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->lrNumber; ?></strong></td>
                                            </tr>
                                            <?php } $ewayBill = $this->session->userdata('eway'); if ($ewayBill == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Eway Bill :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->ewayBill; ?></strong></td>
                                            </tr>
                                            <?php } $duedate = $this->session->userdata('duedate'); if ($duedate == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Due Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->dueDate; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->ptd == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Receipt Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->recieptDate; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->lrdate == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>LR Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->lrDate ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->vahical_no == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Vehicle No :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->vahical_no ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->SalesmanOnPrintOut == 'Yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;">
                                                    <strong>Salesman Name :</strong>
                                                </td>
                                                <td style="width: 50%; text-align: right; border-right: none;">
                                                    <strong><?= get_title('employees', ['Id' => $party->SalesmanId], 'Name'); ?></strong>
                                                </td>
                                            </tr>
                                            <?php } if ($company_ka_data->orderDate == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Order Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->orderDate; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->orderNumber == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Order Number :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->orderNumber; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->dcdate == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>DC Date. :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->dcDate ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->dcno == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>DC No. :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->dcNumber ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->lot_no == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Lot No :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->lot_no; ?></strong></td>
                                            </tr>
                                            <?php } } ?>
                                        </table>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <?php if ($estimateConfig->shipping_address == 'Y') { ?>
                                    <td style="width: 50%; padding: 10px;">
                                        <table style="width: 100%; border-collapse: collapse;font-size: 12px;">
                                            <?php $dispatch_by = $this->session->userdata('dpb'); if ($dispatch_by == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Dispatch By :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= ucwords($result->transport); ?></strong></td>
                                            </tr>
                                            <?php } $lr = $this->session->userdata('lrnumber'); if ($lr == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>LR No :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->lrNumber; ?></strong></td>
                                            </tr>
                                            <?php } $ewayBill = $this->session->userdata('eway'); if ($ewayBill == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Eway Bill :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->ewayBill; ?></strong></td>
                                            </tr>
                                            <?php } $duedate = $this->session->userdata('duedate'); if ($duedate == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Due Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->dueDate; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->ptd == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Receipt Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->recieptDate; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->lrdate == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>LR Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->lrDate; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->vahical_no == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Vehicle No :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->vahical_no; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->orderDate == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Order Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->orderDate; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->orderNumber == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Order Number :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->orderNumber; ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->dcdate == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>DC Date. :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->dcDate ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->dcno == 'yes') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>DC No. :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->dcNumber ?></strong></td>
                                            </tr>
                                            <?php } if ($company_ka_data->lot_no == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Lot No :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= $result->lot_no ?></strong></td>
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </td>
                                    <?php } else { if ($estimateConfig->shipping_address == 'Y') { ?>
                                    <td></td>
                                    <?php } } if ($estimateConfig->shipping_address == 'Y') { ?>
                                    <td style="width: 50%; padding: 10px;">
                                        <table style="width: 100%; border-collapse: collapse;font-size: 12px;">
                                            <?php if ($estimateConfig->invoice_number == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Invoice No :</strong></td>
                                                <td style="width: 50%; text-align: right;color:red; border-right: none;"><strong><?= $result->invoiceNumber; ?></strong></td>
                                            </tr>
                                            <?php } if ($estimateConfig->invoice_date == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Invoice Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;"><strong><?= date('d/m/Y', strtotime($result->invoiceDate)); ?></strong></td>
                                            </tr>
                                            <?php } if ($comp_profile->due_date == 'Yes') { ?>
                                            <tr>
                                                <td style="width: 50%; border-right: none;"><strong>Due Date :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;">
                                                    <strong>
                                                        <?php
                                                            if (!empty($comp_profile->payment_due_period)) {
                                                                echo date('d/m/Y', strtotime($result->invoiceDate . '+ ' . $comp_profile->payment_due_period . 'days')); 
                                                            }
                                                        ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                            <?php } if ($estimateConfig->state == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%; border-right: none;">
                                                    <strong>State of Supply :</strong>
                                                </td>
                                                <td style="width: 50%; text-align: right; border-right: none;">
                                                    <strong>
                                                        <?= get_title('state_list', ['id' => $party->state], 'code'); ?>
                                                        -
                                                        <?= get_title('state_list', ['id' => $party->state], 'state'); ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                            <?php } if ($estimateConfig->payment_mode == 'Y') { ?>
                                            <tr>
                                                <td style="width: 50%;border-right:none;"><strong>Payment Mode :</strong></td>
                                                <td style="width: 50%; text-align: right; border-right: none;">
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
                <tr style="font-size: 13px;">
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
                    <th class="item_name">DESCRIPTION OF GOODS/SERVICE</th>
                    <?php
                        if ($estimateConfig->hsn == 'Y') {
                            echo '<th class="item_hsn">HSN/SAC</th>';
                        }
                        if ($estimateConfig->qty == 'Y') {
                            echo '<th style="class="item_qty">QTY</th>';
                        }
                        if ($estimateConfig->sqrfoot == 'Y') {
                            echo '<th class="item_qty">SqFt</th>';
                        }
                        if ($estimateConfig->discount_invoice == 'Y') {
                            echo '<th class="discount">Discount</th>';
                        }
                        if ($estimateConfig->unit == 'Y') {
                            echo '<th class="unit">UNIT</th>';
                        }
                        if ($estimateConfig->SubUnit == 'Y') {
                            echo '<th class="unit">Sub Unit</th>';
                        }
                        if ($estimateConfig->mrp == 'Y') {
                            echo '<th class="item_mrp">MRP</th>';
                        }
                        if ($estimateConfig->sell_price == 'Y') {
                            echo '<th class="item_rate">RATE</th>';
                        }
                        if ($estimateConfig->tax == 'Y') {
                            echo '<th class="tax">Tax%</th>';
                        }
                        if ($estimateConfig->taxable_amount == 'Y') {
                            echo '<th class="taxable_amount">Taxble Amt</th>';
                        }
                        if ($estimateConfig->taxamount == 'Y') {
                            echo '<th class="tax_amount">Tax Amount</th>';
                        }
                        if ($estimateConfig->net_amount == 'Y') {
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
                            $totalDiscount += (float) get_title('product', ['id' => $row->product_id], 'mrp') * (float) $row->qty;
                ?>
                <tr class="text-center" style="font-size: 12px;">
                    <td><?= $key + 1; ?></td>
                    <td class="text-left">
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
                    <?php if ($estimateConfig->hsn == 'Y') { ?>
                    <td><?= $row->hsn; ?></td>
                    <?php } if ($estimateConfig->qty == 'Y') { ?>
                    <td>
                        <?php
                            if ($estimateConfig->SubUnit == 'Y') {
                                $_subVal = (float) get_title('sub_unit', ['Id' => get_title('product', ['id' => $row->product_id], 'SubUnit')], 'Value');
                                if ($_subVal > 0) {
                                    echo ((float) $row->qty / $_subVal);
                                } else {
                                    echo $row->qty;
                                }
                            } else {
                                echo $row->qty;
                            }
                        ?>
                    </td>
                    <?php } if ($estimateConfig->sqrfoot == 'Y') { ?>
                    <td><?= $row->sqft; ?></td>
                    <?php } if ($estimateConfig->discount_invoice == 'Y') { ?>
                    <td><?= $row->discount; ?>%</td>
                    <?php } if ($estimateConfig->unit == 'Y') { ?>
                    <td><?= get_title('unit', ['id' => get_title('product', ['id' => $row->product_id], 'unit')], 'uqc_code'); ?></td>
                    <?php } if ($estimateConfig->SubUnit == 'Y') { ?>
                    <td><?= get_title('sub_unit', ['Id' => get_title('product', ['id' => $row->product_id], 'SubUnit')], 'SubUnitName'); ?></td>
                    <?php }  if ($estimateConfig->mrp == 'Y') { ?>
                    <td><?= get_title('product', ['id' => $row->product_id], 'mrp'); ?></td>
                    <?php } if ($estimateConfig->sell_price == 'Y') { ?>
                    <td><?= money_formats($row->purchase_price / $row->qty); ?></td>
                    <?php } if ($estimateConfig->tax == 'Y') { ?>
                    <td><?= $row->tax; ?>%</td>
                    <?php } if ($estimateConfig->taxable_amount == 'Y') { ?>
                    <td><?= $row->purchase_price; ?></td>
                    <?php } if ($estimateConfig->taxamount == 'Y') { ?>
                    <td><?= money_formats($row->amount); ?></td>
                    <?php } if ($estimateConfig->net_amount == 'Y') { ?>
                    <td style="text-align:right;"><?= money_formats($row->netAmount); ?></td>
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
                    <?php if ($estimateConfig->hsn == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->qty == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->sqrfoot == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->discount_invoice == 'Y') {  ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->unit == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->SubUnit == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->mrp == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->sell_price == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->tax == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->taxable_amount == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->taxamount == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->net_amount == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } ?>
                </tr>
                <?php } } ?>
                <tr id="page-main">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <?php if ($estimateConfig->hsn == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->qty == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->sqrfoot == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->discount_invoice == 'Y') {  ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->unit == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->SubUnit == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->mrp == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->sell_price == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->tax == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->taxable_amount == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->taxamount == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } if ($estimateConfig->net_amount == 'Y') { ?>
                    <td>&nbsp;</td>
                    <?php } ?>
                </tr>
                <tr style="text-align: center;">
                    <th></th>
                    <th></th>
                    <?php if ($estimateConfig->hsn == 'Y') { ?>
                    <th></th>
                    <?php } if ($estimateConfig->qty == 'Y') { ?>
                    <th class="item_qty" style="text-align: left;"><?= $nosItm; ?></th>
                    <?php } if ($estimateConfig->sqrfoot == 'Y') { ?>
                    <th class="item_qty"></th>
                    <?php } if ($estimateConfig->discount_invoice == 'Y') { ?>
                    <th class="discount"></th>
                    <?php } if ($estimateConfig->unit == 'Y') { ?>
                    <th class="unit"></th>
                    <?php } if ($estimateConfig->SubUnit == 'Y') { ?>
                    <th class="unit"></th>
                    <?php } if ($estimateConfig->mrp == 'Y') { ?>
                    <th></th>
                    <?php } if ($estimateConfig->sell_price == 'Y') { ?>
                    <th></th>
                    <?php } if ($estimateConfig->tax == 'Y') { ?>
                    <th></th>
                    <?php } if ($estimateConfig->taxable_amount == 'Y') { ?>
                    <th style="text-align: left;"><?= money_formats($result->taxableAmount); ?></th>
                    <?php } if ($estimateConfig->taxamount == 'Y') { ?>
                    <th style="text-align: left;"><?= money_formats($result->totalTax); ?></th>
                    <?php } if ($estimateConfig->net_amount == 'Y') { ?>
                    <th style="text-align: right;"><?= money_formats($result->totalValue); ?></th>
                    <?php } ?>
                </tr>
            </tbody>
        </table>
        <table class="item_table" style="border: 1px solid #000;">
            <tbody>
                <tr>
                    <?php if ($estimateConfig->total_summary == 'Y') { ?>
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
                                    $this->db->from('estimate_product');
                                    $this->db->where('invoiceID', $result->id);
                                    $this->db->where('InvoiceNo', $result->invoiceNumber);
                                    $this->db->where('type', 'Sale');
                                    $this->db->group_by('tax');
                                    $tax = $this->db->get()->result();
                                    $tcount = 2;
                                    if (isset($tax) && !empty($tax)) {
                                        foreach ($tax as $row) { 
                                ?>
                                <tr style="font-size: 15px;">
                                    <td class="tax_slab" style="text-align: left; white-space: nowrap;">
                                        <b>GST @<?= $row->tax; ?>% </b>
                                    </td>
                                    <td><?= money_formats($row->purchase_price, 2); ?></td>
                                    <td><?= money_formats($row->sgst, 2); ?></td>
                                    <td><?= money_formats($row->cgst, 2); ?></td>
                                    <td><?= money_formats($row->igst, 2); ?></td>
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
                                <?php if ($estimateConfig->total_taxable_amount == 'Y') { ?>
                                <tr>
                                    <td style="border-bottom: 1px solid; padding: 2px 2px;"><b>TAXABLE</b></td>
                                    <td style="border-bottom: 1px solid;padding: 2px 2px;">
                                        <b style="float:right;">
                                            <i class="fa fa-inr"></i> <?= money_formats($result->taxableAmount); ?>
                                        </b>
                                    </td>
                                </tr>
                                <?php } if ($estimateConfig->total_tax_amount == 'Y') { ?>
                                <tr>
                                    <td style="border-bottom: 1px solid;padding: 2px 2px;"><b>TAX</b></td>
                                    <td style="border-bottom: 1px solid;padding: 2px 2px;">
                                        <b style="float:right;">
                                            <i class="fa fa-inr"></i> <?= money_formats($result->totalTax); ?>
                                        </b>
                                    </td>
                                </tr>
                                <?php } if ($estimateConfig->round_off == 'Y') { ?>
                                <tr>
                                    <td style="border-bottom: 1px solid;padding: 2px 2px; white-space: nowrap;"><b>ROUND OFF</b></td>
                                    <td style="border-bottom: 1px solid;padding: 2px 2px; white-space: nowrap;">
                                        <b style="float:right;">
                                            <i class="fa fa-inr"></i> <?= money_formats($result->roundOff); ?>
                                        </b>
                                    </td>
                                </tr>
                                <?php } if ($estimateConfig->freight_charge == 'Y') { ?>
                                <tr>
                                    <td style="border-bottom: 1px solid; padding: 3px 2px;"><b>FREIGHT CHARGE</b></td>
                                    <td style="border-bottom: 1px solid; padding: 3px 2px;">
                                        <b style="float:right;">
                                            <i class="fa fa-inr"></i> <?= money_formats($result->freight_charge); ?>
                                        </b>
                                    </td>
                                </tr>
                                <?php } if ($estimateConfig->cash_discount == 'Y') { ?>
                                <tr>
                                    <td style="border-bottom: 1px solid; padding: 3px 2px;"><b>CASH DISCOUNT</b></td>
                                    <td style="border-bottom: 1px solid; padding: 3px 2px;">
                                        <b style="float:right;">
                                            <i class="fa fa-inr"></i> <?= money_formats($result->cash_discount); ?>
                                        </b>
                                    </td>
                                </tr>
                                <?php } if ($estimateConfig->grand_total == 'Y') { ?>
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
        <?php if ($estimateConfig->hsnsac_summary == 'Y') { ?>
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
                $this->db->from('estimate_product');
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
                <td style="text-align:right;"><?= $row->tax / 2; ?>%</td>
                <td style="text-align:right;white-space: nowrap;"><i class="fa fa-inr"></i> <?= money_formats($row->cgst, 2); ?></td>
                <td style="text-align:right;"><?= $row->tax / 2; ?>%</td>
                <td style="text-align:right;white-space: nowrap;"><i class="fa fa-inr"></i> <?= money_formats($row->sgst, 2); ?></td>
                <?php } if ($row->igst == "0.00") { ?>
                <td style="text-align:right;">-</td>
                <?php } else { ?>
                <td style="text-align:right;"><?= $row->tax; ?>%</td>
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
            <?php if ($estimateConfig->bank_info == 'Y') { ?>
            <div style="font-weight:bold;font-family:'ArialMt Bold';">
                <?php 
                    $where = ['status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')){
                        $where['CompanyBranchId'] = $this->session->userdata('CompBranchId'); 
                    }
                    $bank = $this->db->get_where('comp_profile', $where)->row();
                    if (isset($bank) && !empty($bank)) {
                ?>
                <div class="bank_account_name">
                    Bank Account Information : <strong><?= $bank->bank_details; ?></strong>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <?php if ($estimateConfig->remark == 'Y') { ?>
        <div class="remarks" style="border: 1px solid #000; min-width: 634px;">
            <div style="font-weight:bold;font-family:'ArialMt Bold';">
                Remarks : <?= $result->remark; ?>
            </div>
        </div>
        <?php } ?>
        <table class="inv_details" cellspacing="0" cellpadding="0" style="border: 1px solid #000; width: 100%;">
            <tbody>
                <tr>
                    <td class="terms">
                        <div class="label" style="font-size:15px;">Terms and Conditions</div>
                        <span style="font-size: 12px; line-height: 1.1;">
                            <?php 
                                $where = ['status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
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
                    <?php if ($estimateConfig->signature == 'Y') { ?>
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
                                    ?>
                                    <?php 
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
                                    <b style="font-size: 12px;">For: <?= $comp_profile->name; ?></b>
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
                                    ?>
                                    <?php 
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
        <?php if ($estimateConfig->footer == 'Y') { ?>
        <div class="footer" style="font-size: 12px;">
            This is a computer generated copy by 24x7Munshi
        </div>
        <?php } } } ?>

        <script type="text/javascript">
            $(document).ready(function() {
                <?php if ($estimateConfig->hsnsac_summary == 'N') { ?>
                if (document.body.scrollHeight < 1000) {
                    document.getElementById("page-main").style.height = document.getElementById("page-main").clientHeight + 150 + 'px';
                }
                <?php } else { ?>
                document.getElementById("page-main").style.height = document.getElementById("page-main").clientHeight + 50 + 'px';
                <?php } ?>
                window.print();
            });

            function closePrintView() {
                window.open('', '_self');
                window.close();
            }
            
            $(document).ready(function() {
                for (var z = 1; z <= 2; z++) {
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