<html>

<head>
	<style data-merge-styles="true"></style>
	<title>UMATIC</title>
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
			/* width: 19.1cm; */
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
			border-top: 0px;
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
			width: 200px;

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
			width: 100px;
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
			table-layout: fixed;
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
			vertical-align: bottom;
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
			width: 75px;
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
			width: 75px;
			text-align: right;
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
			white-space: pre-wrap;
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
	</style>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
</head>

<body onload="window.print()">
	<?php
	$comp_id = $this->session->userdata('comp_id');
	$user_id = $this->session->userdata('user_id');
	$where = ['comp_id' => $comp_id, 'user_id' => $user_id];
	$comp_profile = $this->user_model->getRow('comp_profile', $where);

	if ($comp_profile) {
		$jklmn = $comp_profile->total_invoice;
	?>
		<?php
		for ($jkl = 0; $jkl < $jklmn; $jkl++) {
		?>
			<div class="main_container">
				<?php
				if (!$jkl) {
				?>
					<div class="copy_title text-primary">ORIGINAL</div>

				<?php
				} else {
				?>
					<div class="copy_title text-dark">DUPLICATE</div>

				<?php
				}
				?>
				<div class="container">
					<table border="0" cellspacing="0" class="item_table" id="item_table_1">
						<thead>
							<tr class="hidden_head">
								<th class="item_no"></th>
								<th class="item_name"></th>
								<th class="item_hsn"></th>
								<th class="item_unit"></th>
								<th class="item_qty"></th>
								<th class="item_rate"></th>
								<th class="item_qty"></th>
								<th class="item_rate"></th>
								<th class="item_qty"></th>
								<th class="item_rate"></th>
								<!-- <th class="item_taxable"></th>
                        <th class="item_tax_perc_only"></th> -->
							</tr>
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
												<img src="<?= base_url() ?>uploads/complogo/<?= $comp_profile->logo ?>" style="width:150px;height100px;float:<?= $log_postion ?>;text-align:<?= $log_postion ?>;">
											<?php
											}
											?>
											<div style="float:<?= $comp_details_position ?>;text-align:<?= $comp_details_position ?>">
												<div class="name" style="font-size: 15px;"><?= $comp_profile->name ?> </div>
												<div class="address" style="font-size: 15px;"><?= $this->session->userdata('address') ?></div>
												<div class="contact_no" style="font-size: 15px;"><?= $this->session->userdata('numbers') ?></div>
												<div class="email" style="font-size: 15px;"><?= $this->session->userdata('email') ?></div>
												<div class="website" style="font-size: 15px;">www.umatic.in</div>
												<div class="gst_no" style="font-size: 15px;"><?= $this->session->userdata('gst_nos') ?></div>

											</div>
										</div>
										<div class="invoice_title"><b><?= $comp_profile->title_invoice ?></b></div>
										<?php if ($result->customerId != '' && $result->customerId != 0) {
											$party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
										}
										?>
										<div class="invoice_details">
											<div class="billing_address" style="width:350px">
												<div class="address_title">Billing Address,</div>
												<div class="name">Name:<?= $party->name ?></div>
												<div class="address">Address:<?= $result->customerAddress ?></div>
												<div class="gst_no">GST NO:<?= $result->gstNo ?></div>
												<div class="contact">

													<b>Contact Details:<?= $result->customerMobile ?>, <?= $result->customerEmail ?>

												</div>

											</div>
											<table border="0" class="inv_rows">

												<tbody>
													<tr>
														<!-- <th>Transit Ex</th>
                                                <td class="ad"></td> -->
														<th>Invoice No</th>
														<td><?= $result->invoiceNumber ?></td>
													</tr>
													<tr>
														<th>Invoice Date</th>
														<td><?= date('d/m/Y', strtotime($result->added_at)); ?></td>
													</tr>
													<tr>
														<th>State of Supply</th>
														<td><?= get_title('state_list', ['id' => $party->state], 'state'); ?></td>
													</tr>
													<tr>
														<th>Payment Mode</th>
														<td>Cash</td>
													</tr>
													<tr>
														<th><b>Order Date</b></th>
														<td><?= $result->orderDate ? date('d/m/Y', strtotime($result->orderDate)) : '' ?></td>
													</tr>
													<tr>
														<th><b>Order No</b></th>
														<td><?= $result->orderNumber ?></td>
													</tr>
													<?php
													$dispatch_by = $this->session->userdata('dpb');
													if ($dispatch_by == 'yes') {
													?>
														<tr>
															<th><b>Dispatch By</b></th>
															<td><?= ucwords($result->transport) ?></td>
														</tr>
													<?php
													}
													$lr = $this->session->userdata('lrnumber');
													if ($lr == 'yes') {
													?>
														<tr>
															<th><b>LR No</b></th>
															<td><?= $result->lrNumber ?></td>
														</tr>

													<?php
													}


													$ewayBill = $this->session->userdata('eway');
													if ($ewayBill == 'yes') {
													?>
														<tr>
															<th><b>Eway Bill</b></th>
															<td><?= $result->ewayBill ?></td>
														</tr>
													<?php
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</td>
							</tr>

							<tr>
								<th class="item_no">SL</th>
								<th class="item_name">ITEM NAME</th>
								<th class="item_hsn">HSN/SAC</th>
								<th class="item_qty">QTY</th>
								<th class="item_unit">MRP</th>
								<th class="item_rate">RATE</th>
								<?php
								// $discount = $this->session->userdata('discount');
								// if ($discount == 'yes') {
								?>
								<!-- <th class="item_rate">DIS</th> -->
								<?php
								// }
								?>
								<!-- <th class="border-right">CD</th> -->

								<th class="">Taxble Amt</th>
								<th class="tax">Tax%</th>

								<th class="">Tax AMOUNT</th>
								<th>NetAmount</th>
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
										<td><?= get_title('product', ['id' => $row->product_id], 'p_name'); ?> <small>
												<?php
												if ($row->product_dec) {
												?>
													<i>(<?= $row->product_dec ?>)</i>
												<?php
												}
												?>
												<?php if ($row->serials) {
													echo '(';
													foreach ($row->serials as $key => $value) {
														if (sizeof($row->serials) - 1 > $key) {
															echo $value->serial_no . ',';
														} else {
															echo $value->serial_no;
														}
													}
													echo ')';
												} ?></small></td>
										<td><?= $row->hsn ?></td>
										<td><?= $row->qty ?></td>
										<td><?= get_title('product', ['id' => $row->product_id], 'mrp') ?></td>
										<td><?= money_formats($rate) ?></td>
										<td>
											<?php
											echo $row->purchase_price;
											?>
										</td>
										<td><?= $row->tax ?>%</td>
										<td>
											<?= money_formats($row->amount) ?>
										</td>
										<td>
											<?= money_formats($row->netAmount); ?>
										</td>
										<!-- <td></td> -->
									</tr>

							<?php
									$counts = $key;
								}
							}
							if (isset($counts) && !empty($counts)) {
								$lines = $lines - $counts;
							} else {
								$lines = $lines - $counts;
							}
							?>
							<?php if ($lines > 0) {
								for ($i = 0; $i < $lines; $i++) { ?>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<!-- <td>&nbsp;</td> -->

										<!-- <td></td> -->
									</tr>
							<?php }
							} ?>
							<tr style="text-align: center;">
								<!-- <th></th> -->
								<th></th>
								<th></th>
								<th></th>

								<th class="item_qty"><?= $nosItm ?></th>
								<th></th>
								<th></th>


								<th><?= money_formats($result->taxableAmount) ?></th>
								<th></th>
								<th>
									<?= money_formats($result->taxableAmount) ?>
								</th>
								<th><?= money_formats($result->total) ?></th>
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
												<th class="tax_summary_details">TAXABLE</th>
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
													<tr style="font-size: 18px;">
														<td class="tax_slab" style="text-align:left"><?= $row->tax; ?>%</td>
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
									<div class="summary_label">Taxable</div>
									<div class="summary_value text-center"><i class="fa fa-inr"></i><?= money_formats($result->taxableAmount) ?></div>
									<div class="summary_label">Tax</div>
									<div class="summary_value text-center"><i class="fa fa-inr"></i><?= money_formats($result->totalTax) ?></div>

									<div class="summary_label">ROUND OFF</div>
									<div class="summary_value text-center"><i class="fa fa-inr"></i><?= money_formats($result->roundOff) ?></div>
									<div class="summary_label">Grand Total</div>
									<div class="summary_value text-center"><i class="fa fa-inr"></i> <?= money_formats($result->total) ?></div>

								</td>

							</tr>

						</tbody>
					</table>
					<div class="amount_word"><?= ucwords($result->amountInWords) ?>/-</div>
					<div class="bank_info">
						<div style="font-weight:bold;font-family:'ArialMt Bold'">Bank Account Information :</div>

						<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
						if (isset($bank) && !empty($bank)) {
						?>
							<div class="bank_account_name"><?= $bank->bank_details; ?> </div>
						<?php
						}
						?>
					</div>

					<table class="inv_details" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td class="terms">
									<div class="label" style="font-size: 20px;">Terms and Conditions</div>
									<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
									if (isset($bank) && !empty($bank)) {
									?>
										<span style="font-size: 18px;"><?= $bank->content; ?></span>
									<?php
									}
									?>

								</td>
								<td class="customer_signature">
									<div class="label"><?= $comp_profile->name ?></div>
									<br>
									Customer Signature
								</td>
								<!-- <td class="author"><img src="https://umaticexpert.com/data/images/sale_5c25bc6a9fe68.png">
                            <div>Authorised Signature</div>
                        </td> -->
							</tr>
						</tbody>
					</table>
				</div>
				<div class="footer">This is a Computer Generated Copy Generated by Umatic</div>
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