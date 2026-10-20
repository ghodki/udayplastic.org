<html>

<head>
	<title>Uday Plastic</title>
	<style>
		@page {
			/* size: A4; */
			margin: 0;

		}

		/* @page {
			size: 7in 9.25in;
			margin: 27mm 16mm 27mm 16mm;
		} */

		.text-right {
			text-align: right;
		}

		.container {
			margin: 3em;
			/* margin-top: 20%; */
			padding-top: 5em;
			padding-left: 2em;
			margin-bottom: 5em;
		}

		li {
			line-height: 18px;
			margin-left: 0px;
		}

		div {
			font-size: 25px;
		}

		ol {
			margin-block-start: 0PX;
			padding-inline-start: 20px;
		}

		.outer {
			font-size: 100%;
			table-layout: fixed;
			width: 100%;
			border: 1px solid #000;
			border-collapse: collapse;
			font-size: 20px;
		}

		td {
			line-height: 23px;
			padding: 0px;
			/* padding-left: .25em; */
		}

		tr {
			font-size: 16px;
		}

		.mytable th {
			border-bottom: 1px solid #000;
			border-top: 1px solid #000;
			border-collapse: collapse;
		}

		.mytable {
			border-collapse: collapse;
			width: 100%;
			font-size: 12px
		}

		.text-uppercase {
			text-transform: uppercase;
			margin-top: 1px;
			margin-bottom: 0px;
		}

		.border-right {
			border-right: 1px solid #000 !important;
		}

		.border-left {
			border-left: 1px solid #000;
		}

		.border-bottom {
			border-bottom: 1px solid #000 !important;
		}

		.border-top {
			border-top: 1px solid #000;
		}

		.text-center {
			text-align: center;
		}

		small {
			font-size: 10px;
			line-height: 11px;
		}

		.gstTable {
			width: 100%;
			border: 1px solid #000;
			border-collapse: collapse;
		}

		/* .gstTable td {
			/* border: 1px solid #000; */
		/* } */

		.blankLine {
			line-height: 30px;
		}

		@media print {

			body {
				font-size: 20px;
			}
		}

		tr td span b p {
			/* line-height: 10px; */
			margin-bottom: -1px;

		}

		tr td span b strong {
			/* line-height: 10px; */
			margin-bottom: -1px;

		}

		tr td span b h1 {
			/* line-height: 10px; */
			margin-bottom: -1px;

		}

		tr td span b h2 {
			/* line-height: 10px; */
			margin-bottom: -1px;

		}

		tr td span b h3 {
			/* line-height: 10px; */
			margin-bottom: -1px;

		}

		tr td span b h4 {
			/* line-height: 10px; */
			margin-bottom: -1px;

		}

		tr td span b h5 {
			/* line-height: 10px; */
			margin-bottom: -1px;

		}

		tr td span b h6 {
			/* line-height: 10px; */
			margin-bottom: -1px;

		}

		/* .conatiner {
			display: table;
		}

		#pageFooter {
			display: table-footer-group;
		}

		#pageFooter:after {
			counter-increment: <?= $pages ?>;
			content: counter(<?= $pages ?>);
		} */
	</style>
</head>

<!-- <body style="font-size:12px;"> -->

<body style="font-size:40px;" onload="window.print()">
	<?php if ($result->customerId != '' && $result->customerId != 0) {
		$party = $this->db->get_where('parties', ['id' => $result->customerId])->row();
		$info = $this->db->get('business_details')->row();
	}
	// $total_inv
	// $total_invoice=[];
	$comp_id = $this->session->userdata('comp_id');
	$user_id = $this->session->userdata('user_id');
	$where = ['comp_id' => $comp_id, 'user_id' => $user_id];
	$comp_profile = $this->user_model->getRow('comp_profile', $where);
	// echo '<pre>';
	// print_r($comp_profile);
	// die;
	// echo $this->session->userdata('format_of_bill');
	// die;
	if ($comp_profile) {
		$jklmn = $comp_profile->total_invoice;
	?>
		<?php
		for ($jkl = 0; $jkl < $jklmn; $jkl++) {
		?>
			<div class="container" style="margin-bottom:105%">
				<h1 style="text-align:center;margin:0px;">GST-Invoice</h1>




				<table class="outer" style="width:384px;text-align:center;margin:auto;">

					<tbody class="tblbody">
						<!-- <thead> -->
						<tr>
							<td width="100%" class="border-right border-bottom">

								<?php $bank = $this->db->get_where('comp_profile', ['status' => 1, 'user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')])->row();
								if (isset($bank) && !empty($bank)) {
								?>
									<!--  <div class="col-md-6" style="display:inline-block; width:40%; padding-bottom:1px;"> -->
									<span><b><?= $bank->name; ?></b></span>
									<span>Address:<?php echo $this->session->userdata('address'); ?></span>
									<br>
									<span>Phone:<?php echo $this->session->userdata('numbers'); ?></span>
									<br>
									<span>GST:<?php echo $this->session->userdata('gst_nos'); ?></span>



									<!-- <span><?= $bank->bank_details; ?></span> -->
									<!-- <br> -->
									<!-- </div> -->

									<!-- <div class="col-md-6" style="display:inline-block; width:40%; padding-bottom:1px;"><span><?= $bank->name; ?></span></div> -->


								<?php
								}
								?>
								<!-- <b>Buyer Details</b><br> -->

							</td>

						</tr>


						<!-- <tr> -->
						<tr class="border-bottom border-right" style="text-align:left !important;">
							<td width="70%" class="border-bottom" colspan="2">
								<b>Buyer Details</b><br>
								<b><?= $result->customerId != '' && $result->customerId != 0 ?  $party->name : $result->customerName ?></b><br>

								<b>Contact Details </b> <?= isset($party) ? $party->contact : $result->customerMobile ?>,
						</tr>

						<tr style="width:100px;" class="border-right">
							<td class="border-right border-bottom">
								<span>Invoice:<b><?= $result->invoiceNumber ?></b></span>
								<span style="margin-left:75px;">Date:<b><?= $result->invoiceDate; ?></b></span>


							</td>
							<!-- <td class="border-right border-bottom">Date</td> -->

						</tr>
						<!-- </tr> -->
						<!-- </thead> -->
						<style>
							.mytable tr {
								height: 40px
							}

							.mytable tr:last-child {
								height: auto;
							}
						</style>
						<tr>
							<td style="vertical-align:text-top" colspan="2">
								<table class="mytable" style="margin:0px;">
									<thead>
										<tr style="background-color: #999999; -webkit-print-color-adjust: exact; font-size:14px">
											<th style="width:25px">S/N</th>
											<th>Item</th>

											<!-- <th class="border-right">HSN</th>
											<th class="border-right" style="width:30%">PARTICULAR</th>
											<th class="border-right" style="width:40px">GST</th> -->
											<th class="border-right" style="width:50px">QTY</th>
											<th class="border-right">RATE</th>
											<!-- <th class="border-right">DIS</th> -->
											<!-- <th class="border-right">CD</th> -->
											<!-- <th class="border-right">Taxable Amt</th>

											<th>AMOUNT</th> -->

											<th>NetAmount</th>

										</tr>
									</thead>
									<tbody style="font-size:25px;">
										<?php
										$nosItm = 0;
										$lines = 6;
										$counts = 0;
										$totalDiscount = 0;
										$totalRate = 0;
										$totalCD = 0;
										// print_r($result);
										// die;
										if ($result->product) {
											foreach ($result->product as $key => $row) {
												$qtty = $row->qty;
												$nosItm += $qtty;

												$taxIte =  get_title('product', ['id' => $row->tax], 'tax');
												$rate = $row->rate;
												// $trheight=40;
										?>
												<tr style="vertical-align:top;">
													<td class="border-right" style="text-align:center"><?= $key + 1 ?></td>
													<!-- <td class="border-right text-right" style="text-align:center"><?= $row->hsn ?></td> -->
													<td class="border-right" style="text-align:center"><?= get_title('product', ['id' => $row->product_id], 'p_name'); ?> <small>
															<i>(<?= $row->product_dec ?>)</i>
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
													<!-- <td class="border-right text-right" style="text-align:center"><?= $row->tax ?>%</td> -->
													<td class="border-right" style="text-align:center"><?= $row->qty ?></td>
													<td class="border-right text-right" style="text-align:center"><?= money_formats($rate) ?></td>
													<!-- <td class="border-right text-right" style="text-align:center"> -->
													<?php
													$totalRate += $rate * $row->qty;
													// if ($row->discounttype == '%') {
													// $amt = $rate * $row->qty;
													// $totalDiscount += $amt * $row->discount / 100;
													// $rate = $rate * (100 - (float)$row->discount) / 100;
													// echo $row->discount . '%';
													// } else {
													// $totalDiscount +=  $row->discount * $row->qty;
													// echo 'Rs' . $row->discount;
													// $rate = $rate - $row->discount;
													// }
													?>
													<!-- </td> -->
													<td class="border-right text-right" style="text-align:center">
														<?php
														// if ($row->discounttypecd == '%') {
														echo $row->purchase_price;

														// } else {
														// 	echo $row->discounttypecd . $row->cd;
														// 	$totalCD += $row->cd * $row->qty;

														// 	$rate = $rate - $row->cd;
														// }
														?>
													</td>
							</td>
							<!-- <td class="text-right" style="text-align:center"><?= money_formats($row->amount) ?></td> -->

							<!-- <td class="border-right text-right" style="text-align:center"><?= money_formats($row->netAmount) ?></td> -->
						</tr>
				<?php
												$counts = $key;
											}
										}
				?>

				<?php
				if (isset($counts) && !empty($counts)) {

					// $count=count($counts);

					$lines = $lines - $counts;
				} else {

					$lines = $lines - $counts;
				}

				?>
					</tbody>
					<tfoot>
						<?php if ($lines > 0) {
							for ($i = 0; $i < $lines; $i++) { ?>
								<tr>
									<td class="border-right blankLine">&nbsp;</td>
									<td class="border-right blankLine">&nbsp;</td>
									<td class="border-right blankLine">&nbsp;</td>
									<td class="border-right blankLine">&nbsp;</td>
									<!-- <td class="border-right blankLine">&nbsp;</td> -->
									<!-- <td class="border-right blankLine">&nbsp;</td> -->
									<!-- <td class="border-right blankLine">&nbsp;</td> -->
									<!-- <td class="border-right blankLine">&nbsp;</td> -->
									<!-- <td class="border-right blankLine">&nbsp;</td> -->
									<td class="blankLine">&nbsp;</td>
								</tr>
						<?php }
						} ?>
						<tr class="border-top">
							<td></td>

							<td>Sub Total</td>
							<!-- <td></td> -->
							<!-- <td></td> -->
							<td><?= $nosItm ?></td>
							<td></td>

							<td><?= $result->taxableAmount ?></td>
							<td></td>



						</tr>
					</tfoot>
				</table>
				</td>
				</tr>

				<!-- <tfoot id="pageFooter"> -->
				<tr style="font-size:12px">
					<td class="">
						<table class="gstTable">
						
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
							$this->db->where('type', 'Purchase');
							$this->db->group_by('tax');
							$tax = $this->db->get()->result();
							// echo '<pre>';
							// print_r($tax);
							// die;
							$tcount = 4;
							if (isset($tax) && !empty($tax)) {
								foreach ($tax as $row) { ?>
									<tr style="font-size:12px">
										<td></td>
										<td></td>

										<td style="text-align:center;">CGST@<?= $row->tax; ?>%</td>
										<td style="text-align:center;"><?= money_formats($row->cgst, 2) ?></td>

									</tr>
									<tr style="font-size:12px">
										<td></td>
										<td></td>


										<td style="text-align:center;">SGST@<?= $row->tax; ?>%</td>
										<td style="text-align:center;"><?= money_formats($row->sgst, 2) ?></td>

									</tr>
									<tr style="font-size:12px">
										<td></td>
										<td></td>


										<td style="text-align:center;">IGST@<?= $row->tax; ?>%</td>
										<td style="text-align:center;"><?= money_formats($row->igst, 2) ?></td>

									</tr>
									<!-- <tr style="font-size:12px">

										<td style="text-align:center;"><?= money_formats($row->price) ?></td>
									</tr>
									<tr style="font-size:12px">

										<td style="text-align:center;"><?= money_formats($row->taxAmount, 2) ?></td>
									</tr> -->

								<?php $tcount--;
								}
								if ($tcount > 0) {
								}
								for ($i = 0; $i < $tcount; $i++) { ?>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
							<?php   }
							} ?>
							<tr class="border-top">
								<td></td>
								<td></td>
								<td>Total</td>
								<td><b><?= money_formats($result->total) ?></b></td>

							</tr>
						</table>
						<!-- <span style="margin-right: 1em;"><span>No of ltems : </span><?= $counts + 1 ?> </span>
						<span><span>Total Qty : </span><?= $nosItm; ?></span> -->
						<!-- <div><span>Remark : </span><?= $result->remark ?></div>
						<div><span>Rs In Words : </span><?= ucwords($result->amountInWords) ?> </div> -->
					</td>

				</tr>


				<!-- </tfoot> -->
				</tbody>

				</table>
				<p style="text-align:center">Thank You</p>
				<div style="font-size:10px;text-align:center;">Generated by <?= $info ? $info->name : '' ?> contact: <?= $info ? $info->mobile : '' ?>, <?= $info ? $info->email : '' ?></div>
			</div>

	<?php
		}
	}
	?>
	<script>
		$(document).ready(function() {
			$('.loader').removeClass('loader');
		});
	</script>
</body>

</html>
