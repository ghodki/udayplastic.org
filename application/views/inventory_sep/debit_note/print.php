<html>

<head>
	<style data-merge-styles="true"></style>
	<title>Uday Plastic</title>
	<link rel="stylesheet" href="<?= base_url(); ?>plugins/fontawesome-free/css/all.min.css" />
	<style>
		body {
			font-family: sans-serif;
			margin: 0px;
			font-size: 12px;
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
			font-size: 12px;

		}

		.gstin,
		.pan,
		.cin {
			font-size: 15px;
			/*font-family: 'ArialMt Bold';*/
			font-weight: bold;
			display: block;
		}

		.invoice_title {
			text-align: center;
			padding: 3px;
			font-size: 15px;
			/*font-family: 'ArialMt Bold';*/
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
			/*font-family: 'ArialMt Bold';*/
			font-weight: bold;
		}

		.invoice_details .gstin,
		.invoice_details .pan,
		.invoice_details .cin {
			font-size: 16px;
			/*font-family: 'ArialMt Bold';*/
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
			/*font-family: 'ArialMt Bold';*/
			font-weight: bold;
			width: 150px;
		}

		.inv_rows td.ad {}

		.inv_rows th:after {
			content: ' : ';
			/*font-family: 'ArialMt Bold';*/
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
			/* table-layout: fixed; */
			/* word-break: break-all; */
		}

		.item_table th {
			border-top: 1px solid;
			border-bottom: 1px solid;
			/*font-family: 'ArialMt Bold';*/
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
			/* width: 40px; */
			text-align: center;
			vertical-align: top;
		}

		.item_table .item_code {
			/* width: 75px; */
			text-align: center;
		}

		.item_table .item_hsn {
			/* width: 84px; */
			text-align: center;
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
			/* width: 40px; */
			text-align: center;
		}

		.item_table .item_qty {
			/* width: 104px; */

		}

		.item_table .item_rate {
			/* width: 85px; */
			/* text-align: right; */
		}

		.item_table .tax {
			/* width: 75px; */
			text-align: right;
		}

		.item_table .item_mrp {
			/* width: 75px; */
			text-align: right;
		}

		.item_table .item_discount {
			/* width: 70px; */
			text-align: right;
		}

		.item_table .item_tax_amount {
			/* width: 75px; */
			text-align: right;
		}

		.item_table .item_tax_perc {
			/* width: 25px; */
			text-align: center;
		}

		.item_table .item_tax_perc_only {
			/* width: 40px; */
			text-align: center;
		}

		.item_table .item_tax_type {
			/* width: 45px; */
			text-align: center;
		}

		.item_table .item_taxable {
			/* width: 75px; */
			text-align: right;
		}

		.item_table .item_total {
			/* width: 75px; */
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
			/*font-family: 'ArialMt Bold';*/
			font-weight: bold;
			font-size: 16px;
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
			width: 97%;
			/*font-family: 'ArialMt Bold';*/
			font-weight: bold;
			font-size: 14px;
			padding-left: 10px;
			border-bottom: 1px solid;
			line-height: 22px;
		}

		.total_summary .summary_value {
			display: inline-block;
			width: 46%;
			/*font-family: 'ArialMt Bold';*/
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
			/*font-family: 'ArialMt Bold';*/
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
			width: 300px;
		}

		.inv_details .terms .label,
		.inv_details .remarks .label {
			/*font-family: 'ArialMt Bold';*/
			font-weight: bold;
		}

		.inv_details .customer_signature,
		.inv_details .author {
			vertical-align: bottom;
			text-align: center;
			width: 300px;
		}

		.inv_details .receiver_signature,
		.inv_details .author {
			vertical-align: bottom;
			text-align: center;
			width: 100px;
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

<!--<body onload="window.print()">-->

<body>

	<?php
	$product_field = 0;
	$comp_id = $this->session->userdata('comp_id');
	$user_id = $this->session->userdata('user_id');
        $CompBranchId = $this->session->userdata('CompBranchId');
        
	$where = ['comp_id' => $comp_id, 'user_id' => $user_id];
        if ($this->session->userdata('BranchRole')) {
            $where['CompanyBranchId'] = $CompBranchId;
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


	$a5config = $this->user_model->getRow('debit_note_config', $where);

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

				<?php

				if ($jkl == 0) {
				?>
					<div class="copy_title text-primary" style="margin-right:34px;">ORIGINAL</div>

				<?php
				} elseif ($jkl == 1) {
				?>
					<div class="copy_title text-dark" style="margin-right:34px;">DUPLICATE</div>

				<?php
				} else {
				?>
					<div class="copy_title text-dark" style="margin-right:34px;">TRIPLICATE</div>

				<?php
				}
				?>

				<div class="container">
					<table border="0" cellspacing="0" class="item_table" id="item_table_1">
						<thead>

							<tr>
								<td style="padding:0px;" colspan="13">
									<div class="header_content">
										<?php
										$log_postion = $comp_profile->logo_position;
										$logo_status = $comp_profile->logo_status;
										$comp_details_position = $comp_profile->comp_details_position;
                                                                                if ($this->session->userdata('BranchRole')) {
                                                                                    $CompanyBranch = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId, 'CompId' => $comp_id, 'BranchUserId' => $user_id]);
                                                                                } else {
                                                                                    $inventory_billing = $this->user_model->getRow('inventory_billing', ['id' => $comp_id, 'user_id' => $user_id]);
                                                                                }

										?>
										<div class="header" style="text-align:<?= $log_postion ?>">
											<?php
											if ($logo_status == 'enable' && $log_postion) {
											?>
												<img src="<?= base_url() ?>uploads/complogo/<?= $comp_profile->logo ?>" style="width: <?= $comp_profile->logo_width ?>px;height: <?= $comp_profile->logo_height ?>px;float:<?= $log_postion ?>;text-align:<?= $log_postion ?>;">
											<?php
											}
											?>
											<div style="float:<?= $comp_details_position ?>;text-align:<?= $comp_details_position ?>;margin-top:-28px;"">
												<?php
												if ($a5config->comp_name == 'Y') {
												?>
													<div class=" name" style="font-size:16;margin-bottom: -20px;">
												<h2 style=" margin-top: 23px; "><strong><?= $comp_profile->name ?></strong></h2>
											</div>
										<?php
												}
										?>
										<?php
										if ($a5config->comp_address == 'Y') {
										?>
                                                                                        <div class="address" style="font-size: 18px;"><?= $this->session->userdata('BranchRole') ? $CompanyBranch->Address : $inventory_billing->address ?></div>
										<?php
										}
										?>
										<?php
										if ($a5config->comp_mobile_number == 'Y') {
										?>
											<div class="contact_no" style="font-size: 18px;"><?= $this->session->userdata('BranchRole') ? $CompanyBranch->Mobile : $inventory_billing->number ?></div>
										<?php
										}
										?>
										<?php
										if ($a5config->comp_email == 'Y') {
										?>
											<div class="email" style="font-size: 18px;"><?= $this->session->userdata('BranchRole') ? $CompanyBranch->Email : $inventory_billing->email ?></div>
										<?php
										}
										?>
										<?php
										if ($a5config->comp_gst == 'Y') {
										?>
											<div class="gst_no" style="font-size: 18px;color:red;"><b style="color:black;">GSTIN:<?= $this->session->userdata('BranchRole') ? $CompanyBranch->GST : $inventory_billing->gst_no ?></b></div>
										<?php
										}
										?>
                                                                            </div>
									</div>
                                                                        <?php 
                                                                            if ($result->customerId != '' && $result->customerId != 0) {
                                                                                $party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
                                                                            }
                                                                        ?>
                                                                        <table style="width: 100%; border-collapse: collapse;font-size: 16px;">
                                                                            <?php if ($a5config->title) { ?>
                                                                            <tr>
                                                                                <td colspan="2" style="text-align: center; border-bottom: 1px solid #000; border-top: 1px solid #000;">
                                                                                    <h3 style="margin: 0px;"><?= $a5config->title ?></h3>
                                                                                </td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                            <tr>
                                                                                <td style="width: 50%; padding: 10px;border-bottom: 1px solid #000;line-height:15px;">
                                                                                    <h6 style=" margin-bottom: auto; "><strong>Billing Address,</strong></h6>
                                                                                    <?php if ($a5config->customer_name == 'Y') { ?>
                                                                                    <h6 style="text-transform: uppercase; margin: 5px 0 5px 0;;font-size:15px;">
                                                                                        <strong><?= $party->name ?></strong>
                                                                                    </h6>
                                                                                    <?php } if ($a5config->customer_address == 'Y') { ?>
                                                                                    <p style="margin: 5px 0 5px 0;font-size:12px;">
                                                                                        <?= $party->address_1; ?>&nbsp;<?= $party->area; ?>
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
                                                                                    <p style="margin: 0 0 5px 0;color:red;"><strong>GSTIN: <?= $party->gst; ?></strong></p>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <?php if ($a5config->shipping_address == 'Y') { ?>
                                                                                <td style="width: 50%; padding: 10px; border-left: 1px solid #000; border-bottom: 1px solid #000;">
                                                                                    <h5 style="margin: 0 0 5px 0;"><strong>Shipping Address,</strong></h5>
                                                                                    <p style="text-transform: uppercase; margin: 0 0 5px 0;"><?= $result->shipping_address ?></p>
                                                                                </td>
                                                                                <?php } else { ?>
												<td style="width: 50%; padding: 10px;">
													<table style="width: 100%; border-collapse: collapse;">
														<?php
														if ($a5config->invoice_number == 'Y') {
														?>
															<tr>
																<td style="width: 50%;border-right:none;"><strong>Invoice No :</strong></td>
																<td style="width: 50%; text-align: right;color:red;"><strong><?= $result->invoiceNumber ?></strong></td>
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

														if ($counter > 4 || $a5config->shipping_address == 'N') {

														?>



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


															if ($company_ka_data->ptd == 'yes') {
															?>
																<tr>
																	<td style="width: 50%;border-right:none;"><strong>Reciept Date :</strong></td>
																	<td style="width: 50%; text-align: right;"><strong><?= $result->recieptDate ?></strong></td>
																</tr>
															<?php


															}


															if ($company_ka_data->lrdate == 'yes') {
															?>
																<tr>
																	<td style="width: 50%;border-right:none;"><strong>Lrdate :</strong></td>
																	<td style="width: 50%; text-align: right;"><strong><?= $result->lrDate ?></strong></td>
																</tr>
															<?php


															}
															if ($company_ka_data->vahical_no == 'yes') {
															?>
																<tr>
																	<td style="width: 50%;border-right:none;"><strong>Vehicle No :</strong></td>
																	<td style="width: 50%; text-align: right;"><strong><?= $result->vahical_no ?></strong></td>
																</tr>
															<?php


															}

															if ($company_ka_data->orderDate == 'Y') {
															?>
																<tr>
																	<td style="width: 50%;border-right:none;"><strong>OrderDate :</strong></td>
																	<td style="width: 50%; text-align: right;"><strong><?= $result->orderDate ?></strong></td>
																</tr>
															<?php

															}
															if ($company_ka_data->orderNumber == 'Y') {
															?>
																<tr>
																	<td style="width: 50%;border-right:none;"><strong>OrderNumber :</strong></td>
																	<td style="width: 50%; text-align: right;"><strong><?= $result->orderNumber ?></strong></td>
																</tr>
															<?php

															}
															if ($company_ka_data->lot_no == 'Y') {
															?>
																<tr>
																	<td style="width: 50%;border-right:none;"><strong>Lot No :</strong></td>
																	<td style="width: 50%; text-align: right;"><strong><?= $result->lot_no ?></strong></td>
																</tr>
															<?php


															}

															?>





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
											if ($a5config->shipping_address == 'Y') {

											?>
												<td style="width: 50%; padding: 10px;">
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


														if ($company_ka_data->ptd == 'yes') {
														?>
															<tr>
																<td style="width: 50%;border-right:none;"><strong>Reciept Date :</strong></td>
																<td style="width: 50%; text-align: right;"><strong><?= $result->recieptDate ?></strong></td>
															</tr>
														<?php


														}


														if ($company_ka_data->lrdate == 'yes') {
														?>
															<tr>
																<td style="width: 50%;border-right:none;"><strong>Lrdate :</strong></td>
																<td style="width: 50%; text-align: right;"><strong><?= $result->lrDate ?></strong></td>
															</tr>
														<?php


														}
														if ($company_ka_data->vahical_no == 'yes') {
														?>
															<tr>
																<td style="width: 50%;border-right:none;"><strong>Vehicle No :</strong></td>
																<td style="width: 50%; text-align: right;"><strong><?= $result->vahical_no ?></strong></td>
															</tr>
														<?php


														}

														if ($company_ka_data->orderDate == 'Y') {
														?>
															<tr>
																<td style="width: 50%;border-right:none;"><strong>OrderDate :</strong></td>
																<td style="width: 50%; text-align: right;"><strong><?= $result->orderDate ?></strong></td>
															</tr>
														<?php

														}
														if ($company_ka_data->orderNumber == 'Y') {
														?>
															<tr>
																<td style="width: 50%;border-right:none;"><strong>OrderNumber :</strong></td>
																<td style="width: 50%; text-align: right;"><strong><?= $result->orderNumber ?></strong></td>
															</tr>
														<?php

														}
														if ($company_ka_data->lot_no == 'Y') {
														?>
															<tr>
																<td style="width: 50%;border-right:none;"><strong>Lot No :</strong></td>
																<td style="width: 50%; text-align: right;"><strong><?= $result->lot_no ?></strong></td>
															</tr>
														<?php


														}

														?>





														<?php

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
																<td style="width: 50%; text-align: right;color:red;"><strong><?= $result->invoiceNumber ?></strong></td>
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
																		if ($result->voucherType == 2) {
																			echo "Cash";
																		} elseif ($result->voucherType == 1) {
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
								<th class="item_name" style="width:<?= $width ?>px;">DESCRIPTION OF GOODS</th>
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

								if ($a5config->sqrfoot == 'Y') {
									echo '<th class="item_qty">SqFt</th>';
								} else {
									echo '<th style="border-right:0px;"></th>';
								}
								if ($a5config->discount_invoice == 'Y') {
									echo '<th class="discount">Discount</th>';
								} else {
									echo '<th style="border-right:0px;"></th>';
								}

								if ($a5config->unit == 'Y') {
									echo '<th class="unit">UNIT</th>';
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
										<td class="text-left"><?php

																$product_name = get_title('product', ['id' => $row->product_id], 'p_name');
																if ($product_name) {
																	echo $product_name;
																} else {
																	echo $product_name;
																}
																if ($row->product_dec) {
																?>
												<br>
												<div class="descc"><?= $row->product_dec ?></div>
											<?php
																}
											?>


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
										if ($a5config->sqrfoot == 'Y') {
										?>
											<td><?= $row->sqft ?></td>
										<?php
										} else {
											echo '<td style="border-right:0px;border-bottom:0px;"></td>';
										}
										?>
										<?php
										if ($a5config->discount_invoice == 'Y') {
										?>
											<td><?= $row->discount ?>%</td>
										<?php
										} else {
											echo '<td style="border-right:0px;border-bottom:0px;"></td>';
										}
										?>
										<?php
										if ($a5config->unit == 'Y') {
										?>
											<td><?= get_title('product', ['id' => $row->product_id], 'unit_name') ?></td>

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
											<td><?= money_formats($row->purchase_price / $row->qty) ?></td>
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
											// }else
											// {
											// 	echo '<td style="border-right:0px;border-bottom:0px;"></td>';

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

							if (isset($counts) && !empty($counts)) {

								// $count=count($counts);

								$lines = $lines - $counts;
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
										if ($a5config->sqrfoot == 'Y') {
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
										if ($a5config->discount_invoice == 'Y') {
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
										if ($a5config->unit == 'Y') {
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

										if ($a5config->taxamount == 'Y') {
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
								if ($a5config->sqrfoot == 'Y') {
								?>
									<th class="item_qty"></th>
								<?php
								} else {
									echo '<th style="border-right:0px;"></th>';
								}
								if ($a5config->discount_invoice == 'Y') {
								?>
									<th class="discount"></th>
								<?php
								} else {
									echo '<th style="border-right:0px;"></th>';
								}

								if ($a5config->unit == 'Y') {
								?>
									<th class="unit"></th>
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
								if ($a5config->tax == 'Y') {
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

									<th><?= money_formats($result->totalValue) ?></th>
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
								<?php
								if ($a5config->total_summary == 'Y') {
								?>
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
												$this->db->select_sum('purchase_price');
												$this->db->select_sum('sgst');
												$this->db->select_sum('cgst');
												$this->db->select_sum('igst');
												$this->db->select_sum('taxAmount');
												$this->db->from('invoice_product');
												$this->db->where('invoiceID', $result->id);
												$this->db->where('InvoiceNo', $result->invoiceNumber);
												$this->db->where('type', 'Purchase');
												$this->db->group_by('tax');
												$tax = $this->db->get()->result();
												$tcount = 2;
												if (isset($tax) && !empty($tax)) {
													foreach ($tax as $row) { ?>
														<tr style="font-size:20px;">
															<td class="tax_slab" style="text-align:left"><b>GST @<?= $row->tax; ?>%</b></td>
															<td><?= ($row->purchase_price) ?></td>
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
								<?php
								}
								?>
								<td class="total_summary">
									<?php
									if ($a5config->total_taxable_amount == 'Y') {
									?>
										<div class="summary_label" style="clear: both;">
											<span style="float:left;">TAXABLE</span>
											<span style="float:right;"><i class="fa fa-inr"></i><?= money_formats($result->taxableAmount) ?></span>

										</div>
									<?php
									}
									?>
									<?php
									if ($a5config->total_tax_amount == 'Y') {
									?>
										<div class="summary_label" style="clear: both;">
											<span style="float:left;">TAX</span>
											<span style="float:right;"><i class="fa fa-inr"></i><?= money_formats($result->totalTax) ?></span>

										</div>

									<?php
									}
									?>
									<?php
									if ($a5config->round_off == 'Y') {
									?>
										<div class="summary_label" style="clear: both;border-bottom: 1px solid;">
											<span style="float:left;">ROUND OFF</span>
											<span style="float:right;"><i class="fa fa-inr"></i><?= money_formats($result->roundOff) ?></span>

										</div>

									<?php
									}
									?>
									<?php
									if ($a5config->grand_total == 'Y') {
									?>

										<div class="summary_label" style="clear: both;border-bottom:none;">
											<span style="float:left;">GRAND TOTAL</span>
											<span style="float:right;"><i class="fa fa-inr"></i><?= money_formats($result->total) ?></span>

										</div>
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

							<?php
                                                            $bankWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                                            if ($this->session->userdata('BranchRole')) {
                                                                $bankWhere['CompanyBranchId'] = $CompBranchId;
                                                            }
                                                            $bank = $this->db->get_where('comp_profile', $bankWhere)->row();
                                                            if (isset($bank) && !empty($bank)) {
							?>
                                                        <div class="bank_account_name"><strong><?= $bank->bank_details; ?> </strong></div>
						<?php
							}
						}
						?>
					</div>
					<table class="inv_details" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td class="terms">
									<div class="label" style="font-size:17px;margin-bottom:-13px;">Terms and Conditions</div>
                                                                        <?php 
                                                                            $contentWhere = ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                                                                            if ($this->session->userdata('BranchRole')) {
                                                                                $contentWhere['CompanyBranchId'] = $CompBranchId;
                                                                            }
                                                                            $candccontent = $this->db->get_where('comp_profile', $contentWhere)->row();
									if (isset($candccontent) && !empty($candccontent)) {
									?>
										<span style="font-size:14px;line-height: 1.1;"><?= $candccontent->content; ?></span>
									<?php
									}
									?>

								</td>
								<?php
								if ($a5config->signature == 'Y') {
								?>
									<td class="receiver_signature" style="font-size:20px;">

										<div>
											<p>Receiver Signatures</p>
										</div>
									</td>
								<?php
								}
								?>

								<td class="customer_signature" style="font-size:16px;">
									<div class="label"><b>For:<?= $comp_profile->name ?></b></div>
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
										?>
											<img src="<?= base_url() ?>uploads/signature/<?= $bank->scan_signature ?>" style=" width: <?= $signature_width ?>px; height: <?= $signature_height ?>px;">
									<?php
										}
									}
									?>
									<br>
									Authorised Signature
								</td>

							</tr>
						</tbody>
					</table>
				</div>

				<?php
				if ($a5config->footer == 'Y') {
				?>
					<div class=" footer" style="font-size:20px;">This is a Computer Generated Copy by Uday Plastic
					</div>
				<?php
				}
				?>
				<span style="float:right;">Continue..</span>
			</div>
	<?php
		}
	}
	?>



	<script>
		$(document).ready(function() {
			window.print();
			// 			setTimeout("closePrintView()", 1000);
		});

		function closePrintView() {
			// 			document.location.href = "<?= base_url('purchase/purchase_report') ?>";
			window.open('', '_self')
			window.close()
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
			// 			window.print();
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